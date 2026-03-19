<?php

/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme ecohost for publication on Themeforest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */



/**
 * Include the TGM_Plugin_Activation class.
 */
require_once SOLAK_DIR_PATH_FRAM . 'plugins-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'solak_register_required_plugins' );
function solak_register_required_plugins() {

    /*
    * Array of plugin arrays. Required keys are name and slug.
    * If the source is NOT from the .org repo, then source is also required.
    */

    $selected_options   =   get_option('et_selected_solak_demo_plugin');

    if($selected_options == 'with_woocommerce'){
        $plugins = array(

            array(
                'name'                  => esc_html__( 'Solak Core', 'solak' ),
                'slug'                  => 'solak-core',
                'version'               => '1.0',
                'source'                => 'https://wordpress.themehour.net/solak/demo/solak-core.zip',
                'required'              => true,
            ),

            array(
                'name'                  => esc_html__( 'One Click Demo Importer', 'solak' ),
                'slug'                  => 'one-click-demo-import',
                'required'              => true,
            ),

            array(
                'name'      => esc_html__( 'Elementor', 'solak' ),
                'slug'      => 'elementor',
                'version'   => '',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'Redux Framework', 'solak' ),
                'slug'      => 'redux-framework',
                'version'   => '',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'CMB2', 'solak' ),
                'slug'      => 'cmb2',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'Contact Form 7', 'solak' ),
                'slug'      => 'contact-form-7',
                'version'   => '',
                'required'  => true,
            ),
            array(
                'name'      => esc_html__( 'WooCommerce', 'solak' ),
                'slug'      => 'woocommerce',
                'version'   => '',
                'required'  => true,
            ),
            array(
                'name'      => esc_html__( 'WPC Smart Quick View for WooCommerce', 'solak' ),
                'slug'      => 'woo-smart-quick-view',
                'version'   => '',
                'required'  => true,
            ),
            array(
                'name'      => esc_html__( 'WPC Smart Wishlist for WooCommerce', 'solak' ),
                'slug'      => 'woo-smart-wishlist',
                'version'   => '',
                'required'  => true,
            ),

        );
        
    }else{
        $plugins = array(

            array(
                'name'                  => esc_html__( 'Solak Core', 'solak' ),
                'slug'                  => 'solak-core',
                'version'               => '1.0',
                'source'                => 'https://wordpress.themeholy.com/solak/demo/solak-core.zip',
                'required'              => true,
            ),

            array(
                'name'                  => esc_html__( 'One Click Demo Importer', 'solak' ),
                'slug'                  => 'one-click-demo-import',
                'required'              => true,
            ),

            array(
                'name'      => esc_html__( 'Elementor', 'solak' ),
                'slug'      => 'elementor',
                'version'   => '',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'Redux Framework', 'solak' ),
                'slug'      => 'redux-framework',
                'version'   => '',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'CMB2', 'solak' ),
                'slug'      => 'cmb2',
                'required'  => true,
            ),

            array(
                'name'      => esc_html__( 'Contact Form 7', 'solak' ),
                'slug'      => 'contact-form-7',
                'version'   => '',
                'required'  => true,
            ),

        ); 
    }

    $config = array(
        'id'           => 'solak',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );

    tgmpa( $plugins, $config );
}