<?php

namespace App\Services\AiCopilot;

use App\Models\Website;
use App\Services\WpBridgeService;
use Illuminate\Support\Facades\Log;

/**
 * Executes AI Copilot tool calls by delegating to WpBridgeService.
 */
class ActionExecutor
{
    private WpBridgeService $bridge;

    public function __construct(WpBridgeService $bridge)
    {
        $this->bridge = $bridge;
    }

    /**
     * Execute a single tool call and return the result.
     */
    public function execute(Website $website, string $toolName, array $input): array
    {
        // Normalize Gemini parameter name mangling (e.g., pagea_id → page_id)
        $input = $this->normalizeInput($input);

        try {
            $result = match ($toolName) {
                'edit_element_text'   => $this->editElementText($website, $input),
                'edit_element_style'  => $this->editElementStyle($website, $input),
                'edit_element_image'  => $this->editElementImage($website, $input),
                'get_page_editables'  => $this->getPageEditables($website, $input),
                'add_section'         => $this->addSection($website, $input),
                'remove_section'      => $this->removeSection($website, $input),
                'reorder_sections'    => $this->reorderSections($website, $input),
                'create_page'         => $this->createPage($website, $input),
                'update_page_title'   => $this->updatePageTitle($website, $input),
                'delete_page'         => $this->deletePage($website, $input),
                'set_global_colors'   => $this->setGlobalColors($website, $input),
                'get_global_colors'   => $this->getGlobalColors($website),
                'set_global_fonts'    => $this->setGlobalFonts($website, $input),
                'update_page_seo'     => $this->updatePageSeo($website, $input),
                'get_page_seo'        => $this->getPageSeo($website, $input),
                'create_product'      => $this->createProduct($website, $input),
                'update_product'      => $this->updateProduct($website, $input),
                'list_products'       => $this->listProducts($website, $input),
                'install_plugin'      => $this->installPlugin($website, $input),
                'upload_image'        => $this->uploadImage($website, $input),
                'get_menus'           => $this->getMenus($website),
                'update_menu'         => $this->updateMenu($website, $input),
                'update_site_settings' => $this->updateSiteSettings($website, $input),
                default               => ['success' => false, 'error' => "Unknown tool: {$toolName}"],
            };

            return array_merge(['tool' => $toolName], $result);
        } catch (\Exception $e) {
            Log::error("Copilot action failed [{$toolName}]: {$e->getMessage()}");
            return ['tool' => $toolName, 'success' => false, 'error' => $e->getMessage()];
        }
    }

    // ─── Content Editing ───

    private function editElementText(Website $website, array $input): array
    {
        $pageId = $input['page_id'];
        $elementId = $input['element_id'];
        $field = $input['field'];
        $value = $input['value'];

        // Get current data
        $result = $this->bridge->getElementorPageData($website, $pageId);
        $data = $result['data']['elementor_data'] ?? [];

        if (empty($data)) {
            return ['success' => false, 'error' => 'No Elementor data found'];
        }

        // Find and update the element
        $updated = $this->updateElementField($data, $elementId, $field, $value);

        if (!$updated) {
            return ['success' => false, 'error' => "Element {$elementId} not found"];
        }

        // Save back
        $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $saveResult = $this->bridge->updateElementorPageData($website, $pageId, $newJson);

        return [
            'success' => true,
            'action' => 'text_edited',
            'page_id' => $pageId,
            'element_id' => $elementId,
            'field' => $field,
            'new_value' => $value,
            'before_data' => $saveResult['data']['before_data'] ?? null,
        ];
    }

