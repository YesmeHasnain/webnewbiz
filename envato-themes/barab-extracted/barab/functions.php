<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Include File
 *
 */

// Constants
require_once get_parent_theme_file_path() . '/inc/barab-constants.php';

//theme setup
require_once BARAB_DIR_PATH_INC . 'theme-setup.php';

//essential scripts
require_once BARAB_DIR_PATH_INC . 'essential-scripts.php';

// Woo Hooks
require_once BARAB_DIR_PATH_INC . 'woo-hooks/barab-woo-hooks.php';

// Woo Hooks Functions
require_once BARAB_DIR_PATH_INC . 'woo-hooks/barab-woo-hooks-functions.php';

// plugin activation
require_once BARAB_DIR_PATH_FRAM . 'plugins-activation/barab-active-plugins.php';

// theme dynamic css
require_once BARAB_DIR_PATH_INC . 'barab-commoncss.php';

// meta options
require_once BARAB_DIR_PATH_FRAM . 'barab-meta/barab-config.php';

// page breadcrumbs
require_once BARAB_DIR_PATH_INC . 'barab-breadcrumbs.php';

// sidebar register
require_once BARAB_DIR_PATH_INC . 'barab-widgets-reg.php';

//essential functions
require_once BARAB_DIR_PATH_INC . 'barab-functions.php';

// helper function
require_once BARAB_DIR_PATH_INC . 'wp-html-helper.php';

// Demo Data
require_once BARAB_DEMO_DIR_PATH . 'demo-import.php';

// pagination
require_once BARAB_DIR_PATH_INC . 'wp_bootstrap_pagination.php';

// hooks
require_once BARAB_DIR_PATH_HOOKS . 'hooks.php';

// hooks funtion
require_once BARAB_DIR_PATH_HOOKS . 'hooks-functions.php'; 

// Image main size
add_filter( 'big_image_size_threshold', '__return_false' );

// Woocoomerce cart count by ajax
add_action('wp_ajax_update_cart_count', 'update_cart_count');
add_action('wp_ajax_nopriv_update_cart_count', 'update_cart_count');

function update_cart_count() {
    if (class_exists('woocommerce')) {
        global $woocommerce;
        $product_id = intval($_POST['product_id']);
        $woocommerce->cart->add_to_cart($product_id);

        $cart_count = $woocommerce->cart->cart_contents_count;
        echo esc_html($cart_count);
    }
    wp_die();
}
