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

    solak_setPostViews( get_the_ID() );

    ?>
    <div <?php post_class(); ?>>
    <?php
        if( class_exists('ReduxFramework') ) {
            $solak_post_details_title_position = solak_opt('solak_post_details_title_position');
        } else {
            $solak_post_details_title_position = 'header';
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
        do_action( 'solak_blog_post_thumb' );
        
        echo '<div class="blog-content">';
            // Blog Post Meta
            do_action( 'solak_blog_post_meta' );

            if( $solak_post_details_title_position != 'header' ) {
                echo '<h2 class="blog-title">'.wp_kses( get_the_title(), $allowhtml ).'</h2>';
            }

            if( get_the_content() ){

                the_content();
                // Link Pages
                solak_link_pages();
            }  

            if( class_exists('ReduxFramework') ) {
                $solak_post_details_share_options = solak_opt('solak_post_details_share_options');
                $solak_display_post_tags = solak_opt('solak_display_post_tags');
                $solak_author_options = solak_opt('solak_post_details_author_desc_trigger');
            } else {
                $solak_post_details_share_options = false;
                $solak_display_post_tags = false;
                $solak_author_options = false;
            }
            
            $solak_post_tag = get_the_tags();
            
            if( ! empty( $solak_display_post_tags ) || ( ! empty($solak_post_details_share_options )) ){
                echo '<div class="share-links clearfix">';
                    echo '<div class="row justify-content-between">';
                        if( is_array( $solak_post_tag ) && ! empty( $solak_post_tag ) ){
                            if( count( $solak_post_tag ) > 1 ){
                                $tag_text = __( 'Tags:', 'solak' );
                            }else{
                                $tag_text = __( 'Tag:', 'solak' );
                            }
                            if($solak_display_post_tags){ 
                                echo '<div class="col-md-auto">';
                                    echo '<span class="share-links-title">'.esc_html($tag_text).'</span>';
                                    echo '<div class="tagcloud">';
                                        foreach( $solak_post_tag as $tags ){
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
                        * Hook solak_blog_details_share_options
                        *
                        * @Hooked solak_blog_details_share_options_cb 10
                        *
                        */
                        do_action( 'solak_blog_details_share_options' );
    
                    echo '</div>';
    
                echo '</div>';    
            }  
        
        echo '</div>';

    echo '</div>'; 

        /**
        *
        * Hook for Post Navigation
        *
        * Hook solak_blog_details_post_navigation
        *
        * @Hooked solak_blog_details_post_navigation_cb 10
        *
        */
        do_action( 'solak_blog_details_post_navigation' );

        /**
        *
        * Hook for Blog Authro Bio
        *
        * Hook solak_blog_details_author_bio
        *
        * @Hooked solak_blog_details_author_bio_cb 10
        *
        */
        do_action( 'solak_blog_details_author_bio' );

        /**
        *
        * Hook for Blog Details Comments
        *
        * Hook solak_blog_details_comments
        *
        * @Hooked solak_blog_details_comments_cb 10
        *
        */
        do_action( 'solak_blog_details_comments' );
