<?php

// Create general section
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'General Options', 'xcency' ),
	'id'     => 'general_options',
	'icon'   => 'fa fa-google',
	'fields' => array(
		array(
			'id'       => 'theme_primary_color',
			'type'     => 'color',
			'title'    => esc_html__( 'Primary Color', 'xcency' ),
			'desc'     => esc_html__( 'Select theme primary color. Few colors not change from here. You can change them from individual Elementor widget.', 'xcency' ),
		),

		array(
			'id'       => 'enable_preloader',
			'type'     => 'switcher',
			'title'    => esc_html__( 'Enable Pre Loader', 'xcency' ),
			'text_on'  => esc_html__( 'Yes', 'xcency' ),
			'text_off' => esc_html__( 'No', 'xcency' ),
			'desc'     => esc_html__( 'Enable or disable Site Preloader.', 'xcency' ),
			'default'  => true
		),

		array(
			'id'         => 'preloader_big_text',
			'type'       => 'text',
			'title'      => esc_html__( 'Preloader Big Text', 'xcency' ),
			'desc'     => esc_html__( 'Type Preloader big text here.', 'xcency' ),
			'default'      => esc_html__( 'Xcency', 'xcency' ),
			'dependency'   => array( 'enable_preloader', '==', 'true' ),
		),

		array(
			'id'         => 'preloader_small_text',
			'type'       => 'text',
			'title'      => esc_html__( 'Preloader Small Text', 'xcency' ),
			'default'      => esc_html__( 'Loading...', 'xcency' ),
			'desc'     => esc_html__( 'Type Preloader small text here.', 'xcency' ),
			'dependency'   => array( 'enable_preloader', '==', 'true' ),
		),

		array(
			'id'          => 'preloader_background_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Preloader Background Color', 'xcency' ),
			'desc'        => esc_html__( 'Select preloader background color.', 'xcency' ),
			'dependency'  => array( 'enable_preloader', '==', true ),
			'output'      => '.text-preloader-wrapper',
			'output_mode' => 'background-color'
		),
	)
) );