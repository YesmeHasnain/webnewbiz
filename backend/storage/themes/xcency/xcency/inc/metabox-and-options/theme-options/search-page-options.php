<?php
//Search Options

CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Search Page', 'xcency' ),
	'id'     => 'search_page_options',
	'icon'   => 'fa fa-search',
	'fields' => array(

		array(
			'id'      => 'search_layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Search Layout', 'xcency' ),
			'options' => array(
				'grid'          => esc_html__( 'Grid Full', 'xcency' ),
				'grid-ls'       => esc_html__( 'Grid With Left Sidebar', 'xcency' ),
				'grid-rs'       => esc_html__( 'Grid With Right Sidebar', 'xcency' ),
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'xcency' ),
				'right-sidebar' => esc_html__( 'Right Sidebar', 'xcency' ),
			),
			'default' => 'right-sidebar',
			'desc'    => esc_html__( 'Select search page layout.', 'xcency' ),
		),

		array(
			'id'       => 'search_banner',
			'type'     => 'switcher',
			'title'    => esc_html__( 'Enable Search Banner', 'xcency' ),
			'default'  => true,
			'text_on'  => esc_html__( 'Yes', 'xcency' ),
			'text_off' => esc_html__( 'No', 'xcency' ),
			'desc'     => esc_html__( 'Enable or disable search page banner.', 'xcency' ),
		),

		array(
			'id'                    => 'search_banner_background_options',
			'type'                  => 'background',
			'title'                 => esc_html__( 'Banner Background', 'xcency' ),
			'background_gradient'   => true,
			'background_origin'     => false,
			'background_clip'       => false,
			'background_blend-mode' => false,
			'background_attachment' => false,
			'background_size'       => false,
			'background_position'   => false,
			'background_repeat'     => false,
			'dependency'            => array( 'search_banner', '==', true ),
			'output'                => '.banner-area.search-banner',
			'desc'                  => esc_html__( 'If you want different banner background settings for search page then select search page banner background options from here.', 'xcency' ),
		),

		array(
			'id'    => 'search_placeholder',
			'type'  => 'text',
			'title' => esc_html__( 'Search Field Placeholder', 'xcency' ),
			'desc'  => esc_html__( 'Type search placeholder here.', 'xcency' ),
		),
	)
) );