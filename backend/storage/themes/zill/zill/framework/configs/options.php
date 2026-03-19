<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('zill_setup_customizer')){
    function zill_setup_customizer( $args ){

        $args['prefix']      = 'zill';
        $args['options']    = [
            /** `General` panel */
            'general_settings' => array(
                'title'       => esc_html__( 'General Site settings', 'zill' ),
                'priority'    => 40,
                'type'        => 'panel',
            ),
            /** `Favicon` section */
            'favicon' => array(
                'title'       => esc_html__( 'Favicon', 'zill' ),
                'priority'    => 10,
                'panel'       => 'general_settings',
                'type'        => 'section',
            ),
            /** `Logo` section */
            'logos' => array(
                'title'       => esc_html__( 'Logo', 'zill' ),
                'priority'    => 15,
                'panel'       => 'general_settings',
                'type'        => 'section',
            ),
            'logo_default' => array(
                'title'    => esc_html__( 'Logo', 'zill' ),
                'section'  => 'logos',
                'priority' => 20,
                'field'     => 'image',
                'type'     => 'control',
                'button_labels' => array(
                    'select' => esc_html__( 'Select Logo', 'zill' ),
                    'remove' => esc_html__( 'Remove Logo', 'zill' ),
                    'change' => esc_html__( 'Change Logo', 'zill' ),
                ),
            ),
            'logo_transparency' => array(
                'title'    => esc_html__( 'Logo Transparency', 'zill' ),
                'section'  => 'logos',
                'priority' => 25,
                'field'     => 'file',
                'type'     => 'control',
                'button_labels' => array(
                    'select' => esc_html__( 'Select Logo', 'zill' ),
                    'remove' => esc_html__( 'Remove Logo', 'zill' ),
                    'change' => esc_html__( 'Change Logo', 'zill' ),
                ),
            ),
            /** `Preloader` panel */
            'preloader' => array(
                'title'       => esc_html__( 'Page preloader', 'zill' ),
                'priority'    => 15,
                'panel'       => 'general_settings',
                'type'        => 'section',
            ),
            'page_preloader' => array(
                'title'    => esc_html__( 'Show page preloader', 'zill' ),
                'section'  => 'preloader',
                'priority' => 10,
                'default'  => false,
                'field'     => 'checkbox',
                'type'     => 'control',
            ),
            'page_preloader_type' => array(
                'title'    => esc_html__( 'Page preloader type', 'zill' ),
                'section'  => 'preloader',
                'priority' => 15,
                'field'     => 'select',
                'default'  => '1',
                'type'     => 'control',
                'choices'  => [
                    '1' => esc_html__( 'Type 1', 'zill' ),
                    '2' => esc_html__( 'Type 2', 'zill' ),
                    '3' => esc_html__( 'Type 3', 'zill' ),
                    '4' => esc_html__( 'Type 4', 'zill' ),
                    '5' => esc_html__( 'Type 5', 'zill' ),
                    'custom' => esc_html__( 'Custom', 'zill' ),
                ],
            ),
            'page_preloader_custom' => array(
                'title'    => esc_html__( 'Custom page preloader image', 'zill' ),
                'section'  => 'preloader',
                'priority' => 20,
                'field'     => 'image',
                'type'     => 'control',
            ),
            'page_preloader_bgcolor' => array(
	            'title'   => esc_html__( 'Preloader background color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 20,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => '.la-image-loading',
			            'property' => 'background-color'
		            ]
	            ]
            ),
            'page_preloader_textcolor' => array(
	            'title'   => esc_html__( 'Preloader text color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 20,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => '.la-image-loading',
			            'property' => 'color'
		            ]
	            ]
            ),
            /** `Color Schema` panel */
            'body_bgcolor' => array(
	            'title'   => esc_html__( 'Body Background Color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 20,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-body-bg-color'
		            ]
	            ]
            ),
            'text_color' => array(
	            'title'   => esc_html__( 'Text color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 20,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-body-font-color'
		            ]
	            ]
            ),
            'primary_color' => array(
                'title'   => esc_html__( 'Primary color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 25,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-primary-color'
	                ]
                ]
            ),
            'secondary_color' => array(
                'title'   => esc_html__( 'Secondary color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 30,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-secondary-color'
	                ]
                ]
            ),
            'third_color' => array(
	            'title'   => esc_html__( 'Third color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 35,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-three-color'
		            ]
	            ]
            ),
            'border_color' => array(
	            'title'   => esc_html__( 'Border color', 'zill' ),
	            'section' => 'colors',
	            'field'   => 'hex_color',
	            'type'    => 'control',
	            'priority' => 38,
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-border-color'
		            ]
	            ]
            ),
            'link_color' => array(
                'title'   => esc_html__( 'Link color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 40,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-link-color'
	                ]
                ]
            ),
            'link_hover_color' => array(
                'title'   => esc_html__( 'Link hover color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 45,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-link-hover-color'
	                ]
                ]
            ),
            'h_color' => array(
                'title'   => esc_html__( 'Heading color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 48,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-heading-font-color'
	                ],
	                [
		                'selector' => 'h1, h2, h3, h4, h5, h6, .theme-heading',
		                'property' => 'color'
	                ]
                ]
            ),
            'h1_color' => array(
                'title'   => esc_html__( 'H1 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 50,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h1, h1',
		                'property' => 'color'
	                ]
                ]
            ),
            'h2_color' => array(
                'title'   => esc_html__( 'H2 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 55,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h2, h2',
		                'property' => 'color'
	                ]
                ]
            ),
            'h3_color' => array(
                'title'   => esc_html__( 'H3 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 60,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h3, h3',
		                'property' => 'color'
	                ]
                ]
            ),
            'h4_color' => array(
                'title'   => esc_html__( 'H4 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 65,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h4, h4',
		                'property' => 'color'
	                ]
                ]
            ),
            'h5_color' => array(
                'title'   => esc_html__( 'H5 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 70,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h5, h5',
		                'property' => 'color'
	                ]
                ]
            ),
            'h6_color' => array(
                'title'   => esc_html__( 'H6 color', 'zill' ),
                'section' => 'colors',
                'field'   => 'hex_color',
                'type'    => 'control',
                'priority' => 75,
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => '.h6 h6',
		                'property' => 'color'
	                ]
                ]
            ),
            /** `Typography Settings` panel */
            'typography' => array(
                'title'       => esc_html__( 'Typography', 'zill' ),
                'description' => esc_html__( 'Configure typography settings', 'zill' ),
                'priority'    => 45,
                'type'        => 'panel',
            ),
            /** `Body text` section */
            'body_typography' => array(
                'title'       => esc_html__( 'Body text', 'zill' ),
                'priority'    => 5,
                'panel'       => 'typography',
                'type'        => 'section',
            ),
            'body_font_family' => array(
                'title'   => esc_html__( 'Font Family', 'zill' ),
                'section' => 'body_typography',
                'field'   => 'fonts',
                'type'    => 'control',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-family'
	                ]
                ]
            ),
            'body_font_style' => array(
                'title'   => esc_html__( 'Font Style', 'zill' ),
                'section' => 'body_typography',
                'field'   => 'select',
                'choices' => zill_customizer_get_font_styles(),
                'type'    => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-style'
	                ]
                ]
            ),
            'body_font_weight' => array(
                'title'   => esc_html__( 'Font Weight', 'zill' ),
                'section' => 'body_typography',
                'field'   => 'select',
                'choices' => zill_customizer_get_font_weight(),
                'type'    => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-weight'
	                ]
                ]
            ),
            'body_font_size' => array(
                'title'       => esc_html__( 'Font Size, px', 'zill' ),
                'section'     => 'body_typography',
                'field'       => 'number',
                'unit'       => 'px',
                'input_attrs' => array(
                    'min'  => 6,
                    'max'  => 50,
                    'step' => 1,
                ),
                'type' => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-size'
	                ]
                ]
            ),
            'body_line_height' => array(
                'title'       => esc_html__( 'Line Height', 'zill' ),
                'description' => esc_html__( 'Relative to the font-size of the element', 'zill' ),
                'section'     => 'body_typography',
                'field'       => 'number',
                'input_attrs' => array(
                    'min'  => 1.0,
                    'max'  => 3.0,
                    'step' => 0.1,
                ),
                'type' => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-line-height'
	                ]
                ]
            ),
            'body_letter_spacing' => array(
                'title'       => esc_html__( 'Letter Spacing, px', 'zill' ),
                'section'     => 'body_typography',
                'field'       => 'number',
                'unit'       => 'px',
                'input_attrs' => array(
                    'min'  => -10,
                    'max'  => 10,
                    'step' => 1,
                ),
                'type' => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-spacing'
	                ]
                ]
            ),
            'body_character_set' => array(
                'title'   => esc_html__( 'Character Set', 'zill' ),
                'section' => 'body_typography',
                'default' => 'latin',
                'field'   => 'select',
                'choices' => zill_customizer_get_character_sets(),
                'type'    => 'control',
            ),
            'body_text_align' => array(
                'title'   => esc_html__( 'Text Align', 'zill' ),
                'section' => 'body_typography',
                'field'   => 'select',
                'choices' => zill_customizer_get_text_aligns(),
                'type'    => 'control',
                'transport'=> 'postMessage',
                'css' => [
	                [
		                'selector' => ':root',
		                'property' => '--theme-body-font-align'
	                ]
                ]
            ),

            /** `Heading` section */
            'heading_typography' => array(
	            'title'       => esc_html__( 'Heading Typography', 'zill' ),
	            'priority'    => 10,
	            'panel'       => 'typography',
	            'type'        => 'section',
            ),
            'heading_font_family' => array(
	            'title'   => esc_html__( 'Font Family', 'zill' ),
	            'section' => 'heading_typography',
	            'field'   => 'fonts',
	            'type'    => 'control',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-family'
		            ]
	            ]
            ),
            'heading_font_style' => array(
	            'title'   => esc_html__( 'Font Style', 'zill' ),
	            'section' => 'heading_typography',
	            'field'   => 'select',
	            'choices' => zill_customizer_get_font_styles(),
	            'type'    => 'control',
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-style'
		            ]
	            ]
            ),
            'heading_font_weight' => array(
	            'title'   => esc_html__( 'Font Weight', 'zill' ),
	            'section' => 'heading_typography',
	            'field'   => 'select',
	            'choices' => zill_customizer_get_font_weight(),
	            'type'    => 'control',
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-weight'
		            ]
	            ]
            ),
            'heading_line_height' => array(
	            'title'       => esc_html__( 'Line Height', 'zill' ),
	            'description' => esc_html__( 'Relative to the font-size of the element', 'zill' ),
	            'section'     => 'heading_typography',
	            'field'       => 'number',
	            'input_attrs' => array(
		            'min'  => 1.0,
		            'max'  => 3.0,
		            'step' => 0.1,
	            ),
	            'type' => 'control',
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-line-height'
		            ]
	            ]
            ),
            'heading_letter_spacing' => array(
	            'title'       => esc_html__( 'Letter Spacing, px', 'zill' ),
	            'section'     => 'heading_typography',
	            'field'       => 'number',
                'unit'       => 'px',
	            'input_attrs' => array(
		            'min'  => -10,
		            'max'  => 10,
		            'step' => 1,
	            ),
	            'type' => 'control',
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-spacing'
		            ]
	            ]
            ),
            'heading_character_set' => array(
	            'title'   => esc_html__( 'Character Set', 'zill' ),
	            'section' => 'heading_typography',
	            'default' => 'latin',
	            'field'   => 'select',
	            'choices' => zill_customizer_get_character_sets(),
	            'type'    => 'control',
            ),
            'heading_text_align' => array(
	            'title'   => esc_html__( 'Text Align', 'zill' ),
	            'section' => 'heading_typography',
	            'field'   => 'select',
	            'choices' => zill_customizer_get_text_aligns(),
	            'type'    => 'control',
	            'transport'=> 'postMessage',
	            'css' => [
		            [
			            'selector' => ':root',
			            'property' => '--theme-heading-font-align'
		            ]
	            ]
            ),
        ];

	    if(function_exists('WC')){

		    $woo_default_attr = Zill_WooCommerce_Compare::get_default_attributes();
		    $woo_tax_attr = Zill_WooCommerce_Compare::get_taxonomies();
		    $woo_all_attr = array_merge($woo_default_attr, $woo_tax_attr);

	    	$woo_opts = [
			    /** WooCommerce */
			    'shop_settings' => array(
				    'title'       => esc_html__( 'Shop settings', 'zill' ),
				    'priority'    => 70,
				    'panel'       => 'general_settings',
				    'type'        => 'section',
			    ),
			    'shopdetail_settings' => array(
				    'title'       => esc_html__( 'Product settings', 'zill' ),
				    'priority'    => 75,
				    'panel'       => 'general_settings',
				    'type'        => 'section',
			    ),
			    'compare_wishlist' => array(
				    'title'       => esc_html__( 'Compare & Wishlist', 'zill' ),
				    'priority'    => 80,
				    'panel'       => 'general_settings',
				    'type'        => 'section',
			    ),
			    'shop_cart' => array(
				    'title'       => esc_html__( 'Cart', 'zill' ),
				    'priority'    => 85,
				    'panel'       => 'general_settings',
				    'type'        => 'section',
			    ),
			    'freeshipping_thresholds' => array(
				    'title'         => esc_html__( 'WooCommerce Enable Free Shipping Thresholds', 'zill' ),
				    'description'   => esc_html__( 'Enable Free shipping amount notice', 'zill' ),
				    'section'       => 'shop_cart',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'thresholds_text1' => array(
				    'title'         => esc_html__( 'Shipping bar text 1', 'zill' ),
				    'section'       => 'shop_cart',
				    'default'       => esc_html__('[icon]Spend [amount] to get Free Shipping', 'zill'),
				    'description'       => esc_html__('[icon]Spend [amount] to get Free Shipping', 'zill'),
				    'field'          => 'text',
				    'type'          => 'control',
			    ),
			    'thresholds_text2' => array(
				    'title'         => esc_html__( 'Shipping bar text 2', 'zill' ),
				    'section'       => 'shop_cart',
				    'default'       => esc_html__('[icon]Congratulations! You\'ve got free shipping!', 'zill'),
				    'description'       => esc_html__('[icon]Congratulations! You\'ve got free shipping!', 'zill'),
				    'field'          => 'text',
				    'type'          => 'control',
			    ),
			    'woocommerce_gallery_zoom' => array(
				    'title'         => esc_html__( 'Enable WooCommerce Zoom', 'zill' ),
				    'section'       => 'shopdetail_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'woocommerce_gallery_lightbox' => array(
				    'title'         => esc_html__( 'Enable WooCommerce LightBox', 'zill' ),
				    'section'       => 'shopdetail_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'single_ajax_add_cart' => array(
				    'title'         => esc_html__( 'Ajax Add to Cart', 'zill' ),
				    'description'   => esc_html__( 'Support Ajax Add to cart for all types of products', 'zill' ),
				    'section'       => 'shopdetail_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'wc_per_page_default' => array(
				    'title'         => esc_html__( 'Per Page Default', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => '',
				    'description'   => esc_html__('Enter total products display per page', 'zill'),
				    'field'          => 'text',
				    'type'          => 'control',
			    ),
			    'wc_per_page_allow' => array(
				    'title'         => esc_html__( 'Per Page Allow', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => '',
				    'description'       => esc_html__('Comma-separated. ( i.e: 3,6,9)', 'zill'),
				    'field'          => 'text',
				    'type'          => 'control',
			    ),
			    'wc_per_row_allow' => array(
				    'title'         => esc_html__( 'Per Row Allow', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => '',
				    'description'       => esc_html__('Comma-separated. ( i.e: 2,3,4)', 'zill'),
				    'field'          => 'text',
				    'type'          => 'control',
			    ),
			    'catalog_mode' => array(
				    'title'         => esc_html__( 'Catalog Mode', 'zill' ),
				    'description'   => esc_html__( 'Turn on to disable the shopping functionality of WooCommerce.', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'catalog_mode_price' => array(
				    'title'         => esc_html__( 'Catalog Mode Price', 'zill' ),
				    'description'   => esc_html__( 'Turn on to do not show product price', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'woocommerce_show_wishlist_btn' => array(
				    'title'         => esc_html__( 'Show Wishlist Button', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    'woocommerce_show_compare_btn' => array(
				    'title'         => esc_html__( 'Show Compare Button', 'zill' ),
				    'section'       => 'shop_settings',
				    'default'       => false,
				    'field'          => 'checkbox',
				    'type'          => 'control',
			    ),
			    /* Wishlist Compare */
			    'wishlist_page' => array(
				    'title'   => esc_html__( 'Wishlist Page', 'zill' ),
				    'description'   => esc_html__( 'The content of page must be contain [la_wishlist] shortcode', 'zill' ),
				    'section' => 'compare_wishlist',
				    'field'   => 'dropdown-pages',
				    'type'    => 'control',
			    ),
			    'compare_page' => array(
				    'title'   => esc_html__( 'Compare Page', 'zill' ),
				    'description'   => esc_html__( 'The content of page must be contain [la_compare] shortcode', 'zill' ),
				    'section' => 'compare_wishlist',
				    'field'   => 'dropdown-pages',
				    'type'    => 'control',
			    ),
			    'compare_attribute' => array(
				    'title'   => esc_html__( 'Compare fields to show', 'zill' ),
				    'description'   => esc_html__( 'Select the fields to show in the comparison table', 'zill' ),
				    'section' => 'compare_wishlist',
				    'field'   => 'checkbox-multiple',
				    'choices' => $woo_all_attr,
				    'type'    => 'control',
			    ),
		    ];
		    $args['options'] = array_merge($args['options'], $woo_opts);
	    }

	    $args['options'] = array_merge($args['options'], zill_customizer_heading_typo());

        return $args;
    }
}

add_filter('lastudio-kit/theme/customizer/options', 'zill_setup_customizer');

if(!function_exists('zill_customizer_heading_typo')){
	function zill_customizer_heading_typo(){
		$options = [];
		for ($i = 1; $i <=6; $i++){
			$options['h'.$i.'_typography'] = [
				'title'       => sprintf(__('H%s Heading', 'zill'), $i),
				'priority'    => 10 + ( $i * 5 ),
				'panel'       => 'typography',
				'type'        => 'section',
			];
			$options['h'.$i.'_font_family'] = [
				'title'   => esc_html__( 'Font Family', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'   => 'fonts',
				'type'    => 'control',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-family'
					]
				]
			];
			$options['h'.$i.'_font_style'] = [
				'title'   => esc_html__( 'Font Style', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'   => 'select',
				'choices' => zill_customizer_get_font_styles(),
				'type'    => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-style'
					]
				]
			];
			$options['h'.$i.'_font_weight'] = [
				'title'   => esc_html__( 'Font Weight', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'   => 'select',
				'choices' => zill_customizer_get_font_weight(),
				'type'    => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-weight'
					]
				]
			];
			$options['h'.$i.'_font_size'] = [
				'title'       => esc_html__( 'Font Size, px', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'        => 'lakit_responsive',
				'unit'        => 'px',
				'responsive'  => true,
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-size'
					]
				]
			];
			$options['h'.$i.'_line_height'] = [
				'title'       => esc_html__( 'Line Height', 'zill' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-line-height'
					]
				]
			];
			$options['h'.$i.'_letter_spacing'] = [
				'title'       => esc_html__( 'Letter Spacing, px', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'        => 'lakit_responsive',
				'unit'        => 'px',
				'responsive'  => true,
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-spacing'
					]
				]
			];
			$options['h'.$i.'_character_set'] = [
				'title'   => esc_html__( 'Character Set', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => zill_customizer_get_character_sets(),
				'type'    => 'control',
			];
			$options['h'.$i.'_text_align'] = [
				'title'   => esc_html__( 'Text Align', 'zill' ),
				'section' => 'h'.$i.'_typography',
				'field'   => 'select',
				'choices' => zill_customizer_get_text_aligns(),
				'type'    => 'control',
				'transport'=> 'postMessage',
				'css' => [
					[
						'selector' => ':root',
						'property' => '--theme-h'.$i.'-font-align'
					]
				]
			];
		}
		return $options;
	}
}

/**
 * Move native `site_icon` control (based on WordPress core) into custom section.
 *
 * @since 1.0.0
 * @param  object $wp_customize
 * @return void
 */
if(!function_exists('zill_customizer_change_core_controls')){
    function zill_customizer_change_core_controls( $wp_customize ) {
        $wp_customize->remove_control('display_header_text');
        $wp_customize->remove_control('header_textcolor');
	    $wp_customize->remove_control( 'background_color' );
        $wp_customize->get_control( 'site_icon' )->section      = 'zill_favicon';
        $wp_customize->get_section( 'colors' )->title = esc_html__( 'Color Scheme', 'zill' );
    }
}

// Move native `site_icon` control (based on WordPress core) in custom section.
add_action( 'customize_register', 'zill_customizer_change_core_controls', 20 );


/**
 * Get font styles
 *
 * @since 1.0.0
 * @return array
 */
if(!function_exists('zill_customizer_get_font_styles')){
    function zill_customizer_get_font_styles() {
        return apply_filters( 'zill-theme/font/styles', array(
            'normal'  => esc_html__( 'Normal', 'zill' ),
            'italic'  => esc_html__( 'Italic', 'zill' ),
            'oblique' => esc_html__( 'Oblique', 'zill' ),
            'inherit' => esc_html__( 'Inherit', 'zill' ),
        ) );
    }    
}


/**
 * Get character sets
 *
 * @since 1.0.0
 * @return array
 */
if(!function_exists('zill_customizer_get_character_sets')){
    function zill_customizer_get_character_sets() {
        return apply_filters( 'zill-theme/font/character_sets', array(
            'latin'        => esc_html__( 'Latin', 'zill' ),
            'greek'        => esc_html__( 'Greek', 'zill' ),
            'greek-ext'    => esc_html__( 'Greek Extended', 'zill' ),
            'vietnamese'   => esc_html__( 'Vietnamese', 'zill' ),
            'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'zill' ),
            'latin-ext'    => esc_html__( 'Latin Extended', 'zill' ),
            'cyrillic'     => esc_html__( 'Cyrillic', 'zill' ),
        ) );
    }
}

/**
 * Get text aligns
 *
 * @since 1.0.0
 * @return array
 */
if(!function_exists('zill_customizer_get_text_aligns')){
    function zill_customizer_get_text_aligns() {
        return apply_filters( 'zill-theme/font/text-aligns', array(
            'inherit' => esc_html__( 'Inherit', 'zill' ),
            'center'  => esc_html__( 'Center', 'zill' ),
            'justify' => esc_html__( 'Justify', 'zill' ),
            'left'    => esc_html__( 'Left', 'zill' ),
            'right'   => esc_html__( 'Right', 'zill' ),
        ) );
    }   
}

/**
 * Get font weights
 *
 * @since 1.0.0
 * @return array
 */
if(!function_exists('zill_customizer_get_font_weight')){
    function zill_customizer_get_font_weight() {
        return apply_filters( 'zill-theme/font/weight', array(
            '100' => '100',
            '200' => '200',
            '300' => '300',
            '400' => '400',
            '500' => '500',
            '600' => '600',
            '700' => '700',
            '800' => '800',
            '900' => '900',
        ) );
    }   
}

if(!function_exists('zill_customizer_list_pages')){
	function zill_customizer_list_pages(){
		$pages = get_pages();
		$opts = [
			'' => ''
		];
		foreach ($pages as $page){
			$opts[$page->ID] = $page->post_title;
		}
		return $opts;
	}
}