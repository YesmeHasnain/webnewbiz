<?php
defined( 'ABSPATH' ) || exit;

?>

<div id="popup-cart-shipping-calculator" class="lakit-popup-template">
    <div class="lakit-popup--title h4 theme-heading"><?php esc_html_e( 'Estimate shipping rates', 'zill' ); ?></div>
    <div class="lakit-popup--content">
        <?php wc_get_template( 'cart/shipping-calculator.php' ); ?>
        <div class="form-submit">
            <button type="button" class="button"><?php esc_html_e( 'Calculate shipping rates ', 'zill' ); ?></button>
            <a href="#" rel="nofollow" class="lakit-popup--close"><span><?php esc_html_e( 'Cancel', 'zill' ) ?></span></a>
        </div>
    </div>
</div>