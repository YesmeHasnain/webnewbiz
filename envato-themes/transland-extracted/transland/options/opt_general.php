<?php
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Preloader Settings', 'transland' ),
    'id'               => 'preloader_opt',
    'icon'             => 'dashicons dashicons-sos',
    'fields'           => array(

        array(
            'id'      => 'is_preloader',
            'type'    => 'switch',
            'title'   => esc_html__( 'Pre-loader', 'transland' ),
            'on'      => esc_html__('Enable', 'transland'),
            'off'     => esc_html__('Disable', 'transland'),
            'default'   => '0',
        ),

        array(
            'title'     => esc_html__('Pre-Loader Title Text', 'transland'),
            'desc'  => esc_html__('change preloader title with your own.', 'transland'),
            'id'        => 'preloader_title',
            'type'      => 'text',
            'default'  => get_bloginfo('name'),
            'required' => array('is_preloader', '=', '1'),
        ),

        array(
            'required' => array('is_preloader', '=', '1'),
            'id'       => 'loading_text',
            'type'     => 'text',
            'title'    => esc_html__( 'Loading Text', 'transland' ),
            'default'  => esc_html__('Loading', 'transland'),
        ),

        array(
            'title'     => esc_html__('Preloader Title Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice solid color for preloader title (Big Heading) color.', 'transland' ),
            'id'        => 'preloader_title_color',
            'type'      => 'color',
            'output'      => array(
                'color' => '.preloader .animation-preloader .txt-loading .letters-loading, .preloader .animation-preloader .txt-loading .letters-loading::before',
            ),
            'required' => array('is_preloader', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Preloader Loading Text Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice color for preloader loading text (p) color.', 'transland' ),
            'id'        => 'preloader_text_color',
            'type'      => 'color',
            'output'      => array(
                'color' => '.preloader .animation-preloader p',
            ),
            'required' => array('is_preloader', '=', '1'),
        ),

        array(
            'title'     => esc_html__('Preloader Spinner (moving) Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice your solid color for border top Spinner (moving) color.', 'transland' ),
            'id'        => 'preloader_spinner_color',
            'type'      => 'color',
            'output'      => array(
                'border-top-color' => '.preloader .animation-preloader .spinner',
            ),
            'required' => array('is_preloader', '=', '1'),
        ),

        array(
            'required' => array('is_preloader', '=', '1'),
            'title'     => esc_html__('Preloader Background', 'transland'),
            'id'        => 'preloader_bg',
            'type'      => 'background',
        ),

    )
));