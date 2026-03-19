<?php

Kirki::add_section( 'single_product', array(
    'title'       => esc_html__( 'Product page', 'vasia' ),
    'panel'       => 'woocommerce',
) );

Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'single_product_layout',
	'label'       => esc_html__( 'Select product page layout', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 'simple',
	'choices'     => [
		'simple' => get_template_directory_uri() . '/assets/images/customizer/single-product1.jpg',
		'fulltop' => get_template_directory_uri() . '/assets/images/customizer/single-product2.jpg',
		'fullleft' => get_template_directory_uri() . '/assets/images/customizer/single-product3.jpg',
		'vertical' => get_template_directory_uri() . '/assets/images/customizer/single-product4.jpg',
		'grid' => get_template_directory_uri() . '/assets/images/customizer/single-product5.jpg',
		'showcase' => get_template_directory_uri() . '/assets/images/customizer/single-product6.jpg',
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_image',
	'label'       => esc_html__( 'Image block width', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 'default',
	'choices'     => [
		'smaller' => esc_html__( 'Smaller', 'vasia' ),
		'default' => esc_html__( 'Default', 'vasia' ),
		'larger'  => esc_html__( 'Larger', 'vasia' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'single_product_layout',
			'operator' => '!==',
			'value'    => 'fulltop',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'fulltop_item',
	'label'       => esc_html__( 'Number of images on screen', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 3,
	'choices'     => [
		'min'  => 1,
		'max'  => 6,
		'step' => 1,
	],
	'active_callback'  => [
		[
			'setting'  => 'single_product_layout',
			'operator' => '==',
			'value'    => 'fulltop',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'grid_p_item',
	'label'       => esc_html__( 'Number of column on screen', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 2,
	'choices'     => [
		'min'  => 1,
		'max'  => 2,
		'step' => 1,
	],
	'active_callback'  => [
		[
			'setting'  => 'single_product_layout',
			'operator' => '==',
			'value'    => 'grid',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'single_product_bg',
	'label'       => __( 'Background color', 'vasia' ),
	'section'     => 'single_product',
	'default'     => '#f6f6f6',
	'active_callback'  => [
		[
			'setting'  => 'single_product_layout',
			'operator' => '==',
			'value'    => 'showcase',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'single_product_sizechart',
	'section'     => 'single_product',
	'default'         => '<div class="sub-divider">' . __( 'Size Chart', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_sizechart_title',
	'label'    => esc_html__( 'Size chart title', 'vasia' ),
	'section'  => 'single_product',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'single_product_sizechart_content',
	'label'    => esc_html__( 'Size chart content', 'vasia' ),
	'section'  => 'single_product',
	'description'  => esc_html__( 'Allow using HTML, shortcode', 'vasia' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'single_product_sub',
	'section'     => 'single_product',
	'default'         => '<div class="sub-divider">' . __( 'Options', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'zoom_active',
	'label'       => esc_html__( 'Active zoom', 'vasia' ),
	'section'     => 'single_product',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'single_product_tab',
	'label'       => esc_html__( 'Tab design', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 'default',
	'multiple'    => 1,
	'choices'     => [
		'default' => esc_html__( 'Default', 'vasia' ),
		'horizontal' => esc_html__( 'Horizontal', 'vasia' ),
		'vertical' => esc_html__( 'Vertical', 'vasia' ),
		'accordion' => esc_html__( 'Accordion', 'vasia' ),
	],
] );

Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_tab_title',
	'label'    => esc_html__( 'Additional tab title', 'vasia' ),
	'section'  => 'single_product',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'single_product_tab_content',
	'label'    => esc_html__( 'Additional tab content', 'vasia' ),
	'section'  => 'single_product',
	'description'  => esc_html__( 'Allow using HTML, shortcode', 'vasia' ),
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'custom_single_product_upsell_title',
	'section'     => 'single_product',
	'default'         => '<div class="customize-title-divider">' . __( 'Upsell products block', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'single_product_upsell',
	'label'       => esc_html__( 'Status', 'vasia' ),
	'section'     => 'single_product',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'vasia' ),
		'off' => esc_html__( 'Disable', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_upsell_title',
	'label'    => esc_html__( 'Title', 'vasia' ),
	'section'  => 'single_product',
	'default'  => esc_html__( 'Upsell products', 'vasia' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'single_product_upsell_item',
	'label'       => esc_html__( 'Product on screen', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 4,
	'choices'     => [
		'min'  => 3,
		'max'  => 6,
		'step' => 1,
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'custom_single_product_related_title',
	'section'     => 'single_product',
	'default'         => '<div class="customize-title-divider">' . __( 'Related products block', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'single_product_related',
	'label'       => esc_html__( 'Status', 'vasia' ),
	'section'     => 'single_product',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'vasia' ),
		'off' => esc_html__( 'Disable', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_related_title',
	'label'    => esc_html__( 'Title', 'vasia' ),
	'section'  => 'single_product',
	'default'  => esc_html__( 'Related products', 'vasia' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'single_product_related_item',
	'label'       => esc_html__( 'Product on screen', 'vasia' ),
	'section'     => 'single_product',
	'default'     => 4,
	'choices'     => [
		'min'  => 3,
		'max'  => 6,
		'step' => 1,
	],
] );