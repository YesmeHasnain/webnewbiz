<?php
/**
 * Claude CLI Client - Uses structured tool calls for reliable editing
 * Supports: text, styles, images, sections, site settings, pages
 */
class AICopilot_ClaudeAPI
{
    public static function chat(array $messages, string $systemPrompt, array $tools, int $pageId = 0, ?array $selectedElement = null): array
    {
        $actions = [];
        $userMessage = '';
        foreach ($messages as $msg) {
            if (($msg['role'] ?? '') === 'user') {
                $userMessage = is_string($msg['content']) ? $msg['content'] : json_encode($msg['content']);
            }
        }

        // Step 1: Get full page structure with IDs, types, styles
        $pageContext = '';
        if ($pageId) {
            $editables = AICopilot_Executor::execute('get_page_editables', ['page_id' => $pageId]);
            if (!empty($editables['editables'])) {
                $pageContext = json_encode($editables['editables'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
        }

        // Step 2: Build prompt with all available tools
        $prompt = self::buildPrompt($userMessage, $pageContext, $pageId, $selectedElement);

        // Step 3: Call Claude CLI
        $response = self::callCLI($prompt);
        if (!$response['success']) return $response;

        // Step 4: Parse response
        $parsed = self::parseJSON($response['data']);
        if (!$parsed) {
            return ['success' => true, 'reply' => $response['data'], 'actions' => []];
        }

        $reply = $parsed['reply'] ?? 'Changes applied.';

        // Step 5: Execute structured tool calls
        if (!empty($parsed['actions']) && is_array($parsed['actions'])) {
            foreach ($parsed['actions'] as $action) {
                $tool = $action['tool'] ?? '';
                $input = $action['input'] ?? [];
                if (!$tool || !is_array($input)) continue;

                // Auto-inject page_id if missing
                if ($pageId && empty($input['page_id'])) {
                    $input['page_id'] = $pageId;
                }

                $result = AICopilot_Executor::execute($tool, $input);
                $actions[] = ['tool' => $tool, 'input' => $input, 'result' => $result];
            }
        }

        // Legacy support: text replacements via "changes" array
        if (!empty($parsed['changes']) && is_array($parsed['changes']) && $pageId) {
            foreach ($parsed['changes'] as $change) {
                $old = $change['old_text'] ?? '';
                $new = $change['new_text'] ?? '';
                if ($old && $new && $old !== $new) {
                    $result = AICopilot_Executor::execute('edit_text_by_content', [
                        'page_id' => $pageId,
                        'old_text' => $old,
                        'new_text' => $new,
                    ]);
                    $actions[] = ['tool' => 'edit_text', 'input' => ['old' => $old, 'new' => $new], 'result' => $result];
                }
            }
        }

        // Legacy support: site_settings
        if (!empty($parsed['site_settings'])) {
            $result = AICopilot_Executor::execute('update_site_settings', $parsed['site_settings']);
            $actions[] = ['tool' => 'update_site_settings', 'input' => $parsed['site_settings'], 'result' => $result];
        }

        // Legacy support: new_page
        if (!empty($parsed['new_page'])) {
            $result = AICopilot_Executor::execute('create_page', $parsed['new_page']);
            $actions[] = ['tool' => 'create_page', 'input' => $parsed['new_page'], 'result' => $result];
        }

        // Clear cache after all changes
        if (!empty($actions) && $pageId) {
            AICopilot_Executor::execute('clear_cache', ['page_id' => $pageId]);
        }

        return ['success' => true, 'reply' => $reply, 'actions' => $actions];
    }

    private static function buildPrompt(string $userMessage, string $pageContext, int $pageId, ?array $selectedElement = null): string
    {
        $siteName = get_bloginfo('name');
        $siteDesc = get_bloginfo('description');

        // Gather all page titles to understand site structure
        $allPages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => 20]);
        $pageList = [];
        foreach ($allPages as $p) {
            $pageList[] = $p->post_title . ' (ID:' . $p->ID . ')';
        }

        $prompt = "You are an AI website editor for \"{$siteName}\" - \"{$siteDesc}\".\n";
        $prompt .= "Site pages: " . implode(', ', $pageList) . "\n\n";

        // Analyze existing content to understand business type
        $prompt .= "IMPORTANT: Before creating ANY content, analyze the existing website to understand:\n";
        $prompt .= "1. What type of business is this? (restaurant, clinic, agency, shop, etc.)\n";
        $prompt .= "2. What is their brand voice/tone? (casual, professional, luxury, friendly)\n";
        $prompt .= "3. What services/products do they offer?\n";
        $prompt .= "ALL content you generate MUST match this business context. Never generate generic content.\n\n";

        if ($pageContext) {
            $prompt .= "CURRENT PAGE ELEMENTS (page_id={$pageId}):\n{$pageContext}\n\n";
        }

        // If user selected a specific element in the preview
        if ($selectedElement && !empty($selectedElement['id'])) {
            $selType = $selectedElement['widgetType'] ?? $selectedElement['elType'] ?? $selectedElement['type'] ?? 'element';
            $selText = $selectedElement['text'] ?? '';
            $selId = $selectedElement['id'];
            $prompt .= "USER HAS SELECTED THIS ELEMENT: id=\"{$selId}\", type=\"{$selType}\", content=\"{$selText}\"\n";
            $prompt .= "IMPORTANT: Apply changes to THIS specific element (id={$selId}). Use this element_id in your actions.\n\n";
        }

        $prompt .= <<<'TOOLS'
AVAILABLE TOOLS (use these in your "actions" array):

1. edit_element_text - Change text content of a widget
   Input: {"element_id":"id", "field":"title|editor|text", "value":"new text"}
   - title = headings, editor = paragraphs/text-editor widgets, text = buttons

2. edit_element_style - Change styling (color, font, background, spacing)
   Input: {"element_id":"id", "property":"prop_name", "value":"value"}
   Properties (USE EXACT NAMES - they differ per widget type!):
   - title_color: heading widget text color (hex, e.g. "#ff0000")
   - text_color: text-editor/paragraph widget text color (NOT "color", must be "text_color")
   - button_text_color: button widget text color
   - button_background_color: button widget background color
   - background_color: section/container/column background (hex)
   - font_family: font name (e.g. "Poppins", "Montserrat")
   - font_size: e.g. "32px", "1.5rem"
   - font_weight: e.g. "400", "600", "700"
   - text_align: "left", "center", "right"
   - padding: e.g. "20px" or {"top":"20","right":"20","bottom":"40","left":"20","unit":"px"}
   - margin: same format as padding
   - border_radius: e.g. "8px"
   - opacity: e.g. "0.8"

3. edit_element_image - Change an image widget's image
   Input: {"element_id":"id", "image_url":"https://..."}

4. add_section - Add a new section to the page
   Input: {"section_type":"hero|features|testimonials|cta|contact|pricing|faq|custom", "position":0, "content":{"title":"...","subtitle":"...","items":[{"title":"...","text":"..."}]}}
   position: 0=top, -1=bottom (default)

5. remove_section - Remove a section/container from page
   Input: {"element_id":"section_or_container_id"}

6. set_global_colors - Change site-wide Elementor colors
   Input: {"primary":"#hex","secondary":"#hex","text":"#hex","accent":"#hex"}

7. update_site_settings - Change site title/tagline
   Input: {"blogname":"...","blogdescription":"..."}

8. create_page - Create a new WordPress page WITH Elementor sections
   Input: {"title":"Page Title","status":"publish","sections":[{"type":"hero","content":{"title":"...","subtitle":"..."}},{"type":"features","content":{"title":"...","items":[{"title":"...","text":"..."}]}}]}
   IMPORTANT: Always include "sections" array with content so the page has Elementor layout (not empty).
   Generate 4-6 sections with rich content specific to the page topic.
   If no sections provided, a basic hero section will be created automatically.

9. search_images - Search for stock photos (returns options for user to choose)
   Input: {"query":"modern building sunset","count":6}
   Use when user asks to find/change/generate an image. Always use count:6 for more options. The user will pick from results.

10. use_image - Download an image and insert into an element
    Input: {"image_url":"https://...","element_id":"id","alt":"description"}

11. duplicate_widget - Duplicate/copy a widget or section (inserts copy right after original)
    Input: {"element_id":"id_to_duplicate"}

12. generate_page - Generate a full page with multiple sections at once
    Input: {"sections":[{"type":"hero","content":{"title":"...","subtitle":"..."}},{"type":"features","content":{"title":"...","items":[{"title":"...","text":"..."}]}}], "mode":"replace|append"}
    Section types: hero, features, testimonials, cta, contact, pricing, faq, gallery, custom

13. move_section - Move a section up or down on the page (reorder sections)
    Input: {"element_id":"section_id","direction":"up|down"}
    Use when user wants to reorder, swap, move up/down, or rearrange sections on the page.
    To swap two sections: move one up or down until it reaches the desired position.

14. get_menu_items - Get WordPress navigation menu items (header/footer nav links)
    Input: {} (no parameters needed)
    Returns all menus with their items (title, URL, ID). Call this FIRST before editing menu items.

15. edit_menu_item - Edit, add, or remove navigation menu links
    Input: {"action":"edit","old_title":"About-Us","title":"About Me"}
    - For edit: use "old_title" to find the menu item by name (NO need to call get_menu_items first!)
    - For add: {"action":"add","title":"Services","url":"/services/"}
    - For remove: {"action":"remove","old_title":"Gallery"}
    IMPORTANT: Use "old_title" field to identify the menu item - do NOT use item_id, just provide old_title.

16. add_widget - Insert ANY Elementor widget into a section on the page
    Input: {"section_id":"id_or_empty","widget_type":"social-icons","settings":{...},"position":-1}
    Widget types: social-icons, icon-list, video, google_maps, divider, spacer, star-rating, progress, counter, alert, tabs, accordion, toggle, html, image-gallery, shortcode, etc.
    Common settings examples:
    - social-icons: {"social_icon_list":[{"social_icon":{"value":"fab fa-facebook","library":"fa-brands"},"link":{"url":"https://facebook.com"}},{"social_icon":{"value":"fab fa-twitter","library":"fa-brands"},"link":{"url":"https://twitter.com"}},{"social_icon":{"value":"fab fa-instagram","library":"fa-brands"},"link":{"url":"https://instagram.com"}}]}
    - video: {"youtube_url":"https://youtube.com/watch?v=..."}
    - google_maps: {"address":"New York, USA","zoom":{"size":14}}
    - divider: {"color":"#333","weight":{"size":2,"unit":"px"}}
    - counter: {"ending_number":"500","title":"Clients","prefix":"","suffix":"+"}
    - star-rating: {"rating":{"size":5},"title":"Our Rating"}
    - icon-list: {"icon_list":[{"text":"Phone: 123-456","icon":{"value":"fas fa-phone","library":"fa-solid"}},{"text":"Email: info@site.com","icon":{"value":"fas fa-envelope","library":"fa-solid"}}]}
    - alert: {"alert_title":"Notice","alert_description":"Important message","alert_type":"info"}

15. edit_repeater - Edit items inside lists/sliders/tabs/accordions
    Input: {"element_id":"id","field":"slides","index":0,"item_field":"heading","value":"New Heading","action":"edit"}
    Actions: "edit" (change item field), "add" (add new item), "remove" (remove item at index)
    Common repeater fields:
    - Slider: field="slides", item_fields: heading, description, button_text, background_image
    - Tabs: field="tabs", item_fields: tab_title, tab_content
    - Accordion: field="tabs", item_fields: tab_title, tab_content
    - Icon List: field="icon_list", item_fields: text, icon
    - Social Icons: field="social_icon_list", item_fields: social_icon, link
    - Price List: field="price_list", item_fields: title, price, description, image

16. get_products - Get WooCommerce products list (names, prices, stock, descriptions)
    Input: {"count":20,"search":"optional search term"}
    Use when user asks about products or wants to edit product details.

15. edit_product - Edit a WooCommerce product
    Input: {"product_id":123,"name":"New Name","price":"29.99","sale_price":"19.99","description":"<p>HTML</p>","short_description":"Short text","sku":"SKU123","stock_status":"instock","stock_quantity":50}
    Use when user wants to change product name, price, description, stock, or SKU.
    ALWAYS call get_products first to find the product ID before editing.

16. apply_site_theme - SITE-WIDE color theme change (all pages + headers + footers + templates + global colors)
    Input: {"bg_color":"#hex","accent_color":"#hex","text_color":"#hex","font":"FontName"}
    - bg_color: dark background color for sections/containers (e.g. "#0F172A", "#1a1a2e")
    - accent_color: vibrant color for buttons, highlights, links (e.g. "#6366F1", "#DC2626")
    - text_color: light text color for headings and paragraphs (e.g. "#F8FAFC", "#FFFFFF")
    - font: optional font family (e.g. "Inter", "Poppins", "Montserrat"). Omit to keep current fonts.
    USE THIS TOOL when user asks to change website colors, color scheme, theme colors, brand colors, or anything about site-wide appearance.
    DO NOT use edit_element_style for site-wide changes - use apply_site_theme instead.
    Convert named colors to hex: red=#DC2626, blue=#2563EB, green=#16A34A, orange=#F97316, purple=#9333EA, pink=#EC4899, gold=#D4A574, navy=#1E3A5F, teal=#14B8A6, cyan=#06B6D4, yellow=#EAB308, black=#111827, white=#F8FAFC, dark=#0F172A

TOOLS;

        $prompt .= "\n\nUSER REQUEST: \"{$userMessage}\"\n\n";

        $prompt .= <<<'INSTRUCTIONS'
Return ONLY a valid JSON object:
{
  "reply": "Friendly message explaining what you did",
  "actions": [
    {"tool": "tool_name", "input": {parameters}}
  ]
}

RULES:
- Use element_id values from the CURRENT PAGE ELEMENTS list
- For style changes (color, font, background): use edit_element_style, NOT text replacement
- For text changes: use edit_element_text with the element's id and correct field
- For section/container background: use edit_element_style on the section/container id
- Multiple actions in one response are fine
- Return ONLY valid JSON, no markdown fences, no extra text

CONTENT GENERATION:
When user asks to write, rewrite, expand, shorten, or change tone:
- "Write a paragraph about X" → Generate text, use edit_element_text on selected/relevant element (field:"editor", value:"<p>generated HTML</p>")
- "Rewrite this heading" → Read current text from element list, write improved version, use edit_element_text
- "Make this shorter/longer" → Read current text, shorten/expand it, use edit_element_text
- "Change tone to professional/casual/friendly" → Rewrite current text in that tone
- "Add a section about X" → Use add_section with generated title, subtitle, and items
- For editor fields, use proper HTML: <p>, <strong>, <em>, <ul><li> etc.
- Write content that matches the website's existing style and industry
- If SEO keywords are mentioned, naturally incorporate them into the content

CONTEXT TAGS (may appear at end of user message):
- [Tone: professional] → Write all content in that tone
- [Language: Urdu] → Write all content in that language
- [SEO Keywords: luxury, homes] → Naturally include these keywords in generated content
These tags are set by the user via UI dropdowns. Respect them for ALL content generation.

PAGE CREATION & GENERATION:
When user asks to create a page, generate a page, or build a landing page:

STEP 1: Analyze the website first. Look at the site name, existing pages, and current page content to understand:
- Business type (coffee shop, dental clinic, law firm, restaurant, etc.)
- Their actual services/products
- Brand tone (casual, luxury, professional, etc.)

STEP 2: Use create_page (for new page) or generate_page (for current page) with sections array.
Each section: {"type":"hero|features|testimonials|cta|faq|custom", "content":{"title":"...","subtitle":"...","items":[...]}}

STEP 3: Generate 5-7 sections with BUSINESS-SPECIFIC content:
  1. hero: powerful headline about THEIR specific business + 2-3 sentence subtitle
  2. features/services: 3-4 items about THEIR actual services (not generic)
  3. testimonials: 3 realistic customer reviews mentioning specific services/products
  4. pricing: actual service/product names with realistic prices for that industry
  5. faq: 4-5 questions a REAL customer would ask about THIS type of business
  6. cta: compelling call-to-action relevant to their business
  7. contact: realistic business info

RULES for page content:
- NEVER use generic placeholder text
- Content MUST feel like it was written specifically for THIS business
- Match the tone of existing website content
- Every item MUST have both "title" AND "text" fields with 2+ sentences
- Include specific details (prices, service names, locations) that match the industry
- mode: "replace" replaces all content, "append" adds to existing

WIDGET MANAGEMENT:
- "duplicate this widget/element" → use duplicate_widget tool with element_id
- "copy this section" → use duplicate_widget on the section/container id
INSTRUCTIONS;

        return $prompt;
    }

    /**
     * Call Claude via VPS API (no local CLI needed)
     * Falls back to local CLI if VPS is unavailable
     */
    private static function callCLI(string $prompt): array
    {
        // VPS API endpoint
        $apiUrl = 'http://72.61.98.106:8090/ai';
        $secret = 'aic_s3cr3t_k3y_2026';

        @set_time_limit(300);

        $response = wp_remote_post($apiUrl, [
            'timeout' => 180,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'secret' => $secret,
                'prompt' => $prompt,
            ]),
        ]);

