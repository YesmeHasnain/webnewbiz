<?php 

Kirki::add_section( 'styles_color', array(
    'title'       => esc_html__( 'Color', 'vasia' ),
    'panel'       => 'styles',
) );

Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'primary_color',
	'label'       => __( 'Primary color', 'vasia' ),
	'section'     => 'styles_color',
	'default'     => '#313030',
] );

Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'link_color',
	'label'       => __( 'Links color', 'vasia' ),
	'section'     => 'styles_color',
	'default'     => '#313030',
] );