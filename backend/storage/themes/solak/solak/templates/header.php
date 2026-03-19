<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

    // Block direct access
    if( ! defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) && defined('ELEMENTOR_VERSION') ) {
        if( is_page() || is_page_template('template-builder.php') ) {
            $solak_post_id = get_the_ID();

            // Get the page settings manager
            $solak_page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

            // Get the settings model for current post
            $solak_page_settings_model = $solak_page_settings_manager->get_model( $solak_post_id );

            // Retrieve the color we added before
            $solak_header_style = $solak_page_settings_model->get_settings( 'solak_header_style' );
            $solak_header_builder_option = $solak_page_settings_model->get_settings( 'solak_header_builder_option' );

            if( $solak_header_style == 'header_builder'  ) {

                if( !empty( $solak_header_builder_option ) ) {
                    $solakheader = get_post( $solak_header_builder_option );
                    echo '<header class="header">';
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $solakheader->ID );
                    echo '</header>';
                }
            } else {
                // global options
                $solak_header_builder_trigger = solak_opt('solak_header_options');
                if( $solak_header_builder_trigger == '2' ) {
                    echo '<header>';
                    $solak_global_header_select = get_post( solak_opt( 'solak_header_select_options' ) );
                    $header_post = get_post( $solak_global_header_select );
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $header_post->ID );
                    echo '</header>';
                } else {
                    // wordpress Header
                    solak_global_header_option();
                }
            }
        } else {
            $solak_header_options = solak_opt('solak_header_options');
            if( $solak_header_options == '1' ) {
                solak_global_header_option();
            } else {
                $solak_header_select_options = solak_opt('solak_header_select_options');
                $solakheader = get_post( $solak_header_select_options );
                echo '<header class="header">';
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $solakheader->ID );
                echo '</header>';
            }
        }
    } else {
        solak_global_header_option();
    }