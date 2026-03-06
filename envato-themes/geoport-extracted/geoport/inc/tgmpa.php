<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme geoport for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require get_parent_theme_file_path('inc/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'geoport_register_required_plugins' );


function geoport_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin bundled with a theme.
	 	array(
			'name'      => esc_html__('Geoport Master','geoport'),
			'slug'      => 'geoport-master',
			'source'    => get_template_directory() . '/inc/plugins/geoport-master.zip',
			'version'   => '1.0.0',
			'required'  => true,	
		),
		array(
			'name'      => esc_html__('Revolution Slider','geoport'),
			'slug'      => 'revslider',
			'source'    => get_template_directory() . '/inc/plugins/revslider.zip',
			'version'   => '6.0.5',
			'required'  => true,	
		),
		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		array(
			'name'      => esc_html__('Elementor Page Builder','geoport'),
			'slug'      => 'elementor',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('WooCommerce','geoport'),
			'slug'      => 'woocommerce',
			'required'  => true,
		),	
		array(
			'name'      => esc_html__('Contact Form 7','geoport'),
			'slug'      => 'contact-form-7',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('MailChimp for WordPress','geoport'),
			'slug'      => 'mailchimp-for-wp',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('Classic Widgets','geoport'),
			'slug'      => 'classic-widgets',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('Instagram Feed','geoport'),
			'slug'      => 'instagram-feed',
			'required'  => true,
		),
		array(
			'name'      => esc_html__('One Click Demo Import','geoport'),
			'slug'      => 'one-click-demo-import',
			'required'  => false,
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'geoport',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}