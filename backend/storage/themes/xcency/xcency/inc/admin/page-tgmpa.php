<?php

function xcency_install_required_plugins() {

	$plugins = array(

		array(
			'name'     => esc_html__('Breadcrumb NavXT', 'xcency'),
			'slug'     => 'breadcrumb-navxt',
			'version'  => '7.3.0',
			'required' => false,
		),

		array(
			'name'     => esc_html__('Contact Form 7', 'xcency'),
			'slug'     => 'contact-form-7',
			'version'  => '5.9.4',
			'required' => false
		),

		array(
			'name'     => esc_html__('Elementor Page Builder', 'xcency'),
			'slug'     => 'elementor',
			'version'  => '3.21.6',
			'required' => true,
		),

		array(
			'name'     => esc_html__('Mailchimp for WordPress', 'xcency'),
			'slug'     => 'mailchimp-for-wp',
			'version'  => '4.9.13',
			'required' => false,
		),

		array(
			'name'     => esc_html__('One Click Demo Import', 'xcency'),
			'slug'     => 'one-click-demo-import',
			'version'  => '3.2.1',
			'required' => false,
		),


		array(
			'name'     => esc_html__('Xcency Core', 'xcency'),
			'slug'     => 'xcency-core',
			'source'   => get_template_directory(). '/inc/plugins/xcency-core.zip',
			'version'  => '1.0.0',
			'required' => true
		),
	);

	$config = array(
		'id'           => 'xcency',
		'parent_slug'  => 'xcency',
		'menu'         => 'xcency-plugins',
		'has_notices'  => true,
		'dismissable'  => true,
		'is_automatic' => false,
		'dismiss_msg'  => '',
		'message'      => '',
		'default_path' => '',
	);

	tgmpa($plugins, $config);
}

add_action('tgmpa_register', 'xcency_install_required_plugins');