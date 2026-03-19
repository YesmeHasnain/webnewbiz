<?php

$theme_path_images = SIBERIA_THEME_DIRECTORY . 'assets/img/';

/**
 * Update Kirki Config
 */

Kirki::add_config( 'siberia_customize', array(
	'capability' => 'edit_theme_options',
	'option_type' => 'theme_mod',
) );

$first_level = 10;
$second_level = 10;

// Theme options
Kirki::add_panel( 'theme_options', array(
	'priority' => $first_level++,
    'title' => esc_html__('Theme Options', 'siberia'),
    'icon'  => 'dashicons-admin-generic',
) );

// Container Max-Width
Kirki::add_section('section_general_settings', array(
    'title' => esc_html__('General', 'siberia'),
    'icon' => 'dashicons-align-wide',
    'panel' => 'theme_options',
    'priority' => $first_level++,
));

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-general.php';

// Header
Kirki::add_section('header_settings', array(
    'title' => esc_html__('Header', 'siberia'),
    'icon'  => 'dashicons-schedule',
    'panel' => 'theme_options',
    'priority' => $first_level++,
));

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-header.php';

// Logo
Kirki::add_section('logo_settings', array(
    'title' => esc_html__('Logo', 'siberia'),
    'icon'  => 'dashicons-format-image',
    'panel' => 'theme_options',
    'priority' => $first_level++,
));

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-logo.php';

// Menu Navigation
Kirki::add_section('menu_settings', array(
    'title' => esc_html__('Menu', 'siberia'),
    'panel' => 'theme_options',
    'icon' => 'dashicons-menu',
    'priority' => $first_level++,
));

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-menu.php';

// Custom Footer
Kirki::add_section('footer_settings', array(
    'title' => esc_html__('Footer', 'siberia'),
    'panel' => 'theme_options',
    'icon' => 'dashicons-button',
    'priority' => $first_level++,
));

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-footer.php';

// Fonts_setting
Kirki::add_section( 'fonts_setting', array(
    'panel' => 'theme_options',
    'title' => esc_html__( 'Typography', 'siberia' ),
    'icon' => 'dashicons-text',
    'priority' => $first_level++
) );

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-typography.php';

// Colors themes
Kirki::add_section( 'colors_schemes', array(
    'panel' => 'theme_options',
    'title' => esc_html__( 'Colors', 'siberia' ),
    'icon' => 'dashicons-art',
    'priority' => $first_level++
) );

require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-colors.php';

// Google Map
Kirki::add_section( 'section_google_map', array(
    'panel' => 'theme_options',
    'title' => esc_html__( 'Google Map', 'siberia' ),
    'icon'  => 'dashicons-location-alt',
    'priority' => $first_level++
) );
require_once SIBERIA_REQUIRE_DIRECTORY . 'inc/framework/setup/section-google.php';