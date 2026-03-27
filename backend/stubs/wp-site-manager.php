<?php
/**
 * WebNewBiz Site Manager Bridge
 * Installed per-site to enable remote management from Laravel backend.
 * Token is replaced during installation.
 */

// Security: only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'error' => 'Method not allowed']));
}

header('Content-Type: application/json; charset=utf-8');
set_time_limit(120);
error_reporting(0);

// Catch fatal errors
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Fatal: ' . $err['message']]);
    }
});

// Auth
$BRIDGE_TOKEN = '__BRIDGE_TOKEN__';

$token = $_SERVER['HTTP_X_BRIDGE_TOKEN']
    ?? $_POST['_token']
    ?? '';

if (!hash_equals($BRIDGE_TOKEN, $token)) {
    http_response_code(403);
    die(json_encode(['success' => false, 'error' => 'Invalid token']));
}

// Load WordPress
define('ABSPATH', __DIR__ . '/');
define('WP_USE_THEMES', false);

require ABSPATH . 'wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/misc.php';

$action = $_POST['action'] ?? '';

// Silent Upgrader Skin
if (!class_exists('WNB_Silent_Skin')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    class WNB_Silent_Skin extends WP_Upgrader_Skin {
        public function header() {}
        public function footer() {}
        public function error($errors) {}
        public function feedback($feedback, ...$args) {}
        public function before() {}
        public function after() {}
    }
}

function wnb_json($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function wnb_ok($data = []) {
    wnb_json(array_merge(['success' => true], $data));
}

function wnb_err($msg, $code = 400) {
    http_response_code($code);
    wnb_json(['success' => false, 'error' => $msg]);
}

// ─── Action Router ───
try {
    switch ($action) {
        case 'overview':          handle_overview(); break;
        case 'plugins.list':      handle_plugins_list(); break;
        case 'plugins.activate':  handle_plugins_activate(); break;
        case 'plugins.deactivate':handle_plugins_deactivate(); break;
        case 'plugins.install':   handle_plugins_install(); break;
        case 'plugins.delete':    handle_plugins_delete(); break;
        case 'plugins.update':    handle_plugins_update(); break;
        case 'themes.list':       handle_themes_list(); break;
        case 'themes.activate':   handle_themes_activate(); break;
        case 'themes.install':    handle_themes_install(); break;
        case 'themes.delete':     handle_themes_delete(); break;
        case 'themes.update':     handle_themes_update(); break;
        case 'updates.check':     handle_updates_check(); break;
        case 'cache.clear':       handle_cache_clear(); break;
        case 'pages.list':        handle_pages_list(); break;
        case 'options.get':       handle_options_get(); break;
        case 'options.set':       handle_options_set(); break;
        case 'woo.products.list': handle_woo_products_list(); break;
        case 'woo.products.get':  handle_woo_products_get(); break;
        case 'woo.products.create': handle_woo_products_create(); break;
        case 'woo.products.update': handle_woo_products_update(); break;
        case 'woo.products.delete': handle_woo_products_delete(); break;
        case 'woo.orders.list':   handle_woo_orders_list(); break;
        case 'woo.categories.list': handle_woo_categories_list(); break;

        // WebNewBiz Builder Plugin
        case 'wnb.dashboard':          handle_wnb_dashboard(); break;
        case 'wnb.analytics':          handle_wnb_analytics(); break;
        case 'wnb.performance.get':    handle_wnb_performance_get(); break;
        case 'wnb.performance.save':   handle_wnb_performance_save(); break;
        case 'wnb.cache.stats':        handle_wnb_cache_stats(); break;
        case 'wnb.cache.purge':        handle_wnb_cache_purge(); break;
        case 'wnb.cache.settings':     handle_wnb_cache_settings(); break;
        case 'wnb.security.get':       handle_wnb_security_get(); break;
        case 'wnb.security.save':      handle_wnb_security_save(); break;
        case 'wnb.backup.list':        handle_wnb_backup_list(); break;
        case 'wnb.backup.create':      handle_wnb_backup_create(); break;
        case 'wnb.backup.delete':      handle_wnb_backup_delete(); break;
        case 'wnb.backup.restore':     handle_wnb_backup_restore(); break;
        case 'wnb.database.stats':     handle_wnb_database_stats(); break;
        case 'wnb.database.cleanup':   handle_wnb_database_cleanup(); break;
        case 'wnb.database.optimize':  handle_wnb_database_optimize(); break;
        case 'wnb.maintenance.get':    handle_wnb_maintenance_get(); break;
        case 'wnb.maintenance.toggle': handle_wnb_maintenance_toggle(); break;
        case 'wnb.maintenance.save':   handle_wnb_maintenance_save(); break;
        case 'wnb.images.stats':       handle_wnb_images_stats(); break;
        case 'wnb.images.optimize':    handle_wnb_images_optimize(); break;
        case 'wnb.images.settings':    handle_wnb_images_settings(); break;
        case 'wnb.seo.get':            handle_wnb_seo_get(); break;
        case 'wnb.seo.save':           handle_wnb_seo_save(); break;
        case 'wnb.seo.redirect.add':   handle_wnb_seo_redirect_add(); break;
        case 'wnb.seo.redirect.delete':handle_wnb_seo_redirect_delete(); break;
        case 'wnb.seo.sitemap':        handle_wnb_seo_sitemap(); break;
        case 'wnb.seo.robots':         handle_wnb_seo_robots(); break;
        case 'wnb.ai.generate':        handle_wnb_ai_generate(); break;
        case 'wnb.ai.history':         handle_wnb_ai_history(); break;

        // Logo / Branding
        case 'logo.get':               handle_logo_get(); break;
        case 'logo.upload':            handle_logo_upload(); break;
        case 'logo.remove':            handle_logo_remove(); break;
        case 'logo.generate':          handle_logo_generate(); break;

        // AI Copilot — Elementor
        case 'elementor.page.get':         handle_elementor_page_get(); break;
        case 'elementor.page.update':      handle_elementor_page_update(); break;
        case 'elementor.page.editables':   handle_elementor_page_editables(); break;
        case 'elementor.page.create':      handle_elementor_page_create(); break;
        case 'elementor.section.add':      handle_elementor_section_add(); break;
        case 'elementor.section.remove':   handle_elementor_section_remove(); break;
        case 'elementor.section.reorder':  handle_elementor_section_reorder(); break;
        case 'elementor.css.regenerate':   handle_elementor_css_regenerate(); break;
        case 'elementor.global.colors':    handle_elementor_global_colors(); break;
        case 'elementor.global.fonts':     handle_elementor_global_fonts(); break;

        // AI Copilot — Media
        case 'media.upload_url':           handle_media_upload_url(); break;
        case 'media.list':                 handle_media_list(); break;

        // AI Copilot — Menus
        case 'menu.list':                  handle_menu_list(); break;
        case 'menu.update':               handle_menu_update(); break;

        // AI Copilot — SEO (page-level)
        case 'seo.page.get':              handle_seo_page_get(); break;
        case 'seo.page.update':           handle_seo_page_update(); break;

        // AI Copilot — Posts
        case 'posts.create':              handle_posts_create(); break;
        case 'posts.update':              handle_posts_update(); break;
        case 'posts.delete':              handle_posts_delete(); break;

        default:
            wnb_err("Unknown action: {$action}");
    }
} catch (\Throwable $e) {
    wnb_err($e->getMessage(), 500);
}

// ══════════════════════════════════════════
// ─── OVERVIEW ────────────────────────────
// ══════════════════════════════════════════

function handle_overview() {
    global $wpdb;

    $plugins = get_plugins();
    $active = (array) get_option('active_plugins', []);
    $theme = wp_get_theme();

    // Disk usage
    $sitePath = ABSPATH;
    $diskBytes = 0;
    if (function_exists('shell_exec')) {
        if (PHP_OS_FAMILY === 'Windows') {
            $out = shell_exec('dir /s /-c "' . str_replace('/', '\\', $sitePath) . '" 2>NUL | findstr /C:"File(s)"');
            if (preg_match('/(\d+)\s+bytes/', str_replace(',', '', $out ?? ''), $m)) {
                $diskBytes = (int) $m[1];
            }
        } else {
            $out = shell_exec("du -sb " . escapeshellarg($sitePath) . " 2>/dev/null | cut -f1");
            $diskBytes = (int) trim($out ?? '0');
        }
    }

    // DB size
    $dbSize = 0;
    $dbName = DB_NAME;
    $rows = $wpdb->get_results(
        $wpdb->prepare("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = %s", $dbName)
    );
    if (!empty($rows[0]->size)) {
        $dbSize = (float) $rows[0]->size;
    }

    $pageCount = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='page' AND post_status != 'trash'");
    $postCount = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='post' AND post_status != 'trash'");
    $userCount = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->users}");

    wnb_ok([
        'data' => [
            'wp_version'      => get_bloginfo('version'),
            'php_version'     => phpversion(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'disk_usage_mb'   => round($diskBytes / 1048576, 2),
            'db_size_mb'      => round($dbSize / 1048576, 2),
            'active_plugins'  => count($active),
            'total_plugins'   => count($plugins),
            'active_theme'    => $theme->get('Name'),
            'total_pages'     => $pageCount,
            'total_posts'     => $postCount,
            'total_users'     => $userCount,
            'site_url'        => get_site_url(),
            'site_title'      => get_bloginfo('name'),
            'admin_email'     => get_option('admin_email'),
            'multisite'       => is_multisite(),
            'woocommerce_active' => in_array('woocommerce/woocommerce.php', $active),
        ],
    ]);
}

// ══════════════════════════════════════════
// ─── PLUGINS ─────────────────────────────
// ══════════════════════════════════════════

function handle_plugins_list() {
    $allPlugins = get_plugins();
    $active = (array) get_option('active_plugins', []);
    $updates = get_site_transient('update_plugins');

    $result = [];
    foreach ($allPlugins as $file => $data) {
        $slug = dirname($file);
        if ($slug === '.') $slug = basename($file, '.php');

        $updateVer = null;
        if (!empty($updates->response[$file]->new_version)) {
            $updateVer = $updates->response[$file]->new_version;
        }

        $result[] = [
            'file'             => $file,
            'name'             => $data['Name'],
            'slug'             => $slug,
            'version'          => $data['Version'],
            'is_active'        => in_array($file, $active),
            'description'      => wp_strip_all_tags($data['Description']),
            'author'           => wp_strip_all_tags($data['Author']),
            'url'              => $data['PluginURI'] ?? '',
            'update_available' => $updateVer,
        ];
    }

    wnb_ok(['data' => $result]);
}

function handle_plugins_activate() {
    $plugin = $_POST['plugin'] ?? '';
    if (!$plugin) wnb_err('Missing plugin parameter');

    $result = activate_plugin($plugin);
    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    wnb_ok(['message' => 'Plugin activated']);
}

function handle_plugins_deactivate() {
    $plugin = $_POST['plugin'] ?? '';
    if (!$plugin) wnb_err('Missing plugin parameter');

    deactivate_plugins($plugin);
    wnb_ok(['message' => 'Plugin deactivated']);
}

function handle_plugins_install() {
    $slug = $_POST['slug'] ?? '';
    if (!$slug) wnb_err('Missing slug parameter');

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    $api = plugins_api('plugin_information', [
        'slug'   => $slug,
        'fields' => ['sections' => false],
    ]);

    if (is_wp_error($api)) {
        wnb_err('Plugin not found: ' . $api->get_error_message());
    }

    $skin = new WNB_Silent_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result = $upgrader->install($api->download_link);

    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    if ($result === false) {
        wnb_err('Plugin installation failed');
    }

    wnb_ok(['message' => "Plugin '{$slug}' installed"]);
}

function handle_plugins_delete() {
    $plugin = $_POST['plugin'] ?? '';
    if (!$plugin) wnb_err('Missing plugin parameter');

    if (is_plugin_active($plugin)) {
        deactivate_plugins($plugin);
    }

    $result = delete_plugins([$plugin]);
    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    wnb_ok(['message' => 'Plugin deleted']);
}

function handle_plugins_update() {
    $plugin = $_POST['plugin'] ?? '';
    if (!$plugin) wnb_err('Missing plugin parameter');

    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/update.php';

    // Force WordPress to check for latest plugin updates
    wp_update_plugins();

    $skin = new WNB_Silent_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result = $upgrader->upgrade($plugin);

    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    if ($result === false) {
        // Get more detail about why it failed
        $errors = $skin->get_errors();
        if ($errors && is_wp_error($errors)) {
            wnb_err('Update failed: ' . $errors->get_error_message());
        }
        wnb_err('Plugin update failed — no update package found. Try again.');
    }

    // Clear update transients so the list refreshes
    delete_site_transient('update_plugins');
    wp_update_plugins();

    wnb_ok(['message' => 'Plugin updated successfully']);
}

// ══════════════════════════════════════════
// ─── THEMES ──────────────────────────────
// ══════════════════════════════════════════

function handle_themes_list() {
    $themes = wp_get_themes();
    $activeSlug = get_stylesheet();
    $updates = get_site_transient('update_themes');

    $result = [];
    foreach ($themes as $slug => $theme) {
        $updateVer = null;
        if (!empty($updates->response[$slug]['new_version'])) {
            $updateVer = $updates->response[$slug]['new_version'];
        }

        $screenshot = '';
        if ($theme->get_screenshot()) {
            $screenshot = $theme->get_screenshot();
        }

        $result[] = [
            'slug'             => $slug,
            'name'             => $theme->get('Name'),
            'version'          => $theme->get('Version'),
            'is_active'        => ($slug === $activeSlug),
            'screenshot'       => $screenshot,
            'description'      => $theme->get('Description'),
            'author'           => $theme->get('Author'),
            'update_available' => $updateVer,
        ];
    }

    wnb_ok(['data' => $result]);
}

function handle_themes_activate() {
    $theme = $_POST['theme'] ?? '';
    if (!$theme) wnb_err('Missing theme parameter');

    $themeObj = wp_get_theme($theme);
    if (!$themeObj->exists()) {
        wnb_err("Theme '{$theme}' not found");
    }

    switch_theme($theme);
    wnb_ok(['message' => "Theme '{$theme}' activated"]);
}

function handle_themes_install() {
    $slug = $_POST['slug'] ?? '';
    if (!$slug) wnb_err('Missing slug parameter');

    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/theme.php';

    $api = themes_api('theme_information', [
        'slug'   => $slug,
        'fields' => ['sections' => false],
    ]);

    if (is_wp_error($api)) {
        wnb_err('Theme not found: ' . $api->get_error_message());
    }

    $skin = new WNB_Silent_Skin();
    $upgrader = new Theme_Upgrader($skin);
    $result = $upgrader->install($api->download_link);

    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    if ($result === false) {
        wnb_err('Theme installation failed');
    }

    wnb_ok(['message' => "Theme '{$slug}' installed"]);
}

function handle_themes_delete() {
    $theme = $_POST['theme'] ?? '';
    if (!$theme) wnb_err('Missing theme parameter');

    if (get_stylesheet() === $theme) {
        wnb_err('Cannot delete the active theme');
    }

    $result = delete_theme($theme);
    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    wnb_ok(['message' => "Theme '{$theme}' deleted"]);
}

function handle_themes_update() {
    $theme = $_POST['theme'] ?? '';
    if (!$theme) wnb_err('Missing theme parameter');

    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

    $skin = new WNB_Silent_Skin();
    $upgrader = new Theme_Upgrader($skin);
    $result = $upgrader->upgrade($theme);

    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }
    if ($result === false) {
        wnb_err('Theme update failed');
    }

    wnb_ok(['message' => "Theme '{$theme}' updated"]);
}

