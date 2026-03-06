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


    // preloader hook function
    if( ! function_exists( 'barab_preloader_wrap_cb' ) ) {
        function barab_preloader_wrap_cb() {
            $preloader_display              =  barab_opt('barab_display_preloader');
            $barab_preloader_btn_text        =  barab_opt('barab_preloader_btn_text');
            $barab_preloader_text        =  barab_opt('barab_preloader_text');

            if( class_exists('ReduxFramework') ){
                if( $preloader_display ){
                    $chars = str_split($barab_preloader_text);
                    
                    echo '<div id="preloader" class="preloader">';  
                        if( !empty( $barab_preloader_btn_text ) ){
                            echo '<button class="th-btn style2 preloaderCls">'.esc_html( $barab_preloader_btn_text ).'</button>';
                        }
                        echo '<div class="preloader-inner">';
                            if(!empty(barab_opt('barab_preloader_logo', 'url' ) )){
                                echo '<div class="header-logo">';
                                    echo '<img src="'.esc_url( barab_opt('barab_preloader_logo', 'url' ) ).'" alt="'.esc_attr__('Logo', 'barab').'">';
                                echo '</div>';
                            }
                            if( !empty( $barab_preloader_text ) ){
                                echo '<div class="txt-loading">';
                                    foreach ($chars as $char) {
                                        echo '<span data-text-preloader="'.esc_attr($char).'" class="letters-loading">'.esc_html($char).'</span>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
            }else{
                echo '<div class="preloader">';
                    echo '<button class="th-btn style2 preloaderCls">'.esc_html__( 'Cancel Preloader', 'barab' ).'</button>';
                    echo '<div class="preloader-inner">';
                        echo '<span class="loader"></span>';
                    echo '</div>';
                echo '</div>';
            }

        }
    }

    // Header Hook function
    if( !function_exists('barab_header_cb') ) { 
        function barab_header_cb( ) {
            get_template_part('templates/header');
        }
    }

    // Header Hook function
    if( !function_exists('barab_breadcrumb_cb') ) { 
        function barab_breadcrumb_cb( ) {
            get_template_part('templates/header-menu-bottom');
        }
    }

    // back top top hook function
    if( ! function_exists( 'barab_back_to_top_cb' ) ) {
        function barab_back_to_top_cb( ) {
            $backtotop_trigger = barab_opt('barab_display_bcktotop');
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
    if( !function_exists('barab_blog_start_wrap_cb') ) {
        function barab_blog_start_wrap_cb() { ?>
            <section class="th-blog-wrapper space-top space-extra-bottom">
                <div class="container">
                    <div class="row">
        <?php }
    }

    // Blog End Wrapper Function
    if( !function_exists('barab_blog_end_wrap_cb') ) {
        function barab_blog_end_wrap_cb() {?>
                    </div>
                </div>
            </section>
        <?php }
    }

    // Blog Column Start Wrapper Function
    if( !function_exists('barab_blog_col_start_wrap_cb') ) {
        function barab_blog_col_start_wrap_cb() {
           
                //Redux option work
                if( class_exists('ReduxFramework') ) {
                    $barab_blog_sidebar = barab_opt('barab_blog_sidebar');
                }else{
                    $barab_blog_sidebar = '1';
                }

                if( class_exists('ReduxFramework') ) {
                    // $barab_blog_sidebar = barab_opt('barab_blog_sidebar');
                    if( $barab_blog_sidebar == '2' && is_active_sidebar('barab-blog-sidebar') ) {
                        echo '<div class="col-xxl-9 col-lg-8  order-lg-last">';
                    } elseif( $barab_blog_sidebar == '3' && is_active_sidebar('barab-blog-sidebar') ) {
                        echo '<div class="col-xxl-9 col-lg-8">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }

                } else {
                    if( is_active_sidebar('barab-blog-sidebar') ) {
                        echo '<div class="col-xxl-9 col-lg-8">';
                    } else {
                        echo '<div class="col-lg-12">';
                    }
                }
                

        }
    }
    // Blog Column End Wrapper Function
    if( !function_exists('barab_blog_col_end_wrap_cb') ) {
        function barab_blog_col_end_wrap_cb() {
            echo '</div>';
        }
    }

    // Blog Sidebar
    if( !function_exists('barab_blog_sidebar_cb') ) {
        function barab_blog_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_blog_sidebar = barab_opt('barab_blog_sidebar');
            } else {
                $barab_blog_sidebar = 2;
                
            }
            if( $barab_blog_sidebar != 1 && is_active_sidebar('barab-blog-sidebar') ) {
                // Sidebar
                get_sidebar();
            }
        }
    }


    if( !function_exists('barab_blog_details_sidebar_cb') ) {
        function barab_blog_details_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_blog_single_sidebar = barab_opt('barab_blog_single_sidebar');
            } else {
                $barab_blog_single_sidebar = 4;
            }
            if( $barab_blog_single_sidebar != 1 ) {
                // Sidebar
                get_sidebar();
            }

        }
    }

    // Blog Pagination Function
    if( !function_exists('barab_blog_pagination_cb') ) {
        function barab_blog_pagination_cb( ) {
            get_template_part('templates/pagination');
        }
    }

    // Blog Content Function
    if( !function_exists('barab_blog_content_cb') ) {
        function barab_blog_content_cb( ) {

            //Redux option work
            if( class_exists('ReduxFramework') ) {
                $barab_blog_grid = barab_opt('barab_blog_grid');  
            }else{
                $barab_blog_grid = '1';
            }

            if( $barab_blog_grid == '1' ) {
                $barab_blog_grid_class = 'col-lg-12';
            } elseif( $barab_blog_grid == '2' ) {
                $barab_blog_grid_class = 'col-sm-6';
            } else {
                $barab_blog_grid_class = 'col-lg-4 col-sm-6';
            }

            echo '<div class="row">';
                if( have_posts() ) {
                    while( have_posts() ) {
                        the_post();
                        echo '<div class="'.esc_attr($barab_blog_grid_class).'">';
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
    if( !function_exists('barab_footer_content_cb') ) {
        function barab_footer_content_cb( ) {

            if( class_exists('ReduxFramework') && did_action( 'elementor/loaded' )  ){
                if( is_page() || is_page_template('template-builder.php') ) {
                    $post_id = get_the_ID();

                    // Get the page settings manager
                    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

                    // Get the settings model for current post
                    $page_settings_model = $page_settings_manager->get_model( $post_id );

                    // Retrieve the Footer Style
                    $footer_settings = $page_settings_model->get_settings( 'barab_footer_style' );

                    // Footer Local
                    $footer_local = $page_settings_model->get_settings( 'barab_footer_builder_option' );

                    // Footer Enable Disable
                    $footer_enable_disable = $page_settings_model->get_settings( 'barab_footer_choice' );

                    if( $footer_enable_disable == 'yes' ){
                        if( $footer_settings == 'footer_builder' ) {
                            // local options
                            $barab_local_footer = get_post( $footer_local );
                            echo '<footer>';
                            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $barab_local_footer->ID );
                            echo '</footer>';
                        } else {
                            // global options
                            $barab_footer_builder_trigger = barab_opt('barab_footer_builder_trigger');
                            if( $barab_footer_builder_trigger == 'footer_builder' ) {
                                echo '<footer>';
                                $barab_global_footer_select = get_post( barab_opt( 'barab_footer_builder_select' ) );
                                $footer_post = get_post( $barab_global_footer_select );
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                                echo '</footer>';
                            } else {
                                // wordpress widgets
                                barab_footer_global_option();
                            }
                        }
                    }
                } else {
                    // global options
                    $barab_footer_builder_trigger = barab_opt('barab_footer_builder_trigger');
                    if( $barab_footer_builder_trigger == 'footer_builder' ) {
                        echo '<footer>';
                        $barab_global_footer_select = get_post( barab_opt( 'barab_footer_builder_select' ) );
                        $footer_post = get_post( $barab_global_footer_select );
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $footer_post->ID );
                        echo '</footer>';
                    } else {
                        // wordpress widgets
                        barab_footer_global_option();
                    }
                }
            } else { ?>
                <div class="footer-layout1 footer-sitcky">
                    <div class="copyright-wrap bg-theme2">
                        <div class="container">
                            <p class="copyright-text text-center"><?php echo sprintf( 'Copyright <i class="fal fa-copyright"></i> %s <a href="%s"> %s </a> All Rights Reserved.', date('Y'), esc_url('#'), esc_html__( 'Barab.','barab') ); ?></p> 
                        </div>
                    </div>
                </div>
            <?php }

        }
    }

    // blog details wrapper start hook function
    if( !function_exists('barab_blog_details_wrapper_start_cb') ) {
        function barab_blog_details_wrapper_start_cb( ) {
            echo '<section class="th-blog-wrapper blog-details space-top space-extra-bottom">';
                echo '<div class="container">';
                    if( is_active_sidebar( 'barab-blog-sidebar' ) ){
                        $barab_gutter_class = 'gx-60';
                    }else{
                        $barab_gutter_class = '';
                    }
                    // echo '<div class="row './/esc_attr( $barab_gutter_class ).'">';
                    echo '<div class="row">';
        }
    }

    // blog details column wrapper start hook function
    if( !function_exists('barab_blog_details_col_start_cb') ) {
        function barab_blog_details_col_start_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_blog_single_sidebar = barab_opt('barab_blog_single_sidebar');
                if( $barab_blog_single_sidebar == '2' && is_active_sidebar('barab-blog-sidebar') ) {
                    echo '<div class="col-xxl-9 col-lg-8 order-lg-last">';
                } elseif( $barab_blog_single_sidebar == '3' && is_active_sidebar('barab-blog-sidebar') ) {
                    echo '<div class="col-xxl-9 col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }

            } else {
                if( is_active_sidebar('barab-blog-sidebar') ) {
                    echo '<div class="col-xxl-9 col-lg-8">';
                } else {
                    echo '<div class="col-lg-12">';
                }
            }
        }
    }

    // blog details post meta hook function
    if( !function_exists('barab_blog_post_meta_cb') ) { 
        function barab_blog_post_meta_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_display_post_author      =  barab_opt('barab_display_post_author');
                $barab_display_post_date      =  barab_opt('barab_display_post_date');
                $barab_display_post_cate   =  barab_opt('barab_display_post_cate');
                $barab_display_post_comments      =  barab_opt('barab_display_post_comments');
                $barab_display_post_min      =  barab_opt('barab_display_post_min');
                $barab_post_read_min_text      =  barab_opt('barab_post_read_min_text');
                $barab_post_read_min_count      =  barab_opt('barab_post_read_min_count');
            } else {
                $barab_display_post_author      = '1';
                $barab_display_post_date      = '1';
                $barab_display_post_cate   = '0';
                $barab_display_post_comments      = '0'; 
                $barab_display_post_min      = '1'; 
                $barab_post_read_min_text      = 'min read'; 
                $barab_post_read_min_count      = '150'; 
            }

                echo '<div class="blog-meta">';
                    if( $barab_display_post_author ){
                        echo '<a class="author" href="'.esc_url( get_author_posts_url( get_the_author_meta('ID') ) ).'"><i class="far fa-user"></i>'. esc_html__('By ', 'barab') .esc_html( ucwords( get_the_author() ) ).'</a>'; 
                    }
                    if( $barab_display_post_date ){
                        echo ' <a href="'.esc_url( barab_blog_date_permalink() ).'"><i class="fa-light fa-calendar"></i>'.esc_html( get_the_date() ).'</a>';
                    }
                    if( $barab_display_post_cate ){
                        $categories = get_the_category(); 
                        if(!empty($categories)){
                        echo '<a href="'.esc_url( get_category_link( $categories[0]->term_id ) ).'"><i class="far fa-tag"></i>'.esc_html( $categories[0]->name ).'</a>';
                        }
                    }
                    if( $barab_display_post_comments ){
                        ?>
                        <a href="#"><i class="far fa-messages"></i>
                            <?php 
                                echo get_comments_number(); 
                                if(get_comments_number() == 1){
                                    echo esc_html__(' Comment', 'barab'); 
                                }else{
                                    echo esc_html__(' Comments', 'barab'); 
                                }
                                ?></a>
                        <?php
                    } 
                    if( $barab_display_post_min ){
                        if (function_exists('barab_get_reading_time')) {
                            echo '<a href="#">';
                                echo '<i class="far fa-clock"></i>';
                                echo barab_get_reading_time(get_the_ID());
                            echo '</a>';
                        }   
                    }
                echo '</div>';
        }
    }

    // Blog post reading time count
    function barab_get_reading_time($post_id) {
        if (class_exists('ReduxFramework')) {
            $barab_post_read_min_text = !empty(barab_opt('barab_post_read_min_text')) 
                                        ? sanitize_text_field(barab_opt('barab_post_read_min_text')) 
                                        : esc_html__('min read', 'barab');
            $words_per_minute = !empty(barab_opt('barab_post_read_min_count')) 
                                ? (int) barab_opt('barab_post_read_min_count') 
                                : 150;
        } else {
            $barab_post_read_min_text = esc_html__('min read', 'barab');
            $words_per_minute = 150;
        }
        
        // Get the content of the post
        $content = get_post_field('post_content', $post_id);
        
        // Count the number of words
        $word_count = str_word_count(strip_tags($content));
        
        // Calculate the reading time
        $reading_time = ceil($word_count / $words_per_minute);
        
        // Return the estimated reading time without the anchor tag
        return esc_html($reading_time . ' ' . $barab_post_read_min_text);
    }

    // blog details share options hook function
    if( !function_exists('barab_blog_details_share_options_cb') ) {
        function barab_blog_details_share_options_cb( ) {

            if( class_exists('ReduxFramework') ) {
                $barab_post_details_share = barab_opt('barab_post_details_share_options');
            } else {
                $barab_post_details_share = "0";
            } 

            if( function_exists( 'barab_social_sharing_buttons' ) ){ 
                if( $barab_post_details_share ){
                    echo '<div class="col-sm-auto text-xl-end">'; 
                        echo '<span class="share-links-title">'.esc_html__('Share:', 'barab').'</span>';
                       echo '<div class="th-social style2 align-items-center">';
                            echo barab_social_sharing_buttons();
                        echo '</div>';
                    echo '</div>';
                }
            }
            
    
        }
    }
    
    
    // blog details author bio hook function
    if( !function_exists('barab_blog_details_author_bio_cb') ) {
        function barab_blog_details_author_bio_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $postauthorbox =  barab_opt( 'barab_post_details_author_box' );
            } else {
                $postauthorbox = '0';
            }
            if(  $postauthorbox == '1' ) {
                echo '<div class="widget widget-author">';
                    echo '<div class="author-widget-wrap">';
                        echo '<div class="avater">';
                            echo '<img src="'.esc_url( get_avatar_url( get_the_author_meta('ID') ) ).'" alt="'.esc_attr__('Author Image', 'barab').'">';
                        echo '</div>';
                        echo '<div class="author-info">';
                            echo '<h4 class="box-title"><a class="text-inherit" href="blog.html">'.esc_html( ucwords( get_the_author() )).'</a></h4>';
                            echo '<span class="desig">'.get_user_meta( get_the_author_meta('ID'), '_barab_author_desig',true ).'</span>';
                            echo '<p class="author-bio">'.get_the_author_meta( 'user_description', get_the_author_meta('ID') ).'</p>';
                            echo '<div class="social-links">';
                                $barab_social_icons = get_user_meta( get_the_author_meta('ID'), '_barab_social_profile_group',true );
                                if(!empty($barab_social_icons)){
                                    foreach( $barab_social_icons as $singleicon ) {
                                        if( ! empty( $singleicon['_barab_social_profile_icon'] ) ) {
                                            echo '<a href="'.esc_url( $singleicon['_barab_lawyer_social_profile_link'] ).'"><i class="'.esc_attr( $singleicon['_barab_social_profile_icon'] ).'"></i></a>';
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
     if( !function_exists( 'barab_blog_details_post_navigation_cb' ) ) {
        function barab_blog_details_post_navigation_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_post_navigation = barab_opt('barab_post_details_post_navigation');
            } else {
                $barab_post_navigation = 0;
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
  
            if( ($barab_post_navigation == '1') && (!empty($prevpost) || !empty($nextpost)) ) {
                echo '<div class="blog-navigation">';
                    if ( ! empty( $prevpost ) ) {
                        $prev_img_url = get_the_post_thumbnail_url( $prevpost->ID, 'medium' );
                        $prev_img_alt = esc_attr( get_the_title( $prevpost->ID ) );

                        echo '<a href="' . esc_url( get_permalink( $prevpost->ID ) ) . '" class="nav-btn prev">';
                            echo '<div class="icon">';
                                echo '<img src="' . get_template_directory_uri() . '/assets/img/icon/left-arrow.svg" alt="' . esc_attr__( 'Left arrow', 'barab' ) . '">';
                            echo '</div>';
                            echo '<div class="title-wrap">';
                                echo '<span class="nav-text">'.esc_html__( 'Previous Post', 'barab' ).'</span>';
                                echo '<span class="title">' . esc_html( get_the_title( $prevpost->ID ) ) . '</span>';
                            echo '</div>';
                        echo '</a>';
                    }
                    // if ( ! empty( $prevpost ) && ! empty( $nextpost ) ) {
                    //     echo '<a href="#" class="blog-btn"><i class="fa-solid fa-grid"></i></a>';
                    // }
                    if ( ! empty( $nextpost ) ) {
                        $next_img_url = get_the_post_thumbnail_url( $nextpost->ID, 'medium' );
                        $next_img_alt = esc_attr( get_the_title( $nextpost->ID ) );

                        echo '<a href="' . esc_url( get_permalink( $nextpost->ID ) ) . '" class="nav-btn next">';
                            echo '<div class="icon">';
                                echo '<img src="' . get_template_directory_uri() . '/assets/img/icon/right-arrow.svg" alt="' . esc_attr__( 'Left arrow', 'barab' ) . '">';
                            echo '</div>';
                            echo '<div class="title-wrap">';
                                echo '<span class="nav-text">'.esc_html__( 'Next Post', 'barab' ).'</span>';
                                echo '<span class="title">' . esc_html( get_the_title( $nextpost->ID ) ) . '</span>';
                            echo '</div>';
                        echo '</a>';
                    }
                echo '</div>';


            }

        }
    }

    // Blog Details Comments hook function
    if( !function_exists('barab_blog_details_comments_cb') ) {
        function barab_blog_details_comments_cb( ) {
            if ( ! comments_open() ) {
                echo '<div class="blog-comment-area">';
                    echo barab_heading_tag( array(
                        "tag"   => "h3",
                        "text"  => esc_html__( 'Comments are closed', 'barab' ),
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
    if( !function_exists('barab_blog_details_col_end_cb') ) {
        function barab_blog_details_col_end_cb( ) {
            echo '</div>';
        }
    }

    // Blog Details Wrapper end hook function
    if( !function_exists('barab_blog_details_wrapper_end_cb') ) {
        function barab_blog_details_wrapper_end_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page start wrapper hook function
    if( !function_exists('barab_page_start_wrap_cb') ) {
        function barab_page_start_wrap_cb( ) {
            
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
    if( !function_exists('barab_page_end_wrap_cb') ) {
        function barab_page_end_wrap_cb( ) {
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    }

    // page column wrapper start hook function
    if( !function_exists('barab_page_col_start_wrap_cb') ) {
        function barab_page_col_start_wrap_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_page_sidebar = barab_opt('barab_page_sidebar');
            }else {
                $barab_page_sidebar = '1';
            }
            
            if( $barab_page_sidebar == '2' && is_active_sidebar('barab-page-sidebar') ) {
                echo '<div class="col-lg-8 order-last">';
            } elseif( $barab_page_sidebar == '3' && is_active_sidebar('barab-page-sidebar') ) {
                echo '<div class="col-lg-8">';
            } else {
                echo '<div class="col-lg-12">';
            }

        }
    }

    // page column wrapper end hook function
    if( !function_exists('barab_page_col_end_wrap_cb') ) {
        function barab_page_col_end_wrap_cb( ) {
            echo '</div>';
        }
    }

    // page sidebar hook function
    if( !function_exists('barab_page_sidebar_cb') ) {
        function barab_page_sidebar_cb( ) {
            if( class_exists('ReduxFramework') ) {
                $barab_page_sidebar = barab_opt('barab_page_sidebar');
            }else {
                $barab_page_sidebar = '1';
            }

            if( class_exists('ReduxFramework') ) {
                $barab_page_layoutopt = barab_opt('barab_page_layoutopt');
            }else {
                $barab_page_layoutopt = '3';
            }

            if( $barab_page_layoutopt == '1' && $barab_page_sidebar != 1 ) {
                get_sidebar('page');
            } elseif( $barab_page_layoutopt == '2' && $barab_page_sidebar != 1 ) {
                get_sidebar();
            }
        }
    }

    // page content hook function
    if( !function_exists('barab_page_content_cb') ) {
        function barab_page_content_cb( ) {
            if(  class_exists('woocommerce') && ( is_woocommerce() || is_cart() || is_checkout() || is_page('wishlist') || is_account_page() )  ) {
                echo '<div class="woocommerce--content">';
            } else {
                echo '<div class="page--content clearfix">';
            }

                the_content();

                // Link Pages
                barab_link_pages();

            echo '</div>';
            // comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

        }
    }

    if( !function_exists('barab_blog_post_thumb_cb') ) {
        function barab_blog_post_thumb_cb( ) {
            if( get_post_format() ) {
                $format = get_post_format();
            }else{
                $format = 'standard';
            }

            $barab_post_slider_thumbnail = barab_meta( 'post_format_slider' );

            if( $format == 'gallery' ){
                    if( !empty( $barab_post_slider_thumbnail ) ){
                    echo '<div class="blog-img th-slider" data-slider-options=\'{"effect":"fade"}\'>';
                        echo '<div class="swiper-wrapper">';
                            foreach( $barab_post_slider_thumbnail as $single_image ){
                                echo '<div class="swiper-slide">';
                                    echo barab_img_tag( array(
                                        'url'   => esc_url( $single_image )
                                    ) );
                                echo '</div>';
                            }
                        echo '</div>';
                        echo '<button class="slider-arrow slider-prev"><i class="far fa-arrow-left"></i></button>';
                        echo '<button class="slider-arrow slider-next"><i class="far fa-arrow-right"></i></button>';
                    echo '</div>';
                }else{
                    echo '<div class="blog-img global-img">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">'; 
                        }

                        the_post_thumbnail();

                        if( ! is_single() ){
                            echo '</a>';
                        }
                    echo '</div>';
                }

            }elseif( has_post_thumbnail() && $format == 'standard' ) {
                echo '<!-- Post Thumbnail -->';
                echo '<div class="blog-img global-img">';
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
                if( has_post_thumbnail() && ! empty ( barab_meta( 'post_format_video' ) ) ){
                    echo '<div class="blog-img blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            the_post_thumbnail();

                        if( ! is_single() ){
                            echo '</a>';
                        }
                        echo '<a href="'.esc_url( barab_meta( 'post_format_video' ) ).'" class="play-btn popup-video">';
                            echo '<i class="fas fa-play"></i>';
                        echo '</a>';
                    echo '</div>';
                }elseif( ! has_post_thumbnail() && ! is_single() ){
                    echo '<div class="blog-video">';
                        if( ! is_single() ){
                            echo '<a href="'.esc_url( get_permalink() ).'" class="post-thumbnail">';
                        }
                            echo barab_embedded_media( array( 'video', 'iframe' ) );
                        if( ! is_single() ){
                            echo '</a>';
                        }
                    echo '</div>';
                } 
            }elseif( $format == 'audio' ){
                $barab_audio = barab_meta( 'post_format_audio' );
                if( ! empty( $barab_audio ) ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $barab_audio );
                    echo '</div>';
                }elseif( ! is_single() ){
                    echo '<div class="blog-audio">';
                        echo wp_oembed_get( $barab_audio );
                    echo '</div>';
                }
            }

        }
    }
 
    if( !function_exists('barab_blog_post_content_cb') ) {
        function barab_blog_post_content_cb( ) {
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
                $barab_excerpt_length          = barab_opt( 'barab_blog_postExcerpt' );
                $barab_display_post_category   = barab_opt( 'barab_display_post_category' );
            } else {
                $barab_excerpt_length          = '35';
                $barab_display_post_category   = '1';
            }

            if( class_exists( 'ReduxFramework' ) ) {
                $barab_blog_admin = barab_opt( 'barab_blog_post_author' );
                $barab_blog_readmore_setting_val = barab_opt('barab_blog_readmore_setting');
                if( $barab_blog_readmore_setting_val == 'custom' ) {
                    $barab_blog_readmore_setting = barab_opt('barab_blog_custom_readmore');
                } else {
                    $barab_blog_readmore_setting = __( 'Read More', 'barab' );
                }
            } else {
                $barab_blog_readmore_setting = __( 'Read More', 'barab' );
                $barab_blog_admin = true;
            }
            echo '<!-- blog-content -->';

                do_action( 'barab_blog_post_thumb' );
                
                echo '<div class="blog-content">';

                    // Blog Post Meta
                    do_action( 'barab_blog_post_meta' ); 

                    echo '<h2 class="blog-title"><a href="'.esc_url( get_permalink() ).'">'.wp_kses( get_the_title( ), $allowhtml ).'</a></h2>';

                    echo '<!-- Post Summary -->';
                    echo barab_paragraph_tag( array(
                        "text"  => wp_kses( wp_trim_words( get_the_excerpt(), $barab_excerpt_length, '' ), $allowhtml ),
                        "class" => 'blog-text',
                    ) );
  
                    if( !empty(  $barab_blog_readmore_setting ) ){
                        echo '<a href="'.esc_url( get_permalink() ).'" class="th-btn btn-mask btn-sm style2">'.esc_html( $barab_blog_readmore_setting ).'</a>';
                    } 

                echo '</div>';
            echo '<!-- End Post Content -->';
        }
    }
