<?php

Kirki::add_section( 'header_promo', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Promo text', 'vasia' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_promo_active',
	'label'       => esc_html__( 'Active promo block', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => 'off',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'vasia' ),
		'off' => esc_html__( 'No', 'vasia' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'header_promo_type',
	'label'       => esc_html__( 'Select type', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => 'text',
	'choices'     => [
		'text'    => esc_html__( 'Text', 'vasia' ),
		'image'   => esc_html__( 'Image', 'vasia' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'image',
	'settings'    => 'header_promo_image',
	'label'       => esc_html__( 'Upload your image', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => '',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'image',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'header_promo_link',
	'label'    => esc_html__( 'Link', 'vasia' ),
	'section'  => 'header_promo',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'image',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_promo_text',
	'label'    => esc_html__( 'Add your text', 'vasia' ),
	'description'    => esc_html__( 'Allow using HTML or shortcode', 'vasia' ),
	'section'  => 'header_promo',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_promo_close',
	'label'       => esc_html__( 'Show close button', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => 'on',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'vasia' ),
		'off' => esc_html__( 'No', 'vasia' ),
	],
	'transport'   => 'postMessage',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_promo_style',
	'section'     => 'header_promo',
	'default'         => '<div class="sub-divider">' . __( 'Promo Style', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_promo_height',
	'label'       => esc_html__( 'Height', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => 40,
	'choices'     => [
		'min'  => 0,
		'max'  => 200,
		'step' => 1,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_promo_color',
	'label'       => esc_html__( 'Color', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => '#ffffff',
	'choices'     => [
		'alpha' => true,
	],
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_promo_bground',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'header_promo',
	'default'     => '#313030',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );