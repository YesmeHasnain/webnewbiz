<?php

Kirki::add_section( 'preloader', array(
    'title'       => esc_html__( 'Preloader', 'vasia' ),
    'panel'       => 'general'
) );

Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'preloader_active',
	'label'       => esc_html__( 'Active proloader website', 'vasia' ),
	'section'     => 'preloader',
	'default'     => '0',
] );

Kirki::add_field( 'option', [
	'type'        => 'image',
	'settings'    => 'preloader_gif',
	'label'       => esc_html__( 'Upload your GIF', 'vasia' ),
	'description' => esc_html__( 'If there no image, the default gif will be used.', 'vasia' ),
	'section'     => 'preloader',
	'default'     => '',
] );

Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'preloader_bg',
	'label'       => __( 'Background', 'vasia' ),
	'section'     => 'preloader',
	'default'     => '',
] );