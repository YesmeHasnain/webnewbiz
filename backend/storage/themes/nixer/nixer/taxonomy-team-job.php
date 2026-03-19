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
							<?php if(isset($nixer_redux_demo['tjob-title']) && $nixer_redux_demo['tjob-title']!=''){?>
								<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['tjob-title']));?>
							<?php }else{?>
								<?php echo esc_html__( 'Team Job:', 'nixer' );
							}?>
							<span class="text-cap"><?php printf( esc_html__( ' %s', 'nixer' ), single_term_title( '', false ) );?></span>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</div> 
</section>

<section class="tp-team-ptb p-relative">
	<div class="tp-team-inner-wrap pt-120 pb-70">
		<div class="container">
			<div class="row">
				<?php
				while (have_posts()): the_post();
					$post_id = get_the_ID();
					$taxonomy = 'team-job';
					$categories = get_the_terms($post_id, $taxonomy);
					if (!empty($categories) && !is_wp_error($categories)) {
						$first_category = $categories[0];
						$category_link = get_term_link($first_category->term_id);
						$category_name = $first_category->name;
					}
				?>
					<div class="col-lg-4 col-md-6">
						<div class="tp-team-inner-item mb-80">
							<div class="tp-team-inner-item-thumb">
								<?php if (has_post_thumbnail()) { ?>
									<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
								<?php } else { ?>
									<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/team/team/team-thumb-1.jpg" alt="<?php the_title_attribute(); ?>">
								<?php } ?>
								<div class="tp-team-inner-item-social">
									<button class="tp-team-inner-item-social-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="21" height="19" viewBox="0 0 21 19" fill="none">
											<path d="M6.87444 15.4269C6.87444 14.3809 6.43993 13.4386 5.74876 12.7846L9.25367 6.90193C9.64043 7.05898 10.0605 7.14609 10.5 7.14609C10.9396 7.14609 11.3597 7.05898 11.7463 6.90193L15.2514 12.7846C14.5602 13.4386 14.1256 14.3809 14.1256 15.4269C14.1256 17.3971 15.6675 19 17.5629 19C19.4582 19 21 17.3971 21 15.4269C21 13.4566 19.4581 11.8537 17.5627 11.8537C17.1232 11.8537 16.7031 11.941 16.3164 12.0979L12.8115 6.21542C13.5026 5.56138 13.9373 4.61905 13.9373 3.57313C13.9373 1.60286 12.3952 8.28423e-08 10.5 0C8.60463 -8.28493e-08 7.0627 1.60286 7.0627 3.57313C7.0627 4.61905 7.49721 5.56138 8.18855 6.21542L4.68363 12.0979C4.29687 11.941 3.87678 11.8537 3.43714 11.8537C1.54193 11.8537 8.61249e-08 13.4566 0 15.4269C-8.61213e-08 17.3971 1.54193 19 3.43714 19C5.33251 19 6.87444 17.3971 6.87444 15.4269ZM17.5627 13.1567C18.7669 13.1567 19.7466 14.1751 19.7466 15.4269C19.7466 16.6786 18.7669 17.6971 17.5627 17.6971C16.3585 17.6971 15.3788 16.6786 15.3788 15.4269C15.3788 14.1751 16.3585 13.1567 17.5627 13.1567ZM8.31608 3.57313C8.31608 2.32118 9.29581 1.30274 10.5 1.30274C11.7042 1.30274 12.6839 2.32118 12.6839 3.57313C12.6839 4.8249 11.7042 5.84318 10.5 5.84318C9.29581 5.84318 8.31608 4.8249 8.31608 3.57313ZM3.4373 13.1567C4.64149 13.1567 5.62122 14.1751 5.62122 15.4269C5.62122 16.6786 4.64149 17.6971 3.4373 17.6971C2.23311 17.6971 1.25338 16.6786 1.25338 15.4269C1.25338 14.1751 2.23311 13.1567 3.4373 13.1567Z" fill="black"/>
										</svg>
									</button>
									<div class="tp-team-inner-item-social-icons">
										<?php 
										$social_links = [
											['link' => get_post_meta(get_the_ID(), '_cmb_link_social_1', true), 'icon' => get_post_meta(get_the_ID(), '_cmb_icon_social_1', true)],
											['link' => get_post_meta(get_the_ID(), '_cmb_link_social_2', true), 'icon' => get_post_meta(get_the_ID(), '_cmb_icon_social_2', true)],
											['link' => get_post_meta(get_the_ID(), '_cmb_link_social_3', true), 'icon' => get_post_meta(get_the_ID(), '_cmb_icon_social_3', true)],
											['link' => get_post_meta(get_the_ID(), '_cmb_link_social_4', true), 'icon' => get_post_meta(get_the_ID(), '_cmb_icon_social_4', true)],
										]; ?>
										<?php foreach ($social_links as $social) :
											if (!empty($social['link']) && !empty($social['icon'])) : ?>
												<a href="<?php echo esc_url($social['link']); ?>">
													<span><?php echo nixer_inline_svg_from_url($social['icon']); ?></span>
												</a>
											<?php endif;
										endforeach; ?>
									</div>
								</div>
							</div>
							<div class="tp-team-inner-item-content">
								<h4 class="tp-team-inner-item-title">
									<a class="textline" href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h4>
								<span>
									<?php
									$terms = get_the_terms(get_the_ID(), 'team-job');
									if ($terms && !is_wp_error($terms)) {
									    echo esc_html($terms[0]->name);
									} ?>
								</span>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
				<?php 
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
	</div>
</section>

<?php
get_footer();
?>