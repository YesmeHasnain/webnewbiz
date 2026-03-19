<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * Product Single
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'ssp_1',
	'section' => 'section_single_product',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Navigation', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'product_navigation',
	'section' => 'section_single_product',
	'label' => esc_html__( 'Product Navigation', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'show',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'product_navigation_style',
	'section' => 'section_single_product',
	'label' => esc_html__( 'Navigation Style', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'style-1' => esc_html__( 'Style 1', 'ziomm' ),
		'style-2' => esc_html__( 'Style 2', 'ziomm' ),
		'style-3' => esc_html__( 'Style 3', 'ziomm' )
	),
	'default' => 'style-1',
	'active_callback' => array(
		array(
			'setting' => 'product_navigation',
			'operator' => '==',
			'value' => 'show'
		)
	),
) );

if ( class_exists( 'Kirki_Helper' ) ) {
	VLT_Options::add_field( array(
		'type' => 'select',
		'settings' => 'shop_link',
		'section' => 'section_single_product',
		'label' => esc_html__( 'Shop Link', 'ziomm' ),
		'tooltip' => esc_html__( 'For back button.', 'ziomm' ),
		'priority' => $priority++,
		'transport' => 'auto',
		'multiple' => 1,
		'choices' => Kirki_Helper::get_posts(
			array(
				'posts_per_page' => 9999,
				'post_type' => 'page'
			)
		),
		'default' => '',
		'active_callback' => array(
			array(
				'setting' => 'product_navigation',
				'operator' => '==',
				'value' => 'show'
			)
		),
	) );
}

/**
 * Shop General
 */
VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'shop_cart_icon',
	'section' => 'section_shop_general',
	'label' => esc_html__( 'Cart Icon', 'ziomm' ),
	'description' => esc_html__( 'The cart icon is shown only on pages related to the store.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'show',
) );