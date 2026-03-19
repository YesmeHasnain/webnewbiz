<?php
$options = earls_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );

//Mian Logo Settings
$main_logo = $options->get( 'main_color_logo' );
$main_logo_dimension = $options->get( 'main_color_logo_dimension' );

$logo_type = '';
$logo_text = '';
$logo_typography = ''; ?>

	
    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><i class="fas fa-times"></i></div>
        
        <nav class="menu-box">
            <div class="nav-logo">
            	<?php echo earls_logo( $logo_type, $main_logo, $main_logo_dimension, $logo_text, $logo_typography ); ?>
            </div>
            
            <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
            
            <?php if( $options->get( 'show_mobile_info_v1' )){ ?>
            <div class="contact-info">
                <h4><?php echo wp_kses($options->get('mobile_info_title_v1'), true); ?></h4>
                <ul>
                    <?php if($options->get('mobile_address_v1')){ ?><li><?php echo wp_kses($options->get('mobile_address_v1'), true); ?></li><?php } ?>
                    <?php if($options->get('mobile_phone_no_v1')){ ?><li><a href="tel:<?php echo esc_attr($options->get('mobile_phone_no_v1')); ?>"><?php echo wp_kses($options->get('mobile_phone_no_v1'), true); ?></a></li><?php } ?>
                    <?php if($options->get('mobile_email_address_v1')){ ?><li><a href="mailto:<?php echo esc_attr($options->get('mobile_email_address_v1')); ?>"><?php echo wp_kses($options->get('mobile_email_address_v1'), true); ?></a></li><?php } ?>
                </ul>
            </div>
            <?php } ?>
            
            <?php
				if( $options->get('show_mobile_social_icon_v1') ):
				$icons = $options->get( 'mobile_header_social_icon_v1' );
				if ( ! empty( $icons ) ) :
			?>
            <div class="social-links">
                <ul class="clearfix">
                    <?php
						foreach ( $icons as $h_icon ) :
						$header_social_icons = json_decode( urldecode( earls_set( $h_icon, 'data' ) ) );
						if ( earls_set( $header_social_icons, 'enable' ) == '' ) {
							continue;
						}
						$icon_class = explode( '-', earls_set( $header_social_icons, 'icon' ) );
					?>
					<li><a href="<?php echo esc_url(earls_set( $header_social_icons, 'url' )); ?>" <?php if( earls_set( $header_social_icons, 'background' ) || earls_set( $header_social_icons, 'color' ) ):?>style="background-color:<?php echo esc_attr(earls_set( $header_social_icons, 'background' )); ?>; color: <?php echo esc_attr(earls_set( $header_social_icons, 'color' )); ?>"<?php endif;?>><span class="fab <?php echo esc_attr( earls_set( $header_social_icons, 'icon' ) ); ?>"></span></a></li>
					<?php endforeach; ?>
                </ul>
            </div>
            <?php endif; endif; ?>
        </nav>
    </div>
    <!-- End Mobile Menu -->
    
