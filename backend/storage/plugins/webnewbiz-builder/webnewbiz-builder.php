<?php
/**
 * Plugin Name: WebNewBiz Builder
 * Plugin URI: https://webnewbiz.com
 * Description: WebNewBiz is an ultimate premium tool, based on Elementor, to create websites with stunning design.
 * Version: 1.0.0
 * Author: WebNewBiz
 * Author URI: https://webnewbiz.com
 * License: Proprietary
 * Text Domain: webnewbiz-builder
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if (!defined('ABSPATH')) exit;

define('WEBNEWBIZ_VERSION', '1.0.0');
define('WEBNEWBIZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WEBNEWBIZ_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WEBNEWBIZ_PLUGIN_FILE', __FILE__);

/**
 * Main WebNewBiz Builder class
 */
final class WebNewBiz_Builder {

    private static ?self $instance = null;

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_includes();
        $this->init_modules();
        $this->init_hooks();
    }

    /**
     * Initialize feature module singletons
     */
    private function init_modules(): void {
        if (class_exists('WebNewBiz_Cache'))          WebNewBiz_Cache::instance();
        if (class_exists('WebNewBiz_Performance'))     WebNewBiz_Performance::instance();
        if (class_exists('WebNewBiz_Database'))        WebNewBiz_Database::instance();
        if (class_exists('WebNewBiz_ImageOptimizer'))  WebNewBiz_ImageOptimizer::instance();
        if (class_exists('WebNewBiz_Security'))        WebNewBiz_Security::instance();
        if (class_exists('WebNewBiz_Backup'))          WebNewBiz_Backup::instance();
        if (class_exists('WebNewBiz_Maintenance'))     WebNewBiz_Maintenance::instance();
        if (class_exists('WebNewBiz_WhiteLabel'))      WebNewBiz_WhiteLabel::instance();
        if (class_exists('WebNewBiz_SEO'))             WebNewBiz_SEO::instance();
        if (class_exists('WebNewBiz_Analytics'))        WebNewBiz_Analytics::instance();
        if (class_exists('WebNewBiz_AIAssistant'))      WebNewBiz_AIAssistant::instance();
    }

    /**
     * Load all module include files
     */
    private function load_includes(): void {
        $includes = [
            'class-admin.php',
            'class-connector.php',
            'class-cache.php',
            'class-performance.php',
            'class-security.php',
            'class-seo.php',
            'class-database.php',
            'class-backup.php',
            'class-image-optimizer.php',
            'class-analytics.php',
            'class-maintenance.php',
            'class-whitelabel.php',
            'class-ai-assistant.php',
        ];

        foreach ($includes as $file) {
            $path = WEBNEWBIZ_PLUGIN_DIR . 'includes/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Initialize all WordPress hooks
     */
    private function init_hooks(): void {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_bar_menu', [$this, 'admin_bar_item'], 100);
        add_action('wp_dashboard_setup', [$this, 'register_dashboard_widget']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'plugin_action_links']);
        add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2);

        // Disable auto-update offers for this plugin (managed by platform)
        add_filter('auto_update_plugin', [$this, 'disable_auto_update'], 10, 2);

        // Load AI chatbot inside Elementor editor
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_elementor_chatbot']);

        // Add "Premium" badge next to plugin name
        add_filter('all_plugins', [$this, 'modify_plugin_info']);

        // Register all AJAX handlers
        $this->register_ajax_handlers();
    }

    /**
     * Register all wp_ajax_ hooks for module AJAX operations
     */
    private function register_ajax_handlers(): void {
        // Module classes register their own AJAX handlers in constructors:
        // - class-cache: wnb_purge_cache, wnb_save_cache_settings
        // - class-performance: wnb_save_performance_settings
        // - class-database: wnb_db_cleanup, wnb_db_optimize, wnb_db_stats
        // - class-image-optimizer: wnb_optimize_images, wnb_save_image_settings, wnb_image_stats
        // - class-security: wnb_save_security_settings, wnb_get_security_score, wnb_get_activity_log
        // - class-backup: wnb_create_backup, wnb_delete_backup, wnb_restore_backup, wnb_list_backups
        // - class-seo: wnb_save_seo_settings, wnb_generate_sitemap, wnb_save_redirect, wnb_delete_redirect, wnb_save_robots
        // - class-ai-assistant: wnb_ai_generate, wnb_ai_save_key, wnb_ai_seo_generate, wnb_ai_history
        // - class-maintenance: wnb_toggle_maintenance, wnb_save_maintenance_settings
        // - class-whitelabel: wnb_save_whitelabel_settings
        // - class-analytics: wnb_get_analytics, wnb_get_popular_pages

        // General settings (not handled by any module)
        add_action('wp_ajax_wnb_save_setting', [$this, 'ajax_save_setting']);
        add_action('wp_ajax_wnb_save_settings', [$this, 'ajax_save_settings']);

        // Analytics tracking — frontend pageview tracking (nopriv for visitors)
        add_action('wp_ajax_wnb_track_pageview', [$this, 'ajax_track_pageview']);
        add_action('wp_ajax_nopriv_wnb_track_pageview', [$this, 'ajax_track_pageview']);
    }

    /* ─────────────────────────────────────────────
     * AJAX Handlers
     * ───────────────────────────────────────────── */

    /**
     * AJAX: Purge cache
     */
    public function ajax_purge_cache(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $type = sanitize_text_field($_POST['cache_type'] ?? 'all');

        // Clear WordPress object cache
        if ($type === 'all' || $type === 'object') {
            wp_cache_flush();
        }

        // Clear Elementor CSS cache
        if ($type === 'all' || $type === 'elementor') {
            delete_post_meta_by_key('_elementor_css');
            delete_option('_elementor_global_css');
            $upload_dir = wp_upload_dir();
            $elementor_css = $upload_dir['basedir'] . '/elementor/css/';
            if (is_dir($elementor_css)) {
                $files = glob($elementor_css . '*.css');
                if ($files) {
                    foreach ($files as $f) {
                        @unlink($f);
                    }
                }
            }
        }

        // Clear transients
        if ($type === 'all' || $type === 'transients') {
            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
            $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
        }

        // Clear page cache directory if exists
        if ($type === 'all' || $type === 'page') {
            $cache_dir = WP_CONTENT_DIR . '/cache/webnewbiz/';
            if (is_dir($cache_dir)) {
                $this->recursive_delete($cache_dir);
            }
        }

        update_option('wnb_last_cache_purge', current_time('mysql'));

        wp_send_json_success([
            'message' => 'Cache purged successfully',
            'type' => $type,
            'time' => current_time('M j, Y g:i A'),
        ]);
    }

    /**
     * AJAX: Save individual setting (toggle)
     */
    public function ajax_save_setting(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $key = sanitize_key($_POST['key'] ?? '');
        $value = sanitize_text_field($_POST['value'] ?? '');

        if (empty($key)) wp_send_json_error('Invalid setting key');

        // Whitelist of allowed option keys
        $allowed = [
            'wnb_disable_emojis', 'wnb_disable_embeds', 'wnb_remove_jquery_migrate',
            'wnb_minify_html', 'wnb_lazy_load_images', 'wnb_lazy_load_iframes',
            'wnb_dns_prefetch', 'wnb_preload_resources', 'wnb_disable_heartbeat',
            'wnb_remove_query_strings', 'wnb_disable_rss', 'wnb_disable_self_pingbacks',
            'wnb_disable_xmlrpc', 'wnb_disable_file_editor', 'wnb_hide_wp_version',
            'wnb_security_headers', 'wnb_limit_login_attempts', 'wnb_disable_user_enum',
            'wnb_block_bad_bots', 'wnb_force_ssl_admin', 'wnb_disable_php_uploads',
            'wnb_auto_purge_post_save', 'wnb_auto_purge_plugin_update',
            'wnb_auto_optimize_upload', 'wnb_webp_conversion', 'wnb_strip_exif',
            'wnb_compression_quality', 'wnb_max_image_dimensions',
            'wnb_maintenance_mode', 'wnb_maintenance_message', 'wnb_maintenance_bg_color',
            'wnb_maintenance_allow_admins', 'wnb_maintenance_custom_css',
            'wnb_maintenance_back_date',
            'wnb_whitelabel_login_logo', 'wnb_whitelabel_login_bg',
            'wnb_whitelabel_footer_text', 'wnb_whitelabel_hide_branding',
            'wnb_whitelabel_widget_title', 'wnb_whitelabel_widget_content',
            'wnb_whitelabel_remove_wp_logo',
            'wnb_db_auto_cleanup', 'wnb_db_cleanup_schedule',
            'wnb_backup_schedule', 'wnb_sitemap_enabled',
            'wnb_schema_org_name', 'wnb_schema_org_logo', 'wnb_schema_org_phone',
            'wnb_robots_txt', 'wnb_email_notifications', 'wnb_notification_email',
            'wnb_claude_api_key', 'webnewbiz_platform_url',
        ];

        if (!in_array($key, $allowed, true)) {
            wp_send_json_error('Setting not allowed: ' . $key);
        }

        update_option($key, $value);

        wp_send_json_success([
            'message' => 'Setting saved',
            'key' => $key,
            'value' => $value,
        ]);
    }

    /**
     * AJAX: Database cleanup
     */
    public function ajax_db_cleanup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        global $wpdb;
        $type = sanitize_text_field($_POST['cleanup_type'] ?? 'all');
        $cleaned = 0;

        // Post revisions
        if ($type === 'all' || $type === 'revisions') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'");
        }

        // Auto drafts
        if ($type === 'all' || $type === 'auto_drafts') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'auto-draft'");
        }

        // Trashed posts
        if ($type === 'all' || $type === 'trashed_posts') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'");
        }

        // Spam comments
        if ($type === 'all' || $type === 'spam_comments') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
        }

        // Trashed comments
        if ($type === 'all' || $type === 'trashed_comments') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'trash'");
        }

        // Expired transients
        if ($type === 'all' || $type === 'expired_transients') {
            $time = time();
            $cleaned += $wpdb->query(
                "DELETE a, b FROM {$wpdb->options} a
                 INNER JOIN {$wpdb->options} b ON b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12))
                 WHERE a.option_name LIKE '_transient_%'
                 AND a.option_name NOT LIKE '_transient_timeout_%'
                 AND b.option_value < {$time}"
            );
        }

        // All transients
        if ($type === 'transients') {
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
            $cleaned += $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
        }

        // Orphaned post meta
        if ($type === 'all' || $type === 'orphaned_meta') {
            $cleaned += $wpdb->query(
                "DELETE pm FROM {$wpdb->postmeta} pm
                 LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                 WHERE p.ID IS NULL"
            );
        }

        // Optimize tables
        if ($type === 'optimize') {
            $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
            foreach ($tables as $t) {
                $wpdb->query("OPTIMIZE TABLE `{$t[0]}`");
            }
            $cleaned = count($tables);
        }

        update_option('wnb_last_db_cleanup', current_time('mysql'));

        wp_send_json_success([
            'message' => "Cleaned {$cleaned} items",
            'cleaned' => $cleaned,
            'type' => $type,
            'time' => current_time('M j, Y g:i A'),
        ]);
    }

    /**
     * AJAX: Create backup
     */
    public function ajax_create_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $type = sanitize_text_field($_POST['backup_type'] ?? 'full');
        $backup_dir = WP_CONTENT_DIR . '/backups/webnewbiz/';

        if (!is_dir($backup_dir)) {
            wp_mkdir_p($backup_dir);
            // Add .htaccess to block direct access
            file_put_contents($backup_dir . '.htaccess', "Deny from all\n");
        }

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "backup_{$type}_{$timestamp}";
        $result = ['files' => [], 'size' => 0];

        // Database backup
        if ($type === 'full' || $type === 'database') {
            global $wpdb;
            $sql_file = $backup_dir . $filename . '.sql';
            $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
            $sql_content = "-- WebNewBiz Backup\n-- Date: " . current_time('mysql') . "\n-- Type: {$type}\n\n";

            foreach ($tables as $table) {
                $table_name = $table[0];
                $sql_content .= "DROP TABLE IF EXISTS `{$table_name}`;\n";

                $create = $wpdb->get_row("SHOW CREATE TABLE `{$table_name}`", ARRAY_N);
                $sql_content .= $create[1] . ";\n\n";

                $rows = $wpdb->get_results("SELECT * FROM `{$table_name}`", ARRAY_A);
                foreach ($rows as $row) {
                    $values = array_map(function ($v) use ($wpdb) {
                        return $v === null ? 'NULL' : "'" . $wpdb->_real_escape($v) . "'";
                    }, $row);
                    $sql_content .= "INSERT INTO `{$table_name}` VALUES(" . implode(',', $values) . ");\n";
                }
                $sql_content .= "\n";
            }

            file_put_contents($sql_file, $sql_content);
            $result['files'][] = $sql_file;
            $result['size'] += filesize($sql_file);
        }

        // Files backup (zip wp-content)
        if ($type === 'full' || $type === 'files') {
            $zip_file = $backup_dir . $filename . '.zip';
            if (class_exists('ZipArchive')) {
                $zip = new ZipArchive();
                if ($zip->open($zip_file, ZipArchive::CREATE) === true) {
                    $source = WP_CONTENT_DIR;
                    $base = dirname($source);
                    $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );
                    // Skip backup dir and large cache dirs
                    foreach ($iterator as $item) {
                        $path = $item->getPathname();
                        if (strpos($path, 'backups/webnewbiz') !== false) continue;
                        if (strpos($path, '/cache/') !== false) continue;
                        if ($item->isDir()) {
                            $zip->addEmptyDir(str_replace($base . '/', '', $path . '/'));
                        } else {
                            if ($item->getSize() < 50 * 1024 * 1024) { // Skip files > 50MB
                                $zip->addFile($path, str_replace($base . '/', '', $path));
                            }
                        }
                    }
                    $zip->close();
                    $result['files'][] = $zip_file;
                    $result['size'] += filesize($zip_file);
                }
            }
        }

        // Save backup record
        $backups = get_option('wnb_backups', []);
        $backups[] = [
            'id' => uniqid('bk_'),
            'date' => current_time('mysql'),
            'type' => $type,
            'size' => $result['size'],
            'files' => $result['files'],
            'filename' => $filename,
        ];
        update_option('wnb_backups', $backups);

        wp_send_json_success([
            'message' => 'Backup created successfully',
            'backup' => end($backups),
            'size_formatted' => size_format($result['size']),
        ]);
    }

    /**
     * AJAX: Delete backup
     */
    public function ajax_delete_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $backup_id = sanitize_text_field($_POST['backup_id'] ?? '');
        $backups = get_option('wnb_backups', []);

        foreach ($backups as $i => $backup) {
            if ($backup['id'] === $backup_id) {
                // Delete files
                foreach ($backup['files'] as $file) {
                    if (file_exists($file)) @unlink($file);
                }
                unset($backups[$i]);
                update_option('wnb_backups', array_values($backups));
                wp_send_json_success(['message' => 'Backup deleted']);
            }
        }

        wp_send_json_error('Backup not found');
    }

    /**
     * AJAX: Restore backup
     */
    public function ajax_restore_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $backup_id = sanitize_text_field($_POST['backup_id'] ?? '');
        $backups = get_option('wnb_backups', []);
        $target = null;

        foreach ($backups as $backup) {
            if ($backup['id'] === $backup_id) {
                $target = $backup;
                break;
            }
        }

        if (!$target) wp_send_json_error('Backup not found');

        // Restore database
        foreach ($target['files'] as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql' && file_exists($file)) {
                global $wpdb;
                $sql = file_get_contents($file);
                $queries = explode(";\n", $sql);
                foreach ($queries as $q) {
                    $q = trim($q);
                    if (!empty($q) && strpos($q, '--') !== 0) {
                        $wpdb->query($q);
                    }
                }
            }
        }

        wp_send_json_success(['message' => 'Database restored. Please refresh the page.']);
    }

    /**
     * AJAX: Optimize images
     */
    public function ajax_optimize_images(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $quality = (int) get_option('wnb_compression_quality', 82);
        $max_dim = (int) get_option('wnb_max_image_dimensions', 2048);
        $webp = get_option('wnb_webp_conversion', '0') === '1';
        $strip_exif = get_option('wnb_strip_exif', '1') === '1';

        $upload_dir = wp_upload_dir();
        $base = $upload_dir['basedir'];

        // Get unoptimized images
        $optimized = get_option('wnb_optimized_images', []);
        $images = glob($base . '/**/*.{jpg,jpeg,png}', GLOB_BRACE);
        if (!$images) $images = [];

        // Also check subdirectories
        $sub_images = glob($base . '/*/**/*.{jpg,jpeg,png}', GLOB_BRACE);
        if ($sub_images) $images = array_merge($images, $sub_images);

        $images = array_unique($images);
        $processed = 0;
        $saved_bytes = 0;
        $limit = 10; // Process 10 at a time

        foreach ($images as $img_path) {
            if ($processed >= $limit) break;
            $rel_path = str_replace($base, '', $img_path);
            if (isset($optimized[$rel_path])) continue;

            $original_size = filesize($img_path);
            $info = getimagesize($img_path);
            if (!$info) continue;

            $mime = $info['mime'];
            $width = $info[0];
            $height = $info[1];

            // Load image
            $image = null;
            if ($mime === 'image/jpeg') {
                $image = @imagecreatefromjpeg($img_path);
            } elseif ($mime === 'image/png') {
                $image = @imagecreatefrompng($img_path);
            }

            if (!$image) continue;

            // Resize if needed
            if ($width > $max_dim || $height > $max_dim) {
                $ratio = min($max_dim / $width, $max_dim / $height);
                $new_w = (int) ($width * $ratio);
                $new_h = (int) ($height * $ratio);
                $resized = imagecreatetruecolor($new_w, $new_h);

                if ($mime === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }

                imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }

            // Save optimized
            if ($mime === 'image/jpeg') {
                imagejpeg($image, $img_path, $quality);
            } elseif ($mime === 'image/png') {
                $png_quality = (int) ((100 - $quality) / 11.11);
                imagepng($image, $img_path, max(0, min(9, $png_quality)));
            }

            // WebP conversion
            if ($webp && function_exists('imagewebp')) {
                $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $img_path);
                imagewebp($image, $webp_path, $quality);
            }

            imagedestroy($image);

            $new_size = filesize($img_path);
            $savings = $original_size - $new_size;
            $saved_bytes += max(0, $savings);

            $optimized[$rel_path] = [
                'original_size' => $original_size,
                'optimized_size' => $new_size,
                'savings' => max(0, $savings),
                'date' => current_time('mysql'),
            ];

            $processed++;
        }

        update_option('wnb_optimized_images', $optimized);

        $total_images = count($images);
        $total_optimized = count($optimized);

        wp_send_json_success([
            'message' => "Optimized {$processed} images" . ($saved_bytes > 0 ? ', saved ' . size_format($saved_bytes) : ''),
            'processed' => $processed,
            'saved_bytes' => $saved_bytes,
            'saved_formatted' => size_format($saved_bytes),
            'total_images' => $total_images,
            'total_optimized' => $total_optimized,
            'remaining' => max(0, $total_images - $total_optimized),
        ]);
    }

    /**
     * AJAX: AI content generation
     */
    public function ajax_ai_generate(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $prompt = sanitize_textarea_field($_POST['prompt'] ?? '');
        $content_type = sanitize_text_field($_POST['content_type'] ?? 'blog_post');
        $tone = sanitize_text_field($_POST['tone'] ?? 'professional');
        $length = sanitize_text_field($_POST['length'] ?? 'medium');

        if (empty($prompt)) wp_send_json_error('Prompt is required');

        $api_key = get_option('wnb_claude_api_key', '');
        if (empty($api_key)) {
            wp_send_json_error('Claude API key not configured. Go to Settings to add it.');
        }

        // Build system prompt
        $type_labels = [
            'blog_post' => 'a blog post',
            'page_content' => 'website page content',
            'product_description' => 'a product description',
            'seo_meta' => 'SEO meta title and description',
            'faq' => 'an FAQ section with questions and answers',
            'email' => 'a professional email',
        ];
        $length_map = [
            'short' => '100-200 words',
            'medium' => '300-500 words',
            'long' => '800-1200 words',
        ];

        $system = "You are a professional content writer. Write " .
            ($type_labels[$content_type] ?? 'content') .
            " in a {$tone} tone. Target length: " .
            ($length_map[$length] ?? '300-500 words') .
            ". Output clean, well-structured HTML suitable for WordPress.";

        // Call Claude API
        $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
            'timeout' => 60,
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
                'anthropic-version' => '2023-06-01',
            ],
            'body' => json_encode([
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 4096,
                'system' => $system,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]),
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error('API request failed: ' . $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            wp_send_json_error('API error: ' . ($body['error']['message'] ?? 'Unknown error'));
        }

        $content = $body['content'][0]['text'] ?? '';

        if (empty($content)) {
            wp_send_json_error('No content generated');
        }

        // Save to history
        $history = get_option('wnb_ai_history', []);
        array_unshift($history, [
            'id' => uniqid('ai_'),
            'prompt' => $prompt,
            'content_type' => $content_type,
            'tone' => $tone,
            'length' => $length,
            'content' => $content,
            'date' => current_time('mysql'),
        ]);
        // Keep last 50 entries
        $history = array_slice($history, 0, 50);
        update_option('wnb_ai_history', $history);

        wp_send_json_success([
            'content' => $content,
            'content_type' => $content_type,
            'word_count' => str_word_count(strip_tags($content)),
        ]);
    }

    /**
     * AJAX: Toggle maintenance mode
     */
    public function ajax_toggle_maintenance(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $enabled = sanitize_text_field($_POST['enabled'] ?? '0');
        update_option('wnb_maintenance_mode', $enabled);

        wp_send_json_success([
            'message' => $enabled === '1' ? 'Maintenance mode enabled' : 'Maintenance mode disabled',
            'enabled' => $enabled,
        ]);
    }

    /**
     * AJAX: Save 301 redirect
     */
    public function ajax_save_redirect(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $from = sanitize_text_field($_POST['from'] ?? '');
        $to = esc_url_raw($_POST['to'] ?? '');

        if (empty($from) || empty($to)) wp_send_json_error('Both "From" and "To" URLs are required');

        $redirects = get_option('wnb_redirects', []);
        $redirects[] = [
            'id' => uniqid('rd_'),
            'from' => $from,
            'to' => $to,
            'date' => current_time('mysql'),
        ];
        update_option('wnb_redirects', $redirects);

        wp_send_json_success(['message' => 'Redirect saved', 'redirect' => end($redirects)]);
    }

    /**
     * AJAX: Delete redirect
     */
    public function ajax_delete_redirect(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $redirect_id = sanitize_text_field($_POST['redirect_id'] ?? '');
        $redirects = get_option('wnb_redirects', []);

        $redirects = array_values(array_filter($redirects, fn($r) => $r['id'] !== $redirect_id));
        update_option('wnb_redirects', $redirects);

        wp_send_json_success(['message' => 'Redirect deleted']);
    }

    /**
     * AJAX: Track pageview (for analytics)
     */
    public function ajax_track_pageview(): void {
        // Lightweight — no nonce for nopriv (frontend tracking)
        $url = sanitize_text_field($_POST['url'] ?? '');
        $referrer = sanitize_text_field($_POST['referrer'] ?? '');
        $ua = sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '');

        if (empty($url)) wp_die();

        // Determine device type
        $device = 'desktop';
        if (preg_match('/mobile|android|iphone|ipod/i', $ua)) {
            $device = 'mobile';
        } elseif (preg_match('/tablet|ipad/i', $ua)) {
            $device = 'tablet';
        }

        // Store in daily buckets
        $date = current_time('Y-m-d');
        $key = 'wnb_analytics_' . $date;
        $data = get_option($key, [
            'views' => 0,
            'unique_ips' => [],
            'pages' => [],
            'referrers' => [],
            'devices' => ['desktop' => 0, 'mobile' => 0, 'tablet' => 0],
        ]);

        $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
        $ip_hash = md5($ip . $date); // Privacy-friendly hash

        $data['views']++;
        if (!in_array($ip_hash, $data['unique_ips'])) {
            $data['unique_ips'][] = $ip_hash;
        }

        // Page stats
        if (!isset($data['pages'][$url])) {
            $data['pages'][$url] = ['views' => 0, 'unique' => []];
        }
        $data['pages'][$url]['views']++;
        if (!in_array($ip_hash, $data['pages'][$url]['unique'])) {
            $data['pages'][$url]['unique'][] = $ip_hash;
        }

        // Referrer stats
        if (!empty($referrer)) {
            $ref_host = parse_url($referrer, PHP_URL_HOST) ?: $referrer;
            if (!isset($data['referrers'][$ref_host])) {
                $data['referrers'][$ref_host] = 0;
            }
            $data['referrers'][$ref_host]++;
        }

        // Device stats
        $data['devices'][$device]++;

        update_option($key, $data);
        wp_die();
    }

    /**
     * AJAX: Save all settings at once
     */
    public function ajax_save_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

        $settings = $_POST['settings'] ?? [];

        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                $key = sanitize_key($key);
                $value = sanitize_text_field($value);
                update_option($key, $value);
            }
        }

        wp_send_json_success(['message' => 'Settings saved']);
    }

    /* ─────────────────────────────────────────────
     * Admin Menu
     * ───────────────────────────────────────────── */

    /**
     * Register the admin menu with all submenu pages
     */
    public function register_admin_menu(): void {
        $admin = WebNewBiz_Admin::instance();

        add_menu_page(
            'WebNewBiz',
            'WebNewBiz',
            'manage_options',
            'webnewbiz',
            [$admin, 'render_dashboard'],
            $this->get_menu_icon_svg(),
            2
        );

        $submenus = [
            ['webnewbiz',           'Dashboard',       'render_dashboard'],
            ['webnewbiz-booster',   'Website Booster', 'render_booster'],
            ['webnewbiz-cache',     'Cache Manager',   'render_cache'],
            ['webnewbiz-images',    'Image Optimizer', 'render_images'],
            ['webnewbiz-security',  'Security',        'render_security'],
            ['webnewbiz-seo',       'SEO Tools',       'render_seo'],
            ['webnewbiz-backups',   'Backups',         'render_backups'],
            ['webnewbiz-database',  'Database',        'render_database'],
            ['webnewbiz-analytics', 'Analytics',       'render_analytics'],
            ['webnewbiz-ai',        'AI Assistant',    'render_ai'],
            ['webnewbiz-maintenance','Maintenance',    'render_maintenance'],
            ['webnewbiz-whitelabel','White Label',     'render_whitelabel'],
            ['webnewbiz-settings',  'Settings',        'render_settings'],
        ];

        foreach ($submenus as $sm) {
            add_submenu_page(
                'webnewbiz',
                $sm[1],
                $sm[1],
                'manage_options',
                $sm[0],
                [$admin, $sm[2]]
            );
        }
    }

    /**
     * Enqueue admin CSS & JS
     */
    public function enqueue_admin_assets(string $hook): void {
        // Global admin bar styles on all pages
        wp_enqueue_style(
            'webnewbiz-admin-global',
            WEBNEWBIZ_PLUGIN_URL . 'assets/css/admin.css',
            [],
            WEBNEWBIZ_VERSION
        );

        // AI Chatbot — loads on ALL admin pages
        wp_enqueue_style(
            'webnewbiz-ai-chatbot-css',
            WEBNEWBIZ_PLUGIN_URL . 'assets/css/ai-chatbot.css',
            [],
            WEBNEWBIZ_VERSION
        );
        wp_enqueue_script(
            'webnewbiz-ai-chatbot',
            WEBNEWBIZ_PLUGIN_URL . 'assets/js/ai-chatbot.js',
            [],
            WEBNEWBIZ_VERSION,
            true
        );
        wp_localize_script('webnewbiz-ai-chatbot', 'wnbAiChat', [
            'ajaxUrl'  => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wnb_admin_nonce'),
            'siteName' => get_bloginfo('name'),
        ]);

        // Full assets only on our pages
        if (strpos($hook, 'webnewbiz') !== false) {
            wp_enqueue_style(
                'webnewbiz-admin',
                WEBNEWBIZ_PLUGIN_URL . 'assets/css/admin.css',
                [],
                WEBNEWBIZ_VERSION
            );

            wp_enqueue_script(
                'webnewbiz-admin-js',
                WEBNEWBIZ_PLUGIN_URL . 'assets/js/admin.js',
                [],
                WEBNEWBIZ_VERSION,
                true
            );

            wp_localize_script('webnewbiz-admin-js', 'wnbAdmin', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('wnb_admin_nonce'),
                'pluginUrl' => WEBNEWBIZ_PLUGIN_URL,
            ]);
        }
    }

    /**
     * Enqueue AI chatbot inside Elementor editor
     */
    public function enqueue_elementor_chatbot(): void {
        wp_enqueue_style(
            'webnewbiz-ai-chatbot-css',
            WEBNEWBIZ_PLUGIN_URL . 'assets/css/ai-chatbot.css',
            [],
            WEBNEWBIZ_VERSION
        );
        wp_enqueue_script(
            'webnewbiz-ai-chatbot',
            WEBNEWBIZ_PLUGIN_URL . 'assets/js/ai-chatbot.js',
            [],
            WEBNEWBIZ_VERSION,
            true
        );
        wp_localize_script('webnewbiz-ai-chatbot', 'wnbAiChat', [
            'ajaxUrl'  => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wnb_admin_nonce'),
            'siteName' => get_bloginfo('name'),
        ]);
    }

    /**
     * Admin bar menu item with sub-links
     */
    public function admin_bar_item(\WP_Admin_Bar $admin_bar): void {
        if (!current_user_can('manage_options')) return;

        $admin_bar->add_node([
            'id'    => 'webnewbiz',
            'title' => '<span class="ab-icon webnewbiz-ab-icon"></span> WebNewBiz',
            'href'  => admin_url('admin.php?page=webnewbiz'),
            'meta'  => ['class' => 'webnewbiz-admin-bar'],
        ]);

        $bar_links = [
            ['webnewbiz-ab-dashboard', 'Go to Platform', $this->get_platform_url()],
            ['webnewbiz-ab-booster',   'Website Booster', admin_url('admin.php?page=webnewbiz-booster')],
            ['webnewbiz-ab-cache',     'Purge Cache',     admin_url('admin.php?page=webnewbiz-cache')],
            ['webnewbiz-ab-security',  'Security',        admin_url('admin.php?page=webnewbiz-security')],
            ['webnewbiz-ab-backups',   'Backups',         admin_url('admin.php?page=webnewbiz-backups')],
            ['webnewbiz-ab-ai',        'AI Assistant',    admin_url('admin.php?page=webnewbiz-ai')],
        ];

        foreach ($bar_links as $link) {
            $admin_bar->add_node([
                'parent' => 'webnewbiz',
                'id'     => $link[0],
                'title'  => $link[1],
                'href'   => $link[2],
            ]);
        }
    }

    /**
     * Dashboard widget
     */
    public function register_dashboard_widget(): void {
        wp_add_dashboard_widget(
            'webnewbiz_status',
            'WebNewBiz -- Site Status',
            [WebNewBiz_Admin::instance(), 'render_dashboard_widget']
        );

        // Move our widget to the top
        global $wp_meta_boxes;
        $dashboard = $wp_meta_boxes['dashboard']['normal']['core'] ?? [];
        if (isset($dashboard['webnewbiz_status'])) {
            $widget = ['webnewbiz_status' => $dashboard['webnewbiz_status']];
            unset($dashboard['webnewbiz_status']);
            $wp_meta_boxes['dashboard']['normal']['core'] = array_merge($widget, $dashboard);
        }
    }

    /**
     * Plugin action links (on Plugins page)
     */
    public function plugin_action_links(array $links): array {
        $dashboard_link = '<a href="' . admin_url('admin.php?page=webnewbiz') . '">Dashboard</a>';
        $premium_badge = '<span style="color:#fff;background:#6366f1;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;">Premium</span>';
        array_unshift($links, $dashboard_link, $premium_badge);
        return $links;
    }

    /**
     * Plugin row meta (Premium badge)
     */
    public function plugin_row_meta(array $meta, string $file): array {
        if ($file === plugin_basename(__FILE__)) {
            $meta[] = '<span style="color:#fff;background:#6366f1;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;">Premium</span>';
        }
        return $meta;
    }

    /**
     * Modify plugin info to show Premium label
     */
    public function modify_plugin_info(array $plugins): array {
        $file = plugin_basename(__FILE__);
        if (isset($plugins[$file])) {
            $plugins[$file]['Name'] = 'WebNewBiz Builder';
            $plugins[$file]['AuthorName'] = 'WebNewBiz';
            $plugins[$file]['AuthorURI'] = 'https://webnewbiz.com';
        }
        return $plugins;
    }

    /**
     * Disable auto-updates -- managed through platform
     */
    public function disable_auto_update($update, $item) {
        if (isset($item->plugin) && $item->plugin === plugin_basename(__FILE__)) {
            return false;
        }
        return $update;
    }

    /**
     * Base64-encoded SVG icon for admin menu (purple rounded square with W)
     */
    private function get_menu_icon_svg(): string {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect width="20" height="20" rx="4" fill="currentColor"/><text x="10" y="14.5" font-family="Arial,sans-serif" font-size="12" font-weight="700" fill="#23282d" text-anchor="middle">W</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get platform dashboard URL
     */
    public function get_platform_url(): string {
        return get_option('webnewbiz_platform_url', 'http://localhost:4200/dashboard');
    }

    /**
     * Get site connection token
     */
    public function get_connection_token(): string {
        return get_option('webnewbiz_connection_token', '');
    }

    /**
     * Recursively delete a directory
     */
    private function recursive_delete(string $dir): void {
        if (!is_dir($dir)) return;
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->recursive_delete($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}

// Initialize
function webnewbiz_builder(): WebNewBiz_Builder {
    return WebNewBiz_Builder::instance();
}

add_action('plugins_loaded', 'webnewbiz_builder');

// Activation hook
register_activation_hook(__FILE__, function () {
    // Set default options
    $defaults = [
        'webnewbiz_platform_url' => 'http://localhost:4200/dashboard',
        'webnewbiz_connected_at' => current_time('mysql'),
        'wnb_disable_emojis' => '1',
        'wnb_disable_embeds' => '1',
        'wnb_remove_jquery_migrate' => '1',
        'wnb_minify_html' => '0',
        'wnb_lazy_load_images' => '1',
        'wnb_lazy_load_iframes' => '1',
        'wnb_dns_prefetch' => '1',
        'wnb_preload_resources' => '0',
        'wnb_disable_heartbeat' => '0',
        'wnb_remove_query_strings' => '1',
        'wnb_disable_rss' => '0',
        'wnb_disable_self_pingbacks' => '1',
        'wnb_disable_xmlrpc' => '1',
        'wnb_disable_file_editor' => '1',
        'wnb_hide_wp_version' => '1',
        'wnb_security_headers' => '1',
        'wnb_limit_login_attempts' => '1',
        'wnb_disable_user_enum' => '1',
        'wnb_block_bad_bots' => '0',
        'wnb_force_ssl_admin' => '0',
        'wnb_disable_php_uploads' => '1',
        'wnb_maintenance_mode' => '0',
        'wnb_maintenance_allow_admins' => '1',
        'wnb_compression_quality' => '82',
        'wnb_max_image_dimensions' => '2048',
        'wnb_webp_conversion' => '0',
        'wnb_strip_exif' => '1',
        'wnb_auto_optimize_upload' => '0',
        'wnb_backup_schedule' => 'off',
        'wnb_db_auto_cleanup' => '0',
        'wnb_sitemap_enabled' => '1',
        'wnb_email_notifications' => '0',
    ];

    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }

    // Create analytics custom table
    if (class_exists('WebNewBiz_Analytics')) {
        WebNewBiz_Analytics::create_table();
    }
});

// Maintenance mode frontend intercept (must run early)
add_action('template_redirect', function () {
    if (get_option('wnb_maintenance_mode', '0') !== '1') return;
    if (is_user_logged_in() && current_user_can('manage_options') && get_option('wnb_maintenance_allow_admins', '1') === '1') return;
    if (wp_doing_ajax()) return;

    $message = get_option('wnb_maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');
    $bg_color = get_option('wnb_maintenance_bg_color', '#1e293b');
    $back_date = get_option('wnb_maintenance_back_date', '');
    $custom_css = get_option('wnb_maintenance_custom_css', '');

    header('HTTP/1.1 503 Service Unavailable');
    header('Retry-After: 3600');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo esc_html(get_bloginfo('name')); ?> - Maintenance</title>
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:<?php echo esc_attr($bg_color); ?>; color:#fff; padding:40px 20px; }
            .maint-box { text-align:center; max-width:520px; }
            .maint-box h1 { font-size:28px; margin-bottom:16px; font-weight:700; }
            .maint-box p { font-size:16px; opacity:0.85; line-height:1.6; margin-bottom:12px; }
            .maint-time { font-size:14px; opacity:0.6; margin-top:20px; }
            <?php echo esc_html($custom_css); ?>
        </style>
    </head>
    <body>
        <div class="maint-box">
            <h1>Under Maintenance</h1>
            <p><?php echo esc_html($message); ?></p>
            <?php if ($back_date): ?>
                <p class="maint-time">Expected back: <?php echo esc_html($back_date); ?></p>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
});

// SEO: Handle 301 redirects
add_action('template_redirect', function () {
    $redirects = get_option('wnb_redirects', []);
    if (empty($redirects)) return;

    $current = $_SERVER['REQUEST_URI'] ?? '';
    foreach ($redirects as $r) {
        if (rtrim($r['from'], '/') === rtrim($current, '/')) {
            wp_redirect($r['to'], 301);
            exit;
        }
    }
}, 1);

// Frontend analytics tracker
add_action('wp_footer', function () {
    if (is_admin()) return;
    ?>
    <script>
    (function(){
        var d = new FormData();
        d.append('action', 'wnb_track_pageview');
        d.append('url', window.location.pathname);
        d.append('referrer', document.referrer || '');
        fetch('<?php echo admin_url("admin-ajax.php"); ?>', { method:'POST', body:d, keepalive:true });
    })();
    </script>
    <?php
});
