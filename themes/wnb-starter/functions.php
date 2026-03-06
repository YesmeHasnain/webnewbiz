<?php
if (!defined('ABSPATH')) exit;

define('WNB_THEME_VERSION', '1.0.0');

/**
 * Theme setup.
 */
function wnb_theme_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-background', ['default-color' => 'ffffff']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    register_nav_menus([
        'header_menu' => __('Header Menu', 'wnb-starter'),
        'footer_menu' => __('Footer Menu', 'wnb-starter'),
    ]);

    // Elementor support
    add_theme_support('elementor');
    add_theme_support('header-footer-elementor');
}
add_action('after_setup_theme', 'wnb_theme_setup');

/**
 * Content width.
 */
function wnb_theme_content_width() {
    $GLOBALS['content_width'] = apply_filters('wnb_theme_content_width', 960);
}
add_action('after_setup_theme', 'wnb_theme_content_width', 0);

/**
 * Register widget areas.
 */
function wnb_theme_widgets_init() {
    register_sidebar([
        'name'          => __('Sidebar', 'wnb-starter'),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => __('Footer 1', 'wnb-starter'),
        'id'            => 'footer-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => __('Footer 2', 'wnb-starter'),
        'id'            => 'footer-2',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'wnb_theme_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function wnb_theme_scripts() {
    // Google Fonts
    wp_enqueue_style('wnb-fonts', wnb_google_fonts_url(), [], null);

    // Theme stylesheet
    wp_enqueue_style('wnb-style', get_stylesheet_uri(), [], WNB_THEME_VERSION);

    // Navigation script
    wp_enqueue_script('wnb-navigation', get_template_directory_uri() . '/assets/js/navigation.js', [], WNB_THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'wnb_theme_scripts');

/**
 * Google Fonts URL.
 */
function wnb_google_fonts_url(): string {
    return 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap';
}

/**
 * Disable Elementor default color/typography schemes for full theme control.
 */
function wnb_elementor_settings() {
    update_option('elementor_disable_color_schemes', 'yes');
    update_option('elementor_disable_typography_schemes', 'yes');
}
add_action('after_switch_theme', 'wnb_elementor_settings');

/**
 * Add body classes.
 */
function wnb_theme_body_classes($classes) {
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    return $classes;
}
add_filter('body_class', 'wnb_theme_body_classes');

/**
 * Mobile menu toggle script (inline).
 */
function wnb_menu_toggle_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.querySelector('.menu-toggle');
        var menu = document.getElementById('primary-menu');
        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                menu.classList.toggle('toggled');
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !expanded);
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'wnb_menu_toggle_script');
