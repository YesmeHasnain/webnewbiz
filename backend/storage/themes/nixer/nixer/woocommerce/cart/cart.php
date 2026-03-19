<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;
$nixer_redux_demo = get_option('redux_demo');
?> 

<section class="tp-shop-breadcrumb-ptb pt-160 pb-100 p-relative">
	<div class="tp-about-me-bg" data-background="<?php echo esc_url($nixer_redux_demo['cart-bg']['url']); ?>"></div>
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="tp-about-us-heading">
					<span class="tp-breadcrumb-subtitle">
						<?php echo esc_attr($nixer_redux_demo['cart-heading']); ?>
					</span>
					<h3 class="tp-breadcrumb-title tp-title-anim">
						<?php if ((isset($nixer_redux_demo['cart-title'])) && ('' != $nixer_redux_demo['cart-title'])) { ?>
							<?php echo esc_attr($nixer_redux_demo['cart-title']); ?>
						<?php } else { ?>
							<?php echo esc_html__( 'Shopping Cart', 'nixer' )?>
						<?php } ?>
					</h3>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="tp-cart-area pb-120 pt-120">
	<div class="container">
		<div class="row">
			<div class="col-xl-9 col-lg-8">
				<div class="mr-30">
					<?php do_action( 'woocommerce_before_cart' ); ?>
				</div>
				<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<?php do_action( 'woocommerce_before_cart_table' ); ?>
					<div class="tp-cart-list mb-25 mr-30">
						<table class="table shop_table_responsive woocommerce-cart-form__contents" cellspacing="0">
							<thead>
								<tr>
									<th colspan="2" class="tp-cart-header-product"><?php esc_html_e( 'Product', 'nixer' ); ?></th>
									<th class="tp-cart-header-price"><?php esc_html_e( 'Price', 'nixer' ); ?></th>
									<th class="tp-cart-header-quantity"><?php esc_html_e( 'Quantity', 'nixer' ); ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php do_action( 'woocommerce_before_cart_contents' ); ?>

								<?php
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
									/**
									 * Filter the product name.
									 *
									 * @since 2.1.0
									 * @param string $product_name Name of the product in the cart.
									 * @param array $cart_item The product in the cart.
									 * @param string $cart_item_key Key for the product in the cart.
									 */
									$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

									if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
										$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
										?>
										<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

											<td class="tp-cart-img">
											<?php
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

											if ( ! $product_permalink ) {
												echo esc_html($thumbnail); // PHPCS: XSS ok.
											} else {
												printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
											}
											?>
											</td>

											<td class="tp-cart-title" data-title="<?php esc_attr_e( 'Product', 'nixer' ); ?>">
											<?php
											if ( ! $product_permalink ) {
												echo wp_kses_post( $product_name . '&nbsp;' );
											} else {
												/**
												 * This filter is documented above.
												 *
												 * @since 2.1.0
												 */
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											// Meta data.
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'nixer' ) . '</p>', $product_id ) );
											}
											?>
											</td>

											<td class="tp-cart-price" data-title="<?php esc_attr_e( 'Price', 'nixer' ); ?>">
												<?php
													echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
												?>
											</td>

											<td class="tp-cart-quantity tp-product-details-quantity">
												<div class="tp-product-quantity mt-10 mb-10">
													<span class="tp-cart-minus">
														<svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M1 1H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
														</svg>
													</span>
													<?php
													if ( $_product->is_sold_individually() ) {
														$min_quantity = 1;
														$max_quantity = 1;
													} else {
														$min_quantity = 1;
														$max_quantity = $_product->get_max_purchase_quantity();

														if ( $max_quantity <= 0 ) {
															$max_quantity = '';
														}
													} ?>
													<input class="tp-cart-input"
														type="number" 
														name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" 
														value="<?php echo esc_attr($cart_item['quantity']); ?>" 
														min="<?php echo esc_html($min_quantity); ?>" 
														max="<?php echo esc_html($max_quantity); ?>">
													<span class="tp-cart-plus">
														<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path d="M5 1V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
															<path d="M1 5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
														</svg>
													</span>
												</div>
											</td>

											<td class="tp-cart-action">
												<?php
													echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														'woocommerce_cart_item_remove_link',
														sprintf(
															'<a href="%s" class="tp-cart-action-btn remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.53033 1.53033C9.82322 1.23744 9.82322 0.762563 9.53033 0.46967C9.23744 0.176777 8.76256 0.176777 8.46967 0.46967L5 3.93934L1.53033 0.46967C1.23744 0.176777 0.762563 0.176777 0.46967 0.46967C0.176777 0.762563 0.176777 1.23744 0.46967 1.53033L3.93934 5L0.46967 8.46967C0.176777 8.76256 0.176777 9.23744 0.46967 9.53033C0.762563 9.82322 1.23744 9.82322 1.53033 9.53033L5 6.06066L8.46967 9.53033C8.76256 9.82322 9.23744 9.82322 9.53033 9.53033C9.82322 9.23744 9.82322 8.76256 9.53033 8.46967L6.06066 5L9.53033 1.53033Z" fill="currentColor"/></svg><span>Remove</span></a>',
															esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
															/* translators: %s is the product name */
															esc_attr( sprintf( __( 'Remove %s from cart', 'nixer' ), wp_strip_all_tags( $product_name ) ) ),
															esc_attr( $product_id ),
															esc_attr( $_product->get_sku() )
														),
														$cart_item_key
													);
												?>
											</td>
										</tr>
										<?php
									}
								}
								?>

								<?php do_action( 'woocommerce_cart_contents' ); ?>

								<?php do_action( 'woocommerce_after_cart_contents' ); ?>
							</tbody>
						</table>
					</div>
					<?php do_action( 'woocommerce_after_cart_table' ); ?>

					<div class="tp-cart-bottom">
						<div class="row align-items-end">
							<div class="col-xl-6 col-md-8">
								<?php if ( wc_coupons_enabled() ) { ?>
									<div class="coupon tp-cart-coupon-input-box">
										<label for="coupon_code"><?php esc_html_e( 'Coupon Code:', 'nixer' ); ?></label>
										<div class="tp-cart-coupon-input d-flex align-items-center"> 
											<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter Coupon Code', 'nixer' ); ?>" /> 
											<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'nixer' ); ?>"><?php esc_html_e( 'Apply', 'nixer' ); ?></button>
											<?php do_action( 'woocommerce_cart_coupon' ); ?>
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="col-xl-6 col-md-4">
								<div class="tp-cart-update text-md-end">
									<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'nixer' ); ?>"><?php esc_html_e( 'Update cart', 'nixer' ); ?></button>
									<?php do_action( 'woocommerce_cart_actions' ); ?>
								</div>
							</div>
							<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-xl-3 col-lg-4 col-md-6">
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
				
				<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
				?>
				
			</div>
		</div>
	</div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
