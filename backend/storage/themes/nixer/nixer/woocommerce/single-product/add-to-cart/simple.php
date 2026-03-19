<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );
		?>
		<div class="tp-product-details-quantity">
			<div class="tp-product-quantity mb-15 mr-15">
				<span class="tp-cart-minus">
					<svg width="11" height="2" viewBox="0 0 11 2" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
				<input 
				type="number" 
				name="quantity" 
				class="tp-cart-input" 
				value="<?php echo esc_attr( isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity() ); ?>" 
				min="<?php echo esc_attr( $product->get_min_purchase_quantity() ); ?>" 
				max="<?php echo esc_attr( ( $product->get_max_purchase_quantity() > 0 ) ? $product->get_max_purchase_quantity() : '' ); ?>" 
				step="1">
				<span class="tp-cart-plus">
					<svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 6H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M5.5 10.5V1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
			</div>
		</div>

		<?php
		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>
		<div class="tp-product-details-add-to-cart mb-15 mr-10">
			<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button tp-product-details-add-to-cart-btn w-100 alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		</div>
		<?php
		if (function_exists('YITH_WCWL')) { 
		?>
			<button type="button" class="tp-product-action-btn tp-product-add-to-wishlist-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
				<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
			</button>
		<?php } ?>
		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
