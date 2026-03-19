<?php

Kirki::add_section( 'header_logo', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Logo', 'vasia' ),
    'panel'       => 'header',	
) );
	
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_logo_maxwidth',
	'label'       => esc_html__( 'Max width (px)', 'vasia' ),
	'section'     => 'header_logo',
	'default'     => 100,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'transport'   => 'postMessage',
] );