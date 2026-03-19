<?php

	$widgets = wp_get_sidebars_widgets();
	$shop_filters_area_widgets_counter = (count($widgets['shop-filters-area']) >= 5) ? 4 : count($widgets['shop-filters-area']);

	foreach( $widgets['shop-filters-area'] as $k ) {
		if(strpos($k, 'monster-') !== false) {
			$shop_filters_area_widgets_counter = 4;
		}
	}

?>

<div class="site-shop-filters">
	<div class="site-shop-filters-inside">

		<?php if ( 1 == Nova_OP::getOption('shop_sidebar') ) : ?>

			<?php if (isset($widgets['shop-widget-area'])) : ?>

				<aside class="widget-area shop-widget-area hide-for-large">

					<?php dynamic_sidebar( 'shop-widget-area' ); ?>

				</aside>

			<?php endif; ?>

		<?php endif; ?>

		<?php if (isset($widgets['shop-filters-area'])) : ?>

			<aside class="widget-area">

				<div class="row small-up-1 medium-up-2 large-up-<?php echo esc_attr($shop_filters_area_widgets_counter); ?> shop-filters-area-content">
					<?php dynamic_sidebar( 'shop-filters-area' ); ?>
				</div>

			</aside>

		<?php endif; ?>

	</div>
</div>