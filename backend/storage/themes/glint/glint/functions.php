<?php
/**
 * Glint functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

/**
 * Define Const for theme Dir
 * @since 1.0.0
 * */
define( 'GLINT_ROOT_PATH', get_template_directory() );
define( 'GLINT_ROOT_URL', get_template_directory_uri() );
define( 'GLINT_CSS', GLINT_ROOT_URL . '/assets/css' );
define( 'GLINT_JS', GLINT_ROOT_URL . '/assets/js' );
define( 'GLINT_INC', GLINT_ROOT_PATH . '/inc' );
define( 'GLINT_THEME_OPTIONS', GLINT_INC . '/theme-options' );
define( 'GLINT_THEME_STYLESHEETS', GLINT_INC . '/theme-stylesheets' );
define( 'GLINT_CS_IMAGES', GLINT_ROOT_URL . '/inc/theme-options/images' );

/**
 * define theme info
 * @since 1.0.0
 * */
if ( is_child_theme() ) {
    $theme        = wp_get_theme();
    $parent_theme = $theme->Template;
    $theme_info   = wp_get_theme( $parent_theme );
} else {
    $theme_info = wp_get_theme();
}
define( 'GLINT_DEV_MODE', true );
$glint_version = GLINT_DEV_MODE ? time() : $theme_info->get( 'Version' );
define( 'GLINT_NAME', $theme_info->get( 'Name' ) );
define( 'GLINT_VERSION', $glint_version );
define( 'GLINT_AUTHOR', $theme_info->get( 'Author' ) );
define( 'GLINT_AUTHOR_URI', $theme_info->get( 'AuthorURI' ) );

/*
 * include template helper function
 * @since 1.0.0
 * */
if ( file_exists( GLINT_INC . '/template-functions.php' ) && GLINT_INC . '/template-tags.php' ) {
    require_once GLINT_INC . '/template-functions.php';
    require_once GLINT_INC . '/template-tags.php';

    function glint_function( $instance ) {
        $new_instance = false;
        switch ( $instance ) {
        case ( "Functions" ):
            $new_instance = class_exists( 'Glint_Helper_Functions' ) ? Glint_Helper_Functions::getInstance() : false;
            break;
        case ( "Tags" ):
            $new_instance = class_exists( 'Glint_Tags' ) ? Glint_Tags::getInstance() : false;
            break;
        default:
            $new_instance = false;
            break;
        }
        return $new_instance;
    }
}

/**
 * Detect Homepage
 *
 * @return boolean value
 */
if( !function_exists('glint_detect_homepage') ){
    function glint_detect_homepage() {
        /*If front page is set to display a static page, get the URL of the posts page.*/
        $homepage_id = get_option( 'page_on_front' );

        /*current page id*/
        $current_page_id = ( is_page( get_the_ID() ) ) ? get_the_ID() : '';

        if( $homepage_id == $current_page_id ) {
            return true;
        } else {
            return false;
        }

    }
}

/*
 * Include codester helper functions
 * @since 1.0.0
 */
if ( file_exists( GLINT_INC . '/cs-framework-functions.php' ) ) {
    require_once GLINT_INC . '/cs-framework-functions.php';
}

/*
 * Include theme init file
 * @since 1.0.0
 */
if ( file_exists( GLINT_INC . '/class-glint-init.php' ) ) {
    require_once GLINT_INC . '/class-glint-init.php';
}
if ( file_exists( GLINT_INC . '/nav-menu-walker.php' ) ) {
    require_once GLINT_INC . '/nav-menu-walker.php';
}

if ( file_exists( GLINT_INC . '/plugins/tgma/activate.php' ) ) {
    require_once GLINT_INC . '/plugins/tgma/activate.php';
}

// Move comments textarea to bottom
function glint_move_comment_field_to_bottom( $fields ) {

    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter( 'comment_form_fields', 'glint_move_comment_field_to_bottom' );
remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );