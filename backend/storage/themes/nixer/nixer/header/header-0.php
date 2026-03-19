<?php $nixer_redux_demo = get_option('redux_demo'); 
$nixer_page_id = get_query_var('nixer_page_id', 0); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
		<?php if(isset($nixer_redux_demo['favicon']['url'])){?>
		<link rel="shortcut icon" href="<?php echo esc_url($nixer_redux_demo['favicon']['url']); ?>">
		<?php }?>
	<?php }?>
	<?php wp_head(); ?>
</head>
<body id="body" <?php body_class('tp-magic-cursor'); ?>>
	<?php
		wp_body_open();
	?>
	<?php if (!empty($nixer_redux_demo['pre-switch'])): ?>
		<div id="loading">
			<div class="loader-mask">
				<div class="loader">
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	<?php endif ?>
	<div id="magic-cursor">
		<div id="ball"></div>
	</div>
	<div class="back-to-top-wrapper">
		<button id="back_to_top" type="button" class="back-to-top-btn">
			<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>               
		</button>
	</div>
	<?php if (!empty($nixer_redux_demo['h0-right-item']) && !empty($nixer_redux_demo['h0-mini-cart-switch'])): ?>
		<?php get_template_part( 'header/header-templates/header', 'minicart' ); ?>
	<?php endif ?>
	<?php get_template_part( 'header/header-templates/header', 'canvas' ); ?>
	<header class="tp-header-black-inner-ptb tp-header-transparent">
		<div id="header-sticky" class="tp-header-main-sticky p-relative">
			<div class="container container-1760">
				<div class="d-flex justify-content-between align-items-center">
					<div class="tp-header-logo">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<?php if (!empty($nixer_redux_demo['h0-logo-white']['url'])) { ?>
								<img class="white d-block d-lg-block d-xl-block" data-width="120" src="<?php echo esc_url($nixer_redux_demo['h0-logo-white']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } else { ?>
								<img class="white d-block d-lg-block d-xl-block" data-width="120" src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/logo/logo.png" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
							<?php if (!empty($nixer_redux_demo['h0-logo-black']['url'])) { ?>
								<img class="black d-none d-lg-none d-xl-none" data-width="120" src="<?php echo esc_url($nixer_redux_demo['h0-logo-black']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } else { ?>
								<img class="black d-none d-lg-none d-xl-none" data-width="120" src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/logo/logo-black.png" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</a>
					</div>
					<div class="tp-header-inner-box d-flex align-items-center justify-content-between">
						<div class="tp-header-inner-menu inner-white d-none d-xl-block">
							<div class="tp-main-menu inner-white">
								<nav class="tp-mobile-menu-active">
									<?php
									wp_nav_menu( 
										array( 
											'theme_location' 	=> 'primary',
											'container' 		=> '',
											'menu_class' 		=> '',
											'menu_id' 			=> '',
											'menu'            	=> '',
											'container_class' 	=> '',
											'container_id'    	=> '',
											'echo'            	=> true,
											'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
											'walker'            => new nixer_wp_bootstrap_navwalker(),
											'before'          	=> '',
											'after'           	=> '',
											'link_before'     	=> '',
											'link_after'      	=> '',
											'items_wrap'      	=> '<ul class="text_cap %2$s">%3$s</ul>',
											'depth'           	=> 3,
										)
									); ?>
								</nav>
							</div>
						</div>
						<?php if (!empty($nixer_redux_demo['h0-right-item'])) { ?>
							<div class="tp-header-inner-right inner-white d-flex align-items-center">
								<?php if (!empty($nixer_redux_demo['h0-mini-cart-switch'])): ?>
									<div class="tp-header-inner-cart p-relative">
										<button class="cartmini-open-btn">
											<span>
												<svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20" fill="none">
													<path d="M5.14062 6.09765V5.22429C5.14062 3.19845 6.90564 1.20862 9.09973 1.01955C11.7131 0.785448 13.917 2.68524 13.917 5.05322V6.29573M12.943 9.99602H12.9517M6.11483 9.99602H6.12359M6.60789 19H12.4588C16.3789 19 17.081 17.5504 17.2858 15.7857L18.0171 10.3834C18.2804 8.18652 17.5978 6.39478 13.4339 6.39478H5.63274C1.46885 6.39478 0.786251 8.18652 1.04954 10.3834L1.7809 15.7857C1.98568 17.5504 2.68779 19 6.60789 19Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
												</svg>
											</span>
											<em><?php echo WC()->cart->get_cart_contents_count(); ?></em>
										</button>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['h0-canvas-switch'])) { ?>
									<div class="tp-header-hamburger offcanvas-open-btn">
								<?php } else { ?>
									<div class="tp-header-hamburger offcanvas-open-btn d-block d-xl-none">
								<?php } ?>
										<button class="tp-hamburger-btn">
											<span></span>
											<span></span>
										</button>
									</div>
							</div>
						<?php } else { ?>
							<div class="tp-header-inner-right inner-white d-flex align-items-center d-block d-xl-none">
								<div class="tp-header-hamburger offcanvas-open-btn d-block d-xl-none">
									<button class="tp-hamburger-btn">
										<span></span>
										<span></span>
									</button>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</header>
	<div id="smooth-wrapper">
		<div id="smooth-content">
