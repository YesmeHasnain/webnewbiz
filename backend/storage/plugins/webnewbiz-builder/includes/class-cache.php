<?php
/**
 * WebNewBiz Cache Manager
 *
 * Handles WordPress object cache, Elementor CSS cache, transients,
 * browser caching rules (.htaccess), and auto-purge on save/update.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Cache {

    private static ?self $instance = null;

    /** Default settings */
    private array $defaults = [
        'auto_purge_on_save'   => true,
        'auto_purge_on_update' => true,
        'browser_cache_enabled' => false,
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $settings = $this->get_settings();

        // Auto-purge on post save
        if ($settings['auto_purge_on_save']) {
            add_action('save_post', [$this, 'on_save_post'], 20, 2);
        }

        // Auto-purge on plugin/theme update
        if ($settings['auto_purge_on_update']) {
            add_action('upgrader_process_complete', [$this, 'on_upgrader_complete'], 20, 2);
        }

        // AJAX handlers
        add_action('wp_ajax_wnb_purge_cache', [$this, 'ajax_purge_cache']);
        add_action('wp_ajax_wnb_save_cache_settings', [$this, 'ajax_save_cache_settings']);
    }

    // ──────────────────────────────────────────────
    //  Public API
    // ──────────────────────────────────────────────

    /**
     * Purge everything: object cache, Elementor, transients, page cache.
     */
    public function purge_all(): array {
        $results = [];
        $results['object_cache']  = $this->purge_object_cache();
        $results['elementor']     = $this->purge_elementor();
        $results['transients']    = $this->purge_transients();
        $results['page_cache']    = $this->purge_page_cache();
        return $results;
    }

    /**
     * Purge Elementor CSS cache: postmeta rows + CSS files on disk.
     */
    public function purge_elementor(): bool {
        global $wpdb;

        // Delete _elementor_css from wp_postmeta
        $wpdb->query(
            "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_css'"
        );

        // Delete _elementor_element_cache from wp_postmeta
        $wpdb->query(
            "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_element_cache'"
        );

        // Delete Elementor global CSS option
        delete_option('_elementor_global_css');
        delete_option('elementor-custom-breakpoints-files');

        // Remove CSS files from the Elementor uploads directory
        $upload_dir = wp_upload_dir();
        $elementor_css_dir = trailingslashit($upload_dir['basedir']) . 'elementor/css';

        if (is_dir($elementor_css_dir)) {
            $files = glob($elementor_css_dir . '/*.css');
            if (is_array($files)) {
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
        }

        // Also clear the Elementor tmp directory
        $elementor_tmp_dir = trailingslashit($upload_dir['basedir']) . 'elementor/tmp';
        if (is_dir($elementor_tmp_dir)) {
            $files = glob($elementor_tmp_dir . '/*');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Flush the WordPress object cache.
     */
    public function purge_object_cache(): bool {
        return wp_cache_flush();
    }

    /**
     * Delete all transients from wp_options.
     */
    public function purge_transients(): int {
        global $wpdb;

        // Count before delete
        $count = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options}
             WHERE option_name LIKE '_transient_%'
                OR option_name LIKE '_site_transient_%'"
        );

        // Delete transient values and their timeout entries
        $wpdb->query(
            "DELETE FROM {$wpdb->options}
             WHERE option_name LIKE '_transient_%'
                OR option_name LIKE '_site_transient_%'"
        );

        return $count;
    }

    /**
     * Purge any known page cache (WP Super Cache, W3TC, LiteSpeed, etc.).
     * If no external cache plugin, this is a no-op.
     */
    public function purge_page_cache(): bool {
        // WP Super Cache
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
            return true;
        }

        // W3 Total Cache
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
            return true;
        }

        // LiteSpeed Cache
        if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
            LiteSpeed_Cache_API::purge_all();
            return true;
        }

        // WP Fastest Cache
        if (function_exists('wpfc_clear_all_cache')) {
            wpfc_clear_all_cache(true);
            return true;
        }

        // No page cache plugin detected — attempt to clear the advanced-cache.php cache dir
        $cache_dir = WP_CONTENT_DIR . '/cache';
        if (is_dir($cache_dir)) {
            $this->recursive_delete($cache_dir, false);
            return true;
        }

        return false;
    }

    // ──────────────────────────────────────────────
    //  Browser Caching (.htaccess)
    // ──────────────────────────────────────────────

    /**
     * Write browser caching rules (mod_expires) into .htaccess.
     */
    public function enable_browser_cache(): bool {
        if (!function_exists('insert_with_markers')) {
            require_once ABSPATH . 'wp-admin/includes/misc.php';
        }

        $rules = [
            '<IfModule mod_expires.c>',
            '    ExpiresActive On',
            '    ExpiresDefault "access plus 1 month"',
            '',
            '    # HTML',
            '    ExpiresByType text/html "access plus 1 hour"',
            '',
            '    # CSS',
            '    ExpiresByType text/css "access plus 1 year"',
            '',
            '    # JavaScript',
            '    ExpiresByType application/javascript "access plus 1 year"',
            '    ExpiresByType text/javascript "access plus 1 year"',
            '',
            '    # Images',
            '    ExpiresByType image/jpeg "access plus 1 year"',
            '    ExpiresByType image/png "access plus 1 year"',
            '    ExpiresByType image/gif "access plus 1 year"',
            '    ExpiresByType image/webp "access plus 1 year"',
            '    ExpiresByType image/svg+xml "access plus 1 year"',
            '    ExpiresByType image/x-icon "access plus 1 year"',
            '',
            '    # Fonts',
            '    ExpiresByType font/woff "access plus 1 year"',
            '    ExpiresByType font/woff2 "access plus 1 year"',
            '    ExpiresByType application/font-woff "access plus 1 year"',
            '    ExpiresByType application/font-woff2 "access plus 1 year"',
            '',
            '    # Video/Audio',
            '    ExpiresByType video/mp4 "access plus 1 year"',
            '    ExpiresByType audio/mpeg "access plus 1 year"',
            '</IfModule>',
            '',
            '<IfModule mod_headers.c>',
            '    <FilesMatch "\\.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff|woff2)$">',
            '        Header set Cache-Control "public, max-age=31536000, immutable"',
            '    </FilesMatch>',
            '    <FilesMatch "\\.(html|htm)$">',
            '        Header set Cache-Control "public, max-age=3600, must-revalidate"',
            '    </FilesMatch>',
            '</IfModule>',
        ];

        $htaccess = $this->get_htaccess_path();
        $result = insert_with_markers($htaccess, 'WebNewBiz Browser Cache', $rules);

        if ($result) {
            $settings = $this->get_settings();
            $settings['browser_cache_enabled'] = true;
            $this->save_settings($settings);
        }

        return $result;
    }

    /**
     * Remove our browser caching rules from .htaccess.
     */
    public function disable_browser_cache(): bool {
        if (!function_exists('insert_with_markers')) {
            require_once ABSPATH . 'wp-admin/includes/misc.php';
        }

        $htaccess = $this->get_htaccess_path();
        $result = insert_with_markers($htaccess, 'WebNewBiz Browser Cache', []);

        if ($result) {
            $settings = $this->get_settings();
            $settings['browser_cache_enabled'] = false;
            $this->save_settings($settings);
        }

        return $result;
    }

    // ──────────────────────────────────────────────
    //  Stats & Settings
    // ──────────────────────────────────────────────

    /**
     * Return cache-related statistics.
     */
    public function get_stats(): array {
        global $wpdb;

        // Transient count
        $transient_count = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options}
             WHERE option_name LIKE '_transient_%'
                OR option_name LIKE '_site_transient_%'"
        );

        // Elementor CSS files count
        $elementor_css_count = 0;
        $upload_dir = wp_upload_dir();
        $elementor_css_dir = trailingslashit($upload_dir['basedir']) . 'elementor/css';
        if (is_dir($elementor_css_dir)) {
            $files = glob($elementor_css_dir . '/*.css');
            $elementor_css_count = is_array($files) ? count($files) : 0;
        }

        // Elementor postmeta cache entries
        $elementor_meta_count = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta}
             WHERE meta_key IN ('_elementor_css', '_elementor_element_cache')"
        );

        // Object cache status
        $object_cache_active = wp_using_ext_object_cache();

        return [
            'transient_count'       => $transient_count,
            'elementor_css_files'   => $elementor_css_count,
            'elementor_meta_entries'=> $elementor_meta_count,
            'object_cache_active'   => $object_cache_active,
            'browser_cache_enabled' => $this->get_settings()['browser_cache_enabled'],
        ];
    }

    /**
     * Get saved cache settings.
     */
    public function get_settings(): array {
        $saved = get_option('wnb_cache_settings', []);
        return wp_parse_args($saved, $this->defaults);
    }

    /**
     * Save cache settings.
     */
    public function save_settings(array $data): bool {
        $clean = [
            'auto_purge_on_save'    => !empty($data['auto_purge_on_save']),
            'auto_purge_on_update'  => !empty($data['auto_purge_on_update']),
            'browser_cache_enabled' => !empty($data['browser_cache_enabled']),
        ];
        return update_option('wnb_cache_settings', $clean);
    }

    // ──────────────────────────────────────────────
    //  Hook Callbacks
    // ──────────────────────────────────────────────

    /**
     * Auto-purge on post save (if enabled).
     */
    public function on_save_post(int $post_id, \WP_Post $post): void {
        // Skip revisions and autosaves
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        // Only purge for public post types
        $post_type_obj = get_post_type_object($post->post_type);
        if (!$post_type_obj || !$post_type_obj->public) {
            return;
        }

        // Purge Elementor CSS for this specific post
        delete_post_meta($post_id, '_elementor_css');
        delete_post_meta($post_id, '_elementor_element_cache');

        // Flush object cache
        wp_cache_flush();
    }

    /**
     * Auto-purge after plugin/theme updates.
     */
    public function on_upgrader_complete($upgrader, array $hook_extra): void {
        $this->purge_all();
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Purge cache by type.
     */
    public function ajax_purge_cache(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $cache_type = sanitize_text_field($_POST['cache_type'] ?? 'all');

        switch ($cache_type) {
            case 'all':
                $results = $this->purge_all();
                wp_send_json_success([
                    'message' => 'All caches purged successfully.',
                    'details' => $results,
                ]);
                break;

            case 'elementor':
                $this->purge_elementor();
                wp_send_json_success([
                    'message' => 'Elementor cache purged successfully.',
                ]);
                break;

            case 'object':
                $this->purge_object_cache();
                wp_send_json_success([
                    'message' => 'Object cache flushed successfully.',
                ]);
                break;

            case 'transients':
                $count = $this->purge_transients();
                wp_send_json_success([
                    'message' => sprintf('%d transients deleted.', $count),
                    'count'   => $count,
                ]);
                break;

            case 'page':
                $this->purge_page_cache();
                wp_send_json_success([
                    'message' => 'Page cache purged successfully.',
                ]);
                break;

            case 'browser_enable':
                $ok = $this->enable_browser_cache();
                if ($ok) {
                    wp_send_json_success(['message' => 'Browser caching enabled.']);
                } else {
                    wp_send_json_error(['message' => 'Could not write to .htaccess. Check file permissions.']);
                }
                break;

            case 'browser_disable':
                $ok = $this->disable_browser_cache();
                if ($ok) {
                    wp_send_json_success(['message' => 'Browser caching disabled.']);
                } else {
                    wp_send_json_error(['message' => 'Could not write to .htaccess. Check file permissions.']);
                }
                break;

            default:
                wp_send_json_error(['message' => 'Unknown cache type: ' . $cache_type]);
                break;
        }
    }

    /**
     * AJAX: Save cache settings.
     */
    public function ajax_save_cache_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $data = [
            'auto_purge_on_save'    => !empty($_POST['auto_purge_on_save']),
            'auto_purge_on_update'  => !empty($_POST['auto_purge_on_update']),
            'browser_cache_enabled' => !empty($_POST['browser_cache_enabled']),
        ];

        // If browser cache setting changed, apply it
        $current = $this->get_settings();
        if ($data['browser_cache_enabled'] && !$current['browser_cache_enabled']) {
            $this->enable_browser_cache();
        } elseif (!$data['browser_cache_enabled'] && $current['browser_cache_enabled']) {
            $this->disable_browser_cache();
        }

        $this->save_settings($data);

        wp_send_json_success([
            'message'  => 'Cache settings saved.',
            'settings' => $this->get_settings(),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Private Helpers
    // ──────────────────────────────────────────────

    /**
     * Get the path to the root .htaccess file.
     */
    private function get_htaccess_path(): string {
        return ABSPATH . '.htaccess';
    }

    /**
     * Recursively delete directory contents.
     *
     * @param string $dir   Directory path.
     * @param bool   $rmdir Whether to remove the directory itself.
     */
    private function recursive_delete(string $dir, bool $rmdir = true): void {
        if (!is_dir($dir)) return;

        $items = scandir($dir);
        if ($items === false) return;

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->recursive_delete($path, true);
            } else {
                @unlink($path);
            }
        }

        if ($rmdir) {
            @rmdir($dir);
        }
    }
}
