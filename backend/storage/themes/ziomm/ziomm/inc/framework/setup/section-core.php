<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * General
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sg_1',
	'section' => 'core_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Colors', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'body_background',
	'section' => 'core_general',
	'label' => esc_html__( 'Site Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#ffffff',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'center center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => 'body'
		),
	),
) );

VLT_Options::add_field( array(
	'type' => 'multicolor',
	'settings' => 'accent_colors',
	'section' => 'core_general',
	'label' => esc_html__( 'Accent Colors', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'first' => esc_html__( 'First', 'ziomm' ),
		'second' => esc_html__( 'Second', 'ziomm' )
	),
	'default' => array(
		'first' => '#eb2353',
		'second' => '#ff8d18'
	)
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sg_2',
	'section' => 'core_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Preloader', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'preloader_style',
	'section' => 'core_general',
	'label' => esc_html__( 'Style Preloader', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'none' => esc_html__( 'None', 'ziomm' ),
		'image' => esc_html__( 'Image', 'ziomm' ),
		'bounce' => esc_html__( 'Bounce', 'ziomm' )
	),
	'default' => 'bounce'
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'preloader_image',
	'section' => 'core_general',
	'label' => esc_html__( 'Preloader Image', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'active_callback' => array(
		array(
			'setting' => 'preloader_style',
			'operator' => '==',
			'value' => 'image'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sg_3',
	'section' => 'core_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Additional', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'back_to_top',
	'section' => 'core_general',
	'label' => esc_html__( '"Back to Top" Button', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'show',
) );

VLT_Options::add_field( array(
	'type' => 'code',
	'settings' => 'tracking_code',
	'section' => 'core_general',
	'label' => esc_html__( 'Tracking Code', 'ziomm' ),
	'description' => esc_html__( 'Copy and paste your tracking code here.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'language' => 'php',
	),
	'default' => '',
) );

/**
 * Site Protection
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'csp_1',
	'section' => 'core_site_protection',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Click Copyright', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'site_protection',
	'section' => 'core_site_protection',
	'label' => esc_html__( 'Site Protection', 'ziomm' ),
	'tooltip' => esc_html__( 'It works for all visitors except administrator. You can check it in "Incognito Mode".', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'hide',
) );

VLT_Options::add_field( array(
	'type' => 'editor',
	'settings' => 'site_protection_content',
	'section' => 'core_site_protection',
	'label' => esc_html__( 'Content', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'active_callback' => array(
		array(
			'setting' => 'site_protection',
			'operator' => '==',
			'value' => 'show'
		)
	)
) );

/**
 * Selection
 */
VLT_Options::add_field( array(
	'type' => 'color',
	'settings' => 'selection_text_color',
	'section' => 'core_selection',
	'label' => esc_html__( 'Selection Text Color', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'alpha' => false
	),
	'default' => '#ffffff',
	'output' => array(
		array(
			'element' => '::selection',
			'property' => 'color',
			'suffix' => '!important'
		),
		array(
			'element' => '::-moz-selection',
			'property' => 'color',
			'suffix' => '!important'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'color',
	'settings' => 'selection_background_color',
	'section' => 'core_selection',
	'label' => esc_html__( 'Selection Background Color', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'alpha' => true
	),
	'default' => '#eb2353',
	'output' => array(
		array(
			'element' => '::selection',
			'property' => 'background-color',
			'suffix' => '!important'
		),
		array(
			'element' => '::-moz-selection',
			'property' => 'background-color',
			'suffix' => '!important'
		)
	)
) );

/**
 * Scrollbar
 */
VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'custom_scrollbar',
	'section' => 'core_scrollbar',
	'label' => esc_html__( 'Custom Scrollbar', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' )
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'color',
	'settings' => 'custom_scrollbar_track_color',
	'section' => 'core_scrollbar',
	'label' => esc_html__( 'Track Color', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'alpha' => true
	),
	'default' => 'rgba(16,16,16,.05)',
	'output' => array(
		array(
			'element' => '::-webkit-scrollbar',
			'property' => 'background-color'
		)
	),
	'active_callback' => array(
		array(
			'setting' => 'custom_scrollbar',
			'operator' => '==',
			'value' => 'enable'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'color',
	'settings' => 'custom_scrollbar_bar_color',
	'section' => 'core_scrollbar',
	'label' => esc_html__( 'Bar Color', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'alpha' => false
	),
	'default' => '#eb2353',
	'output' => array(
		array(
			'element' => '::-webkit-scrollbar-thumb',
			'property' => 'background-color'
		)
	),
	'active_callback' => array(
		array(
			'setting' => 'custom_scrollbar',
			'operator' => '==',
			'value' => 'enable'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'slider',
	'settings' => 'custom_scrollbar_width',
	'section' => 'core_scrollbar',
	'label' => esc_html__( 'Bar Width', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'min' => '0',
		'max' => '16',
		'step' => '1'
	),
	'default' => '5',
	'output' => array(
		array(
			'element' => '::-webkit-scrollbar',
			'property' => 'width',
			'units' => 'px'
		)
	),
	'active_callback' => array(
		array(
			'setting' => 'custom_scrollbar',
			'operator' => '==',
			'value' => 'enable'
		)
	)
) );

/**
 * Custom sidebars
 */
VLT_Options::add_field( array(
	'type' => 'repeater',
	'settings' => 'custom_sidebars',
	'section' => 'core_sidebars',
	'label' => esc_attr__( 'Custom Sidebars', 'ziomm' ),
	'description' => esc_html__( 'Create new sidebars and use them later via the page builder for different areas.', 'ziomm' ),
	'row_label' => array(
		'type' => 'field',
		'field' => 'sidebar_title',
		'value' => esc_attr__( 'Custom Sidebar', 'ziomm' ),
	),
	'button_label' => esc_attr__( 'Add new sidebar area', 'ziomm' ),
	'default' => '',
	'fields' => array(
		'sidebar_title' => array(
			'type' => 'text',
			'label' => esc_attr__( 'Sidebar Title', 'ziomm' ),
			'default' => '',
		),
		'sidebar_description' => array(
			'type' => 'textarea',
			'label' => esc_attr__( 'Sidebar Description', 'ziomm' ),
			'default' => '',
		),
	)
));

/**
 * Admin logo
 */
VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'login_logo_image',
	'section' => 'core_login_logo',
	'label' => esc_html__( 'Authorization Logo', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => $theme_path_images . 'vlthemes.png',
) );

VLT_Options::add_field( array(
	'type' => 'dimension',
	'settings' => 'login_logo_image_height',
	'section' => 'core_login_logo',
	'label' => esc_html__( 'Logo Height', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '115px',
	'active_callback' => array(
		array(
			'setting' => 'login_logo_image',
			'operator' => '!=',
			'value' => ''
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'dimension',
	'settings' => 'login_logo_image_width',
	'section' => 'core_login_logo',
	'label' => esc_html__( 'Logo Width', 'ziomm' ),
	'transport' => 'auto',
	'priority' => $priority++,
	'default' => '102px',
	'active_callback' => array(
		array(
			'setting' => 'login_logo_image',
			'operator' => '!=',
			'value' => ''
		)
	)
) );