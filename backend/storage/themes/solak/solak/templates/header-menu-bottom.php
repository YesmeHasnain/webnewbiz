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

    if( defined( 'CMB2_LOADED' )  ){
        if( !empty( solak_meta('page_breadcrumb_area') ) ) {
            $solak_page_breadcrumb_area  = solak_meta('page_breadcrumb_area');
        } else {
            $solak_page_breadcrumb_area = '1';
        }
    }else{
        $solak_page_breadcrumb_area = '1';
    }
    
    $allowhtml = array(
        'p'         => array(
            'class'     => array()
        ),
        'span'      => array(
            'class'     => array(),
        ),
        'a'         => array(
            'href'      => array(),
            'title'     => array()
        ),
        'br'        => array(),
        'em'        => array(),
        'strong'    => array(),
        'b'         => array(),
        'sub'       => array(),
        'sup'       => array(),
    );
    
    if(  is_page() || is_page_template( 'template-builder.php' )  ) {
        if( $solak_page_breadcrumb_area == '1' ) {
            echo '<!-- Page title 2 -->';
            
            if( class_exists( 'ReduxFramework' ) ){
                $ex_class = '';
            }else{
                $ex_class = ' th-breadcumb';   
            }
            echo '<div class="breadcumb-banner new">';
                echo '<div class="breadcumb-wrapper '. esc_attr($ex_class).'" id="breadcumbwrap">';
                    echo '<div class="container">';
                        echo '<div class="breadcumb-content">';
                            if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {
                                if( !empty( solak_meta('page_breadcrumb_settings') ) ) {
                                    if( solak_meta('page_breadcrumb_settings') == 'page' ) {
                                        $solak_page_title_switcher = solak_meta('page_title');
                                    } else {
                                        $solak_page_title_switcher = solak_opt('solak_page_title_switcher');
                                    }
                                } else {
                                    $solak_page_title_switcher = '1';
                                }
                            } else {
                                $solak_page_title_switcher = '1';
                            }

                            if( $solak_page_title_switcher ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    $solak_page_title_tag    = solak_opt('solak_page_title_tag');
                                }else{
                                    $solak_page_title_tag    = 'h1';
                                }

                                if( defined( 'CMB2_LOADED' )  ){
                                    if( !empty( solak_meta('page_title_settings') ) ) {
                                        $solak_custom_title = solak_meta('page_title_settings');
                                    } else {
                                        $solak_custom_title = 'default';
                                    }
                                }else{
                                    $solak_custom_title = 'default';
                                }

                                if( $solak_custom_title == 'default' ) {
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => esc_html( get_the_title( ) ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                } else {
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => esc_html( solak_meta('custom_page_title') ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }

                            }
                            if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {

                                if( solak_meta('page_breadcrumb_settings') == 'page' ) {
                                    $solak_breadcrumb_switcher = solak_meta('page_breadcrumb_trigger');
                                } else {
                                    $solak_breadcrumb_switcher = solak_opt('solak_enable_breadcrumb');
                                }

                            } else {
                                $solak_breadcrumb_switcher = '1';
                            }

                            if( $solak_breadcrumb_switcher == '1' && (  is_page() || is_page_template( 'template-builder.php' ) )) {
                                    solak_breadcrumbs(
                                        array(
                                            'breadcrumbs_classes' => '',
                                        )
                                    );
                            }
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<!-- End of Page title -->';
            
        }
    } else {
        echo '<!-- Page title 3 -->';
         if( class_exists( 'ReduxFramework' ) ){
            $ex_class = '';
            if (class_exists( 'woocommerce' ) && is_shop()){
            $breadcumb_bg_class = 'custom-woo-class';
            }elseif(is_404()){
                $breadcumb_bg_class = 'custom-error-class';
            }elseif(is_search()){
                $breadcumb_bg_class = 'custom-search-class';
            }elseif(is_archive()){
                $breadcumb_bg_class = 'custom-archive-class';
            }else{
                $breadcumb_bg_class = '';
            }
        }else{
            $breadcumb_bg_class = ''; 
            $ex_class = ' th-breadcumb';     
        }

        echo '<div class="breadcumb-banner">';
            echo '<div class="breadcumb-wrapper '. esc_attr($breadcumb_bg_class . $ex_class).'">'; 
                echo '<div class="container z-index-common">';
                        echo '<div class="breadcumb-content">';
                            if( class_exists( 'ReduxFramework' )  ){
                                $solak_page_title_switcher  = solak_opt('solak_page_title_switcher');
                            }else{
                                $solak_page_title_switcher = '1';
                            }

                            if( $solak_page_title_switcher ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    $solak_page_title_tag    = solak_opt('solak_page_title_tag');
                                }else{
                                    $solak_page_title_tag    = 'h1';
                                }
                                if( class_exists('woocommerce') && is_shop() ) {
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => wp_kses( woocommerce_page_title( false ), $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }elseif ( is_archive() ){
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => wp_kses( get_the_archive_title(), $allowhtml ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }elseif ( is_home() ){
                                    $solak_blog_page_title_setting = solak_opt('solak_blog_page_title_setting');
                                    $solak_blog_page_title_switcher = solak_opt('solak_blog_page_title_switcher');
                                    $solak_blog_page_custom_title = solak_opt('solak_blog_page_custom_title');
                                    if( class_exists('ReduxFramework') ){
                                        if( $solak_blog_page_title_switcher ){
                                            echo solak_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $solak_page_title_tag ),
                                                    "text"  => !empty( $solak_blog_page_custom_title ) && $solak_blog_page_title_setting == 'custom' ? esc_html( $solak_blog_page_custom_title) : esc_html__( 'Latest News', 'solak' ),
                                                    'class' => 'breadcumb-title'
                                                )
                                            );
                                        }
                                    }else{
                                        echo solak_heading_tag(
                                            array(
                                                "tag"   => "h1",
                                                "text"  => esc_html__( 'Latest News', 'solak' ),
                                                'class' => 'breadcumb-title',
                                            )
                                        );
                                    }
                                }elseif( is_search() ){
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => esc_html__( 'Search Result', 'solak' ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }elseif( is_404() ){
                                    echo solak_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $solak_page_title_tag ),
                                            "text"  => esc_html__( 'Error Page', 'solak' ),
                                            'class' => 'breadcumb-title'
                                        )
                                    );
                                }elseif( is_singular( 'product' ) ){
                                    $posttitle_position  = solak_opt('solak_product_details_title_position');
                                    $postTitlePos = false;
                                    if( class_exists( 'ReduxFramework' ) ){
                                        if( $posttitle_position && $posttitle_position != 'header' ){
                                            $postTitlePos = true;
                                        }
                                    }else{
                                        $postTitlePos = false;
                                    }

                                    if( $postTitlePos != true ){
                                        echo solak_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $solak_page_title_tag ),
                                                "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    } else {
                                        if( class_exists( 'ReduxFramework' ) ){
                                            $solak_post_details_custom_title  = solak_opt('solak_product_details_custom_title');
                                        }else{
                                            $solak_post_details_custom_title = __( 'Shop Details','solak' );
                                        }

                                        if( !empty( $solak_post_details_custom_title ) ) {
                                            echo solak_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $solak_page_title_tag ),
                                                    "text"  => wp_kses( $solak_post_details_custom_title, $allowhtml ),
                                                    'class' => 'breadcumb-title'
                                                )
                                            );
                                        }
                                    }
                                }else{
                                    $posttitle_position  = solak_opt('solak_post_details_title_position');
                                    $postTitlePos = false;
                                    if( is_single() ){
                                        if( class_exists( 'ReduxFramework' ) ){
                                            if( $posttitle_position && $posttitle_position != 'header' ){
                                                $postTitlePos = true;
                                            }
                                        }else{
                                            $postTitlePos = false;
                                        }
                                    }
                                    if( is_singular( 'product' ) ){
                                        $posttitle_position  = solak_opt('solak_product_details_title_position');
                                        $postTitlePos = false;
                                        if( class_exists( 'ReduxFramework' ) ){
                                            if( $posttitle_position && $posttitle_position != 'header' ){
                                                $postTitlePos = true;
                                            }
                                        }else{
                                            $postTitlePos = false;
                                        }
                                    }

                                    if( $postTitlePos != true ){
                                        echo solak_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $solak_page_title_tag ),
                                                "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                                'class' => 'breadcumb-title'
                                            )
                                        );
                                    } else {
                                        if( class_exists( 'ReduxFramework' ) ){
                                            $solak_post_details_custom_title  = solak_opt('solak_post_details_custom_title');
                                        }else{
                                            $solak_post_details_custom_title = __( 'Blog Details','solak' );
                                        }

                                        if( !empty( $solak_post_details_custom_title ) ) {
                                            echo solak_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $solak_page_title_tag ),
                                                    "text"  => wp_kses( $solak_post_details_custom_title, $allowhtml ),
                                                    'class' => 'breadcumb-title'
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                            if( class_exists('ReduxFramework') ) {
                                $solak_breadcrumb_switcher = solak_opt( 'solak_enable_breadcrumb' );
                            } else {
                                $solak_breadcrumb_switcher = '1';
                            }
                            if( $solak_breadcrumb_switcher == '1' ) {
                                if(solak_breadcrumbs()){
                                echo '<div>';
                                    solak_breadcrumbs(
                                        array(
                                            'breadcrumbs_classes' => 'nav',
                                        )
                                    );
                                echo '</div>';
                                }
                            }
                        echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        echo '<!-- End of Page title -->';
    }