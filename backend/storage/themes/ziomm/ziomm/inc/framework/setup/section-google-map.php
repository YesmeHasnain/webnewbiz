<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

VLT_Options::add_field( array(
	'type' => 'text',
	'settings' => 'google_map_api_key',
	'section' => 'section_google_map',
	'label' => esc_html__( 'API Key', 'ziomm' ),
	'description' => 'Get your API Key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> or read <a href="http://bsf.io/google-map-api-key" target="_blank">this article</a> for more information.',
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '',
) );