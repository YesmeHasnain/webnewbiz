<?php

Kirki::add_section( 'header_contact', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Contact', 'vasia' ),
    'panel'       => 'header',
   
) );
	
Kirki::add_field( 'option', [	
	'type'        => 'image',	
	'settings'    => 'he_contact_image',	
	'label'       => esc_html__( 'Image', 'vasia' ),	
	'description' => esc_html__( 'Recommend 35x35 size', 'vasia' ),	
	'section'     => 'header_contact',	
	'default'     => '',
	'transport' => 'postMessage'		
] );	
Kirki::add_field( 'option', [	
	'type'     => 'text',	
	'settings' => 'he_contact_phone',	
	'label'    => esc_html__( 'Phone number', 'vasia' ),	
	'section'  => 'header_contact',
	'transport' => 'postMessage'	
] );	
Kirki::add_field( 'option', [	
	'type'     => 'text',	
	'settings' => 'he_contact_text',	
	'label'    => esc_html__( 'Text', 'vasia' ),	
	'section'  => 'header_contact',	
	'transport' => 'postMessage'	
] );