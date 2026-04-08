<?php
/**
 * Tool Executor - Executes AI tool calls on WordPress/Elementor
 * All style changes go through parsed JSON (never str_replace on raw JSON)
 */
class AICopilot_Executor
{
    public static function execute(string $tool, array $input): array
    {
        return match ($tool) {
            'edit_element_text'    => self::editElementText($input),
            'edit_element_style'   => self::editElementStyle($input),
            'edit_element_image'   => self::editElementImage($input),
            'edit_text_by_content' => self::editTextByContent($input),
            'get_page_editables'   => self::getPageEditables($input),
            'add_section'          => self::addSection($input),
            'remove_section'       => self::removeSection($input),
            'create_page'          => self::createPage($input),
            'update_page_seo'      => self::updatePageSeo($input),
            'set_global_colors'    => self::setGlobalColors($input),
            'upload_image'         => self::uploadImage($input),
            'search_images'        => self::searchImages($input),
            'use_image'            => self::useImage($input),
            'duplicate_widget'     => self::duplicateWidget($input),
            'generate_page'        => self::generateFullPage($input),
            'move_section'         => self::moveSection($input),
            'apply_site_theme'     => self::applySiteTheme($input),
            'edit_product'         => self::editProduct($input),
            'get_menu_items'       => self::getMenuItems($input),
            'edit_menu_item'       => self::editMenuItem($input),
            'add_widget'           => self::addWidget($input),
            'edit_repeater'        => self::editRepeater($input),
            'get_products'         => self::getProducts($input),
            'update_site_settings' => self::updateSiteSettings($input),
            'clear_cache'          => self::clearCache($input),
            default => ['success' => false, 'error' => "Unknown tool: {$tool}"],
        };
    }

    // ─── Helper: Find element across page + header/footer templates ───

    /**
     * Find which post (page or template) contains an element ID
     * Returns the correct post ID so edits go to the right place
     */
    private static function findPostForElement(int $pageId, string $elementId): int
    {
        // Check current page first
        $data = get_post_meta($pageId, '_elementor_data', true);
        if ($data) {
            $elements = json_decode($data, true);
            if ($elements && self::elementExists($elements, $elementId)) {
                return $pageId;
            }
        }

        // Check header/footer templates
        $templates = get_posts(['post_type' => ['elementor-hf', 'elementor_library'], 'post_status' => 'publish', 'posts_per_page' => 10, 'fields' => 'ids']);
        foreach ($templates as $tplId) {
            $tplData = get_post_meta($tplId, '_elementor_data', true);
            if (!$tplData) continue;
            $tplElements = json_decode($tplData, true);
            if ($tplElements && self::elementExists($tplElements, $elementId)) {
                return $tplId;
            }
        }

        return $pageId; // fallback to original
    }

    private static function elementExists(array $elements, string $id): bool
    {
        foreach ($elements as $el) {
            if (($el['id'] ?? '') === $id) return true;
            if (!empty($el['elements']) && self::elementExists($el['elements'], $id)) return true;
        }
        return false;
    }

    // ─── Text Editing ───

    private static function editElementText(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        // Smart lookup: find correct post (page or header/footer template)
        $pageId = self::findPostForElement($pageId, $elementId);
        $field = $input['field'] ?? 'title';
        $value = $input['value'] ?? '';

        $fieldMap = [
            'button_text' => 'text',
            'btn_text'    => 'text',
            'heading'     => 'title',
            'content'     => 'editor',
            'paragraph'   => 'editor',
            'description' => 'description_text',
            'counter'     => 'ending_number',
            'number'      => 'ending_number',
            'price'       => 'price',
            'period'      => 'period',
            'name'        => 'testimonial_name',
            'job'         => 'testimonial_job',
            'quote'       => 'testimonial_content',
            'alert'       => 'alert_title',
            'badge'       => 'badge_text',
            'ribbon'      => 'ribbon_title',
            'prefix'      => 'prefix',
            'suffix'      => 'suffix',
            'caption'     => 'caption',
        ];
        $field = $fieldMap[$field] ?? $field;

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data found'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid Elementor data'];

        // Track before value for diff
        $oldValue = self::getElementFieldValue($elements, $elementId, $field);

        $elements = self::updateElementField($elements, $elementId, $field, $value);
        self::saveElementorData($pageId, $elements);

