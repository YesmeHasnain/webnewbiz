<?php
Kirki::add_field( 'siberia_customize', array(
    'type'     => 'select',
    'settings' => 'footer_template',
    'section' => 'footer_settings',
    'label' => esc_html__( 'Footer Template', 'siberia' ),
    'priority' => $priority++,

    'choices'     => ms_get_elementor_templates(),
) );