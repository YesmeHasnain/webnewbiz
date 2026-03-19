<?php
function startli_scripts() {
	//register styles
	global $startli_option;
	wp_enqueue_style( 'boostrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css' );	
	wp_enqueue_style( 'rt-icons', get_template_directory_uri() .'/assets/css/rt-icons.css');	
    wp_enqueue_style( 'magnific-popup', get_template_directory_uri() .'/assets/css/magnific-popup.css');
	wp_enqueue_style( 'swiper', get_template_directory_uri().'/assets/css/swiper-bundle.min.css' );
	wp_enqueue_style( 'startli-style-default', get_template_directory_uri() .'/assets/scss/theme.css' );
	wp_enqueue_style( 'startli-style-responsive', get_template_directory_uri() .'/assets/css/responsive.css' );
	if ( is_rtl() ) {
		wp_enqueue_style(  'startli-rtl',  get_template_directory_uri().'/assets/scss/rtl.css' );		
	}
	wp_enqueue_style( 'startli-style', get_stylesheet_uri() );		
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-2.8.3.min.js', array('jquery'), '2.8.3', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '5.2.0', true );
	wp_enqueue_script( 'swiper', get_template_directory_uri().'/assets/js/swiper-bundle.min.js', array('jquery'), '8.2.3');
	wp_enqueue_script( 'wow', get_template_directory_uri().'/assets/js/wow.min.js', array('jquery'), '1.1.2');
	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/assets/js/waypoints.min.js', array('jquery'), '2.0.3', true );
	wp_enqueue_script( 'waypoints-sticky', get_template_directory_uri() . '/assets/js/waypoints-sticky.min.js', array('jquery'), '1.6.2', true );	
	wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/assets/js/jquery.counterup.min.js', array('jquery'), '1.0', true );
	wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true );	
	wp_enqueue_script( 'isotope-startli', get_template_directory_uri() . '/assets/js/isotope-startli.js', array('jquery', 'imagesloaded'), '20151215', true );	
	wp_enqueue_script('startli-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), wp_get_theme()->get( 'Version' ), true);	
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'startli_scripts' );

add_action( 'admin_enqueue_scripts', 'startli_load_admin_styles' );
function startli_load_admin_styles($screen) {
	wp_enqueue_style( 'startli-admin-style', get_template_directory_uri() . '/assets/css/admin-style.css', true, '1.0.0' );
	wp_enqueue_script( 'startli-admin-script', get_template_directory_uri() . '/assets/js/admin-script.js', array('jquery'), '1.0.0', true );
} 