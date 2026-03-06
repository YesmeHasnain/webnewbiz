<?php
if (!defined('ABSPATH')) exit;

define('WNB_THEME_VERSION', '1.0.0');

function wnb_theme_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-background', ['default-color' => 'ffffff']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('custom-logo', ['height' => 250, 'width' => 250, 'flex-width' => true, 'flex-height' => true]);
    register_nav_menus(['header_menu' => __('Header Menu', 'wnb-aurora'), 'footer_menu' => __('Footer Menu', 'wnb-aurora')]);
    add_theme_support('elementor');
    add_theme_support('header-footer-elementor');
}
add_action('after_setup_theme', 'wnb_theme_setup');

function wnb_theme_content_width() { $GLOBALS['content_width'] = 960; }
add_action('after_setup_theme', 'wnb_theme_content_width', 0);

function wnb_theme_widgets_init() {
    $args = ['before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>'];
    register_sidebar(array_merge($args, ['name' => 'Sidebar', 'id' => 'sidebar-1']));
    register_sidebar(array_merge($args, ['name' => 'Footer 1', 'id' => 'footer-1']));
    register_sidebar(array_merge($args, ['name' => 'Footer 2', 'id' => 'footer-2']));
}
add_action('widgets_init', 'wnb_theme_widgets_init');

function wnb_theme_scripts() {
    wp_enqueue_style('wnb-fonts', 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Figtree:wght@400;500;600;700&display=swap', [], null);
    wp_enqueue_style('wnb-style', get_stylesheet_uri(), [], WNB_THEME_VERSION);
}
add_action('wp_enqueue_scripts', 'wnb_theme_scripts');

function wnb_elementor_settings() {
    update_option('elementor_disable_color_schemes', 'yes');
    update_option('elementor_disable_typography_schemes', 'yes');
}
add_action('after_switch_theme', 'wnb_elementor_settings');

function wnb_theme_body_classes($classes) {
    if (!is_singular()) $classes[] = 'hfeed';
    if (!is_active_sidebar('sidebar-1')) $classes[] = 'no-sidebar';
    return $classes;
}
add_filter('body_class', 'wnb_theme_body_classes');

function wnb_menu_toggle_script() { ?>
<script>document.addEventListener('DOMContentLoaded',function(){var t=document.querySelector('.menu-toggle'),m=document.getElementById('primary-menu');if(t&&m)t.addEventListener('click',function(){m.classList.toggle('toggled');t.setAttribute('aria-expanded',t.getAttribute('aria-expanded')!=='true')})});</script>
<?php }
add_action('wp_footer', 'wnb_menu_toggle_script');
