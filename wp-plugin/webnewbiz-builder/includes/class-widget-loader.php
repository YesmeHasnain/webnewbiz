<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Widget_Loader {

    /**
     * Widget registry: slug => class name.
     */
    private array $widgets = [
        'hero-section'  => 'Hero_Section',
        'features-grid' => 'Features_Grid',
        'testimonials'  => 'Testimonials',
        'cta-banner'    => 'CTA_Banner',
        'contact-info'  => 'Contact_Info',
    ];

    /**
     * Register all widgets with Elementor.
     */
    public function register_widgets($widgets_manager): void {
        // Load base widget class
        require_once WNB_DIR . 'widgets/class-base-widget.php';

        foreach ($this->widgets as $slug => $class_name) {
            $file = WNB_DIR . "widgets/{$slug}/class-{$slug}.php";
            if (file_exists($file)) {
                require_once $file;
                $full_class = "\\WebnewBiz\\Builder\\Widgets\\{$class_name}";
                if (class_exists($full_class)) {
                    $widgets_manager->register(new $full_class());
                }
            }
        }
    }

    /**
     * Register CSS for each widget (loaded on-demand via get_style_depends).
     */
    public function register_widget_styles(): void {
        foreach ($this->widgets as $slug => $class_name) {
            $css_file = WNB_DIR . "widgets/{$slug}/assets/style.css";
            if (file_exists($css_file)) {
                wp_register_style(
                    'wnb-' . $slug,
                    WNB_URL . "widgets/{$slug}/assets/style.css",
                    [],
                    WNB_VERSION
                );
            }
        }
    }
}
