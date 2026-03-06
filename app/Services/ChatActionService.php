<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Log;

class ChatActionService
{
    public function __construct(
        private WordPressService $wordpressService,
    ) {}

    public function executeActions(Website $website, array $actions): array
    {
        $results = [];
        $dbName = $website->wp_db_name;

        if (!$dbName) {
            return [['action' => 'error', 'result' => 'No database found for website']];
        }

        foreach ($actions as $action) {
            $type = $action['type'] ?? '';
            try {
                $result = match ($type) {
                    'update_site_title' => $this->updateSiteTitle($website, $action),
                    'update_tagline' => $this->updateTagline($website, $action),
                    'update_hero_text' => $this->updateHeroText($dbName, $action),
                    'change_colors' => $this->changeColors($dbName, $website, $action),
                    'update_page_content' => $this->updatePageContent($dbName, $action),
                    'add_page' => $this->addPage($website, $action),
                    'delete_page' => $this->deletePage($dbName, $action),
                    'change_button_text' => $this->changeButtonText($dbName, $action),
                    // ── ADVANCED ACTIONS ─────────────────────────────────
                    'update_section_content' => $this->updateSectionContent($dbName, $action),
                    'add_section' => $this->addSection($dbName, $action),
                    'remove_section' => $this->removeSection($dbName, $action),
                    'reorder_sections' => $this->reorderSections($dbName, $action),
                    'change_font' => $this->changeFont($dbName, $website, $action),
                    'update_image' => $this->updateImage($dbName, $website, $action),
                    'add_testimonial' => $this->addTestimonial($dbName, $action),
                    'update_pricing' => $this->updatePricing($dbName, $action),
                    'add_animation' => $this->addAnimation($dbName, $action),
                    'update_seo' => $this->updateSeo($website, $action),
                    'generate_section_content' => $this->generateSectionContent($dbName, $website, $action),
                    'update_menu' => $this->updateMenu($dbName, $action),
                    'change_background' => $this->changeBackground($dbName, $action),
                    'update_footer' => $this->updateFooter($dbName, $action),
                    'add_social_links' => $this->addSocialLinks($dbName, $action),
                    'update_contact_info' => $this->updateContactInfo($dbName, $website, $action),
                    'toggle_section_visibility' => $this->toggleSectionVisibility($dbName, $action),
                    'duplicate_page' => $this->duplicatePage($dbName, $action),
                    'inject_custom_css' => $this->injectCustomCss($website, $action),
                    'inject_custom_js' => $this->injectCustomJs($website, $action),
                    'update_logo_text' => $this->updateLogoText($dbName, $website, $action),
                    'change_heading_style' => $this->changeHeadingStyle($dbName, $action),
                    'update_all_buttons' => $this->updateAllButtons($dbName, $action),
                    'replace_all_images' => $this->replaceAllImages($dbName, $website, $action),
                    default => ['action' => $type, 'result' => 'unknown_action'],
                };
                $results[] = $result;
            } catch (\Exception $e) {
                Log::warning("Chat action '{$type}' failed: {$e->getMessage()}");
                $results[] = ['action' => $type, 'result' => 'error', 'detail' => $e->getMessage()];
            }
        }

        return $results;
    }

    // ── BASIC ACTIONS ──────────────────────────────────────────────

    private function updateSiteTitle(Website $website, array $action): array
    {
        $this->wordpressService->updateOption($website, 'blogname', $action['value'] ?? '');
        return ['action' => 'update_site_title', 'result' => 'success', 'detail' => "Site title updated to: {$action['value']}"];
    }

    private function updateTagline(Website $website, array $action): array
    {
        $this->wordpressService->updateOption($website, 'blogdescription', $action['value'] ?? '');
        return ['action' => 'update_tagline', 'result' => 'success', 'detail' => "Tagline updated"];
    }

    private function updateHeroText(string $dbName, array $action): array
    {
        $pages = $this->wordpressService->getPages($dbName);
        $homePageId = $this->findHomePage($pages);
        if (!$homePageId) return ['action' => 'update_hero_text', 'result' => 'failed', 'detail' => 'Home page not found'];

        $data = $this->wordpressService->getPageElementorData($dbName, $homePageId);
        if (!$data) return ['action' => 'update_hero_text', 'result' => 'failed'];

        $updated = false;
        if (isset($action['title'])) {
            $updated = $this->findAndUpdateWidget($data, 'heading', 'title', $action['title']) || $updated;
        }
        if (isset($action['subtitle'])) {
            $updated = $this->findAndUpdateWidget($data, 'text-editor', 'editor', '<p>' . e($action['subtitle']) . '</p>') || $updated;
        }
        if (isset($action['cta'])) {
            $updated = $this->findAndUpdateWidget($data, 'button', 'text', $action['cta']) || $updated;
        }
        if ($updated) $this->wordpressService->updatePageElementorData($dbName, $homePageId, $data);
        return ['action' => 'update_hero_text', 'result' => $updated ? 'success' : 'failed'];
    }

