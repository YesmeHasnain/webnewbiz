<?php
/**
 * Saasten functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package saasten
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

define('SAASTEN_DEV_MODE',true);
$saasten_version = SAASTEN_DEV_MODE ? time() : $theme_info->get('Version');
define('SAASTEN_NAME',$theme_info->get('Name'));
define('SAASTEN_VERSION',$saasten_version);
define('SAASTEN_AUTHOR',$theme_info->get('Author'));
define('SAASTEN_AUTHOR_URI',$theme_info->get('AuthorURI'));


/**
 * Define Const for theme Dir
 * @since 1.0.0
 * */

define('SAASTEN_THEME_URI', get_template_directory_uri());
define('SAASTEN_IMG', SAASTEN_THEME_URI . '/assets/images');
define('SAASTEN_CSS', SAASTEN_THEME_URI . '/assets/css');
define('SAASTEN_JS', SAASTEN_THEME_URI . '/assets/js');
define('SAASTEN_THEME_DIR', get_template_directory());
define('SAASTEN_IMG_DIR', SAASTEN_THEME_DIR . '/assets/images');
define('SAASTEN_CSS_DIR', SAASTEN_THEME_DIR . '/assets/css');
define('SAASTEN_JS_DIR', SAASTEN_THEME_DIR . '/assets/js');
define('SAASTEN_INC', SAASTEN_THEME_DIR . '/inc');
define('SAASTEN_THEME_OPTIONS',SAASTEN_INC .'/theme-options');
define('SAASTEN_THEME_OPTIONS_IMG',SAASTEN_THEME_OPTIONS .'/img');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
*/
	 
