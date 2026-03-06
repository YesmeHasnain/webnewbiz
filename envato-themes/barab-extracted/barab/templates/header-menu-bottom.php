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

    if( defined( 'CMB2_LOADED' )  ){
        if( !empty( barab_meta('page_breadcrumb_area') ) ) {
            $barab_page_breadcrumb_area  = barab_meta('page_breadcrumb_area');
        } else {
            $barab_page_breadcrumb_area = '1';
        }
    }else{
        $barab_page_breadcrumb_area = '1';
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
        if( $barab_page_breadcrumb_area == '1' ) {
            echo '<!-- Page title 2 -->';
            
            if( class_exists( 'ReduxFramework' ) ){
                $ex_class = '';
            }else{
                $ex_class = ' th-breadcumb';   
            } 
            echo '<div class="breadcumb-area">';
                echo '<div class="breadcumb-wrapper '. esc_attr($ex_class).'" id="breadcumbwrap">';
                    echo '<div class="container">';
                        echo '<div class="breadcumb-content">';
                            if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {
                                if( !empty( barab_meta('page_breadcrumb_settings') ) ) {
                                    if( barab_meta('page_breadcrumb_settings') == 'page' ) {
                                        $barab_page_title_switcher = barab_meta('page_title');
                                    } else {
                                        $barab_page_title_switcher = barab_opt('barab_page_title_switcher');
                                    }
                                } else {
                                    $barab_page_title_switcher = '1';
                                }
                            } else {
                                $barab_page_title_switcher = '1';
                            }

                            if( $barab_page_title_switcher ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    $barab_page_title_tag    = barab_opt('barab_page_title_tag');
                                }else{
                                    $barab_page_title_tag    = 'h1';
                                }

                                if( defined( 'CMB2_LOADED' )  ){
                                    if( !empty( barab_meta('page_title_settings') ) ) {
                                        $barab_custom_title = barab_meta('page_title_settings');
                                    } else {
                                        $barab_custom_title = 'default';
                                    }
                                }else{
                                    $barab_custom_title = 'default';
                                }

                                if( $barab_custom_title == 'default' ) {
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => esc_html( get_the_title( ) ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    ); 
                                } else {
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => esc_html( barab_meta('custom_page_title') ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    );
                                }

                            }
                            if( defined('CMB2_LOADED') || class_exists('ReduxFramework') ) {

                                if( barab_meta('page_breadcrumb_settings') == 'page' ) {
                                    $barab_breadcrumb_switcher = barab_meta('page_breadcrumb_trigger');
                                } else {
                                    $barab_breadcrumb_switcher = barab_opt('barab_enable_breadcrumb');
                                }

                            } else {
                                $barab_breadcrumb_switcher = '1';
                            }

                            if( $barab_breadcrumb_switcher == '1' && (  is_page() || is_page_template( 'template-builder.php' ) )) {
                                    barab_breadcrumbs(
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

        echo '<div class="breadcumb-area">';
            echo '<div class="breadcumb-wrapper '. esc_attr($breadcumb_bg_class . $ex_class).'">'; 
                echo '<div class="container z-index-common">';
                        echo '<div class="breadcumb-content">';
                            if( class_exists( 'ReduxFramework' )  ){
                                $barab_page_title_switcher  = barab_opt('barab_page_title_switcher');
                            }else{
                                $barab_page_title_switcher = '1';
                            }

                            if( $barab_page_title_switcher ){
                                if( class_exists( 'ReduxFramework' ) ){
                                    $barab_page_title_tag    = barab_opt('barab_page_title_tag');
                                }else{
                                    $barab_page_title_tag    = 'h1';
                                }
                                if( class_exists('woocommerce') && is_shop() ) {
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => wp_kses( woocommerce_page_title( false ), $allowhtml ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    );
                                }elseif ( is_archive() ){
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => wp_kses( get_the_archive_title(), $allowhtml ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    );
                                }elseif ( is_home() ){
                                    $barab_blog_page_title_setting = barab_opt('barab_blog_page_title_setting');
                                    $barab_blog_page_title_switcher = barab_opt('barab_blog_page_title_switcher');
                                    $barab_blog_page_custom_title = barab_opt('barab_blog_page_custom_title');
                                    if( class_exists('ReduxFramework') ){
                                        if( $barab_blog_page_title_switcher ){
                                            echo barab_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $barab_page_title_tag ),
                                                    "text"  => !empty( $barab_blog_page_custom_title ) && $barab_blog_page_title_setting == 'custom' ? esc_html( $barab_blog_page_custom_title) : esc_html__( 'Latest News', 'barab' ),
                                                    'class' => 'breadcumb-title text-anime-style-2',
                                                )
                                            );
                                        }
                                    }else{
                                        echo barab_heading_tag(
                                            array(
                                                "tag"   => "h1",
                                                "text"  => esc_html__( 'Latest News', 'barab' ),
                                                'class' => 'breadcumb-title text-anime-style-2',
                                            )
                                        );
                                    }
                                }elseif( is_search() ){
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => esc_html__( 'Search Result', 'barab' ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    );
                                }elseif( is_404() ){
                                    echo barab_heading_tag(
                                        array(
                                            "tag"   => esc_attr( $barab_page_title_tag ),
                                            "text"  => esc_html__( 'Error Page', 'barab' ),
                                            'class' => 'breadcumb-title text-anime-style-2',
                                        )
                                    );
                                }elseif( is_singular( 'product' ) ){
                                    $posttitle_position  = barab_opt('barab_product_details_title_position');
                                    $postTitlePos = false;
                                    if( class_exists( 'ReduxFramework' ) ){
                                        if( $posttitle_position && $posttitle_position != 'header' ){
                                            $postTitlePos = true;
                                        }
                                    }else{
                                        $postTitlePos = false;
                                    }

                                    if( $postTitlePos != true ){
                                        echo barab_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $barab_page_title_tag ),
                                                "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                                'class' => 'breadcumb-title text-anime-style-2',
                                            )
                                        );
                                    } else {
                                        if( class_exists( 'ReduxFramework' ) ){
                                            $barab_post_details_custom_title  = barab_opt('barab_product_details_custom_title');
                                        }else{
                                            $barab_post_details_custom_title = __( 'Shop Details','barab' );
                                        }

                                        if( !empty( $barab_post_details_custom_title ) ) {
                                            echo barab_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $barab_page_title_tag ),
                                                    "text"  => wp_kses( $barab_post_details_custom_title, $allowhtml ),
                                                    'class' => 'breadcumb-title text-anime-style-2',
                                                )
                                            );
                                        }
                                    }
                                }else{
                                    $posttitle_position  = barab_opt('barab_post_details_title_position');
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
                                        $posttitle_position  = barab_opt('barab_product_details_title_position');
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
                                        echo barab_heading_tag(
                                            array(
                                                "tag"   => esc_attr( $barab_page_title_tag ),
                                                "text"  => wp_kses( get_the_title( ), $allowhtml ),
                                                'class' => 'breadcumb-title text-anime-style-2',
                                            )
                                        );
                                    } else {
                                        if( class_exists( 'ReduxFramework' ) ){
                                            $barab_post_details_custom_title  = barab_opt('barab_post_details_custom_title');
                                        }else{
                                            $barab_post_details_custom_title = __( 'Blog Details','barab' );
                                        }

                                        if( !empty( $barab_post_details_custom_title ) ) {
                                            echo barab_heading_tag(
                                                array(
                                                    "tag"   => esc_attr( $barab_page_title_tag ),
                                                    "text"  => wp_kses( $barab_post_details_custom_title, $allowhtml ),
                                                    'class' => 'breadcumb-title text-anime-style-2',
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                            if( class_exists('ReduxFramework') ) {
                                $barab_breadcrumb_switcher = barab_opt( 'barab_enable_breadcrumb' );
                            } else {
                                $barab_breadcrumb_switcher = '1';
                            }
                            if( $barab_breadcrumb_switcher == '1' ) {
                                if(barab_breadcrumbs()){
                                echo '<div>';
                                    barab_breadcrumbs(
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