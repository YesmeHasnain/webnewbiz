<?php
	extract( $args );
?>
<div class="desktop-header header16 d-none d-lg-block">	
	<?php vasia_promo_block(); ?>
	<?php vasia_header_topbar(); ?>
	<div class="main-header <?php echo vasia_header_sticky(); ?>">
		<div class="container">
			<div class="main-header-content ">
				<div class="row">
					<div class="col col-2">
						<div id="_desktop_logo_">
							<?php vasia_site_logo(); ?>
						</div>
					</div>
					<div class="col col-7">
						<?php if($vertical_menu){ ?>
							<div class="vertical-menu">
								<?php vasia_vertical_menu(); ?>
							</div>
						<?php } ?>
						<div class="main-menu menu-background ">
							<div id="_desktop_menu_">
								<?php vasia_main_menu(); ?>
							</div>
						</div>
					</div>
					<div class="col col-3 col-header-icon text-right">
						<?php vasia_header_search_with_icon(); ?>
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
	</div>
	
</div>
