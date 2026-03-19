<?php

// Create Page Options
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Page Options', 'xcency' ),
	'id'     => 'page_options',
	'icon'   => 'fa fa-file-text-o',
	'fields' => array(
		array(
			'id'      => 'page_default_layout',
			'type'    => 'select',
			'title'   => esc_html__('Page Layout', 'xcency'),
			'options' => array(
				'full-width'  => esc_html__('Full Width', 'xcency'),
				'left-sidebar'  => esc_html__('Left Sidebar', 'xcency'),
				'right-sidebar' => esc_html__('Right Sidebar', 'xcency'),
			),
			'default' => 'full-width',
			'desc'    => esc_html__('Select page layout.', 'xcency'),
		),

		array(
			'id'         => 'page_default_sidebar',
			'type'       => 'select',
			'title'      => esc_html__( 'Sidebar', 'xcency' ),
			'options'    => 'xcency_sidebars',
			'default' => 'sidebar',
			'dependency' => array( 'page_default_layout', '!=', 'full-width' ),
			'desc'       => esc_html__( 'Select default sidebar for all pages. You can override this settings on individual page.', 'xcency' ),
		),
	)
) );