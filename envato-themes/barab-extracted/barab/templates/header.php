<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

    // Block direct access
    if( ! defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ) {
        if( is_page() || is_page_template('template-builder.php') ) {
            $barab_post_id = get_the_ID();

            // Get the page settings manager
            $barab_page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $barab_page_settings_model = $barab_page_settings_manager->get_model( $barab_post_id );

            // Retrieve the color we added before
            $barab_header_style = $barab_page_settings_model->get_settings( 'barab_header_style' );
            $barab_header_builder_option = $barab_page_settings_model->get_settings( 'barab_header_builder_option' );

            if( $barab_header_style == 'header_builder'  ) {

                if( !empty( $barab_header_builder_option ) ) {
                    $barabheader = get_post( $barab_header_builder_option );
                    echo '<header class="header">';
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $barabheader->ID );
                    echo '</header>';
                }
            } else {
                // global options
                $barab_header_builder_trigger = barab_opt('barab_header_options');
                if( $barab_header_builder_trigger == '2' ) {
                    echo '<header>';
                    $barab_global_header_select = get_post( barab_opt( 'barab_header_select_options' ) );
                    $header_post = get_post( $barab_global_header_select );
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_post->ID );
                    echo '</header>';
                } else {
                    // wordpress Header
                    barab_global_header_option();
                }
            }
        } else {
            $barab_header_options = barab_opt('barab_header_options');
            if( $barab_header_options == '1' ) {
                barab_global_header_option();
            } else {
                $barab_header_select_options = barab_opt('barab_header_select_options');
                $barabheader = get_post( $barab_header_select_options );
                echo '<header class="header">';
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $barabheader->ID );
                echo '</header>';
            }
        }
    } else {
        barab_global_header_option();
    }