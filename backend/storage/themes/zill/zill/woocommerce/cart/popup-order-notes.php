<?php
defined( 'ABSPATH' ) || exit;


$notes = WC()->session->get( 'lakit_order_notes', '' );

?>

<div id="popup-cart-order-notes" class="lakit-popup-template">
    <div class="lakit-popup--title h4 theme-heading"><?php esc_html_e( 'Add note for seller', 'zill' ); ?></div>
    <div class="lakit-popup--content">
        <form class="form-order-notes" method="POST">
            <?php zill_wc_render_order_note_textarea(); ?>
            <div class="form-submit">
                <button type="submit" class="button"><span><?php esc_html_e( 'Save', 'zill' ); ?></span></button>
                <a href="#" rel="nofollow" class="lakit-popup--close"><span><?php esc_html_e( 'Cancel', 'zill' ) ?></span></a>
            </div>
        </form>
    </div>
</div>