<?php
/**
 * Plugin Name: AI Website Copilot
 * Plugin URI: https://webnewbiz.com
 * Description: AI-powered website editor. Chat with AI to edit text, styles, images, add sections, manage SEO, and more - all through natural language.
 * Version: 3.5.1
 * Author: WebNewBiz
 * Author URI: https://webnewbiz.com
 * Text Domain: ai-copilot
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if (!defined('ABSPATH')) exit;

define('AICOPILOT_VERSION', '3.5.1');
define('AICOPILOT_PATH', plugin_dir_path(__FILE__));
define('AICOPILOT_URL', plugin_dir_url(__FILE__));

// Load includes
require_once AICOPILOT_PATH . 'includes/class-settings.php';
require_once AICOPILOT_PATH . 'includes/class-tools.php';
require_once AICOPILOT_PATH . 'includes/class-history.php';
require_once AICOPILOT_PATH . 'includes/class-image-gen.php';
require_once AICOPILOT_PATH . 'includes/class-executor.php';
require_once AICOPILOT_PATH . 'includes/class-claude-api.php';
require_once AICOPILOT_PATH . 'includes/class-chat.php';
require_once AICOPILOT_PATH . 'includes/class-admin.php';

// Initialize
add_action('plugins_loaded', function () {
    AICopilot_Settings::init();
    AICopilot_Admin::init();
    AICopilot_Chat::init();
});
