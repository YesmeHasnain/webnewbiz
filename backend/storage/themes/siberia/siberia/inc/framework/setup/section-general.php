<?php

/**
 * General
 */
$priority = 0;

Kirki::add_field( 'siberia_customize', array(
	'type'        => 'switch',
	'settings'    => 'page_transition',
	'label'       => esc_html__( 'Page transition', 'siberia' ),
	'section'     => 'section_general_settings',
	'default'     => '1',
	'priority'    => 10,
	'transport' => 'auto',
    'choices'     => array(
        'on'  => esc_html__( 'On', 'siberia' ),
        'off' => esc_html__( 'Off', 'siberia' ),
    ),
) );

Kirki::add_field( 'siberia_customize', array(
	'type'        => 'switch',
	'settings'    => 'top_btn',
	'label'       => esc_html__( 'Back To Top Button', 'siberia' ),
	'section'     => 'section_general_settings',
	'default'     => '1',
	'priority'    => 10,
	'transport' => 'auto',
    'choices'     => array(
        'on'  => esc_html__( 'On', 'siberia' ),
        'off' => esc_html__( 'Off', 'siberia' ),
    ),
) );

Kirki::add_field( 'siberia_customize', array(
	'type'        => 'switch',
	'settings'    => 'mode_switcher',
	'label'       => esc_html__( 'Theme Mode Switcher', 'siberia' ),
	'section'     => 'section_general_settings',
	'default'     => '1',
	'priority'    => 10,
	'transport' => 'auto',
    'choices'     => array(
        'on'  => esc_html__( 'On', 'siberia' ),
        'off' => esc_html__( 'Off', 'siberia' ),
    ),
) );