    private function changeColors(string $dbName, Website $website, array $action): array
    {
        $colors = array_filter(['primary' => $action['primary'] ?? null, 'secondary' => $action['secondary'] ?? null, 'accent' => $action['accent'] ?? null]);
        if (empty($colors)) return ['action' => 'change_colors', 'result' => 'failed', 'detail' => 'No colors provided'];

        $pdo = $this->getPdo($dbName);
        $this->updateElementorGlobalColors($pdo, $colors);
        $this->updateColorsInAllPages($pdo, $colors);
        return ['action' => 'change_colors', 'result' => 'success', 'detail' => 'Colors updated: ' . json_encode($colors)];
    }

    private function updatePageContent(string $dbName, array $action): array
    {
        $postId = (int) ($action['page_id'] ?? 0);
        $newContent = $action['content'] ?? '';
        if (!$postId || !$newContent) return ['action' => 'update_page_content', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $postId);
        if (!$data) return ['action' => 'update_page_content', 'result' => 'failed'];

        $this->updateTextInElementorData($data, $newContent);
        $this->wordpressService->updatePageElementorData($dbName, $postId, $data);
        return ['action' => 'update_page_content', 'result' => 'success'];
    }

    private function addPage(Website $website, array $action): array
    {
        $title = $action['title'] ?? 'New Page';
        $result = $this->wordpressService->createPage($website, $title, $action['content'] ?? '');
        return ['action' => 'add_page', 'result' => ($result['success'] ?? false) ? 'success' : 'failed', 'detail' => "Page '{$title}' created"];
    }

    private function deletePage(string $dbName, array $action): array
    {
        $postId = (int) ($action['page_id'] ?? 0);
        if (!$postId) return ['action' => 'delete_page', 'result' => 'failed'];
        $this->getPdo($dbName)->prepare("UPDATE wp_posts SET post_status='trash' WHERE ID = ? AND post_type='page'")->execute([$postId]);
        return ['action' => 'delete_page', 'result' => 'success', 'detail' => "Page {$postId} trashed"];
    }

    private function changeButtonText(string $dbName, array $action): array
    {
        $postId = (int) ($action['page_id'] ?? 0);
        $newText = $action['text'] ?? '';
        if (!$postId || !$newText) return ['action' => 'change_button_text', 'result' => 'failed'];
        $data = $this->wordpressService->getPageElementorData($dbName, $postId);
        if (!$data) return ['action' => 'change_button_text', 'result' => 'failed'];
        $this->updateButtonTextInData($data, $newText);
        $this->wordpressService->updatePageElementorData($dbName, $postId, $data);
        return ['action' => 'change_button_text', 'result' => 'success'];
    }

    // ── SECTION ACTIONS ────────────────────────────────────────────

