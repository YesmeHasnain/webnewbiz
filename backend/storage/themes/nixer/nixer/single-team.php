<?php 
$nixer_redux_demo = get_option('redux_demo');
get_header();
?>
	<main data-bg-color="#121212">
		<?php 
		while (have_posts()) : the_post();
			$team_thumb = get_post_meta(get_the_ID(),'_cmb_team_thumbail', true);
			$team_location = get_post_meta(get_the_ID(),'_cmb_team_location', true);
			$team_introduce = get_post_meta(get_the_ID(),'_cmb_team_introduce', true);
			$links = get_post_meta(get_the_ID(),'_cmb_team_link_social', true);
			$icons = get_post_meta(get_the_ID(),'_cmb_Team_icon_social', true);
		?>
			<section class="tp-team-details-ptb p-relative pt-80">
				<div class="container container-1480">
					<div class="row align-items-center">
						<div class="col-lg-7 order-2 order-lg-1">
							<?php if (!empty($team_thumb) || has_post_thumbnail()): ?>
								<div class="tp-team-details-thumb p-relative z-index-1">
									<?php if (!empty($team_thumb)) { ?>
										<img src="<?php echo wp_get_attachment_url($team_thumb);?>" alt="<?php the_title_attribute(); ?>">
									<?php } else { ?>
										<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
									<?php } ?>
								</div>
							<?php endif ?>
						</div>
						<div class="col-lg-5 order-1 order-lg-2">
							<div class="tp-team-details-content p-relative z-index-1">
								<h4 class="tp-team-details-title tp-char-animation"><?php the_title(); ?></h4>
								<span>
									<?php
									$post_id = get_the_ID();
									$taxonomy = 'team-job'; 
									$categories = get_the_terms($post_id, $taxonomy);
									?>
									<?php if (!empty($categories)): ?>
										<?php
										$first_category = $categories[0];
										$category_link = get_category_link($first_category->term_id);
										$category_name = $first_category->name;
										?>
										<a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category_name); ?></a>
									<?php endif ?>
									<?php if (!empty($categories) && !empty($team_location)): ?>
										|
									<?php endif ?>
									<?php echo wp_kses_post($team_location); ?>
								</span>
								<?php if (!empty($team_introduce)): ?>
									<p><?php echo wp_kses_post($team_introduce); ?></p>
								<?php endif ?>
								<?php if ( ! empty( $links ) && is_array( $links ) && ! empty( $icons ) && is_array( $icons ) ) : ?>
									<div class="tp-team-details-social">
										<?php
										foreach ( $links as $index => $link ) :
											$icon = $icons[ $index ] ?? '';
											$name = $names[ $index ] ?? '';
											?>
											<?php if ( ! empty( $link ) && ! empty( $icon ) ) : ?>
												<a href="<?php echo esc_url( $link ); ?>">
													<span>
														<?php echo nixer_inline_svg_from_url($icon); ?>
													</span>
												</a>
											<?php endif ?>
										<?php endforeach; ?>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</main>
<?php get_template_part( 'footer/footer', '6' ); ?>