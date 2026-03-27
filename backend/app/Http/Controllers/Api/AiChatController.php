<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\AnthropicService;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    private AnthropicService $ai;
    private WpBridgeService $bridge;

    public function __construct(AnthropicService $ai, WpBridgeService $bridge)
    {
        $this->ai = $ai;
        $this->bridge = $bridge;
    }

    /**
     * POST /api/websites/{websiteId}/ai-chat
     * Send a message to the AI assistant with website context
     */
    public function chat(Request $request, $websiteId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'array|max:20',
        ]);

        $website = Website::findOrFail($websiteId);
        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        // Gather website context
        $context = $this->getWebsiteContext($website);

        $systemPrompt = <<<PROMPT
You are an expert AI website assistant for "{$website->name}".
You help the user improve their WordPress + WooCommerce website.

WEBSITE CONTEXT:
- Name: {$website->name}
- Type: {$website->business_type}
- URL: {$website->url}
- Layout: {$website->layout_slug}
- Status: {$website->status}
{$context}

YOUR CAPABILITIES:
1. **Content suggestions** — Write page content, headlines, CTAs, product descriptions
2. **Design advice** — Suggest colors, fonts, layout improvements
3. **SEO optimization** — Write meta titles, descriptions, suggest keywords
4. **Product ideas** — Generate WooCommerce product names, descriptions, prices
5. **Marketing copy** — Email templates, social media posts, ad copy
6. **Technical guidance** — Explain WordPress features, plugin recommendations

RULES:
- Be concise and actionable. Give direct answers, not essays.
- When generating content, format it clearly with headings and bullet points.
- When suggesting code or CSS changes, provide the exact code.
- If user asks to make changes, explain what to do step-by-step.
- Use the website context to personalize every response.
- Respond in the same language the user writes in (Urdu/English/etc).
- Use markdown formatting for readability.
PROMPT;

        // Build messages array for Claude
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

        try {
            $result = $this->ai->chat($messages, $systemPrompt, 2048);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'reply' => $result['data'],
                ]);
            }

            return response()->json([
                'success' => false,
                'reply' => 'AI service temporarily unavailable. Please try again.',
            ], 503);

        } catch (\Exception $e) {
            Log::error("AI Chat error for site {$website->slug}: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'reply' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    /**
     * GET /api/websites/{websiteId}/ai-chat/suggestions
     * Get quick action suggestions based on website type
     */
    public function suggestions($websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $type = strtolower($website->business_type ?? 'business');

        $common = [
            ['icon' => '✍️', 'text' => 'Rewrite my homepage content', 'prompt' => 'Write compelling homepage content for my business. Include a hero headline, subtitle, and 3 key selling points.'],
            ['icon' => '🔍', 'text' => 'Generate SEO meta tags', 'prompt' => 'Generate SEO-optimized meta title and meta description for each page of my website.'],
            ['icon' => '🎨', 'text' => 'Suggest a color scheme', 'prompt' => 'Suggest a professional color scheme for my website with hex codes. Include primary, secondary, accent, background, and text colors.'],
            ['icon' => '📝', 'text' => 'Write About Us page', 'prompt' => 'Write a compelling About Us page for my business. Include our story, mission, values, and a call to action.'],
        ];

        $ecommerce = [
            ['icon' => '🛍️', 'text' => 'Generate product descriptions', 'prompt' => 'Generate 5 product ideas with names, descriptions, and prices that would work well for my store.'],
            ['icon' => '📢', 'text' => 'Write marketing copy', 'prompt' => 'Write 3 promotional banner texts and 2 email marketing subject lines for my store.'],
            ['icon' => '⭐', 'text' => 'Create testimonials', 'prompt' => 'Write 4 realistic customer testimonials for my business with names and ratings.'],
        ];

        $service = [
            ['icon' => '📋', 'text' => 'List my services', 'prompt' => 'Write professional descriptions for 6 services my business could offer, with titles and short descriptions.'],
            ['icon' => '❓', 'text' => 'Generate FAQ section', 'prompt' => 'Write 8 frequently asked questions and answers for my business website.'],
            ['icon' => '⭐', 'text' => 'Create testimonials', 'prompt' => 'Write 4 realistic customer testimonials for my business with names, roles, and ratings.'],
        ];

        // Pick relevant suggestions
        $isEcom = in_array($type, ['ecommerce', 'clothing', 'fashion', 'retail', 'store', 'shop']);
        $suggestions = array_merge($common, $isEcom ? $ecommerce : $service);

        return response()->json([
            'success' => true,
            'suggestions' => array_slice($suggestions, 0, 6),
        ]);
    }

    /**
     * Gather website context for AI
     */
    private function getWebsiteContext(Website $website): string
    {
        $lines = [];

        try {
            $overview = $this->bridge->getOverview($website);
            if (!empty($overview['data'])) {
                $d = $overview['data'];
                $lines[] = "- Theme: " . ($d['theme'] ?? 'unknown');
                $lines[] = "- Pages: " . (isset($d['pages']) ? count($d['pages']) : 'unknown');
                $lines[] = "- Plugins: " . (isset($d['plugins']) ? count($d['plugins']) : 'unknown');
                if (!empty($d['woocommerce'])) {
                    $lines[] = "- WooCommerce: active, " . ($d['woocommerce']['products'] ?? 0) . " products";
                }
            }
        } catch (\Exception $e) {
            // Context is optional, continue without it
        }

        return implode("\n", $lines);
    }
}