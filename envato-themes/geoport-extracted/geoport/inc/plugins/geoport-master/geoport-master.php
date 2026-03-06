<?php 
/*
Plugin Name: Geoport Master
Plugin URI: https://pluginspoint.com/geoport/
Description: After install the Geoport Theme, you must need to install "Geoport Master" plugin first to get all features.
Author: Johanspond
Author URI: https://themeforest.net/user/johanspond
Version: 3.0.3
Text Domain: geoport
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//define
define( 'GEOPORT_VERSION', '3.0.3' );
define( 'GEOPORT_PLG_URL', plugin_dir_url( __FILE__ ) );
define( 'GEOPORT_PLG_DIR', dirname( __FILE__ ) );
define( 'GEOPORT_PLG_DEMO_PATH', dirname( __FILE__ ) . '/demo-importer/' );
define( 'GEOPORT_PLG_DEMO_URL', plugin_dir_url( __FILE__ ) . 'demo-importer/' );


# load plugin textdomain
function geoport_plugin_load_textdomain(){
	load_plugin_textdomain('geoport', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
}
add_action('plugins_loaded', 'geoport_plugin_load_textdomain');


function geoport_plugin_loaded(){   
	# Plugin required file include.
	require_once GEOPORT_PLG_DIR . '/inc/helper.php';
	require_once GEOPORT_PLG_DIR . '/inc/enqueue.php';
	require_once GEOPORT_PLG_DIR . '/inc/post-like.php';
	require_once GEOPORT_PLG_DIR . '/inc/style-functions.php';
	require_once GEOPORT_PLG_DIR . '/inc/custom-style.php';
	require_once GEOPORT_PLG_DIR . '/inc/custom-widgets.php';
	require_once GEOPORT_PLG_DIR . '/inc/custom-posttype.php';
	# Theme option framework.
	require_once GEOPORT_PLG_DIR . '/framework/geoport-framework.php';
}
add_action( 'plugins_loaded','geoport_plugin_loaded' );

/*------------------------------------------------------------------------------------------------------------------*/
/*  Elementor Widget Elements.
/*------------------------------------------------------------------------------------------------------------------*/
require_once GEOPORT_PLG_DIR . '/geoport-elements/geoport-elements.php';

/*------------------------------------------------------------------------------------------------------------------*/
/*  OneClick Demo Import File.
/*------------------------------------------------------------------------------------------------------------------*/
require_once GEOPORT_PLG_DIR . '/demo-importer/import.php';