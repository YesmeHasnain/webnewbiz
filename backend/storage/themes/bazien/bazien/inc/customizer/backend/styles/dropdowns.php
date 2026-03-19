<?php

$sep_id  = 200;
$section = 'style_dropdowns';

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'dropdowns_bg_color',
    'label'       => esc_html__( 'Dropdowns Background Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#fff',
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'dropdowns_font_color',
    'label'       => esc_html__( 'Dropdowns Text Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#000',
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'dropdowns_accent_color',
    'label'       => esc_html__( 'Dropdowns Accent Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#BC9064',
    'priority'    => 10,
) );
