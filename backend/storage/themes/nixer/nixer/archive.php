<?php 
$nixer_redux_demo = get_option('redux_demo');
get_template_part( 'header/header', '9' );
?>
<section class="tp-blog-sidebar-ptb pt-200 p-relative">
	<div class="tp-team-top-border">
		<div class="container container-1170">
			<div class="row">
				<div class="col-lg-12">
					<div class="tp-blog-heading p-relative pb-70">
						<h3 class="tp-breadcrumb-title tp-title-anim">
							<?php if(isset($nixer_redux_demo['arch-title']) && $nixer_redux_demo['arch-title']!=''){?>
								<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['arch-title']));?>
							<?php }else{?>
								<?php echo esc_html__( 'Archive', 'nixer' );
							}?>
							<span class="text-cap"><?php printf( esc_html__( ' %s', 'nixer' ), get_the_archive_title() );?></span>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="postbox__area pt-120 pb-80">
	<div class="container container-1170">
		<div class="row">
			<?php if (is_active_sidebar( 'sidebar-1' )) { ?>
				<div class="col-xxl-8 col-xl-8 col-lg-8">
			<?php } else { ?>
				<div class="col-xxl-12 col-xl-12 col-lg-12">
			<?php } ?>
					<div class="postbox__wrapper">
						<?php
						$i = 0;
						while (have_posts()): the_post();
							$i++;
							$blog_excerpt = get_post_meta(get_the_ID(),'_cmb_post_blog_excerpt', true);
							$post_fomat = get_post_meta(get_the_ID(),'_cmb_post_fomat', true);
						?>
							<?php if ($i % 3 == 0): ?>
								<?php get_template_part( 'single-templates/blog', 'blockquote' );?>
							<?php endif ?>
							<article class="postbox__item mb-80" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php if ($post_fomat == 'format2') { 
									$gallery_images = get_post_meta(get_the_ID(), '_cmb_fm-img-gallery', false);
									if (!empty($gallery_images)) { ?>
										<div class="postbox__thumb w-img">
											<div class="postbox__thumb-slider p-relative">
												<div class="swiper-container postbox__thumb-slider-active fix">
													<div class="swiper-wrapper">
														<?php 
														foreach ($gallery_images as $image_id):
															$image_url = wp_get_attachment_image_url($image_id, 'full');
														?>
															<div class="swiper-slide">
																<img class="ratio-39x25" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
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
										<div class="postbox__thumb">
											<a class="popup-video" href="<?php echo esc_url($image_id); ?>">
												<?php 
												foreach ($bg_video as $image_id): 
													$image_url = wp_get_attachment_image_url($image_id, 'full');
												?>
													<img class="ratio-39x25" src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>">
												<?php endforeach; ?>
											</a>
											<div class="postbox__play-btn">
												<a class="popup-video" href="<?php echo esc_url($video_link); ?>">
													<i class="fa-sharp fa-solid fa-play"></i>
												</a>
											</div>
										</div>
									<?php endif ?>
								<?php } elseif ($post_fomat == 'format1') { ?>
									<?php if (has_post_thumbnail()): ?>
										<div class="postbox__thumb">
											<a href="<?php the_permalink(); ?>">
												<img class="ratio-39x25" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
											</a>
										</div>
									<?php endif ?>
								<?php } else { ?>
									<?php if (has_post_thumbnail()): ?>
										<div class="postbox__thumb">
											<a href="<?php the_permalink(); ?>">
												<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
											</a>
										</div>
									<?php endif ?>
								<?php } ?>
								<div class="postbox__content">
									<div class="postbox__meta">
										<span>
											<?php
											$categories = get_the_category(get_the_ID());
											if (!empty($categories)) {
												foreach ($categories as $index => $category) {
													$category_link = get_category_link($category->term_id);
													$category_name = $category->name;
													?>
													<a href="<?php echo esc_url($category_link); ?>">
														<?php echo esc_html($category_name); ?>
													</a>
													<?php
													if ($index < count($categories) - 1) {
														echo '&nbsp;&middot;&nbsp;';
													}
												}
												echo '.';
											} ?>
											<?php the_time(get_option( 'date_format' ));?>
										</span>
									</div>
									<h3 class="postbox__title">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h3>
									<div class="postbox__text">
										<p>
											<?php if ( '' !== wp_specialchars_decode($blog_excerpt)): ?>
												<?php print wp_specialchars_decode($blog_excerpt); ?>
											<?php else:?>
												<?php if(isset($nixer_redux_demo['blog_excerpt'])){?>
												<?php echo esc_attr(nixer_excerpt($nixer_redux_demo['blog_excerpt'])); ?>
												<?php }else{?>
												<?php echo esc_attr(nixer_excerpt(35)); 
												}?>
											<?php endif ?>
										</p>
									</div>
									<div class="postbox__read-more">
										<a href="<?php the_permalink(); ?>" class="tp-btn-border-lg">
											<?php if(isset($nixer_redux_demo['arch-btn-read']) && $nixer_redux_demo['arch-btn-read']!=''){?>
												<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['arch-btn-read']));?>
											<?php }else{?>
												<?php echo esc_html__( 'Read More', 'nixer' );
											}?>
											<span>
												<svg xmlns="http://www.w3.org/2000/svg" width="15" height="12" viewBox="0 0 15 12" fill="none">
													<path d="M-0.00195312 5.99976H13.966M8.46297 0C8.46297 3.31648 11.386 6.0001 14.9982 6.0001C11.386 6.0001 8.46297 8.68333 8.46297 11.9998" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
										 		</svg>
										 	</span>
										 </a>
									</div>
								</div>
							</article>
						<?php endwhile; ?>
						<?php 
						$paged = get_query_var('paged') ? get_query_var('paged') : 1;
						$big = 999999999;
						$pagination = array(
							'base'      	=> str_replace($big, '%#%', get_pagenum_link($big)),
							'format'    	=> '',
							'current'   	=> $paged,
							'total'     	=> $wp_query->max_num_pages,
							'prev_text'     => wp_specialchars_decode('<i class="fa-regular fa-arrow-left icon"></i>', ENT_QUOTES),
							'next_text'     => wp_specialchars_decode('<i class="fa-regular fa-arrow-right icon"></i>', ENT_QUOTES),
							'type'      	=> 'list',
							'end_size'    	=> 3,
							'mid_size'    	=> 3
						);
						$pagination_links = paginate_links($pagination);
						if (!empty($pagination_links)): ?>
							<div class="basic-pagination text-center mt-80">
								<nav>
									<?php 
									echo str_replace("<ul class='page-numbers'>", '<ul class="page-pagination">', $pagination_links); 
									?>
								</nav>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php if (is_active_sidebar( 'sidebar-1' )){?>
				<div class="col-xxl-4 col-xl-4 col-lg-4">
					<div class="sidebar__wrapper">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>

<?php
get_footer();
?>