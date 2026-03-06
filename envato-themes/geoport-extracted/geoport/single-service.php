<?php
/**
 * The template for displaying all service single posts.
 *
 * @package geoport
 */

get_header(); 

do_action( 'geoport_breadcrum' );

if( function_exists( 'geoport_framework_init' ) ) {
  $service_details_thumbnail = geoport_get_option('service_post_details_thumbnail');
  $prev_next_switch = geoport_get_option('prev_and_next_post_switch');
}else {
  $service_details_thumbnail = '';
  $prev_next_switch = '';
}

?>

<div class="primary-bg">
    <div class="inner-blog service-details">
        <div class="container">
            <?php
                if ( is_active_sidebar( 'service-widgets') ) {
                    if( function_exists( 'geoport_framework_init' ) ) {
                        $service_details_layout = geoport_get_option('service_details_layout');
                        if ( $service_details_layout == 'left-sidebar' ) {
                            $col   = '8';
                            $class = 'order-12';
                        } elseif ( $service_details_layout == 'right-sidebar' ) {
                            $col   = '8';
                            $class = '';
                         } elseif ( $service_details_layout == 'full-width' ) {
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
            <div class="row">
                <div class="col-lg-<?php echo esc_attr( $col . ' ' . $class ); ?>">
                    <div class="services-details-content">
                        <?php 
                            if ( have_posts() ) : while ( have_posts() ) : the_post(); 
                                if (!empty( $service_details_thumbnail )) {
                        ?>
                        <div class="post-details-thumb">
                            <?php the_post_thumbnail(); ?>
                        </div>
                        <?php } ?>
                        <div class="service-desc">
                            <div class="desc">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php
                            if (!empty( $prev_next_switch )){
                                geoport_post_nav();
                            }
                            endwhile; 
                            else :
                            get_template_part( 'template-parts/content', 'none' );
                            endif; 
                        ?>
                    </div>
                </div>
                
                <?php 
                    if ( is_active_sidebar( 'service-widgets') ) { 
                        if( function_exists( 'geoport_framework_init' ) ) {
                            $service_single_layout = geoport_get_option('service_details_layout');
                            if ( $service_single_layout == 'left-sidebar' ||  $service_single_layout == 'right-sidebar' ) { ?>
                                <div class="col-lg-4">
                                    <aside class="services-sidebar pl-10">
                                        <?php dynamic_sidebar( 'service-widgets' ); ?>
                                    </aside>
                                </div>
                            <?php } elseif ($service_single_layout == 'full-width') {
                                
                            } else { ?>
                                <div class="col-lg-4">
                                    <aside class="services-sidebar pl-10">
                                        <?php dynamic_sidebar( 'service-widgets' ); ?>
                                    </aside>
                                </div>
                            <?php }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- blog-grid-end -->
<?php get_footer(); ?>