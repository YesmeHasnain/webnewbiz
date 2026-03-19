<?php
/**
 * The Template for displaying dropdown wishlist products.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist-product-counter.php.
 *
 * @version           2.8.0
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
wp_enqueue_script( 'tinvwl' );

?>

<a class="icon-btn d-none d-lg-inline-block" href="<?php echo esc_url( tinv_url_wishlist_default() ); ?>">
	<?php if ( $show_counter ) : ?>
    <span class="wishlist_products_counter_number badge"></span>
    <?php endif; ?>
    <i class="fal fa-heart"></i>
</a>