// ══════════════════════════════════════════
// ─── UPDATES ─────────────────────────────
// ══════════════════════════════════════════

function handle_updates_check() {
    require_once ABSPATH . 'wp-admin/includes/update.php';

    wp_update_plugins();
    wp_update_themes();
    wp_version_check();

    global $wp_version;

    // Core
    $coreUpdates = get_core_updates();
    $coreNew = null;
    if (!empty($coreUpdates) && $coreUpdates[0]->response === 'upgrade') {
        $coreNew = $coreUpdates[0]->version;
    }

    // Plugins
    $pluginUpdates = get_site_transient('update_plugins');
    $pluginList = [];
    if (!empty($pluginUpdates->response)) {
        $allPlugins = get_plugins();
        foreach ($pluginUpdates->response as $file => $info) {
            $pluginList[] = [
                'file'        => $file,
                'name'        => $allPlugins[$file]['Name'] ?? $file,
                'current'     => $allPlugins[$file]['Version'] ?? '?',
                'new_version' => $info->new_version,
            ];
        }
    }

    // Themes
    $themeUpdates = get_site_transient('update_themes');
    $themeList = [];
    if (!empty($themeUpdates->response)) {
        $allThemes = wp_get_themes();
        foreach ($themeUpdates->response as $slug => $info) {
            $themeList[] = [
                'slug'        => $slug,
                'name'        => isset($allThemes[$slug]) ? $allThemes[$slug]->get('Name') : $slug,
                'current'     => isset($allThemes[$slug]) ? $allThemes[$slug]->get('Version') : '?',
                'new_version' => $info['new_version'],
            ];
        }
    }

    wnb_ok([
        'data' => [
            'core'    => ['current' => $wp_version, 'new_version' => $coreNew],
            'plugins' => $pluginList,
            'themes'  => $themeList,
        ],
    ]);
}

// ══════════════════════════════════════════
// ─── CACHE ───────────────────────────────
// ══════════════════════════════════════════

function handle_cache_clear() {
    global $wpdb;
    $cleared = [];

    // WP object cache
    wp_cache_flush();
    $cleared[] = 'wp_object_cache';

    // WP transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
    $cleared[] = 'wp_transients';

    // Elementor CSS cache
    $uploadDir = wp_upload_dir();
    $elementorCss = $uploadDir['basedir'] . '/elementor/css';
    if (is_dir($elementorCss)) {
        array_map('unlink', glob($elementorCss . '/*.css'));
        $cleared[] = 'elementor_css';
    }

    // Elementor element cache
    $deleted = $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_element_cache'");
    if ($deleted) $cleared[] = 'elementor_element_cache';

    // Elementor files cache
    if (class_exists('\Elementor\Plugin')) {
        try {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
            $cleared[] = 'elementor_files';
        } catch (\Throwable $e) {}
    }

    wnb_ok(['cleared' => $cleared]);
}

// ══════════════════════════════════════════
// ─── PAGES ───────────────────────────────
// ══════════════════════════════════════════

