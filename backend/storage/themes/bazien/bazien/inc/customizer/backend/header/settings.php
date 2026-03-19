<?php

$sep_id  = 8475;
$section = 'header_settings';

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'header_wide',
  'label'       => esc_html__( 'Header Wide', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,

) );
Kirki::add_field( 'bazien', array(
    'type'        => 'slider',
    'settings'    => 'header_height',
    'label'       => esc_html__( 'Header Height (px)', 'bazien' ),
    'section'     => $section,
    'default'     => 100,
    'priority'    => 10,
    'choices'     => array(
        'min'  => 80,
        'max'  => 300,
        'step' => 1
    ),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'header_transparent',
  'label'       => esc_html__( 'Header Transparent', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'slider',
    'settings'    => 'header_font_size',
    'label'       => esc_html__( 'Header Text Size', 'bazien' ),
    'section'     => $section,
    'default'     => 16,
    'priority'    => 10,
    'choices'     => array(
        'min'  => 9,
        'max'  => 24,
        'step' => 1
    ),

) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,

) );
// ---------------------------------------------


Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'header_search_toggle',
    'label'       => esc_html__( 'Header Search', 'bazien' ),
    'section'     => $section,
    'default'     => 1,
    'priority'    => 10,
    'choices'     => array(
        'on'  => esc_html__( 'On', 'bazien' ),
        'off' => esc_html__( 'Off', 'bazien' ),
    ),

) );

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'header_search_style',
    'label'       => esc_html__( 'Search Style', 'bazien' ),
    'section'     => $section,
    'default'     => 'fullscreen',
    'priority'    => 10,
    'choices'     => array(
        'default'  => esc_html__( 'Default', 'bazien' ),
        'fullscreen'  => esc_html__( 'Fullscreen', 'bazien' ),
    ),
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'checkbox',
    'settings'    => 'header_search_by_category',
    'label'       => esc_html__( 'Search by Category', 'bazien' ),
    'section'     => $section,
    'default'     => '0',
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'header_search_toggle',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
