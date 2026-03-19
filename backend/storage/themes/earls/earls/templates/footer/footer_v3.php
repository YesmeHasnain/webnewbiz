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

	$footer_bg_v3 = $options->get( 'footer_bg_image_v3' );
	$footer_bg_v3 = earls_set( $footer_bg_v3, 'url');
?>
    
    <!-- main-footer -->
    <footer class="main-footer three m-0" <?php if($footer_bg_v3){ ?>style="background-image: url(<?php echo esc_url($footer_bg_v3); ?>)"<?php } ?>>
        <div class="medium-container">
            <?php if ( is_active_sidebar( 'footer-sidebar2' ) ) { ?>
            <div class="row">
                <?php dynamic_sidebar( 'footer-sidebar2' ); ?>
            </div>
            <?php } ?>
            
            <div class="footer___bottom">
                <div class="copy__right">
                    <p><?php echo wp_kses($options->get('copyright_text3', 'Copyright By © <a href="#">EARLS</a> - 2023'), true); ?></p>
                </div>
            </div>
            
        </div>
    </footer>
    <!-- main-footer end -->    