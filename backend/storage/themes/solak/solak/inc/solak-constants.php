<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
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
if ( ! defined( 'SOLAK_DIR_URI' ) ) {
    define('SOLAK_DIR_URI', get_parent_theme_file_uri().'/' );
}

// Assist URI
if ( ! defined( 'SOLAK_DIR_ASSIST_URI' ) ) {
    define( 'SOLAK_DIR_ASSIST_URI', get_theme_file_uri('/assets/') );
}


// Css File URI
if ( ! defined( 'SOLAK_DIR_CSS_URI' ) ) {
    define( 'SOLAK_DIR_CSS_URI', get_theme_file_uri('/assets/css/') );
}

// Js File URI
if (!defined('SOLAK_DIR_JS_URI')) {
    define('SOLAK_DIR_JS_URI', get_theme_file_uri('/assets/js/'));
}


// Base Directory
if (!defined('SOLAK_DIR_PATH')) {
    define('SOLAK_DIR_PATH', get_parent_theme_file_path() . '/');
}

//Inc Folder Directory
if (!defined('SOLAK_DIR_PATH_INC')) {
    define('SOLAK_DIR_PATH_INC', SOLAK_DIR_PATH . 'inc/');
}

//SOLAK framework Folder Directory
if (!defined('SOLAK_DIR_PATH_FRAM')) {
    define('SOLAK_DIR_PATH_FRAM', SOLAK_DIR_PATH_INC . 'solak-framework/');
}

//Hooks Folder Directory
if (!defined('SOLAK_DIR_PATH_HOOKS')) {
    define('SOLAK_DIR_PATH_HOOKS', SOLAK_DIR_PATH_INC . 'hooks/');
}

//Demo Data Folder Directory Path
if( !defined( 'SOLAK_DEMO_DIR_PATH' ) ){
    define( 'SOLAK_DEMO_DIR_PATH', SOLAK_DIR_PATH_INC.'demo-data/' );
}
    
//Demo Data Folder Directory URI
if( !defined( 'SOLAK_DEMO_DIR_URI' ) ){
    define( 'SOLAK_DEMO_DIR_URI', SOLAK_DIR_URI.'inc/demo-data/' );
}