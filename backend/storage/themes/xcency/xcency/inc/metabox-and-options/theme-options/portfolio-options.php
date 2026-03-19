<?php
//Portfolio Option
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Portfolio Options', 'xcency' ),
	'id'     => 'portfolio_options',
	'icon'   => 'fa fa-th',
	'fields' => array(
		array(
			'id'      => 'portfolio_default_layout',
			'type'    => 'select',
			'title'   => esc_html__('Portfolio Layout', 'xcency'),
			'options' => array(
				'full-width'  => esc_html__('Full Width', 'xcency'),
				'left-sidebar'  => esc_html__('Left Sidebar', 'xcency'),
				'right-sidebar' => esc_html__('Right Sidebar', 'xcency'),
			),
			'default' => 'full-width',
			'desc'    => esc_html__('Select portfolio layout.', 'xcency'),
		),

		array(
			'id'         => 'portfolio_default_sidebar',
			'type'       => 'select',
			'title'      => esc_html__( 'Sidebar', 'xcency' ),
			'options'    => 'xcency_sidebars',
			'default' => 'portfolio-sidebar',
			'dependency' => array( 'portfolio_default_layout', '!=', 'full-width' ),
			'desc'       => esc_html__( 'Select default sidebar for all portfolios. You can override this settings on individual portfolio.', 'xcency' ),
		),

		array(
			'id'    => 'portfolio_url_slug',
			'type'  => 'text',
			'default' => 'portfolio',
			'title' => esc_html__( 'URL Slug', 'xcency' ),
			'desc'  => esc_html__( 'Change portfolio slug on URL. Don\'t forget to reset permalink after change this.', 'xcency' ),
		),
	)
) );