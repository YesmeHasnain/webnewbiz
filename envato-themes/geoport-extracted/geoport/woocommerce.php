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
                        if ( is_active_sidebar( 'shop') ) {
                            $shop_layout = geoport_get_option('shop_layout');
                            if ( $shop_layout == 'left-sidebar' ) {
                                $col   = '8';
                                $class = 'order-12';
                            } elseif ( $shop_layout == 'right-sidebar' ) {
                                $col   = '8';
                                $class = '';
                             } elseif ( $shop_layout == 'full-width' ) {
                                $class = '';
                                $col   = '12';
                            } else {
                                $class = '';
                                $col   = '8';
                            }
                        } else {
                            $col   = '12';
                            $class = '';
                        }
                    } else {
                        $col   = '12';
                        $class = '';
                    }
                ?>
                <div class="col-lg-<?php echo esc_attr( $col . ' ' . $class ); ?>">
                    <div class="page-details-content mb-10">
                        <?php if ( have_posts() ) : ?>

                            <?php woocommerce_content(); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <!-- Start Blog Sidebar -->
                <?php 
                    if( function_exists( 'geoport_framework_init' ) ) {
                        if ( is_active_sidebar( 'shop') ) {
                            $shop_layout = geoport_get_option('shop_layout');
                            if ( $shop_layout == 'left-sidebar' ||  $shop_layout == 'right-sidebar' ) { ?>
                                <div class="col-lg-4">
                                    <aside class="blog-sidebar woosidebar <?php echo esc_attr( $sidebar_class ); ?>">
                                        <?php dynamic_sidebar( 'shop' ); ?>
                                    </aside>
                                </div>
                            <?php } elseif ($shop_layout == 'full-width') {
                                
                            } else { ?>
                                <div class="col-lg-4">
                                    <aside class="blog-sidebar woosidebar <?php echo esc_attr( $sidebar_class ); ?>">
                                        <?php dynamic_sidebar( 'shop' ); ?>
                                    </aside>
                                </div>
                            <?php }
                        } else { 
                        if ( is_active_sidebar( 'shop') ) {
                        ?>
                            <div class="col-lg-4">
                                <aside class="blog-sidebar woosidebar <?php echo esc_attr( $sidebar_class ); ?>">
                                    <?php dynamic_sidebar( 'shop' ); ?>
                                </aside>
                            </div>
                        <?php } }
                    }
                ?>
           </div><!-- row -->
        </div><!-- container -->
    </div>
</div>
<!-- page-section - End
================================================== -->
<?php get_footer(); ?>