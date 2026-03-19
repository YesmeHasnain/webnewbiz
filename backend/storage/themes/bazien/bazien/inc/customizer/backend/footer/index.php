<?php

// ============================================
// Panel
// ============================================

// no panel


// ============================================
// Sections
// ============================================

Kirki::add_section( 'footer', array(
    'title'          => esc_html__( 'Footer', 'bazien' ),
    'priority'       => 60,
    'capability'     => 'edit_theme_options',
) );


// ============================================
// Controls
// ============================================

$sep_id  = 48536;
$section = 'footer';
$footer_link = sprintf('<a href="%s">%s</a>', add_query_arg(array('post_type' => 'elementor_library', 'elementor_library_type' => 'footer'), admin_url('edit.php')), __('here', 'bazien'));
Kirki::add_field( 'bazien', array(
    'type'        => 'select',
    'settings'    => 'footer_template',
    'label'       => esc_html__( 'Footer Template', 'bazien' ),
    'section'     => 'footer',
    'default'     => 'type-mini',
    'priority'    => 10,
    'choices'     => array(
        'type-mini'     => esc_html__( 'Footer Mini', 'bazien' ),
        'type-builder'     => esc_html__( 'Footer Builder', 'bazien' ),
    ),
) );
Kirki::add_field( 'bazien', array(
    'type'        => 'select',
    'settings'    => 'footer_template_builder',
    'label'       => esc_html__( 'Footer Builder Template', 'bazien' ),
    'section'     => 'footer',
    'default'     => 'type-mini',
    'priority'    => 10,
    'choices'     => nova_get_config_footer_layout_opts(),
    'description'        => sprintf( __('You can manage footer layout on %s', 'bazien'), $footer_link ),
    'active_callback'    => array(
        array(
            'setting'  => 'footer_template',
            'operator' => '==',
            'value'    => 'type-builder',
        ),
    ),
) );
Kirki::add_field( 'bazien', array(
    'type'     => 'textarea',
    'settings' => 'footer_text',
    'label'    => esc_html__( 'Copyright Text', 'bazien' ),
    'section'  => $section,
    'default'  => esc_html__( '© 2021 Bazien All rights reserved. Designed by Novaworks', 'bazien' ),
    'priority' => 10,
    'active_callback'    => array(
        array(
            'setting'  => 'footer_template',
            'operator' => '==',
            'value'    => 'type-mini',
        ),
    ),
) );
