<?php
/**
 * geoport functions and definitions
 *
 * @package geoport
 */

/*------------------------------------------------------------------------------------------------------------------*/
/*	Define
/*------------------------------------------------------------------------------------------------------------------*/

define("GEOPORT_CSS", get_template_directory_uri() . '/assets/css/');
define("GEOPORT_JS", get_template_directory_uri() . '/assets/js/');
define("GEOPORT_INC", get_template_directory() . '/inc/');
define("GEOPORT_CORE", get_template_directory() . '/inc/core/');

/*------------------------------------------------------------------------------------------------------------------*/
/*	Require file list
/*------------------------------------------------------------------------------------------------------------------*/
require_once GEOPORT_CORE . 'geoport-themesetup.php';
require_once GEOPORT_CORE . 'geoport-scripts.php';

/*------------------------------------------------------------------------------------------------------------------*/
/*	Custom functions that act independently of the theme templates.
/*------------------------------------------------------------------------------------------------------------------*/

require_once GEOPORT_INC . 'extras.php';
require_once GEOPORT_INC . 'template-tags.php';
require_once GEOPORT_INC . 'jetpack.php';
require_once GEOPORT_INC . 'custom-header.php';
require_once GEOPORT_INC . 'tgmpa.php';

if( function_exists( 'geoport_framework_init' ) ) {
    require_once GEOPORT_INC . 'custom.php';
}