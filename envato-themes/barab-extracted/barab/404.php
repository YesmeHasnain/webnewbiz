<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */
 
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}

    if( class_exists( 'ReduxFramework' ) ) {
        $barab404title     = barab_opt( 'barab_error_title' ); 
        $barab404description  = barab_opt( 'barab_error_description' );
        $barab404btntext      = barab_opt( 'barab_error_btn_text' );
    } else {
        $barab404title     = __( 'Error 404', 'barab' );
        $barab404description  = __( 'Oops! The page you’re looking for doesn’t exist', 'barab' );
        $barab404btntext      = __( 'Back To Home', 'barab');

    }
 
    // get header //
    get_header(); 

    if(!empty(barab_opt('barab_error_img', 'url' ) )){
        $bg_url = barab_opt('barab_error_img', 'url' );
    }else{
        $bg_url = '';
    }

     echo '<section class="space">'; 
        echo '<div class="container">';
            echo '<div class="error-img">';
                if(!empty(barab_opt('barab_error_img', 'url' ) )){
                    echo '<img src="'.esc_url( barab_opt('barab_error_img', 'url' ) ).'" alt="'.esc_attr__('404 image', 'barab').'">';
                }else{
                    echo '<img src="'.get_template_directory_uri().'/assets/img/error.png" alt="'.esc_attr__('404 image', 'barab').'">';
                }
            echo '</div>';
            echo '<div class="error-content">';
                if(!empty($barab404title)){
                    echo '<h2 class="error-title fw-semibold">'.wp_kses_post( $barab404title ).'</h2>';
                }
                if(!empty($barab404description)){
                    echo '<p class="error-text">'.esc_html( $barab404description ).'</p>';
                }
                echo '<a href="'.esc_url( home_url('/') ).'" class="th-btn style-radius style2 error-btn">'.esc_html( $barab404btntext ).'</a>';
            echo '</div>';
        echo '</div>';
    echo '</section>';

    //footer
    get_footer();