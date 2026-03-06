<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * Define constant 
 *
 */

// Base URI
if ( ! defined( 'BARAB_DIR_URI' ) ) {
    define('BARAB_DIR_URI', get_parent_theme_file_uri().'/' );
}

// Assist URI
if ( ! defined( 'BARAB_DIR_ASSIST_URI' ) ) {
    define( 'BARAB_DIR_ASSIST_URI', get_theme_file_uri('/assets/') );
}


// Css File URI
if ( ! defined( 'BARAB_DIR_CSS_URI' ) ) {
    define( 'BARAB_DIR_CSS_URI', get_theme_file_uri('/assets/css/') );
}

// Js File URI
if (!defined('BARAB_DIR_JS_URI')) {
    define('BARAB_DIR_JS_URI', get_theme_file_uri('/assets/js/'));
}


// Base Directory
if (!defined('BARAB_DIR_PATH')) {
    define('BARAB_DIR_PATH', get_parent_theme_file_path() . '/');
}

//Inc Folder Directory
if (!defined('BARAB_DIR_PATH_INC')) {
    define('BARAB_DIR_PATH_INC', BARAB_DIR_PATH . 'inc/');
}

//BARAB framework Folder Directory
if (!defined('BARAB_DIR_PATH_FRAM')) {
    define('BARAB_DIR_PATH_FRAM', BARAB_DIR_PATH_INC . 'barab-framework/');
}

//Hooks Folder Directory
if (!defined('BARAB_DIR_PATH_HOOKS')) {
    define('BARAB_DIR_PATH_HOOKS', BARAB_DIR_PATH_INC . 'hooks/');
}

//Demo Data Folder Directory Path
if( !defined( 'BARAB_DEMO_DIR_PATH' ) ){
    define( 'BARAB_DEMO_DIR_PATH', BARAB_DIR_PATH_INC.'demo-data/' );
}
    
//Demo Data Folder Directory URI
if( !defined( 'BARAB_DEMO_DIR_URI' ) ){
    define( 'BARAB_DEMO_DIR_URI', BARAB_DIR_URI.'inc/demo-data/' );
}