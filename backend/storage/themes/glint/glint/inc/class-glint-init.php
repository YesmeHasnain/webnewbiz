<?php

/**
 * theme init class
 * */
if (!defined('ABSPATH')) {
    exit(); //exit if access directly
}

if (!class_exists('Glint_Init')) {

    class Glint_Init
    {

        private static $instance;

        public function __construct()
        {
            //theme setup
            add_action('after_setup_theme', array($this, 'theme_setup'));
            //widget init
            add_action('widgets_init', array($this, 'widgets_init'));
            //theme assets
            add_action('wp_enqueue_scripts', array($this, 'theme_assets'));
        }

        /**
         * getInstance();
         * @since 1.0.0
         * */
        public static function getInstance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * theme setup
         * @since 1.0.0
         * */
        public function theme_setup()
        {
            /*
             * Make theme available for translation.
             * Translations can be filed in the /languages/ directory.
             * If you're building a theme based on glint, use a find and replace
             * to change 'glint' to the name of your theme in all the template files.
             */
            load_theme_textdomain('glint', get_template_directory() . '/languages');

            // Add default posts and comments RSS feed links to head.
            add_theme_support('automatic-feed-links');

            /*
             * Let WordPress manage the document title.
             * By adding theme support, we declare that this theme does not use a
             * hard-coded <title> tag in the document head, and expect WordPress to
             * provide it for us.
             */
            add_theme_support('title-tag');

            /**
             * Registers an editor stylesheet for the theme.
             */
            function glint_theme_add_editor_styles()
            {
                add_editor_style('custom-style.css');
            }
            add_action('admin_init', 'glint_theme_add_editor_styles');

            /*
             * Enable support for Post Thumbnails on posts and pages.
             *
             * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
             */
            add_theme_support('post-thumbnails');

            add_theme_support('custom-header');

            //Custom image size
            add_image_size('glint-blog', 732, 475, true);
            add_image_size('glint-blog-widget', 70, 70, true);

            // This theme uses wp_nav_menu() in one location.
            register_nav_menus(array(
                'mainmenu' => esc_html__('Primary Menu', 'glint'),
            ));

            register_nav_menus(array(
                'footermenu' => esc_html__('Footer Menu', 'glint'),
            ));

            /*
             * Switch default core markup for search form, comment form, and comments
             * to output valid HTML5.
             */
            add_theme_support('html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            ));

            // Set up the WordPress core custom background feature.
            add_theme_support('custom-background', apply_filters('glint_custom_background_args', array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )));

            // Add theme support for selective refresh for widgets.
            add_theme_support('customize-selective-refresh-widgets');

            /**
             * Add support for core custom logo.
             *
             * @link https://codex.wordpress.org/Theme_Logo
             */
            add_theme_support('custom-logo', array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            ));

            // This variable is intended to be overruled from themes.
            // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
            $GLOBALS['content_width'] = apply_filters('glint_content_width', 640);

            //post formats
            add_theme_support('post-formats', array('image', 'video', 'quote', 'link', 'gallery'));

            //load theme dependency files
            self::include_files();
        }

        /**
         * widgets_init
         * @since 1.0.0
         * */
        public function widgets_init()
        {

            register_sidebar(array(
                'name'          => esc_html__('Sidebar', 'glint'),
                'id'            => 'sidebar-1',
                'description'   => esc_html__('Add widgets here.', 'glint'),
                'before_widget' => '<div id="%1$s" class="widget %2$s blog-box blog-post text-left">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '<span>.</span></h3><div class="border-effect"></div>',
            ));

            register_sidebar(array(
                'name'          => esc_html__('Header Side panel Menu Widget', 'glint'),
                'id'            => 'header-widget-nav',
                'description'   => esc_html__('Add Header Side Panel widgets here.', 'glint'),
                'before_widget' => '<div id="%1$s" class="header-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ));
        }

        /**
         * include files
         * @since 1.0.0
         * */
        public function include_files()
        {

            $includes_files = array(

                array(
                    'file-name' => 'breadcrumb',
                    'file-path' => GLINT_INC,
                ),

                array(
                    'file-name' => 'class-theme-hooks',
                    'file-path' => GLINT_INC,
                ),

                array(
                    'file-name' => 'comments-modification',
                    'file-path' => GLINT_INC,
                ),

                array(
                    'file-name' => 'customizer',
                    'file-path' => GLINT_INC,
                ),

                array(
                    'file-name' => 'class-glint-excerpt',
                    'file-path' => GLINT_INC,
                ),

                array(
                    'file-name' => 'theme-customizer',
                    'file-path' => GLINT_THEME_OPTIONS,
                ),

                array(
                    'file-name' => 'theme-metabox',
                    'file-path' => GLINT_THEME_OPTIONS,
                ),

                array(
                    'file-name' => 'theme-options',
                    'file-path' => GLINT_THEME_OPTIONS,
                ),

                array(
                    'file-name' => 'theme-inline-styles',
                    'file-path' => GLINT_THEME_STYLESHEETS,
                ),

            );

            if (is_array($includes_files) && !empty($includes_files)) {
                foreach ($includes_files as $file) {
                    if (file_exists($file['file-path'] . '/' . $file['file-name'] . '.php')) {
                        require_once $file['file-path'] . '/' . $file['file-name'] . '.php';
                    }
                }
            }
        }

        /**
         * theme assets
         * @since 1.0.0
         * */
        public function theme_assets()
        {
            self::theme_css();
            self::theme_js();
        }

        /**
         * theme css
         * @since 1.0.0
         * */
        /*
         *Glint load font
         */
        public static function glint_fonts_url()
        {
            $fonts_url = '';
            $fonts     = array();
            $subsets   = 'latin,latin-ext';
            if ('off' !== esc_html_x('on', 'Rubik font: on or off', 'glint')) {
                $fonts[] = esc_html('Rubik:300,400,600');
            }
            if ('off' !== esc_html_x('on', 'Oswald font: on or off', 'glint')) {
                $fonts[] = esc_html('Oswald:400,600,700');
            }
            if ($fonts) {
                $fonts_url = add_query_arg(array(
                    'family' => implode('|', $fonts),
                    'subset' => $subsets,
                ),  '//fonts.googleapis.com/css');
            }
            return esc_url_raw($fonts_url);
        }

        public function theme_css()
        {

            $ver = GLINT_VERSION;
            $includes_css = array(
                array(
                    'handle' => 'glint-font',
                    'src'    => self::glint_fonts_url(),
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'animate',
                    'src'    => GLINT_CSS . '/animate.min.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'bootstrap',
                    'src'    => GLINT_CSS . '/bootstrap.min.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'font-awesome',
                    'src'    => GLINT_CSS . '/font-awesome.min.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'modal-video',
                    'src'    => GLINT_CSS . '/modal-video.min.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'nice-select',
                    'src'    => GLINT_CSS . '/nice-select.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'owl-carousel',
                    'src'    => GLINT_CSS . '/owl.carousel.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'progress-css',
                    'src'    => GLINT_CSS . '/progresscircle.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'steller-nav',
                    'src'    => GLINT_CSS . '/stellarnav.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'glint-main',
                    'src'    => GLINT_CSS . '/main.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
                array(
                    'handle' => 'glint-responsive',
                    'src'    => GLINT_CSS . '/responsive.css',
                    'deps'   => array(),
                    'ver'    => $ver,
                    'media'  => 'all',
                ),
            );

            if (is_array($includes_css) && !empty($includes_css)) {
                foreach ($includes_css as $css) {
                    call_user_func_array('wp_enqueue_style', $css);
                }
            }

            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            wp_enqueue_style('glint-style', get_stylesheet_uri());
        }

        /**
         * theme js
         * @since 1.0.0
         * */
        public function theme_js()
        {

            wp_enqueue_script('animatenumber', get_template_directory_uri() . '/assets/js/animatenumber.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('appear', get_template_directory_uri() . '/assets/js/appear.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('wow', get_template_directory_uri() . '/assets/js/wow.min.js', array('jquery'), '1.1.3', true);
            wp_enqueue_script('bar', get_template_directory_uri() . '/assets/js/bars.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('select', get_template_directory_uri() . '/assets/js/jquery.nice-select.min.js', array('jquery'), '1.6.2', true);
            wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '4.4.1', true);
            wp_enqueue_script('hoverdir', get_template_directory_uri() . '/assets/js/jquery.hoverdir.js', array('jquery'), '1.1.2', true);
            wp_enqueue_script('nav', get_template_directory_uri() . '/assets/js/jquery.nav.js', array('jquery'), '3.0.0', true);
            wp_enqueue_script('waypoints', get_template_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('jquery'), '4.0.0', true);
            wp_enqueue_script('modalvideo', get_template_directory_uri() . '/assets/js/jquery-modal-video.min.js', array('jquery'), '2.4.1', true);
            wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.js', array('jquery'), '2.2.1', true);
            wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('counteru', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('instafeed', get_template_directory_uri() . '/assets/js/instafeed.min.js', array('jquery'), '1.0.0', true);
            wp_enqueue_script('isotope', get_template_directory_uri() . '/assets/js/isotope-min.js', array('jquery'), '2.6.0', true);
            wp_enqueue_script('stellarnav', get_template_directory_uri() . '/assets/js/stellarnav.js', array('jquery'), '2.6.0', true);
            wp_enqueue_script('progressbar', get_template_directory_uri() . '/assets/js/progresscircle.js', array('jquery'), '2.6.0', true);
            wp_enqueue_script('widget-active', get_template_directory_uri() . '/assets/js/el-widget-active.js', array('jquery'), '2.6.0', true);
            wp_enqueue_script('glint-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
            wp_localize_script(
                'glint-main-js',
                'mainJsObj',
                array(
                    'phoneText' => cs_get_option('mobile_phone_text'),
                    'locationUrl' => cs_get_option('mobile_location_url'),
                )
            );

            // var_dump(cs_get_option('mobile_phone_text'));
            // wp_die();

            if (function_exists('cs_get_option')):
                $css_custom = cs_get_option('custom_css');
            endif;

            if (function_exists('cs_get_option')):
                wp_add_inline_style('glint-main', $css_custom);
            endif;
        }
    } //end class

    if (class_exists('Glint_Init')) {
        Glint_Init::getInstance();
    }
}
