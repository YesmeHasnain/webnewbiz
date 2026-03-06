<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Website;
use App\Services\AnthropicService;
use App\Services\ChatActionService;
use App\Services\WordPressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private AnthropicService $anthropicService,
        private ChatActionService $chatActionService,
        private WordPressService $wordpressService,
    ) {}

    public function sendMessage(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate(['message' => 'required|string|max:2000']);

        $userMessage = $request->input('message');
        $dbName = $website->wp_db_name;

        // Load website state
        $pages = $dbName ? $this->wordpressService->getPages($dbName) : [];
        $siteInfo = $dbName ? $this->wordpressService->getSiteInfo($dbName) : [];

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($website, $pages, $siteInfo);

        // Get recent conversation history
        $history = ChatMessage::where('website_id', $website->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

        $messages = [];
        foreach ($history as $msg) {
            $messages[] = ['role' => $msg->role, 'content' => $msg->content];
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        // Send to Claude
        $result = $this->anthropicService->chat($messages, $systemPrompt);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'reply' => 'Sorry, I encountered an error. Please try again.',
            ], 500);
        }

        $responseText = $result['data'];
        $reply = $responseText;
        $actionsTaken = [];

        // Try to parse JSON response (handle AI wrapping JSON in text)
        try {
            $parsed = json_decode($responseText, true);
            if (!is_array($parsed) || !isset($parsed['reply'])) {
                // Try extracting JSON from within the response text
                if (preg_match('/\{[^{}]*"reply"\s*:/s', $responseText)) {
                    $start = strpos($responseText, '{');
                    $end = strrpos($responseText, '}');
                    if ($start !== false && $end !== false && $end > $start) {
                        $jsonStr = substr($responseText, $start, $end - $start + 1);
                        $parsed = json_decode($jsonStr, true);
                    }
                }
            }
            if (is_array($parsed) && isset($parsed['reply'])) {
                $reply = $parsed['reply'];
                if (!empty($parsed['actions']) && is_array($parsed['actions'])) {
                    $actionsTaken = $this->chatActionService->executeActions($website, $parsed['actions']);
                }
            }
        } catch (\Exception $e) {
            // Response wasn't JSON, use raw text as reply
        }

        // Save messages
        ChatMessage::create([
            'website_id' => $website->id,
            'user_id' => auth()->id(),
            'role' => 'user',
            'content' => $userMessage,
        ]);

        ChatMessage::create([
            'website_id' => $website->id,
            'user_id' => auth()->id(),
            'role' => 'assistant',
            'content' => $reply,
            'actions_taken' => !empty($actionsTaken) ? $actionsTaken : null,
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply,
            'actions_taken' => $actionsTaken,
        ]);
    }

    public function history(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $messages = ChatMessage::where('website_id', $website->id)
            ->orderBy('id', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($m) => [
                'role' => $m->role,
                'content' => $m->content,
                'actions_taken' => $m->actions_taken,
                'created_at' => $m->created_at->toISOString(),
            ]);

        return response()->json(['messages' => $messages]);
    }

    private function buildSystemPrompt(Website $website, array $pages, array $siteInfo): string
    {
        $pagesInfo = '';
        foreach ($pages as $page) {
            $pagesInfo .= "- Page ID {$page['ID']}: \"{$page['post_title']}\" (slug: {$page['post_name']})\n";
        }

        return "You are an advanced AI website editor assistant for \"{$website->name}\".
You have FULL control over this website — you can edit ANY element, section, page, style, content, SEO, and more.

Website URL: {$website->url}
Type: {$website->ai_business_type}
Site Title: " . ($siteInfo['blogname'] ?? $website->name) . "
Tagline: " . ($siteInfo['blogdescription'] ?? '') . "

Pages:
{$pagesInfo}

ALWAYS respond with JSON:
{\"reply\": \"Your response\", \"actions\": [{\"type\": \"...\", ...}]}

═══ AVAILABLE ACTIONS (30+) ═══

BASIC CONTENT:
- {\"type\":\"update_site_title\", \"value\":\"New Title\"}
- {\"type\":\"update_tagline\", \"value\":\"New Tagline\"}
- {\"type\":\"update_hero_text\", \"title\":\"...\", \"subtitle\":\"...\", \"cta\":\"Button Text\"}
- {\"type\":\"update_page_content\", \"page_id\":123, \"content\":\"New text\"}
- {\"type\":\"update_logo_text\", \"text\":\"Brand Name\"}

PAGE MANAGEMENT:
- {\"type\":\"add_page\", \"title\":\"Page Title\", \"content\":\"<p>HTML</p>\"}
- {\"type\":\"delete_page\", \"page_id\":123}
- {\"type\":\"duplicate_page\", \"page_id\":123, \"new_title\":\"Copy of Page\"}

SECTION EDITING (per page):
- {\"type\":\"update_section_content\", \"page_id\":123, \"section_index\":0, \"content\":{\"title\":\"...\", \"subtitle\":\"...\", \"button_text\":\"...\", \"items\":[{\"title\":\"...\", \"description\":\"...\"}]}}
- {\"type\":\"add_section\", \"page_id\":123, \"section_type\":\"features|testimonials|pricing|cta|content|gallery|team|faq|stats|process\", \"position\":\"end\", \"content\":{\"title\":\"...\", \"items\":[...]}}
- {\"type\":\"remove_section\", \"page_id\":123, \"section_index\":2}
- {\"type\":\"reorder_sections\", \"page_id\":123, \"order\":[0,2,1,3]}
- {\"type\":\"toggle_section_visibility\", \"page_id\":123, \"section_index\":1, \"visible\":false}

AI CONTENT GENERATION (auto-creates new section):
- {\"type\":\"generate_section_content\", \"page_id\":123, \"section_type\":\"features\", \"instructions\":\"Make it about our unique advantages\"}

DESIGN & STYLING:
- {\"type\":\"change_colors\", \"primary\":\"#hex\", \"secondary\":\"#hex\", \"accent\":\"#hex\"}
- {\"type\":\"change_font\", \"heading_font\":\"Playfair Display\", \"body_font\":\"Inter\"}
- {\"type\":\"change_heading_style\", \"style\":\"uppercase|capitalize|bold|italic|underline\"}
- {\"type\":\"change_background\", \"page_id\":123, \"section_index\":0, \"color\":\"#hex\"} (or gradient:{\"from\":\"#\",\"to\":\"#\"} or image_url)
- {\"type\":\"add_animation\", \"animation\":\"fadeInUp|fadeIn|zoomIn|bounceIn|slideInUp\", \"target\":\"all|sections|widgets\", \"page_id\":123}
- {\"type\":\"inject_custom_css\", \"css\":\"body{color:red}\", \"mode\":\"append|replace\"}
- {\"type\":\"inject_custom_js\", \"js\":\"console.log('hi')\", \"mode\":\"append|replace\"}

BUTTONS:
- {\"type\":\"change_button_text\", \"page_id\":123, \"text\":\"New Text\"}
- {\"type\":\"update_all_buttons\", \"text\":\"Contact Us\", \"color\":\"#hex\", \"url\":\"/contact\"}

IMAGES:
- {\"type\":\"update_image\", \"page_id\":123, \"widget_index\":0, \"url\":\"https://...\"}
- {\"type\":\"replace_all_images\", \"category\":\"restaurant\"} (fetches fresh stock photos)

NAVIGATION & FOOTER:
- {\"type\":\"update_menu\", \"items\":[{\"id\":45, \"title\":\"New Name\"}]}
- {\"type\":\"update_footer\", \"copyright\":\"© 2026 Business\", \"heading\":\"Company Name\"}
- {\"type\":\"add_social_links\", \"links\":{\"facebook\":\"url\", \"instagram\":\"url\", \"twitter\":\"url\"}}

CONTACT & SEO:
- {\"type\":\"update_contact_info\", \"phone\":\"+1-555-0123\", \"email\":\"hi@biz.com\", \"address\":\"123 Main St\", \"hours\":\"Mon-Fri 9-5\"}
- {\"type\":\"update_seo\", \"page_slug\":\"home\", \"meta_title\":\"...\", \"meta_description\":\"...\", \"keywords\":[\"...\"]}

═══ GUIDELINES ═══
- If user asks a question without needing changes, return actions as empty array []
- Be proactive: suggest improvements after making changes
- Chain multiple actions in one response (e.g., change colors + fonts + animations)
- When user says \"make it modern\", apply: clean colors + modern fonts + subtle animations
- When user says \"redesign\", change colors + fonts + button style + animations + backgrounds
- When user says \"add SEO\", generate meta titles/descriptions for ALL pages
- When user says \"improve content\", use generate_section_content to create better content
- Always explain what you changed in the reply
IMPORTANT: Always respond with valid JSON only.";
    }
}
