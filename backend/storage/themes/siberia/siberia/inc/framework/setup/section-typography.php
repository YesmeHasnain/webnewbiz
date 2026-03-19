<?php

// Headers
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
	'type'        => 'typography',
	'settings'    => 'font_title_settings',
	'label'       => esc_html__( 'Font for Titles:', 'siberia' ),
	'section'     => 'fonts_setting',
	'default'     => array(
		'font-family'    => 'var(--font-heading)',
		'subsets'        => array( 'latin-ext' ),
		'variant'        => '400',
	),
	'choices' => [
		'fonts' => [
			'standard' => [ 'Neue Haas Grotesk Display Pro' ],
			],
	],
	'priority'    => 10,
	'output'      => array(
		array(
			'element' => 'h1, h2, h3, h4, h5, h6',
		)
	)
) );

// Main
$priority = 0;
Kirki::add_field( 'siberia_customize', array(
	'type'        => 'typography',
	'settings'    => 'main_font',
	'section' => 'fonts_setting',
	'label'       => esc_html__( 'Primary Font', 'siberia' ),
	'section'     => 'fonts_setting',
	'default'     => [
		'font-family'    => 'var(--font-primary)',
		'variant'        => 'regular',
	],
	'priority'    => 10,
	'choices' => [
		'fonts' => [
			'standard' => [ 'Noto Sans' ],
			],
	],
	'output'      => array(
		array(
			'element'  => 'body',
			'property' => 'font-family',
		),
		array(
			'property' => 'font-size',
			'context'  => array( 'editor' ),
		),
		array(
			'element'  => '.edit-post-visual-editor.editor-styles-wrapper',
			'context'  => array( 'editor' ),
			'property' => 'font-family',
		),
	),

) );