function saasten_setup(){
	
	// make the theme available for translation
	load_theme_textdomain( 'saasten', get_template_directory() . '/languages' );
	
	// add support for post formats
    add_theme_support('post-formats', [
        'standard', 'image', 'video', 'audio','gallery'
    ]);

    // add support for automatic feed links
    add_theme_support('automatic-feed-links');

    // let WordPress manage the document title
    add_theme_support('title-tag');
	
	// add editor style theme support
	function saasten_theme_add_editor_styles() {
		add_editor_style( 'custom-style.css' );
	}
	add_action( 'admin_init', 'saasten_theme_add_editor_styles' );

    // add support for post thumbnails
    add_theme_support('post-thumbnails');
	
	// hard crop center center
    set_post_thumbnail_size(803, 490, ['center', 'center']);
	add_image_size( 'saasten-box-slider-small', 96, 96, true );
	
	
	// register navigation menus
    register_nav_menus(
        [
            'primary' => esc_html__('Primary Menu', 'saasten'),
            'footermenu' => esc_html__('Footer Menu', 'saasten'),
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

add_action('after_setup_theme', 'saasten_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
*/
 
function saasten_widget_init() {
	

        register_sidebar( array (
			'name' => esc_html__('Blog widget area', 'saasten'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Blog Sidebar Widget.', 'saasten'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			
		) );
		
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area One', 'saasten' ),
			'id'            => 'footer-widget-1',
			'description'   => esc_html__( 'Add Footer  widgets here.', 'saasten' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Two', 'saasten' ),
			'id'            => 'footer-widget-2',
			'description'   => esc_html__( 'Add Footer widgets here.', 'saasten' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Three', 'saasten' ),
			'id'            => 'footer-widget-3',
			'description'   => esc_html__( 'Add Footer widgets here.', 'saasten' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );			

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widget Area Four', 'saasten' ),
			'id'            => 'footer-widget-4',
			'description'   => esc_html__( 'Add Footer widgets here.', 'saasten' ),
			'before_widget' => '<div id="%1$s" class="footer-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );		
					
}

add_action('widgets_init', 'saasten_widget_init');


/**
 * Enqueue scripts and styles.
 */
function saasten_scripts() {
	
	// Theme CSS 
	
	wp_enqueue_style( 'themefont-awesome', SAASTEN_CSS . '/font-awesome.css');
	wp_enqueue_style( 'icon-font',  SAASTEN_CSS . '/icon-font.css' );
	wp_enqueue_style( 'remix-font',  SAASTEN_CSS . '/remixicon.css' );
	wp_enqueue_style( 'animate',  SAASTEN_CSS . '/animate.css' );
	//wp_enqueue_style( 'aos',  SAASTEN_CSS . '/aos.min.css' );
	wp_enqueue_style( 'magnific-popup',  SAASTEN_CSS . '/magnific-popup.css' );
	wp_enqueue_style( 'owl-carousel',  SAASTEN_CSS . '/owl.carousel.min.css' );
	wp_enqueue_style( 'owl-theme',  SAASTEN_CSS . '/owl.theme.min.css' );
	wp_enqueue_style( 'slick',  SAASTEN_CSS . '/slick.css' );
	wp_enqueue_style( 'slicknav',  SAASTEN_CSS . '/slicknav.css' );
	wp_enqueue_style( 'bootstrap', SAASTEN_CSS . '/bootstrap.min.css', array(), '4.0', 'all');
	wp_enqueue_style( 'theme-fonts', SAASTEN_CSS . '/theme-fonts.css', array(), '1.0', 'all');
	wp_enqueue_style( 'saasten-main',  SAASTEN_CSS . '/main.css' );
	wp_enqueue_style( 'saasten-responsive',  SAASTEN_CSS . '/responsive.css' );	

	wp_enqueue_style( 'saasten-style', get_stylesheet_uri() );
	
	// Theme JS
	
	wp_enqueue_script( 'bootstrap',  SAASTEN_JS . '/bootstrap.min.js', array( 'jquery' ),  '4.0', true );
	wp_enqueue_script( 'popper',  SAASTEN_JS . '/popper.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'jquery-magnific-popup',  SAASTEN_JS . '/jquery.magnific-popup.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'jquery-appear',  SAASTEN_JS . '/jquery.appear.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'owl-carousel',  SAASTEN_JS . '/owl.carousel.min.js', array( 'jquery' ),  '1.0', true );
	wp_enqueue_script( 'slick', SAASTEN_JS . '/slick.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jquery-slicknav', SAASTEN_JS . '/jquery.slicknav.min.js', array( 'jquery' ), '1.0', true );

	// Custom JS Scripts
	
	wp_enqueue_script( 'saasten-scripts',  SAASTEN_JS . '/scripts.js', array( 'jquery' ),  '1.0', true );


	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	

}

add_action( 'wp_enqueue_scripts', 'saasten_scripts' );


/*
* Include codester helper functions
* @since 1.0.0
*/

if ( file_exists( SAASTEN_INC.'/cs-framework-functions.php' ) ) {
	require_once  SAASTEN_INC.'/cs-framework-functions.php';
}

/**
 * Theme option panel & Metaboxes.
*/
 if ( file_exists( SAASTEN_THEME_OPTIONS.'/theme-options.php' ) ) {
	require_once  SAASTEN_THEME_OPTIONS.'/theme-options.php';
}

if ( file_exists( SAASTEN_THEME_OPTIONS.'/theme-metabox.php' ) ) {
	require_once  SAASTEN_THEME_OPTIONS.'/theme-metabox.php';
}

if ( file_exists( SAASTEN_THEME_OPTIONS.'/theme-customizer.php' ) ) {
	require_once  SAASTEN_THEME_OPTIONS.'/theme-customizer.php';
}


if ( file_exists( SAASTEN_THEME_OPTIONS.'/theme-inline-styles.php' ) ) {
	require_once  SAASTEN_THEME_OPTIONS.'/theme-inline-styles.php';
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
function saasten_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'saasten_content_width', 640 );
}

add_action( 'after_setup_theme', 'saasten_content_width', 0 );

/**
 * Nav menu fallback function
*/

function saasten_fallback_menu() {
	get_template_part( 'template-parts/default', 'menu' );
}


function saasten_enable_svg_upload( $upload_mimes ) {
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}
add_filter( 'upload_mimes', 'saasten_enable_svg_upload', 10, 1 );





