<?php 
$nixer_redux_demo = get_option('redux_demo');

$args = array(
	'post_type'      => 'product',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) :
	while ( $query->have_posts() ) : $query->the_post();

		$product = wc_get_product( get_the_ID() );

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			continue;
		}

		$product_id = $product->get_id();

		?>
		<div class="product-popup__modal modal fade" id="productModal-<?php echo esc_attr($product_id); ?>" tabindex="-1" aria-labelledby="productModal-<?php echo esc_attr($product_id); ?>" >
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="product-popup__modal-wrapper">
						<div class="product-popup__modal-close">
							<button class="product-popup__modal-close-btn" type="button" data-bs-toggle="modal" data-bs-target="#productModal-<?php echo esc_attr($product_id); ?>">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g opacity="0.4">
										<path d="M21 1L1 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M1 1L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</g>
								</svg>
							</button>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div class="tp-product-details-thumb-wrapper tp-tab pb-50">
									<div class="tab-content m-img" id="productDetailsNavContent">
										<?php
										$attachment_ids = $product->get_gallery_image_ids();
										$thumbnail_id = $product->get_image_id();
										if ( ! empty( $attachment_ids ) ) :
											foreach ( $attachment_ids as $key => $attachment_id ) :
												$image_url = wp_get_attachment_url( $attachment_id );
												$active_class = ($key === 0) ? 'show active' : ''; 
												$tab_id = 'nav-' . $product_id . ($key + 1); 
												$tab_label = 'nav-' . $product_id . ($key + 1) . '-tab';
											?>
												<div class="tab-pane fade show <?php echo esc_attr($active_class); ?>" id="<?php echo esc_attr($tab_id); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($tab_label); ?>" tabindex="0">
													<div class="tp-product-details-nav-main-thumb">
														<img class="w-100 ratio-34x25" src="<?php echo esc_url($image_url); ?>" alt="Product Image <?php echo esc_attr($key + 1); ?>">
													</div>
												</div>
											<?php endforeach; ?>
										<?php else : ?>
											<?php 
											if ( $thumbnail_id ) :
										        $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
										        ?>
										        <div class="tab-pane fade show active" id="<?php echo esc_attr($tab_id); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($tab_label); ?>" tabindex="0">
													<div class="tp-product-details-nav-main-thumb">
														<img src="<?php echo esc_url($thumbnail_url); ?>" alt="Product Thumbnail">
													</div>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<nav>
										<div class="nav nav-tabs " id="productDetailsNavThumb" role="tablist">
											<?php										$attachment_ids = $product->get_gallery_image_ids(); 
											if ( ! empty( $attachment_ids ) ) :
												foreach ( $attachment_ids as $key => $attachment_id ) :
													$image_url = wp_get_attachment_image_url( $attachment_id, 'full' );
													$active_class = ($key === 0) ? 'active' : ''; 
													$tab_id = 'nav-' . $product_id . ($key + 1); 
													$tab_target = '#nav-' . $product_id . ($key + 1);
													$tab_label = 'nav-' . $product_id . ($key + 1) . '-tab';
													?>
													<button class="nav-link <?php echo esc_attr($active_class); ?>" id="<?php echo esc_attr($tab_label); ?>" data-bs-toggle="tab" data-bs-target="<?php echo esc_attr($tab_target); ?>" type="button" role="tab" aria-controls="<?php echo esc_attr($tab_id); ?>" aria-selected="true">
														<img src="<?php echo esc_url( $image_url ); ?>" alt="Product Image <?php echo esc_attr($key + 1); ?>">
													</button>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</nav>
								</div>
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
												<span><?php esc_html_e('SKU:  ', 'nixer'); ?></span>
												<p><?php echo esc_html( $product->get_sku() ); ?></p>
											</div>
										<?php endif ?>
										<?php 
										$categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
										if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
											<div class="tp-product-details-query-item d-flex align-items-center">
												<span><?php esc_html_e('Categories:', 'nixer'); ?></span>
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
				</div>
			</div>
		</div>
		<?php
	endwhile;
	wp_reset_postdata();
endif;
?>
