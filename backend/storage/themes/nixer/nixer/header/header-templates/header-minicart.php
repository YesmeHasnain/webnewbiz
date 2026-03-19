<?php 
$nixer_redux_demo = get_option('redux_demo');
?>
<?php
if (WC()->cart) {
	$cart_total = WC()->cart->get_total();
	$cart_total_plain = floatval(WC()->cart->get_total('raw'));
} else {
	$cart_total_plain = 0;
} ?>
<div class="cartmini__area">
	<div class="cartmini__wrapper p-relative d-flex justify-content-between flex-column">
		<div class="cartmini__close">
			<button type="button" class="cartmini__close-btn cartmini-close-btn"><i class="fal fa-times"></i></button>
		</div>
		<div class="cartmini__top-wrapper">
			<div class="cartmini__top p-relative">
				<div class="cartmini__top-title">
					<h4><?php echo wp_kses_post(!empty($nixer_redux_demo['mini-title']) ? $nixer_redux_demo['mini-title'] : 'Shopping Cart'); ?></h4>
				</div>
			</div>
			<?php if (!empty($nixer_redux_demo['mini-shipping-switch'])): ?>
				<div class="cartmini__shipping">
					<?php if (!empty($nixer_redux_demo['mini-shipping-text-1'])): ?>
						<p>
							<?php echo esc_attr($nixer_redux_demo['mini-shipping-text-1']); ?>
							<span><?php echo esc_html($nixer_redux_demo['mini-shipping-text-price']); ?></span>
						</p>
					<?php endif ?>
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" 
							role="progressbar" 
							style="width: 0%;"
							id="progress-bar"
							aria-valuenow="0" 
							aria-valuemin="0" 
							aria-valuemax="100">
						</div>
					</div>
					<?php if (!empty($nixer_redux_demo['mini-shipping-text-free'])): ?>
						<p id="free-shipping-message" class="mt-5" style="display: none; color: green; font-weight: bold;">
						    <?php echo esc_attr($nixer_redux_demo['mini-shipping-text-free']); ?>
						</p>
					<?php endif ?>
					<?php
					$price = $nixer_redux_demo['mini-shipping-text-price'];
					$number = (int) filter_var($price, FILTER_SANITIZE_NUMBER_INT);
					?>
					<script>
					document.addEventListener("DOMContentLoaded", function() {
					    let orderValue = <?php echo esc_attr($cart_total_plain); ?>;
					    let freeShippingThreshold = <?php echo esc_attr($number); ?>;
					    let progressBar = document.getElementById("progress-bar");
					    let message = document.getElementById("free-shipping-message");

					    let progress = Math.min((orderValue / freeShippingThreshold) * 100, 100);
					    
					    progressBar.style.width = progress + "%";
					    progressBar.setAttribute("aria-valuenow", progress);

					    if (orderValue >= freeShippingThreshold) {
					        message.style.display = "block";
					    }
					});
					</script>
				</div>
			<?php endif ?>
			<div class="cartmini__widget">
				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						/**
						 * This filter is documented in woocommerce/templates/cart/cart.php.
						 *
						 * @since 2.1.0
						 */
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="cartmini__widget-item <?php echo wp_kses_post( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
							<?php if ( empty( $product_permalink ) ) : ?>
								<div class="cartmini__thumb">
									<?php echo wp_kses_post($thumbnail); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							<?php else : ?>
								<div class="cartmini__thumb">
									<a href="<?php echo wp_kses_post( $product_permalink ); ?>">
										<?php echo wp_kses_post($thumbnail); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="cartmini__content">
								<h5 class="cartmini__title">
									<?php if (empty( $product_permalink )) { ?>
										<span><?php echo wp_kses_post( $product_name ); ?></span>
									<?php } else { ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>">
											<?php echo wp_kses_post( $product_name ); ?>
										</a>
									<?php } ?>
								</h5>
								<div class="cartmini__price-wrapper">
									<span class="cartmini__price">
										<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $product_price, $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
									<span class="cartmini__quantity">
										<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								</div>
							</div>
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="cartmini__del" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-regular fa-xmark"></i></a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr( sprintf( __( 'Remove %s from cart', 'nixer' ), wp_strip_all_tags( $product_name ) ) ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
						</div>
						<?php
					}
				}
				do_action( 'woocommerce_mini_cart_contents' );
				?>
			</div>
		</div>
		<div class="cartmini__checkout">
			<div class="cartmini__checkout-title mb-30">
				<?php
				/**
				 * Hook: woocommerce_widget_shopping_cart_total.
				 *
				 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
				 */
				do_action( 'woocommerce_widget_shopping_cart_total' );
				?>
			</div>
			<?php if ((!empty($nixer_redux_demo['mini-link-btn-1']) && !empty($nixer_redux_demo['mini-text-btn-1'])) || (!empty($nixer_redux_demo['mini-link-btn-2']) && !empty($nixer_redux_demo['mini-text-btn-2']))): ?>
				<div class="cartmini__checkout-btn">
					<?php if (!empty($nixer_redux_demo['mini-link-btn-1']) && !empty($nixer_redux_demo['mini-text-btn-1'])): ?>
						<a href="<?php echo esc_attr($nixer_redux_demo['mini-link-btn-1']); ?>" class="tp-btn mb-10 w-100"> 
							<?php echo esc_attr($nixer_redux_demo['mini-text-btn-1']); ?>
						</a>
			 		<?php endif ?>
			 		<?php if (!empty($nixer_redux_demo['mini-link-btn-2']) && !empty($nixer_redux_demo['mini-text-btn-2'])): ?>
						<a href="<?php echo esc_attr($nixer_redux_demo['mini-link-btn-2']); ?>" class="tp-btn tp-btn-border w-100"> 
							<?php echo esc_attr($nixer_redux_demo['mini-text-btn-2']); ?>
						</a>
					<?php endif ?>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>