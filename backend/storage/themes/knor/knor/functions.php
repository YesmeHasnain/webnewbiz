<?php
/**
 * Knor functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package knor
 */


/**
 * define theme info
 * @since 1.0.0
 * */
 
 if (is_child_theme()){
	$theme = wp_get_theme();
	$parent_theme = $theme->Template;
	$theme_info = wp_get_theme($parent_theme);
}else{
	$theme_info = wp_get_theme();
}

define('KNOR_DEV_MODE',true);
$knor_version = KNOR_DEV_MODE ? time() : $theme_info->get('Version');
define('KNOR_NAME',$theme_info->get('Name'));
define('KNOR_VERSION',$knor_version);
define('KNOR_AUTHOR',$theme_info->get('Author'));
define('KNOR_AUTHOR_URI',$theme_info->get('AuthorURI'));


/**
 * Define Const for theme Dir
 * @since 1.0.0
 * */

define('KNOR_THEME_URI', get_template_directory_uri());
define('KNOR_IMG', KNOR_THEME_URI . '/assets/images');
define('KNOR_CSS', KNOR_THEME_URI . '/assets/css');
define('KNOR_JS', KNOR_THEME_URI . '/assets/js');
define('KNOR_THEME_DIR', get_template_directory());
define('KNOR_IMG_DIR', KNOR_THEME_DIR . '/assets/images');
define('KNOR_CSS_DIR', KNOR_THEME_DIR . '/assets/css');
define('KNOR_JS_DIR', KNOR_THEME_DIR . '/assets/js');
define('KNOR_INC', KNOR_THEME_DIR . '/inc');
define('KNOR_THEME_OPTIONS',KNOR_INC .'/theme-options');
define('KNOR_THEME_OPTIONS_IMG',KNOR_THEME_OPTIONS .'/img');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
*/
	 
function knor_setup(){
	
	// make the theme available for translation
	load_theme_textdomain( 'knor', get_template_directory() . '/languages' );
	
	// add support for post formats
    add_theme_support('post-formats', [
        'standard', 'image', 'video', 'audio','gallery'
    ]);

    // add support for automatic feed links
    add_theme_support('automatic-feed-links');

    // let WordPress manage the document title
    add_theme_support('title-tag');
	
	// add editor style theme support
	function knor_theme_add_editor_styles() {
		add_editor_style( 'custom-style.css' );
	}
	add_action( 'admin_init', 'knor_theme_add_editor_styles' );

    // add support for post thumbnails
    add_theme_support('post-thumbnails');
	
	// hard crop center center
    set_post_thumbnail_size(803, 490, ['center', 'center']);
	add_image_size( 'knor-box-slider-small', 96, 96, true );
	
	
	// register navigation menus
    register_nav_menus(
        [
            'primary' => esc_html__('Primary Menu', 'knor'),
            'footermenu' => esc_html__('Footer Menu', 'knor'),
        ]
    );
	
	
	// HTML5 markup support for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));
	
	
	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 150,
		'width'       => 300,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	
	
	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	
	/*
     * Enable support for wide alignment class for Gutenberg blocks.
     */
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
		
}

