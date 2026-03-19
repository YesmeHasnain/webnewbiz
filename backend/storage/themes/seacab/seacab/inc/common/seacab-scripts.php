<?php

/**
 * seacab_scripts description
 * @return [type] [description]
 */
function seacab_scripts() {

    /**
     * all css files
    */

    wp_enqueue_style( 'seacab-fonts', seacab_fonts_url(), array(), time() );
    if( is_rtl() ){
        wp_enqueue_style( 'bootstrap-rtl', SEACAB_THEME_ASSETS.'bootstrap.rtl.min.css', array() );
    }else{
        wp_enqueue_style( 'bootstrap', SEACAB_THEME_ASSETS.'vendors/bootstrap/css/bootstrap.min.css', array() );
    }
    wp_enqueue_style( 'animate', SEACAB_THEME_ASSETS . 'vendors/animate/animate.min.css', [] );
    wp_enqueue_style( 'custom-animate', SEACAB_THEME_ASSETS . 'vendors/animate/custom-animate.css', [] );
    wp_enqueue_style( 'font-awesome-5', SEACAB_THEME_ASSETS . 'vendors/fontawesome/css/all.min.css', [] );
    wp_enqueue_style( 'magnific-popup', SEACAB_THEME_ASSETS . 'vendors/jquery-magnific-popup/jquery.magnific-popup.css', [] );
    wp_enqueue_style( 'swiper', SEACAB_THEME_ASSETS . 'vendors/swiper/swiper.min.css', [] );
    wp_enqueue_style( 'jarallax', SEACAB_THEME_ASSETS . 'vendors/jarallax/jarallax.css', [] );
    wp_enqueue_style( 'odometer', SEACAB_THEME_ASSETS . 'vendors/odometer/odometer.min.css', [] );
    wp_enqueue_style( 'conult-icons', SEACAB_THEME_ASSETS . 'vendors/conult-icons/style.css', [] );
    wp_enqueue_style( 'reey-font', SEACAB_THEME_ASSETS . 'vendors/reey-font/stylesheet.css', [] );
    wp_enqueue_style( 'owl-carousel', SEACAB_THEME_ASSETS . 'vendors/owl-carousel/owl.carousel.min.css', [] );
    wp_enqueue_style( 'owl-carousel-default', SEACAB_THEME_ASSETS . 'vendors/owl-carousel/owl.theme.default.min.css', [] );
    wp_enqueue_style( 'bootstrap-select', SEACAB_THEME_ASSETS . 'vendors/bootstrap-select/css/bootstrap-select.min.css', [] );
    wp_enqueue_style( 'nice-select', SEACAB_THEME_ASSETS . 'vendors/nice-select/nice-select.css', [] );
    wp_enqueue_style( 'seacab-core', SEACAB_THEME_ASSETS . 'css/seacab-core.css', [], time() );
    wp_enqueue_style( 'seacab-unit', SEACAB_THEME_ASSETS . 'css/seacab-unit.css', [], time() );
    wp_enqueue_style( 'seacab-responsive', SEACAB_THEME_ASSETS . 'css/seacab-responsive.css', [], time() );
    wp_enqueue_style( 'seacab-custom', SEACAB_THEME_ASSETS . 'css/seacab-custom.css', [] );
    wp_enqueue_style( 'seacab-style', get_stylesheet_uri() );

    // all js
    wp_enqueue_script( 'bootstrap-bundle', SEACAB_THEME_ASSETS . 'vendors/bootstrap/js/bootstrap.bundle.min.js', [ 'jquery' ], '', true );
    wp_enqueue_script( 'appear', SEACAB_THEME_ASSETS . 'vendors/jquery-appear/jquery.appear.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'magnific-popup', SEACAB_THEME_ASSETS . 'vendors/jquery-magnific-popup/jquery.magnific-popup.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'odometer', SEACAB_THEME_ASSETS . 'vendors/odometer/odometer.min.js', [ 'jquery' ], '', true );
    wp_enqueue_script( 'jarallax', SEACAB_THEME_ASSETS . 'vendors/jarallax/jarallax.min.js', [ 'jquery' ], '', true );
    wp_enqueue_script( 'swiper-5', SEACAB_THEME_ASSETS . 'vendors/swiper/swiper.min.js', [ 'jquery' ], '5.4.5', true );
    wp_enqueue_script( 'wow', SEACAB_THEME_ASSETS . 'vendors/wow/wow.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'isotope', SEACAB_THEME_ASSETS . 'vendors/isotope/isotope.js', [ 'imagesloaded' ], false, true );
    wp_enqueue_script( 'countdown', SEACAB_THEME_ASSETS . 'vendors/countdown/countdown.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'owl-carousel', SEACAB_THEME_ASSETS . 'vendors/owl-carousel/owl.carousel.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'bootstrap-select', SEACAB_THEME_ASSETS . 'vendors/bootstrap-select/js/bootstrap-select.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'nice-select', SEACAB_THEME_ASSETS . 'vendors/nice-select/jquery.nice-select.min.js', [ 'jquery' ], false, true );
    wp_enqueue_script( 'seacab-main', SEACAB_THEME_ASSETS . 'js/seacab.js', [ 'jquery' ], time(), true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'seacab_scripts' );

/*
Register Fonts
 */
function seacab_fonts_url() {
    $font_url = '';

    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'seacab' ) ) {
        $font_url = 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Red+Hat+Text:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap';
    }
    return $font_url;
}