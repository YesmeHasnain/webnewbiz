<?php
/**
 * WebNewBiz Platform Connector
 * Handles communication between the WP site and the WebNewBiz platform.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Connector {

    private static ?self $instance = null;

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    /**
     * Register REST API routes for platform communication
     */
    public function register_rest_routes(): void {
        register_rest_route('webnewbiz/v1', '/status', [
            'methods' => 'GET',
            'callback' => [$this, 'get_status'],
            'permission_callback' => [$this, 'verify_platform_token'],
        ]);

        register_rest_route('webnewbiz/v1', '/health', [
            'methods' => 'GET',
            'callback' => [$this, 'get_health'],
            'permission_callback' => '__return_true', // Public health check
        ]);
    }

    /**
     * Verify the platform connection token
     */
    public function verify_platform_token(\WP_REST_Request $request): bool {
        $token = $request->get_header('X-WebNewBiz-Token');
        $stored = get_option('webnewbiz_connection_token', '');

        if (empty($stored) || empty($token)) return false;

        return hash_equals($stored, $token);
    }

    /**
     * GET /webnewbiz/v1/status — Full site status
     */
    public function get_status(\WP_REST_Request $request): \WP_REST_Response {
        $theme = wp_get_theme();
        $plugins = get_option('active_plugins', []);

        return new \WP_REST_Response([
            'success' => true,
            'data' => [
                'site_url' => get_site_url(),
                'site_name' => get_bloginfo('name'),
                'wp_version' => get_bloginfo('version'),
                'php_version' => phpversion(),
                'active_theme' => $theme->get('Name'),
                'theme_version' => $theme->get('Version'),
                'active_plugins' => count($plugins),
                'total_pages' => wp_count_posts('page')->publish ?? 0,
                'total_posts' => wp_count_posts('post')->publish ?? 0,
                'woocommerce_active' => class_exists('WooCommerce'),
                'webnewbiz_version' => WEBNEWBIZ_VERSION,
                'connected_at' => get_option('webnewbiz_connected_at', ''),
            ],
        ]);
    }

    /**
     * GET /webnewbiz/v1/health — Simple health check (public)
     */
    public function get_health(): \WP_REST_Response {
        return new \WP_REST_Response([
            'status' => 'ok',
            'plugin' => 'webnewbiz-builder',
            'version' => WEBNEWBIZ_VERSION,
        ]);
    }
}

// Initialize
WebNewBiz_Connector::instance();
