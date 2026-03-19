<?php
return array(
	'title'      => esc_html__( 'Header Setting', 'earls' ),
	'id'         => 'headers_setting',
	'desc'       => '',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'      => 'header_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Header Source Type', 'earls' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'earls' ),
				'e' => esc_html__( 'Elementor', 'earls' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => 'header_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'earls' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'	=> -1
			],
			'required' => [ 'header_source_type', '=', 'e' ],
		),
		array(
			'id'       => 'header_style_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Settings', 'earls' ),
			'required' => array( 'header_source_type', '=', 'd' ),
		),

		//Header Settings
		array(
		    'id'       => 'header_style_settings',
		    'type'     => 'image_select',
		    'title'    => esc_html__( 'Choose Header Styles', 'earls' ),
		    'subtitle' => esc_html__( 'Choose Header Styles', 'earls' ),
		    'options'  => array(

			    'header_v1'  => array(
				    'alt' => esc_html__( 'Header Style 1', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/header/header_v1.png',
			    ),
			    'header_v2'  => array(
				    'alt' => esc_html__( 'Header Style 2', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/header/header_v2.png',
			    ),
				'header_v3'  => array(
				    'alt' => esc_html__( 'Header Style 3', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/header/header_v3.png',
			    ),
				'header_v4'  => array(
				    'alt' => esc_html__( 'Header Style 4', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/header/header_v4.png',
			    ),
				'header_v5'  => array(
				    'alt' => esc_html__( 'Header Style 5', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/header/header_v5.png',
			    ),
			),
			'required' => array( 'header_source_type', '=', 'd' ),
			'default' => 'header_v1',
	    ),

		/***********************************************************************
								Header Version 1 Start
		************************************************************************/
		array(
			'id'       => 'header_v1_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Style One Settings', 'earls' ),
			'required' => array( 'header_style_settings', '=', 'header_v1' ),
		),
		array(
            'id' => 'show_button_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Booking Button', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v1' ),
        ),
		array(
			'id'      => 'btn_title_v1',
			'type'    => 'text',
			'title'   => __( 'Button Title', 'earls' ),
			'required' => array( 'show_button_v1', '=', true ),
		),
		array(
			'id'      => 'btn_link_v1',
			'type'    => 'text',
			'title'   => __( 'Button Link', 'earls' ),
			'required' => array( 'show_button_v1', '=', true ),
		),
		
		/***********************************************************************
								Header Version 2 Start
		************************************************************************/
		array(
			'id'       => 'header_v2_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Style Two Settings', 'earls' ),
			'required' => array( 'header_style_settings', '=', 'header_v2' ),
		),
		
        /***********************************************************************
								Header Version 3 Start
		************************************************************************/
		array(
			'id'       => 'header_v3_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Style Three Settings', 'earls' ),
			'required' => array( 'header_style_settings', '=', 'header_v3' ),
		),
		//Booking Button Style
		array(
            'id' => 'show_button_v3',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Booking Button', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v3' ),
        ),
		array(
			'id'      => 'btn_title_v3',
			'type'    => 'text',
			'title'   => __( 'Button Title', 'earls' ),
			'required' => array( 'show_button_v3', '=', true ),
		),
		array(
			'id'      => 'btn_link_v3',
			'type'    => 'text',
			'title'   => __( 'Button Link', 'earls' ),
			'required' => array( 'show_button_v3', '=', true ),
		),
		//Phone No Style
		array(
            'id' => 'show_phone_no_v3',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Phone Info', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v3' ),
        ),
		array(
			'id'      => 'phone_title_v3',
			'type'    => 'text',
			'title'   => __( 'Phone Title', 'earls' ),
			'required' => array( 'show_phone_no_v3', '=', true ),
		),
		array(
			'id'      => 'phone_no_v3',
			'type'    => 'text',
			'title'   => __( 'Phone No.', 'earls' ),
			'required' => array( 'show_phone_no_v3', '=', true ),
		),
		
		/***********************************************************************
								Header Version 4 Start
		************************************************************************/
		array(
			'id'       => 'header_v4_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Style Four Settings', 'earls' ),
			'required' => array( 'header_style_settings', '=', 'header_v4' ),
		),
		//Booking Button Style
		array(
            'id' => 'show_button_v4',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Booking Button', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v4' ),
        ),
		array(
			'id'      => 'btn_title_v4',
			'type'    => 'text',
			'title'   => __( 'Button Title', 'earls' ),
			'required' => array( 'show_button_v4', '=', true ),
		),
		array(
			'id'      => 'btn_link_v4',
			'type'    => 'text',
			'title'   => __( 'Button Link', 'earls' ),
			'required' => array( 'show_button_v4', '=', true ),
		),
		//Social Icons
		array(
            'id' => 'show_social_icon_v4',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Social Icons', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v4' ),
        ),
		array(
            'id'    => 'header_social_icon_v4',
            'type'  => 'social_media',
            'title' => esc_html__( 'Social Media', 'earls' ),
            'required' => array( 'show_social_icon_v4', '=', true ),
        ),
		
		/***********************************************************************
								Header Version 5 Start
		************************************************************************/
		array(
			'id'       => 'header_v5_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Header Style Five Settings', 'earls' ),
			'required' => array( 'header_style_settings', '=', 'header_v5' ),
		),
		//Shopping Cart Icon
		array(
            'id' => 'show_shopping_cart_icon_v5',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Shopping Cart Icons', 'earls'),
            'default' => false,
            'required' => array( 'header_style_settings', '=', 'header_v5' ),
        ),
		
		array(
			'id'       => 'header_style_section_end',
			'type'     => 'section',
			'indent'      => false,
			'required' => [ 'header_source_type', '=', 'd' ],
		),
	),
);
