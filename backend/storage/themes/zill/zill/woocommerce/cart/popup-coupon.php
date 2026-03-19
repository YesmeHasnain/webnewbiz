<?php
defined( 'ABSPATH' ) || exit;

?>

<div id="popup-cart-coupon" class="lakit-popup-template">
    <div class="lakit-popup--title h4 theme-heading"><?php esc_html_e( 'Select or input Coupon', 'zill' ); ?></div>
    <div class="lakit-popup--content">
        <form class="form-coupon" method="POST">
            <p class="form-description"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'zill' ); ?></p>

            <div class="form-row">
                <input type="text" name="coupon_code" class="input-text" value="" required placeholder="<?php esc_attr_e( 'Coupon code', 'zill' ); ?>"/>
            </div>

            <?php do_action( 'woocommerce_cart_coupon' ); ?>

            <div class="form-submit">
                <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'zill' ); ?>"><span><?php esc_html_e( 'Apply coupon', 'zill' ); ?></span></button>
                <a href="#" rel="nofollow" class="lakit-popup--close"><span><?php esc_html_e( 'Cancel', 'zill' ) ?></span></a>
            </div>
        </form>
    </div>
</div>
