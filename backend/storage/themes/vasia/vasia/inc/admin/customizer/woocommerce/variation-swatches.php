<?php

//Get all attributes
$atts = array();
$attribute_taxonomies = wc_get_attribute_taxonomies();
foreach($attribute_taxonomies as $attribute){
	$atts[$attribute->attribute_name] = $attribute->attribute_label;
}

Kirki::add_section( 'variant_swatches', array(
    'title'       => esc_html__( 'Variant swatches', 'vasia' ),
    'panel'       => 'woocommerce',
) );


Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_general_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'General', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'swatches_main_attr',
	'label'       => esc_html__( 'Main attribute', 'vasia' ),
	'description' => esc_html__( 'Attribute show in product catalog', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => '',
	'placeholder' => esc_html__( 'Select an attribute...', 'vasia' ),
	'multiple'    => 1,
	'choices'     => $atts,
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'swatches_attr_image',
	'label'       => esc_html__( 'Replace main attribute by image', 'vasia' ),
	'description' => esc_html__( 'Use variant image instead of attribute. Only available when main attribute type is color/texture', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_shop_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'Catalog product', 'vasia' ) . '</div>',
] );


Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'swatches_attr_active',
	'label'       => esc_html__( 'Active image swatches', 'vasia' ),
	'description' => esc_html__( 'Change image for each product variantion', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'swatches_attr_action',
	'label'       => esc_html__( 'Action on attribute', 'vasia' ),
	'description' => esc_html__( 'Action on attribute for image swatches change', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => 'hover',
	'choices'     => [
		'hover'   => esc_html__( 'Hover', 'vasia' ),
		'click' => esc_html__( 'Click', 'vasia' ),
	],
	'active_callback' => [
		[
			'setting'  => 'swatches_attr_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_single_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'Product page', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_color_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="sub-divider">' . __( 'Color or texture type', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'swatches_color_design',
	'label'       => esc_html__( 'Color/image attribute display', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => 'circle',
	'choices'     => [
		'circle'   => esc_html__( 'Circle', 'vasia' ),
		'square' => esc_html__( 'Square', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'swatches_color_size',
	'label'       => esc_html__( 'Color/image attribute size', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => 20,
	'choices'     => [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	],
	'transport'   => 'postMessage',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_label_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="sub-divider">' . __( 'Label type', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'swatches_label_design',
	'label'       => esc_html__( 'Label attribute display', 'vasia' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
	'choices'     => [
		'1' => get_template_directory_uri() . '/assets/images/customizer/swatches-label1.jpg',
		'2' => get_template_directory_uri() . '/assets/images/customizer/swatches-label2.jpg',
	],
] );
