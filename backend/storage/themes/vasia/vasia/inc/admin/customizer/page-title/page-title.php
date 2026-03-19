<?php

Kirki::add_section( 'page_title', array(
    'priority'    => 52,
    'title'       => esc_html__( 'Page title', 'vasia' ),
) );

Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'page_title_design',
	'label'       => esc_html__( 'Page title design', 'vasia' ),
	'section'     => 'page_title',
	'default'     => '1',
	'choices'     => [
		'1'   => get_template_directory_uri() . '/assets/images/customizer/page-title-1.jpg',
		'2' => get_template_directory_uri() . '/assets/images/customizer/page-title-2.jpg',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'page_title_align',
	'label'       => esc_html__( 'Align', 'vasia' ),
	'section'     => 'page_title',
	'default'     => 'center',
	'choices'     => [
		'left'   => esc_html__( 'Left', 'vasia' ),
		'center' => esc_html__( 'Center', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'page_title_size',
	'label'       => esc_html__( 'Size', 'vasia' ),
	'section'     => 'page_title',
	'default'     => 'medium',
	'choices'     => [
		'small'   => esc_html__( 'Small', 'vasia' ),
		'medium' => esc_html__( 'Medium', 'vasia' ),
		'large'  => esc_html__( 'Large', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'page_title_color',
	'label'       => esc_html__( 'Text color', 'vasia' ),
	'section'     => 'page_title',
	'default'     => 'dark',
	'choices'     => [
		'dark'   => get_template_directory_uri() . '/assets/images/customizer/text-dark.svg',
		'light' => get_template_directory_uri() . '/assets/images/customizer/text-light.svg',
	],
	'active_callback'  => [
		[
			'setting'  => 'page_title_design',
			'operator' => '===',
			'value'    => '1',
		],
	]
] );

Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'page_title_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'page_title',
	'default'     => [
		'background-color'      => 'rgba(20,20,20,.8)',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => '.page-title-section',
		],
	],
	'active_callback'  => [
		[
			'setting'  => 'page_title_design',
			'operator' => '===',
			'value'    => '1',
		],
	]
] );

Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'page_title_padding',
	'label'       => esc_html__( 'Padding top & bottom (px)', 'vasia' ),
	'section'     => 'page_title',
	'default'     => 180,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'active_callback' => [
		[
			'setting'  => 'page_title_design',
			'operator' => '==',
			'value'    => '1',
		]
	],
	'transport'   => 'postMessage',
] );
