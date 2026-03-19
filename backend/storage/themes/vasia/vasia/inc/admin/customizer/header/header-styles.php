<?php


Kirki::add_section( 'header_styles', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Header Styles', 'vasia' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_styles_general',
	'section'     => 'header_styles',
	'default'         => '<div class="customize-title-divider">' . __( 'General', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_sticky_active',
	'label'       => esc_html__( 'Active header sticky', 'vasia' ),
	'section'     => 'header_styles',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'vasia' ),
		'off' => esc_html__( 'No', 'vasia' ),
	],
] );


Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_styles_main',
	'section'     => 'header_styles',
	'default'         => '<div class="customize-title-divider">' . __( 'Header main', 'vasia' ) . '</div>',
] );


Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'header_main_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'header_styles',
	'default'     => [
		'background-color'      => '#ffffff',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_main_text_color',
	'label'       => esc_html__( 'Text color', 'vasia' ),
	'section'     => 'header_styles',
	'default'     => '#313030',
	'choices'     => [
		'alpha' => true,
	],
	
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_main_padding',
	'label'       => esc_html__( 'Padding top & bottom (px)', 'vasia' ),
	'section'     => 'header_styles',
	'default'     => '0',
	'choices'     => [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	],
	'transport'   => 'postMessage',
] );


