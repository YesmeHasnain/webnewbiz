<?php
/**
 * WebNewBiz AI Assistant — Claude-Powered Content Generator
 *
 * Our competitive edge over 10Web (ChatGPT) — uses Anthropic Claude
 * for higher-quality, more natural content generation.
 * Supports: blog posts, page content, product descriptions, SEO meta,
 * FAQs, and professional emails.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_AIAssistant {

    private static ?self $instance = null;

    /** Claude API endpoint */
    private const API_URL = 'https://api.anthropic.com/v1/messages';

    /** Claude model to use (fast + good quality) */
    private const MODEL = 'claude-sonnet-4-20250514';

    /** Anthropic API version header */
    private const API_VERSION = '2023-06-01';

    /** Max history entries to keep */
    private const MAX_HISTORY = 20;

    /** Content type system prompts */
    private const TYPE_PROMPTS = [
        'blog_post' => 'You are an expert content writer specializing in SEO-optimized blog posts. Write an engaging, well-structured blog post with a compelling introduction, clear subheadings (use ## for H2, ### for H3), actionable insights, and a strong conclusion. Include natural keyword usage without stuffing. The content should be original, informative, and written for a human audience first.',

        'page_content' => 'You are a web content specialist. Write professional website page content that is clear, persuasive, and conversion-oriented. Structure it with appropriate headings, short paragraphs, and compelling calls-to-action. Focus on communicating value to the visitor and building trust. Keep sentences concise and scannable.',

        'product_description' => 'You are an ecommerce copywriter. Write a compelling product description that highlights key features, benefits, and unique selling points. Use sensory language, address customer pain points, and create urgency. Include specifications where relevant. The description should make the reader want to buy immediately.',

        'seo_meta' => 'Generate SEO metadata for a web page. Provide exactly two items:
1. SEO Title: Maximum 60 characters. Should be compelling and include the primary keyword naturally.
2. Meta Description: Maximum 160 characters. Should be a persuasive summary that encourages clicks from search results.

Format your response exactly as:
TITLE: [your title here]
DESCRIPTION: [your description here]',

        'faq' => 'Generate a comprehensive FAQ section with 5-8 questions and answers. Each question should address a real concern that potential customers or visitors would have. Answers should be helpful, concise (2-4 sentences), and build confidence. Format each as:

Q: [Question]
A: [Answer]',

        'email' => 'You are a professional email copywriter. Write a well-crafted email with a clear subject line, engaging opening, professional body, and appropriate closing with call-to-action. The tone should match the specified context. Format as:

SUBJECT: [subject line]

[email body]',
    ];

    /** Max tokens per content length setting */
    private const LENGTH_TOKENS = [
        'short'  => 500,
        'medium' => 1500,
        'long'   => 3000,
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // AJAX handlers
        add_action('wp_ajax_wnb_ai_generate', [$this, 'ajax_ai_generate']);
        add_action('wp_ajax_wnb_ai_save_key', [$this, 'ajax_ai_save_key']);
        add_action('wp_ajax_wnb_ai_seo_generate', [$this, 'ajax_ai_seo_generate']);
        add_action('wp_ajax_wnb_ai_history', [$this, 'ajax_ai_history']);
        add_action('wp_ajax_wnb_ai_chat', [$this, 'ajax_ai_chat']);
        add_action('wp_ajax_wnb_ai_edit', [$this, 'ajax_ai_edit']);
    }

    // ──────────────────────────────────────────────
    //  Core Content Generation
    // ──────────────────────────────────────────────

    /**
     * Generate content using Claude AI.
     *
     * @param array $params {
     *   type:     string  blog_post|page_content|product_description|seo_meta|faq|email
     *   prompt:   string  The user's prompt/instructions
     *   tone:     string  professional|casual|friendly|formal (default: professional)
     *   length:   string  short|medium|long (default: medium)
     *   language: string  Language name or 'auto' (default: auto)
     * }
     * @return string|WP_Error Generated content or error.
     */
    public function generate_content(array $params): string|\WP_Error {
        $type     = sanitize_text_field($params['type'] ?? 'blog_post');
        $prompt   = sanitize_textarea_field($params['prompt'] ?? '');
        $tone     = sanitize_text_field($params['tone'] ?? 'professional');
        $length   = sanitize_text_field($params['length'] ?? 'medium');
        $language = sanitize_text_field($params['language'] ?? 'auto');

        if (empty($prompt)) {
            return new \WP_Error('missing_prompt', 'A prompt is required to generate content.');
        }

        if (!$this->has_api_key()) {
            return new \WP_Error('no_api_key', 'Claude API key is not configured. Go to WebNewBiz > Settings to add your Anthropic API key.');
        }

        // Build system prompt
        $system_prompt = self::TYPE_PROMPTS[$type] ?? self::TYPE_PROMPTS['blog_post'];

        // Add tone instruction
        $tone_instruction = match ($tone) {
            'casual'       => "\n\nUse a casual, conversational tone. Write as if talking to a friend.",
            'friendly'     => "\n\nUse a warm, friendly tone that is approachable and encouraging.",
            'formal'       => "\n\nUse a formal, authoritative tone suitable for professional or academic contexts.",
            'professional' => "\n\nUse a professional tone that is clear, confident, and trustworthy.",
            default        => "\n\nUse a professional tone.",
        };
        $system_prompt .= $tone_instruction;

        // Add language instruction
        if ($language && $language !== 'auto') {
            $system_prompt .= "\n\nWrite the content in {$language}.";
        }

        // Determine max tokens
        $max_tokens = self::LENGTH_TOKENS[$length] ?? self::LENGTH_TOKENS['medium'];

        // Call Claude API
        $result = $this->call_claude_api($system_prompt, $prompt, $max_tokens);

        if (is_wp_error($result)) {
            return $result;
        }

        // Save to history
        $this->add_to_history([
            'type'       => $type,
            'prompt'     => wp_trim_words($prompt, 20, '...'),
            'tone'       => $tone,
            'length'     => $length,
            'chars'      => strlen($result),
            'created_at' => current_time('mysql'),
        ]);

        return $result;
    }

    // ──────────────────────────────────────────────
    //  Claude API Communication
    // ──────────────────────────────────────────────

    /**
     * Call the Claude API and return the generated text.
     *
     * @param string $system_prompt System instructions.
     * @param string $user_prompt   The user's message/prompt.
     * @param int    $max_tokens    Maximum tokens to generate.
     * @return string|WP_Error The generated text, or error.
     */
    public function call_claude_api(string $system_prompt, string $user_prompt, int $max_tokens = 1500): string|\WP_Error {
        $api_key = $this->get_api_key();

        if (empty($api_key)) {
            return new \WP_Error('no_api_key', 'Claude API key is not set.');
        }

        $body = [
            'model'      => self::MODEL,
            'max_tokens' => $max_tokens,
            'system'     => $system_prompt,
            'messages'   => [
                [
                    'role'    => 'user',
                    'content' => $user_prompt,
                ],
            ],
        ];

        $response = wp_remote_post(self::API_URL, [
            'timeout'  => 60,
            'headers'  => [
                'Content-Type'      => 'application/json',
                'x-api-key'         => $api_key,
                'anthropic-version'  => self::API_VERSION,
            ],
            'body'     => wp_json_encode($body),
        ]);

        // Network error
        if (is_wp_error($response)) {
            return new \WP_Error(
                'api_network_error',
                'Failed to connect to Claude API: ' . $response->get_error_message()
            );
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $body_raw    = wp_remote_retrieve_body($response);
        $data        = json_decode($body_raw, true);

        // Handle HTTP error codes
        if ($status_code !== 200) {
            $error_msg = $data['error']['message'] ?? "API returned status {$status_code}";

            switch ($status_code) {
                case 401:
                    return new \WP_Error('invalid_api_key', 'Invalid API key. Please check your Anthropic API key in settings.');

                case 429:
                    return new \WP_Error('rate_limited', 'Rate limit exceeded. Please wait a moment and try again.');

                case 400:
                    return new \WP_Error('bad_request', 'Bad request: ' . $error_msg);

                case 500:
                case 502:
                case 503:
                    return new \WP_Error('api_server_error', 'Claude API is temporarily unavailable. Please try again later.');

                default:
                    return new \WP_Error('api_error', 'Claude API error: ' . $error_msg);
            }
        }

        // Extract the generated text from the response
        if (empty($data['content']) || !is_array($data['content'])) {
            return new \WP_Error('empty_response', 'Claude API returned an empty response.');
        }

        // Claude returns content as an array of blocks; get the first text block
        $text = '';
        foreach ($data['content'] as $block) {
            if (($block['type'] ?? '') === 'text') {
                $text .= $block['text'];
            }
        }

        if (empty($text)) {
            return new \WP_Error('no_text', 'Claude API response contained no text content.');
        }

        // Track usage
        $this->increment_usage();

        return $text;
    }

    // ──────────────────────────────────────────────
    //  API Key Management
    // ──────────────────────────────────────────────

    /**
     * Get the decrypted API key.
     * Checks both encrypted (wnb_ai_api_key) and plain-text (wnb_claude_api_key) options.
     */
    public function get_api_key(): string {
        // First try the encrypted option
        $encrypted = get_option('wnb_ai_api_key', '');
        if (!empty($encrypted)) {
            $key = $this->decrypt($encrypted);
            if ($key) return $key;
        }

        // Fallback: plain-text key set by platform provisioning
        $plain = get_option('wnb_claude_api_key', '');
        if (!empty($plain) && strpos($plain, 'sk-ant-') === 0) {
            return $plain;
        }

        return '';
    }

    /**
     * Save the API key (encrypted).
     */
    public function set_api_key(string $key): bool {
        $key = trim($key);

        if (empty($key)) {
            return delete_option('wnb_ai_api_key');
        }

        // Validate format: Anthropic keys start with "sk-ant-"
        if (strpos($key, 'sk-ant-') !== 0) {
            return false;
        }

        $encrypted = $this->encrypt($key);
        return update_option('wnb_ai_api_key', $encrypted);
    }

    /**
     * Check if an API key is configured.
     */
    public function has_api_key(): bool {
        return !empty($this->get_api_key());
    }

    /**
     * Encrypt a value using WordPress salts.
     */
    private function encrypt(string $value): string {
        $salt = wp_salt('auth');
        $key  = hash('sha256', $salt, true);
        $iv   = substr(hash('sha256', wp_salt('secure_auth'), true), 0, 16);

        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($encrypted);
    }

    /**
     * Decrypt a value using WordPress salts.
     */
    private function decrypt(string $value): string {
        $salt = wp_salt('auth');
        $key  = hash('sha256', $salt, true);
        $iv   = substr(hash('sha256', wp_salt('secure_auth'), true), 0, 16);

        $decoded   = base64_decode($value);
        $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
        return $decrypted ?: '';
    }

    // ──────────────────────────────────────────────
    //  SEO Integration
    // ──────────────────────────────────────────────

    /**
     * Generate SEO meta title and description for a post using AI.
     *
     * @param int $post_id WordPress post ID.
     * @return array|WP_Error {title, description} or error.
     */
    public function generate_seo_for_post(int $post_id): array|\WP_Error {
        $post = get_post($post_id);
        if (!$post) {
            return new \WP_Error('invalid_post', 'Post not found.');
        }

        $title   = $post->post_title;
        $content = wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 200, '...');

        $user_prompt = "Generate SEO metadata for this page:\n\n";
        $user_prompt .= "Page Title: {$title}\n\n";
        if ($content) {
            $user_prompt .= "Page Content Summary:\n{$content}\n\n";
        }
        $user_prompt .= "Website: " . get_bloginfo('name');

        $result = $this->call_claude_api(
            self::TYPE_PROMPTS['seo_meta'],
            $user_prompt,
            300
        );

        if (is_wp_error($result)) {
            return $result;
        }

        // Parse the structured response
        $seo_title = '';
        $seo_desc  = '';

        // Match TITLE: and DESCRIPTION: patterns
        if (preg_match('/TITLE:\s*(.+)/i', $result, $m)) {
            $seo_title = trim($m[1]);
        }
        if (preg_match('/DESCRIPTION:\s*(.+)/i', $result, $m)) {
            $seo_desc = trim($m[1]);
        }

        // Fallback: if parsing fails, use first line as title, rest as description
        if (empty($seo_title)) {
            $lines = array_filter(explode("\n", $result));
            $seo_title = trim(array_shift($lines) ?? '');
            $seo_desc  = trim(implode(' ', $lines));
        }

        // Enforce length limits
        $seo_title = mb_substr($seo_title, 0, 60);
        $seo_desc  = mb_substr($seo_desc, 0, 160);

        // Save to postmeta
        update_post_meta($post_id, 'wnb_seo_title', sanitize_text_field($seo_title));
        update_post_meta($post_id, 'wnb_meta_description', sanitize_textarea_field($seo_desc));

        return [
            'title'       => $seo_title,
            'description' => $seo_desc,
        ];
    }

    /**
     * Generate alt text for an image. (Placeholder — needs Claude Vision API.)
     *
     * @param string $image_url URL of the image.
     * @return string|WP_Error Suggested alt text.
     */
    public function generate_alt_text(string $image_url): string|\WP_Error {
        if (!$this->has_api_key()) {
            return new \WP_Error('no_api_key', 'API key not configured.');
        }

        // For now, generate based on the image filename as a hint
        $filename = basename(wp_parse_url($image_url, PHP_URL_PATH));
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $hint     = str_replace(['-', '_'], ' ', $filename);

        $system = 'You are an accessibility expert. Generate a concise, descriptive alt text (max 125 characters) for an image. The alt text should be helpful for screen readers and describe what the image likely shows based on its context.';
        $prompt = "Generate alt text for an image with filename hint: \"{$hint}\". Keep it under 125 characters.";

        $result = $this->call_claude_api($system, $prompt, 100);

        if (is_wp_error($result)) return $result;

        // Strip any quotes the AI might wrap around it
        return trim($result, '"\'');
    }

    // ──────────────────────────────────────────────
    //  History & Usage
    // ──────────────────────────────────────────────

    /**
     * Get generation history (last N entries).
     */
    public function get_history(): array {
        return get_option('wnb_ai_history', []);
    }

    /**
     * Add an entry to generation history.
     */
    private function add_to_history(array $entry): void {
        $history = $this->get_history();

        // Prepend new entry
        array_unshift($history, $entry);

        // Keep only the last N
        $history = array_slice($history, 0, self::MAX_HISTORY);

        update_option('wnb_ai_history', $history);
    }

    /**
     * Clear all generation history.
     */
    public function clear_history(): bool {
        return update_option('wnb_ai_history', []);
    }

    /**
     * Get usage statistics.
     */
    public function get_usage_stats(): array {
        $history = $this->get_history();
        $total   = (int) get_option('wnb_ai_total_generations', 0);

        // Count this month's generations
        $this_month = 0;
        $month_start = gmdate('Y-m-01 00:00:00');
        foreach ($history as $entry) {
            if (($entry['created_at'] ?? '') >= $month_start) {
                $this_month++;
            }
        }

        return [
            'total_generations' => $total,
            'this_month'        => $this_month,
            'api_key_set'       => $this->has_api_key(),
        ];
    }

    /**
     * Increment the total generation counter.
     */
    private function increment_usage(): void {
        $total = (int) get_option('wnb_ai_total_generations', 0);
        update_option('wnb_ai_total_generations', $total + 1);
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Generate content via AI.
     */
    public function ajax_ai_generate(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $type   = sanitize_text_field($_POST['type'] ?? 'blog_post');
        $prompt = sanitize_textarea_field($_POST['prompt'] ?? '');
        $tone   = sanitize_text_field($_POST['tone'] ?? 'professional');
        $length = sanitize_text_field($_POST['length'] ?? 'medium');

        // Validate type
        $valid_types = array_keys(self::TYPE_PROMPTS);
        if (!in_array($type, $valid_types, true)) {
            wp_send_json_error(['message' => 'Invalid content type. Supported: ' . implode(', ', $valid_types)]);
        }

        // Validate tone
        if (!in_array($tone, ['professional', 'casual', 'friendly', 'formal'], true)) {
            $tone = 'professional';
        }

        // Validate length
        if (!in_array($length, ['short', 'medium', 'long'], true)) {
            $length = 'medium';
        }

        if (empty($prompt)) {
            wp_send_json_error(['message' => 'Please provide a prompt or topic.']);
        }

        $result = $this->generate_content([
            'type'   => $type,
            'prompt' => $prompt,
            'tone'   => $tone,
            'length' => $length,
        ]);

        if (is_wp_error($result)) {
            wp_send_json_error([
                'message' => $result->get_error_message(),
                'code'    => $result->get_error_code(),
            ]);
        }

        wp_send_json_success([
            'content' => $result,
            'type'    => $type,
            'chars'   => strlen($result),
            'words'   => str_word_count($result),
        ]);
    }

    /**
     * AJAX: AI Chatbot — conversational assistant for WP admin / Elementor.
     */
    public function ajax_ai_chat(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $message = sanitize_textarea_field($_POST['message'] ?? '');
        if (empty($message)) {
            wp_send_json_error(['message' => 'Please provide a message.']);
        }

        if (!$this->has_api_key()) {
            wp_send_json_error(['message' => 'Claude API key is not configured. Go to WebNewBiz > Settings to add your API key.']);
        }

        $site_name  = get_bloginfo('name');
        $site_url   = home_url();
        $theme      = wp_get_theme()->get('Name');
        $wp_version = get_bloginfo('version');

        $system_prompt = "You are an AI assistant built into the WebNewBiz Builder WordPress plugin. You help website owners manage and improve their websites.

Website context:
- Site name: {$site_name}
- URL: {$site_url}
- Theme: {$theme}
- WordPress: {$wp_version}

You can help with:
- Writing and improving website content (headings, paragraphs, CTAs)
- SEO optimization suggestions
- Performance improvement tips
- Security recommendations
- General WordPress guidance

Keep responses concise and actionable. Use short paragraphs. When suggesting content changes, provide the exact text the user can copy-paste. If the user asks about changing something in Elementor, guide them step by step.";

        $result = $this->call_claude_api($system_prompt, $message, 1500);

        if (is_wp_error($result)) {
            wp_send_json_error([
                'message' => $result->get_error_message(),
                'code'    => $result->get_error_code(),
            ]);
        }

        // Save to history
        $this->add_to_history([
            'type'       => 'chat',
            'prompt'     => wp_trim_words($message, 20, '...'),
            'tone'       => 'chat',
            'length'     => 'medium',
            'chars'      => strlen($result),
            'created_at' => current_time('mysql'),
        ]);

        wp_send_json_success([
            'content' => $result,
            'chars'   => strlen($result),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Real-Time Elementor Editing via AI
    // ──────────────────────────────────────────────

    /**
     * AJAX: AI Edit — modify Elementor page content in real-time.
     */
    public function ajax_ai_edit(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $page_id     = intval($_POST['page_id'] ?? 0);
        $instruction = sanitize_textarea_field($_POST['instruction'] ?? '');

        if (!$page_id || empty($instruction)) {
            wp_send_json_error(['message' => 'Missing page ID or instruction.']);
        }

        // Get current Elementor data
        $raw = get_post_meta($page_id, '_elementor_data', true);
        if (empty($raw)) {
            wp_send_json_error(['message' => 'Page has no Elementor data.']);
        }

        $elements = json_decode($raw, true);
        if (!is_array($elements)) {
            wp_send_json_error(['message' => 'Invalid Elementor data.']);
        }

        if (!$this->has_api_key()) {
            wp_send_json_error(['message' => 'AI API key not configured.']);
        }

        // Extract editable elements for AI context
        $editables = [];
        $this->extract_editables($elements, $editables);

        if (empty($editables)) {
            wp_send_json_error(['message' => 'No editable elements found on this page.']);
        }

        $context = json_encode($editables, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $system = <<<'SYSPROMPT'
You are an AI that edits website content on Elementor pages. You receive a list of editable elements and a user instruction. Return ONLY a valid JSON array of changes.

Each change object: {"id": "element_id", "field": "field_name", "value": "new_value"}

Widget types and their editable fields:
- heading: "title" (plain text)
- text-editor: "editor" (can contain HTML like <p>, <strong>, <em>)
- button: "text" (button label), "url" (button link)
- image: "url" (image URL), "alt" (alt text)
- icon-list: items are nested, use "title" field per item

Rules:
- ONLY return changes the user explicitly asked for
- Return valid JSON array — no markdown, no explanation, no code fences
- For text changes, preserve any existing HTML tags in text-editor fields
- If the user asks to change a heading, match by current text content
- If the instruction is unclear or impossible, return: []
- You can change multiple elements at once
- Respond with ONLY the JSON array, nothing else
SYSPROMPT;

        $user_prompt = "Current page elements:\n{$context}\n\nUser instruction: {$instruction}";

        $result = $this->call_claude_api($system, $user_prompt, 2000);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }

        // Strip markdown code fences if Claude wraps them
        $cleaned = trim($result);
        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $cleaned);
        $cleaned = preg_replace('/\s*```$/', '', $cleaned);
        $cleaned = trim($cleaned);

        $changes = json_decode($cleaned, true);
        if (!is_array($changes) || empty($changes)) {
            wp_send_json_success([
                'message' => 'No changes needed based on your instruction.',
                'changes' => 0,
            ]);
            return;
        }

        // Apply each change to the Elementor elements tree
        $applied = 0;
        $descriptions = [];
        foreach ($changes as $change) {
            $id    = $change['id'] ?? '';
            $field = $change['field'] ?? '';
            $value = $change['value'] ?? '';
            if (empty($id) || empty($field)) continue;

            if ($this->apply_elementor_change($elements, $id, $field, $value)) {
                $applied++;
                $descriptions[] = "{$field} → " . mb_substr($value, 0, 50) . (mb_strlen($value) > 50 ? '...' : '');
            }
        }

        if ($applied > 0) {
            // Save updated data
            $new_json = wp_slash(wp_json_encode($elements));
            update_post_meta($page_id, '_elementor_data', $new_json);

            // Clear Elementor CSS cache for this page
            delete_post_meta($page_id, '_elementor_css');
            delete_post_meta($page_id, '_elementor_page_assets');

            wp_send_json_success([
                'message'      => "Applied {$applied} change(s). Reloading...",
                'changes'      => $applied,
                'descriptions' => $descriptions,
            ]);
        } else {
            wp_send_json_success([
                'message' => 'Could not find matching elements to change.',
                'changes' => 0,
            ]);
        }
    }

    /**
     * Recursively extract editable elements from Elementor data tree.
     */
    private function extract_editables(array $elements, array &$out, int $depth = 0): void {
        foreach ($elements as $el) {
            $type     = $el['widgetType'] ?? ($el['elType'] ?? '');
            $id       = $el['id'] ?? '';
            $settings = $el['settings'] ?? [];

            switch ($type) {
                case 'heading':
                    $out[] = [
                        'id'   => $id,
                        'type' => 'heading',
                        'text' => $settings['title'] ?? '',
                        'tag'  => $settings['header_size'] ?? 'h2',
                    ];
                    break;

                case 'text-editor':
                    $text = $settings['editor'] ?? '';
                    $plain = wp_strip_all_tags($text);
                    if (mb_strlen($plain) > 0) {
                        $out[] = [
                            'id'   => $id,
                            'type' => 'text-editor',
                            'text' => mb_substr($plain, 0, 200) . (mb_strlen($plain) > 200 ? '...' : ''),
                            'html' => mb_strlen($text) > 300 ? mb_substr($text, 0, 300) . '...' : $text,
                        ];
                    }
                    break;

                case 'button':
                    $out[] = [
                        'id'   => $id,
                        'type' => 'button',
                        'text' => $settings['text'] ?? '',
                        'url'  => $settings['link']['url'] ?? '',
                    ];
                    break;

                case 'image':
                    $out[] = [
                        'id'   => $id,
                        'type' => 'image',
                        'url'  => $settings['image']['url'] ?? '',
                        'alt'  => $settings['image']['alt'] ?? '',
                    ];
                    break;

                case 'icon-list':
                    $items = $settings['icon_list'] ?? [];
                    $texts = array_map(fn($i) => $i['text'] ?? '', $items);
                    if (!empty(array_filter($texts))) {
                        $out[] = [
                            'id'    => $id,
                            'type'  => 'icon-list',
                            'items' => $texts,
                        ];
                    }
                    break;
            }

            // Recurse into children
            if (!empty($el['elements'])) {
                $this->extract_editables($el['elements'], $out, $depth + 1);
            }
        }
    }

    /**
     * Apply a single change to the Elementor elements tree (recursive).
     */
    private function apply_elementor_change(array &$elements, string $targetId, string $field, $value): bool {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $targetId) {
                switch ($field) {
                    case 'title':
                        $el['settings']['title'] = $value;
                        return true;
                    case 'editor':
                        $el['settings']['editor'] = $value;
                        return true;
                    case 'text':
                        // Button text
                        $el['settings']['text'] = $value;
                        return true;
                    case 'url':
                        // Button or image URL
                        if (isset($el['settings']['link'])) {
                            $el['settings']['link']['url'] = $value;
                        } elseif (isset($el['settings']['image'])) {
                            $el['settings']['image']['url'] = $value;
                        }
                        return true;
                    case 'alt':
                        if (isset($el['settings']['image'])) {
                            $el['settings']['image']['alt'] = $value;
                        }
                        return true;
                    default:
                        // Generic settings field
                        $el['settings'][$field] = $value;
                        return true;
                }
            }

            // Recurse
            if (!empty($el['elements'])) {
                if ($this->apply_elementor_change($el['elements'], $targetId, $field, $value)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * AJAX: Save Claude API key.
     */
    public function ajax_ai_save_key(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $key = sanitize_text_field($_POST['api_key'] ?? '');

        if (empty($key)) {
            // Clearing the key
            delete_option('wnb_ai_api_key');
            wp_send_json_success(['message' => 'API key removed.', 'has_key' => false]);
            return;
        }

        // Validate format
        if (strpos($key, 'sk-ant-') !== 0) {
            wp_send_json_error(['message' => 'Invalid API key format. Anthropic API keys start with "sk-ant-".']);
            return;
        }

        // Test the key with a minimal request
        $test_result = $this->test_api_key($key);
        if (is_wp_error($test_result)) {
            wp_send_json_error([
                'message' => 'API key validation failed: ' . $test_result->get_error_message(),
            ]);
            return;
        }

        $saved = $this->set_api_key($key);

        if ($saved) {
            wp_send_json_success([
                'message' => 'API key saved and verified successfully.',
                'has_key' => true,
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to save API key.']);
        }
    }

    /**
     * AJAX: Generate SEO meta for a specific post.
     */
    public function ajax_ai_seo_generate(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $post_id = (int) ($_POST['post_id'] ?? 0);
        if (!$post_id) {
            wp_send_json_error(['message' => 'Post ID is required.']);
        }

        $result = $this->generate_seo_for_post($post_id);

        if (is_wp_error($result)) {
            wp_send_json_error([
                'message' => $result->get_error_message(),
                'code'    => $result->get_error_code(),
            ]);
        }

        wp_send_json_success([
            'message'     => 'SEO metadata generated and saved.',
            'title'       => $result['title'],
            'description' => $result['description'],
            'post_id'     => $post_id,
        ]);
    }

    /**
     * AJAX: Return generation history.
     */
    public function ajax_ai_history(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $action = sanitize_text_field($_POST['history_action'] ?? 'get');

        if ($action === 'clear') {
            $this->clear_history();
            wp_send_json_success([
                'message' => 'Generation history cleared.',
                'history' => [],
                'stats'   => $this->get_usage_stats(),
            ]);
            return;
        }

        wp_send_json_success([
            'history' => $this->get_history(),
            'stats'   => $this->get_usage_stats(),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Private Helpers
    // ──────────────────────────────────────────────

    /**
     * Test an API key by making a minimal Claude request.
     *
     * @return true|WP_Error
     */
    private function test_api_key(string $key): true|\WP_Error {
        $body = [
            'model'      => self::MODEL,
            'max_tokens' => 10,
            'messages'   => [
                [
                    'role'    => 'user',
                    'content' => 'Say "ok".',
                ],
            ],
        ];

        $response = wp_remote_post(self::API_URL, [
            'timeout'  => 15,
            'headers'  => [
                'Content-Type'      => 'application/json',
                'x-api-key'         => $key,
                'anthropic-version'  => self::API_VERSION,
            ],
            'body'     => wp_json_encode($body),
        ]);

        if (is_wp_error($response)) {
            return new \WP_Error('network_error', 'Could not connect to Claude API: ' . $response->get_error_message());
        }

        $status = wp_remote_retrieve_response_code($response);

        if ($status === 401) {
            return new \WP_Error('invalid_key', 'Invalid API key.');
        }

        if ($status === 200) {
            return true;
        }

        $body_data = json_decode(wp_remote_retrieve_body($response), true);
        $msg = $body_data['error']['message'] ?? "Unexpected status: {$status}";

        return new \WP_Error('api_error', $msg);
    }
}
