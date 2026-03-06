<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package geoport
 */

get_header(); 

do_action('geoport_breadcrum');

?>

    <!-- blog-details-section - start
    ================================================== -->
    
    <?php if( function_exists( 'geoport_framework_init' ) ) { 
        $img_id = geoport_get_option( 'geoport_404_image' );
        $attachment = wp_get_attachment_image_src( $img_id, 'full' );
        $image    = ($attachment) ? $attachment[0] : $img_id;
        $title = geoport_get_option( '404_page_title' );
        $text = geoport_get_option( '404_text' );
        $btn_txt = geoport_get_option( '404_btn_txt' );
        $btn2_txt = geoport_get_option( '404_btn2_txt' );
        $btn2_link = geoport_get_option( '404_btn2_link' );

        if ( !empty( $image ) ) {
            $img_col = '5';
        } else {
            $img_col = '12 text-center';
        }
    ?>

    <!-- 404-area -->
    <section class="error-area error-bg d-flex align-items-center" data-background="<?php echo esc_url( $image ); ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-<?php echo esc_attr( $img_col ); ?>">
                    <div class="error-content">
                        <?php if ( !empty( $title ) ) { ?>
                        <span><?php echo esc_html( $title ); ?></span>
                        <?php } if ( !empty( $text ) ) { ?>
                        <h2><?php echo esc_html( $text ); ?></h2>
                        <?php } ?>
                        <div class="error-btn">
                            <?php if ( !empty( $btn_txt ) ) { ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn orange-btn"><i class="fal fa-home-lg"></i> <?php echo esc_html( $btn_txt ); ?></a>
                            <?php } if ( !empty( $btn2_txt ) ) { ?>
                            <a href="<?php echo esc_url( $btn2_link ); ?>" class="btn gray-btn"><i class="fal fa-envelope"></i> <?php echo esc_html( $btn2_txt ); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- 404-area-end -->

    <?php } else { ?>

    <!-- 404-area -->
    <section class="error-area default">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-content text-center">
                        <h2><?php esc_html_e( '404', 'geoport' ); ?></h2>
                        <span><?php esc_html_e( 'Sorry, Page Not Found', 'geoport' ); ?></span>
                        <p><?php esc_html_e( 'The page you are looking for was removed or might never existed.', 'geoport' ); ?></p>
                        <div class="error-btn">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn orange-btn"><i class="fal fa-home-lg"></i> <?php esc_html_e( 'Go Back Home', 'geoport' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- 404-area-end -->
    <?php } ?>

<?php get_footer(); ?>