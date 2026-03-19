<?php
/**
 * Single Product Sale Flash
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $product;
?>
<?php if ( $product->is_on_sale() ) : ?>
	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'gauthier' ) . '</span>', $post, $product ); ?>
	<?php
endif;
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */