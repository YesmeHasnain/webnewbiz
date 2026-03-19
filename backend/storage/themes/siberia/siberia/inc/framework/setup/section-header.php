<?php

Kirki::add_field( 'siberia_customize', array(
    'type' => 'dimension',
    'settings' => 'header_height',
    'section' => 'header_settings',
    'label' => esc_html__( 'Header Height', 'siberia' ),
    'description' => esc_html__( 'Select the height of the logo (default: 80px). For proportions, the width is set automatically', 'siberia' ),
    'priority' => $priority++,
    'transport' => 'auto',
    'default' => '80px',
    'output'    => [
        [
            'element'  => ':root',
            'property' => '--main-header-height-md',
        ],
    ],
    'transport' => 'auto',
) );

Kirki::add_field( 'theme_config_id', [
    'type'        => 'radio-buttonset',
    'section'     => 'header_settings',
    'settings'    => 'type_header',
    'label' => esc_html__( 'Header Style', 'siberia' ),
    'default'     => 'default',
    'choices'     => [
        'default'   => esc_html__( 'Default', 'siberia' ),
        'fixed' => esc_html__( 'Fixed', 'siberia' ),
        'sticky'  => esc_html__( 'Sticky', 'siberia' ),
    ],
    'priority' => $priority++,
] );

Kirki::add_field( 'siberia_customize', [
    'type'        => 'checkbox',
    'section'     => 'header_settings',
    'settings'    => 'blur_hedaer',
    'label'       => esc_html__( 'Header Blur Effect', 'siberia' ),
    'default'     => true,
    'priority' => $priority++,
] );