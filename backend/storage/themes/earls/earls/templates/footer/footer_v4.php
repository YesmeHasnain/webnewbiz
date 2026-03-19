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

	$footer_logo_v4 = $options->get( 'footer_logo_v4' );
	$footer_logo_v4 = earls_set( $footer_logo_v4, 'url');
?>
    
    <!-- main-footer -->
    <footer class="main-footer-four see__pad p_relative">
        <div class="content-container">
            <?php if($options->get('show_footer_pattern_v4')){ ?>
            <div class="anim-icon">
                <div class="icon icons-1 " data-parallax='{"y": 200}' style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shape/shape-03.png);"></div>
                <div class="icon icons-2 " data-parallax='{"y": 200}' style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shape/shape-04.png);"></div>
                <div class="icon icons-3 " data-parallax='{"y": 200}' style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shape/shape-11.png);"></div>
                <div class="icon icons-4 " data-parallax='{"y": 200}' style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shape/shape-12.png);"></div>
            </div>
            <?php } ?>
            
            <div class="footer__content text-center">
                <div class="widget__content">
                    
					<?php if($footer_logo_v4){ ?>
                    <div class="footer__logo">
                        <figure>
                            <img src="<?php echo esc_url($footer_logo_v4); ?>" alt="<?php esc_attr_e('Awesome Image', 'earls'); ?>">
                        </figure>
                    </div>
                    <?php } ?>
                    
					<?php
						if( $options->get('show_footer_social_icon_v4') ):
						$icons = $options->get( 'footer_header_social_icon_v4' );
						if ( ! empty( $icons ) ) :
					?>
                    <div class="footer__social__midea">
                        <ul>
                            <?php
								foreach ( $icons as $h_icon ) :
								$header_social_icons = json_decode( urldecode( earls_set( $h_icon, 'data' ) ) );
								if ( earls_set( $header_social_icons, 'enable' ) == '' ) {
									continue;
								}
								$icon_class = explode( '-', earls_set( $header_social_icons, 'icon' ) );
							?>
							<li><a href="<?php echo esc_url(earls_set( $header_social_icons, 'url' )); ?>" <?php if( earls_set( $header_social_icons, 'background' ) || earls_set( $header_social_icons, 'color' ) ):?>style="background-color:<?php echo esc_attr(earls_set( $header_social_icons, 'background' )); ?>; color: <?php echo esc_attr(earls_set( $header_social_icons, 'color' )); ?>"<?php endif;?>><span class="social_media fab <?php echo esc_attr( earls_set( $header_social_icons, 'icon' ) ); ?>"></span></a></li>
							<?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; endif; ?>
                    
                    <?php if($options->get('show_footer_info_v4')){ ?>
                    <div class="footer__middel__text">
                        <p><?php echo wp_kses($options->get('footer_address_v4'), true); ?> </p>
                        <p> <?php echo wp_kses($options->get('footer_phone_no_v4'), true); ?></p>
                        <p><?php echo wp_kses($options->get('footer_working_days_v4'), true); ?></p>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="footer___bottom">
                    <div class="copy__right">
                        <p> <?php echo wp_kses($options->get('copyright_text4', 'Copyright By © <a href="#">EARLS</a> - 2023'), true); ?></p>
                    </div>
                </div>
                
            </div>
        </div>
    </footer>
    <!-- main-footer end -->  