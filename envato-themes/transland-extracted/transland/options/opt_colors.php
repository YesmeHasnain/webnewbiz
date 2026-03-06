<?php
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Brand Colors Settings', 'transland' ),
    'id'               => 'brand_colors_opt',
    'icon'             => 'dashicons dashicons-art',
    'fields'           => array(

        array(
            'id'      => 'is_brand_color',
            'type'    => 'switch',
            'title'   => esc_html__( 'Change Brand Colors?', 'transland' ),
            'on'      => esc_html__('Enable', 'transland'),
            'off'     => esc_html__('Disable', 'transland'),
            'default'   => '0',
        ),

        array(
            'title'     => esc_html__('Theme Color - Primary', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for primary color.', 'transland' ),
            'id'        => 'transland_theme_color',
            'type'      => 'color',
            'default'      => '#1f425d',
            'required' => array('is_brand_color', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Second Theme Color - Secondary', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for Secondary color.', 'transland' ),
            'id'        => 'transland_second_theme_color',
            'type'      => 'color',
            'default'      => '#4ab9cf',
            'required' => array('is_brand_color', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Third Theme Color - Secondary 2', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for Secondary Two color.', 'transland' ),
            'id'        => 'transland_third_theme_color',
            'type'      => 'color',
            'default'      => '#fd7062',
            'required' => array('is_brand_color', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Text Color - Body Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for Body Text, p, span colors.', 'transland' ),
            'id'        => 'transland_body_color',
            'type'      => 'color',
            'required' => array('is_brand_color', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Heading Color - H1,H2,H3,H4,H5,H6 Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for Heading Tags color.', 'transland' ),
            'id'        => 'transland_heading_color',
            'type'      => 'color',
            'required' => array('is_brand_color', '=', '1'),
        ),

    )
));