<?php 

Kirki::add_section( 'layout', array(
    'title'       => esc_html__( 'Layout', 'vasia' ),
    'panel'       => 'general'
) );

Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'layout_mode',
	'label'       => esc_html__( 'Layout mode', 'vasia' ),
	'section'     => 'layout',
	'default'     => 'fullwidth',
	'choices'     => [
		'fullwidth'   => esc_html__( 'Full width', 'vasia' ),
		'boxed' => esc_html__( 'Boxed', 'vasia' ),
	],
	'transport'   => 'postMessage',
] );

Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'site_width',
	'label'    => esc_html__( 'Site Content Width (px,%,rem,em..)', 'vasia' ),
	'section'  => 'layout',
	'description'  => esc_html__( 'Set the default width of content containers.', 'vasia' ),
	'transport'   => 'postMessage',
	'default'   => '1470px',
] );

Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'layout_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'layout',
	'default'     => [
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => 'body',
		],
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'layout_boxed',
	'section'     => 'layout',
	'default'         => '<div class="customize-title-divider">' . __( 'Boxed layout', 'vasia' ) . '</div>',
	'active_callback' => [
		[
			'setting'  => 'layout_mode',
			'operator' => '==',
			'value'    => 'boxed',
		]
	],
] );

Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'boxed_width',
	'label'    => esc_html__( 'Boxed Width (px,%,rem,em..)', 'vasia' ),
	'section'  => 'layout',
	'description'  => esc_html__( 'Use for boxed layout mode', 'vasia' ),
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'layout_mode',
			'operator' => '==',
			'value'    => 'boxed',
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'layout_boxed_background',
	'label'       => esc_html__( 'Boxed Background', 'vasia' ),
	'section'     => 'layout',
	'default'     => [
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => '#page',
		],
	],
	'active_callback' => [
		[
			'setting'  => 'layout_mode',
			'operator' => '==',
			'value'    => 'boxed',
		]
	],
] );