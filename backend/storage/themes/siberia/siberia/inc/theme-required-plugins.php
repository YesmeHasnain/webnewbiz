<?php

add_action( 'tgmpa_register', 'siberia_register_required_plugins' );

function siberia_register_required_plugins() {

	$source = 'https://theme.madsparrow.me/plugins/';

	$plugins = array(

		array(
			'name' => esc_html__( 'Elementor Page Builder', 'siberia' ),
			'slug' => 'elementor',
			'required' => false,
		),

		array(
			'name' => esc_html__( 'Advanced Custom Fields PRO', 'siberia' ),
			'slug' => 'acf_pro',
			'source' => esc_url( $source . 'advanced-custom-fields-pro.zip'),
			'required' => true,
		),

		array(
			'name' => esc_html__( 'Siberia Helper Plugin', 'siberia' ),
			'slug' => 'siberia_plugin',
			'source' => esc_url( $source . 'siberia_plugin.zip'),
			'required' => true,
		),

		array(
			'name' => esc_html__( 'Kirki', 'siberia' ),
			'slug' => 'kirki',
			'required' => true,
		),

		array(
			'name' => esc_html__( 'Contact Form 7', 'siberia' ),
			'slug' => 'contact-form-7',
			'required' => true,
		),

		array(
			'name' => esc_html__( 'MC4WP: Mailchimp for WordPress', 'siberia' ),
			'slug' => 'mailchimp-for-wp',
			'required' => false,
		),

		array(
			'name' => esc_html__( 'One Click Demo Import', 'siberia' ),
			'slug' => 'one-click-demo-import',
			'required' => true,
		),
	);

	$config = array(
		'id'           => 'siberia',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}