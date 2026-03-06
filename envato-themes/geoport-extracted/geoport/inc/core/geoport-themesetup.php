<?php 
/**  add action with call back function .
--------------------------------------------------------------------------------------------------- */
add_action('after_setup_theme', 'geoport_content_width', 0);
add_action('after_setup_theme', 'geoport_setup');

/*------------------------------------------------------------------------------------------------------------------*/
/*	geoport setup
/*------------------------------------------------------------------------------------------------------------------*/

if ( !function_exists('geoport_setup') ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
	function geoport_setup() {
		
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on widely, use a find and replace
		 * to change 'widely' to the name of your theme in all the template files
		 --------------------------------------------------------------------------------------------------- */
		load_theme_textdomain( 'geoport', get_template_directory() . '/languages' );

		/*
		* Add default posts and comments RSS feed links to head.
		--------------------------------------------------------------------------------------------------- */
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		--------------------------------------------------------------------------------------------------- */
		add_theme_support( 'title-tag' );

		/* 
		 * Enable support for Post Thumbnails on posts and pages.
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		--------------------------------------------------------------------------------------------------- */
		add_theme_support( 'post-thumbnails' );

		/** Gutenberg Support
		--------------------------------------------------------------------------------------------------- */
		add_theme_support( 'align-wide' );

		/** Add Custom Image Size.
		--------------------------------------------------------------------------------------------------- */
		add_image_size( 'geoport-770-460', 770, 460, TRUE );
		add_image_size( 'geoport-470-330', 470, 330, TRUE );
		add_image_size( 'geoport-thumb-140-140', 140, 140, TRUE );

		/** This theme uses wp_nav_menu() in one location..
		--------------------------------------------------------------------------------------------------- */
		register_nav_menus(array(
			'primary' => esc_html__('Primary Menu', 'geoport'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		--------------------------------------------------------------------------------------------------- */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		--------------------------------------------------------------------------------------------------- */
		add_theme_support('post-formats', array(
			'image',
			'video',
			'quote',
			'link',
		));

		/** Set up the WordPress core custom background feature.
		--------------------------------------------------------------------------------------------------- */
		add_theme_support('custom-background', apply_filters('geoport_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		/* 
		 * enable custom logo support
		 * set up the WordPress custome Logo support
		--------------------------------------------------------------------------------------------------- */
		add_theme_support( 'custom-logo', array(
			'height'      => 65,
			'width'       => 245,
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		/* - For Woocommerce
		======================================================================================*/
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
endif; // geoport_setup


/*------------------------------------------------------------------------------------------------------------------*/
/*	Sidebar Register
/*------------------------------------------------------------------------------------------------------------------*/
/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 *
 *
**/

function geoport_widgets_init() {
	register_sidebar(array(
		'name' => esc_html__('Sidebar Widgets', 'geoport'),
		'id' => 'right-sidebar',
		'description' => esc_html__('Widgets in this area will be shown on page Sidebar.', 'geoport'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<div class="widget-title"><span></span><h4>',
		'after_title'   => '</h4></div>',
	));
	register_sidebar(array(
		'name' => esc_html__('Footer Widgets 1', 'geoport'),
		'id' => 'footer-widgets1',
		'description' => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
		'before_widget' => '<div id="%1$s" class="%2$s"><div class="%2$s footer-widget">',
		'after_widget' => '</div></div>',
		'before_title'  => '<div class="footer-widget-title"><h4>',
		'after_title'   => '</h4></div>',
	));
	register_sidebar(array(
		'name' => esc_html__('Footer Widgets 2', 'geoport'),
		'id' => 'footer-widgets2',
		'description' => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
		'before_widget' => '<div id="%1$s" class="%2$s"><div class="%2$s footer-widget">',
		'after_widget' => '</div></div>',
		'before_title'  => '<div class="footer-widget-title"><h4>',
		'after_title'   => '</h4></div>',
	));
	register_sidebar(array(
		'name' => esc_html__('Footer Widgets 3', 'geoport'),
		'id' => 'footer-widgets3',
		'description' => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
		'before_widget' => '<div id="%1$s" class="%2$s"><div class="%2$s footer-widget">',
		'after_widget' => '</div></div>',
		'before_title'  => '<div class="footer-widget-title"><h4>',
		'after_title'   => '</h4></div>',
	));
	register_sidebar(array(
		'name' => esc_html__('Footer Widgets 4', 'geoport'),
		'id' => 'footer-widgets4',
		'description' => esc_html__('Widgets in this area will be shown on footer.', 'geoport'),
		'before_widget' => '<div id="%1$s" class="%2$s"><div class="%2$s footer-widget">',
		'after_widget' => '</div></div>',
		'before_title'  => '<div class="footer-widget-title"><h4>',
		'after_title'   => '</h4></div>',
	));
	if ( class_exists( 'woocommerce' ) ) {
		register_sidebar(array(
			'name' 			=> esc_html__('Shop Sidebar', 'geoport'),
			'id' 			=> 'shop',
			'description' 	=> '',
			'before_widget' => '<div id="%1$s" class="sidebar-widget woo-siebar %2$s">',
			'after_widget' 	=> '</div>',
			'before_title'  => '<div class="widget-title"><span></span><h4>',
			'after_title'   => '</h4></div>',
		));
	}
}
add_action('widgets_init', 'geoport_widgets_init');


/**
 * Add Woocommerce-activateg class to the body.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function geoport_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'geoport_woocommerce_active_body_class' );

/*------------------------------------------------------------------------------------------------------------------*/
/*	  $content_width
/*------------------------------------------------------------------------------------------------------------------*/ 
  
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function geoport_content_width() {
	$GLOBALS['content_width'] = apply_filters('geoport_content_width', 1170);
}