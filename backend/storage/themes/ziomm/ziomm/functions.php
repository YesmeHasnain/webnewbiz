<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

define( 'ZIOMM_THEME_DIRECTORY', trailingslashit( get_template_directory_uri() ) );
define( 'ZIOMM_REQUIRE_DIRECTORY', trailingslashit( get_template_directory() ) );
define( 'ZIOMM_WOOCOMMERCE', class_exists( 'WooCommerce' ) ? true : false );
define( 'ZIOMM_DEVELOPMENT', false );

/**
 * After setup theme
 */
if ( ! function_exists( 'ziomm_setup' ) ) {
	function ziomm_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Ziomm, use a find and replace
		 * to change 'ziomm' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ziomm', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1920, 9999 );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'post-formats', array(
			'gallery',
			'link',
			'quote',
			'video',
			'audio'
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name' => esc_html__( 'Small', 'ziomm' ),
					'shortName' => esc_html__( 'S', 'ziomm' ),
					'size' => 15,
					'slug' => 'small',
				),
				array(
					'name' => esc_html__( 'Normal', 'ziomm' ),
					'shortName' => esc_html__( 'M', 'ziomm' ),
					'size' => 16,
					'slug' => 'normal',
				),
				array(
					'name' => esc_html__( 'Large', 'ziomm' ),
					'shortName' => esc_html__( 'L', 'ziomm' ),
					'size' => 26,
					'slug' => 'large',
				),
				array(
					'name' => esc_html__( 'Huge', 'ziomm' ),
					'shortName' => esc_html__( 'XL', 'ziomm' ),
					'size' => 34,
					'slug' => 'huge',
				),
			)
		);

		// Editor color palette.
		add_theme_support( 'editor-color-palette', array(
			array(
				'name' => esc_html__( 'First', 'ziomm' ),
				'slug' => 'first',
				'color' => ziomm_get_theme_mod( 'accent_colors' )[ 'first' ],
			),
			array(
				'name' => esc_html__( 'Second', 'ziomm' ),
				'slug' => 'second',
				'color' => ziomm_get_theme_mod( 'accent_colors' )[ 'second' ],
			),
			array(
				'name' => esc_html__( 'Text', 'ziomm' ),
				'slug' => 'text',
				'color' => '#242424',
			),
			array(
				'name' => esc_html__( 'White', 'ziomm' ),
				'slug' => 'white',
				'color' => '#ffffff',
			),
			array(
				'name' => esc_html__( 'Black', 'ziomm' ),
				'slug' => 'black',
				'color' => '#101010',
			)
		) );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// WooCommerce
		if ( ZIOMM_WOOCOMMERCE ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'woocommerce', array(
				'thumbnail_image_width' => 800,
				'gallery_thumbnail_image_width' => 150,
				'single_image_width' => 800,
			) );
		}

		// register nav menus
		register_nav_menus( array(
			'primary-menu' => esc_html__( 'Primary Menu', 'ziomm' ),
			'contact-menu' => esc_html__( 'Contact Menu', 'ziomm' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'ziomm' )
		) );

		// 800x605
		add_image_size( 'ziomm-800x605_crop', 800, 600, true );
		add_image_size( 'ziomm-800x605', 800 );

		// 1280x853
		add_image_size( 'ziomm-1280x750_crop', 1280, 853, true );
		add_image_size( 'ziomm-1280x750', 1280 );

		// 1920x1080
		add_image_size( 'ziomm-1920x1080_crop', 1920, 1080, true );
		add_image_size( 'ziomm-1920x1080', 1920 );

		// 1920x960
		add_image_size( 'ziomm-1920x960_crop', 1920, 960, true );

	}
}
add_action( 'after_setup_theme', 'ziomm_setup' );

/**
 * Content width
 */
if ( ! function_exists( 'ziomm_content_width' ) ) {
	function ziomm_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'ziomm/content_width', 1200 );
	}
}
add_action( 'after_setup_theme', 'ziomm_content_width', 0 );

/**
 * Import ACF fields
 */
if ( ! ZIOMM_DEVELOPMENT ) {
	function ziomm_acf_show_admin_panel() {
		return apply_filters( 'ziomm/acf_show_admin_panel', false );
	}
	add_filter( 'acf/settings/show_admin', 'ziomm_acf_show_admin_panel' );
}

if ( ! ZIOMM_DEVELOPMENT ) {
	require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/helper/custom-fields/custom-fields.php';
}

if ( ! function_exists( 'ziomm_acf_save_json' ) ) {
	function ziomm_acf_save_json( $path ) {
		$path = ZIOMM_REQUIRE_DIRECTORY . 'inc/helper/custom-fields';
		return $path;
	}
}
add_filter( 'acf/settings/save_json', 'ziomm_acf_save_json' );

if ( ZIOMM_DEVELOPMENT ) {
	if ( ! function_exists( 'ziomm_acf_load_json' ) ) {
		function ziomm_acf_load_json( $paths ) {
			unset( $paths[0] );
			$paths[] = ZIOMM_REQUIRE_DIRECTORY . 'inc/helper/custom-fields';
			return $paths;
		}
	}
	add_filter( 'acf/settings/load_json', 'ziomm_acf_load_json' );
}

/**
 * Include Kirki fields
 */
require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/customizer-helper.php';
require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/customizer.php';
require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/customizer-dynamic-css.php';

/**
 * Required files
 */
$ziomm_theme_includes = array(
	'required-plugins',
	'enqueue',
	'includes',
	'demo-import',
	'functions',
	'actions',
	'filters',
	'menus',
	'portfolio'
);

if ( ZIOMM_WOOCOMMERCE ) {
	$ziomm_theme_includes[] = 'woocommerce';
}

foreach ( $ziomm_theme_includes as $file ) {
	require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/theme-' . $file . '.php';
}

// Unset the global variable.
unset( $ziomm_theme_includes );