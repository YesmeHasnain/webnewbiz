<?php
//Service Option
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Service Options', 'xcency' ),
	'id'     => 'service_options',
	'icon'   => 'fa fa-th',
	'fields' => array(
		array(
			'id'      => 'service_default_layout',
			'type'    => 'select',
			'title'   => esc_html__('Service Layout', 'xcency'),
			'options' => array(
				'full-width'  => esc_html__('Full Width', 'xcency'),
				'left-sidebar'  => esc_html__('Left Sidebar', 'xcency'),
				'right-sidebar' => esc_html__('Right Sidebar', 'xcency'),
			),
			'default' => 'full-width',
			'desc'    => esc_html__('Select service layout.', 'xcency'),
		),

		array(
			'id'         => 'service_default_sidebar',
			'type'       => 'select',
			'title'      => esc_html__( 'Sidebar', 'xcency' ),
			'options'    => 'xcency_sidebars',
			'default' => 'service-sidebar',
			'dependency' => array( 'service_default_layout', '!=', 'full-width' ),
			'desc'       => esc_html__( 'Select default sidebar for all services. You can override this settings on individual service.', 'xcency' ),
		),

		array(
			'id'    => 'service_url_slug',
			'type'  => 'text',
			'default' => 'service',
			'title' => esc_html__( 'URL Slug', 'xcency' ),
			'desc'  => esc_html__( 'Change service slug on URL. Don\'t forget to reset permalink after change this.', 'xcency' ),
		),

	)
) );