<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ContentSwapperService
{
    private string $dbHost;
    private string $dbUser;
    private string $dbPass;

    public function __construct()
    {
        $this->dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $this->dbUser = config('database.connections.mysql.username', 'root');
        $this->dbPass = config('database.connections.mysql.password', '');
    }

    /**
     * Swap all demo content in a cloned site with AI-generated content.
     *
     * @param string $dbName         Clone database name
     * @param array  $aiContent      AI-generated content keyed by section (pages, business_name, etc.)
     * @param array  $imageMap       Map of role => ['url' => ..., 'id' => ...] for replacement images
     * @param string $businessName   Business name to replace in titles/headings
     * @param string $oldSiteUrl     The master site URL being replaced
     * @param string $newSiteUrl     The clone site URL
     */
    public function swap(
        string $dbName,
        array $aiContent,
        array $imageMap,
        string $businessName,
        string $oldSiteUrl = '',
        string $newSiteUrl = ''
    ): void {
        $pdo = $this->getPdo($dbName);

        // Step 1: Update site title
        if ($businessName) {
            $this->setOption($pdo, 'blogname', $businessName);
            $this->setOption($pdo, 'blogdescription', $aiContent['tagline'] ?? '');
        }

        // Step 2: Get all pages/posts with Elementor data
        $pages = $this->getPagesWithElementorData($pdo);
        Log::info("ContentSwapper: Found " . count($pages) . " pages with Elementor data");

        // Build content map from AI content
        $contentMap = $this->buildContentMap($aiContent);

        // Track which content sections have been used
        $usedSections = [];
        $textIndex = 0;

        // Step 3: Walk each page and replace content
        foreach ($pages as $page) {
            $elementorData = json_decode($page['elementor_data'], true);
            if (!$elementorData || !is_array($elementorData)) {
                continue;
            }

            $pageSlug = $page['post_name'];
            $pageContent = $contentMap[$pageSlug] ?? $contentMap['generic'] ?? [];

            $this->walkAndReplace($elementorData, $pageContent, $imageMap, $businessName, $textIndex);

            // Save back
            $newJson = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $this->updateElementorData($pdo, $page['post_id'], $newJson);

            // Also update post_content (Elementor stores a plain-text version)
            $plainContent = $this->extractPlainText($elementorData);
            if ($plainContent) {
                $pdo->prepare("UPDATE wp_posts SET post_content = ? WHERE ID = ?")->execute([$plainContent, $page['post_id']]);
            }

            Log::info("ContentSwapper: Processed page '{$pageSlug}' (ID {$page['post_id']})");
        }

        // Step 4: Update post titles for pages
        $this->updatePageTitles($pdo, $aiContent, $businessName);

        // Step 5: Replace images in Elementor data
        if (!empty($imageMap)) {
            $this->replaceImagesGlobally($pdo, $imageMap);
        }

        // Step 6: Update nav menu labels
        $this->updateNavMenus($pdo, $businessName);

        // Step 7: Replace any remaining demo text in theme options
        $this->updateThemeOptions($pdo, $businessName, $aiContent);

        Log::info("ContentSwapper: Swap complete for {$dbName}");
    }

    private function getPagesWithElementorData(\PDO $pdo): array
    {
        $stmt = $pdo->query("
            SELECT p.ID as post_id, p.post_name, p.post_title, p.post_type,
                   pm.meta_value as elementor_data
            FROM wp_posts p
            JOIN wp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_elementor_data'
            WHERE p.post_type IN ('page', 'post', 'elementor-hf', 'elementor_library')
              AND p.post_status IN ('publish', 'draft', 'private')
              AND pm.meta_value IS NOT NULL
              AND pm.meta_value != ''
              AND pm.meta_value != '[]'
            ORDER BY p.menu_order ASC, p.ID ASC
        ");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Build a content map from AI-generated content, keyed by page slug.
     * Each page gets arrays of: headings, paragraphs, items
     */
    private function buildContentMap(array $aiContent): array
    {
        $map = [];

        // AI content may have pages as array-of-objects with slug keys, or as slug-keyed assoc array
        $pages = $aiContent['pages'] ?? [];

        // Detect slug-keyed format: { "home": {...}, "about": {...} }
        if (!empty($pages) && !isset($pages[0])) {
            $normalized = [];
            foreach ($pages as $slug => $pageData) {
                if (is_array($pageData)) {
                    $pageData['slug'] = $slug;
                    $normalized[] = $pageData;
                }
            }
            $pages = $normalized;
        }

        foreach ($pages as $page) {
            $slug = $page['slug'] ?? '';
            if (!$slug) continue;

            $map[$slug] = [
                'headings' => [],
                'paragraphs' => [],
                'items' => [],
                'buttons' => [],
            ];

            // Collect headings and text from sections
            $sections = $page['sections'] ?? [];
            foreach ($sections as $section) {
                if (isset($section['title'])) {
                    $map[$slug]['headings'][] = $section['title'];
                }
                if (isset($section['subtitle'])) {
                    $map[$slug]['headings'][] = $section['subtitle'];
                }
                if (isset($section['description'])) {
                    $map[$slug]['paragraphs'][] = $section['description'];
                }
                if (isset($section['content'])) {
                    $map[$slug]['paragraphs'][] = $section['content'];
                }
                if (isset($section['button_text'])) {
                    $map[$slug]['buttons'][] = $section['button_text'];
                }

                // Items (features, services, team members, etc.)
                $items = $section['items'] ?? $section['features'] ?? $section['services'] ?? [];
                foreach ($items as $item) {
                    $map[$slug]['items'][] = $item;
                    if (isset($item['title'])) {
                        $map[$slug]['headings'][] = $item['title'];
                    }
                    if (isset($item['description'])) {
                        $map[$slug]['paragraphs'][] = $item['description'];
                    }
                }
            }

            // Also gather from flat keys (home_hero_title, etc.)
            foreach ($page as $key => $value) {
                if (is_string($value) && strlen($value) > 5) {
                    if (str_contains($key, 'title') || str_contains($key, 'heading')) {
                        $map[$slug]['headings'][] = $value;
                    } elseif (str_contains($key, 'description') || str_contains($key, 'text') || str_contains($key, 'content')) {
                        $map[$slug]['paragraphs'][] = $value;
                    }
                }
            }
        }

        // Also populate generic content from top-level keys
        $map['generic'] = [
            'headings' => [],
            'paragraphs' => [],
            'items' => [],
            'buttons' => [],
        ];

        foreach (['hero_title', 'hero_subtitle', 'about_title', 'services_title', 'contact_title'] as $key) {
            if (isset($aiContent[$key])) {
                $map['generic']['headings'][] = $aiContent[$key];
            }
        }
        foreach (['hero_description', 'about_description', 'services_description', 'contact_description'] as $key) {
            if (isset($aiContent[$key])) {
                $map['generic']['paragraphs'][] = $aiContent[$key];
            }
        }

        // Add tagline to generic headings
        if (isset($aiContent['tagline'])) {
            $map['generic']['headings'][] = $aiContent['tagline'];
        }

        // Ensure home page also gets top-level hero content
        if (isset($map['home'])) {
            if (isset($aiContent['hero_title']) && !in_array($aiContent['hero_title'], $map['home']['headings'])) {
                array_unshift($map['home']['headings'], $aiContent['hero_title']);
            }
            if (isset($aiContent['hero_subtitle']) && !in_array($aiContent['hero_subtitle'], $map['home']['headings'])) {
                $map['home']['headings'][] = $aiContent['hero_subtitle'];
            }
            if (isset($aiContent['hero_cta']) && !in_array($aiContent['hero_cta'], $map['home']['buttons'])) {
                array_unshift($map['home']['buttons'], $aiContent['hero_cta']);
            }
        }

        return $map;
    }

    /**
     * Recursively walk Elementor elements and replace text content.
     */
    private function walkAndReplace(array &$elements, array $pageContent, array $imageMap, string $businessName, int &$textIndex): void
    {
        foreach ($elements as &$element) {
            $elType = $element['elType'] ?? '';
            $widgetType = $element['widgetType'] ?? '';
            $settings = &$element['settings'] ?? [];

            if ($elType === 'widget' && !empty($settings)) {
                $this->replaceWidgetContent($settings, $widgetType, $pageContent, $imageMap, $businessName, $textIndex);
            }

            // Recurse into children
            if (!empty($element['elements']) && is_array($element['elements'])) {
                $this->walkAndReplace($element['elements'], $pageContent, $imageMap, $businessName, $textIndex);
            }
        }
    }

    /**
     * Replace content in a single widget's settings based on widget type.
     */
    private function replaceWidgetContent(array &$settings, string $widgetType, array $pageContent, array $imageMap, string $businessName, int &$textIndex): void
    {
        $headings = $pageContent['headings'] ?? [];
        $paragraphs = $pageContent['paragraphs'] ?? [];
        $buttons = $pageContent['buttons'] ?? [];

        switch ($widgetType) {
            case 'heading':
                if (!empty($headings) && isset($settings['title'])) {
                    $idx = $textIndex % count($headings);
                    $settings['title'] = $headings[$idx];
                    $textIndex++;
                }
                break;

            case 'text-editor':
                if (!empty($paragraphs) && isset($settings['editor'])) {
                    $idx = $textIndex % count($paragraphs);
                    $settings['editor'] = '<p>' . $paragraphs[$idx] . '</p>';
                    $textIndex++;
                }
                break;

            case 'button':
            case 'cta':
                if (!empty($buttons) && isset($settings['text'])) {
                    $idx = min($textIndex, count($buttons) - 1);
                    $settings['text'] = $buttons[$idx] ?? $settings['text'];
                }
                break;

            case 'image':
            case 'image-box':
                $this->replaceImageWidget($settings, $imageMap);
                // Replace text fields in image-box
                if ($widgetType === 'image-box') {
                    if (!empty($headings) && isset($settings['title_text'])) {
                        $idx = $textIndex % count($headings);
                        $settings['title_text'] = $headings[$idx];
                        $textIndex++;
                    }
                    if (!empty($paragraphs) && isset($settings['description_text'])) {
                        $idx = $textIndex % count($paragraphs);
                        $settings['description_text'] = $paragraphs[$idx];
                        $textIndex++;
                    }
                }
                break;

            case 'icon-box':
                if (!empty($headings) && isset($settings['title_text'])) {
                    $idx = $textIndex % count($headings);
                    $settings['title_text'] = $headings[$idx];
                    $textIndex++;
                }
                if (!empty($paragraphs) && isset($settings['description_text'])) {
                    $idx = $textIndex % count($paragraphs);
                    $settings['description_text'] = $paragraphs[$idx];
                    $textIndex++;
                }
                break;

            case 'counter':
            case 'progress':
                // Keep numeric data, update labels
                if (!empty($headings) && isset($settings['title'])) {
                    $idx = $textIndex % count($headings);
                    $settings['title'] = $headings[$idx];
                    $textIndex++;
                }
                break;

            case 'testimonial':
                if (!empty($paragraphs) && isset($settings['testimonial_content'])) {
                    $idx = $textIndex % count($paragraphs);
                    $settings['testimonial_content'] = $paragraphs[$idx];
                    $textIndex++;
                }
                if ($businessName && isset($settings['testimonial_name'])) {
                    // Keep original name — it's a person's name
                }
                break;

            case 'image-carousel':
            case 'image-gallery':
                if (isset($settings['carousel']) && is_array($settings['carousel'])) {
                    $this->replaceCarouselImages($settings['carousel'], $imageMap);
                }
                if (isset($settings['gallery']) && is_array($settings['gallery'])) {
                    $this->replaceCarouselImages($settings['gallery'], $imageMap);
                }
                break;

            default:
                // Generic: scan all string settings for text replacement
                $this->genericTextReplace($settings, $headings, $paragraphs, $businessName, $textIndex);
                break;
        }
    }

    /**
     * Replace image URL/ID in widget settings.
     */
    private function replaceImageWidget(array &$settings, array $imageMap): void
    {
        if (isset($settings['image']) && is_array($settings['image'])) {
            $replacement = $this->pickImage($imageMap, $settings['image']['url'] ?? '');
            if ($replacement) {
                $settings['image']['url'] = $replacement['url'];
                if (isset($replacement['id'])) {
                    $settings['image']['id'] = $replacement['id'];
                }
            }
        }

        // Background images in section/column settings
        if (isset($settings['background_image']) && is_array($settings['background_image'])) {
            $replacement = $this->pickImage($imageMap, $settings['background_image']['url'] ?? '');
            if ($replacement) {
                $settings['background_image']['url'] = $replacement['url'];
                if (isset($replacement['id'])) {
                    $settings['background_image']['id'] = $replacement['id'];
                }
            }
        }
    }

    /**
     * Replace images in carousel/gallery arrays.
     */
    private function replaceCarouselImages(array &$images, array $imageMap): void
    {
        $galleryImages = array_filter($imageMap, fn($img) => str_contains($img['role'] ?? '', 'gallery'));
        $galleryList = array_values($galleryImages);

        foreach ($images as $i => &$img) {
            if (isset($galleryList[$i])) {
                $img['url'] = $galleryList[$i]['url'];
                if (isset($galleryList[$i]['id'])) {
                    $img['id'] = $galleryList[$i]['id'];
                }
            }
        }
    }

    /**
     * Pick a replacement image based on context.
     */
    private function pickImage(array $imageMap, string $currentUrl): ?array
    {
        if (empty($imageMap)) return null;

        // Try to match by context (hero, about, services, team, etc.)
        $urlLower = strtolower($currentUrl);

        $contextMap = [
            'hero' => ['hero', 'banner', 'slider', 'main'],
            'about' => ['about', 'story', 'who'],
            'services' => ['service', 'feature', 'what-we'],
            'team' => ['team', 'staff', 'people', 'member'],
            'gallery' => ['gallery', 'portfolio', 'work', 'project'],
            'contact' => ['contact', 'map', 'location'],
        ];

        foreach ($contextMap as $role => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($urlLower, $keyword)) {
                    if (isset($imageMap[$role])) {
                        return $imageMap[$role];
                    }
                    // Try numbered variants
                    for ($i = 1; $i <= 6; $i++) {
                        if (isset($imageMap[$role . $i]) || isset($imageMap[$role . '_' . $i])) {
                            return $imageMap[$role . $i] ?? $imageMap[$role . '_' . $i];
                        }
                    }
                }
            }
        }

        // Default: return hero or first available
        return $imageMap['hero'] ?? reset($imageMap) ?: null;
    }

    /**
     * Generic text replacement for custom/unknown widget types.
     * Scans settings for string values that look like demo text.
     */
    private function genericTextReplace(array &$settings, array $headings, array $paragraphs, string $businessName, int &$textIndex): void
    {
        foreach ($settings as $key => &$value) {
            if (!is_string($value) || strlen($value) < 5) continue;

            // Skip non-text keys
            if (in_array($key, ['_element_id', '_css_classes', 'css_classes', '__dynamic__', '_animation', 'motion_fx_motion_fx_scrolling'])) continue;
            if (str_starts_with($key, '_') && !str_contains($key, 'title') && !str_contains($key, 'text') && !str_contains($key, 'desc')) continue;

            // Text-like keys
            $isTitle = str_contains($key, 'title') || str_contains($key, 'heading') || str_contains($key, 'name') || str_contains($key, 'label');
            $isText = str_contains($key, 'text') || str_contains($key, 'desc') || str_contains($key, 'content') || str_contains($key, 'editor') || str_contains($key, 'info');

            if ($isTitle && !empty($headings)) {
                $idx = $textIndex % count($headings);
                $value = $headings[$idx];
                $textIndex++;
            } elseif ($isText && !empty($paragraphs)) {
                $idx = $textIndex % count($paragraphs);
                $value = $paragraphs[$idx];
                $textIndex++;
            }
        }
    }

    /**
     * Update Elementor data in postmeta.
     */
    private function updateElementorData(\PDO $pdo, int $postId, string $json): void
    {
        $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'");
        $stmt->execute([$json, $postId]);
    }

    /**
     * Extract plain text from Elementor structure for post_content.
     */
    private function extractPlainText(array $elements): string
    {
        $texts = [];
        foreach ($elements as $element) {
            $settings = $element['settings'] ?? [];
            foreach (['title', 'editor', 'description_text', 'title_text', 'testimonial_content'] as $key) {
                if (isset($settings[$key]) && is_string($settings[$key]) && strlen($settings[$key]) > 3) {
                    $texts[] = strip_tags($settings[$key]);
                }
            }
            if (!empty($element['elements'])) {
                $sub = $this->extractPlainText($element['elements']);
                if ($sub) $texts[] = $sub;
            }
        }
        return implode("\n\n", $texts);
    }

    /**
     * Update page titles based on AI content or business name.
     */
    private function updatePageTitles(\PDO $pdo, array $aiContent, string $businessName): void
    {
        $titleMap = $aiContent['page_titles'] ?? [];

        // Default title mappings if AI doesn't provide them
        if (empty($titleMap) && $businessName) {
            $titleMap = [
                'home' => $businessName . ' - Home',
                'about' => 'About ' . $businessName,
                'contact' => 'Contact ' . $businessName,
                'services' => 'Our Services',
            ];
        }

        foreach ($titleMap as $slug => $title) {
            $pdo->prepare("UPDATE wp_posts SET post_title = ? WHERE post_name LIKE ? AND post_type = 'page'")
                ->execute([$title, '%' . $slug . '%']);
        }

        // Update site title
        if ($businessName) {
            $this->setOption($pdo, 'blogname', $businessName);
        }
    }

    /**
     * Replace images globally in all Elementor data.
     * Scans for demo image URLs (external domains) and replaces with our images.
     */
    private function replaceImagesGlobally(\PDO $pdo, array $imageMap): void
    {
        if (empty($imageMap)) return;

        // Get the site URL to identify local images
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = 'siteurl'");
        $stmt->execute();
        $siteUrl = $stmt->fetchColumn() ?: 'http://localhost';

        // Build a list of our new image URLs
        $heroUrl = $imageMap['hero']['url'] ?? null;
        $aboutUrl = $imageMap['about']['url'] ?? null;
        $servicesUrl = $imageMap['services']['url'] ?? null;
        $galleryUrls = [];
        foreach ($imageMap as $role => $img) {
            if (str_starts_with($role, 'gallery') && isset($img['url'])) {
                $galleryUrls[] = $img['url'];
            }
        }

        // Get all Elementor data with external image URLs
        $stmt = $pdo->query("
            SELECT pm.meta_id, pm.meta_value
            FROM wp_postmeta pm
            WHERE pm.meta_key = '_elementor_data'
              AND pm.meta_value LIKE '%\"url\"%'
        ");

        $imageIndex = 0;
        $allImageUrls = array_filter(array_column($imageMap, 'url'));
        if (empty($allImageUrls)) return;

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = $row['meta_value'];
            $changed = false;

            // Replace external image URLs with our images (round-robin)
            $data = preg_replace_callback(
                '/"url"\s*:\s*"(https?:\/\/[^"]+\.(?:jpg|jpeg|png|webp|gif))"/i',
                function ($match) use ($siteUrl, $allImageUrls, &$imageIndex, &$changed) {
                    $originalUrl = $match[1];
                    // Skip if it's already our site URL
                    if (str_starts_with($originalUrl, $siteUrl)) {
                        return $match[0];
                    }
                    // Skip placeholder/elementor default images
                    if (str_contains($originalUrl, 'placeholder') || str_contains($originalUrl, 'elementor')) {
                        return $match[0];
                    }
                    $changed = true;
                    $newUrl = $allImageUrls[$imageIndex % count($allImageUrls)];
                    $imageIndex++;
                    return '"url":"' . $newUrl . '"';
                },
                $data
            );

            if ($changed) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")
                    ->execute([$data, $row['meta_id']]);
            }
        }

        Log::info("ContentSwapper: Replaced {$imageIndex} external image URLs globally");
    }

    /**
     * Update nav menu item labels to match page titles and business name.
     */
    private function updateNavMenus(\PDO $pdo, string $businessName): void
    {
        // Update custom "Home" menu items to show business name
        $pdo->prepare("
            UPDATE wp_posts SET post_title = ?
            WHERE post_type = 'nav_menu_item'
              AND ID IN (
                  SELECT post_id FROM wp_postmeta
                  WHERE meta_key = '_menu_item_type' AND meta_value = 'custom'
              )
              AND (LOWER(post_title) LIKE '%home%' OR LOWER(post_title) LIKE '%main%')
        ")->execute([$businessName]);

        // Update page-type menu items to match their linked page titles
        $stmt = $pdo->query("
            SELECT mi.ID as menu_item_id, pm.meta_value as page_id
            FROM wp_posts mi
            JOIN wp_postmeta pm ON mi.ID = pm.post_id AND pm.meta_key = '_menu_item_object_id'
            JOIN wp_postmeta pm2 ON mi.ID = pm2.post_id AND pm2.meta_key = '_menu_item_type' AND pm2.meta_value = 'post_type'
            WHERE mi.post_type = 'nav_menu_item'
        ");

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $pageStmt = $pdo->prepare("SELECT post_title FROM wp_posts WHERE ID = ? AND post_type = 'page'");
            $pageStmt->execute([$row['page_id']]);
            $pageTitle = $pageStmt->fetchColumn();

            if ($pageTitle) {
                $pdo->prepare("UPDATE wp_posts SET post_title = ? WHERE ID = ?")
                    ->execute([$pageTitle, $row['menu_item_id']]);
            }
        }

        Log::info("ContentSwapper: Updated nav menu items");
    }

    /**
     * Update theme options (Redux/Codestar) with business-specific content.
     */
    private function updateThemeOptions(\PDO $pdo, string $businessName, array $aiContent): void
    {
        // Common theme option names (covers existing + new envato themes)
        $optionNames = [
            'barab', 'transland', 'theme_options', 'geoport_options', 'redux_options',
            'flavor_medical', 'flavor_law', 'flavor_beauty', 'flavor_realestate',
            'flavor_education', 'flavor_fitness', 'flavor_tech', 'flavor_hotel', 'flavor_shop',
        ];

        foreach ($optionNames as $optionName) {
            $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = ?");
            $stmt->execute([$optionName]);
            $value = $stmt->fetchColumn();

            if (!$value) continue;

            $data = @unserialize($value);
            if (!is_array($data)) continue;

            // Replace footer text, copyright, etc.
            foreach ($data as $key => &$val) {
                if (!is_string($val)) continue;

                if (str_contains($key, 'copyright') || str_contains($key, 'footer_text')) {
                    $val = '© ' . date('Y') . ' ' . $businessName . '. All Rights Reserved.';
                }
                if (str_contains($key, 'site_title') || $key === 'blogname') {
                    $val = $businessName;
                }
                if (str_contains($key, 'phone') && isset($aiContent['phone'])) {
                    $val = $aiContent['phone'];
                }
                if (str_contains($key, 'email') && isset($aiContent['email'])) {
                    $val = $aiContent['email'];
                }
                if (str_contains($key, 'address') && isset($aiContent['address'])) {
                    $val = $aiContent['address'];
                }
            }

            $this->setOption($pdo, $optionName, serialize($data));
        }
    }

    private function setOption(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT option_id FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);

        if ($stmt->fetchColumn()) {
            $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?")->execute([$value, $key]);
        } else {
            $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')")->execute([$key, $value]);
        }
    }

    private function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
