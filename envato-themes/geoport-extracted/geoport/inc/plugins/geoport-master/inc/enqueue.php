<?php
/*------------------------------------------------------------------------------------------------------------------*/
/*  Conditional Register Script
/*------------------------------------------------------------------------------------------------------------------*/

function geoport_master_scripts() {
	wp_enqueue_style( 'geoport-framework', GEOPORT_PLG_URL . 'assets/css/geoport-framework.css' , array(), '' );
	wp_enqueue_style( 'geoport-admin-style', GEOPORT_PLG_URL . 'assets/css/geoport-admin-style.css' , array(), '' );
	wp_enqueue_style( 'font-awesome', GEOPORT_PLG_URL . 'assets/css/fontawesome-all.min.css' , array(), '' );
	
	wp_enqueue_script( 'geoport-plugins', GEOPORT_PLG_URL . 'assets/js/geoport-plugins.js', array('jquery'), '', true );
	wp_enqueue_script( 'geoport-framework', GEOPORT_PLG_URL . 'assets/js/geoport-framework.js', array('jquery'), '', true );
	wp_enqueue_script( 'geoport-master-backend', GEOPORT_PLG_URL . 'assets/js/geoport-master-backend.js', array('jquery'), '', true );
}
add_action( 'admin_enqueue_scripts', 'geoport_master_scripts' );

function geoport_master_forntend_scripts() {
 	wp_enqueue_style( 'fontawesome-all', GEOPORT_PLG_URL . 'assets/css/fontawesome-all.min.css' , array(), '' );
 	wp_enqueue_style( 'geoport-frontend', GEOPORT_PLG_URL . 'assets/css/geoport-frontend.css' , array(), '' );
 	wp_enqueue_style( 'geoport-master', GEOPORT_PLG_URL . 'assets/css/geoport-master.css' , array(), '' );

 	wp_enqueue_script( 'countdown', GEOPORT_PLG_URL . 'assets/js/jquery.countdown.min.js', array('jquery'), '', true );
 	wp_enqueue_script( 'range-slider', GEOPORT_PLG_URL . 'assets/js/range-slider.js', array('jquery'), '', true );
 	wp_enqueue_script( 'geoport-master', GEOPORT_PLG_URL . 'assets/js/geoport-master.js', array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'geoport_master_forntend_scripts', 20 );