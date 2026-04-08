<?php
/**
 * Chat Handler - AJAX endpoint for AI chat
 */
class AICopilot_Chat
{
    public static function init()
    {
        add_action('wp_ajax_ai_copilot_chat', [self::class, 'handleChat']);
        add_action('wp_ajax_ai_copilot_pages', [self::class, 'getPages']);
        add_action('wp_ajax_ai_copilot_undo', [self::class, 'handleUndo']);
        add_action('wp_ajax_ai_copilot_use_image', [self::class, 'handleUseImage']);
        add_action('wp_ajax_ai_copilot_apply_preset', [self::class, 'handleApplyPreset']);
        add_action('wp_ajax_ai_copilot_get_code', [self::class, 'handleGetCode']);
        add_action('wp_ajax_ai_copilot_move_section', [self::class, 'handleMoveSection']);
    }

    public static function handleChat()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');

        if (!current_user_can('edit_pages')) {
            wp_send_json_error('Permission denied');
        }

        $message = sanitize_textarea_field($_POST['message'] ?? '');
        $history = json_decode(stripslashes($_POST['history'] ?? '[]'), true) ?: [];
        $pageId = intval($_POST['page_id'] ?? 0);
        $selectedElement = json_decode(stripslashes($_POST['selected_element'] ?? ''), true) ?: null;

        if (!$message) {
            wp_send_json_error('Message is required');
        }

        // Intercept undo commands - handle locally without calling Claude
        $msgLower = strtolower(trim($message));
        if (in_array($msgLower, ['undo', 'undo that', 'revert', 'go back', 'ctrl z'])) {
            $undoResult = AICopilot_History::undo($pageId);
            wp_send_json_success([
                'reply' => $undoResult['success']
                    ? 'Undo done! ' . ($undoResult['remaining'] ?? 0) . ' undo step(s) remaining.'
                    : 'Cannot undo: ' . ($undoResult['error'] ?? 'Unknown error'),
                'actions' => $undoResult['success'] ? [['tool' => 'undo', 'input' => [], 'result' => $undoResult]] : [],
                'has_changes' => $undoResult['success'],
            ]);
            return;
        }

        // Build system prompt with site context
        $systemPrompt = self::buildSystemPrompt($pageId);

