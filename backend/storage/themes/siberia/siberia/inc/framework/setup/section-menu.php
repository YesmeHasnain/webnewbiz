<?php
Kirki::add_field( 'theme_config_id', [
    'type'        => 'radio-buttonset',
    'section'     => 'menu_settings',
    'settings'    => 'type_menu',
    'label'       => esc_html__( 'Menu Variation', 'siberia' ),
    'default'     => 'default',
    'choices'     => [
        'default'   => esc_html__( 'Default', 'siberia' ),
        'button' => esc_html__( 'Button', 'siberia' ),
    ],
    'priority' => $priority++,
] );

Kirki::add_field('siberia_customize', array(
    'section' => 'menu_settings',
    'type' => 'background',
    'settings' => 'menu_bg',
	'label'       => esc_html__( 'Background Control', 'siberia' ),
	'description' => esc_html__( 'Background conrols are pretty complex - but extremely useful if properly used.', 'siberia' ),
	'default'     => [
		'background-color'      => '#202124',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => '.ms-fs-menu',
		],
	],
    'required'  => array( 
        array( 
            'setting'   => 'type_menu',
            'operator'  => '==',
            'value'     => 'button'
        )
    ),
    'priority' => $priority++
));