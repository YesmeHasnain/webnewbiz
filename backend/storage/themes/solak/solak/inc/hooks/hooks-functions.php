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


    // preloader hook function
    if( ! function_exists( 'solak_preloader_wrap_cb' ) ) {
        function solak_preloader_wrap_cb() {
            $preloader_display           =  solak_opt('solak_display_preloader');
            $solak_display_preloader_btn =  solak_opt('solak_display_preloader_btn');
            $solak_preloader_btn_text    =  solak_opt('solak_preloader_btn_text');
            if(!empty(solak_opt('solak_preloader_logo', 'url' ) )){
                $solak_preloader_logo = solak_opt('solak_preloader_logo', 'url' );
            }else{
                $solak_preloader_logo = '';
            }

            if( class_exists('ReduxFramework') ){
                if( $preloader_display ){
                    echo '<div id="preloader" class="preloader">';
                        if( $solak_display_preloader_btn ){
                            if( !empty( $solak_preloader_btn_text ) ){
                                echo '<button class="th-btn style1 preloaderCls">'.esc_html( $solak_preloader_btn_text ).'</button>';
                            }
                        }
                        echo '<div class="preloader-inner">';
                            if(!empty(solak_opt('solak_preloader_logo', 'url' ) )){
                                echo '<img src="'.esc_url( solak_opt('solak_preloader_logo', 'url' ) ).'" alt="'.esc_attr__('Logo', 'solak').'">';
                            }
                            echo '<div class="loader"></div>';
                        echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="preloader">';
                    echo '<button class="th-btn style1 preloaderCls">'.esc_html__( 'Cancel Preloader', 'solak' ).'</button>';
                    echo '<div class="preloader-inner">
                        <div class="loader"></div>
                    </div>';
                echo '</div>';
            }

        }
    }

    // Header Hook function
    if( !function_exists('solak_header_cb') ) { 
        function solak_header_cb( ) {
            get_template_part('templates/header');
        }
    }

    // Header Hook function
    if( !function_exists('solak_breadcrumb_cb') ) { 
        function solak_breadcrumb_cb( ) {
            get_template_part('templates/header-menu-bottom');
        }
    }

    // back top top hook function
    if( ! function_exists( 'solak_back_to_top_cb' ) ) {
        function solak_back_to_top_cb( ) {
            $backtotop_trigger = solak_opt('solak_display_bcktotop');
            if( class_exists( 'ReduxFramework' ) ) {
                if( $backtotop_trigger ) {
            	?>
                    <div class="scroll-top">
                        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
                            </path>
                        </svg>
                    </div>
                <?php 
                }
            }

        }
    }

    // Blog Start Wrapper Function
    if( !function_exists('solak_blog_start_wrap_cb') ) {
        function solak_blog_start_wrap_cb() { ?>
            <section class="th-blog-wrapper space-top space-extra-bottom">
                <div class="container">
                    <div class="row">
        <?php }
    }

    // Blog End Wrapper Function
    if( !function_exists('solak_blog_end_wrap_cb') ) {
        function solak_blog_end_wrap_cb() {?>
                    </div>
                </div>
            </section>
        <?php }
    }

    // Blog Column Start Wrapper Function
    if( !function_exists('solak_blog_col_start_wrap_cb') ) {
        function solak_blog_col_start_wrap_cb() {
           
                //Redux option work
                if( class_exists('ReduxFramework') ) {
                    $solak_blog_sidebar = solak_opt('solak_blog_sidebar');
                }else{
                    $solak_blog_sidebar = '1';
                }

                if( class_exists('ReduxFramework') ) {
                    // $solak_blog_sidebar = solak_opt('solak_blog_sidebar');
                    if( $solak_blog_sidebar == '2' && is_active_sidebar('solak-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7  order-lg-last">';
                    } elseif( $solak_blog_sidebar == '3' && is_active_sidebar('solak-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }

                } else {
                    if( is_active_sidebar('solak-blog-sidebar') ) {
                        echo '<div class="col-xxl-8 col-lg-7">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }
                }
                

        }
    }
    // Blog Column End Wrapper Function
    if( !function_exists('solak_blog_col_end_wrap_cb') ) {
        function solak_blog_col_end_wrap_cb() {
            echo '</div>';
        }
    }

    // Blog Sidebar
    if( !function_exists('solak_blog_sidebar_cb') ) {
        function solak_blog_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_blog_sidebar = solak_opt('solak_blog_sidebar');
            } else {
                $solak_blog_sidebar = 2;
                
            }
            if( $solak_blog_sidebar != 1 && is_active_sidebar('solak-blog-sidebar') ) {
                // Sidebar
                get_sidebar();
            }
        }
    }


    if( !function_exists('solak_blog_details_sidebar_cb') ) {
        function solak_blog_details_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_blog_single_sidebar = solak_opt('solak_blog_single_sidebar');
            } else {
                $solak_blog_single_sidebar = 4;
            }
            if( $solak_blog_single_sidebar != 1 ) {
                // Sidebar
                get_sidebar();
            }

        }
    }

    // Blog Pagination Function
    if( !function_exists('solak_blog_pagination_cb') ) {
        function solak_blog_pagination_cb( ) {
            get_template_part('templates/pagination');
        }
    }

    // Blog Content Function
    if( !function_exists('solak_blog_content_cb') ) {
        function solak_blog_content_cb( ) {

            //Redux option work
            if( class_exists('ReduxFramework') ) {
                $solak_blog_grid = solak_opt('solak_blog_grid');  
            }else{
                $solak_blog_grid = '1';
            }

            if( $solak_blog_grid == '1' ) {
                $solak_blog_grid_class = 'col-lg-12';
            } elseif( $solak_blog_grid == '2' ) {
                $solak_blog_grid_class = 'col-sm-6';
            } else {
                $solak_blog_grid_class = 'col-lg-4 col-sm-6';
            }

            echo '<div class="row">';
                if( have_posts() ) {
                    while( have_posts() ) {
                        the_post();
                        echo '<div class="'.esc_attr($solak_blog_grid_class).'">';
                            get_template_part('templates/content',get_post_format());
                        echo '</div>';
                    }
                    wp_reset_postdata();
                } else{
                    get_template_part('templates/content','none');
                }
            echo '</div>';
        }
    }

    // footer content Function
    if( !function_exists('solak_footer_content_cb') ) {
        function solak_footer_content_cb( ) {

            if( class_exists('ReduxFramework') && did_action( 'elementor/loaded' )  ){
                if( is_page() || is_page_template('template-builder.php') ) {
                    $post_id = get_the_ID();

                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                    // Get the settings model for current post
                    $page_settings_model = $page_settings_manager->get_model( $post_id );

                    // Retrieve the Footer Style
                    $footer_settings = $page_settings_model->get_settings( 'solak_footer_style' );

                    // Footer Local
                    $footer_local = $page_settings_model->get_settings( 'solak_footer_builder_option' );

                    // Footer Enable Disable
                    $footer_enable_disable = $page_settings_model->get_settings( 'solak_footer_choice' );

                    if( $footer_enable_disable == 'yes' ){
                        if( $footer_settings == 'footer_builder' ) {
                            // local options
                            $solak_local_footer = get_post( $footer_local );
                            echo '<footer>';
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $solak_local_footer->ID );
                            echo '</footer>';
                        } else {
                            // global options
                            $solak_footer_builder_trigger = solak_opt('solak_footer_builder_trigger');
                            if( $solak_footer_builder_trigger == 'footer_builder' ) {
                                echo '<footer>';
                                $solak_global_footer_select = get_post( solak_opt( 'solak_footer_builder_select' ) );
                                $footer_post = get_post( $solak_global_footer_select );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                                echo '</footer>';
                            } else {
                                // wordpress widgets
                                solak_footer_global_option();
                            }
                        }
                    }
                } else {
                    // global options
                    $solak_footer_builder_trigger = solak_opt('solak_footer_builder_trigger');
                    if( $solak_footer_builder_trigger == 'footer_builder' ) {
                        echo '<footer>';
                        $solak_global_footer_select = get_post( solak_opt( 'solak_footer_builder_select' ) );
                        $footer_post = get_post( $solak_global_footer_select );
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                        echo '</footer>';
                    } else {
                        // wordpress widgets
                        solak_footer_global_option();
                    }
                }
            } else { ?>
                <div class="footer-layout1 footer-sitcky">
                    <div class="copyright-wrap bg-theme2">
                        <div class="container">
                            <p class="copyright-text text-center"><?php echo sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a href="%s"> %s </a> All Rights Reserved.', date('Y'), esc_url('#'), esc_html__( 'Solak.','solak') ); ?></p> 
                        </div>
                    </div>
                </div>
            <?php }

        }
    }

    // blog details wrapper start hook function
    if( !function_exists('solak_blog_details_wrapper_start_cb') ) {
        function solak_blog_details_wrapper_start_cb( ) {
            echo '<section class="th-blog-wrapper blog-details space-top space-extra-bottom">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'solak-blog-sidebar' ) ){
                        $solak_gutter_class = 'gx-60';
                    }else{
                        $solak_gutter_class = '';
                    }
                    // echo '<div class="row './/esc_attr( $solak_gutter_class ).'">';
                    echo '<div class="row">';
        }
    }

    // blog details column wrapper start hook function
    if( !function_exists('solak_blog_details_col_start_cb') ) {
        function solak_blog_details_col_start_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_blog_single_sidebar = solak_opt('solak_blog_single_sidebar');
                if( $solak_blog_single_sidebar == '2' && is_active_sidebar('solak-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7 order-lg-last">';
                } elseif( $solak_blog_single_sidebar == '3' && is_active_sidebar('solak-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('solak-blog-sidebar') ) {
                    echo '<div class="col-xxl-8 col-lg-7">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }

    // blog details post meta hook function
    if( !function_exists('solak_blog_post_meta_cb') ) { 
        function solak_blog_post_meta_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_display_post_author      =  solak_opt('solak_display_post_author');
                $solak_display_post_date      =  solak_opt('solak_display_post_date');
                $solak_display_post_cate   =  solak_opt('solak_display_post_cate');
                $solak_display_post_comments      =  solak_opt('solak_display_post_comments');
                $solak_display_post_min      =  solak_opt('solak_display_post_min');
                $solak_post_read_min_text      =  solak_opt('solak_post_read_min_text');
                $solak_post_read_min_count      =  solak_opt('solak_post_read_min_count');
            } else {
                $solak_display_post_author      = '1';
                $solak_display_post_date      = '1';
                $solak_display_post_cate   = '0';
                $solak_display_post_comments      = '0'; 
                $solak_display_post_min      = '1'; 
                $solak_post_read_min_text      = 'min read'; 
                $solak_post_read_min_count      = '150'; 
            }

                echo '<div class="blog-meta">';
                    if( $solak_display_post_author ){
                        echo '<a class="author" href="'.esc_url( get_author_posts_url( get_the_author_meta('ID') ) ).'"><i class="fa-light fa-user"></i>'. esc_html__('By ', 'solak') .esc_html( ucwords( get_the_author() ) ).'</a>';
                    }
                    if( $solak_display_post_date ){
                        echo ' <a href="'.esc_url( solak_blog_date_permalink() ).'"><i class="fa-light fa-calendar"></i>'.esc_html( get_the_date() ).'</a>';
                    }
                    if( $solak_display_post_cate ){
                        $categories = get_the_category(); 
                        if(!empty($categories)){
                        echo '<a href="'.esc_url( get_category_link( $categories[0]->term_id ) ).'"><i class="fa-regular fa-tag"></i>'.esc_html( $categories[0]->name ).'</a>';
                        }
                    }
                    if( $solak_display_post_comments ){
                        ?>
                        <a href="#"><i class="fa-regular fa-comment"></i>
                            <?php 
                                echo get_comments_number(); 
                                if(get_comments_number() == 1){
                                    echo esc_html__(' Comment', 'solak'); 
                                }else{
                                    echo esc_html__(' Comments', 'solak'); 
                                }
                                ?></a>
                        <?php
                    }
                    if( $solak_display_post_min ){
                        if (function_exists('solak_get_reading_time')) {
                            echo solak_get_reading_time(get_the_ID());
                        }
                    }
                echo '</div>';
        }
    }

    // Blog post reading time count
    function solak_get_reading_time($post_id) {
        if (class_exists('ReduxFramework')) {
            $solak_post_read_min_text = !empty(solak_opt('solak_post_read_min_text')) 
                                        ? sanitize_text_field(solak_opt('solak_post_read_min_text')) 
                                        : esc_html__('min read', 'solak');
            $words_per_minute = !empty(solak_opt('solak_post_read_min_count')) 
                                ? (int) solak_opt('solak_post_read_min_count') 
                                : 150;
        } else {
            $solak_post_read_min_text = esc_html__('min read', 'solak');
            $words_per_minute = 150;
        }
        
        // Get the content of the post
        $content = get_post_field('post_content', $post_id);
        
        // Count the number of words
        $word_count = str_word_count(strip_tags($content));
        
        // Calculate the reading time
        $reading_time = ceil($word_count / $words_per_minute);
        
        // Return the estimated reading time
        return '<a href="#"><i class="fa-regular fa-clock"></i>'.esc_html($reading_time .' '. $solak_post_read_min_text).'</a>';
        
    }

    // blog details share options hook function
    if( !function_exists('solak_blog_details_share_options_cb') ) {
        function solak_blog_details_share_options_cb( ) {

            if( class_exists('ReduxFramework') ) {
                $solak_post_details_share = solak_opt('solak_post_details_share_options');
            } else {
                $solak_post_details_share = "0";
            } 

            if( function_exists( 'solak_social_sharing_buttons' ) ){
                if( $solak_post_details_share ){
                    echo '<div class="col-sm-auto text-xl-end">';
                        echo '<span class="share-links-title">'.esc_html__('Share:', 'solak').'</span>';
                       echo ' <div class="social-links">';
                            echo solak_social_sharing_buttons();
                        echo '</div>';
                    echo '</div>';
                }
            }
            
    
        }
    }
    
    
    // blog details author bio hook function
    if( !function_exists('solak_blog_details_author_bio_cb') ) {
        function solak_blog_details_author_bio_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $postauthorbox =  solak_opt( 'solak_post_details_author_box' );
            } else {
                $postauthorbox = '0';
            }
            if(  $postauthorbox == '1' ) {
                echo '<div class="widget widget-author">';
                    echo '<div class="author-widget-wrap">';
                        echo '<div class="avater">';
                            echo '<img src="'.esc_url( get_avatar_url( get_the_author_meta('ID') ) ).'" alt="'.esc_attr__('Author Image', 'solak').'">';
                        echo '</div>';
                        echo '<div class="author-info">';
                            echo '<h4 class="box-title"><a class="text-inherit" href="blog.html">'.esc_html( ucwords( get_the_author() )).'</a></h4>';
                            echo '<span class="desig">'.get_user_meta( get_the_author_meta('ID'), '_solak_author_desig',true ).'</span>';
                            echo '<p class="author-bio">'.get_the_author_meta( 'user_description', get_the_author_meta('ID') ).'</p>';
                            echo '<div class="social-links">';
                                $solak_social_icons = get_user_meta( get_the_author_meta('ID'), '_solak_social_profile_group',true );
                                if(!empty($solak_social_icons)){
                                    foreach( $solak_social_icons as $singleicon ) {
                                        if( ! empty( $singleicon['_solak_social_profile_icon'] ) ) {
                                            echo '<a href="'.esc_url( $singleicon['_solak_lawyer_social_profile_link'] ).'"><i class="'.esc_attr( $singleicon['_solak_social_profile_icon'] ).'"></i></a>';
                                        }
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';

               
            }

        }
    }

     // Blog Details Post Navigation hook function
     if( !function_exists( 'solak_blog_details_post_navigation_cb' ) ) {
        function solak_blog_details_post_navigation_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_post_navigation = solak_opt('solak_post_details_post_navigation');
            } else {
                $solak_post_navigation = 0;
            }

            $prevpost = get_previous_post();
            $nextpost = get_next_post();

            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            ); 

            if( ($solak_post_navigation == '1') && (!empty($prevpost) || !empty($nextpost)) ) {
                echo '<div class="blog-navigation">'; 
                    if( ! empty( $prevpost ) ) {
                        echo '<a href="'.esc_url( get_permalink( $prevpost->ID ) ).'" class="nav-btn prev">';
                            echo '<i class="fa-solid fa-angle-left"></i>';
                            echo ' <span class="nav-text">'.esc_attr__('Previous', 'solak').'</span>';
                        echo '</a>';
                    }

                    if( ! empty( $nextpost ) ) {
                        echo '<a href="'.esc_url( get_permalink( $nextpost->ID ) ).'" class="nav-btn next">';
                            echo '<i class="fa-solid fa-angle-right"></i>';
                            echo ' <span class="nav-text">'.esc_attr__('Next', 'solak').'</span>';
                        echo '</a>';
                    }
                echo '</div>';
            }

        }
    }

    // Blog Details Comments hook function
    if( !function_exists('solak_blog_details_comments_cb') ) {
        function solak_blog_details_comments_cb( ) {
            if ( ! comments_open() ) {
                echo '<div class="blog-comment-area">';
                    echo solak_heading_tag( array(
                        "tag"   => "h3",
                        "text"  => esc_html__( 'Comments are closed', 'solak' ),
                        "class" => "inner-title"
                    ) );
                echo '</div>';
            }

            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        }
    }

    // Blog Details Column end hook function
    if( !function_exists('solak_blog_details_col_end_cb') ) {
        function solak_blog_details_col_end_cb( ) {
            echo '</div>';
        }
    }

    // Blog Details Wrapper end hook function
    if( !function_exists('solak_blog_details_wrapper_end_cb') ) {
        function solak_blog_details_wrapper_end_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page start wrapper hook function
    if( !function_exists('solak_page_start_wrap_cb') ) {
        function solak_page_start_wrap_cb( ) {
            
            if( is_page( 'cart' ) ){
                $section_class = "th-cart-wrapper space-top space-extra-bottom";
            }elseif( is_page( 'checkout' ) ){
                $section_class = "th-checkout-wrapper space-top space-extra-bottom";
            }elseif( is_page('wishlist') ){
                $section_class = "wishlist-area space-top space-extra-bottom";
            }else{
                $section_class = "space-top space-extra-bottom";  
            }
            echo '<section class="'.esc_attr( $section_class ).'">';
                echo '<div class="container">';
                    echo '<div class="row">';
        }
    }

    // page wrapper end hook function
    if( !function_exists('solak_page_end_wrap_cb') ) {
        function solak_page_end_wrap_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page column wrapper start hook function
    if( !function_exists('solak_page_col_start_wrap_cb') ) {
        function solak_page_col_start_wrap_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_page_sidebar = solak_opt('solak_page_sidebar');
            }else {
                $solak_page_sidebar = '1';
            }
            
            if( $solak_page_sidebar == '2' && is_active_sidebar('solak-page-sidebar') ) {
                echo '<div class="col-lg-8 order-last">';
            } elseif( $solak_page_sidebar == '3' && is_active_sidebar('solak-page-sidebar') ) {
                echo '<div class="col-lg-8">';
            } else {
                echo '<div class="col-lg-12">';
            }

        }
    }

    // page column wrapper end hook function
    if( !function_exists('solak_page_col_end_wrap_cb') ) {
        function solak_page_col_end_wrap_cb( ) {
            echo '</div>';
        }
    }

    // page sidebar hook function
    if( !function_exists('solak_page_sidebar_cb') ) {
        function solak_page_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $solak_page_sidebar = solak_opt('solak_page_sidebar');
            }else {
                $solak_page_sidebar = '1';
            }

            if( class_exists('ReduxFramework') ) {
                $solak_page_layoutopt = solak_opt('solak_page_layoutopt');
            }else {
                $solak_page_layoutopt = '3';
            }

            if( $solak_page_layoutopt == '1' && $solak_page_sidebar != 1 ) {
                get_sidebar('page');
            } elseif( $solak_page_layoutopt == '2' && $solak_page_sidebar != 1 ) {
                get_sidebar();
            }
        }
    }

    // page content hook function
    if( !function_exists('solak_page_content_cb') ) {
        function solak_page_content_cb( ) {
            if(  class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_page('wishlist') || is_account_page() )  ) {
                echo '<div class="woocommerce--content">';
            } else {
                echo '<div class="page--content clearfix">';
            }

                the_content();

                // Link Pages
                solak_link_pages();

            echo '</div>';
            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        }
    }

    if( !function_exists('solak_blog_post_thumb_cb') ) {
        function solak_blog_post_thumb_cb( ) {
            if( get_post_format() ) {
                $format = get_post_format();
            }else{
                $format = 'standard';
            }

            $solak_post_slider_thumbnail = solak_meta( 'post_format_slider' );

            if( !empty( $solak_post_slider_thumbnail ) ){
                echo '<div class="blog-img th-slider" data-slider-options=\'{"effect":"fade"}\'>';
                    echo '<div class="swiper-wrapper">';
                        foreach( $solak_post_slider_thumbnail as $single_image ){
                            echo '<div class="swiper-slide">';
                                echo solak_img_tag( array(
                                    'url'   => esc_url( $single_image )
                                ) );
                            echo '</div>';
                        }
                    echo '</div>';
                    echo '<button class="slider-arrow slider-prev"><i class="far fa-arrow-left"></i></button>';
                    echo '<button class="slider-arrow slider-next"><i class="far fa-arrow-right"></i></button>';
                echo '</div>';

            }elseif( has_post_thumbnail() && $format == 'standard' ) {
                echo '<!-- Post Thumbnail -->';
                echo '<div class="blog-img">';
                    if( ! is_single() ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">'; 
                    }

                    the_post_thumbnail();

                    if( ! is_single() ){
                        echo '</a>';
                    }
                echo '</div>';
                echo '<!-- End Post Thumbnail -->';
            }elseif( $format == 'video' ){
                if( has_post_thumbnail() && ! empty ( solak_meta( 'post_format_video' ) ) ){
                    echo '<div class="blog-img blog-video" data-overlay="title" data-opacity="4">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            the_post_thumbnail();

                        if( ! is_single() ){
                            echo '</a>';
                        }
                        echo '<a href="'.esc_url( solak_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';
                            echo '<i class="fas fa-play"></i>';
                        echo '</a>';
                    echo '</div>';
                }elseif( ! has_post_thumbnail() && ! is_single() ){
                    echo '<div class="blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            echo solak_embedded_media( array( 'video', 'iframe' ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                    echo '</div>';
                }
            }elseif( $format == 'audio' ){
                $solak_audio = solak_meta( 'post_format_audio' );
                if( ! empty( $solak_audio ) ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $solak_audio );
                    echo '</div>';
                }elseif( ! is_single() ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $solak_audio );
                    echo '</div>';
                }
            }

        }
    }

    if( !function_exists('solak_blog_post_content_cb') ) {
        function solak_blog_post_content_cb( ) {
            $allowhtml = array(
                'p'         => array(
                    'class'     => array()
                ),
                'span'      => array(),
                'a'         => array(
                    'href'      => array(),
                    'title'     => array()
                ),
                'br'        => array(),
                'em'        => array(),
                'strong'    => array(),
                'b'         => array(),
            );
            if( class_exists( 'ReduxFramework' ) ) {
                $solak_excerpt_length          = solak_opt( 'solak_blog_postExcerpt' );
                $solak_display_post_category   = solak_opt( 'solak_display_post_category' );
            } else {
                $solak_excerpt_length          = '35';
                $solak_display_post_category   = '1';
            }

            if( class_exists( 'ReduxFramework' ) ) {
                $solak_blog_admin = solak_opt( 'solak_blog_post_author' );
                $solak_blog_readmore_setting_val = solak_opt('solak_blog_readmore_setting');
                if( $solak_blog_readmore_setting_val == 'custom' ) {
                    $solak_blog_readmore_setting = solak_opt('solak_blog_custom_readmore');
                } else {
                    $solak_blog_readmore_setting = __( 'Read More', 'solak' );
                }
            } else {
                $solak_blog_readmore_setting = __( 'Read More', 'solak' );
                $solak_blog_admin = true;
            }
            echo '<!-- blog-content -->';

                do_action( 'solak_blog_post_thumb' );
                
                echo '<div class="blog-content">';

                    // Blog Post Meta
                    do_action( 'solak_blog_post_meta' );

                    echo '<h3 class="blog-title"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title( ), $allowhtml ).'</a></h3>';

                    echo '<!-- Post Summary -->';
                    echo solak_paragraph_tag( array(
                        "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $solak_excerpt_length, '' ), $allowhtml ),
                        "class" => 'blog-text',
                    ) );
  
                    if( !empty( $solak_blog_readmore_setting ) ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="th-btn border-btn th-icon"><span class="btn-text" data-back="'.esc_html( $solak_blog_readmore_setting ).'" data-front="'.esc_html( $solak_blog_readmore_setting ).'"></span><i class="fa-regular fa-arrow-right ms-2"></i> </a>';
                    }

                echo '</div>';
            echo '<!-- End Post Content -->';
        }
    }
