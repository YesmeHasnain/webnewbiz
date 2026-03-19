<?php

return array(
	'title'         => esc_html__( 'Header Sidebar Settings', 'earls' ),
    'id'            => 'header_sidebar_setting',
    'desc'          => '',
    'icon'          => 'el el-globe',
    'fields'        => array(
        array(
            'id' => 'show_sidebar_setting',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Sidebar', 'earls'),
            'default' => false,
        ),
		array(
			'id'      => 'sidebar_title_v1',
			'type'    => 'text',
			'title'   => __( 'Title', 'earls' ),
			'required' => array( 'show_sidebar_setting', '=', true ),
		),
		array(
			'id'      => 'sidebar_text_v1',
			'type'    => 'textarea',
			'title'   => __( 'Description', 'earls' ),
			'required' => array( 'show_sidebar_setting', '=', true ),
		),
		//Quote Form
		array(
            'id' => 'show_quote_form_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Quote Form', 'earls'),
            'default' => false,
			'required' => array( 'show_sidebar_setting', '=', true ),
        ),
		array(
			'id'      => 'sidebar_form_title_v1',
			'type'    => 'text',
			'title'   => __( 'Quote Form Title', 'earls' ),
			'required' => array( 'show_quote_form_v1', '=', true ),
		),
		array(
			'id'      => 'sidebar_form_url_v1',
			'type'    => 'textarea',
			'title'   => __( 'Quote Form Url', 'earls' ),
			'required' => array( 'show_quote_form_v1', '=', true ),
		),
		//Social Media
		array(
            'id' => 'show_sidebar_social_icon_v1',
            'type' => 'switch',
            'title' => esc_html__('Enable/Disable Social Icons', 'earls'),
            'default' => false,
            'required' => array( 'show_sidebar_setting', '=', true ),
        ),
		array(
			'id'      => 'sidebar_social_title_v1',
			'type'    => 'text',
			'title'   => __( 'Heading/Title', 'earls' ),
			'required' => array( 'show_sidebar_social_icon_v1', '=', true ),
		),
		array(
			'id'      => 'sidebar_header_social_icon_v1',
			'type'    => 'social_media',
			'title'   => __( 'Social Media', 'earls' ),
			'required' => array( 'show_sidebar_social_icon_v1', '=', true ),
		),
    ),
);

