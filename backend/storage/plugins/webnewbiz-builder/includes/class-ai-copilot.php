<?php
/**
 * WebNewBiz AI Copilot — Premium WordPress widget
 * Loads the copilot chat widget in WP admin and Elementor editor.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_AICopilot {

    private static ?self $instance = null;

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Load in admin pages
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // Load in Elementor editor
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_elementor_assets']);

        // Load on frontend admin bar (for logged-in admin viewing the site)
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
    }

    /**
     * Enqueue copilot assets in WP Admin.
     */
    public function enqueue_admin_assets() {
        if (!$this->is_copilot_enabled()) return;

        $this->enqueue_copilot_scripts('admin');
    }

    /**
     * Enqueue copilot assets in Elementor editor.
     */
    public function enqueue_elementor_assets() {
        if (!$this->is_copilot_enabled()) return;

        $this->enqueue_copilot_scripts('elementor');
    }

    /**
     * Enqueue copilot assets on frontend (only for admin users).
     */
    public function enqueue_frontend_assets() {
        if (!is_user_logged_in() || !current_user_can('manage_options')) return;
        if (!$this->is_copilot_enabled()) return;

        $this->enqueue_copilot_scripts('frontend');
    }

    /**
     * Enqueue the copilot JS + CSS with localized config.
     */
    private function enqueue_copilot_scripts(string $context) {
        wp_enqueue_style(
            'wnb-ai-copilot',
            WEBNEWBIZ_PLUGIN_URL . 'assets/css/ai-copilot.css',
            [],
            WEBNEWBIZ_VERSION
        );

        wp_enqueue_script(
            'wnb-ai-copilot',
            WEBNEWBIZ_PLUGIN_URL . 'assets/js/ai-copilot.js',
            [],
            WEBNEWBIZ_VERSION,
            true
        );

        // Pass config to JS
        wp_localize_script('wnb-ai-copilot', 'wnbCopilot', [
            'platformUrl'   => rtrim(get_option('webnewbiz_platform_api_url', get_option('webnewbiz_platform_url', 'http://localhost:8000')), '/'),
            'platformToken' => get_option('webnewbiz_platform_token', ''),
            'websiteId'     => intval(get_option('webnewbiz_website_id', 0)),
            'siteName'      => get_bloginfo('name'),
            'currentPageId' => $this->get_current_page_id(),
            'isElementor'   => ($context === 'elementor') ? '1' : '0',
            'locale'        => get_locale(),
            'adminUrl'      => admin_url(),
            'siteUrl'       => get_site_url(),
        ]);
    }

    /**
     * Check if copilot is properly configured.
     */
    private function is_copilot_enabled(): bool {
        $token = get_option('webnewbiz_platform_token', '');
        $websiteId = intval(get_option('webnewbiz_website_id', 0));
        return !empty($token) && $websiteId > 0;
    }

    /**
     * Get the current page/post ID from the admin context.
     */
    private function get_current_page_id(): int {
        // In post editor
        if (isset($_GET['post'])) {
            return intval($_GET['post']);
        }

        // In Elementor editor
        if (isset($_GET['elementor-preview'])) {
            return intval($_GET['elementor-preview']);
        }

        // In page list — no specific page
        return 0;
    }
}
