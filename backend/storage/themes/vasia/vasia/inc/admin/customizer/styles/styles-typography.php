<?php 

Kirki::add_section( 'typography', array(
    'title'       => esc_html__( 'Typography', 'vasia' ),
    'panel'       => 'styles',
) );

Kirki::add_field( 'option', [
	'type'        => 'typography',
	'settings'    => 'primary_font',
	'label'       => esc_html__( 'Primary font', 'vasia' ),
	'section'     => 'typography',
	'default'     => [
		'font-family'    => 'Jost',
		'font-size'      => '1.6rem',
		'line-height'    => '1.5',
		'color'          => '#707070',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => 'body',
		],
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'secondary_font_active',
	'label'       => esc_html__( 'Active secondary font', 'vasia' ),
	'section'     => 'typography',
	'default'     => '1',
] );

Kirki::add_field( 'option', [
	'type'        => 'typography',
	'settings'    => 'secondary_font',
	'label'       => esc_html__( 'Secondary font', 'vasia' ),
	'section'     => 'typography',
	'default'     => [
		'font-family'    => 'Jost',
		'variant'        => '400',
		'line-height'    => '1.5',
		'text-transform' => 'none',
		'color'          => '#313030',
	],
	'active_callback' => [
		[
			'setting'  => 'secondary_font_active',
			'operator' => '==',
			'value'    => '1',
		]
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'multicheck',
	'settings'    => 'secondary_font_use',
	'label'       => esc_html__( 'Use sencondary font for: ', 'vasia' ),
	'section'     => 'typography',
	'default'     => array('title'),
	'choices'     => [
		'title'   => esc_html__( 'Heading title', 'vasia' ),
		'testimonial' => esc_html__( 'Testimonial', 'vasia' ),
	],
] );