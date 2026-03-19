<?php
//load all theme jquery script
function mancink_theme_scripts() {
		wp_enqueue_script( 'boostrap', get_template_directory_uri() . '/js/bootstrap.min.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'jquery-superfish', get_template_directory_uri() . '/js/superfish.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'jquery-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/js/jquery.sticky.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'imagesloaded'); 	
		wp_enqueue_script( 'jquery-slick-slider', get_template_directory_uri() . '/js/slick.min.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'jquery-slicknav', get_template_directory_uri() . '/js/jquery.slicknav.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'mancink-animation', get_template_directory_uri() . '/js/slick-animation.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'mancink-totop', get_template_directory_uri() . '/js/totop.js',array( 'jquery' ),'', 'in_footer');
		wp_enqueue_script( 'mancink-scripts', get_template_directory_uri() . '/js/script.js',array( 'jquery' ),'', 'in_footer');
}    




