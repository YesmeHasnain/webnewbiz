<?php
	extract( $args );
?>
<div class="desktop-header header11 d-none d-lg-block">	
	<?php vasia_promo_block(); ?>
	<?php vasia_header_topbar(); ?>
	<div class="main-header ">
		<div class="container">
			<div class="main-header-content ">
				<div class="row">
					
					<div class="col col-4 col-header-icon left">
						<?php vasia_language_switcher();  ?> 
						<?php vasia_currency_switcher();  ?> 
						<?php vasia_header_search_with_icon(); ?>
					</div>
					<div class="col col-4 col-logo">
						<div id="_desktop_logo_">
							<?php vasia_site_logo(); ?>
						</div>
					</div>
					<div class="col col-4 col-header-icon text-right">
						<?php vasia_header_account(); ?>
						<div id="_desktop_wishlist_">
							<?php vasia_wishlist(); ?>
						</div>
						
						<?php if(is_woocommerce_activated()) : ?>
							<?php vasia_header_cart(); ?>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
		<?php if($vertical_menu): ?>
			<div class="top-menu menu-background <?php echo vasia_header_sticky(); ?>">
				<div class="container">
					<div class="row">
						<div class="col col-ver ">
							<div class="vertical-menu">
								<?php vasia_vertical_menu(); ?>
							</div>
						</div>
						<div class="col col-hoz ">
							<div class="main-menu">
								<div id="_desktop_menu_">
									<?php vasia_main_menu(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="top-menu menu-background <?php echo vasia_header_sticky(); ?>">
				<div class="container">
					<div class="main-menu">
						<div id="_desktop_menu_">
							<?php vasia_main_menu(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
	
</div>
