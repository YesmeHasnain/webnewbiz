<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$review_count = $product->get_review_count();

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<section class="tp-product-details-area pt-120 pb-70" data-bg-color="#EFF1F5">
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'container container-1230', $product ); ?>>
		<?php
		/**
		 * Hook: woocommerce_before_single_product.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 */
		do_action( 'woocommerce_before_single_product' );
		?>
		<div class="row">
			<div class="col-lg-6 p-relative">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
			<div class="col-lg-6">
				<div class="tp-product-details-wrapper pb-50">
					<?php 
					$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : 
						$first_category = $categories[0]; ?>
						<div class="tp-product-details-category">
							<span><?php echo esc_html( $first_category->name ); ?></span>
						</div>
					<?php endif; ?>
					<h3 class="tp-product-details-title mb-20"><?php the_title(); ?></h3>

					<div class="tp-product-details-inventory mb-25 d-flex align-items-center justify-content-between">
						<div class="tp-product-details-price-wrapper">
							<?php
							if ( 'product' === get_post_type( $post->ID ) ) {
								if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
									$product = wc_get_product( $post->ID );
								}
								$regular_price = $product->get_regular_price();
								$sale_price = $product->get_sale_price();

								if ( $product->is_on_sale() && $sale_price ) {
									echo '<span class="tp-product-details-price">' . wc_price( $sale_price ) . '</span>';
									echo '<span class="tp-product-price old-price pl-5">' . wc_price( $regular_price ) . '</span>';
								} else {
									echo '<span class="tp-product-details-price">' . wc_price( $regular_price ) . '</span>';
								}
							} ?>
							<?php 
							if ( 'product' === get_post_type($post->ID) ) {
								$product = wc_get_product( $post->ID );
								if ( $product ) {
									$average_rating = (float) $product->get_average_rating();
									$review_count   = $product->get_review_count();

									echo '<div class="tp-product-details-rating-wrapper d-flex align-items-center">';
									echo '<div class="tp-product-details-rating">';
									for ( $i = 1; $i <= floor( $average_rating ); $i++ ) {
										echo '<span><i class="fas fa-star"></i></span>';
									}
									if ( $average_rating - floor( $average_rating ) > 0 ) {
										echo '<span><i class="fas fa-star-half-stroke"></i></span>';
									}
									for ( $i = ceil( $average_rating ); $i < 5; $i++ ) {
										echo '<span><i class="fa-regular fa-star"></i></span>';
									}
									echo '</div>';
									echo '<div class="tp-product-details-reviews">';
									$label = ( $review_count == 1 ) ? 'Review' : 'Reviews';
									echo '<span>(' . esc_html( $review_count ) . ' ' . esc_html( $label ) . ')</span>';
									echo '</div>';
									echo '</div>';
								}
							} ?>
						</div>
					</div>
					<p><?php echo apply_filters( 'the_excerpt', get_the_excerpt( $product->get_id() ) ); ?></p>
					<div class="tp-product-details-action-wrapper mb-10">
						<h3 class="tp-product-details-action-title"><?php esc_html_e('Quantity', 'nixer'); ?></h3>
						<div class="tp-product-details-action-item-wrapper d-flex flex-wrap align-items-center">
							<?php woocommerce_template_single_add_to_cart(); ?>
						</div>
					</div>

					<div class="tp-product-details-query">
						<?php if ( $product && $product->get_sku() ) : ?>
							<div class="tp-product-details-query-item d-flex align-items-center">
								<span>SKU:  </span>
								<p><?php echo esc_html( $product->get_sku() ); ?></p>
							</div>
						<?php endif ?>
						<?php 
						$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
							<div class="tp-product-details-query-item d-flex align-items-center">
								<span><?php esc_html_e('Category:', 'nixer'); ?></span>
								<p>
									<?php 
									$category_names = array();
									foreach ( $categories as $category ) {
										$category_names[] = $category->name;
									}
									echo implode( ', ', $category_names );
									?>
								</p>
							</div>
						<?php endif ?>
						<?php
						global $product;
						$product_tags = get_the_terms( $product->get_id(), 'product_tag' );
						if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) :
						    $tag_names = wp_list_pluck( $product_tags, 'name' );
						    ?>
						    <div class="tp-product-details-query-item d-flex align-items-center">
						        <span>Tag: </span>
						        <p><?php echo esc_html( implode( ', ', $tag_names ) ); ?></p>
						    </div>
						<?php endif; ?>
					</div>

					<?php 
						$link_social_1 = get_post_meta(get_the_ID(),'_cmb_link_social_1', true);
						$icon_social_1 = get_post_meta(get_the_ID(),'_cmb_icon_social_1', true);
						$link_social_2 = get_post_meta(get_the_ID(),'_cmb_link_social_2', true);
						$icon_social_2 = get_post_meta(get_the_ID(),'_cmb_icon_social_2', true);
						$link_social_3 = get_post_meta(get_the_ID(),'_cmb_link_social_3', true);
						$icon_social_3 = get_post_meta(get_the_ID(),'_cmb_icon_social_3', true);
						$link_social_4 = get_post_meta(get_the_ID(),'_cmb_link_social_4', true);
						$icon_social_4 = get_post_meta(get_the_ID(),'_cmb_icon_social_4', true);
					?>
					<?php if ((('' !== wp_specialchars_decode($link_social_1)) && ('' !== wp_specialchars_decode($icon_social_1))) || (('' !== wp_specialchars_decode($link_social_2)) && ('' !== wp_specialchars_decode($icon_social_2))) || (('' !== wp_specialchars_decode($link_social_3)) && ('' !== wp_specialchars_decode($icon_social_3))) || (('' !== wp_specialchars_decode($link_social_4)) && ('' !== wp_specialchars_decode($icon_social_4)))): ?>
						<div class="tp-product-details-social">
							<?php if (('' !== wp_specialchars_decode($link_social_1)) && ('' !== wp_specialchars_decode($icon_social_1))): ?>
								<a href="<?php print wp_specialchars_decode($link_social_1); ?>">
									<?php print wp_specialchars_decode($icon_social_1); ?>
								</a>
							<?php endif ?>
							<?php if (('' !== wp_specialchars_decode($link_social_2)) && ('' !== wp_specialchars_decode($icon_social_2))): ?>
								<a href="<?php print wp_specialchars_decode($link_social_2); ?>">
									<?php print wp_specialchars_decode($icon_social_2); ?>
								</a>
							<?php endif ?>
							<?php if (('' !== wp_specialchars_decode($link_social_3)) && ('' !== wp_specialchars_decode($icon_social_3))): ?>
								<a href="<?php print wp_specialchars_decode($link_social_3); ?>">
									<?php print wp_specialchars_decode($icon_social_3); ?>
								</a>
							<?php endif ?>
							<?php if (('' !== wp_specialchars_decode($link_social_4)) && ('' !== wp_specialchars_decode($icon_social_4))): ?>
								<a href="<?php print wp_specialchars_decode($link_social_4); ?>">
									<?php print wp_specialchars_decode($icon_social_4); ?>
								</a>
							<?php endif ?>
						</div>
					<?php endif ?>
				
				</div>
			</div>
			
		</div>
	</div>