    private function updateSectionContent(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $sectionIndex = (int) ($action['section_index'] ?? 0);
        $content = $action['content'] ?? [];
        if (!$pageId) return ['action' => 'update_section_content', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data || !isset($data[$sectionIndex])) return ['action' => 'update_section_content', 'result' => 'failed'];

        $section = &$data[$sectionIndex];
        if (isset($content['title'])) $this->findAndUpdateWidgetInTree($section, 'heading', 'title', $content['title']);
        if (isset($content['subtitle'])) $this->findAndUpdateWidgetInTree($section, 'text-editor', 'editor', '<p>' . e($content['subtitle']) . '</p>');
        if (isset($content['button_text'])) $this->findAndUpdateWidgetInTree($section, 'button', 'text', $content['button_text']);
        if (isset($content['items'])) $this->updateSectionItems($section, $content['items']);

        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'update_section_content', 'result' => 'success'];
    }

    private function addSection(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $sectionType = $action['section_type'] ?? 'content';
        $position = $action['position'] ?? 'end';
        $content = $action['content'] ?? [];
        if (!$pageId) return ['action' => 'add_section', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data) return ['action' => 'add_section', 'result' => 'failed'];

        $newSection = $this->buildElementorSection($sectionType, $content);
        if ($position === 'start') array_unshift($data, $newSection);
        elseif (is_numeric($position)) array_splice($data, (int) $position, 0, [$newSection]);
        else $data[] = $newSection;

        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'add_section', 'result' => 'success', 'detail' => "Added {$sectionType} section"];
    }

    private function removeSection(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $sectionIndex = (int) ($action['section_index'] ?? -1);
        if (!$pageId || $sectionIndex < 0) return ['action' => 'remove_section', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data || !isset($data[$sectionIndex])) return ['action' => 'remove_section', 'result' => 'failed'];

        array_splice($data, $sectionIndex, 1);
        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'remove_section', 'result' => 'success'];
    }

    private function reorderSections(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $order = $action['order'] ?? [];
        if (!$pageId || empty($order)) return ['action' => 'reorder_sections', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data) return ['action' => 'reorder_sections', 'result' => 'failed'];

        $newData = [];
        foreach ($order as $idx) { if (isset($data[$idx])) $newData[] = $data[$idx]; }
        for ($i = 0; $i < count($data); $i++) { if (!in_array($i, $order)) $newData[] = $data[$i]; }

        $this->wordpressService->updatePageElementorData($dbName, $pageId, $newData);
        return ['action' => 'reorder_sections', 'result' => 'success'];
    }

    // ── TYPOGRAPHY ACTIONS ─────────────────────────────────────────

    private function changeFont(string $dbName, Website $website, array $action): array
    {
        $headingFont = $action['heading_font'] ?? null;
        $bodyFont = $action['body_font'] ?? null;
        if (!$headingFont && !$bodyFont) return ['action' => 'change_font', 'result' => 'failed'];

        $pdo = $this->getPdo($dbName);
        if ($headingFont) $this->updateFontInAllPages($pdo, $dbName, 'heading', $headingFont);

        $details = [];
        if ($headingFont) $details[] = "heading: {$headingFont}";
        if ($bodyFont) $details[] = "body: {$bodyFont}";
        return ['action' => 'change_font', 'result' => 'success', 'detail' => 'Fonts updated: ' . implode(', ', $details)];
    }

    private function changeHeadingStyle(string $dbName, array $action): array
    {
        $style = $action['style'] ?? '';
        $pdo = $this->getPdo($dbName);

        $stmt = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'");
        $count = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = json_decode($row['meta_value'], true);
            if (!$data) continue;
            if ($this->applyHeadingStyleRecursive($data, $style)) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")
                    ->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $row['meta_id']]);
                $count++;
            }
        }
        return ['action' => 'change_heading_style', 'result' => 'success', 'detail' => "Applied '{$style}' to {$count} pages"];
    }

    // ── IMAGE ACTIONS ──────────────────────────────────────────────

    private function updateImage(string $dbName, Website $website, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $imageUrl = $action['url'] ?? '';
        if (!$pageId || !$imageUrl) return ['action' => 'update_image', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data) return ['action' => 'update_image', 'result' => 'failed'];

        $idx = (int) ($action['widget_index'] ?? 0);
        $widgets = [];
        $this->findWidgetsByType($data, 'image', $widgets);
        if (isset($widgets[$idx]) && isset($widgets[$idx]['settings']['image'])) {
            $widgets[$idx]['settings']['image']['url'] = $imageUrl;
            $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
            return ['action' => 'update_image', 'result' => 'success'];
        }
        return ['action' => 'update_image', 'result' => 'failed'];
    }

    private function replaceAllImages(string $dbName, Website $website, array $action): array
    {
        $category = $action['category'] ?? $website->ai_business_type ?? 'business';
        $pdo = $this->getPdo($dbName);

        try {
            $unsplash = app(UnsplashService::class);
            $htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
            $uploadsDir = $htdocsPath . '/' . $website->subdomain . '/wp-content/uploads/' . date('Y/m');
            $uploadsUrl = $website->url . '/wp-content/uploads/' . date('Y/m');

            $images = $unsplash->getWebsiteImages($website->name, $category, $uploadsDir);
            $imageUrls = [];
            foreach ($images as $key => $path) {
                if ($path && file_exists($path)) $imageUrls[] = $uploadsUrl . '/' . basename($path);
            }
            if (empty($imageUrls)) return ['action' => 'replace_all_images', 'result' => 'failed'];

            $stmt = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'");
            $count = 0;
            $imgIdx = 0;
            $siteUrl = $website->url;

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $d = $row['meta_value'];
                $newD = preg_replace_callback('/"url"\s*:\s*"(https?:\/\/[^"]+\.(?:jpg|jpeg|png|webp))"/i', function ($m) use ($imageUrls, &$imgIdx, &$count, $siteUrl) {
                    if (str_starts_with($m[1], $siteUrl)) return $m[0];
                    $count++;
                    return '"url":"' . $imageUrls[$imgIdx++ % count($imageUrls)] . '"';
                }, $d);
                if ($newD !== $d) $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")->execute([$newD, $row['meta_id']]);
            }
            return ['action' => 'replace_all_images', 'result' => 'success', 'detail' => "Replaced {$count} images"];
        } catch (\Exception $e) {
            return ['action' => 'replace_all_images', 'result' => 'failed', 'detail' => $e->getMessage()];
        }
    }

    // ── TESTIMONIAL & PRICING ──────────────────────────────────────

    private function addTestimonial(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        if (!$pageId) { $pages = $this->wordpressService->getPages($dbName); $pageId = $this->findHomePage($pages); }
        if (!$pageId) return ['action' => 'add_testimonial', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data) return ['action' => 'add_testimonial', 'result' => 'failed'];

        $name = $action['name'] ?? 'Happy Customer';
        $newSection = $this->buildElementorSection('testimonials', [
            'title' => 'What Our Clients Say',
            'items' => [
                ['title' => $name, 'description' => $action['content'] ?? 'Great service!'],
            ],
        ]);
        $data[] = $newSection;
        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'add_testimonial', 'result' => 'success', 'detail' => "Testimonial from {$name} added"];
    }

    private function updatePricing(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $plans = $action['plans'] ?? [];
        if (!$pageId || empty($plans)) return ['action' => 'update_pricing', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data) return ['action' => 'update_pricing', 'result' => 'failed'];

        $planIdx = 0;
        $this->updatePricingRecursive($data, $plans, $planIdx);
        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'update_pricing', 'result' => 'success'];
    }

    // ── ANIMATION ──────────────────────────────────────────────────

    private function addAnimation(string $dbName, array $action): array
    {
        $animation = $action['animation'] ?? 'fadeInUp';
        $target = $action['target'] ?? 'all';
        $pdo = $this->getPdo($dbName);
        $pageId = (int) ($action['page_id'] ?? 0);

        $query = $pageId
            ? "SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data' AND post_id = ?"
            : "SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'";
        $stmt = $pdo->prepare($query);
        $stmt->execute($pageId ? [$pageId] : []);

        $count = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = json_decode($row['meta_value'], true);
            if (!$data) continue;
            if ($this->applyAnimationsRecursive($data, $animation, $target)) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")
                    ->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $row['meta_id']]);
                $count++;
            }
        }
        return ['action' => 'add_animation', 'result' => 'success', 'detail' => "Applied '{$animation}' to {$count} pages"];
    }

    // ── SEO ────────────────────────────────────────────────────────

    private function updateSeo(Website $website, array $action): array
    {
        $pageSlug = $action['page_slug'] ?? 'home';
        \App\Models\WebsiteSeoData::updateOrCreate(
            ['website_id' => $website->id, 'page_slug' => $pageSlug],
            array_filter([
                'meta_title' => $action['meta_title'] ?? null,
                'meta_description' => $action['meta_description'] ?? null,
                'meta_keywords' => $action['keywords'] ?? null,
                'og_title' => $action['og_title'] ?? null,
                'og_description' => $action['og_description'] ?? null,
            ])
        );

        $dbName = $website->wp_db_name;
        if ($dbName) {
            $pdo = $this->getPdo($dbName);
            $pid = $this->findPageBySlug($pdo, $pageSlug);
            if ($pid) {
                if (isset($action['meta_title'])) $this->setPostMeta($pdo, $pid, '_yoast_wpseo_title', $action['meta_title']);
                if (isset($action['meta_description'])) $this->setPostMeta($pdo, $pid, '_yoast_wpseo_metadesc', $action['meta_description']);
            }
        }
        return ['action' => 'update_seo', 'result' => 'success', 'detail' => "SEO updated for '{$pageSlug}'"];
    }

    // ── AI CONTENT GENERATION ──────────────────────────────────────

    private function generateSectionContent(string $dbName, Website $website, array $action): array
    {
        $sectionType = $action['section_type'] ?? 'features';
        $instructions = $action['instructions'] ?? '';

        $aiService = app(AIContentService::class);
        $prompt = "Generate content for a '{$sectionType}' section on a {$website->ai_business_type} website called '{$website->name}'.";
        if ($instructions) $prompt .= " Instructions: {$instructions}";
        $prompt .= "\nReturn JSON: {\"title\":\"...\",\"subtitle\":\"...\",\"items\":[{\"title\":\"...\",\"description\":\"...\"}]}\nReturn ONLY valid JSON.";

        $result = $aiService->quickComplete($prompt);
        if (!$result) return ['action' => 'generate_section_content', 'result' => 'failed', 'detail' => 'AI failed'];

        $content = json_decode($result, true);
        if (!$content) { preg_match('/\{.*\}/s', $result, $m); $content = json_decode($m[0] ?? '', true); }
        if (!$content) return ['action' => 'generate_section_content', 'result' => 'failed'];

        $pageId = (int) ($action['page_id'] ?? 0);
        if (!$pageId) { $pages = $this->wordpressService->getPages($dbName); $pageId = $this->findHomePage($pages); }

        return $this->addSection($dbName, ['page_id' => $pageId, 'section_type' => $sectionType, 'content' => $content]);
    }

    // ── MENU & LAYOUT ──────────────────────────────────────────────

    private function updateMenu(string $dbName, array $action): array
    {
        $items = $action['items'] ?? [];
        if (empty($items)) return ['action' => 'update_menu', 'result' => 'failed'];
        $pdo = $this->getPdo($dbName);
        foreach ($items as $item) {
            if (($item['id'] ?? 0) && ($item['title'] ?? '')) {
                $pdo->prepare("UPDATE wp_posts SET post_title = ? WHERE ID = ? AND post_type = 'nav_menu_item'")->execute([$item['title'], $item['id']]);
            }
        }
        return ['action' => 'update_menu', 'result' => 'success'];
    }

    private function changeBackground(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $sectionIndex = (int) ($action['section_index'] ?? 0);
        if (!$pageId) return ['action' => 'change_background', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data || !isset($data[$sectionIndex])) return ['action' => 'change_background', 'result' => 'failed'];

        $s = &$data[$sectionIndex]['settings'];
        if ($color = $action['color'] ?? null) { $s['background_background'] = 'classic'; $s['background_color'] = $color; }
        if ($g = $action['gradient'] ?? null) { $s['background_background'] = 'gradient'; $s['background_color'] = $g['from'] ?? '#000'; $s['background_color_b'] = $g['to'] ?? '#333'; }
        if ($img = $action['image_url'] ?? null) { $s['background_background'] = 'classic'; $s['background_image'] = ['url' => $img, 'id' => 0]; $s['background_size'] = 'cover'; }

        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'change_background', 'result' => 'success'];
    }

    private function updateFooter(string $dbName, array $action): array
    {
        $pdo = $this->getPdo($dbName);
        $stmt = $pdo->query("SELECT p.ID FROM wp_posts p JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='ehf_template_type' AND pm.meta_value='type_footer' WHERE p.post_type='elementor-hf' AND p.post_status='publish' LIMIT 1");
        $footerId = $stmt->fetchColumn();
        if (!$footerId) return ['action' => 'update_footer', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $footerId);
        if (!$data) return ['action' => 'update_footer', 'result' => 'failed'];

        if (isset($action['copyright'])) $this->findAndUpdateAllWidgets($data, 'text-editor', 'editor', $action['copyright']);
        if (isset($action['heading'])) $this->findAndUpdateWidget($data, 'heading', 'title', $action['heading']);

        $this->wordpressService->updatePageElementorData($dbName, $footerId, $data);
        return ['action' => 'update_footer', 'result' => 'success'];
    }

    private function addSocialLinks(string $dbName, array $action): array
    {
        $links = $action['links'] ?? [];
        if (empty($links)) return ['action' => 'add_social_links', 'result' => 'failed'];
        $pdo = $this->getPdo($dbName);
        $this->setWpOption($pdo, 'webnewbiz_social_links', json_encode($links));
        return ['action' => 'add_social_links', 'result' => 'success', 'detail' => 'Social links saved'];
    }

    private function updateContactInfo(string $dbName, Website $website, array $action): array
    {
        $info = array_filter(['phone' => $action['phone'] ?? null, 'email' => $action['email'] ?? null, 'address' => $action['address'] ?? null, 'hours' => $action['hours'] ?? null]);
        $pdo = $this->getPdo($dbName);

        $existing = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name='webnewbiz_contact_info'");
        $existing->execute();
        $current = json_decode($existing->fetchColumn() ?: '{}', true) ?: [];
        $this->setWpOption($pdo, 'webnewbiz_contact_info', json_encode(array_merge($current, $info)));

        // Update in contact page Elementor data
        $pages = $this->wordpressService->getPages($dbName);
        foreach ($pages as $page) {
            if (str_contains(strtolower($page['post_name'] ?? ''), 'contact')) {
                $data = $this->wordpressService->getPageElementorData($dbName, (int) $page['ID']);
                if ($data) { $this->updateContactInfoInTree($data, $info); $this->wordpressService->updatePageElementorData($dbName, (int) $page['ID'], $data); }
                break;
            }
        }
        return ['action' => 'update_contact_info', 'result' => 'success'];
    }

    private function toggleSectionVisibility(string $dbName, array $action): array
    {
        $pageId = (int) ($action['page_id'] ?? 0);
        $sectionIndex = (int) ($action['section_index'] ?? -1);
        $visible = $action['visible'] ?? true;
        if (!$pageId || $sectionIndex < 0) return ['action' => 'toggle_section_visibility', 'result' => 'failed'];

        $data = $this->wordpressService->getPageElementorData($dbName, $pageId);
        if (!$data || !isset($data[$sectionIndex])) return ['action' => 'toggle_section_visibility', 'result' => 'failed'];

        if ($visible) unset($data[$sectionIndex]['settings']['_css_classes']);
        else $data[$sectionIndex]['settings']['_css_classes'] = 'elementor-hidden-desktop elementor-hidden-tablet elementor-hidden-phone';

        $this->wordpressService->updatePageElementorData($dbName, $pageId, $data);
        return ['action' => 'toggle_section_visibility', 'result' => 'success', 'detail' => $visible ? 'Section shown' : 'Section hidden'];
    }

    private function duplicatePage(string $dbName, array $action): array
    {
        $sourceId = (int) ($action['page_id'] ?? 0);
        if (!$sourceId) return ['action' => 'duplicate_page', 'result' => 'failed'];

        $pdo = $this->getPdo($dbName);
        $stmt = $pdo->prepare("SELECT * FROM wp_posts WHERE ID = ?");
        $stmt->execute([$sourceId]);
        $src = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$src) return ['action' => 'duplicate_page', 'result' => 'failed'];

        $title = ($action['new_title'] ?? '') ?: $src['post_title'] . ' (Copy)';
        $pdo->prepare("INSERT INTO wp_posts (post_author,post_date,post_date_gmt,post_content,post_title,post_excerpt,post_status,post_name,post_modified,post_modified_gmt,post_parent,post_type,comment_status,ping_status,to_ping,pinged,post_content_filtered) VALUES (1,NOW(),NOW(),?,?,'','publish',?,NOW(),NOW(),0,'page','closed','closed','','','')")
            ->execute([$src['post_content'], $title, strtolower(str_replace(' ', '-', $title))]);
        $newId = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO wp_postmeta (post_id,meta_key,meta_value) SELECT ?,meta_key,meta_value FROM wp_postmeta WHERE post_id=?")->execute([$newId, $sourceId]);

        return ['action' => 'duplicate_page', 'result' => 'success', 'detail' => "Duplicated as '{$title}' (ID: {$newId})", 'new_page_id' => $newId];
    }

    // ── CSS/JS INJECTION ───────────────────────────────────────────

    private function injectCustomCss(Website $website, array $action): array
    {
        $css = $action['css'] ?? '';
        $mode = $action['mode'] ?? 'append';
        if ($mode === 'append' && $website->custom_css) $css = $website->custom_css . "\n" . $css;
        $website->update(['custom_css' => $css]);
        $this->writeCustomCodeToWp($website, $css, 'css');
        return ['action' => 'inject_custom_css', 'result' => 'success'];
    }

    private function injectCustomJs(Website $website, array $action): array
    {
        $js = $action['js'] ?? '';
        $mode = $action['mode'] ?? 'append';
        if ($mode === 'append' && $website->custom_js) $js = $website->custom_js . "\n" . $js;
        $website->update(['custom_js' => $js]);
        $this->writeCustomCodeToWp($website, $js, 'js');
        return ['action' => 'inject_custom_js', 'result' => 'success'];
    }

    private function updateLogoText(string $dbName, Website $website, array $action): array
    {
        $text = $action['text'] ?? $website->name;
        $pdo = $this->getPdo($dbName);

        $stmt = $pdo->query("SELECT p.ID FROM wp_posts p JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='ehf_template_type' AND pm.meta_value='type_header' WHERE p.post_type='elementor-hf' AND p.post_status='publish' LIMIT 1");
        $headerId = $stmt->fetchColumn();
        if ($headerId) {
            $data = $this->wordpressService->getPageElementorData($dbName, $headerId);
            if ($data) {
                $this->findAndUpdateWidget($data, 'heading', 'title', $text) || $this->findAndUpdateWidget($data, 'hfe-site-title', 'heading', $text);
                $this->wordpressService->updatePageElementorData($dbName, $headerId, $data);
            }
        }
        $this->wordpressService->updateOption($website, 'blogname', $text);
        return ['action' => 'update_logo_text', 'result' => 'success', 'detail' => "Logo text: {$text}"];
    }

    private function updateAllButtons(string $dbName, array $action): array
    {
        $text = $action['text'] ?? '';
        $color = $action['color'] ?? null;
        $url = $action['url'] ?? null;
        if (!$text && !$color && !$url) return ['action' => 'update_all_buttons', 'result' => 'failed'];

        $pdo = $this->getPdo($dbName);
        $stmt = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'");
        $count = 0;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = json_decode($row['meta_value'], true);
            if (!$data) continue;
            if ($this->updateAllButtonsRecursive($data, $text, $color, $url)) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?")->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $row['meta_id']]);
                $count++;
            }
        }
        return ['action' => 'update_all_buttons', 'result' => 'success', 'detail' => "Updated buttons on {$count} pages"];
    }

    // ══════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ══════════════════════════════════════════════════════════════

    private function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO("mysql:host=" . config('database.connections.mysql.host', '127.0.0.1') . ";dbname={$dbName}", config('database.connections.mysql.username', 'root'), config('database.connections.mysql.password', ''));
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    private function findHomePage(array $pages): ?int
    {
        foreach ($pages as $p) { if (in_array($p['post_name'] ?? '', ['home', 'homepage', 'front-page'])) return (int) $p['ID']; }
        return !empty($pages) ? (int) $pages[0]['ID'] : null;
    }

    private function findPageBySlug(\PDO $pdo, string $slug): ?int
    {
        $stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_name=? AND post_type='page' AND post_status='publish' LIMIT 1");
        $stmt->execute([$slug]);
        $id = $stmt->fetchColumn();
        return $id ? (int) $id : null;
    }

    private function getElementorKitId(\PDO $pdo): ?int
    {
        $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name='elementor_active_kit'");
        $id = $stmt->fetchColumn();
        if (!$id) { $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type='elementor_library' AND post_name LIKE '%kit%' LIMIT 1"); $id = $stmt->fetchColumn(); }
        return $id ? (int) $id : null;
    }

    private function setPostMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT meta_id FROM wp_postmeta WHERE post_id=? AND meta_key=?");
        $stmt->execute([$postId, $key]);
        if ($stmt->fetchColumn()) $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key=?")->execute([$value, $postId, $key]);
        else $pdo->prepare("INSERT INTO wp_postmeta (post_id,meta_key,meta_value) VALUES (?,?,?)")->execute([$postId, $key, $value]);
    }

    private function setWpOption(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT option_id FROM wp_options WHERE option_name=?");
        $stmt->execute([$key]);
        if ($stmt->fetchColumn()) $pdo->prepare("UPDATE wp_options SET option_value=? WHERE option_name=?")->execute([$value, $key]);
        else $pdo->prepare("INSERT INTO wp_options (option_name,option_value,autoload) VALUES (?,?,'yes')")->execute([$key, $value]);
    }

    private function findAndUpdateWidget(array &$data, string $widgetType, string $key, string $value): bool
    {
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === $widgetType && isset($el['settings'][$key])) { $el['settings'][$key] = $value; return true; }
            if (isset($el['elements']) && $this->findAndUpdateWidget($el['elements'], $widgetType, $key, $value)) return true;
        }
        return false;
    }

    private function findAndUpdateWidgetInTree(array &$tree, string $widgetType, string $key, string $value): bool
    {
        $arr = isset($tree['elements']) ? $tree['elements'] : [$tree];
        return $this->findAndUpdateWidget($arr, $widgetType, $key, $value);
    }

    private function findAndUpdateAllWidgets(array &$data, string $widgetType, string $key, string $value): int
    {
        $c = 0;
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === $widgetType && isset($el['settings'][$key])) { $el['settings'][$key] = $value; $c++; }
            if (isset($el['elements'])) $c += $this->findAndUpdateAllWidgets($el['elements'], $widgetType, $key, $value);
        }
        return $c;
    }

    private function findWidgetsByType(array &$data, string $widgetType, array &$results): void
    {
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === $widgetType) $results[] = &$el;
            if (isset($el['elements'])) $this->findWidgetsByType($el['elements'], $widgetType, $results);
        }
    }

    private function updateTextInElementorData(array &$data, string $content): void
    {
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'text-editor' && isset($el['settings']['editor'])) { $el['settings']['editor'] = '<p>' . e($content) . '</p>'; return; }
            if (isset($el['elements'])) $this->updateTextInElementorData($el['elements'], $content);
        }
    }

    private function updateButtonTextInData(array &$data, string $text): void
    {
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'button' && isset($el['settings']['text'])) { $el['settings']['text'] = $text; return; }
            if (isset($el['elements'])) $this->updateButtonTextInData($el['elements'], $text);
        }
    }

    private function updateSectionItems(array &$section, array $items): void
    {
        $idx = 0;
        $this->updateItemsRecursive($section, $items, $idx);
    }

    private function updateItemsRecursive(array &$el, array $items, int &$idx): void
    {
        if (isset($el['elements'])) {
            foreach ($el['elements'] as &$child) {
                $wt = $child['widgetType'] ?? '';
                if (in_array($wt, ['icon-box', 'image-box', 'heading', 'text-editor']) && $idx < count($items)) {
                    $item = $items[$idx];
                    if (isset($item['title'], $child['settings']['title_text'])) $child['settings']['title_text'] = $item['title'];
                    if (isset($item['title'], $child['settings']['title'])) $child['settings']['title'] = $item['title'];
                    if (isset($item['description'], $child['settings']['description_text'])) $child['settings']['description_text'] = $item['description'];
                    if (isset($item['description'], $child['settings']['editor'])) $child['settings']['editor'] = '<p>' . e($item['description']) . '</p>';
                    $idx++;
                }
                if (isset($child['elements'])) $this->updateItemsRecursive($child, $items, $idx);
            }
        }
    }

    private function buildElementorSection(string $type, array $content): array
    {
        $sid = substr(md5(uniqid()), 0, 7);
        $cid = substr(md5(uniqid() . '1'), 0, 7);
        $widgets = [];

        $widgets[] = ['id' => substr(md5(uniqid() . '2'), 0, 7), 'elType' => 'widget', 'widgetType' => 'heading', 'settings' => ['title' => $content['title'] ?? ucfirst($type), 'align' => 'center', 'header_size' => 'h2'], 'elements' => []];

        if (isset($content['subtitle'])) {
            $widgets[] = ['id' => substr(md5(uniqid() . '3'), 0, 7), 'elType' => 'widget', 'widgetType' => 'text-editor', 'settings' => ['editor' => '<p style="text-align:center;">' . e($content['subtitle']) . '</p>'], 'elements' => []];
        }

        foreach ($content['items'] ?? [] as $item) {
            $widgets[] = ['id' => substr(md5(uniqid() . rand()), 0, 7), 'elType' => 'widget', 'widgetType' => 'icon-box', 'settings' => ['title_text' => $item['title'] ?? '', 'description_text' => $item['description'] ?? '', 'selected_icon' => ['value' => 'fas fa-star', 'library' => 'fa-solid']], 'elements' => []];
        }

        return ['id' => $sid, 'elType' => 'section', 'settings' => ['padding' => ['top' => '60', 'bottom' => '60', 'unit' => 'px']], 'elements' => [['id' => $cid, 'elType' => 'column', 'settings' => ['_column_size' => 100], 'elements' => $widgets]]];
    }

    private function updateElementorGlobalColors(\PDO $pdo, array $colors): void
    {
        $kitId = $this->getElementorKitId($pdo);
        if (!$kitId) return;
        $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_elementor_page_settings'");
        $stmt->execute([$kitId]);
        $settings = json_decode($stmt->fetchColumn() ?: '{}', true);
        if (isset($colors['primary'])) {
            foreach (($settings['system_colors'] ?? []) as &$c) { if (($c['_id'] ?? '') === 'primary') $c['color'] = $colors['primary']; }
        }
        $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key='_elementor_page_settings'")->execute([json_encode($settings, JSON_UNESCAPED_UNICODE), $kitId]);
    }

    private function updateColorsInAllPages(\PDO $pdo, array $colors): void
    {
        $primary = $colors['primary'] ?? null;
        if (!$primary) return;
        $stmt = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $d = $row['meta_value'];
            $nd = preg_replace('/"button_background_color"\s*:\s*"#[a-fA-F0-9]{6}"/', '"button_background_color":"' . $primary . '"', $d);
            if ($nd !== $d) $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE meta_id=?")->execute([$nd, $row['meta_id']]);
        }
    }

    private function updateFontInAllPages(\PDO $pdo, string $dbName, string $widgetType, string $font): void
    {
        $stmt = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key='_elementor_data'");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = json_decode($row['meta_value'], true);
            if ($data && $this->updateFontRecursive($data, $widgetType, $font)) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE meta_id=?")->execute([json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $row['meta_id']]);
            }
        }
    }

    private function updateFontRecursive(array &$data, string $tw, string $font): bool
    {
        $c = false;
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === $tw) { $el['settings']['typography_typography'] = 'custom'; $el['settings']['typography_font_family'] = $font; $c = true; }
            if (isset($el['elements']) && $this->updateFontRecursive($el['elements'], $tw, $font)) $c = true;
        }
        return $c;
    }

    private function applyAnimationsRecursive(array &$data, string $anim, string $target): bool
    {
        $c = false;
        foreach ($data as &$el) {
            $apply = match ($target) { 'sections' => ($el['elType'] ?? '') === 'section', 'widgets' => ($el['elType'] ?? '') === 'widget', default => in_array($el['elType'] ?? '', ['section', 'widget']) };
            if ($apply) { $el['settings']['_animation'] = $anim; $el['settings']['animation_duration'] = 'normal'; $c = true; }
            if (isset($el['elements']) && $this->applyAnimationsRecursive($el['elements'], $anim, $target)) $c = true;
        }
        return $c;
    }

    private function applyHeadingStyleRecursive(array &$data, string $style): bool
    {
        $c = false;
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'heading') {
                match ($style) {
                    'uppercase' => $el['settings']['title_typography_text_transform'] = 'uppercase',
                    'capitalize' => $el['settings']['title_typography_text_transform'] = 'capitalize',
                    'bold' => $el['settings']['title_typography_font_weight'] = '700',
                    'italic' => $el['settings']['title_typography_font_style'] = 'italic',
                    'underline' => $el['settings']['title_typography_text_decoration'] = 'underline',
                    default => null,
                };
                $c = true;
            }
            if (isset($el['elements']) && $this->applyHeadingStyleRecursive($el['elements'], $style)) $c = true;
        }
        return $c;
    }

    private function updatePricingRecursive(array &$data, array $plans, int &$idx): bool
    {
        $c = false;
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'html' && str_contains($el['settings']['html'] ?? '', 'price') && isset($plans[$idx])) {
                $el['settings']['html'] = $this->buildPricingHtml($plans[$idx]);
                $idx++;
                $c = true;
            }
            if (isset($el['elements']) && $this->updatePricingRecursive($el['elements'], $plans, $idx)) $c = true;
        }
        return $c;
    }

    private function buildPricingHtml(array $p): string
    {
        $name = e($p['name'] ?? 'Plan');
        $price = e($p['price'] ?? '$0');
        $period = e($p['period'] ?? '/mo');
        $fl = '';
        foreach ($p['features'] ?? [] as $f) $fl .= '<li style="padding:8px 0;border-bottom:1px solid #eee;">' . e($f) . '</li>';
        $cta = e($p['cta'] ?? 'Get Started');
        return "<div style='text-align:center;padding:30px;border-radius:12px;background:#fff;box-shadow:0 2px 20px rgba(0,0,0,0.08);'><h3 style='font-size:22px;'>{$name}</h3><div style='font-size:36px;font-weight:700;margin:15px 0;'>{$price}<span style='font-size:14px;color:#666;'>{$period}</span></div><ul style='list-style:none;padding:0;'>{$fl}</ul><a href='#contact' style='display:inline-block;padding:12px 30px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;'>{$cta}</a></div>";
    }

    private function updateAllButtonsRecursive(array &$data, string $text, ?string $color, ?string $url): bool
    {
        $c = false;
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'button') {
                if ($text) $el['settings']['text'] = $text;
                if ($color) $el['settings']['button_background_color'] = $color;
                if ($url) $el['settings']['link'] = ['url' => $url, 'is_external' => false, 'nofollow' => false];
                $c = true;
            }
            if (isset($el['elements']) && $this->updateAllButtonsRecursive($el['elements'], $text, $color, $url)) $c = true;
        }
        return $c;
    }

    private function updateContactInfoInTree(array &$data, array $info): void
    {
        foreach ($data as &$el) {
            if (($el['widgetType'] ?? '') === 'text-editor' && isset($el['settings']['editor'])) {
                $e = $el['settings']['editor'];
                if (isset($info['phone']) && preg_match('/[\d\-\(\)\+\s]{7,}/', $e)) $e = preg_replace('/[\d\-\(\)\+\s]{7,}/', $info['phone'], $e);
                if (isset($info['email']) && preg_match('/[\w\.\-]+@[\w\.\-]+/', $e)) $e = preg_replace('/[\w\.\-]+@[\w\.\-]+/', $info['email'], $e);
                $el['settings']['editor'] = $e;
            }
            if (isset($el['elements'])) $this->updateContactInfoInTree($el['elements'], $info);
        }
    }

    private function writeCustomCodeToWp(Website $website, string $code, string $type): void
    {
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $path = $htdocs . '/' . $website->subdomain . "/wp-content/mu-plugins/webnewbiz-custom-{$type}.php";
        $escaped = addslashes($code);
        $hook = $type === 'css' ? 'wp_head' : 'wp_footer';
        $tag = $type === 'css' ? 'style' : 'script';
        @file_put_contents($path, "<?php\nadd_action('{$hook}', function() { echo '<{$tag}>{$escaped}</{$tag}>'; });\n");
    }
}
