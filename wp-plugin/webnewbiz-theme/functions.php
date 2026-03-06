<?php
/**
 * WebnewBiz Theme functions and definitions.
 */

if (!defined('ABSPATH')) exit;

define('WNB_THEME_VERSION', '1.0.0');

/**
 * Theme setup.
 */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ]);
    add_theme_support('custom-background', [
        'default-color' => 'ffffff',
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'webnewbiz-theme'),
        'footer'  => __('Footer Menu', 'webnewbiz-theme'),
    ]);

    // Content width
    if (!isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1200;
    }
});

/**
 * Enqueue styles and scripts.
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('wnb-theme-base', get_template_directory_uri() . '/assets/css/theme.css', [], WNB_THEME_VERSION);
    wp_enqueue_style('wnb-theme-style', get_stylesheet_uri(), ['wnb-theme-base'], WNB_THEME_VERSION);

    wp_enqueue_script('wnb-navigation', get_template_directory_uri() . '/assets/js/navigation.js', [], WNB_THEME_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
});

/**
 * Register widget areas.
 */
add_action('widgets_init', function () {
    register_sidebar([
        'name'          => __('Sidebar', 'webnewbiz-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'webnewbiz-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => __('Footer', 'webnewbiz-theme'),
        'id'            => 'footer-1',
        'description'   => __('Footer widget area.', 'webnewbiz-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
});

/**
 * Disable Elementor default color/typography schemes so theme controls apply.
 */
add_action('after_switch_theme', function () {
    if (class_exists('\Elementor\Plugin')) {
        update_option('elementor_disable_typography_schemes', '');
        update_option('elementor_disable_color_schemes', '');
    }
});

/**
 * Add body classes.
 */
add_filter('body_class', function ($classes) {
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    if (is_singular() && defined('ELEMENTOR_VERSION')) {
        $document = \Elementor\Plugin::instance()->documents->get(get_the_ID());
        if ($document && !is_bool($document) && $document->is_built_with_elementor()) {
            $classes[] = 'wnb-elementor-page';
        }
    }
    return $classes;
});
