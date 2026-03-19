<?php
/**
 * Plugin Name: Amedea
 * Description: Unique Collection of Elementor Elements
 * Plugin URI: https://amedea.pro/limited-version/
 * Author: Amedea
 * Version: 0.0.3.2
 * Author URI: https://amedea.pro/go/?p=amedea
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: amedea
 * Elementor tested up to: 3.28.0
 */
 
/**
 * Copyright (c) 2024 Moskva Yigit | Krasota Iskusstva
 * All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AMEDEA__FILE', __FILE__ );
define( 'AMEDEA__PATH', plugin_dir_path( AMEDEA__FILE ) );
define( 'AMEDEA__VERSION', '0.3.2' );

/**
 * Main venus wp Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 */

final class AMEDEA {
	
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
	
	/**
	 * Minimum Amedea:Core Version
	 *
	 * @since 1.0.0
	 * @var string Amedea:Core version required to run the plugin.
	 */
	const MINIMUM_AMEDEACORE_VERSION = '0.0.3.3';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '8.1';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'amedea__i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'amedea__init' ) );
		
		//Init Admin Menu
		add_action( 'admin_menu', array( $this, 'amedea__admin_menu' ) );
		
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function amedea__i18n() {
		load_plugin_textdomain( 'amedea', false, dirname( __FILE__ ) . '/languages' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function amedea__init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'amedea__admin_notice' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'amedea__minimum_elementor_version' ) );
			return;
		}
		// Check if Amedea:Core installed and activated
		if(!in_array('amedea/amedea.php', apply_filters('active_plugins', get_option('active_plugins')))){
			add_action( 'admin_notices', array( $this, 'amedea__minimum_core_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'amedea__minimum_php_version' ) );
			return;
		}
		
		// Check verify
		if (null==get_option('__amedea__isactivated')) {
			add_action( 'admin_notices', array( $this, 'amedea__verify' ) );	
		}
		
		//Multinetwork
		if ( is_multisite() ) {
			add_action( 'admin_notices', array( $this, 'amedea__multinetwork' ) );
		}
		
		// Require configuration file
		require_once( 'config.php' );

	}

	/**
	 * Admin notice
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function amedea__admin_notice() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'amedea' ) . '</strong>'
		);

		$html_message = sprintf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );
	}

	/**
	 * Admin notice
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function amedea__minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'amedea' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		$html_message = sprintf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );
	}
	
	/**
	 * Admin notice
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function amedea__minimum_core_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Amedea:Core 3: Required Amedea:Core version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater installed and activated.', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong>' . esc_html__( 'Core', 'amedea' ) . '</strong>',
			self::MINIMUM_AMEDEACORE_VERSION
		);

		$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );
	}

	/**
	 * Admin notice
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function amedea__minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'amedea' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		$html_message = sprintf( '<div class="notice notice-warning"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );
	}
	
	/**
	* Admin notice
	*
	* @since 1.0.0
	* @access public
	*/
	public function amedea__verify() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( 'If you enjoy using "%1$s", you can consider to "%2$s" with 200+ widgets, updates and rapid support', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong><a href="https://amedea.pro/go/?p=pro" target="_blank">' . esc_html__( 'GO PRO', 'amedea' ) . '</a></strong>'
		);
			
		$html_message = sprintf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );

	}
	
	/**
	* Admin notice
	*
	* Warning when the site doesn't have a minimum required PHP version.
	*
	* @since 1.0.0
	* @access public
	*/
	public function amedea__multinetwork() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" cannot run under WordPress Network due to %2$s. Please, disable the feature if you would like to use the plugin.', 'amedea' ),
			'<strong>' . esc_html__( 'Amedea', 'amedea' ) . '</strong>',
			'<strong><a href="https://amedea.pro/licenses/" target="_blank">' . esc_html__( 'license issue', 'amedea' ) . '</a></strong>'
		);
			
		$html_message = sprintf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
		echo wp_kses_post( $html_message );

	}	
	
	
	/**
	* Settings
	*
	* Registers a new settings page under Settings.
	*
	* @since 1.0.0
	* @access public
	*/
	public function amedea__admin_menu() {
		add_submenu_page(
			'options.php',
			esc_html__( 'Settings', 'amedea' ),
			esc_html__( 'Settings', 'amedea' ),
			'manage_options',
			'amedea',
			array(
				$this,
				'settings_page'
			)
		);
	}
	
	/**
	* Callback
	*
	* Settings page display callback.
	*
	* @since 1.0.0
	* @access public
	*/
	public function settings_page() {

		// Require configuration file
		require_once( 'welcome.php' );
		new amedea__welcome();
		
	}

}

/**
* Plugin settings
*
* Creates a link to the plugin details page
*
* @since 1.0.0
* @access public
*/
function amedea__settings( $links ) {
	
	if (null==get_option('__amedea__isactivated')) {
		$getPro = esc_html__( 'Limited Version', 'amedea' );
		$links[] = '' . $getPro . '';
	}
	return $links;
}

//Init Settings
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'amedea__settings');

new AMEDEA();
