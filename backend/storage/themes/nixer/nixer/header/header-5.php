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

	<?php get_template_part( 'header/header-templates/header', 'canvas' ); ?>

	<header id="header-sticky" class="tp-header-5-ptb tp-header-transparent">
		<div class="tp-header-main-sticky p-relative">
			<div class="container container-1720">
				<div class="tp-header-5-border d-flex justify-content-between align-items-center">
					<div class="tp-header-5-leftside d-flex align-items-center">
						<div class="tp-header-5-logo">
							<a href="<?php echo esc_url(home_url('/')); ?>">
								<?php if (!empty($nixer_redux_demo['h5-logo-black']['url'])) { ?>
									<img data-width="120" src="<?php echo esc_url($nixer_redux_demo['h5-logo-black']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
								<?php } else { ?>
									<img data-width="120" src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/logo/logo.png" alt="<?php bloginfo( 'name' ); ?>">
								<?php } ?>
							</a>
						</div>
						<div class="tp-header-5-menu inner-white d-none d-xl-block">
							<div class="tp-main-menu inner-white text-cap">
								<nav class="tp-mobile-menu-active">
									<?php
									wp_nav_menu( 
										array( 
											'theme_location' 	=> 'tertiary',
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
					</div>
					<?php if (!empty($nixer_redux_demo['h5-right-item'])) { ?>
						<div class="tp-header-5-rightside d-flex align-items-center">
							<?php if (!empty($nixer_redux_demo['h5-button-switch-1'])): ?>
								<?php if (!empty($nixer_redux_demo['h5-link-button-1']) && !empty($nixer_redux_demo['h5-text-button-1'])): ?>
									<div class="tp-header-5-call d-none d-xl-block">
										<a href="<?php echo esc_attr($nixer_redux_demo['h5-link-button-1']); ?>">
											<?php echo esc_attr($nixer_redux_demo['h5-text-button-1']); ?>
										</a>
									</div>
								<?php endif ?>
							<?php endif ?>
							<?php if (!empty($nixer_redux_demo['h5-button-switch-2'])): ?>
								<?php if (!empty($nixer_redux_demo['h5-link-button-2']) && !empty($nixer_redux_demo['h5-text-button-2'])): ?>
									<div class="tp-header-5-btn d-none d-xl-block">
										<a href="<?php echo esc_attr($nixer_redux_demo['h5-link-button-2']); ?>" class="tp-btn-5">
											<?php echo esc_attr($nixer_redux_demo['h5-text-button-2']); ?>
										</a>
									</div>
								<?php endif ?>
							<?php endif ?>
							<div class="tp-header-hamburger offcanvas-open-btn d-block d-xl-none">
								<button class="tp-hamburger-btn">
									<span></span>
									<span></span>
								</button>
							</div>
						</div>
					<?php } else { ?>
						<div class="tp-header-5-rightside d-flex align-items-center d-block d-xl-none">
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
	</header>
	<div id="smooth-wrapper">
		<div id="smooth-content">