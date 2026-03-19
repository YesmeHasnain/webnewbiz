<?php

// Create banner options
CSF::createSection($xcency_theme_option, array(
	'title'  => esc_html__('Banner Options', 'xcency'),
	'id'     => 'banner_default_options',
	'icon'   => 'fa fa-flag-o',
	'fields' => array(

		array(
			'id'                    => 'banner_default_background',
			'type'                  => 'background',
			'title'                 => esc_html__( 'Banner Background', 'xcency' ),
			'background_gradient'   => true,
			'background_origin'     => false,
			'background_clip'       => false,
			'background_blend-mode' => false,
			'background_attachment' => false,
			'background_size' => false,
			'background_position' => false,
			'background_repeat' => false,
			'output'                => '.banner-area',
			'desc'                  => esc_html__( 'Select banner background color and image. You can change this settings on individual page / post.', 'xcency' ),
		),

		array(
			'id'         => 'banner_title_tag',
			'type'       => 'button_set',
			'title'      => esc_html__( 'Banner Title Tag', 'xcency' ),
			'options'    => array(
				'h1'   => esc_html__( 'H1', 'xcency' ),
				'h2'   => esc_html__( 'H2', 'xcency' ),
				'h3'   => esc_html__( 'H3', 'xcency' ),
				'h4'   => esc_html__( 'H4', 'xcency' ),
				'h5'   => esc_html__( 'H5', 'xcency' ),
				'h6'   => esc_html__( 'H6', 'xcency' ),
				'span'   => esc_html__( 'SPAN', 'xcency' ),
			),
			'default'    => 'h2',
			'desc'       => esc_html__( 'Select banner title tag.', 'xcency' ),
		),

		array(
			'id'         => 'banner_default_text_align',
			'type'       => 'button_set',
			'title'      => esc_html__( 'Banner Text Align', 'xcency' ),
			'options'    => array(
				'start'   => esc_html__( 'Left', 'xcency' ),
				'center' => esc_html__( 'Center', 'xcency' ),
				'end'  => esc_html__( 'Right', 'xcency' ),
			),
			'default'    => 'center',
			'desc'       => esc_html__( 'Select banner text align. You can change this settings on individual page / post.', 'xcency' ),
		),

		array(
			'id'          => 'banner_default_height',
			'type'        => 'slider',
			'title'       => esc_html__('Banner Height', 'xcency'),
			'min'         => 100,
			'max'         => 800,
			'step'        => 1,
			'unit'        => 'px',
			'output'      => '.banner-area,.header-style-three .banner-area,.header-style-four .banner-area',
			'output_mode' => 'height',
			'desc'        => esc_html__('Select banner height. You can change this settings on individual page / post.', 'xcency'),
		),
	)
));