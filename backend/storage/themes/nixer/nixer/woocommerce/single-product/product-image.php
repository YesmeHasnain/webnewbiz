<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$attachment_ids = $product->get_gallery_image_ids();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);
?>

<div class="tp-product-details-thumb-wrapper tp-tab pb-50">
	<div class="tab-content m-img" id="productDetailsNavContent">
		<?php if ( $attachment_ids ) : ?>
			<?php foreach ( $attachment_ids as $index => $attachment_id ) : 
				$image_url = wp_get_attachment_image_url( $attachment_id, 'full' );
				$active_class = $index === 0 ? 'show active' : '';
			?>
				<div class="tab-pane fade <?php echo esc_attr( $active_class ); ?>" id="nav-<?php echo esc_attr( $index ); ?>" role="tabpanel" aria-labelledby="nav-<?php echo esc_attr( $index ); ?>-tab" tabindex="0">
					<div class="tp-product-details-nav-main-thumb">
						<img class="w-612 ratio-34x25" src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?> <?php echo esc_attr( $index ); ?>">
					</div>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<?php 
			if ( $post_thumbnail_id ) :
		        $thumbnail_url = wp_get_attachment_image_url( $post_thumbnail_id, 'full' ); 
		        ?>
		        <div class="tab-pane fade show active" id="nav-0" role="tabpanel" aria-labelledby="nav-0-tab" tabindex="0">
					<div class="tp-product-details-nav-main-thumb">
						<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?> Thumbnail">
					</div>
				</div>
		    <?php endif; ?>
		<?php endif; ?>
	</div>
	<nav>
		<div class="nav nav-tabs " id="productDetailsNavThumb" role="tablist">
			<?php if ( $attachment_ids ) : ?>
				<?php foreach ( $attachment_ids as $index => $attachment_id ) : 
					$image_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
					$active_class = $index === 0 ? 'active' : '';
				?>
					<button class="nav-link <?php echo esc_attr( $active_class ); ?>" id="nav-<?php echo esc_attr( $index ); ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?php echo esc_attr( $index ); ?>" type="button" role="tab" aria-controls="nav-<?php echo esc_attr( $index ); ?>" aria-selected="<?php echo esc_attr($index) === 0 ? 'true' : 'false'; ?>">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?> <?php echo esc_attr( $index ); ?>">
					</button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</nav>
</div>
