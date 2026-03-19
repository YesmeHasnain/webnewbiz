<?php 
$nixer_redux_demo = get_option('redux_demo');
get_header();
?>

<?php 
while (have_posts()): the_post();
	$post_fomat = get_post_meta(get_the_ID(),'_cmb_post_fomat', true);
?>
	<main data-bg-color="#121212">
		<section class="tp-blog-details-ptb pt-200 pb-100" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="container container-1170">
				<div class="row">
					<div class="col-lg-12">
						<div class="tp-blog-details-wrap mb-60">
							<div class="tp-blog-details-heading mb-60">
								<span class="tp-blog-details-sub-title">
									<?php if (!empty($nixer_redux_demo['post-show-cat'])): ?>
										<?php
										$categories = get_the_category(get_the_ID());
										if (!empty($categories)) {
											$first_category = $categories[0];
											$category_link = get_category_link($first_category->term_id);
											$category_name = $first_category->name;
										?>
											<?php echo esc_html($category_name); ?>
											<?php echo html_entity_decode( '.' , ENT_COMPAT , 'nixer' ) ?>
										<?php } ?>
									<?php endif ?>
									<?php the_time(get_option( 'date_format' ));?>
								</span>
								<h3 class="tp-blog-details-title">
									<?php the_title(); ?>
								</h3>
							</div>
							<?php if ($post_fomat == 'format1') { ?>
								<?php if (has_post_thumbnail()): ?>
									<div class="tp-blog-details-thumb">
										<img class="ratio-117x64" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
									</div>
								<?php endif ?>
							<?php } elseif ($post_fomat == 'format2') { ?>
								<?php 
								$gallery_images = get_post_meta(get_the_ID(), '_cmb_fm-img-gallery', false);
								if (!empty($gallery_images)) { ?>
									<div class="tp-blog-details-thumb postbox__thumb w-img">
										<div class="postbox__thumb-slider p-relative">
											<div class="swiper-container postbox__thumb-slider-active fix">
												<div class="swiper-wrapper">
													<?php 
													foreach ($gallery_images as $image_id): 
														$image_url = wp_get_attachment_image_url($image_id, 'full');
													?>
														<div class="swiper-slide">
															<img class="ratio-117x64" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
														</div>
													<?php endforeach; ?>
												</div>
											</div>
											<div class="postbox__slider-arrow-wrap d-none d-sm-block">
												<button class="postbox-arrow-prev">
													<i class="fa-sharp fa-solid fa-arrow-left"></i>
												</button>
												<button class="postbox-arrow-next">
													<i class="fa-sharp fa-solid fa-arrow-right"></i>
												</button>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } elseif ($post_fomat == 'format3') { 
								$bg_video = get_post_meta(get_the_ID(),'_cmb_fm-bg-video', false);
								$video_link = get_post_meta(get_the_ID(),'_cmb_fm-link-video', true);
							?>
								<?php if (!empty($bg_video) && !empty($video_link)): ?>
									<div class="tp-blog-details-thumb postbox__thumb">
										<a class="popup-video" href="<?php echo esc_url($image_id); ?>">
											<?php 
											foreach ($bg_video as $image_id): 
												$image_url = wp_get_attachment_image_url($image_id, 'full');
											?>
												<img class="ratio-117x64" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
											<?php endforeach; ?>
										</a>
										<div class="postbox__play-btn">
											<a class="popup-video" href="<?php echo esc_url($video_link); ?>">
												<i class="fa-sharp fa-solid fa-play"></i>
											</a>
										</div>
									</div>
								<?php endif ?>
							<?php } else { ?>
								<?php if (has_post_thumbnail()): ?>
									<div class="tp-blog-details-thumb text-center m-auto">
										<img class="w-fit" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
									</div>
								<?php endif ?>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="row">
					<?php if (is_active_sidebar( 'sidebar-1' )) { ?>
						<div class="col-xxl-8 col-xl-8 col-lg-8">
					<?php } else { ?>
						<div class="col-xxl-12 col-xl-12 col-lg-12">
					<?php } ?>
							<div class="tp-postbox-details-wrapper">
								<div class="tp-postbox-details-mate d-flex align-items-center mb-50 p-relative">
									<div class="tp-postbox-details-author d-flex align-items-center">
										<?php 
										$id = get_the_ID();
										$author_id = get_post_field('post_author', $id);
										$avatar_url = get_avatar_url($author_id, array('size' => 50));
										?>
										<?php if (!empty($avatar_url)): ?>
											<div class="tp-postbox-details-author-thumb">
												<img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr(get_the_author_meta('display_name', $author_id)); ?>">
											</div>
										<?php endif ?>
										<div class="tp-postbox-details-author-content">
											<?php if (!empty($nixer_redux_demo['post-meta-1'])) { ?>
												<p><?php echo esc_html($nixer_redux_demo['post-meta-1']);?></p>
											<?php } else { ?>
												<p><?php echo html_entity_decode( 'Author' , ENT_COMPAT , 'nixer' ) ?></p>
											<?php } ?>
											<h4 class="tp-postbox-details-author-title text-cap"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></a></h4>
										</div>
									</div>
									<div class="tp-postbox-details-author-content mr-50">
										<span><?php comments_number( esc_html__('0 Comments', 'nixer'), esc_html__('1 Comment', 'nixer'), esc_html__('% Comments', 'nixer') ); ?></span>
										<h4 class="tp-postbox-details-author-title">
											<a href="#singlecomment">
												<?php if (!empty($nixer_redux_demo['post-meta-2'])) { ?>
													<?php echo wp_kses_post($nixer_redux_demo['post-meta-2']);?>
												<?php } else { ?>
													<?php echo html_entity_decode( 'Join the Conversation' , ENT_COMPAT , 'nixer' ) ?>
												<?php } ?>
											</a>
										</h4>
									</div>
									<?php if ( is_sticky() ) echo '<span class="featured-post sticky">'.esc_html__('Sticky', 'nixer').'</span>';?>
								</div>
								<?php the_content(); ?>
								<?php if ( is_singular() && wp_link_pages( array( 'echo' => false ) ) ) {
									echo '<div class="entry-content">';
									wp_link_pages( array(
										'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'nixer' ),
										'after'       => '</div>',
										'link_before' => '<span class="page-number">',
										'link_after'  => '</span>',
									) );
									echo '</div>';
								} ?>
								<?php if (!empty($nixer_redux_demo['post-switch-tag-share'])): ?>
									<div class="tp-postbox-details-share mt-60">
										<div class="row align-items-center">
											<?php if (!empty($nixer_redux_demo['post-switch-share'])): ?>
												<div class="col-lg-6">
													<div class="tp-postbox-details-social d-flex align-items-center">
														<h4 class="tp-postbox-details-social-title">
															<?php if (!empty($nixer_redux_demo['share-label'])) { ?>
																<?php echo wp_kses_post($nixer_redux_demo['share-label']);?>
															<?php } else { ?>
																<?php echo html_entity_decode( 'Share:' , ENT_COMPAT , 'nixer' ) ?>
															<?php } ?>
														</h4>
														<div class="tp-team-details-social">
															<?php if (!empty($nixer_redux_demo['share-rp-social']['redux_repeater_data']) && is_array($nixer_redux_demo['share-rp-social']['redux_repeater_data'])): ?>
																<?php 
																$num = is_array($nixer_redux_demo['share-rp-social']['redux_repeater_data']) ? count($nixer_redux_demo['share-rp-social']['redux_repeater_data']) : 0;
																$i = 0;
																for ($i=0; $i < $num ; $i++) { 
																	$sc_icon_field  = $nixer_redux_demo['share-rp-social']['icon-field'][$i] ?? '';
																	$sc_link_field = $nixer_redux_demo['share-rp-social']['link-field'][$i] ?? '';
																	$sc_text_field = $nixer_redux_demo['share-rp-social']['text-field'][$i] ?? '';
																	?>
																		<?php if (!empty($sc_link_field) && !empty($sc_icon_field)): ?>
																			<a href="<?php echo esc_url($sc_link_field); ?>">
																				<span>
																					<i class="<?php echo esc_attr($sc_icon_field); ?>"></i>
																				</span>
																			</a>
																		<?php endif ?>
																<?php } ?>
															<?php endif ?>
														</div>
													</div>
												</div>
											<?php endif ?>
											<?php if (!empty($nixer_redux_demo['post-switch-tag'])): ?>
												<div class="col-lg-6">
													<div class="tagcloud white-style text-start text-lg-end">
														<?php echo get_the_tag_list( '', '', '' ); ?>
													</div>
												</div>
											<?php endif ?>
										</div>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['re-switch'])) { ?>
								<?php } else { ?>
									<?php if ( comments_open() || get_comments_number() ) {?>
										<section class="tp-blog-details-ptb pt-110 pb-110" id="singlecomment">
											<div class="tp-postbox-details-wrapper">
												<?php comments_template();?>
											</div>
										</section>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					<?php if (is_active_sidebar( 'sidebar-1' )){?>
						<div class="col-xxl-4 col-xl-4 col-lg-4">
							<div class="sidebar__wrapper sidebar-white-style">
								<?php get_sidebar(); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
		<?php if (!empty($nixer_redux_demo['re-switch'])): ?>
			<?php get_template_part( 'single-templates/single', 'related' );?>
			<?php if ( comments_open() || get_comments_number() ) {?>
				<section class="tp-blog-details-ptb pt-110 pb-110" id="singlecomment">
					<div class="container container-1170">
						<div class="row">
							<div class="col-xxl-8 col-xl-8 col-lg-8">
								<div class="tp-postbox-details-wrapper">
									<?php comments_template();?>
								</div>
							</div>
							<div class="col-xxl-4 col-xl-4 col-lg-4"></div>
						</div>
					</div>
				</section>
			<?php } ?>
		<?php endif ?>
	</main>
<?php endwhile; ?>


<?php get_footer(); ?>