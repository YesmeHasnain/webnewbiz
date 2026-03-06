<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package geoport
 */
get_header(); 


do_action('geoport_breadcrum');

?>

<!-- Page-section - start
================================================== -->

<div class="primary-bg">
    <div class="inner-blog page-details pt-120 pb-80">
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
                <div class="col-lg-<?php echo esc_attr( $col . ' ' . $class ); ?>">
                    <div class="page-details-content mb-10">
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'template-parts/content', 'page' ); ?>
                            
                        <?php endwhile; ?>

                        <?php else : ?>

                            <?php get_template_part( 'template-parts/content', 'none' ); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <!-- Start Blog Sidebar -->
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
                        } else {
                           get_sidebar();
                        }
                    }
                ?>
           </div><!-- row -->
        </div><!-- container -->
    </div>
</div>

<!-- page-section - End -->
<?php get_footer(); ?>