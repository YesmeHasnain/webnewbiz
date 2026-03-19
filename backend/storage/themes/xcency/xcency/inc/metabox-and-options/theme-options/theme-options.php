<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/*
 *  Create theme options
 */

$xcency_theme_option = 'xcency_theme_options';
$xcency_theme_version = wp_get_theme();

CSF::createOptions($xcency_theme_option, array(
	'framework_title' => wp_kses(
		sprintf(__("Xcency Options <small>V %s</small>", 'xcency'), $xcency_theme_version->get('Version')),
		array('small' => array())
	),
	'menu_title'      => esc_html__('Theme Options', 'xcency'),
	'menu_slug'       => 'xcency-options',
	'menu_type'       => 'submenu',
	'menu_parent'     => 'xcency',
	'class'           => 'xcency-theme-option-wrapper',
	'footer_credit'      => wp_kses(
		__( 'Developed by: <a target="_blank" href="https://quintexbd.com">QuintexBD</a>', 'xcency' ),
		array(
			'a'      => array(
				'href'   => array(),
				'target' => array()
			),
		)
	),
	'async_webfont' => false,
	'defaults'        => xcency_default_theme_options(),
	'footer_text'      => '',
));

/*
 * General options
 */
require_once 'general-options.php';

/*
 * Typography options
 */
require_once 'typography-options.php';

/*
 * Header options
 */
require_once 'header-options.php';

/*
 * Banner Options
 */
require_once 'banner-options.php';

/*
 * Page Options
 */
require_once 'page-options.php';

/*
 * Blog Page Options
 */
require_once 'blog-page-options.php';

/*
 * Single Post Options
 */
require_once 'single-post-options.php';

/*
 * Service Options
 */
require_once 'service-options.php';

/*
 * Team Options
 */
require_once 'team-options.php';

/*
 * portfolio Options
 */
require_once 'portfolio-options.php';

/*
 * Archive Page Options
 */
require_once 'archive-page-options.php';

/*
 * Search Page Options
 */
require_once 'search-page-options.php';


/*
 * Error 404 Page Options
 */
require_once 'error-page-options.php';

/*
 * Footer Options
 */
require_once 'footer-options.php';

// Custom Css section
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Custom CSS', 'xcency' ),
	'id'     => 'custom_css_options',
	'icon'   => 'fa fa-css3',
	'fields' => array(

		array(
			'id'       => 'xcency_custom_css',
			'type'     => 'code_editor',
			'title'    => esc_html__( 'Custom CSS', 'xcency' ),
			'settings' => array(
				'theme'  => 'mbo',
				'mode'   => 'css',
			),
			'sanitize' => false,
		),
	)
) );


/*
 * Backup options
 */
CSF::createSection($xcency_theme_option, array(
	'title'  => esc_html__('Backup', 'xcency'),
	'id'     => 'backup_options',
	'icon'   => 'fa fa-window-restore',
	'fields' => array(
		array(
			'type' => 'backup',
		),
	)
));