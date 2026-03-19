<?php 
$nixer_redux_demo = get_option('redux_demo');
?>

<?php if (!empty($nixer_redux_demo['re-clr-bg'])) { ?>
	<section class="tp-blog-ptb pt-100 pb-60" data-bg-color="<?php echo esc_attr($nixer_redux_demo['re-clr-bg']);?>">
<?php } else { ?>
	<section class="tp-blog-ptb pt-100 pb-60" data-bg-color="#1E1E1E">
<?php } ?>
		<div class="container-fluid">
			<div class="tp-blog-wrap">
				<div class="row">
					<?php if (!empty($nixer_redux_demo['re-title'])): ?>
						<div class="col-lg-12">
							<div class="tp-blog-details-related-heading text-center mb-70">
								<h4 class="tp-blog-details-related-title tp-char-animation"><?php echo esc_html($nixer_redux_demo['re-title']);?></h4>
							</div>
						</div>
					<?php endif ?>
					<?php 
						$related_query = new WP_Query(array(
							'post_type'      => 'post',
							'posts_per_page' => 3,
							'post_status'    => 'publish',
							'orderby'        => 'date',
							'order'          => 'DESC',
						));

						while ($related_query->have_posts()) : $related_query->the_post();
							$post_ft_grid = get_post_meta(get_the_ID(),'_cmb_post_ft_grid', true);
							$post_grid_title = get_post_meta(get_the_ID(),'_cmb_post_grid_title', true);
					?>
						<div class="col-lg-4">
							<div class="tp-blog-item p-relative tp_fade_bottom mb-40">
								<div class="tp-blog-item-thumb mb-25">
									<a href="<?php the_permalink(); ?>">
										<?php if ('' != $post_ft_grid) { ?>
											<img src="<?php echo wp_get_attachment_url($post_ft_grid);?>" alt="<?php the_title_attribute(); ?>" class="ratio-293x310">
										<?php } elseif (has_post_thumbnail()) { ?>
											<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>" class="ratio-293x310">
										<?php } else { ?>
											<img src="<?php echo esc_url(get_template_directory_uri());?>/assets/img/blog/blog-thumb-1.jpg" alt="<?php the_title_attribute(); ?>" class="ratio-293x310">
										<?php } ?>
									</a>
								</div>
								<div class="tp-blog-item-content white-style">
									<span class="tp-blog-item-tag"><?php the_time(get_option( 'date_format' ));?></span>
									<h3 class="tp-blog-item-title">
										<a href="<?php the_permalink(); ?>">
											<?php if ( '' !== wp_specialchars_decode($post_grid_title)) { ?>
												<?php print wp_specialchars_decode($post_grid_title); ?>
											<?php } else { ?>
												<?php the_title(); ?>
											<?php } ?>
										</a>
									</h3>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
					<?php 
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
	</section>