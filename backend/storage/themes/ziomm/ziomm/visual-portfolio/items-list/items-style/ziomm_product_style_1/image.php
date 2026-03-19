<?php
/**
 * Item image template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action( 'ziomm_woocommerce_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'ziomm_woocommerce_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart', 10 );

?>

<article <?php wc_product_class( 'vlt-product vlt-product--style-1', $product ); ?>>

	<div class="vp-portfolio__item-img-wrap">

		<div class="vp-portfolio__item-img">

			<?php

				/**
				* Hook: woocommerce_before_shop_loop_item.
				*
				* @hooked woocommerce_template_loop_product_link_open - 10
				*/
				do_action( 'woocommerce_before_shop_loop_item' );

				/**
				 * Hook: woocommerce_before_shop_loop_item_title.
				 *
				 * @hooked woocommerce_show_product_loop_sale_flash - 10
				 * @hooked woocommerce_template_loop_product_thumbnail - 10
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );

				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'ziomm_woocommerce_template_loop_add_to_cart' );

			?>

		</div>

	</div>
	<!-- /.vp-portfolio__item-img-wrap -->