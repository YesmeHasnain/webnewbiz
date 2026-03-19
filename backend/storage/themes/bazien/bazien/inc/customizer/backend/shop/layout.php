<?php

$sep_id  = 45785;
$section = 'shop';

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'shop_layout_width',
    'label'       => esc_html__( 'Layout Width', 'bazien' ),
    'section'     => $section,
    'default'     => 'boxed',
    'priority'    => 10,
    'choices'     => array(
        'wide'  => esc_html__( 'Wide', 'bazien' ),
        'boxed'  => esc_html__( 'Boxed', 'bazien' ),
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
    'settings'    => 'shop_sidebar',
    'label'       => esc_html__( 'Shop Sidebar', 'bazien' ),
    'section'     => $section,
    'default'     => true,
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'shop_sidebar',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'shop_sidebar_position',
    'label'       => esc_html__( 'Sidebar Position', 'bazien' ),
    'section'     => $section,
    'default'     => 'left',
    'priority'    => 10,
    'choices'     => array(
        'left'    => esc_html__( 'Left', 'bazien' ),
        'right'   => esc_html__( 'Right', 'bazien' ),
    ),
    'active_callback'    => array(
        array(
            'setting'  => 'shop_sidebar',
            'operator' => '==',
            'value'    => true,
        ),
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
    'type'        => 'radio-buttonset',
    'settings'    => 'shop_pagination',
    'label'       => esc_html__( 'Pagination', 'bazien' ),
    'section'     => $section,
    'default'     => 'infinite_scroll',
    'priority'    => 10,
    'choices'     => array(
        'default'           => esc_html__( 'Classic', 'bazien' ),
        'load_more_button'  => esc_html__( 'Load More', 'bazien' ),
        'infinite_scroll'   => esc_html__( 'Infinite', 'bazien' ),
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
    'settings'    => 'shop_second_image',
    'label'       => esc_html__( 'Product Image on Hover', 'bazien' ),
    'section'     => $section,
    'default'     => false,
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
    'type'        => 'slider',
    'settings'    => 'shop_mobile_columns',
    'label'       => esc_html__( 'Number of Columns on Mobile', 'bazien' ),
    'section'     => $section,
    'default'     => 2,
    'priority'    => 10,
    'choices'     => array(
        'min'  => 1,
        'max'  => 2,
        'step' => 1
    ),
) );