        if (is_wp_error($response)) {
            // VPS unreachable - try local CLI as fallback
            return self::callLocalCLI($prompt);
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200 || empty($body['success'])) {
            $error = $body['error'] ?? 'VPS API error (HTTP ' . $code . ')';
            // Try local fallback
            return self::callLocalCLI($prompt);
        }

        return ['success' => true, 'data' => $body['data'] ?? ''];
    }

    /**
     * Fallback: Local Claude CLI (if installed on server/PC)
     */
    private static function callLocalCLI(string $prompt): array
    {
        $claudeBin = self::findClaude();
        if (!$claudeBin) return ['success' => false, 'error' => 'AI service unavailable. Please try again.'];

        $promptFile = tempnam(sys_get_temp_dir(), 'aic_') . '.txt';
        file_put_contents($promptFile, $prompt);

        $cmd = escapeshellarg($claudeBin);
        $cmd .= ' --print --dangerously-skip-permissions --max-turns 1 --model sonnet';

        $descriptors = [0 => ['file', $promptFile, 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open($cmd, $descriptors, $pipes, sys_get_temp_dir());

        if (!is_resource($proc)) {
            @unlink($promptFile);
            return ['success' => false, 'error' => 'Failed to start Claude CLI'];
        }

        stream_set_timeout($pipes[1], 180);
        stream_set_timeout($pipes[2], 180);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);
        @unlink($promptFile);

        if ($exitCode !== 0) {
            return ['success' => false, 'error' => 'Claude error: ' . ($stderr ?: "Exit {$exitCode}")];
        }

        return ['success' => true, 'data' => $stdout];
    }

    private static function parseJSON(string $text): ?array
    {
        $text = trim($text);
        // Strip markdown code fences
        $text = preg_replace('/^```(?:json)?\s*\n?/m', '', $text);
        $text = preg_replace('/\n?```\s*$/m', '', $text);
        $text = trim($text);

        $first = strpos($text, '{');
        $last = strrpos($text, '}');
        if ($first !== false && $last !== false) {
            $json = substr($text, $first, $last - $first + 1);
            $parsed = json_decode($json, true);
            if (is_array($parsed)) return $parsed;
        }
        return null;
    }

    private static function findClaude(): ?string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $appData = getenv('APPDATA') ?: '';
            $paths = [$appData . '\\npm\\claude.cmd', $appData . '\\npm\\claude'];
            foreach ($paths as $p) {
                if (file_exists($p)) return $p;
            }
            $result = trim(shell_exec('where claude 2>nul') ?? '');
            if ($result) return explode("\n", $result)[0];
        } else {
            $home = getenv('HOME') ?: '';
            $paths = [$home . '/.local/bin/claude', '/usr/local/bin/claude', '/usr/bin/claude'];
            foreach ($paths as $p) {
                if (file_exists($p)) return $p;
            }
        }
        return null;
    }
}
