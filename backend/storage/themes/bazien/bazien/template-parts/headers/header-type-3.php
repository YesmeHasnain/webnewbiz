<?php
$header_class = "";
$header_wide = "";
if(
	'on' == Nova_OP::getOption('header_transparent')
	|| 'transparency_light' == get_post_meta( nova_get_page_id(), 'metabox_header_transparency', true )
	|| 'transparency_dark' == get_post_meta( nova_get_page_id(), 'metabox_header_transparency', true )
)
{
	$header_class .= "header-transparent ";
	$header_class	.= get_post_meta( nova_get_page_id(), 'metabox_header_transparency', true );
}else {
	$header_class .= "header-static ";
}
$header_class .= " ".Nova_OP::getOption('header_menu_position');
if('on' == Nova_OP::getOption('header_wide')) {
	$header_wide = 'nova-container-fluid';
}else {
	$header_wide = 'nova-container';
}

 ?>
<header id="masthead" class="nova-header header-type-3 <?php echo esc_attr($header_class) ?> headroom">
	<div class="<?php echo esc_attr($header_wide)?> align-middle">
		<div class="header-left-items header-items">
			<div class="nova-header__branding header-branding site-secondary-font">

			<?php if ( ! empty( Nova_OP::getOption('header_logo') ) ) : ?>

				<div class="site-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img class="logo-dark" src="<?php echo esc_url( Nova_OP::getOption('header_logo') ); ?>" title="<?php bloginfo('name'); ?>" alt="<?php bloginfo('name'); ?>">
						<img class="logo-light" src="<?php echo esc_url( Nova_OP::getOption('header_logo_light') ); ?>" title="<?php bloginfo('name'); ?>" alt="<?php bloginfo('name'); ?>">
					</a>
				</div>

			<?php else : ?>

				<div class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a></div>

			<?php endif; ?>

		</div>
		</div>
		<div class="header-center-items header-items has-menu">
			<?php
			if ( class_exists( 'Nova_Mega_Menu_Walker' ) ) {
				echo '<nav class="main-navigation header-primary-nav">';
				wp_nav_menu(array(
					'theme_location'    => 'nova_menu_primary',
					'container'         => false,
					'menu_class'        => 'menu nav-menu',
					'link_before'       => '',
					'link_after'        => '',
					'fallback_cb'     	=> 'Nova_Mega_Menu_Walker',
					'walker'            => new Nova_Mega_Menu_Walker(),
				));
				echo '</nav>';
			}else{
				echo '<nav class="main-navigation header-primary-nav">';
				wp_nav_menu(array(
					'theme_location'    => 'nova_menu_primary',
					'container'         => false,
					'menu_class'        => 'menu nav-menu',
					'link_before'       => '',
					'link_after'        => ''
				));
				echo '</nav>';
			}
			?>
		</div>
		<div class="header-right-items header-items">
			<div class="nova-header__right-action">
				<ul class="actions">
						<?php if ( NOVA_WOOCOMMERCE_IS_ACTIVE ) : ?>
						<?php if ( Nova_OP::getOption('header_user_account') == 1 ) : ?>
							<li class="header-account">
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
									<svg class="svg-icon">
									 <use xlink:href="#bazien-menu-user"></use>
									</svg>
								</a>
							</li>
						<?php endif; ?>
						<?php endif; ?>
						<?php if ( NOVA_WISHLIST_IS_ACTIVE ) : ?>
						<?php if ( Nova_OP::getOption('header_wishlist') == 1 ) : ?>
							<li class="header-wishlist">
								<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>">
									<svg class="svg-icon">
									 <use xlink:href="#bazien-wishlist"></use>
									</svg>
								</a>
							</li>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( Nova_OP::getOption('header_search_toggle') == 1 ) : ?>
						<li class="header-search">
							<a data-toggle="headerSearchModal">
								<svg class="svg-icon svg-menu-search">
								 <use xlink:href="#bazien-search"></use>
								</svg>
							</a>
							<?php get_template_part( 'template-parts/headers/search-modal' ) ?>
						</li>
					<?php endif; ?>
						<?php if ( NOVA_WOOCOMMERCE_IS_ACTIVE ) : ?>
							<?php if ( Nova_OP::getOption('header_cart') == 1 ) : ?>
								<li class="header-cart">
									<a data-toggle="MiniCartCanvas">
										<div class="header-cart-box">
											<svg class="svg-icon svg-bag-icon">
											 <use xlink:href="#bazien-bag"></use>
											</svg>
											<div class="count-badge js_count_bag_item"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></div>
										</div>
									</a>
								</li>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( Nova_OP::getOption('header_burger_menu') == 1 && defined('SF_VERSION_NUM') ) : ?>
					<li>
						<a id="js_bazien_burger_menu">
							<svg class="svg-burger-icon">
							 <use xlink:href="#bazien-burger-menu"></use>
							</svg>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</header>
