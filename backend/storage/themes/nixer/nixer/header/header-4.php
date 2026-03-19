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
<body id="body" <?php body_class(); ?>>
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

	<div class="back-to-top-wrapper">
		<button id="back_to_top" type="button" class="back-to-top-btn">
			<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>               
		</button>
	</div>

	<div class="tp-offcanvas-2-area nixer-dark p-relative">
		<div class="tp-offcanvas-2-bg is-left left-box"></div>
		<div class="tp-offcanvas-2-bg is-right right-box d-none d-md-block"></div>
		<div class="tp-offcanvas-2-wrapper">
			<div class="tp-offcanvas-2-left left-box p-relative">
				<div class="tp-offcanvas-2-left-wrap d-flex justify-content-between align-items-center">
					<div class="tp-offcanvas__logo">
						<a class="logo-1" href="<?php echo esc_url(home_url('/')); ?>">
							<?php if (!empty($nixer_redux_demo['h4-logo-menu']['url'])) { ?>
								<img data-width="120" src="<?php echo esc_url($nixer_redux_demo['h4-logo-menu']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } else { ?>
								<img data-width="120" src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/logo/logo.png" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</a>
					</div>
					<div class="tp-offcanvas-2-close d-md-none text-end">
						<button class="tp-offcanvas-2-close-btn tp-offcanvas-2-close-btn">
							<?php if (!empty($nixer_redux_demo['h4-text-close'])): ?>
								<span class="text">
									<span><?php echo esc_html($nixer_redux_demo['h4-text-close']); ?></span>
								</span>
							<?php endif ?>
							<span class="d-inline-block">
								<span>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="32.621" height="1.00918" transform="matrix(0.704882 0.709325 -0.704882 0.709325 1.0061 0)" fill="currentcolor"/>
										<rect width="32.621" height="1.00918" transform="matrix(0.704882 -0.709325 0.704882 0.709325 0 23.2842)" fill="currentcolor"/>
									</svg>
								</span>
							</span>
						</button>
					</div>
				</div>
				<div class="tp-offcanvas-menu counter-row">
					<nav></nav>
				</div>
			</div>
			<div class="tp-offcanvas-2-right right-box d-none d-md-block p-relative">
				<div class="tp-offcanvas-2-close text-end">
					<button class="tp-offcanvas-2-close-btn">
						<?php if (!empty($nixer_redux_demo['h4-text-close'])): ?>
							<span class="text">
								<span><?php echo esc_html($nixer_redux_demo['h4-text-close']); ?></span>
							</span>
						<?php endif ?>
						<span class="d-inline-block">
							<span>
								<svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9.80859 9.80762L28.1934 28.1924" stroke="currentColor" stroke-width="1.5"
										stroke-linecap="round" stroke-linejoin="round" />
									<path d="M9.80859 28.1924L28.1934 9.80761" stroke="currentColor" stroke-width="1.5"
										stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</span>
					</button>
				</div>
				<?php if (!empty($nixer_redux_demo['h4-text-canvas-right'])): ?>
					<div class="tp-offcanvas-2-right-text">
						<h3><?php echo esc_html($nixer_redux_demo['h4-text-canvas-right']); ?></h3>
					</div>
				<?php endif ?>
				<div class="tp-offcanvas-2-right-inner d-flex align-items-end justify-content-end h-100">
					<div class="tp-offcanvas-2-right-info">
						<div class="tp-offcanvas-2-thumb text-end">
							<?php if (!empty($nixer_redux_demo['h4-img-right-menu']['url'])): ?>
								<img src="<?php echo esc_url($nixer_redux_demo['h4-img-right-menu']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<header id="header-sticky" class="tp-header-4-ptb tp-header-transparent">
		<div class="tp-header-main-sticky p-relative">
			<div class="container container-1800">
				<div class="d-flex justify-content-between align-items-center">
					<div class="tp-header-logo">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<?php if (!empty($nixer_redux_demo['h4-logo']['url'])) { ?>
								<img class="black" data-width="120" src="<?php echo esc_url($nixer_redux_demo['h4-logo']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } else { ?>
								<img class="black" data-width="120" src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/logo/logo.png" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</a>
					</div>
					<div class="tp-header-2-hamburger hamburger-four">
						<div class="tp-header-hamburger offcanvas-open-btn">
							<button class="tp-hamburger-btn">
								<span></span>
								<span></span>
							</button>
						</div>
					</div>
					<div class="tp-header-box d-none d-md-flex align-items-center justify-content-between">
						<div class="tp-header-1-menu">
							<div class="tp-main-menu d-none">
								<nav class="tp-mobile-menu-active">
									<?php
									wp_nav_menu(
										array( 
											'theme_location' 	=> 'secondary',
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
											'items_wrap'      	=> '<ul class="%2$s">%3$s</ul>',
											'depth'           	=> 3,
										)
									); ?>
								</nav>
							</div>
						</div>
						<?php if (!empty($nixer_redux_demo['h4-button-switch'])): ?>
							<div class="tp-header-main-right d-flex align-items-center">
								<div class="tp-header-2-right-btn btn-four d-none d-md-block">
									<?php if (!empty($nixer_redux_demo['h4-link-button']) && !empty($nixer_redux_demo['h4-text-button'])): ?>
										<a class="tp-btn-animation" href="<?php echo esc_attr($nixer_redux_demo['h4-link-button']); ?>">
											<?php for ($i = 0; $i < 6; ++$i) { ?>
												<span><?php echo esc_html($nixer_redux_demo['h4-text-button']); ?></span>
											<?php } ?>
										</a>
									<?php endif ?>
								</div>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</header>

	<div id="smooth-wrapper">
		<div id="smooth-content">