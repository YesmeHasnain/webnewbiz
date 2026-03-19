<?php

Kirki::add_section( 'catalog_mode', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Catalog mode', 'vasia' ),
    'panel'       => 'woocommerce',
) );

Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'catalog_mode_active',
	'label'       => esc_html__( 'Actice catalog mode', 'vasia' ),
	'description' => esc_html__( 'Catalog mode disables the shopping cart on your store. Visitors will be able to browse your products catalog, but can not buy them.', 'vasia' ),
	'section'     => 'catalog_mode',
	'default'     => '0',
] );
Kirki::add_field( 'option', [
	'type'        => 'checkbox',
	'settings'    => 'catalog_mode_price',
	'label'       => esc_html__( 'Show price in catalog mode', 'vasia' ),
	'section'     => 'catalog_mode',
	'default'     => false,
	'active_callback' => [
		[
			'setting'  => 'catalog_mode_active',
			'operator' => '==',
			'value'    => '1',
		]
	],
] );