function handle_pages_list() {
    $pages = get_posts([
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);

    $result = [];
    foreach ($pages as $page) {
        $result[] = [
            'id'       => $page->ID,
            'title'    => $page->post_title,
            'slug'     => $page->post_name,
            'status'   => $page->post_status,
            'url'      => get_permalink($page->ID),
            'template' => get_page_template_slug($page->ID) ?: 'default',
            'modified' => $page->post_modified,
        ];
    }

    wnb_ok(['data' => $result]);
}

// ══════════════════════════════════════════
// ─── OPTIONS ─────────────────────────────
// ══════════════════════════════════════════

function get_options_whitelist() {
    return [
        'blogname', 'blogdescription', 'admin_email',
        'timezone_string', 'date_format', 'time_format',
        'posts_per_page', 'permalink_structure', 'WPLANG',
        'start_of_week',
    ];
}

function handle_options_get() {
    $keys = $_POST['keys'] ?? [];
    if (is_string($keys)) $keys = json_decode($keys, true) ?: [];
    $whitelist = get_options_whitelist();

    $result = [];
    foreach ($keys as $key) {
        if (in_array($key, $whitelist)) {
            $result[$key] = get_option($key, '');
        }
    }

    wnb_ok(['data' => $result]);
}

function handle_options_set() {
    $options = $_POST['options'] ?? [];
    if (is_string($options)) $options = json_decode($options, true) ?: [];
    $whitelist = get_options_whitelist();

    $updated = [];
    foreach ($options as $key => $value) {
        if (in_array($key, $whitelist)) {
            update_option($key, sanitize_text_field($value));
            $updated[] = $key;
        }
    }

    wnb_ok(['updated' => $updated]);
}

// ══════════════════════════════════════════
// ─── WOOCOMMERCE ─────────────────────────
// ══════════════════════════════════════════

function woo_check() {
    if (!class_exists('WooCommerce')) {
        wnb_err('WooCommerce is not active on this site', 400);
    }
}

function woo_format_product($product) {
    $images = [];
    foreach ($product->get_gallery_image_ids() as $imgId) {
        $images[] = wp_get_attachment_url($imgId);
    }
    $featuredImg = $product->get_image_id() ? wp_get_attachment_url($product->get_image_id()) : '';

    $categories = [];
    foreach ($product->get_category_ids() as $catId) {
        $term = get_term($catId, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $categories[] = ['id' => $term->term_id, 'name' => $term->name, 'slug' => $term->slug];
        }
    }

    return [
        'id'             => $product->get_id(),
        'name'           => $product->get_name(),
        'slug'           => $product->get_slug(),
        'type'           => $product->get_type(),
        'status'         => $product->get_status(),
        'sku'            => $product->get_sku(),
        'price'          => $product->get_price(),
        'regular_price'  => $product->get_regular_price(),
        'sale_price'     => $product->get_sale_price(),
        'stock_status'   => $product->get_stock_status(),
        'stock_quantity'  => $product->get_stock_quantity(),
        'manage_stock'   => $product->get_manage_stock(),
        'description'    => $product->get_description(),
        'short_description' => $product->get_short_description(),
        'featured_image' => $featuredImg,
        'gallery_images' => $images,
        'categories'     => $categories,
        'weight'         => $product->get_weight(),
        'virtual'        => $product->get_virtual(),
        'downloadable'   => $product->get_downloadable(),
        'permalink'      => get_permalink($product->get_id()),
        'date_created'   => $product->get_date_created() ? $product->get_date_created()->format('Y-m-d H:i:s') : null,
        'date_modified'  => $product->get_date_modified() ? $product->get_date_modified()->format('Y-m-d H:i:s') : null,
    ];
}

function handle_woo_products_list() {
    woo_check();

    $page = max(1, (int) ($_POST['page'] ?? 1));
    $per_page = min(100, max(1, (int) ($_POST['per_page'] ?? 20)));
    $search = $_POST['search'] ?? '';
    $status = $_POST['status'] ?? 'any';
    $category = $_POST['category'] ?? '';

    $args = [
        'limit'   => $per_page,
        'page'    => $page,
        'status'  => $status,
        'orderby' => 'date',
        'order'   => 'DESC',
    ];

    if ($search) {
        $args['s'] = $search;
    }
    if ($category) {
        $args['category'] = [$category];
    }

    $products = wc_get_products($args);

    // Get total count
    $countArgs = $args;
    $countArgs['limit'] = -1;
    $countArgs['return'] = 'ids';
    $totalIds = wc_get_products($countArgs);
    $total = count($totalIds);

    $result = [];
    foreach ($products as $product) {
        $result[] = woo_format_product($product);
    }

    wnb_ok([
        'data'       => $result,
        'total'      => $total,
        'page'       => $page,
        'per_page'   => $per_page,
        'total_pages' => ceil($total / $per_page),
    ]);
}

function handle_woo_products_get() {
    woo_check();
    $id = (int) ($_POST['product_id'] ?? $_POST['id'] ?? 0);
    if (!$id) wnb_err('Missing product id');

    $product = wc_get_product($id);
    if (!$product) wnb_err('Product not found', 404);

    wnb_ok(['data' => woo_format_product($product)]);
}

function handle_woo_products_create() {
    woo_check();

    $name = $_POST['name'] ?? '';
    if (!$name) wnb_err('Product name is required');

    $product = new \WC_Product_Simple();
    $product->set_name(sanitize_text_field($name));

    if (isset($_POST['description']))       $product->set_description(wp_kses_post($_POST['description']));
    if (isset($_POST['short_description'])) $product->set_short_description(wp_kses_post($_POST['short_description']));
    if (isset($_POST['regular_price']))     $product->set_regular_price(sanitize_text_field($_POST['regular_price']));
    if (isset($_POST['sale_price']))        $product->set_sale_price(sanitize_text_field($_POST['sale_price']));
    if (isset($_POST['sku']))               $product->set_sku(sanitize_text_field($_POST['sku']));
    if (isset($_POST['stock_status']))      $product->set_stock_status(sanitize_text_field($_POST['stock_status']));
    if (isset($_POST['stock_quantity']))    { $product->set_manage_stock(true); $product->set_stock_quantity((int)$_POST['stock_quantity']); }
    if (isset($_POST['weight']))            $product->set_weight(sanitize_text_field($_POST['weight']));
    if (isset($_POST['virtual']))           $product->set_virtual($_POST['virtual'] === 'true' || $_POST['virtual'] === '1');
    if (isset($_POST['status']))            $product->set_status(sanitize_text_field($_POST['status']));
    else                                    $product->set_status('publish');

    // Categories
    if (!empty($_POST['category_ids'])) {
        $catIds = json_decode($_POST['category_ids'], true);
        if (is_array($catIds)) $product->set_category_ids(array_map('intval', $catIds));
    }

    // Featured image from URL
    if (!empty($_POST['image_url'])) {
        $attachId = woo_upload_image_from_url($_POST['image_url'], $name);
        if ($attachId) $product->set_image_id($attachId);
    }

    $id = $product->save();

    wnb_ok(['data' => woo_format_product(wc_get_product($id)), 'message' => 'Product created']);
}

function handle_woo_products_update() {
    woo_check();
    $id = (int) ($_POST['product_id'] ?? $_POST['id'] ?? 0);
    if (!$id) wnb_err('Missing product id');

    $product = wc_get_product($id);
    if (!$product) wnb_err('Product not found', 404);

    if (isset($_POST['name']))              $product->set_name(sanitize_text_field($_POST['name']));
    if (isset($_POST['description']))       $product->set_description(wp_kses_post($_POST['description']));
    if (isset($_POST['short_description'])) $product->set_short_description(wp_kses_post($_POST['short_description']));
    if (isset($_POST['regular_price']))     $product->set_regular_price(sanitize_text_field($_POST['regular_price']));
    if (isset($_POST['sale_price']))        $product->set_sale_price(sanitize_text_field($_POST['sale_price']));
    if (isset($_POST['sku']))               $product->set_sku(sanitize_text_field($_POST['sku']));
    if (isset($_POST['stock_status']))      $product->set_stock_status(sanitize_text_field($_POST['stock_status']));
    if (isset($_POST['status']))            $product->set_status(sanitize_text_field($_POST['status']));
    if (isset($_POST['weight']))            $product->set_weight(sanitize_text_field($_POST['weight']));
    if (isset($_POST['virtual']))           $product->set_virtual($_POST['virtual'] === 'true' || $_POST['virtual'] === '1');

    if (isset($_POST['stock_quantity'])) {
        $product->set_manage_stock(true);
        $product->set_stock_quantity((int) $_POST['stock_quantity']);
    }

    if (!empty($_POST['category_ids'])) {
        $catIds = json_decode($_POST['category_ids'], true);
        if (is_array($catIds)) $product->set_category_ids(array_map('intval', $catIds));
    }

    if (!empty($_POST['image_url'])) {
        $attachId = woo_upload_image_from_url($_POST['image_url'], $product->get_name());
        if ($attachId) $product->set_image_id($attachId);
    }

    $product->save();

    wnb_ok(['data' => woo_format_product(wc_get_product($id)), 'message' => 'Product updated']);
}

function handle_woo_products_delete() {
    woo_check();
    $id = (int) ($_POST['product_id'] ?? $_POST['id'] ?? 0);
    if (!$id) wnb_err('Missing product id');

    $product = wc_get_product($id);
    if (!$product) wnb_err('Product not found', 404);

    $force = ($_POST['force'] ?? '0') === '1' || ($_POST['force'] ?? '') === 'true';
    if ($force) {
        $product->delete(true);
    } else {
        wp_trash_post($id);
    }

    wnb_ok(['message' => 'Product deleted']);
}

function handle_woo_orders_list() {
    woo_check();

    $page = max(1, (int) ($_POST['page'] ?? 1));
    $per_page = min(100, max(1, (int) ($_POST['per_page'] ?? 20)));
    $status = $_POST['status'] ?? 'any';

    $args = [
        'limit'   => $per_page,
        'page'    => $page,
        'status'  => $status,
        'orderby' => 'date',
        'order'   => 'DESC',
    ];

    $orders = wc_get_orders($args);

    $result = [];
    foreach ($orders as $order) {
        $items = [];
        foreach ($order->get_items() as $item) {
            $items[] = [
                'name'     => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'total'    => $item->get_total(),
            ];
        }
        $result[] = [
            'id'             => $order->get_id(),
            'number'         => $order->get_order_number(),
            'status'         => $order->get_status(),
            'total'          => $order->get_total(),
            'currency'       => $order->get_currency(),
            'customer_name'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'customer_email' => $order->get_billing_email(),
            'items'          => $items,
            'item_count'     => $order->get_item_count(),
            'payment_method' => $order->get_payment_method_title(),
            'date_created'   => $order->get_date_created() ? $order->get_date_created()->format('Y-m-d H:i:s') : null,
        ];
    }

    // Total
    $countArgs = $args;
    $countArgs['limit'] = -1;
    $countArgs['return'] = 'ids';
    $totalIds = wc_get_orders($countArgs);
    $total = count($totalIds);

    wnb_ok([
        'data'        => $result,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => ceil($total / $per_page),
    ]);
}

function handle_woo_categories_list() {
    woo_check();

    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'orderby'    => 'name',
    ]);

    $result = [];
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $result[] = [
                'id'    => $term->term_id,
                'name'  => $term->name,
                'slug'  => $term->slug,
                'count' => $term->count,
                'parent' => $term->parent,
            ];
        }
    }

    wnb_ok(['data' => $result]);
}

function woo_upload_image_from_url($url, $title = '') {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $tmp = download_url($url, 30);
    if (is_wp_error($tmp)) return 0;

    $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
    $filename = sanitize_file_name(($title ?: 'product') . '-' . wp_generate_password(6, false)) . '.' . $ext;

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attachId = media_handle_sideload($file_array, 0, $title);
    if (is_wp_error($attachId)) {
        @unlink($tmp);
        return 0;
    }

    return $attachId;
}

// ══════════════════════════════════════════
// ─── WEBNEWBIZ BUILDER PLUGIN ───────────
// ══════════════════════════════════════════

function wnb_check($class) {
    if (!class_exists($class)) {
        wnb_err('WebNewBiz Builder plugin is not active', 400);
    }
}