</section>

<div class="tp-product-details-bottom tp-product-details-bottom-style2 pt-95 pb-85 white-bg">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="tp-product-details-tab-nav tp-tab">
					<nav>
						<div class="nav nav-tabs p-relative tp-product-tab justify-content-sm-start justify-content-center" id="nav-tab" role="tablist">
							<button class="nav-link active" id="nav-description-tab" data-bs-toggle="tab" data-bs-target="#nav-description" type="button" role="tab" aria-controls="nav-description" aria-selected="true"><?php esc_html_e('Description', 'nixer'); ?></button>
							<button class="nav-link" id="nav-addInfo-tab" data-bs-toggle="tab" data-bs-target="#nav-addInfo" type="button" role="tab" aria-controls="nav-addInfo" aria-selected="false"><?php esc_html_e('Additional Information', 'nixer'); ?></button>
							<button class="nav-link" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab" aria-controls="nav-review" aria-selected="false"><?php printf(_n( 'Review (%d)', 'Reviews (%d)', $review_count, 'nixer' ), $review_count ); ?></button>
						</div>
					</nav>  
					<div class="tab-content pt-30" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab" tabindex="0">
							<div class="tp-product-details-desc-wrapper">
								<p><?php the_content(); ?></p>
							</div>
						</div>
						<div class="tab-pane fade" id="nav-addInfo" role="tabpanel" aria-labelledby="nav-addInfo-tab" tabindex="0">
							<div class="tp-product-details-additional-info tp-table-style-2">
								<div class="row justify-content-center">
									<div class="col-xl-10">
										<h3 class="tp-product-details-additional-info-title"><?php esc_html_e('Additional information', 'nixer'); ?></h3>
										<?php 
										$attributes = $product->get_attributes();
										if ( ! empty( $attributes ) ) {
										?>
											<table>
												<tbody>
													<?php 
													foreach ( $attributes as $attribute ) {
													?>
														<?php 
														if ( $attribute->get_visible() ) {
															$label = wc_attribute_label( $attribute->get_name() );

															if ( $attribute->is_taxonomy() ) {
																$values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
																$value  = implode( ', ', $values );
															} else {
																$value = $attribute->get_options();
																$value = implode( ', ', $value );
															}
														?>
																<tr>
																	<td><?php echo esc_html( $label ); ?></td>
																	<td><?php echo esc_html( $value ); ?></td>
																</tr>
														<?php } ?>
													<?php } ?>
												</tbody>
											</table>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab" tabindex="0">
							<?php comments_template(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php do_action( 'woocommerce_after_single_product' ); ?>
