<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;?>

<?php do_action( 'woocommerce_before_cart_contents' ); ?>
<?php if(  WC()->cart->get_cart() ):
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

	if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
?>
		
<div class="single-item">
    <div class="cross-icon product-remove">
        <?php
            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                'woocommerce_cart_item_remove_link',
                sprintf(
                    '<a href="%s" class="remove remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                    esc_html__( 'Remove this item', 'woocommerce' ),
                    esc_attr( $product_id ),
                    esc_attr( $_product->get_sku() )
                ),
                $cart_item_key
            );
        ?>
    </div>
    <figure class="image-box <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
        <?php
            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
        
            if ( ! $_product->is_visible() )
                echo esc_attr($thumbnail);
            else
                printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
        ?>
    </figure>
    <h6>
        <?php
            if ( ! $_product->is_visible() )
                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
            else
                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );
        ?>
        
    </h6>
    <div class="quantity-text"><?php esc_html_e('Quantity', 'dailyfit'); ?> <?php echo wp_kses_post($cart_item['quantity']); ?></div>
    <div class="price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );?></div>

</div>	

<?php
	}
}
else: ?>
<div class="cart-content">
	<p class="center-align"><?php esc_html_e( 'There is no item in your cart',  'dailyfit' ); ?></p>
</div>
<?php endif;?>