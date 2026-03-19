<?php

$sep_id  = 5677;
$section = 'shop_catalog_mode';

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'catalog_mode',
    'label'       => esc_html__( 'Catalog Mode', 'bazien' ),
    'section'     => $section,
    'default'     => false,
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback' => array(
        array(
            'setting'  => 'catalog_mode',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'catalog_mode_button',
    'label'       => esc_html__( 'Remove Add to Cart Button', 'bazien' ),
    'section'     => $section,
    'default'     => true,
    'priority'    => 10,
    'active_callback' => array(
        array(
            'setting'  => 'catalog_mode',
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
    'active_callback' => array(
        array(
            'setting'  => 'catalog_mode',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'catalog_mode_price',
    'label'       => esc_html__( 'Remove Product Price', 'bazien' ),
    'section'     => $section,
    'default'     => false,
    'priority'    => 10,
    'active_callback' => array(
        array(
            'setting'  => 'catalog_mode',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
