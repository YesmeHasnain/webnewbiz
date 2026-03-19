<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="row">
	<div class="col-xl-7 col-lg-7">
		<div class="tp-checkout-verify">
			<div class="tp-checkout-verify-item">
				<div class="woocommerce-form-coupon-toggle">
					<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'nixer' ) . ' <a href="#" class="showcoupon tp-checkout-coupon-form-reveal-btn">' . esc_html__( 'Click here to enter your code', 'nixer' ) . '</a>' ), 'notice' ); ?>
				</div>

				<form class="checkout_coupon woocommerce-form-coupon tp-return-customer" method="post" style="display:none">

					<label><?php esc_html_e( 'Coupon Code:', 'nixer' ); ?></label>

					<div class="tp-return-customer-input">
						<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon Code :', 'nixer' ); ?></label>
						<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon', 'nixer' ); ?>" id="coupon_code" value="" />
					</div>
					
					<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'nixer' ); ?>"><?php esc_html_e( 'Apply', 'nixer' ); ?></button>
				
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>
</div>