function handle_wnb_dashboard() {
    $data = ['plugin_active' => class_exists('WebNewBiz_Builder')];

    if (class_exists('WebNewBiz_Analytics')) {
        $analytics = WebNewBiz_Analytics::instance();
        $data['views_today'] = $analytics->get_views('today');
        $data['views_7days'] = $analytics->get_views('7days');
        $data['views_30days'] = $analytics->get_views('30days');
        $data['unique_30days'] = $analytics->get_unique_visitors('30days');
    }
    if (class_exists('WebNewBiz_Security')) {
        $data['security_score'] = WebNewBiz_Security::instance()->get_security_score();
    }
    if (class_exists('WebNewBiz_Cache')) {
        $data['cache'] = WebNewBiz_Cache::instance()->get_stats();
    }
    if (class_exists('WebNewBiz_Performance')) {
        $data['performance'] = WebNewBiz_Performance::instance()->get_settings();
    }
    if (class_exists('WebNewBiz_Maintenance')) {
        $data['maintenance_enabled'] = WebNewBiz_Maintenance::instance()->is_enabled();
    }

    wnb_ok(['data' => $data]);
}

function handle_wnb_analytics() {
    wnb_check('WebNewBiz_Analytics');
    $a = WebNewBiz_Analytics::instance();
    $period = $_POST['period'] ?? '7days';

    wnb_ok(['data' => [
        'views' => $a->get_views($period),
        'unique_visitors' => $a->get_unique_visitors($period),
        'popular_pages' => $a->get_popular_pages($period, 10),
        'referrers' => $a->get_referrers($period, 10),
        'device_stats' => $a->get_device_stats($period),
        'views_over_time' => $a->get_views_over_time($period),
        'real_time' => $a->get_real_time(5),
    ]]);
}

function handle_wnb_performance_get() {
    wnb_check('WebNewBiz_Performance');
    wnb_ok(['data' => WebNewBiz_Performance::instance()->get_settings()]);
}

function handle_wnb_performance_save() {
    wnb_check('WebNewBiz_Performance');
    $perf = WebNewBiz_Performance::instance();
    $settings = [];
    $allowedKeys = [
        'disable_emojis', 'disable_embeds', 'remove_jquery_migrate',
        'minify_html', 'lazy_load_images', 'lazy_load_iframes',
        'dns_prefetch', 'remove_query_strings', 'disable_heartbeat',
        'disable_self_pingbacks', 'disable_rss', 'preload_fonts',
    ];

    foreach ($allowedKeys as $key) {
        if (isset($_POST[$key])) {
            $settings[$key] = filter_var($_POST[$key], FILTER_VALIDATE_BOOLEAN);
        }
    }

    if (!empty($settings)) {
        $perf->save_settings($settings);
    }

    wnb_ok(['data' => $perf->get_settings()]);
}

function handle_wnb_cache_stats() {
    wnb_check('WebNewBiz_Cache');
    $cache = WebNewBiz_Cache::instance();
    wnb_ok(['data' => [
        'stats' => $cache->get_stats(),
        'settings' => $cache->get_settings(),
    ]]);
}

function handle_wnb_cache_purge() {
    wnb_check('WebNewBiz_Cache');
    $cache = WebNewBiz_Cache::instance();
    $type = $_POST['type'] ?? 'all';

    $result = match($type) {
        'all' => $cache->purge_all(),
        'elementor' => ['elementor' => $cache->purge_elementor()],
        'object' => ['object_cache' => $cache->purge_object_cache()],
        'transients' => ['transients' => $cache->purge_transients()],
        'page' => ['page_cache' => $cache->purge_page_cache()],
        'browser_enable' => ['browser' => $cache->enable_browser_cache()],
        'browser_disable' => ['browser' => $cache->disable_browser_cache()],
        default => null,
    };

    if ($result === null) wnb_err("Invalid cache type: {$type}");
    wnb_ok(['data' => $result, 'message' => "Cache purged: {$type}"]);
}

function handle_wnb_cache_settings() {
    wnb_check('WebNewBiz_Cache');
    $cache = WebNewBiz_Cache::instance();
    $settings = [];
    foreach (['auto_purge_on_save', 'auto_purge_on_update', 'browser_cache_enabled'] as $key) {
        if (isset($_POST[$key])) {
            $settings[$key] = filter_var($_POST[$key], FILTER_VALIDATE_BOOLEAN);
        }
    }
    $cache->save_settings($settings);
    wnb_ok(['data' => $cache->get_settings()]);
}

function handle_wnb_security_get() {
    wnb_check('WebNewBiz_Security');
    $sec = WebNewBiz_Security::instance();
    wnb_ok(['data' => [
        'settings' => $sec->get_settings(),
        'score' => $sec->get_security_score(),
        'blocked_count' => $sec->get_blocked_count(),
        'activity_log' => $sec->get_activity_log(50),
    ]]);
}

function handle_wnb_security_save() {
    wnb_check('WebNewBiz_Security');
    $sec = WebNewBiz_Security::instance();
    $settings = [];
    $allowedKeys = [
        'disable_xmlrpc', 'disable_file_editor', 'hide_wp_version',
        'add_security_headers', 'limit_login_attempts', 'disable_user_enumeration',
        'disable_php_in_uploads', 'force_ssl_admin',
    ];
    foreach ($allowedKeys as $key) {
        if (isset($_POST[$key])) {
            $settings[$key] = filter_var($_POST[$key], FILTER_VALIDATE_BOOLEAN);
        }
    }
    if (!empty($settings)) {
        $sec->save_settings($settings);
    }
    wnb_ok(['data' => [
        'settings' => $sec->get_settings(),
        'score' => $sec->get_security_score(),
    ]]);
}

function handle_wnb_backup_list() {
    wnb_check('WebNewBiz_Backup');
    $bk = WebNewBiz_Backup::instance();
    wnb_ok(['data' => [
        'backups' => $bk->list_backups(),
        'total_size' => $bk->get_total_backup_size(),
    ]]);
}

function handle_wnb_backup_create() {
    wnb_check('WebNewBiz_Backup');
    $type = $_POST['type'] ?? 'database';
    if (!in_array($type, ['full', 'database', 'files'])) wnb_err('Invalid backup type');
    $result = WebNewBiz_Backup::instance()->create_backup($type);
    wnb_ok(['data' => $result]);
}

function handle_wnb_backup_delete() {
    wnb_check('WebNewBiz_Backup');
    $id = $_POST['id'] ?? '';
    if (!$id) wnb_err('Missing backup id');
    $result = WebNewBiz_Backup::instance()->delete_backup($id);
    wnb_ok(['data' => ['deleted' => $result]]);
}

function handle_wnb_backup_restore() {
    wnb_check('WebNewBiz_Backup');
    $id = $_POST['id'] ?? '';
    if (!$id) wnb_err('Missing backup id');
    $result = WebNewBiz_Backup::instance()->restore_backup($id);
    wnb_ok(['data' => ['restored' => $result]]);
}

function handle_wnb_database_stats() {
    wnb_check('WebNewBiz_Database');
    $db = WebNewBiz_Database::instance();
    wnb_ok(['data' => [
        'cleanup_stats' => $db->get_cleanup_stats(),
        'tables' => $db->get_table_sizes(),
        'total_size_mb' => $db->get_total_db_size(),
    ]]);
}

function handle_wnb_database_cleanup() {
    wnb_check('WebNewBiz_Database');
    $db = WebNewBiz_Database::instance();
    $type = $_POST['type'] ?? 'all';
    if ($type === 'all') {
        $result = $db->cleanup_all();
    } else {
        $result = ['cleaned' => $db->cleanup($type), 'type' => $type];
    }
    wnb_ok(['data' => array_merge($result, [
        'cleanup_stats' => $db->get_cleanup_stats(),
        'total_size_mb' => $db->get_total_db_size(),
    ])]);
}

function handle_wnb_database_optimize() {
    wnb_check('WebNewBiz_Database');
    $db = WebNewBiz_Database::instance();
    wnb_ok(['data' => [
        'results' => $db->optimize_tables(),
        'total_size_mb' => $db->get_total_db_size(),
    ]]);
}

function handle_wnb_maintenance_get() {
    wnb_check('WebNewBiz_Maintenance');
    wnb_ok(['data' => WebNewBiz_Maintenance::instance()->get_settings()]);
}

function handle_wnb_maintenance_toggle() {
    wnb_check('WebNewBiz_Maintenance');
    $m = WebNewBiz_Maintenance::instance();
    $enabled = filter_var($_POST['enabled'] ?? '0', FILTER_VALIDATE_BOOLEAN);
    if ($enabled) { $m->enable(); } else { $m->disable(); }
    wnb_ok(['data' => $m->get_settings()]);
}

function handle_wnb_maintenance_save() {
    wnb_check('WebNewBiz_Maintenance');
    $m = WebNewBiz_Maintenance::instance();
    $settings = [];
    foreach (['message', 'back_date', 'bg_color', 'custom_css'] as $key) {
        if (isset($_POST[$key])) $settings[$key] = sanitize_text_field($_POST[$key]);
    }
    if (isset($_POST['allow_admins'])) {
        $settings['allow_admins'] = filter_var($_POST['allow_admins'], FILTER_VALIDATE_BOOLEAN);
    }
    if (isset($_POST['enabled'])) {
        $settings['enabled'] = filter_var($_POST['enabled'], FILTER_VALIDATE_BOOLEAN);
    }
    $m->save_settings($settings);
    wnb_ok(['data' => $m->get_settings()]);
}

function handle_wnb_images_stats() {
    wnb_check('WebNewBiz_ImageOptimizer');
    $img = WebNewBiz_ImageOptimizer::instance();
    wnb_ok(['data' => [
        'stats' => $img->get_stats(),
        'settings' => $img->get_settings(),
    ]]);
}

function handle_wnb_images_optimize() {
    wnb_check('WebNewBiz_ImageOptimizer');
    $limit = min(50, max(1, (int)($_POST['limit'] ?? 10)));
    $result = WebNewBiz_ImageOptimizer::instance()->optimize_bulk($limit);
    wnb_ok(['data' => $result]);
}

function handle_wnb_images_settings() {
    wnb_check('WebNewBiz_ImageOptimizer');
    $img = WebNewBiz_ImageOptimizer::instance();
    $settings = [];
    if (isset($_POST['quality'])) $settings['quality'] = min(100, max(1, (int)$_POST['quality']));
    if (isset($_POST['max_width'])) $settings['max_width'] = (int)$_POST['max_width'];
    if (isset($_POST['max_height'])) $settings['max_height'] = (int)$_POST['max_height'];
    foreach (['webp_enabled', 'auto_optimize', 'strip_exif'] as $key) {
        if (isset($_POST[$key])) $settings[$key] = filter_var($_POST[$key], FILTER_VALIDATE_BOOLEAN);
    }
    $img->save_settings($settings);
    wnb_ok(['data' => $img->get_settings()]);
}

