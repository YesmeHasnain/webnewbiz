<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$knor_logo = knor_get_option( 'theme_logo' );
$knor_logo_id = isset($knor_logo['id']) && !empty($knor_logo['id']) ? $knor_logo['id'] : '';
$knor_logo_url = isset( $knor_logo[ 'url' ] ) ? $knor_logo[ 'url' ] : '';
$knor_logo_alt = get_post_meta($knor_logo_id,'_wp_attachment_image_alt',true);


$search_bar_enable = knor_get_option('search_bar_enable');

$cta_btn_one = knor_get_option('cta_btn_one');
$cta_btn_one_text = knor_get_option('cta_btn_one_text');
$cta_btn_one_link = knor_get_option('cta_btn_one_link');


$cta_btn_two = knor_get_option('cta_btn_two');
$cta_btn_two_text = knor_get_option('cta_btn_two_text');
$cta_btn_two_link = knor_get_option('cta_btn_two_link');

$btn_1_style = knor_get_option('btn_1_style');
$btn_2_style = knor_get_option('btn_2_style');


?>

<header id="theme-header-one" class="theme-header-main header-style-one">
	
	<div class="theme-header-area">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-2">
				
					<div class="logo theme-logo">
					<?php  
					if ( has_custom_logo() || !empty( $knor_logo_url ) ) {
						if( isset( $knor_logo['url'] ) && !empty( $knor_logo_url ) ) { 
							?>
								<a href="<?php echo esc_url( site_url('/')) ?>" class="logo">
									<img class="img-fluid" src="<?php echo esc_url( $knor_logo_url ); ?>" alt="<?php echo esc_attr( $knor_logo_alt  ) ?>">
								</a>
						    <?php 
						} else {
							 the_custom_logo();
						}

					} else {
						printf('<h1 class="text-logo"><a href="%1$s">%2$s</a></h1>',esc_url(site_url('/')),esc_html(get_bloginfo('name')));
					}
					?>
					</div>

				</div>
				
				<div class="col-lg-7">
					<div class="nav-menu-wrapper">
						<div class="knor-responsive-menu"></div>
						<div class="mainmenu">
							<?php
								wp_nav_menu( array(
									'theme_location' => 'primary',
									'container' => 'nav',
									'container_class' => 'nav-main-wrap',
									'menu_class' => 'theme-navigation-wrap theme-main-menu',
									'menu_id'        => 'primary-menu',
									'fallback_cb'  => 'knor_fallback_menu',
								) );
							?>

						</div>
					</div>	
				</div>
				
				<div class="col-lg-3">
					<div class="header-right-wrapper">
						
						<?php if($cta_btn_one == true) :?>

						<div class="header-login-btn <?php if($btn_1_style == 'btn_bordered' ) { echo "header-btn-bordered"; } else { echo "header-btn-flat"; } ?>">
							<a href="<?php echo esc_url($cta_btn_one_link); ?>" target="_blank"><?php echo esc_html($cta_btn_one_text); ?></a>
						</div>

						<?php endif; ?>

						<?php if($cta_btn_two == true) :?>
						<div class="header-signup-btn <?php if($btn_2_style == 'btn_bordered' ) { echo "header-btn-bordered"; } else { echo "header-btn-flat"; } ?>">
							<a href="<?php echo esc_url($cta_btn_two_link); ?>" target="_blank"><?php echo esc_html($cta_btn_two_text); ?></a>
						</div>
						<?php endif; ?>
						

					</div>
				</div>

			</div>
		</div>
	</div>
</header>