    private function editElementStyle(Website $website, array $input): array
    {
        $pageId = $input['page_id'];
        $elementId = $input['element_id'];
        $property = $input['property'];
        $value = $input['value'];

        $result = $this->bridge->getElementorPageData($website, $pageId);
        $data = $result['data']['elementor_data'] ?? [];

        if (empty($data)) {
            return ['success' => false, 'error' => 'No Elementor data found'];
        }

        $updated = $this->updateElementSetting($data, $elementId, $property, $value);

        if (!$updated) {
            return ['success' => false, 'error' => "Element {$elementId} not found"];
        }

        $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $saveResult = $this->bridge->updateElementorPageData($website, $pageId, $newJson);

        return [
            'success' => true,
            'action' => 'style_changed',
            'page_id' => $pageId,
            'element_id' => $elementId,
            'property' => $property,
            'new_value' => $value,
            'before_data' => $saveResult['data']['before_data'] ?? null,
        ];
    }

    private function editElementImage(Website $website, array $input): array
    {
        $pageId = $input['page_id'];
        $elementId = $input['element_id'];
        $imageUrl = $input['image_url'];
        $altText = $input['alt_text'] ?? '';

        // Upload image to media library first
        $uploadResult = $this->bridge->uploadMediaFromUrl($website, $imageUrl, $altText);
        $newUrl = $uploadResult['data']['url'] ?? '';
        $attachId = $uploadResult['data']['attachment_id'] ?? 0;

        if (!$newUrl) {
            return ['success' => false, 'error' => 'Failed to upload image'];
        }

        // Get current data and update the image
        $result = $this->bridge->getElementorPageData($website, $pageId);
        $data = $result['data']['elementor_data'] ?? [];

        $updated = $this->updateElementImage($data, $elementId, $newUrl, $attachId);

        if (!$updated) {
            return ['success' => false, 'error' => "Element {$elementId} not found"];
        }

        $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->bridge->updateElementorPageData($website, $pageId, $newJson);

        return [
            'success' => true,
            'action' => 'image_changed',
            'page_id' => $pageId,
            'element_id' => $elementId,
            'new_image_url' => $newUrl,
        ];
    }

    // ─── Read Operations ───

    private function getPageEditables(Website $website, array $input): array
    {
        $result = $this->bridge->getElementorEditables($website, $input['page_id']);
        return ['success' => true, 'data' => $result['data'] ?? []];
    }

    // ─── Section Management ───

