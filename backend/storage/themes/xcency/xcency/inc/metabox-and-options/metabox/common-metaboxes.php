<?php
$xcency_common_meta = 'xcency_common_meta';

// Create a metabox
CSF::createMetabox($xcency_common_meta, array(
	'title'     => esc_html__('Settings', 'xcency'),
	'post_type' => array('page', 'post', 'xcency_service', 'xcency_team','xcency_portfolio'),
	'data_type' => 'serialize',
));

// Create layout section
CSF::createSection($xcency_common_meta, array(
	'title'  => esc_html__('Layout Settings ', 'xcency'),
	'icon'   => 'fa fa-calculator',
	'fields' => array(

		array(
			'id'      => 'layout_meta',
			'type'    => 'select',
			'title'   => esc_html__('Layout', 'xcency'),
			'options' => array(
				'default'       => esc_html__('Default', 'xcency'),
				'left-sidebar'  => esc_html__('Left Sidebar', 'xcency'),
				'full-width'    => esc_html__('Full Width', 'xcency'),
				'right-sidebar' => esc_html__('Right Sidebar', 'xcency'),
			),
			'default' => 'default',
			'desc'    => esc_html__('Select layout', 'xcency'),
		),

		array(
			'id'         => 'sidebar_meta',
			'type'       => 'select',
			'title'      => esc_html__('Sidebar', 'xcency'),
			'options'    => 'xcency_sidebars',
			'dependency' => array('layout_meta', '!=', 'full-width'),
			'desc'       => esc_html__('Select sidebar you want to show with this page.', 'xcency'),
		),
	)
));

// Create Header section
CSF::createSection( $xcency_common_meta, array(
	'title'  => esc_html__( 'Header Settings ', 'xcency' ),
	'icon'   => 'fa fa-header',
	'fields' => array(

		array(
			'id'      => 'header_style_meta',
			'type'    => 'select',
			'title'         => esc_html__( 'Select Header', 'xcency' ),
			'placeholder'   => esc_html__( 'Default', 'xcency' ),
			'empty_message' => esc_html__( 'No header template found. You can create header template from Xcency Headers > Add New.', 'xcency' ),
			'options'       => 'posts',
			'query_args'    => array(
				'post_type'      => 'xcency_header',
				'posts_per_page' => -1,
			),
			'desc'    => esc_html__('Select header for this page', 'xcency'),
		),
	)
) );

// Create a section
CSF::createSection($xcency_common_meta, array(
	'title'  => esc_html__('Banner Settings', 'xcency'),
	'icon' => 'fa fa-flag-o',
	'fields' => array(
		array(
			'id'       => 'enable_banner',
			'type'     => 'switcher',
			'title'    => esc_html__('Enable Banner', 'xcency'),
			'default'  => true,
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Enable or disable banner.', 'xcency'),
		),

		array(
			'id'                    => 'banner_background_meta',
			'type'                  => 'background',
			'title'                 => esc_html__('Banner Background', 'xcency'),
			'background_gradient'   => true,
			'background_origin'     => false,
			'background_clip'       => false,
			'background_blend-mode' => false,
			'background_attachment' => false,
			'background_size'       => false,
			'background_position'   => false,
			'background_repeat'     => false,
			'dependency'            => array('enable_banner', '==', true),
			'output'                => '.banner-area.post-banner,.banner-area.page-banner,.banner-area.service-banner,.banner-area.team-banner,.banner-area.portfolio-banner',
			'desc'                  => esc_html__('Select banner background color and image', 'xcency'),
		),

		array(
			'id'       => 'hide_tile_meta',
			'type'     => 'switcher',
			'title'    => esc_html__('Hide Title', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show banner title.', 'xcency'),
			'default'  => false,
			'dependency' => array('enable_banner', '==', true),
		),

		array(
			'id'         => 'custom_title',
			'type'       => 'text',
			'title'      => esc_html__('Banner Custom Title', 'xcency'),
			'dependency' => array('enable_banner|hide_tile_meta', '==|==', true|false),
			'desc'       => esc_html__('If you want to use custom title write title here.If you don\'t, leave it empty.', 'xcency')
		),

		array(
			'id'         => 'banner_text_align_meta',
			'type'       => 'select',
			'title'      => esc_html__('Banner Text Align', 'xcency'),
			'options'    => array(
				'default' => esc_html__('Default', 'xcency'),
				'left'    => esc_html__('Left', 'xcency'),
				'center'  => esc_html__('Center', 'xcency'),
				'right'   => esc_html__('Right', 'xcency'),
			),
			'default'    => 'default',
			'dependency' => array('enable_banner', '==', true),
			'desc'       => esc_html__('Select page banner text align.', 'xcency'),
		),

		array(
			'id'          => 'banner_height_meta',
			'type'        => 'slider',
			'title'       => esc_html__('Banner Height', 'xcency'),
			'min'         => 100,
			'max'         => 800,
			'step'        => 1,
			'unit'        => 'px',
			'output'      => '.banner-area.post-banner,.banner-area.page-banner,.banner-area.service-banner,.banner-area.team-banner,.banner-area.portfolio-banner,.header-style-three .banner-area,.header-style-four .banner-area',
			'output_mode' => 'height',
			'subtitle'    => esc_html__('Select banner height.', 'xcency'),
			'desc'        => esc_html__('Select banner height.', 'xcency'),
			'dependency'  => array('enable_banner', '==', true),
		),
	)
));

// Create Footer section
CSF::createSection($xcency_common_meta, array(
	'title'  => esc_html__('Footer Settings ', 'xcency'),
	'icon'   => 'fa fa-wordpress',
	'fields' => array(

		array(
			'id'      => 'footer_style_meta',
			'type'    => 'select',
			'title'         => esc_html__( 'Select Footer', 'xcency' ),
			'placeholder'   => esc_html__( 'Default', 'xcency' ),
			'empty_message' => esc_html__( 'No Footer Template Found. You can create footer template from Xcency Footers > Add New.', 'xcency' ),
			'options'       => 'posts',
			'query_args'    => array(
				'post_type'      => 'xcency_footer',
				'posts_per_page' => -1,
			),
			'desc'    => esc_html__('Select footer for this page', 'xcency'),
		),
	)
));