<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'solak_setup' ) ){
    function solak_setup() {

        // content width
        $GLOBALS['content_width'] = apply_filters( 'solak_content_width', 751 );

		// language file
		function solak_load_textdomain() {
			load_theme_textdomain( 'solak', get_template_directory() . '/languages' );
		}
		add_action( 'after_setup_theme', 'solak_load_textdomain' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// title tag
		add_theme_support( 'title-tag' );

		// post thumbnails
		add_theme_support( 'post-thumbnails' );

        add_image_size( 'solak-shop-single',580,580,true );
        add_image_size( 'solak-shop-thumb',100,106,true );
        add_image_size( 'solak-shop-small-image',294,312,true ); 
        add_image_size( 'solak_90X90',90,90,true );

        register_nav_menus( array(
            'primary-menu'      => esc_html__( 'Primary Menu', 'solak' ),
        ) );

		//support html5
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style'
			)
		);

		add_theme_support('custom-header');

		add_theme_support('custom-background');


        // support post format
        add_theme_support( 'post-formats', array( 'audio', 'video', 'gallery', 'quote') );

		// Custom logo
		add_theme_support( 'custom-logo' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'assets/css/style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

        // support woocommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-slider' );

	}
}
add_action( 'after_setup_theme', 'solak_setup' );