        // Build messages
        $messages = [];
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'] ?? 'user',
                'content' => $msg['content'] ?? '',
            ];
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        // Get tools
        $tools = AICopilot_Tools::getAll();

        // Call Claude with tool-use
        $result = AICopilot_ClaudeAPI::chat($messages, $systemPrompt, $tools, $pageId, $selectedElement);

        if (!$result['success']) {
            wp_send_json_error($result['error'] ?? 'AI request failed');
        }

        wp_send_json_success([
            'reply' => $result['reply'],
            'actions' => $result['actions'] ?? [],
            'has_changes' => !empty($result['actions']),
        ]);
    }

    public static function handleUndo()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');

        if (!current_user_can('edit_pages')) {
            wp_send_json_error('Permission denied');
        }

        $pageId = intval($_POST['page_id'] ?? 0);
        if (!$pageId) {
            wp_send_json_error('No page selected');
        }

        $result = AICopilot_History::undo($pageId);
        if (!$result['success']) {
            wp_send_json_error($result['error'] ?? 'Cannot undo');
        }

        wp_send_json_success($result);
    }

    public static function handleUseImage()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');

        if (!current_user_can('edit_pages')) {
            wp_send_json_error('Permission denied');
        }

        $imageUrl = esc_url_raw($_POST['image_url'] ?? '');
        $elementId = sanitize_text_field($_POST['element_id'] ?? '');
        $pageId = intval($_POST['page_id'] ?? 0);
        $alt = sanitize_text_field($_POST['alt'] ?? '');

        if (!$imageUrl) {
            wp_send_json_error('No image URL');
        }

        $result = AICopilot_Executor::execute('use_image', [
            'image_url' => $imageUrl,
            'element_id' => $elementId,
            'page_id' => $pageId,
            'alt' => $alt,
        ]);

        if (!$result['success']) {
            wp_send_json_error($result['error'] ?? 'Failed');
        }

        wp_send_json_success($result);
    }

    public static function getPages()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');

        $pages = get_posts([
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        $result = [];
        foreach ($pages as $page) {
            $result[] = [
                'id' => $page->ID,
                'title' => $page->post_title,
                'url' => get_permalink($page->ID),
            ];
        }

        wp_send_json_success($result);
    }

    /**
     * Apply style preset directly - no AI needed, programmatic color/font change
     */
    public static function handleApplyPreset()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');
        if (!current_user_can('edit_pages')) {
            wp_send_json_error('Permission denied');
        }

        $pageId = intval($_POST['page_id'] ?? 0);
        $bgColor = sanitize_hex_color($_POST['bg_color'] ?? '');
        $accentColor = sanitize_hex_color($_POST['accent_color'] ?? '');
        $textColor = sanitize_hex_color($_POST['text_color'] ?? '');
        $font = sanitize_text_field($_POST['font'] ?? '');

        if (!$pageId || !$bgColor) {
            wp_send_json_error('Missing parameters');
        }

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) {
            wp_send_json_error('No Elementor data found');
        }

        $elements = json_decode($data, true);
        if (!$elements) {
            wp_send_json_error('Invalid Elementor data');
        }

        // Save snapshot for undo (current page)
        AICopilot_History::saveSnapshot($pageId);

        $counts = ['sections' => 0, 'headings' => 0, 'texts' => 0, 'buttons' => 0];

        // 1. Apply to ALL published pages (not just current)
        $allPages = get_posts([
            'post_type' => 'page',
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        $pagesUpdated = 0;
        foreach ($allPages as $pid) {
            $pData = get_post_meta($pid, '_elementor_data', true);
            if (!$pData) continue;
            $pElements = json_decode($pData, true);
            if (!$pElements) continue;
            $pElements = self::applyPresetToElements($pElements, $bgColor, $accentColor, $textColor, $font, $counts);
            $pJson = wp_slash(json_encode($pElements));
            update_post_meta($pid, '_elementor_data', $pJson);
            AICopilot_Executor::execute('clear_cache', ['page_id' => $pid]);
            $pagesUpdated++;
        }

        // 2. Apply to ALL Elementor templates (headers, footers, sections, popups, etc.)
        $templateIds = get_posts([
            'post_type' => ['elementor_library', 'elementor-hf'],
            'post_status' => ['publish', 'draft'],
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        $tplCount = 0;
        foreach ($templateIds as $tplId) {
            $tplData = get_post_meta($tplId, '_elementor_data', true);
            if (!$tplData) continue;
            $tplElements = json_decode($tplData, true);
            if (!$tplElements) continue;
            $tplElements = self::applyPresetToElements($tplElements, $bgColor, $accentColor, $textColor, $font, $counts);
            $tplJson = wp_slash(json_encode($tplElements));
            update_post_meta($tplId, '_elementor_data', $tplJson);
            AICopilot_Executor::execute('clear_cache', ['page_id' => $tplId]);
            $tplCount++;
        }

        // 3. Update Elementor Kit global colors
        $kit = get_option('elementor_active_kit');
        if ($kit) {
            $kitSettings = get_post_meta($kit, '_elementor_page_settings', true);
            if (is_array($kitSettings)) {
                // Update system colors
                if (!empty($kitSettings['system_colors'])) {
                    foreach ($kitSettings['system_colors'] as &$sc) {
                        $id = $sc['_id'] ?? '';
                        if ($id === 'primary') $sc['color'] = $accentColor;
                        elseif ($id === 'secondary') $sc['color'] = $bgColor;
                        elseif ($id === 'text') $sc['color'] = $textColor;
                        elseif ($id === 'accent') $sc['color'] = $accentColor;
                    }
                }
                // Update system fonts if available
                if (!empty($kitSettings['system_typography']) && $font) {
                    foreach ($kitSettings['system_typography'] as &$st) {
                        $st['typography_font_family'] = $font;
                    }
                }
                update_post_meta($kit, '_elementor_page_settings', $kitSettings);
                AICopilot_Executor::execute('clear_cache', ['page_id' => $kit]);
            }
        }

        // 4. Clear Elementor global CSS cache
        delete_option('_elementor_global_css');
        delete_option('elementor-custom-breakpoints-files');

        wp_send_json_success([
            'success' => true,
            'message' => "Style preset applied site-wide! {$pagesUpdated} pages + {$tplCount} templates updated. ({$counts['sections']} sections, {$counts['headings']} headings, {$counts['texts']} texts, {$counts['buttons']} buttons)",
            'counts' => $counts,
        ]);
    }

    /**
     * Recursively apply preset colors/fonts to ALL elements - comprehensive
     */
    public static function applyPresetToElements(array $elements, string $bg, string $accent, string $text, string $font, array &$counts): array
    {
        // Color properties to clear from __globals__ so inline values take effect
        $globalColorProps = [
            'title_color', 'text_color', 'color', 'button_text_color',
            'button_background_color', 'background_color', 'primary_color',
            'secondary_color', 'icon_color', 'icon_primary_color',
            'icon_secondary_color', 'border_color', 'link_color',
            'title_text_color', 'description_color', 'content_color',
        ];

        foreach ($elements as &$el) {
            $elType = $el['elType'] ?? '';
            $widgetType = $el['widgetType'] ?? '';

            // Clear ALL global color references on every element
            if (isset($el['settings']['__globals__'])) {
                foreach ($globalColorProps as $prop) {
                    unset($el['settings']['__globals__'][$prop]);
                }
            }

            // Sections/Containers/Columns: change background
            if (in_array($elType, ['section', 'container', 'column'])) {
                $el['settings']['background_background'] = 'classic';
                $el['settings']['background_color'] = $bg;
                $counts['sections']++;
            }

            // Headings
            if ($widgetType === 'heading') {
                $el['settings']['title_color'] = $text;
                if ($font) {
                    $el['settings']['typography_typography'] = 'custom';
                    $el['settings']['typography_font_family'] = $font;
                }
                $counts['headings']++;
            }

            // Text editors
            if ($widgetType === 'text-editor') {
                $el['settings']['text_color'] = $text;
                if ($font) {
                    $el['settings']['typography_typography'] = 'custom';
                    $el['settings']['typography_font_family'] = $font;
                }
                $counts['texts']++;
            }

            // Buttons
            if ($widgetType === 'button') {
                $el['settings']['button_background_color'] = $accent;
                $el['settings']['background_color'] = $accent;
                $el['settings']['button_text_color'] = $text;
                if ($font) {
                    $el['settings']['typography_typography'] = 'custom';
                    $el['settings']['typography_font_family'] = $font;
                }
                $counts['buttons']++;
            }

            // Icon boxes
            if ($widgetType === 'icon-box') {
                $el['settings']['title_color'] = $text;
                $el['settings']['description_color'] = $text;
                $el['settings']['primary_color'] = $accent;
                $el['settings']['icon_color'] = $accent;
                if ($font) {
                    $el['settings']['typography_typography'] = 'custom';
                    $el['settings']['typography_font_family'] = $font;
                }
                $counts['texts']++;
            }

            // Image boxes
            if ($widgetType === 'image-box') {
                $el['settings']['title_color'] = $text;
                $el['settings']['description_color'] = $text;
                if ($font) {
                    $el['settings']['typography_typography'] = 'custom';
                    $el['settings']['typography_font_family'] = $font;
                }
                $counts['texts']++;
            }

            // Nav menus
            if ($widgetType === 'nav-menu' || $widgetType === 'navigation-menu') {
                $el['settings']['text_color'] = $text;
                $el['settings']['color_menu_item'] = $text;
                $el['settings']['color_menu_item_hover'] = $accent;
                $counts['texts']++;
            }

            // Dividers
            if ($widgetType === 'divider') {
                $el['settings']['color'] = $accent;
            }

            // Icon lists
            if ($widgetType === 'icon-list') {
                $el['settings']['icon_color'] = $accent;
                $el['settings']['text_color'] = $text;
                $counts['texts']++;
            }

            // HTML widgets - smart color detection & replacement
            if ($widgetType === 'html' && !empty($el['settings']['html'])) {
                $html = $el['settings']['html'];
                // Find ALL hex colors in the HTML
                if (preg_match_all('/#([0-9a-fA-F]{6})\b/', $html, $matches)) {
                    $foundColors = array_unique($matches[0]);
                    foreach ($foundColors as $oldColor) {
                        // Classify each color: is it accent, background, or text?
                        $role = self::classifyColor($oldColor);
                        if ($role === 'accent') {
                            $html = str_ireplace($oldColor, $accent, $html);
                        } elseif ($role === 'bg-dark') {
                            $html = str_ireplace($oldColor, $bg, $html);
                        } elseif ($role === 'text-light') {
                            $html = str_ireplace($oldColor, $text, $html);
                        }
                        // Neutral grays/blacks/whites are left untouched
                    }
                }
                $el['settings']['html'] = $html;
                $counts['texts']++;
            }

            // Recurse into children
            if (!empty($el['elements'])) {
                $el['elements'] = self::applyPresetToElements($el['elements'], $bg, $accent, $text, $font, $counts);
            }
        }
        return $elements;
    }

    public static function handleMoveSection()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');
        if (!current_user_can('edit_pages')) wp_send_json_error('Permission denied');

        $pageId = intval($_POST['page_id'] ?? 0);
        $elementId = sanitize_text_field($_POST['element_id'] ?? '');
        $direction = sanitize_text_field($_POST['direction'] ?? 'down');

        if (!$pageId || !$elementId) wp_send_json_error('Missing parameters');

        $result = AICopilot_Executor::execute('move_section', [
            'page_id' => $pageId,
            'element_id' => $elementId,
            'direction' => $direction,
        ]);

        if (!$result['success']) wp_send_json_error($result['error'] ?? 'Failed');
        wp_send_json_success($result);
    }

    public static function handleGetCode()
    {
        check_ajax_referer('ai_copilot_nonce', 'nonce');
        if (!current_user_can('edit_pages')) wp_send_json_error('Permission denied');

        $pageId = intval($_POST['page_id'] ?? 0);
        if (!$pageId) wp_send_json_error('No page selected');

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) wp_send_json_error('No Elementor data');

        $elements = json_decode($data, true);
        $pretty = json_encode($elements, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        wp_send_json_success(['code' => $pretty, 'page_id' => $pageId]);
    }

    /**
     * Classify a hex color into a role based on its HSL properties
     * Returns: 'accent' (vibrant/saturated), 'bg-dark' (dark muted), 'text-light' (very light), 'neutral' (skip)
     */
    public static function classifyColor(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2; // Lightness 0-1
        $d = $max - $min;

        // Saturation
        $s = 0;
        if ($d > 0) {
            $s = $d / (1 - abs(2 * $l - 1));
        }
        $s = min($s, 1);

        // Very light (L > 0.85) = text-light color (like #FAFAF9, #F8FAFC)
        if ($l > 0.85) return 'text-light';

        // Very dark + low saturation (L < 0.15, S < 0.3) = near-black, skip (neutral)
        if ($l < 0.15 && $s < 0.3) return 'neutral';

        // Saturated color (S > 0.4) = accent/primary color (like #DC2626, #22C55E, #6366F1)
        if ($s > 0.4) return 'accent';

        // Dark with some saturation (L < 0.3) = dark background
        if ($l < 0.3) return 'bg-dark';

        // Everything else = neutral (grays, muted tones) - don't touch
        return 'neutral';
    }

    private static function buildSystemPrompt(int $pageId): string
    {
        $siteName = get_bloginfo('name');
        $siteUrl = home_url();
        $theme = wp_get_theme()->get('Name');

        $prompt = "You are an AI website editor for \"{$siteName}\" ({$siteUrl}). You help users edit their WordPress + Elementor website through natural language commands.\n\n";
        $prompt .= "Site Info:\n- Theme: {$theme}\n- URL: {$siteUrl}\n";

        // Add page info if selected
        if ($pageId) {
            $page = get_post($pageId);
            if ($page) {
                $prompt .= "- Current Page: \"{$page->post_title}\" (ID: {$pageId})\n";
            }
        }

        // List all pages
        $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => 20]);
        if ($pages) {
            $prompt .= "\nSite Pages:\n";
            foreach ($pages as $p) {
                $prompt .= "- {$p->post_title} (ID: {$p->ID})\n";
            }
        }

        $prompt .= "\nRules:\n";
        $prompt .= "- ALWAYS call get_page_editables first to see what elements exist before editing\n";
        $prompt .= "- Use element IDs from get_page_editables when editing\n";
        $prompt .= "- Be helpful and execute changes immediately when asked\n";
        $prompt .= "- Explain what you did after each action\n";
        $prompt .= "- If the user asks something you can't do with tools, explain what they can do instead\n";

        return $prompt;
    }
}
