<?php

$sep_id  = 300;
$section = 'header_elements';

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'icons_on_topbar',
    'label'       => esc_html__( 'Show icons on Topbar', 'bazien' ),
    'section'     => $section,
    'default'     => true,
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
    'type'        => 'toggle',
    'settings'    => 'header_burger_menu',
    'label'       => esc_html__( 'Burger Menu', 'bazien' ),
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
    'type'        => 'toggle',
    'settings'    => 'header_user_account',
    'label'       => esc_html__( 'Account', 'bazien' ),
    'section'     => $section,
    'default'     => true,
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
    'type'        => 'toggle',
    'settings'    => 'header_wishlist',
    'label'       => esc_html__( 'Wishlist', 'bazien' ),
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
    'type'        => 'toggle',
    'settings'    => 'header_cart',
    'label'       => esc_html__( 'Cart', 'bazien' ),
    'section'     => $section,
    'default'     => true,
    'priority'    => 10,

) );
