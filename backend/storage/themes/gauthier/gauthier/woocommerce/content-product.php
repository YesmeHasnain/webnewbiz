<?php
/**
 * The template for displaying product content within loops
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */
defined( 'ABSPATH' ) || exit;
global $product;
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <div class="borderbox2">
        <?php wc_get_template( 'sale-flash.php' ); ?>
        <div class="singleproduct">
            <?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 450, 600, true,true,true ); ?>
            <?php if ($image) { ?>
            <div class="module117-thumb1"> <img src="<?php echo esc_html($image) ?>"/>
                <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
            </div>
            <?php } else { ?>
            <?php } ?>
            <div class="module117-thumb2">
                <?php global $product;
				getImageGallery($product,600,800, true);
				?>
            </div>
        </div>
    </div>
    <?php
	do_action( 'woocommerce_shop_loop_item_title' );
	do_action( 'woocommerce_after_shop_loop_item_title' );
	 ?>
    <?php
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>