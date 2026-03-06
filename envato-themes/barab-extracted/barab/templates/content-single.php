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

    barab_setPostViews( get_the_ID() );

    ?>
    <div <?php post_class(); ?>>
        <?php
        if( class_exists('ReduxFramework') ) {
            $barab_post_details_title_position = barab_opt('barab_post_details_title_position');
            $barab_blog_single_sidebar = barab_opt('barab_blog_single_sidebar');
        } else {
            $barab_post_details_title_position = 'header';
            $barab_blog_single_sidebar = '1';
        }

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

        // Blog Post Thumbnail
        do_action( 'barab_blog_post_thumb' ); 

        echo '<div class="blog-content v2">';
            // Blog Post Meta 
            do_action( 'barab_blog_post_meta' );

            if( $barab_post_details_title_position != 'header' ) {
                echo '<h3 class="blog-title">'.wp_kses( get_the_title(), $allowhtml ).'</h3>';
            }

            if( get_the_content() ){

                the_content();
                // Link Pages
                barab_link_pages();
            }  

            if( class_exists('ReduxFramework') ) {
                $barab_post_details_share_options = barab_opt('barab_post_details_share_options');
                $barab_display_post_tags = barab_opt('barab_display_post_tags');
                $barab_author_options = barab_opt('barab_post_details_author_desc_trigger');
            } else {
                $barab_post_details_share_options = false;
                $barab_display_post_tags = false;
                $barab_author_options = false;
            }
            
            $barab_post_tag = get_the_tags();
            
            if( ! empty( $barab_display_post_tags ) || ( ! empty($barab_post_details_share_options )) ){
                echo '<div class="share-links clearfix">';
                    echo '<div class="row justify-content-between">';
                        if( is_array( $barab_post_tag ) && ! empty( $barab_post_tag ) ){
                            if( count( $barab_post_tag ) > 1 ){
                                $tag_text = __( 'Tags:', 'barab' );
                            }else{
                                $tag_text = __( 'Tag:', 'barab' );
                            } 
                            if($barab_display_post_tags){ 
                                echo '<div class="col-sm-auto">';
                                    echo '<span class="share-links-title">'.esc_html($tag_text).'</span>';
                                    echo '<div class="tagcloud">';
                                        foreach( $barab_post_tag as $tags ){
                                            echo '<a href="'.esc_url( get_tag_link( $tags->term_id ) ).'">'.esc_html( $tags->name ).'</a>';
                                        }
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
    
                        /**
                        *
                        * Hook for Blog Details Share Options
                        *
                        * Hook barab_blog_details_share_options
                        *
                        * @Hooked barab_blog_details_share_options_cb 10
                        *
                        */
                        do_action( 'barab_blog_details_share_options' );
    
                    echo '</div>';
    
                echo '</div>';    
            }  
        
        echo '</div>';

        /**
        *
        * Hook for Post Navigation
        *
        * Hook barab_blog_details_post_navigation
        *
        * @Hooked barab_blog_details_post_navigation_cb 10
        *
        */
        do_action( 'barab_blog_details_post_navigation' );

        /**
        *
        * Hook for Blog Authro Bio
        *
        * Hook barab_blog_details_author_bio
        *
        * @Hooked barab_blog_details_author_bio_cb 10
        *
        */
        do_action( 'barab_blog_details_author_bio' );

        /**
        *
        * Hook for Blog Details Comments
        *
        * Hook barab_blog_details_comments
        *
        * @Hooked barab_blog_details_comments_cb 10
        *
        */
        do_action( 'barab_blog_details_comments' );

    echo '</div>'; 