    private function addSection(Website $website, array $input): array
    {
        $pageId = $input['page_id'];
        $position = $input['position'] ?? -1;
        $sectionType = $input['section_type'] ?? 'custom';
        $content = $input['content'] ?? [];

        $sectionData = SectionTemplates::generate($sectionType, $content);
        $sectionJson = json_encode($sectionData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $result = $this->bridge->addElementorSection($website, $pageId, $sectionJson, $position);

        return [
            'success' => true,
            'action' => 'section_added',
            'page_id' => $pageId,
            'section_type' => $sectionType,
            'before_data' => $result['data']['before_data'] ?? null,
        ];
    }

    private function removeSection(Website $website, array $input): array
    {
        $result = $this->bridge->removeElementorSection($website, $input['page_id'], $input['element_id']);
        return [
            'success' => true,
            'action' => 'section_removed',
            'page_id' => $input['page_id'],
            'element_id' => $input['element_id'],
            'before_data' => $result['data']['before_data'] ?? null,
        ];
    }

    private function reorderSections(Website $website, array $input): array
    {
        $result = $this->bridge->reorderElementorSections($website, $input['page_id'], $input['order']);
        return [
            'success' => true,
            'action' => 'sections_reordered',
            'page_id' => $input['page_id'],
            'before_data' => $result['data']['before_data'] ?? null,
        ];
    }

    // ─── Page Management ───

    private function createPage(Website $website, array $input): array
    {
        $title = $input['title'];
        $content = $input['content'] ?? '';
        $status = $input['status'] ?? 'publish';

        if ($content) {
            $result = $this->bridge->createPost($website, $title, $content, 'page', $status);
        } else {
            $result = $this->bridge->createElementorPage($website, $title, '[]', $status);
        }

        return [
            'success' => true,
            'action' => 'page_created',
            'page_id' => $result['data']['post_id'] ?? $result['data']['page_id'] ?? null,
            'title' => $title,
            'url' => $result['data']['url'] ?? '',
        ];
    }

    private function updatePageTitle(Website $website, array $input): array
    {
        $this->bridge->updatePost($website, $input['page_id'], ['title' => $input['title']]);
        return ['success' => true, 'action' => 'title_updated', 'page_id' => $input['page_id'], 'title' => $input['title']];
    }

    private function deletePage(Website $website, array $input): array
    {
        $force = $input['permanent'] ?? false;
        $this->bridge->deletePost($website, $input['page_id'], $force);
        return ['success' => true, 'action' => 'page_deleted', 'page_id' => $input['page_id']];
    }

    // ─── Global Styles ───

    private function setGlobalColors(Website $website, array $input): array
    {
        $this->bridge->setGlobalColors($website, $input['colors']);
        $this->bridge->regenerateElementorCss($website);
        return ['success' => true, 'action' => 'global_colors_updated'];
    }

    private function getGlobalColors(Website $website): array
    {
        $result = $this->bridge->getGlobalColors($website);
        return ['success' => true, 'data' => $result['data'] ?? []];
    }

    private function setGlobalFonts(Website $website, array $input): array
    {
        $this->bridge->setGlobalFonts($website, $input['fonts']);
        $this->bridge->regenerateElementorCss($website);
        return ['success' => true, 'action' => 'global_fonts_updated'];
    }

    // ─── SEO ───

    private function updatePageSeo(Website $website, array $input): array
    {
        $pageId = $input['page_id'];
        $seoData = array_filter([
            'seo_title' => $input['seo_title'] ?? null,
            'seo_description' => $input['seo_description'] ?? null,
            'focus_keyword' => $input['focus_keyword'] ?? null,
        ]);
        $this->bridge->updatePageSeo($website, $pageId, $seoData);
        return ['success' => true, 'action' => 'seo_updated', 'page_id' => $pageId, 'fields' => array_keys($seoData)];
    }

    private function getPageSeo(Website $website, array $input): array
    {
        $result = $this->bridge->getPageSeo($website, $input['page_id']);
        return ['success' => true, 'data' => $result['data'] ?? []];
    }

    // ─── WooCommerce ───

    private function createProduct(Website $website, array $input): array
    {
        $result = $this->bridge->createProduct($website, $input);
        return [
            'success' => true,
            'action' => 'product_created',
            'product_id' => $result['data']['product_id'] ?? null,
            'name' => $input['name'],
        ];
    }

    private function updateProduct(Website $website, array $input): array
    {
        $productId = $input['product_id'];
        unset($input['product_id']);
        $this->bridge->updateProduct($website, $productId, $input);
        return ['success' => true, 'action' => 'product_updated', 'product_id' => $productId];
    }

    private function listProducts(Website $website, array $input): array
    {
        $result = $this->bridge->listProducts($website, $input);
        return ['success' => true, 'data' => $result['data'] ?? []];
    }

    // ─── Plugins ───

    private function installPlugin(Website $website, array $input): array
    {
        $slug = $input['slug'];
        $this->bridge->installPlugin($website, $slug);

        if ($input['activate'] ?? true) {
            $this->bridge->activatePlugin($website, "{$slug}/{$slug}.php");
        }

        return ['success' => true, 'action' => 'plugin_installed', 'slug' => $slug];
    }

    // ─── Media ───

    private function uploadImage(Website $website, array $input): array
    {
        $result = $this->bridge->uploadMediaFromUrl(
            $website,
            $input['image_url'],
            $input['alt_text'] ?? '',
            $input['title'] ?? ''
        );
        return [
            'success' => true,
            'action' => 'image_uploaded',
            'url' => $result['data']['url'] ?? '',
            'attachment_id' => $result['data']['attachment_id'] ?? null,
        ];
    }

    // ─── Menu ───

    private function getMenus(Website $website): array
    {
        $result = $this->bridge->listMenus($website);
        return ['success' => true, 'data' => $result['data'] ?? []];
    }

    private function updateMenu(Website $website, array $input): array
    {
        $this->bridge->updateMenu($website, $input['menu_id'], $input['items']);
        return ['success' => true, 'action' => 'menu_updated', 'menu_id' => $input['menu_id']];
    }

    // ─── Site Settings ───

    private function updateSiteSettings(Website $website, array $input): array
    {
        $options = array_filter([
            'blogname' => $input['blogname'] ?? null,
            'blogdescription' => $input['blogdescription'] ?? null,
        ]);
        $this->bridge->setOptions($website, $options);
        return ['success' => true, 'action' => 'settings_updated', 'updated' => array_keys($options)];
    }

    // ─── Input Normalization (Gemini sometimes mangles param names) ───

    private function normalizeInput(array $input): array
    {
        $normalized = [];
        $keyMap = [
            'pagea_id' => 'page_id', 'page_ida' => 'page_id', 'pageId' => 'page_id', 'pageaid' => 'page_id',
            'elementa_id' => 'element_id', 'element_ida' => 'element_id', 'elementId' => 'element_id',
            'image_urla' => 'image_url', 'imagea_url' => 'image_url', 'imageUrl' => 'image_url',
            'alt_texta' => 'alt_text', 'alta_text' => 'alt_text', 'altText' => 'alt_text',
            'section_typea' => 'section_type', 'sectiona_type' => 'section_type', 'sectionType' => 'section_type',
            'menu_ida' => 'menu_id', 'menua_id' => 'menu_id', 'menuId' => 'menu_id',
            'product_ida' => 'product_id', 'producta_id' => 'product_id', 'productId' => 'product_id',
            'post_ida' => 'post_id', 'posta_id' => 'post_id', 'postId' => 'post_id',
            'seo_titlea' => 'seo_title', 'seoa_title' => 'seo_title',
            'seo_descriptiona' => 'seo_description', 'seoa_description' => 'seo_description',
            'focus_keyworda' => 'focus_keyword', 'focusa_keyword' => 'focus_keyword',
            'sale_pricea' => 'sale_price', 'salea_price' => 'sale_price',
            'short_descriptiona' => 'short_description',
            'per_pagea' => 'per_page',
        ];

        foreach ($input as $key => $value) {
            $normalizedKey = $keyMap[$key] ?? $key;
            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    // ─── Elementor Tree Manipulation Helpers ───

    private function updateElementField(array &$elements, string $targetId, string $field, $value): bool
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $targetId) {
                $el['settings'][$field] = $value;
                return true;
            }
            if (!empty($el['elements']) && $this->updateElementField($el['elements'], $targetId, $field, $value)) {
                return true;
            }
        }
        return false;
    }

    private function updateElementSetting(array &$elements, string $targetId, string $property, $value): bool
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $targetId) {
                $el['settings'][$property] = $value;
                return true;
            }
            if (!empty($el['elements']) && $this->updateElementSetting($el['elements'], $targetId, $property, $value)) {
                return true;
            }
        }
        return false;
    }

    private function updateElementImage(array &$elements, string $targetId, string $url, int $attachId): bool
    {
        foreach ($elements as &$el) {
            if (($el['id'] ?? '') === $targetId) {
                $settings = &$el['settings'];

                // Handle image widget
                if (isset($settings['image'])) {
                    $settings['image']['url'] = $url;
                    $settings['image']['id'] = $attachId;
                    return true;
                }

                // Handle background image (container/section)
                if (isset($settings['background_image'])) {
                    $settings['background_image']['url'] = $url;
                    $settings['background_image']['id'] = $attachId;
                    return true;
                }

                // Fallback: set as image
                $settings['image'] = ['url' => $url, 'id' => $attachId];
                return true;
            }
            if (!empty($el['elements']) && $this->updateElementImage($el['elements'], $targetId, $url, $attachId)) {
                return true;
            }
        }
        return false;
    }
}
