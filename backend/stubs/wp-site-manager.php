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