add_action('after_setup_theme', 'knor_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
*/
 
function knor_widget_init() {
	

        register_sidebar( array (
			'name' => esc_html__('Blog widget area', 'knor'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Blog Sidebar Widget.', 'knor'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			
		) );
		
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area One', 'knor' ),
			'id'            => 'footer-widget-1',
			'description'   => esc_html__( 'Add Footer  widgets here.', 'knor' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Two', 'knor' ),
			'id'            => 'footer-widget-2',
			'description'   => esc_html__( 'Add Footer widgets here.', 'knor' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Three', 'knor' ),
			'id'            => 'footer-widget-3',
			'description'   => esc_html__( 'Add Footer widgets here.', 'knor' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Four', 'knor' ),
			'id'            => 'footer-widget-4',
			'description'   => esc_html__( 'Add Footer widgets here.', 'knor' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );		


		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Five', 'knor' ),
			'id'            => 'footer-widget-5',
			'description'   => esc_html__( 'Add Footer widgets here.', 'knor' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
					
}

add_action('widgets_init', 'knor_widget_init');


/**
 * Enqueue scripts and styles.
 */
function knor_scripts() {
	
	// Theme CSS 
	
	wp_enqueue_style( 'themefont-awesome', KNOR_CSS . '/font-awesome.css');
	wp_enqueue_style( 'icon-font',  KNOR_CSS . '/icon-font.css' );
	wp_enqueue_style( 'remix-font',  KNOR_CSS . '/remixicon.css' );
	wp_enqueue_style( 'animate-css',  KNOR_CSS . '/animate.css' );
	wp_enqueue_style( 'magnific-popup',  KNOR_CSS . '/magnific-popup.css' );
	wp_enqueue_style( 'owl-carousel',  KNOR_CSS . '/owl.carousel.min.css' );
	wp_enqueue_style( 'owl-theme',  KNOR_CSS . '/owl.theme.min.css' );
	wp_enqueue_style( 'slick',  KNOR_CSS . '/slick.css' );
	wp_enqueue_style( 'slicknav',  KNOR_CSS . '/slicknav.css' );
	wp_enqueue_style( 'theme-fonts',  KNOR_CSS . '/theme-fonts.css' );
	wp_enqueue_style( 'bootstrap', KNOR_CSS . '/bootstrap.min.css', array(), '5.3', 'all');
	wp_enqueue_style( 'knor-main',  KNOR_CSS . '/main.css' );
	wp_enqueue_style( 'knor-responsive',  KNOR_CSS . '/responsive.css' );	

	wp_enqueue_style( 'knor-style', get_stylesheet_uri() );
	
	// Theme JS
	
	wp_enqueue_script( 'bootstrap',  KNOR_JS . '/bootstrap.min.js', array( 'jquery' ),  '4.0', true );
	wp_enqueue_script( 'popper',  KNOR_JS . '/popper.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'jquery-magnific-popup',  KNOR_JS . '/jquery.magnific-popup.min.js', array( 'jquery' ),  '1.0', true );

	wp_enqueue_script( 'isotopejs',  KNOR_JS . '/jquery-isotope.js', array( 'jquery' ),  '1.0', true );

	wp_enqueue_script( 'jquery-appear',  KNOR_JS . '/jquery.appear.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'jquery-waypoints',  KNOR_JS . '/jquery.waypoints.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'jquery-wows',  KNOR_JS . '/wow.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'owl-carousel',  KNOR_JS . '/owl.carousel.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'slick', KNOR_JS . '/slick.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jquery-slicknav', KNOR_JS . '/jquery.slicknav.min.js', array( 'jquery' ), '1.0', true );
	
	// Custom JS Scripts
	
	wp_enqueue_script( 'knor-scripts',  KNOR_JS . '/scripts.js', array( 'jquery' ),  '1.0', true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	

}

add_action( 'wp_enqueue_scripts', 'knor_scripts' );


/*
* Include codester helper functions
* @since 1.0.0
*/

if ( file_exists( KNOR_INC.'/cs-framework-functions.php' ) ) {
	require_once  KNOR_INC.'/cs-framework-functions.php';
}

/**
 * Theme option panel & Metaboxes.
*/
 if ( file_exists( KNOR_THEME_OPTIONS.'/theme-options.php' ) ) {
	require_once  KNOR_THEME_OPTIONS.'/theme-options.php';
}

if ( file_exists( KNOR_THEME_OPTIONS.'/theme-metabox.php' ) ) {
	require_once  KNOR_THEME_OPTIONS.'/theme-metabox.php';
}

// if ( file_exists( KNOR_THEME_OPTIONS.'/theme-customizer.php' ) ) {
// 	require_once  KNOR_THEME_OPTIONS.'/theme-customizer.php';
// }


if ( file_exists( KNOR_THEME_OPTIONS.'/theme-inline-styles.php' ) ) {
	require_once  KNOR_THEME_OPTIONS.'/theme-inline-styles.php';
}


/**
 * Required plugin installer 
*/
require get_template_directory() . '/inc/required-plugins.php';


/**
 * Custom template tags & functions for this theme.
*/
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
*/
function knor_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'knor_content_width', 640 );
}

add_action( 'after_setup_theme', 'knor_content_width', 0 );

/**
 * Nav menu fallback function
*/

function knor_fallback_menu() {
	get_template_part( 'template-parts/default', 'menu' );
}


function knor_enable_svg_upload( $upload_mimes ) {
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}
add_filter( 'upload_mimes', 'knor_enable_svg_upload', 10, 1 );





