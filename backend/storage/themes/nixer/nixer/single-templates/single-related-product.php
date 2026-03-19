<?php 
$nixer_redux_demo = get_option('redux_demo');
?>

<div class="tp-our-related-product pt-40 pb-100">
	<div class="container">
		<div class="row">
			<?php if (!empty($nixer_redux_demo['re-pro-title'])): ?>
				<div class="col-12">
					<div class="text-center tp-product-details-heading">
						<h2 class="tp-section-title mb-30"><?php echo esc_html($nixer_redux_demo['re-pro-title']); ?></h2>
					</div>
				</div>
			<?php endif ?>
			<?php
			$current_product_id = get_the_ID();
			$wp_query = new \WP_Query(array(
				'post_type' => 'product',
				'posts_per_page' => 4,
				'orderby' => 'ID',
				'order' => 'DESC',
				'post__not_in'   => array($current_product_id),
			));
			while($wp_query->have_posts()): $wp_query->the_post();
				$product = wc_get_product( $post->ID );
				$product_id = $product ? $product->get_id() : 0;
				$categories = wp_get_post_terms($product_id, 'product_cat');
			?>
				<div class="col-lg-3 col-md-4 col-sm-6">
					<div class="tp-product-item mb-50">
						<div class="tp-product-thumb mb-15 fix p-relative z-index-1">
							<?php if (has_post_thumbnail()): ?>
								<a href="<?php the_permalink(); ?>">
									<img class="w-100 ratio-47x50" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
								</a>
							<?php endif ?>
							<?php if ( $product->is_on_sale() ) : ?>
								<div class="tp-product-badge">
									<span class="product-discount"><?php echo esc_html__( 'SALE', 'nixer' );?></span>
								</div>
							<?php endif ?>
							<div class="tp-product-action tp-product-action-blackStyle">
								<div class="tp-product-action-item d-flex flex-column">
									<?php if (!empty($nixer_redux_demo['re-pro-action-1'])): ?>
										<button type="button" class="tp-product-action-btn tp-product-add-cart-btn">
											<a href="<?php the_permalink(); ?>">
												<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M11.4144 6.16828L14 3.58412L11.4144 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
													<path d="M1.48883 3.58374L14 3.58374" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
													<path d="M4.07446 8.32153L1.48884 10.9057L4.07446 13.4898" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
													<path d="M14 10.9058H1.48883" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
												<span class="tp-product-tooltip"><?php echo esc_html($nixer_redux_demo['re-pro-action-1']); ?></span> 
											</a>
										</button>
									<?php endif ?>
									<?php if (!empty($nixer_redux_demo['re-pro-action-2'])): ?>
										<button type="button" class="tp-product-action-btn tp-product-quick-view-btn" data-bs-toggle="modal" data-bs-target="#productModal-<?php echo esc_attr($product_id); ?>">
											<svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M8.99948 5.06828C7.80247 5.06828 6.82956 6.04044 6.82956 7.23542C6.82956 8.42951 7.80247 9.40077 8.99948 9.40077C10.1965 9.40077 11.1703 8.42951 11.1703 7.23542C11.1703 6.04044 10.1965 5.06828 8.99948 5.06828ZM8.99942 10.7482C7.0581 10.7482 5.47949 9.17221 5.47949 7.23508C5.47949 5.29705 7.0581 3.72021 8.99942 3.72021C10.9407 3.72021 12.5202 5.29705 12.5202 7.23508C12.5202 9.17221 10.9407 10.7482 8.99942 10.7482Z" fill="currentColor"/>
												<path fill-rule="evenodd" clip-rule="evenodd" d="M1.41273 7.2346C3.08674 10.9265 5.90646 13.1215 8.99978 13.1224C12.0931 13.1215 14.9128 10.9265 16.5868 7.2346C14.9128 3.54363 12.0931 1.34863 8.99978 1.34773C5.90736 1.34863 3.08674 3.54363 1.41273 7.2346ZM9.00164 14.4703H8.99804H8.99714C5.27471 14.4676 1.93209 11.8629 0.0546754 7.50073C-0.0182251 7.33091 -0.0182251 7.13864 0.0546754 6.96883C1.93209 2.60759 5.27561 0.00288103 8.99714 0.000185582C8.99894 -0.000712902 8.99894 -0.000712902 8.99984 0.000185582C9.00164 -0.000712902 9.00164 -0.000712902 9.00254 0.000185582C12.725 0.00288103 16.0676 2.60759 17.945 6.96883C18.0188 7.13864 18.0188 7.33091 17.945 7.50073C16.0685 11.8629 12.725 14.4676 9.00254 14.4703H9.00164Z" fill="currentColor"/>
											</svg>
											<span class="tp-product-tooltip"><?php echo esc_html($nixer_redux_demo['re-pro-action-2']); ?></span>
										</button>
									<?php endif ?>
									<?php
									if (function_exists('YITH_WCWL')) { 
									?>
										<button type="button" class="tp-product-action-btn tp-product-add-to-wishlist-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
											<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
										</button>
									<?php } ?>
								</div>
							</div>
							<div class="tp-product-add-cart-btn-large-wrapper">
								<?php woocommerce_template_loop_add_to_cart(); ?>
							</div>
						</div>
						<div class="tp-product-content">
							<?php
							$product_tags = get_the_terms( $product->get_id(), 'product_tag' );
							if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) :
							    $tag_names = wp_list_pluck( $product_tags, 'name' );
							?>
								<div class="tp-product-tag">
									<span><?php echo esc_html( implode( ', ', $tag_names ) ); ?></span>
								</div>
							<?php endif; ?>
							<h3 class="tp-product-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>
							<div class="tp-product-price-wrapper">
								<?php
								if ( 'product' === get_post_type( $post->ID ) ) {
									if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
										$product = wc_get_product( $post->ID );
									}
									$regular_price = $product->get_regular_price();
									$sale_price = $product->get_sale_price();

									if ( $product->is_on_sale() && $sale_price ) {
										echo '<span class="tp-product-price">' . wc_price( $sale_price ) . '</span>';
										echo '<span class="tp-product-price old-price">' . wc_price( $regular_price ) . '</span>';
									} else {
										echo '<span class="tp-product-price">' . wc_price( $regular_price ) . '</span>';
									}
								} ?>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>
