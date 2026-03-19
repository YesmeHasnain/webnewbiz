<?php
/**
 * Item meta template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

?>

	<div class="vlt-product-content">

		<h3 class="vlt-product-title">

			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

		</h3>
		<!-- /.vlt-product-title -->

		<div class="vlt-product-price">

			<?php

				/**
				* Hook: woocommerce_after_shop_loop_item_title.
				*
				* @hooked woocommerce_template_loop_rating - 5
				* @hooked woocommerce_template_loop_price - 10
				*/
				do_action( 'woocommerce_after_shop_loop_item_title' );

			?>

		</div>
		<!-- /.vlt-product-price -->

	</div>

</article>