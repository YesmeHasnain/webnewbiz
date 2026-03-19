<?php
return array(
	'title'      => esc_html__( 'Logo Setting', 'earls' ),
	'id'         => 'logo_setting',
	'desc'       => '',
	'subsection' => false,
	'fields'     => array(
		//Favicon Style
		array(
			'id'       => 'image_favicon',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Favicon', 'earls' ),
			'subtitle' => esc_html__( 'Insert site favicon image', 'earls' ),
			'default'  => array( 'url' => get_template_directory_uri() . '/assets/images/favicon.png' ),
		),
		//Logo Style
		array(
            'id' => 'normal_logo_show',
            'type' => 'switch',
            'title' => esc_html__('Enable Logo', 'earls'),
            'default' => true,
        ),
		array(
			'id'       => 'main_color_logo',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Logo Image', 'earls' ),
			'subtitle' => esc_html__( 'Insert site logo image', 'earls' ),
			'required' => array( 'normal_logo_show', '=', true ),
		),
		array(
			'id'       => 'main_color_logo_dimension',
			'type'     => 'dimensions',
			'title'    => esc_html__( 'Logo Dimentions', 'earls' ),
			'subtitle' => esc_html__( 'Select Logo Dimentions', 'earls' ),
			'units'    => array( 'em', 'px', '%' ),
			'default'  => array( 'Width' => '', 'Height' => '' ),
			'required' => array( 'normal_logo_show', '=', true ),
		),
		//End Logo Settings
		array(
			'id'       => 'logo_settings_section_end',
			'type'     => 'section',
			'indent'      => false,
		),
	),
);
