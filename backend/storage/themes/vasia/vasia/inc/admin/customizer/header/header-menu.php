<?php


Kirki::add_section( 'header_menu', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Menu', 'vasia' ),
    'panel'       => 'header',
) );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_horizonal_menu',
	'section'     => 'header_menu',
	'default'         => '<div class="customize-title-divider">' . __( 'Horizontal menu', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_background',
	'label'       => esc_html__( 'Menu Background', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'hmenu_main_items',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Main items', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'hmenu_item_align',
	'label'       => esc_html__( 'Item align', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'center',
	'choices'     => [
		'left'   => esc_html__( 'Left', 'vasia' ),
		'center' => esc_html__( 'Center', 'vasia' ),
		'right'  => esc_html__( 'Right', 'vasia' ),
	],
	'transport' => 'postMessage'
] );

Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_color',
	'label'       => esc_html__( 'Color', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => '#313030',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_color_active',
	'label'       => esc_html__( 'Active Color', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => '#313030',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_background_color',
	'label'       => esc_html__( 'Background Color', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_background_color_active',
	'label'       => esc_html__( 'Active Background Color', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'hmenu_item_font',
	'label'       => esc_html__( 'Font size', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 16,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'hmenu_item_space',
	'label'       => esc_html__( 'Space between items', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 20,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'hmenu_submenu',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Submenu', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_vertical_menu',
	'section'     => 'header_menu',
	'default'         => '<div class="customize-title-divider">' . __( 'Vertical menu', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'vertical_menu_active',
	'label'       => esc_html__( 'Active vertical menu', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => '0',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'vmenu_title_section',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'The title', 'vasia' ) . '</div>',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'vmenu_title',
	'label'    => esc_html__( 'Title', 'vasia' ),
	'section'  => 'header_menu',
	'default'  => esc_html__( 'Categories', 'vasia' ),
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_title_size',
	'label'       => esc_html__( 'Title size', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 14,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_title_width',
	'label'       => esc_html__( 'Title width', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 210,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'vmenu_title_bground',
	'label'       => esc_html__( 'Title background', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'vmenu_title_color',
	'label'       => esc_html__( 'Title color', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => '#ffffff',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'vmenu_items_section',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Menu items', 'vasia' ) . '</div>',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'vmenu_action',
	'label'       => esc_html__( 'Show menu items by', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 'click',
	'choices'     => [
		'click'   => esc_html__( 'Click', 'vasia' ),
		'hover' => esc_html__( 'Hover', 'vasia' ),
	],
	'transport' => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_items_width',
	'label'       => esc_html__( 'Items width', 'vasia' ),
	'section'     => 'header_menu',
	'default'     => 270,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );

