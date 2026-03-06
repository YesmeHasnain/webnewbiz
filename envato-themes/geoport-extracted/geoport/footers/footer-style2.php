<!-- footer-section - start
================================================== -->
    <?php if( function_exists( 'geoport_framework_init' ) ) {
        $footer_widget1_columns = geoport_get_option('footer2_widget1_columns');
        $footer_widget2_columns = geoport_get_option('footer2_widget2_columns');
        $footer_widget3_columns = geoport_get_option('footer2_widget3_columns');
        $bg_img_id              = geoport_get_option('footer2_bg_img');
        $attachment             = wp_get_attachment_image_src( $bg_img_id, 'full' );
        $full_bg_img            = ( $attachment ) ? $attachment[0] : $bg_img_id;

        if (!empty($footer_widget1_columns)) {
            $widget1_columns = $footer_widget1_columns;
        } else {
            $widget1_columns = '5';
        }
        if (!empty($footer_widget2_columns)) {
            $widget2_columns = $footer_widget2_columns;
        } else {
            $widget2_columns = '3';
        }
        if (!empty($footer_widget3_columns)) {
            $widget3_columns = $footer_widget3_columns;
        } else {
            $widget3_columns = '4';
        }
    } else {
        $widget1_columns = '4';
        $widget2_columns = '4';
        $widget3_columns = '4';
        $full_bg_img    = '';
    }

    if ( is_active_sidebar( 'footer2-widgets1' ) || is_active_sidebar( 'footer2-widgets2' ) || is_active_sidebar( 'footer2-widgets3' ) ) {
        $footer_widget_activated = 'footer-widget-activated';
    } else {
        $footer_widget_activated = 'footer-widget-not-activated';
    }
?>

<!-- footer-start -->
<footer>
    <?php if ( is_active_sidebar( 'footer2-widgets1' ) || is_active_sidebar( 'footer2-widgets2' ) || is_active_sidebar( 'footer2-widgets3') ) { ?>
    <div class="footer-area footer-bg footer-bg2  <?php echo esc_attr( $footer_widget_activated ); ?>" style="background-image: url(<?php echo esc_url( $full_bg_img ); ?>);">
        <?php 
          if ( function_exists( 'geoport_framework_init' ) ) { 
            $f2bg_img_id = geoport_get_option('footer2_left_bg_img');
            $attachment2 = wp_get_attachment_image_src( $f2bg_img_id, 'full' );
            $left_bg_img = ( $attachment2 ) ? $attachment2[0] : $f2bg_img_id;
            if (!empty($left_bg_img)) {
        ?>
          <div class="footer-img-bg" style="background-image: url( <?php echo esc_url ( $left_bg_img ) ?> );"></div>
        <?php } ?>
        <?php } ?>
        <div class="container">
            <div class="row">
                <?php if ( is_active_sidebar( 'footer2-widgets1' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget1_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer2-widgets1' ); ?>
                </div>
                <?php } if ( is_active_sidebar( 'footer2-widgets2' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget2_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer2-widgets2' ); ?>
                </div>
                <?php } if ( is_active_sidebar( 'footer2-widgets3' ) ) { ?>
                <div class="col-lg-<?php echo esc_attr( $widget3_columns ); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer2-widgets3' ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php get_template_part( 'footers/copyright' ); ?>

</footer>
<!-- footer-end -->