<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
 * Required plugins
 */
if ( ! function_exists( 'ziomm_tgm_plugins' ) ) {
	function ziomm_tgm_plugins() {

		$source = 'https://vlthemes.me/plugins/';

		$plugins = array(
			array(
				'name' => esc_html__( 'Kirki', 'ziomm' ),
				'slug' => 'kirki',
				'required' => true,
			),
			array(
				'name' => esc_html__( 'Ziomm Helper Plugin', 'ziomm' ),
				'slug' => 'ziomm_helper_plugin',
				'source' => esc_url( $source . 'ziomm_helper_plugin.zip' ),
				'required' => true,
				'version' => '1.0.5'
			),
			array(
				'name' => esc_html__( 'Advanced Custom Fields Pro', 'ziomm' ),
				'slug' => 'advanced-custom-fields-pro',
				'source' => esc_url( $source . 'advanced-custom-fields-pro.zip' ),
				'required' => true,
			),
			array(
				'name' => esc_html__( 'Elementor Page Builder', 'ziomm' ),
				'slug' => 'elementor',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'Revolution Slider', 'ziomm' ),
				'slug' => 'revslider',
				'source' => esc_url( $source . 'revslider.zip' ),
				'required' => false,
			),
			array(
				'name' => esc_html__( 'Visual Portfolio', 'ziomm' ),
				'slug' => 'visual-portfolio',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'Contact Form 7', 'ziomm' ),
				'slug' => 'contact-form-7',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'WooCommerce', 'ziomm' ),
				'slug' => 'woocommerce',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'Regenerate Thumbnails', 'ziomm' ),
				'slug' => 'regenerate-thumbnails',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'Classic Widgets', 'ziomm' ),
				'slug' => 'classic-widgets',
				'required' => false,
			),
			array(
				'name' => esc_html__( 'One Click Demo Import', 'ziomm' ),
				'slug' => 'one-click-demo-import',
				'required' => false,
			)
		);

		tgmpa( $plugins );
	}
}
add_action( 'tgmpa_register', 'ziomm_tgm_plugins' );

/**
 * Print notice if helper plugin is not installed
 */
if ( ! function_exists( 'ziomm_helper_plugin_notice' ) ) {
	function ziomm_helper_plugin_notice() {
		if ( class_exists( 'VLThemesHelperPlugin' ) ) {
			return;
		}
		echo '<div class="notice notice-info is-dismissible"><p>' . sprintf( __( 'Please activate <strong>%s</strong> before your work with this theme.', 'ziomm' ), 'Ziomm Helper Plugin' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'ziomm_helper_plugin_notice' );