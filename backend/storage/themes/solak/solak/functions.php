<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$selected_options   =   get_option('et_selected_solak_demo_plugin');

/**
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/solak-constants.php';

//theme setup
require_once SOLAK_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once SOLAK_DIR_PATH_INC . 'essential-scripts.php';

if($selected_options == 'with_woocommerce'){
    // Woo Hooks
    require_once SOLAK_DIR_PATH_INC . 'woo-hooks/solak-woo-hooks.php';

    // Woo Hooks Functions
    require_once SOLAK_DIR_PATH_INC . 'woo-hooks/solak-woo-hooks-functions.php';
}
// plugin activation
require_once SOLAK_DIR_PATH_FRAM . 'plugins-activation/solak-active-plugins.php';

// theme dynamic css
require_once SOLAK_DIR_PATH_INC . 'solak-commoncss.php';

// meta options
require_once SOLAK_DIR_PATH_FRAM . 'solak-meta/solak-config.php';

// page breadcrumbs
require_once SOLAK_DIR_PATH_INC . 'solak-breadcrumbs.php';

// sidebar register
require_once SOLAK_DIR_PATH_INC . 'solak-widgets-reg.php';

//essential functions
require_once SOLAK_DIR_PATH_INC . 'solak-functions.php';

// helper function
require_once SOLAK_DIR_PATH_INC . 'wp-html-helper.php';

// Demo Data
require_once SOLAK_DEMO_DIR_PATH . 'demo-import.php';

// pagination
require_once SOLAK_DIR_PATH_INC . 'wp_bootstrap_pagination.php';

// solak options
require_once SOLAK_DIR_PATH_FRAM . 'solak-options/solak-options.php';

// hooks
require_once SOLAK_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once SOLAK_DIR_PATH_HOOKS . 'hooks-functions.php'; 

add_action('wp_ajax_update_cart_count', 'update_cart_count');
add_action('wp_ajax_nopriv_update_cart_count', 'update_cart_count');

function update_cart_count() {
    if (class_exists('woocommerce')) {
        global $woocommerce;
        $product_id = intval($_POST['product_id']);
        $woocommerce->cart->add_to_cart($product_id); // Add the product to the cart

        $cart_count = $woocommerce->cart->cart_contents_count;
        echo esc_html($cart_count);
    }
    wp_die();
}

// Code with & without woocommerce
if ( is_admin() ) {
    include_once get_template_directory() . '/inc/solak-dashboard/et-admin.php';
}

function solak_enqueue_scripts() {
    wp_enqueue_style(
        'solak-admin-styles',
        get_template_directory_uri() . '/inc/solak-dashboard/css/admin-pages.css',
        array(),
        time()
    );
}
add_action( 'admin_enqueue_scripts', 'solak_enqueue_scripts' );

function solak_dashboard_submenu_page() {

    if(!function_exists('solak_init')) {
        add_menu_page(
            esc_html__( 'ThemeHoly', 'solak' ),
            esc_html__( 'ThemeHoly', 'solak' ),
            'manage_options',
            'solak-dashboard',
            '',
            get_template_directory_uri() . '/assets/img/favicon.png',
            2
        );
    }
    
    add_submenu_page(
        'solak-dashboard',
        esc_html__( 'Dashboard', 'solak' ),
        esc_html__( 'Dashboard', 'solak' ),
        'manage_options',
        'solak-dashboard',
        'solak_screen_welcome'
    );
}
add_action( 'admin_menu', 'solak_dashboard_submenu_page' );

function solak_screen_welcome() {
    echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
    require_once get_parent_theme_file_path( '/inc/solak-dashboard/welcome.php' );
}

function solak_plugins_submenu_page() {

    add_submenu_page(
        'solak-dashboard',
        esc_html__( 'Install Plugins', 'solak' ),
        esc_html__( 'Install Plugins', 'solak' ),
        'manage_options',
        'solak-admin-plugins',
        'solak_screen_plugin'
    );

}
add_action( 'admin_menu', 'solak_plugins_submenu_page' );

function solak_screen_plugin() {
    echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
    require_once get_parent_theme_file_path( '/inc/solak-dashboard/install-plugins.php' );
}
