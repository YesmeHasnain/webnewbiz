<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

    // Block direct access
    if( !defined( 'ABSPATH' ) ){
        exit();
    }

    if( class_exists( 'ReduxFramework' ) ) {
        $solak404title     = solak_opt( 'solak_error_title' ); 
        $solak404description  = solak_opt( 'solak_error_description' );
        $solak404btntext      = solak_opt( 'solak_error_btn_text' );
    } else {
        $solak404title     = __( 'Opp’s That Page Can’t be Found', 'solak' );
        $solak404description  = __( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'solak' );
        $solak404btntext      = __( 'Back To Home', 'solak');

    }

    // get header //
    get_header(); 

    if(!empty(solak_opt('solak_error_bg', 'url' ) )){
        $bg_url = solak_opt('solak_error_bg', 'url' );
    }else{
        $bg_url = '';
    }
    
        echo '<div class="space">';  
            echo '<div class="container">';
                echo '<div class="error-img">';
                    if(!empty(solak_opt('solak_error_img', 'url' ) )){
                        echo '<img src="'.esc_url( solak_opt('solak_error_img', 'url' ) ).'" alt="'.esc_attr__('404 image', 'solak').'">';
                    }else{
                        echo '<img src="'.get_template_directory_uri().'/assets/img/error.svg" alt="'.esc_attr__('404 image', 'solak').'">';
                    }
                echo '</div>';
                echo '<div class="error-content">';
                    if(!empty($solak404title)){
                        echo '<h2 class="error-title">'.wp_kses_post( $solak404title ).'</h2>';
                    }
                    if(!empty($solak404description)){
                        echo '<p class="error-text">'.esc_html( $solak404description ).'</p>';
                    }
                    echo '<a href="'.esc_url( home_url('/') ).'" class="th-btn error-btn"><i class="fal fa-home me-2"></i>'.esc_html( $solak404btntext ).'</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';

    //footer
    get_footer();