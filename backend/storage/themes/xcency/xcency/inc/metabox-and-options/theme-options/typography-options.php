<?php
// Create typography section
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Typography', 'xcency' ),
	'id'     => 'typography_options',
	'icon'   => 'fa fa-text-width',
	'fields' => array(

		array(
			'id'             => 'body_typo',
			'type'           => 'typography',
			'title'          => esc_html__( 'Body', 'xcency' ),
			'desc'           => esc_html__( 'Select body typography.', 'xcency' ),
			'output'         => 'body',
			'text_align'     => false,
			'text_transform' => false,
			'color'          => false,
			'extra_styles'   => true,
			'default'        => array(
				'font-family'  => 'Space Grotesk',
				'type'         => 'google',
				'unit'         => 'px',
				'font-weight'  => '400',
				'extra-styles' => array('500'),
			),
		),

		array(
			'id'             => 'heading_typo',
			'type'           => 'typography',
			'title'          => esc_html__( 'Heading Font', 'xcency' ),
			'desc'           => esc_html__( 'Select heading typography.', 'xcency' ),
			'output'         => 'h1,h2,h3,h4,h5,h6',
			'text_align'     => false,
			'text_transform' => false,
			'color'          => false,
			'extra_styles'   => true,
			'default'        => array(
				'font-family'  => 'Space Grotesk',
				'type'         => 'google',
				'unit'         => 'px',
				'font-weight'  => '700',
				'extra-styles' => array('600'),
			),
		),
	)
) );