<?php

$xcency_theme_data = wp_get_theme();

/*
 * Define theme version
 */
define('XCENCY_VERSION', (WP_DEBUG) ? time() : $xcency_theme_data->get('Version'));

/*
 * Inc folder directory
 */
define('XCENCY_INC_DIR', get_template_directory() . '/inc/');

/*
 * Admin Pages
 */
require_once XCENCY_INC_DIR . 'admin/pages.php';


/*
 * After setup theme
 */
require_once XCENCY_INC_DIR . 'theme-setup.php';

/**
 * Template Functions
 */
require XCENCY_INC_DIR . 'template-functions.php';


/*
 * Load default theme options
 */
require_once XCENCY_INC_DIR . 'metabox-and-options/theme-options/theme-options-default.php';

/*
 * Load meta box and theme options if Codestar framework installed.
 */
add_action('after_setup_theme', 'xcency_add_metabox_and_options', 20);
function xcency_add_metabox_and_options(){
	if( class_exists( 'CSF' ) ) {
		require_once XCENCY_INC_DIR . 'metabox-and-options/metabox-and-options.php';
	}
}


/*
 * Enqueue styles and scripts.
 */
require_once XCENCY_INC_DIR . 'css-and-js.php';

/*
 * Register widget area
 */
require_once XCENCY_INC_DIR . 'widget-area-init.php';

/*
 * Load inline style.
 */
require_once XCENCY_INC_DIR . 'inline-style.php';

/**
 * Implement the Custom Header feature.
 */
require XCENCY_INC_DIR . 'custom-header.php';

/**
 * Customizer additions.
 */
require XCENCY_INC_DIR . '/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require XCENCY_INC_DIR . '/jetpack.php';
}

/*
 * Comment Template
 */
require_once XCENCY_INC_DIR . 'comment-template.php';

/*
 * Import Demo
 */
require_once XCENCY_INC_DIR . 'demo-content/import-demo-content.php';