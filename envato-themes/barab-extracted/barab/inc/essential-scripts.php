<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function barab_essential_scripts() {

    wp_enqueue_style( 'barab-style', get_stylesheet_uri() ,array(), wp_get_theme()->get( 'Version' ) ); 

    // google font
    wp_enqueue_style( 'barab-fonts', barab_google_fonts() ,array(), null );
 
    // Bootstrap Min
    wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ) ,array(), '5.0.0' );

    // Font Awesome Six
    wp_enqueue_style( 'fontawesome', get_theme_file_uri( '/assets/css/fontawesome.min.css' ) ,array(), '6.0.0' );

    // Magnific Popup
    wp_enqueue_style( 'magnific-popup', get_theme_file_uri( '/assets/css/magnific-popup.min.css' ), array(), '1.0' );

    // datetimepicker
    wp_enqueue_style( 'datetimepicker', get_theme_file_uri( '/assets/css/jquery.datetimepicker.min.css' ), array(), '1.0' );

    // Swiper css
    wp_enqueue_style( 'swiper-css', get_theme_file_uri( '/assets/css/swiper-bundle.min.css' ) ,array(), '4.0.13' );

    // Wishlist css
    wp_enqueue_style( 'wishlist-css', get_theme_file_uri( '/assets/css/th-wl.css' ), array(), '1.0' );

    // barab main style
    wp_enqueue_style( 'barab-main-style', get_theme_file_uri('/assets/css/style.css') ,array(), wp_get_theme()->get( 'Version' ) );


    // Load Js

    // Bootstrap
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), '5.0.0', true );

    // swiper js
    wp_enqueue_script( 'swiper-js', get_theme_file_uri( '/assets/js/swiper-bundle.min.js' ), array('jquery'), '1.0.0', true );

    // magnific popup
    wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array('jquery'), '1.1.0', true );
 
    // counterup
    wp_enqueue_script( 'counterup', get_theme_file_uri( '/assets/js/jquery.counterup.min.js' ), array( 'jquery' ), '4.0.0', true );

    // jquery-ui
    wp_enqueue_script( 'jquery-ui-slider' );

    // Isotope Imagesloaded
    wp_enqueue_script( 'imagesloaded' ); 

    // Isotope
    wp_enqueue_script( 'isototpe-pkgd', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0.0', true );

    // datetimepicker
    wp_enqueue_script( 'datetimepicker', get_theme_file_uri( '/assets/js/jquery.datetimepicker.min.js' ), array( 'jquery' ), '1.0.0', true );

    // wow
    wp_enqueue_script( 'wow', get_theme_file_uri( '/assets/js/wow.min.js' ), array( 'jquery' ), '1.0.0', true );

    // tilt
    wp_enqueue_script( 'tilt', get_theme_file_uri( '/assets/js/tilt.jquery.min.js' ), array( 'jquery' ), '1.0.0', true );

    // gsap start
    wp_enqueue_script( 'gsap', get_theme_file_uri( '/assets/js/gsap.min.js' ), array( 'jquery' ), '3.7.1', true );
    wp_enqueue_script( 'ScrollTrigger', get_theme_file_uri( '/assets/js/ScrollTrigger.min.js' ), array( 'jquery' ), '3.3.3', true );

    // lenis
    wp_enqueue_script( 'lenis', get_theme_file_uri( '/assets/js/lenis.min.js' ), array( 'jquery' ), '1.0.0', true );

    // Custom Animation
    wp_enqueue_script( 'custom-animation', get_theme_file_uri( '/assets/js/custom-animation.js' ), array('jquery'), '1.0.0', true );
    // gsap end


    wp_enqueue_script( 'barab-main-script', get_theme_file_uri( '/assets/js/main.js' ), array( 'jquery' ), wp_get_theme()->get( 'Version' ), true );


    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'barab_essential_scripts',99 );


function barab_block_editor_assets( ) {
    // Add custom fonts.
    wp_enqueue_style( 'barab-editor-fonts', barab_google_fonts(), array(), null );
}

add_action( 'enqueue_block_editor_assets', 'barab_block_editor_assets' );

/*
Register Fonts
*/
function barab_google_fonts() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language. 
     */
     
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'barab' ) ) {
        $font_url =  'https://fonts.googleapis.com/css2?family=Bangers&family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Dynalight&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jost:ital,wght@0,100..900;1,100..900&family=Bevan:ital@0;1&display=swap';
    }
    return $font_url;
}