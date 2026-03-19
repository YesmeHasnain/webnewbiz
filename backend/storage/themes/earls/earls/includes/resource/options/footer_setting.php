<?php

return array(
	'title'      => esc_html__( 'Footer Setting', 'earls' ),
	'id'         => 'footer_setting',
	'desc'       => '',
	'subsection' => false,
	'fields'     => array(
		array(
			'id'      => 'footer_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Footer Source Type', 'earls' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'earls' ),
				'e' => esc_html__( 'Elementor', 'earls' ),
			),
			'default' => 'd',
		),
		array(
			'id'       => 'footer_elementor_template',
			'type'     => 'select',
			'title'    => __( 'Template', 'earls' ),
			'data'     => 'posts',
			'args'     => [
				'post_type' => [ 'elementor_library' ],
				'posts_per_page'	=> -1
			],
			'required' => [ 'footer_source_type', '=', 'e' ],
		),
		array(
			'id'       => 'footer_style_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Settings', 'earls' ),
			'required' => array( 'footer_source_type', '=', 'd' ),
		),
		array(
		    'id'       => 'footer_style_settings',
		    'type'     => 'image_select',
		    'title'    => esc_html__( 'Choose Footer Styles', 'earls' ),
		    'subtitle' => esc_html__( 'Choose Footer Styles', 'earls' ),
		    'options'  => array(

			    'footer_v1'  => array(
				    'alt' => esc_html__( 'Footer Style 1', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer_v1.png',
			    ),
				'footer_v2'  => array(
				    'alt' => esc_html__( 'Footer Style 2', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer_v2.png',
			    ),
				'footer_v3'  => array(
				    'alt' => esc_html__( 'Footer Style 3', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer_v3.png',
			    ),
				'footer_v4'  => array(
				    'alt' => esc_html__( 'Footer Style 4', 'earls' ),
				    'img' => get_template_directory_uri() . '/assets/images/redux/footer/footer_v4.png',
			    ),
			),
			'required' => array( 'footer_source_type', '=', 'd' ),
			'default' => 'footer_v1',
	    ),
		
		
		/***********************************************************************
								Footer Version 1 Start
		************************************************************************/
		array(
			'id'       => 'footer_v1_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Style One Settings', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
		),
		array(
            'id' => 'show_top_footer_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Topbar Footer', 'earls'),
            'default' => false,
            'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
        ),
		//Footer Address
		array(
            'id' => 'show_footer_address_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Footer Address', 'earls'),
            'default' => false,
            'required' => array( 'show_top_footer_v1', '=', true ),
        ),
		array(
			'id'      => 'footer_address_title_v1',
			'type'    => 'text',
			'title'   => __( 'Address Title', 'earls' ),
			'required' => array( 'show_footer_address_v1', '=', true ),
		),
		array(
			'id'      => 'footer_address_v1',
			'type'    => 'textarea',
			'title'   => __( 'Address', 'earls' ),
			'required' => array( 'show_footer_address_v1', '=', true ),
		),
		//Footer Contact Info
		array(
            'id' => 'show_footer_contact_info_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Contact Info', 'earls'),
            'default' => false,
            'required' => array( 'show_top_footer_v1', '=', true ),
        ),
		array(
			'id'      => 'footer_info_title_v1',
			'type'    => 'text',
			'title'   => __( 'Contact Title', 'earls' ),
			'required' => array( 'show_footer_contact_info_v1', '=', true ),
		),
		array(
			'id'      => 'footer_phone_no_v1',
			'type'    => 'textarea',
			'title'   => __( 'Phone No.', 'earls' ),
			'required' => array( 'show_footer_contact_info_v1', '=', true ),
		),
		array(
			'id'      => 'footer_working_time_v1',
			'type'    => 'textarea',
			'title'   => __( 'Working Time', 'earls' ),
			'required' => array( 'show_footer_contact_info_v1', '=', true ),
		),
		//Logo
		array(
			'id'       => 'footer_logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Footer Logo Image', 'earls' ),
			'subtitle' => esc_html__( 'Insert Footer logo image', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
		),
		//Menu
		array(
            'id' => 'show_footer_menu_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Footer Menu', 'earls'),
            'default' => false,
            'required' => array( 'footer_style_settings', '=', 'footer_v1' ),
        ),
		
		/***********************************************************************
								Footer Version 2 Start
		************************************************************************/
		array(
			'id'       => 'footer_v2_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Style Two Settings', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		//BG Image
		array(
			'id'       => 'footer_bg_image_v2',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Footer BG Image', 'earls' ),
			'subtitle' => esc_html__( 'Insert Footer BG image', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v2' ),
		),
		
		/***********************************************************************
								Footer Version 3 Start
		************************************************************************/
		array(
			'id'       => 'footer_v3_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Style Three Settings', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v3' ),
		),
		//BG Image
		array(
			'id'       => 'footer_bg_image_v3',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Footer BG Image', 'earls' ),
			'subtitle' => esc_html__( 'Insert Footer BG image', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v3' ),
		),
		//Coypright
		array(
			'id'      => 'copyright_text3',
			'type'    => 'textarea',
			'title'   => __( 'Copyright Text', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v3' ),
		),
		
		
		/***********************************************************************
								Footer Version 4 Start
		************************************************************************/
		array(
			'id'       => 'footer_v4_settings_section_start',
			'type'     => 'section',
			'indent'      => true,
			'title'    => esc_html__( 'Footer Style Four Settings', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
		),
		array(
            'id' => 'show_footer_pattern_v4',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable BG Pattern Image', 'earls'),
            'default' => false,
            'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
        ),
		//Logo
		array(
			'id'       => 'footer_logo_v4',
			'type'     => 'media',
			'url'      => true,
			'title'    => esc_html__( 'Footer Logo Image', 'earls' ),
			'subtitle' => esc_html__( 'Insert Footer logo image', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
		),
		//Social Media
		array(
            'id' => 'show_footer_social_icon_v4',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Social Icons', 'earls'),
            'default' => false,
            'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
        ),
		array(
			'id'      => 'footer_header_social_icon_v4',
			'type'    => 'social_media',
			'title'   => __( 'Social Media', 'earls' ),
			'required' => array( 'show_footer_social_icon_v4', '=', true ),
		),
		
		//Footer Contact Info
		array(
            'id' => 'show_footer_info_v4',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Contact Info', 'earls'),
            'default' => false,
            'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
        ),
		array(
			'id'      => 'footer_address_v4',
			'type'    => 'text',
			'title'   => __( 'Address', 'earls' ),
			'required' => array( 'show_footer_info_v4', '=', true ),
		),
		array(
			'id'      => 'footer_phone_no_v4',
			'type'    => 'textarea',
			'title'   => __( 'Phone No.', 'earls' ),
			'required' => array( 'show_footer_info_v4', '=', true ),
		),
		array(
			'id'      => 'footer_working_days_v4',
			'type'    => 'textarea',
			'title'   => __( 'Working Days', 'earls' ),
			'required' => array( 'show_footer_info_v4', '=', true ),
		),
		
		//Coypright
		array(
			'id'      => 'copyright_text4',
			'type'    => 'textarea',
			'title'   => __( 'Copyright Text', 'earls' ),
			'required' => array( 'footer_style_settings', '=', 'footer_v4' ),
		),
		
		array(
			'id'       => 'footer_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'footer_source_type', '=', 'd' ],
		),
	),
);
