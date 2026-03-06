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
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 19.9.9
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'barab' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'barab' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post">
	<div class="row">
		<div class="form-group">
			<input type="text" name="coupon_code" class="form-control" placeholder="<?php esc_attr_e( 'Coupon code', 'barab' ); ?>" id="coupon_code" value="" />
		</div>
		<div class="form-group"> 
			<button type="submit" class="th-btn radius" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'barab' ); ?>"><?php esc_html_e( 'Apply coupon', 'barab' ); ?></button>
		</div>
		
		<div class="clear"></div>
	</div>
</form>