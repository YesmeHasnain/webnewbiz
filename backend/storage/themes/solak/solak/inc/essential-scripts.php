<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function solak_essential_scripts() {

    wp_enqueue_style( 'solak-style', get_stylesheet_uri() ,array(), wp_get_theme()->get( 'Version' ) ); 

    // google font
    wp_enqueue_style( 'solak-fonts', solak_google_fonts() ,array(), null );

    // Bootstrap Min
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ) ,array(), '5.0.0' );

    // Font Awesome Six
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/assets/css/fontawesome.min.css' ) ,array(), '6.0.0' );

    // Magnific Popup
    wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.min.css' ), array(), '1.0' );

    // Swiper css
    wp_enqueue_style( 'swiper-css', get_theme_file_uri( '/assets/css/swiper-bundle.min.css' ) ,array(), '4.0.13' );

    // Wishlist css
    wp_enqueue_style( 'wishlist-css', get_theme_file_uri( '/assets/css/th-wl.css' ), array(), '1.0' );

    // solak main style
    wp_enqueue_style( 'solak-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), wp_get_theme()->get( 'Version' ) );


    // Load Js

    // Bootstrap
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '5.0.0', true );

    // swiper js
    wp_enqueue_script( 'swiper-js', get_theme_file_uri( '/assets/js/swiper-bundle.min.js' ), array('jquery'), '1.0.0', true );

    // magnific popup
    wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.1.0', true );

    // counterup
    wp_enqueue_script( 'counterup', get_theme_file_uri( '/assets/js/jquery.counterup.min.js' ), array( 'jquery' ), '4.0.0', true );

    // circle-progress
    wp_enqueue_script( 'circle-progress', get_theme_file_uri( '/assets/js/circle-progress.js' ), array( 'jquery' ), '1.2.2', true );

    // jquery-ui
    wp_enqueue_script( 'jquery-ui-slider' );

    // Isotope Imagesloaded
    wp_enqueue_script( 'imagesloaded' ); 

    // Isotope
    wp_enqueue_script( 'isototpe-pkgd', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0.0', true );

    // tilt-jquery
    wp_enqueue_script( 'tilt.jquery', get_theme_file_uri( '/assets/js/tilt.jquery.min.js' ), array( 'jquery' ), '1.0.0', true );

    // nice-select
    wp_enqueue_script( 'nice-select', get_theme_file_uri( '/assets/js/nice-select.min.js' ), array( 'jquery' ), '1.0.0', true );

    // wow
    wp_enqueue_script( 'wow', get_theme_file_uri( '/assets/js/wow.min.js' ), array( 'jquery' ), '1.3.0', true );

    // Gsap JS
    wp_enqueue_script( 'gsap', get_theme_file_uri( '/assets/js/gsap.min.js' ), array( 'jquery' ), '3.11.4', true );
    wp_enqueue_script( 'ScrollTrigger', get_theme_file_uri( '/assets/js/ScrollTrigger.min.js' ), array( 'jquery' ), '3.11.4', true );
    wp_enqueue_script( 'SplitText', get_theme_file_uri( '/assets/js/SplitText.min.js' ), array( 'jquery' ), '3.11.2', true );
    wp_enqueue_script( 'ScrollSmoother', get_theme_file_uri( '/assets/js/ScrollSmoother.min.js' ), array( 'jquery' ), '3.11.4', true );
    wp_enqueue_script( 'ScrollToPlugin', get_theme_file_uri( '/assets/js/ScrollToPlugin.min.js' ), array( 'jquery' ), '3.11.4', true );
    wp_enqueue_script( 'lenis.min', get_theme_file_uri( '/assets/js/lenis.min.js' ), array( 'jquery' ), '1.0.0', true );

    // main script
    wp_enqueue_script( 'solak-main-script', get_theme_file_uri( '/assets/js/main.js' ), array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'solak_essential_scripts',99 );


function solak_block_editor_assets( ) {
    // Add custom fonts.
    wp_enqueue_style( 'solak-editor-fonts', solak_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'solak_block_editor_assets' );

/*
Register Fonts
*/
function solak_google_fonts() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language. 
     */
     
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'solak' ) ) {
        $font_url =  'https://fonts.googleapis.com/css2?family=Anek+Latin:wght@100..800&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap';
    }
    return $font_url;
}