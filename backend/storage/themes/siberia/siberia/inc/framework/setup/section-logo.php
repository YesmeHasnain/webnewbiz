<?php

Kirki::add_field( 'siberia_customize', array(
    'type' => 'dimension',
    'settings' => 'logo_height',
    'section' => 'logo_settings',
    'label' => esc_html__( 'Max Height of Logo Image', 'siberia' ),
    'description' => esc_html__( 'Select the height of the logo. For proportions, the width is set automatically', 'siberia' ),
    'priority' => $priority++,
    'transport' => 'auto',
    'default' => '18px',
    'output' => array(
        array(
            'element' => '.main-header__logo a, .main-header__logo svg, .main-header__logo img',
            'property' => 'height' 
        ) 
    ) 
) );

Kirki::add_field('siberia_customize', array(
    'section' => 'logo_settings',
    'type' => 'image',
    'settings' => 'logo_light',
    'label' => esc_html__('Image Logo Light', 'siberia'),
    'description' => esc_html__( 'Choose a light logo image to display for header', 'siberia' ),
    'priority' => $priority++
));

Kirki::add_field('siberia_customize', array(
    'section' => 'logo_settings',
    'type' => 'image',
    'settings' => 'logo_dark',
    'label' => esc_html__('Image Logo Dark', 'siberia'),
    'description' => esc_html__( 'Choose a dark logo image to display for header', 'siberia' ),
    'priority' => $priority++
));