<?php

// Footer settings
Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Footer', 'transland'),
	'id'        => 'transland_footer',
	'icon'      => 'dashicons dashicons-table-row-before',
));


// ScrollUp settings
Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Scroll Up', 'transland'),
	'id'        => 'transland_scrollup',
	'icon'      => 'el el-arrow-up',
	'subsection'=> true,
	'fields'    => array(   

        array(
            'title'     => esc_html__('Scroll Up Icon Color', 'transland'),
            'id'        => 'scroll_icon_color',
            'type'      => 'color',
            'output'    => '.scroll-up',
        ),

        array(
            'title'     => esc_html__('Scroll Up Background Color', 'transland'),
            'id'        => 'scroll_bg_color',
            'type'      => 'color',
        ),
        
        array(
            'title'     => esc_html__('Scroll Up Hover Icon Color', 'transland'),
            'id'        => 'scroll_hover_icon_color',
            'type'      => 'color',
            'output'    => '.scroll-up:hover',
        ),

        array(
            'title'     => esc_html__('Scroll Up Hover Background Color', 'transland'),
            'id'        => 'scroll_hover_bg_color',
            'type'      => 'color',
        ),

	)
));

// Footer settings
Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Footer Top Settings', 'transland'),
	'id'        => 'transland_footer_widgets_opt',
	'icon'      => 'dashicons dashicons-editor-kitchensink',
	'subsection'=> true,
	'fields'    => array(

        array(
            'title'     => esc_html__( 'Footer Column', 'transland' ),
            'id'        => 'footer_column',
            'type'      => 'select',
            'default'   => '3',
            'options'   => array(
                '6' => esc_html__( 'Two Column', 'transland' ),
                '4' => esc_html__( 'Three Column', 'transland' ),
                '3' => esc_html__( 'Four Column', 'transland' ),
            )
        ),

        array(
            'id'     => 'divider_three',
            'type'   => 'divide',
        ),

        array(
            'title'     => esc_html__('Widget Title Color', 'transland'),
            'id'        => 'widget_title_color',
            'type'      => 'color',
        ),

        array(
            'title'     => esc_html__('Footer Text Color', 'transland'),
            'id'        => 'footer_text_color',
            'type'      => 'color',
            'output'    => 'footer .single-footer-wid ul li a, .widget .textwidget p, footer span, footer p',
        ),

        array(
            'id'     => 'divider_six',
            'type'   => 'divide',
        ),

        array(
            'title'     => esc_html__('Footer Background Color', 'transland'),
            'id'        => 'footer_bg_color',
            'type'      => 'color',
        ),
        
        array(
            'title'    => esc_html__('Footer Background Image', 'transland'),
            'id'       => 'footer_bg_img',
            'type'     => 'media',
            'compiler' => true,
        ),

	)
));

// Footer settings
Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Footer Bottom', 'transland'),
	'id'        => 'transland_footer_style_opt',
	'icon'      => 'dashicons dashicons-editor-kitchensink',
	'subsection'=> true,
	'fields'    => array(

        array(
			'title'     => esc_html__('Footer Copyright', 'transland'),
			'desc'      => esc_html__('write down your own copyright info.', 'transland'),
			'id'        => 'footer_copyright_content',
			'type'      => 'editor',
			'default'   => '<p>&copy; <b>Transland</b> - 2022. All rights reserved.</p>'
		),

        array(
            'title'     => esc_html__('Footer Text Color', 'transland'),
            'id'        => 'footer_text_color',
            'type'      => 'color',
            'output'    => 'footer .footer-bottom p',
        ),

        array(
            'title'     => esc_html__('Footer Link Color', 'transland'),
            'id'        => 'footer_link_color',
            'type'      => 'color',
            'output'    => 'footer .footer-bottom a',
        ),

        array(
            'title'     => esc_html__('Footer Bottom Bar Background', 'transland'),
            'id'        => 'footer_bottom_bg_color',
            'type'      => 'color',
        ),

	)
));
