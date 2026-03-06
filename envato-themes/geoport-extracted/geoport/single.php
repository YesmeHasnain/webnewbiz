<?php
/**
 * The template for displaying all single posts.
 *
 * @package geoport
 */

get_header(); 

do_action( 'geoport_breadcrum' );

?>

<div class="primary-bg">
    <!-- blog-area -->
    <div class="inner-blog single-blog-page">
        <div class="container">
            <div class="row">
                <?php 
                    if( function_exists( 'geoport_framework_init' ) ) {
                        if ( is_active_sidebar( 'right-sidebar') ) {
                            $blog_single_layout = geoport_get_option('blog_single_layout');
                            if ( $blog_single_layout == 'left-sidebar' ) {
                                $col   = '8';
                                $class = 'order-12';
                            } elseif ( $blog_single_layout == 'right-sidebar' ) {
                                $col   = '8';
                                $class = '';
                             } elseif ( $blog_single_layout == 'full-width' ) {
                                $class = '';
                                $col   = '12';
                            } else {
                                $class = '';
                                $col   = '8';
                            }
                        } else {
                            $col   = '8';
                            $class = '';
                        }
                    } else {
                        $col   = '12';
                        $class = '';
                    }
                ?>
                <div class="col-lg-<?php echo esc_attr( $col . ' blog-post-content ' . $class ); ?>">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'template-parts/content', 'single' ); ?>
                            
                    <?php endwhile; ?>

                    <?php else : ?>

                        <?php get_template_part( 'template-parts/content', 'none' ); ?>

                    <?php endif; ?>
                </div>
                <?php 
                    if( function_exists( 'geoport_framework_init' ) ) {
                        if ( is_active_sidebar( 'right-sidebar') ) {
                            $blog_single_layout = geoport_get_option('blog_single_layout');
                            if ( $blog_single_layout == 'left-sidebar' ||  $blog_single_layout == 'right-sidebar' ) {
                                get_sidebar();
                            } elseif ($blog_single_layout == 'full-width') {
                                
                            } else {
                                get_sidebar();
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- blog-grid-end -->

<?php get_footer(); ?>