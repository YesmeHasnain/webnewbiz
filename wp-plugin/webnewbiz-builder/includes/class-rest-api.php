<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class REST_API {

    private AI_Service $ai;

    public function __construct(AI_Service $ai) {
        $this->ai = $ai;
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes(): void {
        $namespace = 'webnewbiz-builder/v1';

        register_rest_route($namespace, '/ai/text-action', [
            'methods'             => 'POST',
            'callback'            => [$this, 'handle_text_action'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'text'        => ['required' => true, 'type' => 'string', 'sanitize_callback' => 'wp_kses_post'],
                'action'      => ['required' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'widget_type' => ['required' => false, 'type' => 'string', 'default' => 'text', 'sanitize_callback' => 'sanitize_text_field'],
                'tone'        => ['required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'language'    => ['required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
            ],
        ]);

        register_rest_route($namespace, '/ai/generate-text', [
            'methods'             => 'POST',
            'callback'            => [$this, 'handle_generate_text'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'prompt'  => ['required' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'],
                'context' => ['required' => false, 'type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field'],
            ],
        ]);

        register_rest_route($namespace, '/ai/chat', [
            'methods'             => 'POST',
            'callback'            => [$this, 'handle_chat'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'message'      => ['required' => true, 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'],
                'page_context' => ['required' => false, 'type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field'],
                'history'      => ['required' => false, 'type' => 'array', 'default' => []],
            ],
        ]);
    }

    public function handle_text_action(\WP_REST_Request $request): \WP_REST_Response {
        $text        = $request->get_param('text');
        $action      = $request->get_param('action');
        $widget_type = $request->get_param('widget_type');

        $allowed_actions = ['rewrite', 'simplify', 'expand', 'shorten', 'fix_grammar', 'change_tone', 'translate'];
        if (!in_array($action, $allowed_actions, true)) {
            return new \WP_REST_Response(['success' => false, 'message' => 'Invalid action. Allowed: ' . implode(', ', $allowed_actions)], 400);
        }

        $extra = [];
        if ($action === 'change_tone') {
            $extra['tone'] = $request->get_param('tone') ?: 'professional';
        }
        if ($action === 'translate') {
            $extra['language'] = $request->get_param('language') ?: 'English';
        }

        $result = $this->ai->text_action($text, $action, $widget_type, $extra);

        if ($result['success']) {
            return new \WP_REST_Response(['success' => true, 'data' => $result['data']], 200);
        }

        return new \WP_REST_Response(['success' => false, 'message' => $result['message'] ?? 'AI generation failed'], 500);
    }

    public function handle_generate_text(\WP_REST_Request $request): \WP_REST_Response {
        $prompt  = $request->get_param('prompt');
        $context = $request->get_param('context');

        $system = 'You are a professional website content writer. Write compelling, well-structured content for websites. Return ONLY the content text, no explanations or markdown formatting unless the content itself requires it.';
        if ($context) {
            $system .= "\n\nContext about the website: {$context}";
        }

        $result = $this->ai->generate($prompt, $system, 2048);

        if ($result['success']) {
            return new \WP_REST_Response(['success' => true, 'data' => $result['data']], 200);
        }

        return new \WP_REST_Response(['success' => false, 'message' => $result['message'] ?? 'AI generation failed'], 500);
    }

    public function handle_chat(\WP_REST_Request $request): \WP_REST_Response {
        $message      = $request->get_param('message');
        $page_context = $request->get_param('page_context');
        $history      = $request->get_param('history') ?: [];

        // Get style settings
        $primary   = get_option('wnb_primary_color', '#2563eb');
        $secondary = get_option('wnb_secondary_color', '#1e40af');
        $accent    = get_option('wnb_accent_color', '#60a5fa');

        $system = <<<SYSTEM
You are an expert Elementor page builder AI assistant. You help users build beautiful website sections by generating valid Elementor JSON.

BRAND COLORS: primary={$primary}, secondary={$secondary}, accent={$accent}

When the user asks to create/add/build a section, you MUST respond with valid JSON in this EXACT format:
```json
{
  "message": "Your friendly response explaining what you created",
  "elementor_data": [<array of Elementor elements>],
  "auto_insert": true
}
```

ELEMENTOR ELEMENT STRUCTURE:
- Every element needs: "id" (7 random hex chars), "elType", "settings", "elements" (array of children)
- Section/Container: {"id":"abc1234","elType":"container","settings":{...},"elements":[...children...]}
- Column: {"id":"abc1234","elType":"column","settings":{"_column_size":50},"elements":[...widgets...]}
- Widget: {"id":"abc1234","elType":"widget","widgetType":"heading","settings":{...},"elements":[]}

WIDGET TYPES & SETTINGS:
- heading: {"title":"Text","header_size":"h2","title_color":"#fff","align":"center","typography_font_size":{"size":36,"unit":"px"}}
- text-editor: {"editor":"<p>HTML content</p>","text_color":"#ccc"}
- button: {"text":"Click Me","link":{"url":"#"},"background_color":"#7c3aed","button_text_color":"#fff","border_radius":{"size":8,"unit":"px"},"align":"center"}
- image: {"image":{"url":"https://picsum.photos/seed/NAME/800/500","id":""},"align":"center"}
- spacer: {"space":{"size":50,"unit":"px"}}
- divider: {"color":"#333","weight":{"size":1,"unit":"px"}}
- icon: {"selected_icon":{"value":"fas fa-star"},"primary_color":"#7c3aed","icon_size":{"size":40},"align":"center"}

CONTAINER SETTINGS:
- flex_direction: "column" or "row"
- flex_align_items: "center", "flex-start", "flex-end", "stretch"
- flex_justify_content: "center", "space-between", "flex-start"
- flex_gap: {"size":20,"unit":"px"}
- padding: {"top":"80","right":"20","bottom":"80","left":"20","unit":"px","isLinked":false}
- background_color: "#0f172a"
- min_height: {"size":500,"unit":"px"}

RULES:
1. Use the brand colors provided above
2. Use picsum.photos for placeholder images with descriptive seeds
3. Always use proper section → container → widget nesting
4. Generate IDs as 7 random hex characters (a-f, 0-9)
5. Make sections visually stunning with proper spacing, colors, and typography
6. If user asks general questions (not building), respond with message only, no elementor_data

CURRENT PAGE CONTEXT:
{$page_context}
SYSTEM;

        // Build messages array for Claude
        $claude_messages = [];
        foreach ($history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $role = $msg['role'] === 'user' ? 'user' : 'assistant';
                $claude_messages[] = ['role' => $role, 'content' => $msg['content']];
            }
        }
        $claude_messages[] = ['role' => 'user', 'content' => $message];

        $result = $this->ai->generate_chat($claude_messages, $system, 8192);

        if (!$result['success']) {
            return new \WP_REST_Response([
                'success' => false,
                'message' => $result['message'] ?? 'AI generation failed',
            ], 500);
        }

        // Parse the response
        $raw = $result['data'];
        $parsed = $this->parse_chat_response($raw);

        return new \WP_REST_Response($parsed, 200);
    }

    /**
     * Parse AI chat response — extract message and optional Elementor JSON.
     */
    private function parse_chat_response(string $raw): array {
        // Try to extract JSON block
        if (preg_match('/```json\s*(.*?)\s*```/s', $raw, $matches)) {
            $json = json_decode($matches[1], true);
            if ($json && isset($json['message'])) {
                return [
                    'success'        => true,
                    'message'        => $json['message'],
                    'elementor_data' => $json['elementor_data'] ?? null,
                    'auto_insert'    => $json['auto_insert'] ?? false,
                ];
            }
        }

        // Try parsing the whole response as JSON
        $json = json_decode($raw, true);
        if ($json && isset($json['message'])) {
            return [
                'success'        => true,
                'message'        => $json['message'],
                'elementor_data' => $json['elementor_data'] ?? null,
                'auto_insert'    => $json['auto_insert'] ?? false,
            ];
        }

        // Plain text response (no Elementor data)
        return [
            'success' => true,
            'message' => $raw,
            'elementor_data' => null,
            'auto_insert' => false,
        ];
    }

    public function check_permission(): bool {
        return current_user_can('edit_posts');
    }
}
