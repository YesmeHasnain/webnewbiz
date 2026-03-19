<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * Advanced
 */
VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'jquery_in_footer',
	'section' => 'section_advanced',
	'label' => esc_html__( 'Load jQuery in footer', 'ziomm' ),
	'description' => esc_html__( 'Solves render-blocking issue, however can cause plugin conflicts.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'disable' => esc_html__( 'Disable', 'ziomm' ),
		'enable' => esc_html__( 'Enable', 'ziomm' ),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'acf_show_admin_panel',
	'section' => 'section_advanced',
	'label' => esc_html__( 'Show ACF in Admin Panel', 'ziomm' ),
	'description' => esc_html__( 'This field enable tab for ACF Professional in your dashboard.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'hide' => esc_html__( 'Hide', 'ziomm' ),
		'show' => esc_html__( 'Show', 'ziomm' ),
	),
	'default' => 'hide',
) );

VLT_Options::add_field( array(
	'type' => 'color',
	'settings' => 'mobile_status_bar_color',
	'section' => 'section_advanced',
	'label' => esc_html__( 'Mobile Status Bar Colors', 'ziomm' ),
	'description' => esc_html__( 'Field for address bar or device status bar to match your brand colors.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '#eb2353',
) );


VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'comment_placement',
	'section' => 'section_advanced',
	'label' => esc_html__( 'Comment Placement', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'top' => esc_html__( 'Top', 'ziomm' ),
		'bottom' => esc_html__( 'Bottom', 'ziomm' )
	),
	'default' => 'bottom',
) );