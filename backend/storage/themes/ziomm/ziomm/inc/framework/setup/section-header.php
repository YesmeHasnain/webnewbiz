<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * Header general
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_1',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_show',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Header Show', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' ),
	),
	'default' => 'show',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_type',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Header Layout', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'default' => esc_html__( 'Default', 'ziomm' ),
		'fullscreen' => esc_html__( 'Fullscreen', 'ziomm' ),
		'fullscreen-dark' => esc_html__( 'Fullscreen Dark', 'ziomm' ),
		'fullscreen-dropdown' => esc_html__( 'Fullscreen Dropdown', 'ziomm' ),
		'fullscreen-dropdown-dark' => esc_html__( 'Fullscreen Dropdown Dark', 'ziomm' ),
		'slide' => esc_html__( 'Slide', 'ziomm' )
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		)
	),
	'default' => 'default',
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_2',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Navigation', 'ziomm' ) . '</div>',
	'priority' => $priority++,
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_opaque',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Navigation Opaque', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'enable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_transparent',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Transparent', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_transparent_always',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Transparent Always', 'ziomm' ),
	'description' => esc_html__( 'Transparent also after page scrolled down.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_sticky',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Sticky', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_hide_on_scroll',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Hide on Scroll', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_sticky',
			'operator' => '==',
			'value' => 'enable',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_white_text_on_top',
	'section' => 'section_header_general',
	'label' => esc_html__( 'White Text on Top', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'navigation_dark',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Dark Navbar', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' ),
	),
	'active_callback' => array(
		array(
			'setting' => 'navigation_show',
			'operator' => '==',
			'value' => 'show',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'slide',
		),
		array(
			'setting' => 'navigation_type',
			'operator' => '!=',
			'value' => 'aside',
		),
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_3',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Logo', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'header_logo',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => [
		'save_as' => 'id'
	],
	'default' => '',
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'header_logo_white',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo White', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => [
		'save_as' => 'id'
	],
	'default' => '',
) );

VLT_Options::add_field( array(
	'type' => 'dimension',
	'settings' => 'header_logo_height',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo Height', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'output' => array(
		array(
			'element' => '.vlt-navbar-logo img',
			'property' => 'height'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_4',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Logo Small', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'header_logo_small',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => [
		'save_as' => 'id'
	],
	'default' => '',
) );

VLT_Options::add_field( array(
	'type' => 'image',
	'settings' => 'header_logo_small_white',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo White', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => [
		'save_as' => 'id'
	],
	'default' => '',
) );

VLT_Options::add_field( array(
	'type' => 'dimension',
	'settings' => 'header_logo_small_height',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Logo Height', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'output' => array(
		array(
			'element' => '.vlt-navbar-logo.vlt-navbar-logo--small img',
			'property' => 'height'
		)
	)
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_5',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Socials', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'repeater',
	'settings' => 'header_social_list',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Social List', 'ziomm' ),
	'description' => esc_html__( 'Social icons is shown only for some styles of menu. (It works for aside, offcanvas and slide menus)', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'row_label' => array(
		'type' => 'text',
		'value' => esc_html__( 'social', 'ziomm' )
	),
	'fields' => array(
		'social_icon' => array(
			'type' => 'select',
			'label' => esc_html__( 'Social Icon', 'ziomm' ),
			'choices' => ziomm_get_social_icons()
		),
		'social_url' => array(
			'type' => 'text',
			'label' => esc_html__( 'Social Url', 'ziomm' )
		),
	),
	'default' => ''
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shg_6',
	'section' => 'section_header_general',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Menu Sounds', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'menu_toggle_sound',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Menu Toggle Sound', 'ziomm' ),
	'description' => esc_html__( 'Sounds when you open / close menu.', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' )
	),
	'default' => 'disable',
) );

VLT_Options::add_field( array(
	'type' => 'upload',
	'settings' => 'open_click_sound',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Audio for "Open Menu"', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'active_callback' => array(
		array(
			'setting' => 'menu_toggle_sound',
			'operator' => '==',
			'value' => 'enable',
		)
	),
) );

VLT_Options::add_field( array(
	'type' => 'upload',
	'settings' => 'close_click_sound',
	'section' => 'section_header_general',
	'label' => esc_html__( 'Audio for "Close Menu"', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
	'active_callback' => array(
		array(
			'setting' => 'menu_toggle_sound',
			'operator' => '==',
			'value' => 'enable',
		)
	),
) );

/**
 * Header fullscreen
 */

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shf_1',
	'section' => 'section_header_fullscreen',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Fullscreen', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'fullscreen_menu_background',
	'section' => 'section_header_fullscreen',
	'label' => esc_html__( 'Menu Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#ffffff',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'top center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-nav--fullscreen__background'
		),
	),
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shf_2',
	'section' => 'section_header_fullscreen',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Fullscreen Dark', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'fullscreen_dark_menu_background',
	'section' => 'section_header_fullscreen',
	'label' => esc_html__( 'Menu Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#101010',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'top center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-nav--fullscreen-dark .vlt-nav--fullscreen__background'
		),
	),
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shf_3',
	'section' => 'section_header_fullscreen',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Fullscreen Dropdown', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'fullscreen_dropdown_menu_background',
	'section' => 'section_header_fullscreen',
	'label' => esc_html__( 'Menu Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#ffffff',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'top center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-nav--fullscreen-dropdown .vlt-nav--fullscreen__background'
		),
	),
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shf_4',
	'section' => 'section_header_fullscreen',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Fullscreen Dropdown Dark', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'fullscreen_dropdown_dark_menu_background',
	'section' => 'section_header_fullscreen',
	'label' => esc_html__( 'Menu Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#101010',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'top center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-nav--fullscreen-dropdown.vlt-nav--fullscreen-dark .vlt-nav--fullscreen__background'
		),
	),
) );

/**
 * Header slide
 */

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shs_1',
	'section' => 'section_header_slide',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Contact Link', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'link',
	'settings' => 'header_contact_link',
	'section' => 'section_header_slide',
	'label' => esc_html__( 'Link', 'ziomm' ),
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'shs_2',
	'section' => 'section_header_slide',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Header Slide', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'slide_menu_background',
	'section' => 'section_header_slide',
	'label' => esc_html__( 'Menu Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#ffffff',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'top center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-nav--slide__background'
		),
	),
) );