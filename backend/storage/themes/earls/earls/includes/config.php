<?php
/**
 * Theme config file.
 *
 * @package EARLS
 * @author  TemplatePath
 * @version 1.0
 * changed
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Restricted' );
}

$config = array();

$config['default']['earls_main_header'][] 	= array( 'earls_main_header_area', 99 );

$config['default']['earls_main_footer'][] 	= array( 'earls_main_footer_area', 99 );

$config['default']['earls_sidebar'][] 	    = array( 'earls_sidebar', 99 );

$config['default']['earls_banner'][] 	    = array( 'earls_banner', 99 );


return $config;
