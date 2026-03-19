<?php

define( 'SIBERIA_THEME_DIRECTORY', esc_url( trailingslashit( get_template_directory_uri() ) ) );
define( 'SIBERIA_REQUIRE_DIRECTORY', trailingslashit( get_template_directory() ) );
define( 'SIBERIA_DEVELOPMENT', true );

/**
 * After Setup
 */

function siberia_setup() {

	register_nav_menus( array(
		'primary-menu' => esc_html__( 'Primary Menu', 'siberia' )
	) );

	load_theme_textdomain( 'siberia', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-formats', array('aside', 'image', 'video', 'audio'));
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',	) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );

	add_image_size( 'siberia-admin-list-thumb', 50, 50, true );
	add_image_size( 'siberia-featured-single-post', 1240, 500, true );
	add_image_size( 'siberia-default-post-thumb', 1240, 480, true );
	add_image_size( 'siberia-card-post-thumb', 400, 268, true );
	add_image_size( 'siberia-list-post-thumb', 400, 268, true );
	add_image_size( 'siberia-portfolio-thumb', 1120, 9999, true );
	add_image_size( 'siberia-portfolio-nav-thumb', 1260, 500, true );
	add_image_size( 'siberia-recent-post-thumb', 80, 80, true );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style( 'style-editor.css' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support dark editor style
	add_theme_support( 'dark-editor-style' );

	// Editor color palette.
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Primary', 'siberia' ),
				'slug' => 'primary',
				'color' => '#1258ca',
			),
			array(
				'name'  => esc_html__( 'Accent', 'siberia' ),
				'slug' => 'accent',
				'color' => '#c70a1a',
			),
			array(
				'name'  => esc_html__( 'Success', 'siberia' ),
				'slug' => 'success',
				'color' => '#88c559',
			),
			array(
				'name'  => esc_html__( 'Black', 'siberia' ),
				'slug' => 'black',
				'color' => '#263654',
			),
			array(
				'name'  => esc_html__( 'Contrast', 'siberia' ),
				'slug' => 'contrast',
				'color' => '#292a2d',
			),
			array(
				'name'  => esc_html__( 'Contrast Medium', 'siberia' ),
				'slug' => 'contrast-medium',
				'color' => '#79797c',
			),
			array(
				'name'  => esc_html__( 'Contrast lower', 'siberia' ),
				'slug' => 'contrast lower',
				'color' => '#323639',
			),
			array(
				'name'  => esc_html__( 'White', 'siberia' ),
				'slug' => 'white',
				'color' => '#ffffff',
			)
		)
	);

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => __( 'Small', 'siberia' ),
				'shortName' => __( 'S', 'siberia' ),
				'size'      => 14,
				'slug'      => 'small',
			),
			array(
				'name'      => __( 'Normal', 'siberia' ),
				'shortName' => __( 'M', 'siberia' ),
				'size'      => 16,
				'slug'      => 'normal',
			),
			array(
				'name'      => __( 'Large', 'siberia' ),
				'shortName' => __( 'L', 'siberia' ),
				'size'      => 24,
				'slug'      => 'large',
			),
			array(
				'name'      => __( 'Huge', 'siberia' ),
				'shortName' => __( 'XL', 'siberia' ),
				'size'      => 28,
				'slug'      => 'huge',
			),
		)
	);
	
}

add_action( 'after_setup_theme', 'siberia_setup' );

/**
 * Content Width
 */
function siberia_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'siberia_content_width', 1170 );
}
add_action( 'after_setup_theme', 'siberia_content_width', 0 );


/**
 * Add Editor Styles
 */
function siberia_add_editor_styles() {
	add_editor_style( 'editor-styles.css' );
}
add_action( 'admin_init', 'siberia_add_editor_styles' );

/**
 * Include and IMPORT/EXPORT ACF fields via JSON
 */
if( false == SIBERIA_DEVELOPMENT ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
	require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/helper/custom-fields/custom-fields.php';
}

function siberia_acf_save_json( $path ) {
	$path = SIBERIA_REQUIRE_DIRECTORY . 'inc/helper/custom-fields';
	return $path;
}
add_filter( 'acf/settings/save_json', 'siberia_acf_save_json' );

function siberia_acf_load_json( $paths ) {
	unset( $paths[0] );
	$paths[] = SIBERIA_REQUIRE_DIRECTORY . 'inc/helper/custom-fields';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'siberia_acf_load_json' );

/**
 * Include required files
 */

// TGM
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/helper/class-tgm-plugin-activation.php';
// TGM register plugins
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-required-plugins.php';
// Style and scripts for theme
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-enqueue.php';
// Theme Functions
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-functions.php';
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-actions.php';
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-filters.php';
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/theme-demo-import.php';

/**
 * Include kirki fields
 */
if ( class_exists( 'Kirki' ) ) {
	require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/customizer.php';
}
function siberia_load_all_variants_and_subsets() {
    if ( class_exists( 'Kirki_Fonts_Google' ) ) {
        Kirki_Fonts_Google::$force_load_all_variants = true;
        Kirki_Fonts_Google::$force_load_all_subsets = true;
    }
}
add_action( 'after_setup_theme', 'siberia_load_all_variants_and_subsets' );