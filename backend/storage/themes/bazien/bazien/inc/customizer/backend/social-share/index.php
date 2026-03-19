<?php

// ============================================
// Panel
// ============================================

// no panel


// ============================================
// Sections
// ============================================

Kirki::add_section( 'social_share', array(
    'title'          => esc_html__( 'Social Share', 'bazien' ),
    'priority'       => 65,
    'capability'     => 'edit_theme_options',
) );


// ============================================
// Controls
// ============================================

$sep_id  = 98495;
$section = 'social_share';

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_facebook',
  'label'       => esc_html__( 'Facebook', 'bazien' ),
	'section'     => $section,
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_twitter',
  'label'       => esc_html__( 'Twitter', 'bazien' ),
	'section'     => $section,
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_reddit',
  'label'       => esc_html__( 'Reddit', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_linkedin',
  'label'       => esc_html__( 'Linkedin', 'bazien' ),
	'section'     => $section,
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_tumblr',
  'label'       => esc_html__( 'Tumblr', 'bazien' ),
	'section'     => $section,
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_pinterest',
  'label'       => esc_html__( 'Pinterest', 'bazien' ),
	'section'     => $section,
	'default'     => 'on',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_line',
  'label'       => esc_html__( 'Line', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_vk',
  'label'       => esc_html__( 'VK', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_whatapps',
  'label'       => esc_html__( 'Whatapps', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_telegram',
  'label'       => esc_html__( 'Telegram', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'sharing_email',
  'label'       => esc_html__( 'Email', 'bazien' ),
	'section'     => $section,
	'default'     => 'off',
	'priority'    => 10,
	'choices'     => array(
		'on'  => esc_html__( 'Enable', 'bazien' ),
		'off' => esc_html__( 'Disable', 'bazien' ),
	),
) );