function handle_wnb_seo_get() {
    wnb_check('WebNewBiz_SEO');
    $seo = WebNewBiz_SEO::instance();
    wnb_ok(['data' => [
        'settings' => $seo->get_settings(),
        'redirects' => $seo->get_redirects(),
        'robots' => $seo->get_robots_content(),
        'sitemap_url' => $seo->get_sitemap_url(),
        'pages_without_meta' => $seo->get_pages_without_meta(),
    ]]);
}

function handle_wnb_seo_save() {
    wnb_check('WebNewBiz_SEO');
    $seo = WebNewBiz_SEO::instance();
    $settings = [];
    foreach (['organization_name', 'organization_logo', 'phone', 'address', 'custom_robots'] as $key) {
        if (isset($_POST[$key])) $settings[$key] = sanitize_text_field($_POST[$key]);
    }
    foreach (['schema_enabled', 'sitemap_enabled', 'og_enabled'] as $key) {
        if (isset($_POST[$key])) $settings[$key] = filter_var($_POST[$key], FILTER_VALIDATE_BOOLEAN);
    }
    $seo->save_settings($settings);
    wnb_ok(['data' => $seo->get_settings()]);
}

function handle_wnb_seo_redirect_add() {
    wnb_check('WebNewBiz_SEO');
    $from = $_POST['from'] ?? '';
    $to = $_POST['to'] ?? '';
    if (!$from || !$to) wnb_err('Missing from or to');
    WebNewBiz_SEO::instance()->add_redirect($from, $to);
    wnb_ok(['data' => ['redirects' => WebNewBiz_SEO::instance()->get_redirects()]]);
}

function handle_wnb_seo_redirect_delete() {
    wnb_check('WebNewBiz_SEO');
    $from = $_POST['from'] ?? '';
    if (!$from) wnb_err('Missing from');
    WebNewBiz_SEO::instance()->delete_redirect($from);
    wnb_ok(['data' => ['redirects' => WebNewBiz_SEO::instance()->get_redirects()]]);
}

function handle_wnb_seo_sitemap() {
    wnb_check('WebNewBiz_SEO');
    $seo = WebNewBiz_SEO::instance();
    $seo->write_sitemap_file();
    wnb_ok(['data' => ['sitemap_url' => $seo->get_sitemap_url()]]);
}

function handle_wnb_seo_robots() {
    wnb_check('WebNewBiz_SEO');
    $content = $_POST['content'] ?? '';
    WebNewBiz_SEO::instance()->save_robots($content);
    wnb_ok(['data' => ['robots' => WebNewBiz_SEO::instance()->get_robots_content()]]);
}

function handle_wnb_ai_generate() {
    wnb_check('WebNewBiz_AIAssistant');
    $ai = WebNewBiz_AIAssistant::instance();
    if (!$ai->has_api_key()) wnb_err('Claude API key not configured');

    $result = $ai->generate_content([
        'type' => $_POST['type'] ?? 'blog_post',
        'prompt' => $_POST['prompt'] ?? '',
        'tone' => $_POST['tone'] ?? 'professional',
        'length' => $_POST['length'] ?? 'medium',
        'language' => $_POST['language'] ?? 'en',
    ]);

    if (is_wp_error($result)) wnb_err($result->get_error_message());
    wnb_ok(['data' => [
        'content' => $result,
        'stats' => $ai->get_usage_stats(),
    ]]);
}

function handle_wnb_ai_history() {
    wnb_check('WebNewBiz_AIAssistant');
    $ai = WebNewBiz_AIAssistant::instance();
    $action = $_POST['history_action'] ?? 'get';
    if ($action === 'clear') $ai->clear_history();
    wnb_ok(['data' => [
        'history' => $ai->get_history(),
        'stats' => $ai->get_usage_stats(),
    ]]);
}

// ══════════════════════════════════════════
// ─── LOGO / BRANDING ────────────────────
// ══════════════════════════════════════════

function handle_logo_get() {
    $customLogoId = get_theme_mod('custom_logo');
    $logoUrl = '';
    $logoId = 0;
    $siteIcon = '';

    if ($customLogoId) {
        $logoUrl = wp_get_attachment_url($customLogoId);
        $logoId = (int) $customLogoId;
    }

    $siteIconId = get_option('site_icon');
    if ($siteIconId) {
        $siteIcon = wp_get_attachment_url($siteIconId);
    }

    wnb_ok(['data' => [
        'logo_url'  => $logoUrl ?: '',
        'logo_id'   => $logoId,
        'site_icon' => $siteIcon ?: '',
        'site_name' => get_bloginfo('name'),
    ]]);
}

function handle_logo_upload() {
    $imageUrl = $_POST['image_url'] ?? '';
    if (!$imageUrl) wnb_err('No image URL provided');

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $tmp = download_url($imageUrl, 30);
    if (is_wp_error($tmp)) {
        wnb_err('Failed to download logo: ' . $tmp->get_error_message());
    }

    $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
    $filename = 'site-logo-' . wp_generate_password(6, false) . '.' . $ext;

    $file_array = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attachId = media_handle_sideload($file_array, 0, 'Site Logo');
    if (is_wp_error($attachId)) {
        @unlink($tmp);
        wnb_err('Failed to save logo: ' . $attachId->get_error_message());
    }

    // Remove old logo attachment if different
    $oldLogoId = get_theme_mod('custom_logo');
    if ($oldLogoId && $oldLogoId != $attachId) {
        wp_delete_attachment($oldLogoId, true);
    }

    set_theme_mod('custom_logo', $attachId);
    $logoUrl = wp_get_attachment_url($attachId);

    wnb_ok(['data' => [
        'logo_url' => $logoUrl,
        'logo_id'  => $attachId,
        'message'  => 'Logo updated successfully',
    ]]);
}

function handle_logo_remove() {
    $logoId = get_theme_mod('custom_logo');
    if ($logoId) {
        wp_delete_attachment($logoId, true);
        remove_theme_mod('custom_logo');
    }
    wnb_ok(['data' => ['message' => 'Logo removed']]);
}

function handle_logo_generate() {
    // This action receives an SVG string from the backend (generated by AI)
    // and saves it as the site logo
    $svgContent = $_POST['svg_content'] ?? '';
    if (!$svgContent) wnb_err('No SVG content provided');

    $uploadDir = wp_upload_dir();
    $filename = 'ai-logo-' . wp_generate_password(6, false) . '.svg';
    $filePath = $uploadDir['path'] . '/' . $filename;

    file_put_contents($filePath, $svgContent);

    $attachment = [
        'post_mime_type' => 'image/svg+xml',
        'post_title'     => 'AI Generated Logo',
        'post_content'   => '',
        'post_status'    => 'inherit',
        'guid'           => $uploadDir['url'] . '/' . $filename,
    ];

    $attachId = wp_insert_attachment($attachment, $filePath);
    if (is_wp_error($attachId) || !$attachId) {
        @unlink($filePath);
        wnb_err('Failed to create logo attachment');
    }

    // Remove old logo
    $oldLogoId = get_theme_mod('custom_logo');
    if ($oldLogoId && $oldLogoId != $attachId) {
        wp_delete_attachment($oldLogoId, true);
    }

    set_theme_mod('custom_logo', $attachId);

    wnb_ok(['data' => [
        'logo_url' => $uploadDir['url'] . '/' . $filename,
        'logo_id'  => $attachId,
        'message'  => 'AI logo applied successfully',
    ]]);
}

// ══════════════════════════════════════════════════════════════
// ─── AI COPILOT — ELEMENTOR ─────────────────────────────────
// ══════════════════════════════════════════════════════════════

/**
 * Get full Elementor data for a page.
 * POST params: page_id
 */
function handle_elementor_page_get() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $post = get_post($pageId);
    if (!$post) wnb_err('Page not found');

    $elementorData = get_post_meta($pageId, '_elementor_data', true);
    $editMode = get_post_meta($pageId, '_elementor_edit_mode', true);
    $pageSettings = get_post_meta($pageId, '_elementor_page_settings', true);

    wnb_ok(['data' => [
        'page_id'        => $pageId,
        'title'          => $post->post_title,
        'status'         => $post->post_status,
        'post_type'      => $post->post_type,
        'url'            => get_permalink($pageId),
        'edit_mode'      => $editMode ?: 'builder',
        'page_settings'  => $pageSettings ?: [],
        'elementor_data' => $elementorData ? json_decode($elementorData, true) : [],
    ]]);
}

/**
 * Update Elementor data for a page (with cache clearing).
 * POST params: page_id, elementor_data (JSON string), title (optional)
 */
function handle_elementor_page_update() {
    global $wpdb;
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $post = get_post($pageId);
    if (!$post) wnb_err('Page not found');

    // Accept base64-encoded data (avoids form-encoding corruption) or raw JSON
    $newData = '';
    if (!empty($_POST['elementor_data_b64'])) {
        $newData = base64_decode($_POST['elementor_data_b64']);
    } elseif (!empty($_POST['elementor_data'])) {
        $newData = $_POST['elementor_data'];
    }
    if (!$newData) wnb_err('Missing elementor_data');

    // Validate JSON
    $decoded = json_decode($newData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        wnb_err('Invalid elementor_data JSON: ' . json_last_error_msg());
    }

    // Store before-state for undo
    $beforeData = get_post_meta($pageId, '_elementor_data', true);

    // Update Elementor data
    update_post_meta($pageId, '_elementor_data', wp_slash($newData));
    update_post_meta($pageId, '_elementor_edit_mode', 'builder');

    // Update title if provided
    if (!empty($_POST['title'])) {
        wp_update_post(['ID' => $pageId, 'post_title' => sanitize_text_field($_POST['title'])]);
    }

    // Clear Elementor caches for this page
    delete_post_meta($pageId, '_elementor_element_cache');

    // Delete CSS cache file
    $uploadDir = wp_upload_dir();
    $cssFile = $uploadDir['basedir'] . '/elementor/css/post-' . $pageId . '.css';
    if (file_exists($cssFile)) @unlink($cssFile);

    // Mark CSS as needing regeneration
    update_post_meta($pageId, '_elementor_css', '');

    wnb_ok(['data' => [
        'page_id'     => $pageId,
        'before_data' => $beforeData ?: '[]',
        'message'     => 'Elementor data updated',
    ]]);
}

/**
 * Extract editable elements from a page's Elementor data.
 * Returns a flat list of editable widgets with their IDs and content.
 * POST params: page_id
 */