        return [
            'success' => true,
            'message' => "Updated {$field} on element {$elementId}",
            'diff' => ['field' => $field, 'before' => substr(strip_tags($oldValue ?? ''), 0, 60), 'after' => substr(strip_tags($value), 0, 60)],
        ];
    }

    /**
     * Safe text replacement - searches through parsed JSON tree, not raw string
     */
    private static function editTextByContent(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $oldText = $input['old_text'] ?? '';
        $newText = $input['new_text'] ?? '';

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data found'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid Elementor data'];

        $found = false;
        $elements = self::replaceTextInElements($elements, $oldText, $newText, $found);

        if (!$found) {
            return ['success' => false, 'error' => "Text '{$oldText}' not found on page"];
        }

        self::saveElementorData($pageId, $elements);
        return ['success' => true, 'message' => "Replaced '{$oldText}' with '{$newText}'"];
    }

    // ─── Style Editing ───

    /**
     * Edit element style - handles Elementor's complex property naming
     */
    private static function editElementStyle(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);
        $property = $input['property'] ?? '';
        $value = $input['value'] ?? '';

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data found'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid Elementor data'];

        // Detect widget type to fix property names per widget
        $widgetType = self::getWidgetType($elements, $elementId);
        $property = self::fixPropertyForWidget($property, $widgetType);

        // Build all Elementor settings needed for this property
        $settings = self::buildStyleSettings($property, $value);

        // Button special handling
        if ($widgetType === 'button' && ($property === 'background_color' || $property === 'button_background_color')) {
            $settings = [
                'background_color' => $value,
                'button_background_color' => $value,
            ];
        }

        // Widget background (heading, text-editor, etc) needs underscore prefix
        if (in_array($widgetType, ['heading', 'text-editor', 'image', 'icon-box', 'image-box']) && $property === 'background_color') {
            $settings = [
                '_background_background' => 'classic',
                '_background_color' => $value,
            ];
        }

        foreach ($settings as $key => $val) {
            $elements = self::updateElementSetting($elements, $elementId, $key, $val);
        }

        // Clear any global color references that would override our change
        $colorProps = ['title_color', 'text_color', 'color', 'button_text_color', 'button_background_color', 'background_color'];
        if (in_array($property, $colorProps)) {
            $elements = self::clearGlobalColorRef($elements, $elementId, $property);
        }

        // Track before value for diff
        $oldValue = self::getElementFieldValue($elements, $elementId, array_key_first($settings));

        self::saveElementorData($pageId, $elements);
        return [
            'success' => true,
            'message' => "Updated style {$property} on element {$elementId}",
            'diff' => ['field' => $property, 'before' => is_string($oldValue) ? $oldValue : '', 'after' => is_string($value) ? $value : ''],
        ];
    }

    /**
     * Map a simple property name to the full Elementor settings needed
     */
    private static function buildStyleSettings(string $property, $value): array
    {
        $settings = [];

        switch ($property) {
            // Background needs type flag + color
            case 'background_color':
                $settings['background_background'] = 'classic';
                $settings['background_color'] = $value;
                break;

            // Font family needs typography enabled
            case 'font_family':
                $settings['typography_typography'] = 'custom';
                $settings['typography_font_family'] = $value;
                break;

            // Font size needs typography enabled + structured value
            case 'font_size':
                $settings['typography_typography'] = 'custom';
                $settings['typography_font_size'] = self::parseSizeValue($value);
                break;

            // Font weight needs typography enabled
            case 'font_weight':
                $settings['typography_typography'] = 'custom';
                $settings['typography_font_weight'] = (string) $value;
                break;

            // Line height
            case 'line_height':
                $settings['typography_typography'] = 'custom';
                $settings['typography_line_height'] = self::parseSizeValue($value);
                break;

            // Letter spacing
            case 'letter_spacing':
                $settings['typography_typography'] = 'custom';
                $settings['typography_letter_spacing'] = self::parseSizeValue($value);
                break;

            // Padding / margin need structured value
            case 'padding':
            case 'margin':
                $settings[$property] = self::parseSpacingValue($value);
                break;

            // Border radius
            case 'border_radius':
                $settings['border_radius'] = self::parseSpacingValue($value);
                break;

            // Button-specific background
            case 'button_background_color':
                $settings['button_background_color'] = $value;
                break;

            // Everything else: direct setting (title_color, color, text_align, opacity, etc.)
            default:
                $settings[$property] = $value;
                break;
        }

        return $settings;
    }

    private static function parseSizeValue($value): array
    {
        if (is_array($value)) return $value;

        $value = trim((string) $value);
        if (preg_match('/^(\d+(?:\.\d+)?)\s*(px|em|rem|%|vw|vh)?$/', $value, $m)) {
            return ['size' => (float) $m[1], 'unit' => $m[2] ?? 'px', 'sizes' => []];
        }

        return ['size' => (float) $value, 'unit' => 'px', 'sizes' => []];
    }

    private static function parseSpacingValue($value): array
    {
        if (is_array($value)) return $value;

        $value = trim((string) $value);
        if (preg_match('/^(\d+)\s*(px|em|rem|%)?$/', $value, $m)) {
            $v = $m[1];
            $unit = $m[2] ?? 'px';
            return ['top' => $v, 'right' => $v, 'bottom' => $v, 'left' => $v, 'unit' => $unit, 'isLinked' => true];
        }

        return ['top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => true];
    }

    // ─── Image Editing ───

    private static function editElementImage(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);
        $imageUrl = $input['image_url'] ?? '';

        if (!$imageUrl) return ['success' => false, 'error' => 'No image URL provided'];

        // Check if it's a local/existing media URL (no need to sideload)
        $attachmentId = 0;
        $finalUrl = $imageUrl;

        if (strpos($imageUrl, home_url()) === 0) {
            // Local URL - find attachment ID
            $attachmentId = attachment_url_to_postid($imageUrl);
        } else {
            // External URL - sideload to media library
            $attachmentId = self::sideloadImage($imageUrl);
            if (!$attachmentId) return ['success' => false, 'error' => 'Failed to download/upload image'];
            $finalUrl = wp_get_attachment_url($attachmentId);
        }

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data found'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid Elementor data'];

        $elements = self::updateElementImage($elements, $elementId, $finalUrl, $attachmentId);
        self::saveElementorData($pageId, $elements);

        return ['success' => true, 'message' => 'Image updated', 'url' => $finalUrl];
    }

    // ─── Page Structure ───

    private static function getPageEditables(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $editables = [];

        // 1. Current page elements
        $data = get_post_meta($pageId, '_elementor_data', true);
        if ($data) {
            $elements = json_decode($data, true);
            if ($elements) {
                $editables[] = ['_section' => 'PAGE', 'page_id' => $pageId];
                self::extractEditables($elements, $editables);
            }
        }

        // 2. Header/Footer templates (elementor-hf post type)
        $hfTemplates = get_posts([
            'post_type' => ['elementor-hf', 'elementor_library'],
            'post_status' => 'publish',
            'posts_per_page' => 10,
        ]);
        foreach ($hfTemplates as $tpl) {
            $tplData = get_post_meta($tpl->ID, '_elementor_data', true);
            if (!$tplData) continue;
            $tplElements = json_decode($tplData, true);
            if (!$tplElements) continue;

            $tplType = get_post_meta($tpl->ID, '_elementor_template_type', true) ?: $tpl->post_title;
            $editables[] = ['_section' => strtoupper($tpl->post_title), 'page_id' => $tpl->ID, 'template_type' => $tplType];
            self::extractEditables($tplElements, $editables);
        }

        if (empty($editables)) return ['success' => false, 'error' => 'No Elementor data'];
        return ['success' => true, 'editables' => $editables];
    }

    private static function addSection(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $type = $input['section_type'] ?? 'custom';
        $position = $input['position'] ?? -1;
        $content = $input['content'] ?? [];

        $data = get_post_meta($pageId, '_elementor_data', true);
        $elements = $data ? json_decode($data, true) : [];
        if (!is_array($elements)) $elements = [];

        // Detect if page uses containers (modern Elementor) or sections (legacy)
        $usesContainers = false;
        foreach ($elements as $el) {
            if (($el['elType'] ?? '') === 'container') {
                $usesContainers = true;
                break;
            }
        }

        // Extract existing page theme (colors, fonts) so new section matches
        $pageTheme = self::extractPageTheme($elements);
        $section = self::buildNewSection($type, $content, $usesContainers, $pageTheme);

        if ($position >= 0 && $position < count($elements)) {
            array_splice($elements, $position, 0, [$section]);
        } else {
            $elements[] = $section;
        }

        self::saveElementorData($pageId, $elements);
        return ['success' => true, 'message' => "Added {$type} section", 'element_id' => $section['id']];
    }

    private static function removeSection(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data found'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid Elementor data'];

        $elements = self::removeElementById($elements, $elementId);
        self::saveElementorData($pageId, $elements);

        return ['success' => true, 'message' => "Removed element {$elementId}"];
    }

    private static function createPage(array $input): array
    {
        $title = $input['title'] ?? 'New Page';
        $content = $input['content'] ?? '';
        $status = $input['status'] ?? 'publish';
        $sections = $input['sections'] ?? [];

        $pageId = wp_insert_post([
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => $status,
            'post_type'    => 'page',
        ]);

        if (is_wp_error($pageId)) {
            return ['success' => false, 'error' => $pageId->get_error_message()];
        }

        // Set Elementor edit mode
        update_post_meta($pageId, '_elementor_edit_mode', 'builder');
        update_post_meta($pageId, '_elementor_template_type', 'wp-page');

        // Extract theme from existing pages so new page matches
        $pageTheme = [];
        $existingPages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => 1, 'exclude' => [$pageId]]);
        if ($existingPages) {
            $existingData = get_post_meta($existingPages[0]->ID, '_elementor_data', true);
            if ($existingData) {
                $existingEls = json_decode($existingData, true);
                if ($existingEls) {
                    $pageTheme = self::extractPageTheme($existingEls);
                }
            }
        }

        // Search for relevant images (ONE API call, reuse across sections)
        $siteName = get_bloginfo('name');
        $imageQuery = $siteName . ' ' . $title;
        $stockImages = AICopilot_ImageGen::search($imageQuery, 6);
        $imageUrls = [];
        foreach ($stockImages as $img) {
            $imageUrls[] = $img['url'] ?? '';
        }

        // If sections provided, build Elementor layout with matching theme + images
        if (!empty($sections)) {
            $elements = [];
            $imgIndex = 0;
            foreach ($sections as $sec) {
                $type = $sec['type'] ?? 'custom';
                $secContent = $sec['content'] ?? $sec;
                // Pass image URL for this section
                $secContent['_image_url'] = $imageUrls[$imgIndex % count($imageUrls)] ?? '';
                $imgIndex++;
                $elements[] = self::buildNewSection($type, $secContent, true, $pageTheme);
            }
            $json = wp_slash(json_encode($elements));
            update_post_meta($pageId, '_elementor_data', $json);
            self::clearElementorCache($pageId);
        } else {
            // Create a basic hero section so page isn't empty
            $heroSection = self::buildNewSection('hero', [
                'title' => $title,
                'subtitle' => 'Welcome to ' . $title,
            ], true, $pageTheme);
            $json = wp_slash(json_encode([$heroSection]));
            update_post_meta($pageId, '_elementor_data', $json);
            self::clearElementorCache($pageId);
        }

        $url = get_permalink($pageId);

        // Auto-add link to navigation (find header HTML widget with nav links)
        $headerPosts = get_posts(['post_type' => ['elementor-hf', 'elementor_library'], 'post_status' => 'publish', 'posts_per_page' => 5, 'fields' => 'ids']);
        foreach ($headerPosts as $hid) {
            $hData = get_post_meta($hid, '_elementor_data', true);
            if (!$hData) continue;
            $hEls = json_decode($hData, true);
            if (!$hEls) continue;
            $updated = false;
            array_walk_recursive($hEls, function(&$val, $key) use ($title, $url, &$updated) {
                if ($key === 'html' && is_string($val) && strpos($val, '</ul>') !== false && strpos($val, 'nav') !== false) {
                    // Add link before closing </ul>
                    $slug = sanitize_title($title);
                    $siteUrl = parse_url(home_url(), PHP_URL_PATH) ?: '';
                    $link = '<li><a href="' . rtrim($siteUrl, '/') . '/' . $slug . '/">' . esc_html($title) . '</a></li>';
                    $val = str_replace('</ul>', $link . '</ul>', $val);
                    $updated = true;
                }
            });
            if ($updated) {
                update_post_meta($hid, '_elementor_data', wp_slash(json_encode($hEls)));
                self::clearElementorCache($hid);
            }
        }

        return [
            'success' => true,
            'page_id' => $pageId,
            'url' => $url,
            'message' => "Created page: {$title}" . (!empty($sections) ? ' with ' . count($sections) . ' sections' : ''),
        ];
    }

    private static function updatePageSeo(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        if ($input['meta_title'] ?? '') update_post_meta($pageId, '_yoast_wpseo_title', $input['meta_title']);
        if ($input['meta_description'] ?? '') update_post_meta($pageId, '_yoast_wpseo_metadesc', $input['meta_description']);
        if ($input['keywords'] ?? '') update_post_meta($pageId, '_yoast_wpseo_focuskw', $input['keywords']);
        return ['success' => true, 'message' => 'SEO updated'];
    }

    private static function setGlobalColors(array $input): array
    {
        $colors = [];
        foreach (['primary', 'secondary', 'text', 'accent'] as $key) {
            if (!empty($input[$key])) $colors[$key] = $input[$key];
        }

        $kit = get_option('elementor_active_kit');
        if ($kit) {
            $kitData = get_post_meta($kit, '_elementor_page_settings', true);
            if (is_array($kitData)) {
                $kitData['system_colors'] = array_map(fn($color, $id) => [
                    '_id' => $id, 'title' => ucfirst($id), 'color' => $color,
                ], $colors, array_keys($colors));
                update_post_meta($kit, '_elementor_page_settings', $kitData);
            }
        }

        return ['success' => true, 'message' => 'Global colors updated', 'colors' => $colors];
    }

    private static function uploadImage(array $input): array
    {
        $url = $input['url'] ?? '';
        $alt = $input['alt'] ?? '';

        $attachmentId = self::sideloadImage($url);
        if (!$attachmentId) return ['success' => false, 'error' => 'Upload failed'];

        if ($alt) update_post_meta($attachmentId, '_wp_attachment_image_alt', $alt);
        return ['success' => true, 'attachment_id' => $attachmentId, 'url' => wp_get_attachment_url($attachmentId)];
    }

    /**
     * Search for stock images - returns options for user to pick from
     */
    /**
     * Duplicate a widget (copies it right after the original)
     */
    private static function duplicateWidget(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid data'];

        $found = false;
        $elements = self::duplicateInTree($elements, $elementId, $found);
        if (!$found) return ['success' => false, 'error' => 'Element not found'];

        self::saveElementorData($pageId, $elements);
        return ['success' => true, 'message' => "Duplicated element {$elementId}"];
    }

    private static function duplicateInTree(array $elements, string $id, bool &$found): array
    {
        $result = [];
        foreach ($elements as $el) {
            $result[] = $el;
            if (($el['id'] ?? '') === $id) {
                $found = true;
                // Deep clone with new IDs
                $clone = self::cloneWithNewIds($el);
                $result[] = $clone;
            }
            if (!$found && !empty($el['elements'])) {
                $lastIdx = count($result) - 1;
                $result[$lastIdx]['elements'] = self::duplicateInTree($el['elements'], $id, $found);
            }
        }
        return $result;
    }

    private static function cloneWithNewIds(array $element): array
    {
        $element['id'] = self::genId();
        if (!empty($element['elements'])) {
            foreach ($element['elements'] as &$child) {
                $child = self::cloneWithNewIds($child);
            }
        }
        return $element;
    }

    /**
     * Generate a full page with multiple sections from AI description
     */
    private static function generateFullPage(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $sections = $input['sections'] ?? [];

        if (empty($sections)) return ['success' => false, 'error' => 'No sections provided'];

        $data = get_post_meta($pageId, '_elementor_data', true);
        $existing = $data ? json_decode($data, true) : [];
        if (!is_array($existing)) $existing = [];

        // Detect container vs section
        $usesContainers = false;
        foreach ($existing as $el) {
            if (($el['elType'] ?? '') === 'container') { $usesContainers = true; break; }
        }

        $newElements = [];
        foreach ($sections as $sec) {
            $type = $sec['type'] ?? 'custom';
            $content = $sec['content'] ?? $sec;
            $newElements[] = self::buildNewSection($type, $content, $usesContainers);
        }

        // Replace or append
        $mode = $input['mode'] ?? 'replace';
        if ($mode === 'replace') {
            $final = $newElements;
        } else {
            $final = array_merge($existing, $newElements);
        }

        self::saveElementorData($pageId, $final);
        return ['success' => true, 'message' => 'Generated ' . count($newElements) . ' sections'];
    }

    private static function searchImages(array $input): array
    {
        $query = $input['query'] ?? '';
        $count = $input['count'] ?? 4;
        if (!$query) return ['success' => false, 'error' => 'No search query'];

        $images = AICopilot_ImageGen::search($query, $count);
        if (empty($images)) {
            return ['success' => false, 'error' => 'No images found'];
        }

        return [
            'success' => true,
            'message' => 'Found ' . count($images) . ' images for "' . $query . '"',
            'images' => $images,
            'needs_selection' => true,
        ];
    }

    /**
     * Download selected image and insert into element
     */
    private static function useImage(array $input): array
    {
        $imageUrl = $input['image_url'] ?? '';
        $elementId = $input['element_id'] ?? '';
        $pageId = $input['page_id'] ?? 0;
        $alt = $input['alt'] ?? '';

        if (!$imageUrl) return ['success' => false, 'error' => 'No image URL'];

        // Download to media library
        $media = AICopilot_ImageGen::downloadToMedia($imageUrl, $alt);
        if (!$media) return ['success' => false, 'error' => 'Failed to download image'];

        // If element specified, insert into it
        if ($elementId && $pageId) {
            $data = get_post_meta($pageId, '_elementor_data', true);
            if ($data) {
                $elements = json_decode($data, true);
                if ($elements) {
                    $elements = self::updateElementImage($elements, $elementId, $media['url'], $media['id']);
                    self::saveElementorData($pageId, $elements);
                }
            }
        }

        return [
            'success' => true,
            'message' => 'Image added to media library' . ($elementId ? ' and inserted into element' : ''),
            'url' => $media['url'],
            'attachment_id' => $media['id'],
        ];
    }

    private static function clearCache(array $input): array
    {
        self::clearElementorCache($input['page_id'] ?? 0);
        return ['success' => true, 'message' => 'Cache cleared'];
    }

    private static function updateSiteSettings(array $input): array
    {
        if ($input['blogname'] ?? '') update_option('blogname', $input['blogname']);
        if ($input['blogdescription'] ?? '') update_option('blogdescription', $input['blogdescription']);
        return ['success' => true, 'message' => 'Site settings updated'];
    }

    // ─── WordPress Nav Menu ───

    private static function getMenuItems(array $input): array
    {
        $menus = wp_get_nav_menus();
        $result = [];
        foreach ($menus as $menu) {
            $items = wp_get_nav_menu_items($menu->term_id);
            $menuItems = [];
            if ($items) {
                foreach ($items as $item) {
                    $menuItems[] = [
                        'id' => $item->ID,
                        'title' => $item->title,
                        'url' => $item->url,
                        'type' => $item->type,
                        'parent' => $item->menu_item_parent,
                    ];
                }
            }
            $result[] = ['menu_name' => $menu->name, 'menu_id' => $menu->term_id, 'items' => $menuItems];
        }
        return ['success' => true, 'menus' => $result];
    }

    private static function editMenuItem(array $input): array
    {
        $itemId = $input['item_id'] ?? 0;
        $action = $input['action'] ?? 'edit';
        $oldTitle = $input['old_title'] ?? $input['find'] ?? $input['search'] ?? '';

        // Smart: if no item_id, find by title/old_title
        if (!$itemId && $oldTitle) {
            $menus = wp_get_nav_menus();
            foreach ($menus as $menu) {
                $items = wp_get_nav_menu_items($menu->term_id);
                if (!$items) continue;
                foreach ($items as $item) {
                    if (strcasecmp(trim($item->title), trim($oldTitle)) === 0 || stripos($item->title, $oldTitle) !== false) {
                        $itemId = $item->ID;
                        break 2;
                    }
                }
            }
        }

        if ($action === 'edit' && $itemId) {
            $updates = [];
            if (isset($input['title'])) $updates['menu-item-title'] = $input['title'];
            if (isset($input['url'])) $updates['menu-item-url'] = $input['url'];

            if (!empty($updates)) {
                wp_update_nav_menu_item(0, $itemId, $updates);
            }
            return ['success' => true, 'message' => 'Menu item updated: ' . ($input['title'] ?? '')];
        }

        if ($action === 'edit' && !$itemId) {
            return ['success' => false, 'error' => 'Menu item not found. Provide old_title to search by name.'];
        }

        if ($action === 'add') {
            $menuId = $input['menu_id'] ?? 0;
            if (!$menuId) {
                $menus = wp_get_nav_menus();
                if ($menus) $menuId = $menus[0]->term_id;
            }
            if (!$menuId) return ['success' => false, 'error' => 'No menu found'];

            $newItemId = wp_update_nav_menu_item($menuId, 0, [
                'menu-item-title' => $input['title'] ?? 'New Link',
                'menu-item-url' => $input['url'] ?? '#',
                'menu-item-status' => 'publish',
                'menu-item-type' => 'custom',
            ]);

            if (is_wp_error($newItemId)) return ['success' => false, 'error' => $newItemId->get_error_message()];
            return ['success' => true, 'message' => 'Menu item added', 'item_id' => $newItemId];
        }

        if ($action === 'remove' && ($itemId || $oldTitle)) {
            if (!$itemId && $oldTitle) {
                // Find by title
                $menus = wp_get_nav_menus();
                foreach ($menus as $menu) {
                    $items = wp_get_nav_menu_items($menu->term_id);
                    if (!$items) continue;
                    foreach ($items as $item) {
                        if (strcasecmp(trim($item->title), trim($oldTitle)) === 0 || stripos($item->title, $oldTitle) !== false) {
                            $itemId = $item->ID;
                            break 2;
                        }
                    }
                }
            }
            if (!$itemId) return ['success' => false, 'error' => 'Menu item not found'];
            wp_delete_post($itemId, true);
            return ['success' => true, 'message' => 'Menu item removed'];
        }

        return ['success' => false, 'error' => 'Invalid action'];
    }

    // ─── Add Widget ───

    /**
     * Insert ANY Elementor widget into a section/container
     * Supports: social-icons, icon-list, video, google_maps, form, divider, spacer, star-rating, progress, counter, etc.
     */
    private static function addWidget(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $sectionId = $input['section_id'] ?? '';
        if ($sectionId) $pageId = self::findPostForElement($pageId, $sectionId);
        $widgetType = $input['widget_type'] ?? '';
        $settings = $input['settings'] ?? [];
        $position = $input['position'] ?? -1; // -1 = end

        if (!$widgetType) return ['success' => false, 'error' => 'No widget type specified'];

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid data'];

        // Build the widget
        $widget = [
            'id' => self::genId(),
            'elType' => 'widget',
            'widgetType' => $widgetType,
            'settings' => $settings,
            'elements' => [],
        ];

        // If section specified, add inside it
        if ($sectionId) {
            $found = false;
            $elements = self::insertWidgetInSection($elements, $sectionId, $widget, $position, $found);
            if (!$found) return ['success' => false, 'error' => 'Section not found: ' . $sectionId];
        } else {
            // Add to last section
            $lastIdx = count($elements) - 1;
            if ($lastIdx >= 0) {
                if (!empty($elements[$lastIdx]['elements'])) {
                    $elements[$lastIdx]['elements'][] = $widget;
                } else {
                    $elements[$lastIdx]['elements'] = [$widget];
                }
            }
        }

        self::saveElementorData($pageId, $elements);
        return ['success' => true, 'message' => "Added {$widgetType} widget", 'widget_id' => $widget['id']];
    }

    private static function insertWidgetInSection(array $elements, string $sectionId, array $widget, int $position, bool &$found): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $sectionId) {
                $found = true;
                if (!isset($el['elements'])) $el['elements'] = [];
                if ($position >= 0 && $position < count($el['elements'])) {
                    array_splice($el['elements'], $position, 0, [$widget]);
                } else {
                    $el['elements'][] = $widget;
                }
                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::insertWidgetInSection($el['elements'], $sectionId, $widget, $position, $found);
                if ($found) return $elements;
            }
        }
        return $elements;
    }

    // ─── Edit Repeater ───

    /**
     * Edit repeater fields (slides, tabs, accordion items, price list, icon list, social icons, etc.)
     */
    private static function editRepeater(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);
        $repeaterField = $input['field'] ?? '';  // e.g. 'slides', 'tabs', 'icon_list', 'social_icon_list'
        $itemIndex = $input['index'] ?? 0;       // which item (0-based)
        $itemField = $input['item_field'] ?? '';  // field inside the item
        $value = $input['value'] ?? '';
        $action = $input['action'] ?? 'edit';    // edit, add, remove

        if (!$elementId || !$repeaterField) return ['success' => false, 'error' => 'Missing element_id or field'];

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid data'];

        AICopilot_History::saveSnapshot($pageId);

        $found = false;
        $elements = self::modifyRepeater($elements, $elementId, $repeaterField, $itemIndex, $itemField, $value, $action, $found);

        if (!$found) return ['success' => false, 'error' => 'Element or repeater field not found'];

        $json = wp_slash(json_encode($elements));
        update_post_meta($pageId, '_elementor_data', $json);
        self::clearElementorCache($pageId);

        return ['success' => true, 'message' => "Repeater {$action}: {$repeaterField}[{$itemIndex}].{$itemField}"];
    }

    private static function modifyRepeater(array $elements, string $id, string $field, int $index, string $itemField, $value, string $action, bool &$found): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $id) {
                if (!isset($el['settings'][$field]) || !is_array($el['settings'][$field])) {
                    // Create repeater if action is 'add'
                    if ($action === 'add') {
                        $el['settings'][$field] = [];
                    } else {
                        return $elements;
                    }
                }

                $found = true;

                if ($action === 'edit' && isset($el['settings'][$field][$index])) {
                    if ($itemField) {
                        $el['settings'][$field][$index][$itemField] = $value;
                    }
                } elseif ($action === 'add') {
                    // Add new item to repeater
                    $newItem = is_array($value) ? $value : [$itemField => $value];
                    $newItem['_id'] = substr(md5(uniqid(mt_rand(), true)), 0, 7);
                    $el['settings'][$field][] = $newItem;
                } elseif ($action === 'remove') {
                    if (isset($el['settings'][$field][$index])) {
                        array_splice($el['settings'][$field], $index, 1);
                    }
                }

                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::modifyRepeater($el['elements'], $id, $field, $index, $itemField, $value, $action, $found);
                if ($found) return $elements;
            }
        }
        return $elements;
    }

    // ─── WooCommerce Products ───

    private static function getProducts(array $input): array
    {
        if (!class_exists('WooCommerce')) return ['success' => false, 'error' => 'WooCommerce not installed'];

        $count = $input['count'] ?? 20;
        $search = $input['search'] ?? '';

        $args = ['post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => $count];
        if ($search) $args['s'] = $search;

        $products = get_posts($args);
        $result = [];
        foreach ($products as $p) {
            $product = wc_get_product($p->ID);
            if (!$product) continue;
            $result[] = [
                'id' => $p->ID,
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'description' => substr(strip_tags($product->get_description()), 0, 150),
                'short_description' => substr(strip_tags($product->get_short_description()), 0, 100),
                'stock_status' => $product->get_stock_status(),
                'sku' => $product->get_sku(),
                'image' => wp_get_attachment_url($product->get_image_id()) ?: '',
            ];
        }

        return ['success' => true, 'products' => $result, 'count' => count($result)];
    }

    private static function editProduct(array $input): array
    {
        if (!class_exists('WooCommerce')) return ['success' => false, 'error' => 'WooCommerce not installed'];

        $productId = $input['product_id'] ?? 0;
        if (!$productId) return ['success' => false, 'error' => 'No product ID'];

        $product = wc_get_product($productId);
        if (!$product) return ['success' => false, 'error' => 'Product not found'];

        $changes = [];

        if (isset($input['name'])) { $product->set_name($input['name']); $changes[] = 'name'; }
        if (isset($input['price'])) { $product->set_regular_price($input['price']); $changes[] = 'price'; }
        if (isset($input['sale_price'])) { $product->set_sale_price($input['sale_price']); $changes[] = 'sale_price'; }
        if (isset($input['description'])) { $product->set_description($input['description']); $changes[] = 'description'; }
        if (isset($input['short_description'])) { $product->set_short_description($input['short_description']); $changes[] = 'short_description'; }
        if (isset($input['sku'])) { $product->set_sku($input['sku']); $changes[] = 'sku'; }
        if (isset($input['stock_status'])) { $product->set_stock_status($input['stock_status']); $changes[] = 'stock_status'; }
        if (isset($input['stock_quantity'])) { $product->set_stock_quantity($input['stock_quantity']); $product->set_manage_stock(true); $changes[] = 'stock'; }

        $product->save();

        return ['success' => true, 'message' => 'Product updated: ' . implode(', ', $changes), 'product_id' => $productId];
    }

    // ─── Move Section ───

    private static function moveSection(array $input): array
    {
        $pageId = $input['page_id'] ?? 0;
        $elementId = $input['element_id'] ?? '';
        $pageId = self::findPostForElement($pageId, $elementId);
        $direction = $input['direction'] ?? 'down'; // up or down

        $data = get_post_meta($pageId, '_elementor_data', true);
        if (!$data) return ['success' => false, 'error' => 'No Elementor data'];

        $elements = json_decode($data, true);
        if (!$elements) return ['success' => false, 'error' => 'Invalid data'];

        // Find the section index at top level
        $index = -1;
        foreach ($elements as $i => $el) {
            if (($el['id'] ?? '') === $elementId) {
                $index = $i;
                break;
            }
        }

        if ($index === -1) return ['success' => false, 'error' => 'Section not found'];

        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;
        if ($newIndex < 0 || $newIndex >= count($elements)) {
            return ['success' => false, 'error' => 'Cannot move ' . $direction . ' - already at ' . ($direction === 'up' ? 'top' : 'bottom')];
        }

        // Swap
        AICopilot_History::saveSnapshot($pageId);
        $temp = $elements[$index];
        $elements[$index] = $elements[$newIndex];
        $elements[$newIndex] = $temp;

        $json = wp_slash(json_encode($elements));
        update_post_meta($pageId, '_elementor_data', $json);
        self::clearElementorCache($pageId);

        return ['success' => true, 'message' => "Moved section {$direction}"];
    }

    // ─── Site-Wide Theme ───

    /**
     * Apply a complete theme (colors + font) across ALL pages, templates, and global settings
     * Called by AI when user asks to change website colors/theme in any language
     */
    private static function applySiteTheme(array $input): array
    {
        $bgColor = $input['bg_color'] ?? '#0F172A';
        $accentColor = $input['accent_color'] ?? '#6366F1';
        $textColor = $input['text_color'] ?? '#F8FAFC';
        $font = $input['font'] ?? '';
        $pageId = $input['page_id'] ?? 0;

        if ($pageId) AICopilot_History::saveSnapshot($pageId);

        $counts = ['sections' => 0, 'headings' => 0, 'texts' => 0, 'buttons' => 0];

        // 1. Apply to ALL pages
        $allPages = get_posts(['post_type' => 'page', 'post_status' => ['publish', 'draft'], 'posts_per_page' => -1, 'fields' => 'ids']);
        $pagesUpdated = 0;
        foreach ($allPages as $pid) {
            $pData = get_post_meta($pid, '_elementor_data', true);
            if (!$pData) continue;
            $pElements = json_decode($pData, true);
            if (!$pElements) continue;
            $pElements = AICopilot_Chat::applyPresetToElements($pElements, $bgColor, $accentColor, $textColor, $font, $counts);
            update_post_meta($pid, '_elementor_data', wp_slash(json_encode($pElements)));
            self::clearElementorCache($pid);
            $pagesUpdated++;
        }

        // 2. Apply to ALL Elementor templates (headers, footers, etc.)
        $templateIds = get_posts(['post_type' => ['elementor_library', 'elementor-hf'], 'post_status' => ['publish', 'draft'], 'posts_per_page' => -1, 'fields' => 'ids']);
        $tplCount = 0;
        foreach ($templateIds as $tplId) {
            $tplData = get_post_meta($tplId, '_elementor_data', true);
            if (!$tplData) continue;
            $tplElements = json_decode($tplData, true);
            if (!$tplElements) continue;
            $tplElements = AICopilot_Chat::applyPresetToElements($tplElements, $bgColor, $accentColor, $textColor, $font, $counts);
            update_post_meta($tplId, '_elementor_data', wp_slash(json_encode($tplElements)));
            self::clearElementorCache($tplId);
            $tplCount++;
        }

        // 3. Update Elementor Kit global colors
        $kit = get_option('elementor_active_kit');
        if ($kit) {
            $kitSettings = get_post_meta($kit, '_elementor_page_settings', true);
            if (is_array($kitSettings)) {
                if (!empty($kitSettings['system_colors'])) {
                    foreach ($kitSettings['system_colors'] as &$sc) {
                        $id = $sc['_id'] ?? '';
                        if ($id === 'primary') $sc['color'] = $accentColor;
                        elseif ($id === 'secondary') $sc['color'] = $bgColor;
                        elseif ($id === 'text') $sc['color'] = $textColor;
                        elseif ($id === 'accent') $sc['color'] = $accentColor;
                    }
                }
                if (!empty($kitSettings['system_typography']) && $font) {
                    foreach ($kitSettings['system_typography'] as &$st) {
                        $st['typography_font_family'] = $font;
                    }
                }
                update_post_meta($kit, '_elementor_page_settings', $kitSettings);
                self::clearElementorCache($kit);
            }
        }
        delete_option('_elementor_global_css');

        return [
            'success' => true,
            'message' => "Site-wide theme applied! {$pagesUpdated} pages + {$tplCount} templates updated. ({$counts['sections']} sections, {$counts['headings']} headings, {$counts['texts']} texts, {$counts['buttons']} buttons)",
        ];
    }

    // ─── Element Tree Helpers ───

    private static function updateElementField(array $elements, string $id, string $field, $value): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $id) {
                $el['settings'][$field] = $value;
                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::updateElementField($el['elements'], $id, $field, $value);
            }
        }
        return $elements;
    }

    private static function updateElementSetting(array $elements, string $id, string $prop, $value): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $id) {
                $el['settings'][$prop] = $value;
                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::updateElementSetting($el['elements'], $id, $prop, $value);
            }
        }
        return $elements;
    }

    /**
     * Clear Elementor global color reference so our custom value takes effect
     * Elementor uses __globals__ to link to kit colors - these override inline values
     */
    private static function clearGlobalColorRef(array $elements, string $id, string $prop): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $id) {
                if (isset($el['settings']['__globals__'][$prop])) {
                    unset($el['settings']['__globals__'][$prop]);
                }
                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::clearGlobalColorRef($el['elements'], $id, $prop);
            }
        }
        return $elements;
    }

    private static function updateElementImage(array $elements, string $id, string $url, int $attachId): array
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $id) {
                $widgetType = $el['widgetType'] ?? '';
                if ($widgetType === 'image' || $widgetType === 'image-box') {
                    $el['settings']['image'] = ['url' => $url, 'id' => $attachId, 'alt' => '', 'source' => 'library'];
                } elseif ($widgetType === 'html' || $widgetType === '') {
                    // Section/container background image
                    $el['settings']['background_background'] = 'classic';
                    $el['settings']['background_image'] = ['url' => $url, 'id' => $attachId];
                } else {
                    $el['settings']['image'] = ['url' => $url, 'id' => $attachId, 'alt' => '', 'source' => 'library'];
                }
                return $elements;
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::updateElementImage($el['elements'], $id, $url, $attachId);
            }
        }
        return $elements;
    }

    /**
     * Safe text replacement through parsed JSON tree (NOT raw string)
     * Only replaces in known text fields: title, editor, text, description, html
     */
    /**
     * UNIVERSAL text replacement - searches ALL string settings in ALL widgets
     */
    private static function replaceTextInElements(array $elements, string $old, string $new, bool &$found): array
    {
        foreach ($elements as &$el) {
            if (isset($el['settings']) && is_array($el['settings'])) {
                foreach ($el['settings'] as $key => &$val) {
                    // Skip internal/private keys
                    if (str_starts_with($key, '_') || str_starts_with($key, '__')) continue;

                    // Direct string replacement
                    if (is_string($val) && strpos($val, $old) !== false) {
                        $val = str_replace($old, $new, $val);
                        $found = true;
                    }

                    // Repeater arrays (slides, tabs, etc.)
                    if (is_array($val)) {
                        foreach ($val as &$item) {
                            if (is_array($item)) {
                                foreach ($item as $ik => &$iv) {
                                    if (is_string($iv) && strpos($iv, $old) !== false) {
                                        $iv = str_replace($old, $new, $iv);
                                        $found = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::replaceTextInElements($el['elements'], $old, $new, $found);
            }
        }
        return $elements;
    }

    /**
     * Remove element by ID - fixed to properly modify nested elements
     */
    private static function removeElementById(array $elements, string $id): array
    {
        $result = [];
        foreach ($elements as $el) {
            if (($el['id'] ?? '') === $id) {
                continue; // skip = remove
            }
            if (!empty($el['elements'])) {
                $el['elements'] = self::removeElementById($el['elements'], $id);
            }
            $result[] = $el;
        }
        return $result;
    }

    /**
     * UNIVERSAL: Extract ALL editable elements with IDs, types, and all text/settings
     * Works with ANY Elementor widget - no hardcoded widget list
     */
    private static function extractEditables(array $elements, array &$result, int $depth = 0): void
    {
        // Text-like setting keys to extract from ANY widget
        $textKeys = [
            'title', 'editor', 'text', 'description', 'html', 'content',
            'title_text', 'description_text', 'button_text', 'inner_text',
            'heading', 'sub_heading', 'subtitle', 'caption', 'prefix', 'suffix',
            'before_text', 'after_text', 'highlighted_text', 'rotating_text',
            'alert_title', 'alert_description', 'tab_title', 'tab_content',
            'testimonial_content', 'testimonial_name', 'testimonial_job',
            'price', 'period', 'ending_number', 'starting_number',
            'author_name', 'author_bio', 'blockquote_content',
            'ribbon_title', 'badge_text', 'fallback_text',
            'link_text', 'item_description', 'name', 'address',
        ];

        foreach ($elements as $el) {
            $elType = $el['elType'] ?? '';
            $widgetType = $el['widgetType'] ?? '';
            $id = $el['id'] ?? '';
            $settings = $el['settings'] ?? [];

            // Include sections/containers (for background styling & removal & reorder)
            if (in_array($elType, ['section', 'container'])) {
                $entry = ['id' => $id, 'type' => $elType, 'is_structure' => true];
                if (!empty($settings['background_color'])) {
                    $entry['background_color'] = $settings['background_color'];
                }
                $childCount = self::countWidgets($el['elements'] ?? []);
                if ($childCount > 0) $entry['widgets_inside'] = $childCount;
                $label = self::getFirstText($el['elements'] ?? []);
                if ($label) $entry['label'] = $label;
                $result[] = $entry;
            }

            // Include ALL widgets (universal - not limited to specific types)
            if ($widgetType) {
                $entry = ['id' => $id, 'type' => $widgetType];

                // Extract ALL text fields from this widget
                foreach ($textKeys as $key) {
                    if (isset($settings[$key]) && is_string($settings[$key]) && $settings[$key] !== '') {
                        $val = strip_tags($settings[$key]);
                        if (strlen($val) > 0) {
                            $entry[$key] = substr($val, 0, 150);
                        }
                    }
                }

                // Extract image if present
                if (!empty($settings['image']['url'])) {
                    $entry['image_url'] = $settings['image']['url'];
                }

                // Extract colors
                foreach (['title_color', 'text_color', 'button_text_color', 'button_background_color', 'color', 'primary_color'] as $colorKey) {
                    if (!empty($settings[$colorKey])) {
                        $entry[$colorKey] = $settings[$colorKey];
                    }
                }

                // Extract font
                if (!empty($settings['typography_font_family'])) {
                    $entry['font_family'] = $settings['typography_font_family'];
                }

                // Extract repeater items (slides, tabs, accordion items, price list, etc.)
                foreach ($settings as $key => $val) {
                    if (is_array($val) && !empty($val) && isset($val[0]) && is_array($val[0])) {
                        // This is a repeater field - extract text from each item
                        $items = [];
                        foreach (array_slice($val, 0, 10) as $i => $item) {
                            $itemTexts = [];
                            foreach ($item as $ik => $iv) {
                                if (is_string($iv) && strlen($iv) > 0 && strlen($iv) < 500 && !str_starts_with($ik, '_') && !str_starts_with($ik, '__')) {
                                    $stripped = strip_tags($iv);
                                    if (strlen($stripped) > 0) {
                                        $itemTexts[$ik] = substr($stripped, 0, 100);
                                    }
                                }
                            }
                            if ($itemTexts) $items[] = $itemTexts;
                        }
                        if ($items) {
                            $entry['repeater_' . $key] = $items;
                        }
                    }
                }

                // Only add if there's something useful (has text, image, or is a known widget)
                if (count($entry) > 2 || in_array($widgetType, ['image', 'video', 'spacer', 'divider', 'html'])) {
                    $result[] = $entry;
                }
            }

            // Recurse into children
            if (!empty($el['elements'])) {
                self::extractEditables($el['elements'], $result, $depth + 1);
            }
        }
    }

    // ─── Section Builder ───

    /**
     * Extract dominant colors/fonts from existing page elements
     */
    private static function extractPageTheme(array $elements): array
    {
        $bgColors = [];
        $headingColors = [];
        $textColors = [];
        $fonts = [];

        $walker = function ($els) use (&$walker, &$bgColors, &$headingColors, &$textColors, &$fonts) {
            foreach ($els as $el) {
                $s = $el['settings'] ?? [];
                $wt = $el['widgetType'] ?? '';
                $et = $el['elType'] ?? '';

                // Collect background colors from containers/sections
                if (in_array($et, ['container', 'section']) && !empty($s['background_color'])) {
                    $bgColors[] = $s['background_color'];
                }
                // Heading colors
                if ($wt === 'heading' && !empty($s['title_color'])) {
                    $headingColors[] = $s['title_color'];
                }
                // Text colors
                if ($wt === 'text-editor' && !empty($s['text_color'])) {
                    $textColors[] = $s['text_color'];
                }
                // Fonts
                if (!empty($s['typography_font_family'])) {
                    $fonts[] = $s['typography_font_family'];
                }

                if (!empty($el['elements'])) $walker($el['elements']);
            }
        };
        $walker($elements);

        // Pick most common values
        return [
            'bg_color' => self::mostCommon($bgColors) ?: '#0C0A09',
            'heading_color' => self::mostCommon($headingColors) ?: '#FAFAF9',
            'text_color' => self::mostCommon($textColors) ?: 'rgba(250,250,249,0.5)',
            'font' => self::mostCommon($fonts) ?: 'Montserrat',
        ];
    }

    private static function mostCommon(array $arr): string
    {
        if (empty($arr)) return '';
        $counts = array_count_values($arr);
        arsort($counts);
        return array_key_first($counts);
    }

    private static function buildNewSection(string $type, array $content, bool $useContainer, array $theme = []): array
    {
        $title = $content['title'] ?? ucfirst($type) . ' Section';
        $subtitle = $content['subtitle'] ?? '';
        $items = $content['items'] ?? [];
        $sectionImage = $content['_image_url'] ?? '';

        // Theme defaults
        $bg = $theme['bg_color'] ?? '#0C0A09';
        $hc = $theme['heading_color'] ?? '#FAFAF9';
        $tc = $theme['text_color'] ?? 'rgba(250,250,249,0.5)';
        $fn = $theme['font'] ?? 'Montserrat';

        // Alternate bg for visual variety
        $bgAlt = self::lightenColor($bg, 15);
        $cardBg = self::lightenColor($bg, 25);
        $accentColor = $theme['accent'] ?? '#6366F1';

        // ── Widget helpers (exact Brooklyn Brew professional values) ──

        $sectionHeading = function($text) use ($hc, $fn) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'heading', 'settings' => [
                'title' => $text, 'header_size' => 'h2', 'align' => 'center',
                'title_color' => $hc, 'typography_typography' => 'custom', 'typography_font_family' => $fn,
                'typography_font_weight' => '900',
                'typography_line_height' => ['size' => 0.95, 'unit' => 'em'],
                'typography_text_transform' => 'uppercase',
                'typography_font_size' => ['size' => 72, 'unit' => 'px', 'sizes' => []],
                'typography_font_size_tablet' => ['size' => 52, 'unit' => 'px'],
                'typography_font_size_mobile' => ['size' => 32, 'unit' => 'px'],
            ], 'elements' => []];
        };

        $heading = function($text, $size = 18, $tag = 'h3', $align = 'center') use ($hc, $fn) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'heading', 'settings' => [
                'title' => $text, 'header_size' => $tag, 'align' => $align,
                'title_color' => $hc, 'typography_typography' => 'custom', 'typography_font_family' => $fn,
                'typography_font_size' => ['size' => $size, 'unit' => 'px', 'sizes' => []],
                'typography_font_weight' => '700',
            ], 'elements' => []];
        };

        $textBlock = function($html, $align = 'center') use ($tc, $fn) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'text-editor', 'settings' => [
                'editor' => $html, 'align' => $align, 'text_color' => $tc,
                'typography_typography' => 'custom', 'typography_font_family' => $fn,
                'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                'typography_line_height' => ['size' => 1.8, 'unit' => 'em'],
                'typography_font_weight' => '300',
            ], 'elements' => []];
        };

        $eyebrow = function($text) use ($hc, $fn) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'text-editor', 'settings' => [
                'editor' => '<p>' . $text . '</p>', 'align' => 'center', 'text_color' => $hc,
                'typography_typography' => 'custom', 'typography_font_family' => $fn,
                'typography_font_size' => ['size' => 13, 'unit' => 'px'],
                'typography_font_weight' => '600', 'typography_letter_spacing' => ['size' => 3, 'unit' => 'px'],
                'typography_text_transform' => 'uppercase',
            ], 'elements' => []];
        };

        $emojiIcon = function($emoji) use ($hc, $fn) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'text-editor', 'settings' => [
                'editor' => "<span style='font-size:28px;'>{$emoji}</span>",
                'text_color' => $hc, 'typography_typography' => 'custom', 'typography_font_family' => $fn,
            ], 'elements' => []];
        };

        $button = function($text, $align = 'center') use ($hc, $fn, $accentColor) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'button', 'settings' => [
                'text' => $text, 'align' => $align, 'button_type' => 'default',
                'background_color' => $accentColor, 'button_background_color' => $accentColor,
                'button_text_color' => $hc,
                'border_radius' => ['top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px', 'isLinked' => true],
                'typography_typography' => 'custom', 'typography_font_family' => $fn,
                'typography_font_size' => ['size' => 13, 'unit' => 'px', 'sizes' => []],
                'typography_font_weight' => '700',
                'typography_letter_spacing' => ['size' => 1.5, 'unit' => 'px', 'sizes' => []],
                'typography_text_transform' => 'uppercase',
            ], 'elements' => []];
        };

        // Professional card container (exact Elementor structure - responsive)
        $card = function($children, $widthPct = 31) use ($bg, $accentColor) {
            return ['id' => self::genId(), 'elType' => 'container', 'settings' => [
                'content_width' => 'full', 'flex_direction' => 'column',
                'padding' => ['unit' => 'px', 'top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'isLinked' => false],
                'background_background' => 'classic', 'background_color' => $bg,
                'border_border' => 'solid',
                'border_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
                'border_color' => 'rgba(250,250,249,0.08)',
                'border_radius' => ['unit' => 'px', 'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'isLinked' => true],
                'width' => ['size' => $widthPct, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
            ], 'elements' => $children];
        };

        // Accent card (with colored top border)
        $accentCard = function($children, $widthPct = 31) use ($bg, $accentColor) {
            return ['id' => self::genId(), 'elType' => 'container', 'settings' => [
                'content_width' => 'full', 'flex_direction' => 'column',
                'padding' => ['unit' => 'px', 'top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'isLinked' => false],
                'background_background' => 'classic', 'background_color' => $bg,
                'border_border' => 'solid',
                'border_width' => ['top' => '3', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
                'border_color' => $accentColor,
                'border_radius' => ['unit' => 'px', 'top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'isLinked' => true],
                'width' => ['size' => $widthPct, 'unit' => '%', 'sizes' => []],
                'width_tablet' => ['size' => 48, 'unit' => '%', 'sizes' => []],
                'width_mobile' => ['size' => 100, 'unit' => '%', 'sizes' => []],
            ], 'elements' => $children];
        };

        // Flex grid row (cards side by side - responsive)
        $gridRow = function($children) {
            return ['id' => self::genId(), 'elType' => 'container', 'settings' => [
                'content_width' => 'full', 'flex_direction' => 'row',
                'flex_direction_mobile' => 'column', 'flex_wrap' => 'wrap',
                'flex_gap' => ['size' => 24, 'unit' => 'px', 'column' => '24', 'row' => '24'],
            ], 'elements' => $children];
        };

        // Image widget
        $imageWidget = function($url, $alt = '') {
            if (!$url) return null;
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'image', 'settings' => [
                'image' => ['url' => $url, 'id' => '', 'alt' => $alt, 'source' => 'library'],
                'image_size' => 'full', 'align' => 'center',
                'width' => ['size' => 100, 'unit' => '%'],
                'border_radius' => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px', 'isLinked' => true],
            ], 'elements' => []];
        };

        // Divider widget
        $divider = function() use ($accentColor) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'divider', 'settings' => [
                'color' => $accentColor, 'weight' => ['size' => 3, 'unit' => 'px'],
                'width' => ['size' => 60, 'unit' => 'px'], 'align' => 'center',
                'gap' => ['size' => 10, 'unit' => 'px'],
            ], 'elements' => []];
        };

        // Spacer widget
        $spacer = function($size = 20) {
            return ['id' => self::genId(), 'elType' => 'widget', 'widgetType' => 'spacer', 'settings' => [
                'space' => ['size' => $size, 'unit' => 'px'],
            ], 'elements' => []];
        };

        $pad = ['top' => '70', 'right' => '50', 'bottom' => '70', 'left' => '50', 'unit' => 'px', 'isLinked' => false];
        $elements = [];
        $cw = count($items) <= 2 ? 48 : (count($items) <= 3 ? 31 : 23);

        // ── Build section based on type ──
        // ALL Elementor native widgets - fully editable in Elementor visual editor

        // Emoji icons for different section types
        $emojis = ['features' => ['⚡','🎯','💎','🔥','✨','🚀'], 'testimonials' => ['💬','💬','💬'], 'pricing' => ['☕','🍵','🧊','🥑','🧁','🍰'], 'team' => ['👤','👤','👤','👤'], 'contact' => ['📍','📞','🕐','📧'], 'faq' => ['❓','❓','❓','❓','❓'], 'custom' => ['⭐','⭐','⭐','⭐']];

        switch ($type) {
            case 'hero':
                $elements[] = $eyebrow($content['eyebrow'] ?? strtoupper($type));
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                if ($sectionImage) {
                    $img = $imageWidget($sectionImage, $title);
                    if ($img) { $elements[] = $spacer(20); $elements[] = $img; }
                }
                $elements[] = $spacer(20);
                $btnRow = $gridRow([
                    $button($content['button_text'] ?? 'Get Started'),
                    $button($content['button2_text'] ?? 'Learn More'),
                ]);
                $elements[] = $btnRow;
                return self::wrapContainer($elements, $bg, ['top' => '100', 'right' => '40', 'bottom' => '100', 'left' => '40', 'unit' => 'px', 'isLinked' => false]);

            case 'features':
            case 'custom':
                $elements[] = $sectionHeading($title);
                $elements[] = $spacer(32);
                if ($items) {
                    $cards = [];
                    $icons = $emojis[$type] ?? $emojis['custom'];
                    foreach ($items as $i => $item) {
                        $t = $item['title'] ?? '';
                        $d = $item['text'] ?? $item['description'] ?? '';
                        $icon = $icons[$i % count($icons)];
                        $cards[] = $card([
                            $emojiIcon($icon),
                            $heading($t, 18, 'h3', 'center'),
                            $textBlock("<p>{$d}</p>", 'center'),
                        ], $cw);
                    }
                    $elements[] = $gridRow($cards);
                }
                return self::wrapContainer($elements, $bg, $pad);

            case 'testimonials':
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(32);
                if ($items) {
                    $cards = [];
                    foreach ($items as $i => $item) {
                        $name = $item['title'] ?? 'Customer';
                        $quote = strip_tags($item['text'] ?? $item['description'] ?? '');
                        $cards[] = $card([
                            $emojiIcon('💬'),
                            $textBlock("<p><em>\"{$quote}\"</em></p>", 'center'),
                            $heading("— {$name}", 14, 'h5', 'center'),
                            $textBlock("<p>★★★★★</p>", 'center'),
                        ], $cw);
                    }
                    $elements[] = $gridRow($cards);
                }
                return self::wrapContainer($elements, $bgAlt, $pad);

            case 'pricing':
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(32);
                if ($items) {
                    $cards = [];
                    $icons = $emojis['pricing'];
                    foreach ($items as $i => $item) {
                        $t = $item['title'] ?? '';
                        $d = $item['text'] ?? $item['description'] ?? '';
                        $icon = $icons[$i % count($icons)];
                        $cards[] = $card([
                            $emojiIcon($icon),
                            $heading($t, 18, 'h3', 'center'),
                            $textBlock("<p>{$d}</p>", 'center'),
                        ], $cw);
                    }
                    $elements[] = $gridRow($cards);
                }
                return self::wrapContainer($elements, $bg, $pad);

            case 'faq':
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(32);
                if ($items) {
                    foreach ($items as $item) {
                        $q = $item['title'] ?? '';
                        $a = $item['text'] ?? $item['description'] ?? '';
                        $elements[] = $card([
                            $heading("❓ {$q}", 18, 'h4', 'left'),
                            $textBlock("<p>{$a}</p>", 'left'),
                        ], 100);
                    }
                }
                return self::wrapContainer($elements, $bgAlt, $pad);

            case 'cta':
                $elements[] = $eyebrow($content['eyebrow'] ?? 'GET IN TOUCH');
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(20);
                $elements[] = $button($content['button_text'] ?? 'Contact Us');
                return self::wrapContainer($elements, $bg, ['top' => '100', 'right' => '60', 'bottom' => '100', 'left' => '60', 'unit' => 'px', 'isLinked' => false]);

            case 'team':
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(32);
                if ($items) {
                    $cards = [];
                    foreach ($items as $item) {
                        $name = $item['title'] ?? '';
                        $role = $item['text'] ?? $item['description'] ?? '';
                        $cards[] = $card([
                            $emojiIcon('👤'),
                            $heading($name, 18, 'h3', 'center'),
                            $textBlock("<p>{$role}</p>", 'center'),
                        ], $cw);
                    }
                    $elements[] = $gridRow($cards);
                }
                return self::wrapContainer($elements, $bg, $pad);

            case 'contact':
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                $elements[] = $spacer(32);
                if ($items) {
                    $cards = [];
                    $icons = $emojis['contact'];
                    foreach ($items as $i => $item) {
                        $t = $item['title'] ?? '';
                        $d = $item['text'] ?? $item['description'] ?? '';
                        $icon = $icons[$i % count($icons)];
                        $cards[] = $card([
                            $emojiIcon($icon),
                            $heading($t, 16, 'h4', 'center'),
                            $textBlock("<p>{$d}</p>", 'center'),
                        ], $cw);
                    }
                    $elements[] = $gridRow($cards);
                }
                $elements[] = $spacer(20);
                $elements[] = $button($content['button_text'] ?? 'Get In Touch');
                return self::wrapContainer($elements, $bgAlt, $pad);

            default:
                $elements[] = $sectionHeading($title);
                if ($subtitle) $elements[] = $textBlock("<p>{$subtitle}</p>");
                return self::wrapContainer($elements, $bg, $pad);
        }
    }

    /**
     * Wrap elements in a container with background and padding
     */
    private static function wrapContainer(array $children, string $bgColor, array $padding): array
    {
        return [
            'id' => self::genId(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'padding' => $padding,
                'background_background' => 'classic',
                'background_color' => $bgColor,
                'flex_direction' => 'column',
                'flex_gap' => ['size' => 20, 'unit' => 'px', 'column' => '20', 'row' => '20'],
            ],
            'elements' => $children,
        ];
    }

    /**
     * Lighten a hex color by a percentage
     */
    private static function lightenColor(string $hex, int $percent): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return '#' . $hex;
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $r = min(255, $r + (int)(($percent / 100) * (255 - $r)));
        $g = min(255, $g + (int)(($percent / 100) * (255 - $g)));
        $b = min(255, $b + (int)(($percent / 100) * (255 - $b)));
        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    // ─── Save & Cache ───

    private static function saveElementorData(int $pageId, array $elements): void
    {
        // Save snapshot for undo before overwriting
        AICopilot_History::saveSnapshot($pageId);

        // Use json_encode (NOT wp_json_encode) to preserve escaped slashes
        // wp_json_encode adds JSON_UNESCAPED_SLASHES which changes Elementor's URL format
        $json = wp_slash(json_encode($elements));
        update_post_meta($pageId, '_elementor_data', $json);
        self::clearElementorCache($pageId);
    }

    private static function clearElementorCache(int $pageId): void
    {
        global $wpdb;

        if ($pageId) {
            // Only clear cache for THIS specific page - not all pages
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key IN ('_elementor_css','_elementor_element_cache','_elementor_page_assets')",
                $pageId
            ));

            // Delete only this page's CSS file
            $upload_dir = wp_upload_dir();
            $css_file = $upload_dir['basedir'] . '/elementor/css/post-' . $pageId . '.css';
            if (file_exists($css_file)) @unlink($css_file);
        }

        // Regenerate CSS via Elementor API (if available)
        if (class_exists('\Elementor\Plugin')) {
            try {
                // Clear just this page's CSS so Elementor regenerates it on next load
                $post_css = \Elementor\Core\Files\CSS\Post::create($pageId);
                $post_css->update();
            } catch (\Exception $e) {
                // Fallback: delete post CSS meta so it regenerates
            }
        }

        // Clear WordPress object cache for this post
        clean_post_cache($pageId);
    }

    // ─── Utility Helpers ───

    private static function sideloadImage(string $url): ?int
    {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $tmp = download_url($url);
        if (is_wp_error($tmp)) return null;

        $file = ['name' => basename(parse_url($url, PHP_URL_PATH)) ?: 'image.jpg', 'tmp_name' => $tmp];
        $id = media_handle_sideload($file, 0);
        if (is_wp_error($id)) {
            @unlink($tmp);
            return null;
        }

        return $id;
    }

    /**
     * Detect widget type for an element ID
     */
    private static function getWidgetType(array $elements, string $id): string
    {
        foreach ($elements as $el) {
            if (($el['id'] ?? '') === $id) {
                return $el['widgetType'] ?? $el['elType'] ?? '';
            }
            if (!empty($el['elements'])) {
                $type = self::getWidgetType($el['elements'], $id);
                if ($type) return $type;
            }
        }
        return '';
    }

    /**
     * Map generic color/style property to correct Elementor property per widget type
     */
    private static function fixPropertyForWidget(string $property, string $widgetType): string
    {
        // Normalize generic color names to correct per-widget property
        $colorNames = ['color', 'text_color', 'text-color', 'textColor', 'title_color'];

        if (in_array($property, $colorNames)) {
            return match ($widgetType) {
                'heading' => 'title_color',
                'text-editor' => 'text_color',
                'button' => 'button_text_color',
                'icon-box' => 'title_color',
                default => 'text_color',
            };
        }

        return $property;
    }

    private static function getElementFieldValue(array $elements, string $id, string $field): mixed
    {
        foreach ($elements as $el) {
            if (($el['id'] ?? '') === $id) {
                return $el['settings'][$field] ?? null;
            }
            if (!empty($el['elements'])) {
                $val = self::getElementFieldValue($el['elements'], $id, $field);
                if ($val !== null) return $val;
            }
        }
        return null;
    }

    private static function genId(): string
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 7);
    }

    private static function countWidgets(array $elements): int
    {
        $count = 0;
        foreach ($elements as $el) {
            if (!empty($el['widgetType'])) $count++;
            if (!empty($el['elements'])) $count += self::countWidgets($el['elements']);
        }
        return $count;
    }

    private static function getFirstText(array $elements): string
    {
        foreach ($elements as $el) {
            $s = $el['settings'] ?? [];
            if (!empty($s['title'])) return substr($s['title'], 0, 50);
            if (!empty($s['editor'])) return substr(strip_tags($s['editor']), 0, 50);
            if (!empty($s['text'])) return substr($s['text'], 0, 50);
            if (!empty($el['elements'])) {
                $text = self::getFirstText($el['elements']);
                if ($text) return $text;
            }
        }
        return '';
    }
}
