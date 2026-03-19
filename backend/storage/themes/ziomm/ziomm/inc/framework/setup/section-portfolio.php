<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * Portfolio Single
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sps_1',
	'section' => 'section_single_portfolio',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Navigation', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'work_navigation',
	'section' => 'section_single_portfolio',
	'label' => esc_html__( 'Work Navigation', 'ziomm' ),
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
	'settings' => 'work_navigation_style',
	'section' => 'section_single_portfolio',
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
			'setting' => 'work_navigation',
			'operator' => '==',
			'value' => 'show'
		)
	),
) );

if ( class_exists( 'Kirki_Helper' ) ) {
	VLT_Options::add_field( array(
		'type' => 'select',
		'settings' => 'portfolio_link',
		'section' => 'section_single_portfolio',
		'label' => esc_html__( 'Portfolio Link', 'ziomm' ),
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
				'setting' => 'work_navigation',
				'operator' => '==',
				'value' => 'show'
			)
		),
	) );
}