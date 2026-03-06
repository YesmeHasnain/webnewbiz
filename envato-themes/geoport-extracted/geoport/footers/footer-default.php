<!-- footer-section - start
================================================== -->
    <?php if( function_exists( 'geoport_framework_init' ) ) {
        $footer_widget1_columns = geoport_get_option('footer_widget1_columns');
        $footer_widget2_columns = geoport_get_option('footer_widget2_columns');
        $footer_widget3_columns = geoport_get_option('footer_widget3_columns');
        $footer_widget3_columns = geoport_get_option('footer_widget4_columns');
        $bg_img_id              = geoport_get_option('footer_left_bg_img');
        $attachment             = wp_get_attachment_image_src( $bg_img_id, 'full' );
        $left_bg_img            = ( $attachment ) ? $attachment[0] : $bg_img_id;

        if (!empty($footer_widget1_columns)) {
            $widget1_columns = $footer_widget1_columns;
        } else {
            $widget1_columns = '4';
        }
        if (!empty($footer_widget2_columns)) {
            $widget2_columns = $footer_widget2_columns;
        } else {
            $widget2_columns = '2';
        }
        if (!empty($footer_widget3_columns)) {
            $widget3_columns = $footer_widget3_columns;
        } else {
            $widget3_columns = '3';
        }
        if (!empty($footer_widget4_columns)) {
            $widget4_columns = $footer_widget4_columns;
        } else {
            $widget4_columns = '3';
        }
    } else {
        $widget1_columns = '3';
        $widget2_columns = '3';
        $widget3_columns = '3';
        $widget4_columns = '3';
        $left_bg_img    = '';
    }

    if ( is_active_sidebar( 'footer-widgets1' ) || is_active_sidebar( 'footer-widgets2' ) || is_active_sidebar( 'footer-widgets3' ) || is_active_sidebar( 'footer-widgets4' ) ) {
        $footer_widget_activated = 'footer-widget-activated';
    } else {
        $footer_widget_activated = 'footer-widget-not-activated';
    }
?>

<!-- footer-start -->
<footer>
    <?php if ( is_active_sidebar( 'footer-widgets1' ) || is_active_sidebar( 'footer-widgets2' ) || is_active_sidebar( 'footer-widgets3' ) || is_active_sidebar( 'footer-widgets4' ) ) { ?>
    <div class="footer-area footer-bg <?php echo esc_attr( $footer_widget_activated ); ?>">
        <?php 
            if ( function_exists( 'geoport_framework_init' ) ) { 
                $footer_left_bg_block = geoport_get_option('footer_left_bg_block');
                if (!empty($footer_left_bg_block)) {
        ?>
            <div class="footer-img-bg" style="background-image: url( <?php echo esc_url ( $left_bg_img ) ?> );"></div>
        <?php } } ?>
        <div class="container">
            <div class="row">
                <?php if ( is_active_sidebar( 'footer-widgets1' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget1_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widgets1' ); ?>
                </div>
                <?php } if ( is_active_sidebar( 'footer-widgets2' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget2_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widgets2' ); ?>
                </div>
                <?php } if ( is_active_sidebar( 'footer-widgets3' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget3_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widgets3' ); ?>
                </div>
                <?php } if ( is_active_sidebar( 'footer-widgets4' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget4_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widgets4' ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php get_template_part( 'footers/copyright' ); ?>

</footer>
<!-- footer-end -->