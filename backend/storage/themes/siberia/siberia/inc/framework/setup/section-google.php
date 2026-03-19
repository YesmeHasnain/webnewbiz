<?php

$priority = 0;

Kirki::add_field( 'siberia_customize', array(
    'type' => 'text',
    'settings' => 'google_map_api_key',
    'section' => 'section_google_map',
    'label' => esc_html__( 'API Key', 'siberia' ),
    'description' => 'Get your API Key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> or read <a href="http://bsf.io/google-map-api-key" target="_blank">this article</a> for more information.',
    'priority' => $priority++,
    'transport' => 'auto',
    'default' => '',
) );