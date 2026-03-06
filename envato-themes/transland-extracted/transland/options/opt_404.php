<?php

Redux::setSection('transland_opt', array(
    'title'     => esc_html__('404 Page Settings', 'transland'),
    'id'        => '404_0pt',
    'icon'      => 'dashicons dashicons-megaphone',
    'fields'    => array(

        array(
            'title'    => esc_html__('404 Banner Image', 'transland'),
            'id'       => 'error_img_banner',
            'type'     => 'media',
            'compiler' => true,
            'default'  => array(
                'url'  => TRANSLAND_DIR_IMG.'/opt/404.png'
            ),
        ),

        array(
            'title'     => esc_html__('Heading', 'transland'),
            'id'        => 'error_title',
            'type'      => 'text',
            'default'   => esc_html__("Page Not Found", 'transland'),
        ),

        array(
            'title'     => esc_html__('Sub Heading', 'transland'),
            'id'        => 'error_subtitle',
            'type'      => 'textarea',
            'default'   => esc_html__('Sorry! The page you are looking doesn’t exist or broken. Go to Homepage from the below button', 'transland'),
        ),

        array(
            'title'     => esc_html__('Button Text', 'transland'),
            'id'        => 'error_btn_label',
            'type'      => 'text',
            'default'   => esc_html__('Back To Home', 'transland'),
        ),

        array(
            'id'          => 'btn_font_color',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Text Color', 'transland' ),
            'output'      => array(
                'color' => '.content-not-found .theme-btn',
            ),
        ),

        array(
            'id'          => 'btn_bg_color',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Background Color', 'transland' ),
            'output'      => array(
                'background' => '.content-not-found .theme-btn',
            ),
        ),

        array(
            'id'          => 'btn_border_color',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Border Color', 'transland' ),
            'output'      => array(
                'border-color' => '.content-not-found .theme-btn',
            ),
        ),

        array(
            'id'          => 'btn_font_color_hover',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Text Hover Color', 'transland' ),
            'output'      => array(
                'color' => '.content-not-found .theme-btn:hover',
            ),
        ),

        array(
            'id'          => 'btn_bg_hover',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Background Hover Color', 'transland' ),
            'output'      => array(
                'background' => '.content-not-found .theme-btn:hover',
            ),
        ),

        array(
            'id'          => 'btn_border_hover',
            'type'        => 'color',
            'title'       => esc_html__( 'Button Border Hover Color', 'transland' ),
            'output'      => array(
                'border-color' => '.content-not-found .theme-btn:hover',
            ),
        ),
    )
));
