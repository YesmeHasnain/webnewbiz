<?php
/**
 * Footer Template  File
 *
 * @package EARLS
 * @author  Template Path
 * @version 1.0
 */

	$options = earls_WSH()->option();
	$allowed_html = wp_kses_allowed_html( 'post' );

	$footer_bg_v2 = $options->get( 'footer_bg_image_v2' );
	$footer_bg_v2 = earls_set( $footer_bg_v2, 'url');
?>
    
    <!-- main-footer -->
    <footer class="main-footer two m-0" <?php if($footer_bg_v2){ ?>style="background-image: url(<?php echo esc_url($footer_bg_v2); ?>)"<?php } ?>>
        <?php if ( is_active_sidebar( 'footer-sidebar' ) ) { ?>
        <div class="medium-container">
            <div class="row clearfix">
                <?php dynamic_sidebar( 'footer-sidebar' ); ?>
            </div>
        </div>
        <?php } ?>
    </footer>
    <!-- main-footer end -->
    