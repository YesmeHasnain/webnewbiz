<?php
/**
 * Plugin Name: WebnewBiz Builder
 * Plugin URI: https://webnewbiz.com
 * Description: AI-powered website builder extending Elementor with custom widgets, templates, and one-click site generation.
 * Version: 1.0.0
 * Author: WebnewBiz
 * Author URI: https://webnewbiz.com
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: webnewbiz-builder
 * Elementor tested up to: 3.25.0
 */

if (!defined('ABSPATH')) exit;

define('WNB_VERSION', '1.0.0');
define('WNB_PREFIX', 'wnb');
define('WNB_DIR', plugin_dir_path(__FILE__));
define('WNB_URL', plugins_url('/', __FILE__));
define('WNB_ELEMENTOR_MIN_VERSION', '3.5.0');

/**
 * Check minimum requirements before loading.
 */
function wnb_check_requirements(): bool {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-warning"><p>';
            echo '<strong>WebnewBiz Builder</strong> requires <strong>Elementor</strong> to be installed and activated.';
            echo '</p></div>';
        });
        return false;
    }

    if (!version_compare(ELEMENTOR_VERSION, WNB_ELEMENTOR_MIN_VERSION, '>=')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-warning"><p>';
            printf(
                '<strong>WebnewBiz Builder</strong> requires Elementor version %s or greater. You are using %s.',
                WNB_ELEMENTOR_MIN_VERSION,
                ELEMENTOR_VERSION
            );
            echo '</p></div>';
        });
        return false;
    }

    return true;
}

/**
 * Initialize the plugin after all plugins are loaded.
 */
add_action('plugins_loaded', function () {
    if (!wnb_check_requirements()) {
        return;
    }

    require_once WNB_DIR . 'includes/class-plugin.php';
    \WebnewBiz\Builder\Plugin::get_instance();
});