function handle_elementor_page_editables() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $elementorData = get_post_meta($pageId, '_elementor_data', true);
    if (!$elementorData) wnb_err('No Elementor data found for this page');

    $data = json_decode($elementorData, true);
    if (!$data) wnb_err('Invalid Elementor data');

    $editables = [];
    _extract_editables_recursive($data, $editables);

    wnb_ok(['data' => [
        'page_id'   => $pageId,
        'title'     => get_the_title($pageId),
        'editables' => $editables,
        'total'     => count($editables),
    ]]);
}

/**
 * Recursively extract editable elements from Elementor tree.
 */
function _extract_editables_recursive($elements, &$editables, $path = '') {
    if (!is_array($elements)) return;

    foreach ($elements as $idx => $el) {
        $elType = $el['elType'] ?? '';
        $widgetType = $el['widgetType'] ?? '';
        $id = $el['id'] ?? '';
        $settings = $el['settings'] ?? [];
        $currentPath = $path ? "{$path}/{$idx}" : (string)$idx;

        // Extract based on element/widget type
        if ($elType === 'widget') {
            $editable = [
                'id'          => $id,
                'widget_type' => $widgetType,
                'path'        => $currentPath,
            ];

            switch ($widgetType) {
                case 'heading':
                    $editable['fields'] = [
                        'title' => $settings['title'] ?? '',
                    ];
                    $editable['style'] = _extract_style_props($settings, ['title_color', 'typography_typography', 'typography_font_size', 'typography_font_family', 'align']);
                    break;

                case 'text-editor':
                    $editable['fields'] = [
                        'editor' => $settings['editor'] ?? '',
                    ];
                    break;

                case 'button':
                    $editable['fields'] = [
                        'text' => $settings['text'] ?? '',
                        'link' => $settings['link']['url'] ?? '',
                    ];
                    $editable['style'] = _extract_style_props($settings, ['button_text_color', 'background_color', 'typography_typography', 'border_radius']);
                    break;

                case 'image':
                    $editable['fields'] = [
                        'image_url' => $settings['image']['url'] ?? '',
                        'image_id'  => $settings['image']['id'] ?? '',
                        'alt'       => $settings['image']['alt'] ?? '',
                        'caption'   => $settings['caption'] ?? '',
                    ];
                    break;

                case 'icon-list':
                    $items = [];
                    foreach (($settings['icon_list'] ?? []) as $item) {
                        $items[] = [
                            'text' => $item['text'] ?? '',
                            'link' => $item['link']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['items' => $items];
                    break;

                case 'icon-box':
                    $editable['fields'] = [
                        'title_text'   => $settings['title_text'] ?? '',
                        'description_text' => $settings['description_text'] ?? '',
                        'link'         => $settings['link']['url'] ?? '',
                    ];
                    break;

                case 'image-box':
                    $editable['fields'] = [
                        'title_text'   => $settings['title_text'] ?? '',
                        'description_text' => $settings['description_text'] ?? '',
                        'image_url'    => $settings['image']['url'] ?? '',
                    ];
                    break;

                case 'counter':
                    $editable['fields'] = [
                        'starting_number' => $settings['starting_number'] ?? '',
                        'ending_number'   => $settings['ending_number'] ?? '',
                        'prefix'          => $settings['prefix'] ?? '',
                        'suffix'          => $settings['suffix'] ?? '',
                        'title'           => $settings['title'] ?? '',
                    ];
                    break;

                case 'progress':
                    $editable['fields'] = [
                        'title'   => $settings['title'] ?? '',
                        'percent' => $settings['percent'] ?? '',
                    ];
                    break;

                case 'testimonial':
                    $editable['fields'] = [
                        'testimonial_content' => $settings['testimonial_content'] ?? '',
                        'testimonial_name'    => $settings['testimonial_name'] ?? '',
                        'testimonial_job'     => $settings['testimonial_job'] ?? '',
                    ];
                    break;

                case 'tabs':
                case 'accordion':
                case 'toggle':
                    $items = [];
                    $tabKey = ($widgetType === 'tabs') ? 'tabs' : (($widgetType === 'accordion') ? 'tabs' : 'tabs');
                    foreach (($settings[$tabKey] ?? []) as $tab) {
                        $items[] = [
                            'title'   => $tab['tab_title'] ?? '',
                            'content' => $tab['tab_content'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['items' => $items];
                    break;

                case 'price-list':
                    $items = [];
                    foreach (($settings['price_list'] ?? []) as $item) {
                        $items[] = [
                            'title'       => $item['title'] ?? '',
                            'description' => $item['item_description'] ?? '',
                            'price'       => $item['price'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['items' => $items];
                    break;

                case 'price-table':
                    $editable['fields'] = [
                        'heading'     => $settings['heading'] ?? '',
                        'sub_heading' => $settings['sub_heading'] ?? '',
                        'price'       => $settings['price'] ?? '',
                        'currency'    => $settings['currency_symbol'] ?? '',
                        'period'      => $settings['period'] ?? '',
                        'button_text' => $settings['button_text'] ?? '',
                        'button_url'  => $settings['button_url']['url'] ?? '',
                        'ribbon_text' => $settings['ribbon_title'] ?? '',
                    ];
                    $features = [];
                    foreach (($settings['features_list'] ?? []) as $f) {
                        $features[] = $f['item_text'] ?? '';
                    }
                    $editable['fields']['features'] = $features;
                    break;

                case 'call-to-action':
                    $editable['fields'] = [
                        'title'       => $settings['title'] ?? '',
                        'description' => $settings['description'] ?? '',
                        'button'      => $settings['button'] ?? '',
                        'ribbon_text' => $settings['ribbon_title'] ?? '',
                    ];
                    break;

                case 'form':
                    $fields = [];
                    foreach (($settings['form_fields'] ?? []) as $f) {
                        $fields[] = [
                            'field_id'    => $f['custom_id'] ?? '',
                            'field_label' => $f['field_label'] ?? '',
                            'field_type'  => $f['field_type'] ?? '',
                            'placeholder' => $f['placeholder'] ?? '',
                            'required'    => $f['required'] ?? '',
                        ];
                    }
                    $editable['fields'] = [
                        'form_name' => $settings['form_name'] ?? '',
                        'fields'    => $fields,
                        'button_text' => $settings['submit_button'] ?? $settings['button_text'] ?? '',
                    ];
                    break;

                case 'nav-menu':
                case 'wp-widget-nav_menu':
                    $editable['fields'] = [
                        'menu_id' => $settings['menu'] ?? $settings['nav_menu'] ?? '',
                    ];
                    break;

                case 'google-maps':
                    $editable['fields'] = [
                        'address' => $settings['address'] ?? '',
                    ];
                    break;

                case 'social-icons':
                    $icons = [];
                    foreach (($settings['social_icon_list'] ?? []) as $icon) {
                        $icons[] = [
                            'social' => $icon['social_icon']['value'] ?? '',
                            'link'   => $icon['link']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['icons' => $icons];
                    break;

                // Biddut theme custom widgets
                case 'tp-slider':
                    $slides = [];
                    foreach (($settings['slider_list'] ?? []) as $s) {
                        $slides[] = [
                            'title'    => $s['tp_slider_title'] ?? '',
                            'subtitle' => $s['tp_slider_sub_title'] ?? '',
                            'btn_text' => $s['tp_btn_btn_text'] ?? '',
                            'btn_link' => $s['tp_btn_btn_link']['url'] ?? '',
                            'image'    => $s['tp_slider_image']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['slides' => $slides];
                    break;

                case 'tp-team':
                    $members = [];
                    foreach (($settings['teams'] ?? []) as $t) {
                        $members[] = [
                            'name'  => $t['title'] ?? '',
                            'role'  => $t['designation'] ?? '',
                            'image' => $t['image']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['members' => $members];
                    break;

                case 'tp-services-box':
                    $services = [];
                    foreach (($settings['tp_service_list'] ?? []) as $s) {
                        $services[] = [
                            'title'       => $s['tp_service_title'] ?? '',
                            'description' => $s['tp_service_description'] ?? '',
                            'image'       => $s['tp_box_image']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['services' => $services];
                    break;

                case 'tp-testimonial-2':
                    $reviews = [];
                    foreach (($settings['reviews_list'] ?? []) as $r) {
                        $reviews[] = [
                            'name'   => $r['reviewer_name'] ?? '',
                            'review' => $r['review_content'] ?? '',
                            'image'  => $r['reviewer_image']['url'] ?? '',
                        ];
                    }
                    $editable['fields'] = ['reviews' => $reviews];
                    break;

                default:
                    // For unknown widgets, try to extract common text fields
                    $commonFields = [];
                    foreach (['title', 'description', 'content', 'text', 'heading', 'editor'] as $key) {
                        if (isset($settings[$key]) && is_string($settings[$key]) && trim($settings[$key]) !== '') {
                            $commonFields[$key] = $settings[$key];
                        }
                    }
                    if (!empty($commonFields)) {
                        $editable['fields'] = $commonFields;
                    } else {
                        continue 2; // Skip widgets with no editable content
                    }
                    break;
            }

            $editables[] = $editable;
        }

        // Extract container/section/column background info
        if (in_array($elType, ['container', 'section', 'column'])) {
            $bgImage = $settings['background_image']['url'] ?? '';
            $bgColor = $settings['background_color'] ?? '';
            $bgOverlay = $settings['background_overlay_color'] ?? '';

            if ($bgImage || $bgColor) {
                $editables[] = [
                    'id'          => $id,
                    'widget_type' => '__container',
                    'el_type'     => $elType,
                    'path'        => $currentPath,
                    'fields'      => [],
                    'style'       => array_filter([
                        'background_image' => $bgImage,
                        'background_color' => $bgColor,
                        'background_overlay_color' => $bgOverlay,
                    ]),
                ];
            }
        }

        // Recurse into children
        if (!empty($el['elements'])) {
            _extract_editables_recursive($el['elements'], $editables, $currentPath);
        }
    }
}

/**
 * Extract style properties from settings.
 */
function _extract_style_props($settings, $keys) {
    $result = [];
    foreach ($keys as $key) {
        if (isset($settings[$key]) && $settings[$key] !== '') {
            $result[$key] = $settings[$key];
        }
    }
    return $result;
}

/**
 * Create a new Elementor page.
 * POST params: title, elementor_data (JSON string), status (optional, default 'publish'), template (optional)
 */
function handle_elementor_page_create() {
    $title = sanitize_text_field($_POST['title'] ?? '');
    if (!$title) wnb_err('Missing title');

    $elementorData = $_POST['elementor_data'] ?? '[]';
    $status = sanitize_text_field($_POST['status'] ?? 'publish');
    $template = sanitize_text_field($_POST['template'] ?? 'elementor_header_footer');

    // Validate JSON
    $decoded = json_decode($elementorData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        wnb_err('Invalid elementor_data JSON');
    }

    $pageId = wp_insert_post([
        'post_title'  => $title,
        'post_status' => $status,
        'post_type'   => 'page',
        'post_content' => '',
    ]);

    if (is_wp_error($pageId)) {
        wnb_err($pageId->get_error_message());
    }

    // Set Elementor meta
    update_post_meta($pageId, '_elementor_data', wp_slash($elementorData));
    update_post_meta($pageId, '_elementor_edit_mode', 'builder');
    update_post_meta($pageId, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.25.0');
    update_post_meta($pageId, '_wp_page_template', $template);

    wnb_ok(['data' => [
        'page_id' => $pageId,
        'title'   => $title,
        'url'     => get_permalink($pageId),
        'message' => "Page '{$title}' created",
    ]]);
}

/**
 * Add a section/container to a page at a specific position.
 * POST params: page_id, position (int, -1 for end), section_data (JSON)
 */
function handle_elementor_section_add() {
    global $wpdb;
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $sectionData = $_POST['section_data'] ?? '';
    if (!$sectionData) wnb_err('Missing section_data');

    $section = json_decode($sectionData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        wnb_err('Invalid section_data JSON');
    }

    $elementorData = get_post_meta($pageId, '_elementor_data', true);
    $data = $elementorData ? json_decode($elementorData, true) : [];
    if (!is_array($data)) $data = [];

    $position = intval($_POST['position'] ?? -1);
    if ($position < 0 || $position >= count($data)) {
        $data[] = $section;
    } else {
        array_splice($data, $position, 0, [$section]);
    }

    $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $beforeData = $elementorData ?: '[]';
    update_post_meta($pageId, '_elementor_data', wp_slash($newJson));
    delete_post_meta($pageId, '_elementor_element_cache');

    wnb_ok(['data' => [
        'page_id'     => $pageId,
        'before_data' => $beforeData,
        'total_sections' => count($data),
        'message'     => 'Section added',
    ]]);
}

/**
 * Remove a section/container by element ID.
 * POST params: page_id, element_id
 */
function handle_elementor_section_remove() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $elementId = $_POST['element_id'] ?? '';
    if (!$elementId) wnb_err('Missing element_id');

    $elementorData = get_post_meta($pageId, '_elementor_data', true);
    $data = $elementorData ? json_decode($elementorData, true) : [];
    if (!is_array($data)) wnb_err('No Elementor data');

    $beforeData = $elementorData;
    $removed = _remove_element_by_id($data, $elementId);

    if (!$removed) wnb_err("Element '{$elementId}' not found");

    $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    update_post_meta($pageId, '_elementor_data', wp_slash($newJson));
    delete_post_meta($pageId, '_elementor_element_cache');

    wnb_ok(['data' => [
        'page_id'     => $pageId,
        'before_data' => $beforeData,
        'message'     => "Element '{$elementId}' removed",
    ]]);
}

function _remove_element_by_id(&$elements, $targetId) {
    foreach ($elements as $idx => &$el) {
        if (($el['id'] ?? '') === $targetId) {
            array_splice($elements, $idx, 1);
            return true;
        }
        if (!empty($el['elements']) && _remove_element_by_id($el['elements'], $targetId)) {
            return true;
        }
    }
    return false;
}

/**
 * Reorder top-level sections.
 * POST params: page_id, order (JSON array of element IDs in desired order)
 */
function handle_elementor_section_reorder() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $order = $_POST['order'] ?? '';
    $orderArr = json_decode($order, true);
    if (!is_array($orderArr)) wnb_err('Invalid order — must be JSON array of element IDs');

    $elementorData = get_post_meta($pageId, '_elementor_data', true);
    $data = $elementorData ? json_decode($elementorData, true) : [];
    if (!is_array($data)) wnb_err('No Elementor data');

    $beforeData = $elementorData;

    // Build lookup by ID
    $byId = [];
    foreach ($data as $el) {
        $byId[$el['id'] ?? ''] = $el;
    }

    $reordered = [];
    foreach ($orderArr as $id) {
        if (isset($byId[$id])) {
            $reordered[] = $byId[$id];
            unset($byId[$id]);
        }
    }
    // Append any remaining sections not in the order array
    foreach ($byId as $el) {
        $reordered[] = $el;
    }

    $newJson = json_encode($reordered, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    update_post_meta($pageId, '_elementor_data', wp_slash($newJson));
    delete_post_meta($pageId, '_elementor_element_cache');

    wnb_ok(['data' => [
        'page_id'     => $pageId,
        'before_data' => $beforeData,
        'message'     => 'Sections reordered',
    ]]);
}

/**
 * Force regenerate Elementor CSS for a page (or all pages).
 * POST params: page_id (optional, 0 = all)
 */
function handle_elementor_css_regenerate() {
    global $wpdb;
    $pageId = intval($_POST['page_id'] ?? 0);

    if ($pageId > 0) {
        // Single page
        delete_post_meta($pageId, '_elementor_element_cache');
        delete_post_meta($pageId, '_elementor_css');

        $uploadDir = wp_upload_dir();
        $cssFile = $uploadDir['basedir'] . '/elementor/css/post-' . $pageId . '.css';
        if (file_exists($cssFile)) @unlink($cssFile);

        wnb_ok(['message' => "CSS regeneration triggered for page {$pageId}"]);
    } else {
        // All pages
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_element_cache'");
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_css'");

        $uploadDir = wp_upload_dir();
        $cssDir = $uploadDir['basedir'] . '/elementor/css';
        if (is_dir($cssDir)) {
            array_map('unlink', glob($cssDir . '/*.css'));
        }

        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }

        wnb_ok(['message' => 'CSS regeneration triggered for all pages']);
    }
}

/**
 * Get/set Elementor global colors.
 * POST params: colors (JSON, optional — if provided, sets; otherwise gets)
 */
function handle_elementor_global_colors() {
    global $wpdb;

    // Find the active kit
    $kitId = intval(get_option('elementor_active_kit', 0));
    if (!$kitId) wnb_err('No Elementor active kit found');

    $kitData = get_post_meta($kitId, '_elementor_page_settings', true);
    if (!is_array($kitData)) $kitData = [];

    $colorsInput = $_POST['colors'] ?? '';

    if ($colorsInput) {
        // SET mode
        $newColors = json_decode($colorsInput, true);
        if (!is_array($newColors)) wnb_err('Invalid colors JSON');

        $kitData['system_colors'] = $newColors;
        update_post_meta($kitId, '_elementor_page_settings', $kitData);

        // Clear CSS cache
        delete_post_meta($kitId, '_elementor_css');
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_css'");

        wnb_ok(['data' => ['colors' => $newColors, 'message' => 'Global colors updated']]);
    } else {
        // GET mode
        $colors = $kitData['system_colors'] ?? [];
        wnb_ok(['data' => ['colors' => $colors, 'kit_id' => $kitId]]);
    }
}

/**
 * Get/set Elementor global fonts.
 * POST params: fonts (JSON, optional)
 */
function handle_elementor_global_fonts() {
    global $wpdb;

    $kitId = intval(get_option('elementor_active_kit', 0));
    if (!$kitId) wnb_err('No Elementor active kit found');

    $kitData = get_post_meta($kitId, '_elementor_page_settings', true);
    if (!is_array($kitData)) $kitData = [];

    $fontsInput = $_POST['fonts'] ?? '';

    if ($fontsInput) {
        $newFonts = json_decode($fontsInput, true);
        if (!is_array($newFonts)) wnb_err('Invalid fonts JSON');

        $kitData['system_typography'] = $newFonts;
        update_post_meta($kitId, '_elementor_page_settings', $kitData);

        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_css'");

        wnb_ok(['data' => ['fonts' => $newFonts, 'message' => 'Global fonts updated']]);
    } else {
        $fonts = $kitData['system_typography'] ?? [];
        wnb_ok(['data' => ['fonts' => $fonts, 'kit_id' => $kitId]]);
    }
}

// ══════════════════════════════════════════════════════════════
// ─── AI COPILOT — MEDIA ─────────────────────────────────────
// ══════════════════════════════════════════════════════════════

/**
 * Upload media from URL.
 * POST params: image_url, alt_text (optional), title (optional)
 */
function handle_media_upload_url() {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $imageUrl = $_POST['image_url'] ?? '';
    if (!$imageUrl) wnb_err('Missing image_url');

    $altText = sanitize_text_field($_POST['alt_text'] ?? '');
    $title = sanitize_text_field($_POST['title'] ?? '');

    // Download
    $tmp = download_url($imageUrl, 60);
    if (is_wp_error($tmp)) {
        wnb_err('Download failed: ' . $tmp->get_error_message());
    }

    $urlPath = parse_url($imageUrl, PHP_URL_PATH);
    $filename = basename($urlPath);
    if (!$filename || strlen($filename) < 3) {
        $filename = 'image-' . time() . '.jpg';
    }

    $fileArray = [
        'name'     => $filename,
        'tmp_name' => $tmp,
    ];

    $attachId = media_handle_sideload($fileArray, 0, $title ?: $filename);
    if (is_wp_error($attachId)) {
        @unlink($tmp);
        wnb_err('Upload failed: ' . $attachId->get_error_message());
    }

    if ($altText) {
        update_post_meta($attachId, '_wp_attachment_image_alt', $altText);
    }

    wnb_ok(['data' => [
        'attachment_id' => $attachId,
        'url'           => wp_get_attachment_url($attachId),
        'title'         => $title ?: $filename,
        'alt'           => $altText,
        'message'       => 'Image uploaded',
    ]]);
}

/**
 * List media items.
 * POST params: per_page (optional, default 20), page (optional, default 1), type (optional: image, video, etc.)
 */
function handle_media_list() {
    $perPage = intval($_POST['per_page'] ?? 20);
    $page = intval($_POST['page'] ?? 1);
    $type = sanitize_text_field($_POST['type'] ?? 'image');

    $args = [
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => min($perPage, 100),
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($type) {
        $args['post_mime_type'] = $type;
    }

    $query = new WP_Query($args);
    $items = [];

    foreach ($query->posts as $att) {
        $meta = wp_get_attachment_metadata($att->ID);
        $items[] = [
            'id'       => $att->ID,
            'url'      => wp_get_attachment_url($att->ID),
            'title'    => $att->post_title,
            'alt'      => get_post_meta($att->ID, '_wp_attachment_image_alt', true),
            'mime'     => $att->post_mime_type,
            'width'    => $meta['width'] ?? null,
            'height'   => $meta['height'] ?? null,
            'filesize' => $meta['filesize'] ?? null,
            'date'     => $att->post_date,
        ];
    }

    wnb_ok(['data' => [
        'items' => $items,
        'total' => $query->found_posts,
        'pages' => $query->max_num_pages,
    ]]);
}

// ══════════════════════════════════════════════════════════════
// ─── AI COPILOT — MENUS ─────────────────────────────────────
// ══════════════════════════════════════════════════════════════

/**
 * Get all menus and their items.
 */
function handle_menu_list() {
    $menus = wp_get_nav_menus();
    $result = [];

    foreach ($menus as $menu) {
        $items = wp_get_nav_menu_items($menu->term_id);
        $menuItems = [];
        if ($items) {
            foreach ($items as $item) {
                $menuItems[] = [
                    'id'        => $item->ID,
                    'title'     => $item->title,
                    'url'       => $item->url,
                    'type'      => $item->type,
                    'object'    => $item->object,
                    'object_id' => $item->object_id,
                    'parent'    => $item->menu_item_parent,
                    'order'     => $item->menu_order,
                    'classes'   => $item->classes,
                ];
            }
        }

        $locations = get_nav_menu_locations();
        $assignedTo = [];
        foreach ($locations as $loc => $menuId) {
            if ($menuId == $menu->term_id) {
                $assignedTo[] = $loc;
            }
        }

        $result[] = [
            'id'          => $menu->term_id,
            'name'        => $menu->name,
            'slug'        => $menu->slug,
            'count'       => $menu->count,
            'locations'   => $assignedTo,
            'items'       => $menuItems,
        ];
    }

    wnb_ok(['data' => $result]);
}

/**
 * Update menu items.
 * POST params: menu_id, items (JSON array of {title, url, type, object, object_id, parent, order})
 */
function handle_menu_update() {
    $menuId = intval($_POST['menu_id'] ?? 0);
    if (!$menuId) wnb_err('Missing menu_id');

    $itemsJson = $_POST['items'] ?? '';
    $items = json_decode($itemsJson, true);
    if (!is_array($items)) wnb_err('Invalid items JSON');

    $menu = wp_get_nav_menu_object($menuId);
    if (!$menu) wnb_err("Menu not found: {$menuId}");

    // Remove existing items
    $existingItems = wp_get_nav_menu_items($menuId);
    if ($existingItems) {
        foreach ($existingItems as $item) {
            wp_delete_post($item->ID, true);
        }
    }

    // Add new items
    $createdItems = [];
    foreach ($items as $idx => $item) {
        $args = [
            'menu-item-title'     => $item['title'] ?? 'Menu Item',
            'menu-item-url'       => $item['url'] ?? '#',
            'menu-item-status'    => 'publish',
            'menu-item-position'  => $item['order'] ?? ($idx + 1),
            'menu-item-type'      => $item['type'] ?? 'custom',
        ];

        if (($item['type'] ?? '') === 'post_type' && !empty($item['object_id'])) {
            $args['menu-item-object'] = $item['object'] ?? 'page';
            $args['menu-item-object-id'] = $item['object_id'];
        }

        if (!empty($item['parent'])) {
            $args['menu-item-parent-id'] = $item['parent'];
        }

        $newItemId = wp_update_nav_menu_item($menuId, 0, $args);
        if (!is_wp_error($newItemId)) {
            $createdItems[] = $newItemId;
        }
    }

    wnb_ok(['data' => [
        'menu_id'  => $menuId,
        'items'    => count($createdItems),
        'message'  => 'Menu updated',
    ]]);
}

// ══════════════════════════════════════════════════════════════
// ─── AI COPILOT — SEO (PAGE-LEVEL) ─────────────────────────
// ══════════════════════════════════════════════════════════════

/**
 * Get SEO meta for a page.
 * POST params: page_id
 */
function handle_seo_page_get() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $post = get_post($pageId);
    if (!$post) wnb_err('Page not found');

    // Try Yoast, RankMath, or raw meta
    $meta = [
        'title'       => get_post_meta($pageId, '_yoast_wpseo_title', true)
                        ?: get_post_meta($pageId, 'rank_math_title', true)
                        ?: $post->post_title,
        'description' => get_post_meta($pageId, '_yoast_wpseo_metadesc', true)
                        ?: get_post_meta($pageId, 'rank_math_description', true)
                        ?: '',
        'focus_keyword' => get_post_meta($pageId, '_yoast_wpseo_focuskw', true)
                          ?: get_post_meta($pageId, 'rank_math_focus_keyword', true)
                          ?: '',
        'og_title'    => get_post_meta($pageId, '_yoast_wpseo_opengraph-title', true)
                        ?: get_post_meta($pageId, 'rank_math_facebook_title', true) ?: '',
        'og_desc'     => get_post_meta($pageId, '_yoast_wpseo_opengraph-description', true)
                        ?: get_post_meta($pageId, 'rank_math_facebook_description', true) ?: '',
        'canonical'   => get_post_meta($pageId, '_yoast_wpseo_canonical', true)
                        ?: get_post_meta($pageId, 'rank_math_canonical_url', true) ?: '',
    ];

    wnb_ok(['data' => [
        'page_id'  => $pageId,
        'title'    => $post->post_title,
        'url'      => get_permalink($pageId),
        'seo_meta' => $meta,
    ]]);
}

/**
 * Update SEO meta for a page.
 * POST params: page_id, seo_title, seo_description, focus_keyword, og_title, og_desc, canonical
 */
function handle_seo_page_update() {
    $pageId = intval($_POST['page_id'] ?? 0);
    if (!$pageId) wnb_err('Missing page_id');

    $post = get_post($pageId);
    if (!$post) wnb_err('Page not found');

    // Detect which SEO plugin is active
    $isYoast = defined('WPSEO_VERSION');
    $isRankMath = class_exists('RankMath');

    $fields = [
        'seo_title'      => $_POST['seo_title'] ?? null,
        'seo_description' => $_POST['seo_description'] ?? null,
        'focus_keyword'  => $_POST['focus_keyword'] ?? null,
        'og_title'       => $_POST['og_title'] ?? null,
        'og_desc'        => $_POST['og_desc'] ?? null,
        'canonical'      => $_POST['canonical'] ?? null,
    ];

    $updated = [];
    foreach ($fields as $key => $val) {
        if ($val === null) continue;

        $val = sanitize_text_field($val);

        if ($isYoast) {
            $yoastKey = match($key) {
                'seo_title'      => '_yoast_wpseo_title',
                'seo_description' => '_yoast_wpseo_metadesc',
                'focus_keyword'  => '_yoast_wpseo_focuskw',
                'og_title'       => '_yoast_wpseo_opengraph-title',
                'og_desc'        => '_yoast_wpseo_opengraph-description',
                'canonical'      => '_yoast_wpseo_canonical',
            };
            update_post_meta($pageId, $yoastKey, $val);
        } elseif ($isRankMath) {
            $rmKey = match($key) {
                'seo_title'      => 'rank_math_title',
                'seo_description' => 'rank_math_description',
                'focus_keyword'  => 'rank_math_focus_keyword',
                'og_title'       => 'rank_math_facebook_title',
                'og_desc'        => 'rank_math_facebook_description',
                'canonical'      => 'rank_math_canonical_url',
            };
            update_post_meta($pageId, $rmKey, $val);
        } else {
            // Fallback: store as custom meta
            update_post_meta($pageId, '_wnb_seo_' . $key, $val);
        }

        $updated[] = $key;
    }

    wnb_ok(['data' => [
        'page_id' => $pageId,
        'updated' => $updated,
        'message' => 'SEO meta updated',
    ]]);
}

// ══════════════════════════════════════════════════════════════
// ─── AI COPILOT — POSTS ─────────────────────────────────────
// ══════════════════════════════════════════════════════════════

/**
 * Create a new post/page.
 * POST params: title, content, post_type (optional: post/page), status (optional), categories (JSON array of IDs)
 */
function handle_posts_create() {
    $title = sanitize_text_field($_POST['title'] ?? '');
    if (!$title) wnb_err('Missing title');

    $content = $_POST['content'] ?? '';
    $postType = sanitize_text_field($_POST['post_type'] ?? 'post');
    $status = sanitize_text_field($_POST['status'] ?? 'publish');

    $postId = wp_insert_post([
        'post_title'   => $title,
        'post_content' => $content,
        'post_type'    => $postType,
        'post_status'  => $status,
    ]);

    if (is_wp_error($postId)) {
        wnb_err($postId->get_error_message());
    }

    // Assign categories if provided
    $categories = $_POST['categories'] ?? '';
    if ($categories) {
        $catIds = json_decode($categories, true);
        if (is_array($catIds)) {
            wp_set_post_categories($postId, $catIds);
        }
    }

    wnb_ok(['data' => [
        'post_id' => $postId,
        'title'   => $title,
        'url'     => get_permalink($postId),
        'message' => ucfirst($postType) . " '{$title}' created",
    ]]);
}

/**
 * Update a post/page.
 * POST params: post_id, title (optional), content (optional), status (optional)
 */
function handle_posts_update() {
    $postId = intval($_POST['post_id'] ?? 0);
    if (!$postId) wnb_err('Missing post_id');

    $post = get_post($postId);
    if (!$post) wnb_err('Post not found');

    $args = ['ID' => $postId];
    if (isset($_POST['title'])) $args['post_title'] = sanitize_text_field($_POST['title']);
    if (isset($_POST['content'])) $args['post_content'] = $_POST['content'];
    if (isset($_POST['status'])) $args['post_status'] = sanitize_text_field($_POST['status']);

    $result = wp_update_post($args);
    if (is_wp_error($result)) {
        wnb_err($result->get_error_message());
    }

    wnb_ok(['data' => [
        'post_id' => $postId,
        'message' => 'Post updated',
    ]]);
}

/**
 * Delete a post/page.
 * POST params: post_id, force (optional: '1' to permanently delete)
 */
function handle_posts_delete() {
    $postId = intval($_POST['post_id'] ?? 0);
    if (!$postId) wnb_err('Missing post_id');

    $force = ($_POST['force'] ?? '0') === '1';

    $result = wp_delete_post($postId, $force);
    if (!$result) wnb_err('Failed to delete post');

    wnb_ok(['data' => [
        'post_id' => $postId,
        'message' => $force ? 'Post permanently deleted' : 'Post moved to trash',
    ]]);
}
