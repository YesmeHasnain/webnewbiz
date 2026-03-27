<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CopilotSession;
use App\Models\CopilotAction;
use App\Models\Website;
use App\Services\AnthropicService;
use App\Services\AiCopilot\ActionExecutor;
use App\Services\AiCopilot\ActionRegistry;
use App\Services\AiCopilot\ContextBuilder;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiCopilotController extends Controller
{
    private AnthropicService $ai;
    private ActionExecutor $executor;
    private ContextBuilder $context;
    private WpBridgeService $bridge;

    public function __construct(
        AnthropicService $ai,
        ActionExecutor $executor,
        ContextBuilder $context,
        WpBridgeService $bridge
    ) {
        $this->ai = $ai;
        $this->executor = $executor;
        $this->context = $context;
        $this->bridge = $bridge;
    }

    /**
     * POST /api/websites/{websiteId}/copilot/chat
     * Main copilot chat — understands intent, executes actions, returns results.
     */
    public function chat(Request $request, $websiteId)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'history' => 'array|max:30',
            'page_id' => 'nullable|integer',
            'session_id' => 'nullable|integer',
        ]);

        // Increase timeout for copilot (Claude + bridge calls can take time)
        set_time_limit(180);

        $website = $request->user()->websites()->findOrFail($websiteId);
        $userMessage = $request->input('message');
        $history = $request->input('history', []);
        $pageId = $request->input('page_id');
        $sessionId = $request->input('session_id');

        // Get or create session
        $session = $this->getOrCreateSession($website, $sessionId);

        // Build context
        $contextData = $this->context->build($website, $pageId);
        $systemPrompt = $this->context->buildSystemPrompt($website, $contextData);

        // Build messages
        $messages = $this->buildMessages($history, $userMessage);

        // Get tools
        $tools = ActionRegistry::getTools();

        try {
            // Call Claude with tools
            $result = $this->ai->chatWithTools($messages, $systemPrompt, $tools, 4096);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'reply' => 'AI service temporarily unavailable. Please try again.',
                    'actions' => [],
                ], 503);
            }

            $content = $result['content'];
            $stopReason = $result['stop_reason'];
            $executedActions = [];
            $textResponse = '';

            // Process response: may contain text + tool_use blocks
            foreach ($content as $block) {
                if ($block['type'] === 'text') {
                    $textResponse .= $block['text'];
                }
            }

            // If Claude wants to use tools
            if ($stopReason === 'tool_use') {
                $toolResults = [];

                foreach ($content as $block) {
                    if ($block['type'] === 'tool_use') {
                        $toolName = $block['name'];
                        $toolInput = $block['input'] ?? [];
                        $toolUseId = $block['id'];

                        // Execute the action
                        $actionResult = $this->executor->execute($website, $toolName, $toolInput);

                        // Store action for undo
                        $this->storeAction($session, $toolName, $toolInput, $actionResult);

                        $executedActions[] = [
                            'tool' => $toolName,
                            'input' => $toolInput,
                            'result' => $actionResult,
                        ];

                        $toolResults[] = [
                            'type' => 'tool_result',
                            'tool_use_id' => $toolUseId,
                            'tool_name' => $toolName,
                            'content' => json_encode($actionResult),
                        ];
                    }
                }

                // Send tool results back to Claude for a summary
                if (!empty($toolResults)) {
                    // Sanitize content: ensure tool_use input is always object (not empty array)
                    $sanitizedContent = array_map(function ($block) {
                        if (($block['type'] ?? '') === 'tool_use') {
                            $block['input'] = !empty($block['input']) ? $block['input'] : (object)[];
                        }
                        return $block;
                    }, $content);

                    $followUpMessages = array_merge($messages, [
                        ['role' => 'assistant', 'content' => $sanitizedContent],
                        ['role' => 'user', 'content' => $toolResults],
                    ]);

                    $summaryResult = $this->ai->chatWithTools($followUpMessages, $systemPrompt, $tools, 2048);

                    if ($summaryResult['success']) {
                        $textResponse = '';
                        foreach ($summaryResult['content'] as $block) {
                            if ($block['type'] === 'text') {
                                $textResponse .= $block['text'];
                            }
                        }

                        // Handle recursive tool use (Claude may want to make more changes)
                        if ($summaryResult['stop_reason'] === 'tool_use') {
                            foreach ($summaryResult['content'] as $block) {
                                if ($block['type'] === 'tool_use') {
                                    $actionResult = $this->executor->execute($website, $block['name'], $block['input'] ?? []);
                                    $this->storeAction($session, $block['name'], $block['input'] ?? [], $actionResult);
                                    $executedActions[] = [
                                        'tool' => $block['name'],
                                        'input' => $block['input'] ?? [],
                                        'result' => $actionResult,
                                    ];
                                }
                            }
                        }
                    }
                }

                // Regenerate CSS if any Elementor changes were made
                $elementorTools = ['edit_element_text', 'edit_element_style', 'edit_element_image', 'add_section', 'remove_section', 'reorder_sections', 'set_global_colors', 'set_global_fonts'];
                $needsCssRegen = collect($executedActions)->pluck('tool')->intersect($elementorTools)->isNotEmpty();

                if ($needsCssRegen && $pageId) {
                    try {
                        $this->bridge->regenerateElementorCss($website, $pageId);
                    } catch (\Exception $e) {
                        Log::warning("CSS regen failed: {$e->getMessage()}");
                    }
                }
            }

            // Determine if actual write changes were made (not just reads)
            $readOnlyTools = ['get_page_editables', 'get_global_colors', 'get_global_fonts', 'get_page_seo', 'list_products', 'get_menus'];
            $writeActions = collect($executedActions)->filter(fn($a) => !in_array($a['tool'], $readOnlyTools));
            $hasRealChanges = $writeActions->isNotEmpty();

            return response()->json([
                'success' => true,
                'reply' => $textResponse,
                'actions' => $executedActions,
                'session_id' => $session->id,
                'has_changes' => $hasRealChanges,
            ]);

        } catch (\Exception $e) {
            Log::error("Copilot error for site {$website->slug}: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'reply' => 'Something went wrong. Please try again.',
                'actions' => [],
            ], 500);
        }
    }

    /**
     * POST /api/websites/{websiteId}/copilot/undo/{actionId}
     * Undo a specific copilot action.
     */
    public function undo(Request $request, $websiteId, $actionId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);

        $action = CopilotAction::where('id', $actionId)
            ->whereHas('session', fn($q) => $q->where('website_id', $website->id))
            ->where('status', 'completed')
            ->firstOrFail();

        $beforeState = $action->before_state;

        if (empty($beforeState)) {
            return response()->json(['success' => false, 'error' => 'No undo data available'], 400);
        }

        try {
            // Restore before state based on action type
            $params = $action->action_params;
            $pageId = $params['page_id'] ?? null;

            if ($pageId && in_array($action->action_type, [
                'edit_element_text', 'edit_element_style', 'edit_element_image',
                'add_section', 'remove_section', 'reorder_sections'
            ])) {
                $this->bridge->updateElementorPageData($website, $pageId, $beforeState);
                $this->bridge->regenerateElementorCss($website, $pageId);
            }

            $action->update(['status' => 'undone']);

            return response()->json([
                'success' => true,
                'message' => 'Action undone successfully',
                'action_id' => $action->id,
            ]);
        } catch (\Exception $e) {
            Log::error("Undo failed for action {$actionId}: {$e->getMessage()}");
            return response()->json(['success' => false, 'error' => 'Undo failed'], 500);
        }
    }

    /**
     * GET /api/websites/{websiteId}/copilot/sessions
     * List copilot sessions.
     */
    public function sessions(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);

        $sessions = CopilotSession::where('website_id', $website->id)
            ->withCount('actions')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['success' => true, 'data' => $sessions]);
    }

    /**
     * GET /api/websites/{websiteId}/copilot/session/{sessionId}
     * Get a session with its actions.
     */
    public function session(Request $request, $websiteId, $sessionId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);

        $session = CopilotSession::where('website_id', $website->id)
            ->where('id', $sessionId)
            ->with('actions')
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $session]);
    }

    /**
     * GET /api/websites/{websiteId}/copilot/suggestions
     * Context-aware suggestions based on current page.
     */
    public function suggestions(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $pageId = $request->input('page_id');

        $suggestions = [
            ['icon' => 'edit', 'text' => 'Edit homepage content', 'prompt' => 'Show me all editable content on the homepage so I can make changes'],
            ['icon' => 'palette', 'text' => 'Change brand colors', 'prompt' => 'Show me the current global colors and help me update them'],
            ['icon' => 'search', 'text' => 'Optimize SEO', 'prompt' => 'Analyze and optimize SEO meta tags for all pages'],
            ['icon' => 'layout', 'text' => 'Add a new section', 'prompt' => 'Add a testimonials section to my homepage'],
            ['icon' => 'type', 'text' => 'Update text content', 'prompt' => 'Help me rewrite the hero section text to be more compelling'],
            ['icon' => 'image', 'text' => 'Change images', 'prompt' => 'Help me update the images on my homepage'],
        ];

        // Add WooCommerce suggestions if active
        $type = strtolower($website->business_type ?? '');
        $isEcom = in_array($type, ['ecommerce', 'clothing', 'fashion', 'retail', 'store', 'shop']);
        if ($isEcom) {
            $suggestions[] = ['icon' => 'shopping-bag', 'text' => 'Add products', 'prompt' => 'Help me add new products to my store'];
            $suggestions[] = ['icon' => 'tag', 'text' => 'Update prices', 'prompt' => 'Show me all products so I can update prices'];
        }

        if ($pageId) {
            array_unshift($suggestions, [
                'icon' => 'eye',
                'text' => 'Analyze this page',
                'prompt' => "Analyze the content on page ID {$pageId} and suggest improvements",
            ]);
        }

        return response()->json([
            'success' => true,
            'suggestions' => array_slice($suggestions, 0, 8),
        ]);
    }

    // ─── Private Helpers ───

    private function getOrCreateSession(Website $website, ?int $sessionId): CopilotSession
    {
        if ($sessionId) {
            $session = CopilotSession::where('id', $sessionId)
                ->where('website_id', $website->id)
                ->first();
            if ($session) {
                $session->touch();
                return $session;
            }
        }

        return CopilotSession::create([
            'website_id' => $website->id,
            'user_id' => $website->user_id,
        ]);
    }

    private function buildMessages(array $history, string $userMessage): array
    {
        $messages = [];
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['content'],
                ];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];
        return $messages;
    }

    private function storeAction(CopilotSession $session, string $toolName, array $input, array $result): void
    {
        try {
            CopilotAction::create([
                'session_id' => $session->id,
                'website_id' => $session->website_id,
                'action_type' => $toolName,
                'action_params' => $input,
                'before_state' => $result['before_data'] ?? null,
                'result' => $result,
                'status' => ($result['success'] ?? false) ? 'completed' : 'failed',
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to store copilot action: {$e->getMessage()}");
        }
    }
}
