<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sfg_1',
	'section' => 'section_footer_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'footer_show',
	'section' => 'section_footer_general',
	'label' => esc_html__( 'Footer Show', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' ),
	),
	'default' => 'hide',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'footer_template',
	'section' => 'section_footer_general',
	'label' => esc_html__( 'Footer Template', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => ziomm_get_elementor_templates(),
	'active_callback' => array(
		array(
			'setting' => 'footer_show',
			'operator' => '==',
			'value' => 'show'
		),
	)
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'footer_fixed',
	'section' => 'section_footer_general',
	'label' => esc_html__( 'Footer Fixed', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' )
	),
	'default' => 'disable',
	'active_callback' => array(
		array(
			'setting' => 'footer_show',
			'operator' => '==',
			'value' => 'show'
		),
	)
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sfg_2',
	'section' => 'section_footer_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Shapes', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'shape_black',
	'section' => 'section_footer_general',
	'label' => esc_html__( 'Shape Black', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => ''
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'shape_white',
	'section' => 'section_footer_general',
	'label' => esc_html__( 'Shape White', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => ''
) );