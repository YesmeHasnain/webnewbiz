<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WpBridgeService
{
    /**
     * Call the wp-site-manager.php bridge on a WordPress site.
     */
    public function call(Website $website, string $action, array $params = []): array
    {
        $siteUrl = rtrim($website->url, '/');
        $bridgeUrl = "{$siteUrl}/wp-site-manager.php";

        $token = Crypt::decrypt($website->wp_auto_login_token);

        $response = Http::timeout(120)
            ->withHeaders(['X-Bridge-Token' => $token])
            ->asForm()
            ->post($bridgeUrl, array_merge(['action' => $action], $params));

        if (!$response->successful()) {
            $error = $response->json('error', 'Bridge request failed with status ' . $response->status());
            Log::warning("WP Bridge error [{$action}] for site {$website->slug}: {$error}");
            throw new \RuntimeException($error);
        }

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            $error = $data['error'] ?? 'Unknown bridge error';
            throw new \RuntimeException($error);
        }

        return $data;
    }

    // ─── Overview ───

    public function getOverview(Website $website): array
    {
        return $this->call($website, 'overview');
    }

    public function checkUpdates(Website $website): array
    {
        return $this->call($website, 'updates.check');
    }

    public function clearCache(Website $website): array
    {
        return $this->call($website, 'cache.clear');
    }

    public function listPages(Website $website): array
    {
        return $this->call($website, 'pages.list');
    }

    public function getOptions(Website $website, array $keys): array
    {
        return $this->call($website, 'options.get', ['keys' => json_encode($keys)]);
    }

    public function setOptions(Website $website, array $options): array
    {
        return $this->call($website, 'options.set', ['options' => json_encode($options)]);
    }

    // ─── Logo / Branding ───

    public function getLogo(Website $website): array
    {
        return $this->call($website, 'logo.get');
    }

    public function uploadLogo(Website $website, string $imageUrl): array
    {
        return $this->call($website, 'logo.upload', ['image_url' => $imageUrl]);
    }

    public function removeLogo(Website $website): array
    {
        return $this->call($website, 'logo.remove');
    }

    public function applyGeneratedLogo(Website $website, string $svgContent): array
    {
        return $this->call($website, 'logo.generate', ['svg_content' => $svgContent]);
    }

    // ─── Plugins ───

    public function listPlugins(Website $website): array
    {
        return $this->call($website, 'plugins.list');
    }

    public function activatePlugin(Website $website, string $plugin): array
    {
        return $this->call($website, 'plugins.activate', ['plugin' => $plugin]);
    }

    public function deactivatePlugin(Website $website, string $plugin): array
    {
        return $this->call($website, 'plugins.deactivate', ['plugin' => $plugin]);
    }

    public function installPlugin(Website $website, string $slug): array
    {
        return $this->call($website, 'plugins.install', ['slug' => $slug]);
    }

    public function deletePlugin(Website $website, string $plugin): array
    {
        return $this->call($website, 'plugins.delete', ['plugin' => $plugin]);
    }

    public function updatePlugin(Website $website, string $plugin): array
    {
        return $this->call($website, 'plugins.update', ['plugin' => $plugin]);
    }

    // ─── Themes ───

    public function listThemes(Website $website): array
    {
        return $this->call($website, 'themes.list');
    }

    public function activateTheme(Website $website, string $theme): array
    {
        return $this->call($website, 'themes.activate', ['theme' => $theme]);
    }

    public function installTheme(Website $website, string $slug): array
    {
        return $this->call($website, 'themes.install', ['slug' => $slug]);
    }

    public function deleteTheme(Website $website, string $theme): array
    {
        return $this->call($website, 'themes.delete', ['theme' => $theme]);
    }

    public function updateTheme(Website $website, string $theme): array
    {
        return $this->call($website, 'themes.update', ['theme' => $theme]);
    }

    // ─── WooCommerce ───

    public function listProducts(Website $website, array $params = []): array
    {
        return $this->call($website, 'woo.products.list', $params);
    }

    public function getProduct(Website $website, int $productId): array
    {
        return $this->call($website, 'woo.products.get', ['product_id' => $productId]);
    }

    public function createProduct(Website $website, array $data): array
    {
        return $this->call($website, 'woo.products.create', $data);
    }

    public function updateProduct(Website $website, int $productId, array $data): array
    {
        return $this->call($website, 'woo.products.update', array_merge(['product_id' => $productId], $data));
    }

    public function deleteProduct(Website $website, int $productId, bool $force = false): array
    {
        return $this->call($website, 'woo.products.delete', ['product_id' => $productId, 'force' => $force ? '1' : '0']);
    }

    public function listOrders(Website $website, array $params = []): array
    {
        return $this->call($website, 'woo.orders.list', $params);
    }

    public function listProductCategories(Website $website): array
    {
        return $this->call($website, 'woo.categories.list');
    }

    // ─── WebNewBiz Builder Plugin ───

    public function wnbDashboard(Website $website): array
    {
        return $this->call($website, 'wnb.dashboard');
    }

    public function wnbAnalytics(Website $website, string $period = '7days'): array
    {
        return $this->call($website, 'wnb.analytics', ['period' => $period]);
    }

    public function wnbPerformanceGet(Website $website): array
    {
        return $this->call($website, 'wnb.performance.get');
    }

    public function wnbPerformanceSave(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.performance.save', $settings);
    }

    public function wnbCacheStats(Website $website): array
    {
        return $this->call($website, 'wnb.cache.stats');
    }

    public function wnbCachePurge(Website $website, string $type = 'all'): array
    {
        return $this->call($website, 'wnb.cache.purge', ['type' => $type]);
    }

    public function wnbCacheSettings(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.cache.settings', $settings);
    }

    public function wnbSecurityGet(Website $website): array
    {
        return $this->call($website, 'wnb.security.get');
    }

    public function wnbSecuritySave(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.security.save', $settings);
    }

    public function wnbBackupList(Website $website): array
    {
        return $this->call($website, 'wnb.backup.list');
    }

    public function wnbBackupCreate(Website $website, string $type = 'database'): array
    {
        return $this->call($website, 'wnb.backup.create', ['type' => $type]);
    }

    public function wnbBackupDelete(Website $website, string $id): array
    {
        return $this->call($website, 'wnb.backup.delete', ['id' => $id]);
    }

    public function wnbBackupRestore(Website $website, string $id): array
    {
        return $this->call($website, 'wnb.backup.restore', ['id' => $id]);
    }

    public function wnbDatabaseStats(Website $website): array
    {
        return $this->call($website, 'wnb.database.stats');
    }

    public function wnbDatabaseCleanup(Website $website, string $type = 'all'): array
    {
        return $this->call($website, 'wnb.database.cleanup', ['type' => $type]);
    }

    public function wnbDatabaseOptimize(Website $website): array
    {
        return $this->call($website, 'wnb.database.optimize');
    }

    public function wnbMaintenanceGet(Website $website): array
    {
        return $this->call($website, 'wnb.maintenance.get');
    }

    public function wnbMaintenanceToggle(Website $website, bool $enabled): array
    {
        return $this->call($website, 'wnb.maintenance.toggle', ['enabled' => $enabled ? '1' : '0']);
    }

    public function wnbMaintenanceSave(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.maintenance.save', $settings);
    }

    public function wnbImagesStats(Website $website): array
    {
        return $this->call($website, 'wnb.images.stats');
    }

    public function wnbImagesOptimize(Website $website, int $limit = 10): array
    {
        return $this->call($website, 'wnb.images.optimize', ['limit' => (string) $limit]);
    }

    public function wnbImagesSettings(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.images.settings', $settings);
    }

    public function wnbSeoGet(Website $website): array
    {
        return $this->call($website, 'wnb.seo.get');
    }

    public function wnbSeoSave(Website $website, array $settings): array
    {
        return $this->call($website, 'wnb.seo.save', $settings);
    }

    public function wnbSeoRedirectAdd(Website $website, string $from, string $to): array
    {
        return $this->call($website, 'wnb.seo.redirect.add', ['from' => $from, 'to' => $to]);
    }

    public function wnbSeoRedirectDelete(Website $website, string $from): array
    {
        return $this->call($website, 'wnb.seo.redirect.delete', ['from' => $from]);
    }

    public function wnbSeoSitemap(Website $website): array
    {
        return $this->call($website, 'wnb.seo.sitemap');
    }

    public function wnbSeoRobots(Website $website, string $content): array
    {
        return $this->call($website, 'wnb.seo.robots', ['content' => $content]);
    }

    public function wnbAiGenerate(Website $website, array $params): array
    {
        return $this->call($website, 'wnb.ai.generate', $params);
    }

    public function wnbAiHistory(Website $website, string $action = 'get'): array
    {
        return $this->call($website, 'wnb.ai.history', ['history_action' => $action]);
    }

    // ─── AI Copilot — Elementor ───

    public function getElementorPageData(Website $website, int $pageId): array
    {
        return $this->call($website, 'elementor.page.get', ['page_id' => $pageId]);
    }

    public function updateElementorPageData(Website $website, int $pageId, string $elementorData, ?string $title = null): array
    {
        $params = ['page_id' => $pageId, 'elementor_data_b64' => base64_encode($elementorData)];
        if ($title !== null) $params['title'] = $title;
        return $this->call($website, 'elementor.page.update', $params);
    }

    public function getElementorEditables(Website $website, int $pageId): array
    {
        return $this->call($website, 'elementor.page.editables', ['page_id' => $pageId]);
    }

    public function createElementorPage(Website $website, string $title, string $elementorData, string $status = 'publish'): array
    {
        return $this->call($website, 'elementor.page.create', [
            'title' => $title,
            'elementor_data' => $elementorData,
            'status' => $status,
        ]);
    }

    public function addElementorSection(Website $website, int $pageId, string $sectionData, int $position = -1): array
    {
        return $this->call($website, 'elementor.section.add', [
            'page_id' => $pageId,
            'section_data' => $sectionData,
            'position' => $position,
        ]);
    }

    public function removeElementorSection(Website $website, int $pageId, string $elementId): array
    {
        return $this->call($website, 'elementor.section.remove', [
            'page_id' => $pageId,
            'element_id' => $elementId,
        ]);
    }

    public function reorderElementorSections(Website $website, int $pageId, array $order): array
    {
        return $this->call($website, 'elementor.section.reorder', [
            'page_id' => $pageId,
            'order' => json_encode($order),
        ]);
    }

    public function regenerateElementorCss(Website $website, int $pageId = 0): array
    {
        return $this->call($website, 'elementor.css.regenerate', ['page_id' => $pageId]);
    }

    public function getGlobalColors(Website $website): array
    {
        return $this->call($website, 'elementor.global.colors');
    }

    public function setGlobalColors(Website $website, array $colors): array
    {
        return $this->call($website, 'elementor.global.colors', ['colors' => json_encode($colors)]);
    }

    public function getGlobalFonts(Website $website): array
    {
        return $this->call($website, 'elementor.global.fonts');
    }

    public function setGlobalFonts(Website $website, array $fonts): array
    {
        return $this->call($website, 'elementor.global.fonts', ['fonts' => json_encode($fonts)]);
    }

    // ─── AI Copilot — Media ───

    public function uploadMediaFromUrl(Website $website, string $imageUrl, string $altText = '', string $title = ''): array
    {
        return $this->call($website, 'media.upload_url', [
            'image_url' => $imageUrl,
            'alt_text' => $altText,
            'title' => $title,
        ]);
    }

    public function listMedia(Website $website, int $perPage = 20, int $page = 1, string $type = 'image'): array
    {
        return $this->call($website, 'media.list', [
            'per_page' => $perPage,
            'page' => $page,
            'type' => $type,
        ]);
    }

    // ─── AI Copilot — Menus ───

    public function listMenus(Website $website): array
    {
        return $this->call($website, 'menu.list');
    }

    public function updateMenu(Website $website, int $menuId, array $items): array
    {
        return $this->call($website, 'menu.update', [
            'menu_id' => $menuId,
            'items' => json_encode($items),
        ]);
    }

    // ─── AI Copilot — SEO (page-level) ───

    public function getPageSeo(Website $website, int $pageId): array
    {
        return $this->call($website, 'seo.page.get', ['page_id' => $pageId]);
    }

    public function updatePageSeo(Website $website, int $pageId, array $seoData): array
    {
        return $this->call($website, 'seo.page.update', array_merge(['page_id' => $pageId], $seoData));
    }

    // ─── AI Copilot — Posts ───

    public function createPost(Website $website, string $title, string $content, string $postType = 'post', string $status = 'publish'): array
    {
        return $this->call($website, 'posts.create', [
            'title' => $title,
            'content' => $content,
            'post_type' => $postType,
            'status' => $status,
        ]);
    }

    public function updatePost(Website $website, int $postId, array $data): array
    {
        return $this->call($website, 'posts.update', array_merge(['post_id' => $postId], $data));
    }

    public function deletePost(Website $website, int $postId, bool $force = false): array
    {
        return $this->call($website, 'posts.delete', [
            'post_id' => $postId,
            'force' => $force ? '1' : '0',
        ]);
    }
}
