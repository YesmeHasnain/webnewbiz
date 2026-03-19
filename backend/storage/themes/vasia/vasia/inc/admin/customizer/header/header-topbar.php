<?php
$topbar_content = array(
	'none' => esc_html__( '-----', 'vasia' ),
	'account' => esc_html__( 'Account', 'vasia' ),
	'topbar-menu' => esc_html__( 'Topbar menu', 'vasia' ),
	'social' => esc_html__( 'Social list', 'vasia' ),
	'language' => esc_html__( 'Language switcher', 'vasia' ),
	'currency' => esc_html__( 'Currency switcher', 'vasia' ),
	'html1' => esc_html__( 'HTML 1', 'vasia' ),
	'html2' => esc_html__( 'HTML 2', 'vasia' ),
);
Kirki::add_section( 'header_topbar', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Header Topbar', 'vasia' ),
    'panel'       => 'header',
) );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_topbar_custom1',
	'section'     => 'header_topbar',
	'default'         => '<div class="customize-title-divider">' . __( 'General', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_topbar_active',
	'label'       => esc_html__( 'Active topbar', 'vasia' ),
	'section'     => 'header_topbar',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'vasia' ),
		'off' => esc_html__( 'Disable', 'vasia' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_topbar_background',
	'label'       => esc_html__( 'Background', 'vasia' ),
	'section'     => 'header_topbar',
	'default'     => '#313030',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_topbar_text_color',
	'label'       => esc_html__( 'Text color', 'vasia' ),
	'section'     => 'header_topbar',
	'default'     => '#ffffff',
	'choices'     => [
		'alpha' => true,
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_topbar_custom2',
	'section'     => 'header_topbar',
	'default'         => '<div class="customize-title-divider">' . __( 'Layout', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Left position', 'vasia' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'vasia' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'vasia' ),
	'settings'     => 'topbar_left',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'vasia' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );

Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Center position', 'vasia' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'vasia' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'vasia' ),
	'settings'     => 'topbar_center',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'vasia' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Right position', 'vasia' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'vasia' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'vasia' ),
	'settings'     => 'topbar_right',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'vasia' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );