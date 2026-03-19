<?php

$sep_id  = 6477;
$section = 'page_header';

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'page_header_style',
    'label'       => esc_html__( 'Page Header Style', 'bazien' ),
    'section'     => $section,
    'default'     => 'large',
    'priority'    => 10,
    'choices'     => array(
        'mini'  => esc_html__( 'Mini', 'bazien' ),
        'large'  => esc_html__( 'Large', 'bazien' ),
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
    'type'        => 'image',
    'settings'    => 'page_header_background_image',
    'label'       => esc_html__( 'Background Image', 'bazien' ),
    'section'     => $section,
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'pager_header_overlay_color',
    'label'       => esc_html__( 'Page Header Background Overlay Color', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'pager_header_background_color',
    'label'       => esc_html__( 'Page Header Background Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#F6F6F6',
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'pager_header_font_color',
    'label'       => esc_html__( 'Page Header Font Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#616161',
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'color',
    'settings'    => 'pager_header_heading_color',
    'label'       => esc_html__( 'Page Header Heading Color', 'bazien' ),
    'section'     => $section,
    'default'     => '#000',
    'priority'    => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'page_header_style',
            'operator' => '==',
            'value'    => 'large',
        ),
    ),

) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'page_header_breadcrumb_toggle',
    'label'       => esc_html__( 'Site Breadcrumb', 'bazien' ),
    'section'     => $section,
    'default'     => 1,
    'priority'    => 10,
    'choices'     => array(
        'on'  => esc_html__( 'On', 'bazien' ),
        'off' => esc_html__( 'Off', 'bazien' ),
    ),

) );

// ---------------------------------------------
