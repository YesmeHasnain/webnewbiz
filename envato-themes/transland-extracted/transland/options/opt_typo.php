<?php

Redux::setSection( 'transland_opt', array(
    'title'            => esc_html__( 'Typography', 'transland' ),
    'id'               => 'transland_typo_opt',
    'icon'             => 'dashicons dashicons-editor-textcolor',
    'fields'           => array(
        array(
            'id'        => 'body_font',
            'type'      => 'typography',
            'google'      => true, 
            'title'     => esc_html__( 'Body Typography', 'transland' ),
            'subtitle'  => esc_html__( 'These settings control the typography for the body text globally.', 'transland' ),
            'output'    => 'body',
        ),
    )
));


Redux::setSection( 'transland_opt', array(
    'title'            => esc_html__( 'Headers Typography', 'transland' ),
    'id'               => 'headers_font_opt',
    'icon'             => 'dashicons dashicons-editor-spellcheck',
    'subsection'       => true,
    'fields'           => array(

        array(
            'id'        => 'header_typo',
            'type'      => 'typography',
            'google'      => true, 
            'title'     => esc_html__( 'All Heading Typography', 'transland' ),
            'subtitle'  => esc_html__( 'These settings control the typography for all Headers.', 'transland' ),
            'output'    => 'body h1, body h2, body h3, body h4, body h4, body h5, body h6',
        ),
    )
));