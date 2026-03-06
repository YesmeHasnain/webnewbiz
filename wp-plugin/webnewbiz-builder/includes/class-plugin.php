<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Plugin {

    private static ?Plugin $instance = null;

    public static function get_instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init();
    }

    private function init(): void {
        // Load components
        require_once WNB_DIR . 'includes/class-widget-loader.php';
        require_once WNB_DIR . 'includes/class-template-library.php';
        require_once WNB_DIR . 'includes/class-settings.php';
        require_once WNB_DIR . 'includes/class-ai-service.php';
        require_once WNB_DIR . 'includes/class-content-generator.php';
        require_once WNB_DIR . 'includes/class-image-service.php';
        require_once WNB_DIR . 'includes/class-rest-api.php';
        require_once WNB_DIR . 'includes/class-theme-manager.php';
        require_once WNB_DIR . 'includes/class-website-generator.php';

        $widget_loader = new Widget_Loader();
        new Settings();

        // AI system
        $ai_service        = new AI_Service();
        $content_generator = new Content_Generator($ai_service);
        $image_service     = new Image_Service();
        new REST_API($ai_service);
        new Website_Generator($content_generator, $image_service);

        // Register widget category
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);

        // Register widgets
        add_action('elementor/widgets/register', [$widget_loader, 'register_widgets']);

        // Register widget styles
        add_action('elementor/frontend/after_register_styles', [$widget_loader, 'register_widget_styles']);

        // Common frontend CSS
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);

        // Elementor editor AI scripts
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_ai_assets']);

        // Admin generator page assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    /**
     * Register the "WebnewBiz Widgets" category in the Elementor editor.
     */
    public function register_widget_category($elements_manager): void {
        $elements_manager->add_category('webnewbiz-widgets', [
            'title' => __('WebnewBiz Widgets', 'webnewbiz-builder'),
            'icon'  => 'fa fa-plug',
        ]);
    }

    /**
     * Enqueue common frontend assets.
     */
    public function enqueue_frontend_assets(): void {
        wp_register_style(
            'wnb-widgets-common',
            WNB_URL . 'assets/frontend/css/widgets-common.css',
            [],
            WNB_VERSION
        );
    }

    /**
     * Enqueue AI button scripts/styles in the Elementor editor.
     */
    public function enqueue_editor_ai_assets(): void {
        wp_enqueue_script(
            'wnb-ai-button',
            WNB_URL . 'assets/editor/js/ai-button.js',
            ['jquery', 'wp-api-fetch'],
            WNB_VERSION,
            true
        );
        wp_enqueue_script(
            'wnb-ai-panel',
            WNB_URL . 'assets/editor/js/ai-panel.js',
            ['jquery', 'wp-api-fetch'],
            WNB_VERSION,
            true
        );
        wp_enqueue_style(
            'wnb-ai-panel-css',
            WNB_URL . 'assets/editor/css/ai-panel.css',
            [],
            WNB_VERSION
        );
    }

    /**
     * Enqueue admin assets on the generator page only.
     */
    public function enqueue_admin_assets(string $hook): void {
        if (strpos($hook, 'wnb-generate') === false) {
            return;
        }

        wp_enqueue_style(
            'wnb-generator-css',
            WNB_URL . 'assets/admin/css/generator.css',
            [],
            WNB_VERSION
        );
        wp_enqueue_script(
            'wnb-generator-js',
            WNB_URL . 'assets/admin/js/generator.js',
            ['jquery'],
            WNB_VERSION,
            true
        );
        wp_localize_script('wnb-generator-js', 'wnbGenerator', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
        ]);
    }
}
