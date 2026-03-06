<?php
/**
 * Plugin Name: Geoport WordPress Theme Elements Support
 * Description: Plugin created by Johanspond to support Geoport theme with custom features! 
 * Version:     1.0.0
 * Author:      Johanspond
 * Author URI:  https://www.facebook.com/cool.malek1
 * Plugin URI:  https://www.themepoints.com/
 * Text Domain: geoport
 */
/* This loads the plugin.php file which is the main one */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
require( __DIR__ . '/helper/elements-helper.php' ); 

/**
 * Load Hello World
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function geoport_load_elements() {
	// Load localization file
	load_plugin_textdomain( 'geoport' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Check version required
	$elementor_version_required = '1.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		return;
	}

	// Add a custom category for panel widgets
	add_action( 'elementor/init', function() {
	   \Elementor\Plugin::$instance->elements_manager->add_category( 
	   	'geoport-elements', [
	   		'title' => __( 'Geoport', 'geoport' ),
	   		'icon' => 'fal fa-header', //default icon
	   	],
	   	3 // position
	   );
	} );

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );   //loading the main plugin
}
add_action( 'plugins_loaded', 'geoport_load_elements' );   //notiung but checking and notice