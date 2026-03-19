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
							<?php if(isset($nixer_redux_demo['svcat-title']) && $nixer_redux_demo['svcat-title']!=''){?>
								<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['svcat-title']));?>
							<?php }else{?>
								<?php echo esc_html__( 'Service Category:', 'nixer' );
							}?>
							<span class="text-cap"><?php printf( esc_html__( ' %s', 'nixer' ), single_term_title( '', false ) );?></span>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</div> 
</section>

<section class="tp-service-2-ptb pt-100 pb-120">
	<div class="container container-1520">
		<div class="row">
			<div class="col-lg-12">
				<div class="tp-service-2-wrapper service-inner p-relative z-index-1">
					<?php
					$i = 0;
					while (have_posts()): the_post();
						$post_id = get_the_ID();
						$i++;
					?>
						<div class="tp-service-2-item p-relative z-index-1 d-flex align-items-center">
							<div class="tp-service-2-item-list">
								<span><?php echo esc_html(sprintf('%02d', $i)); ?></span>
							</div>
							<?php if (has_post_thumbnail()) { ?>
								<div class="tp-service-2-item-thumb">
									<a href="<?php the_permalink(); ?>">
										<img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
									</a>
								</div>
							<?php } ?>
							<div class="tp-service-2-item-text">
								<h2 class="tp-service-2-item-text-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>
							</div>
							<div class="tp-service-2-item-point">
								<ul>
									<?php 
									$terms = get_the_terms(get_the_ID(), 'service-cat');
									if (!empty($terms) && !is_wp_error($terms)) {
										$terms = array_slice($terms, 0, 5);
										foreach ($terms as $term) {
											$term_link = get_term_link($term);
											echo '<li><a href="' . esc_url($term_link) . '">. ' . esc_html($term->name) . '</a></li>';
										}
									} ?>
								</ul>
							</div>
							<div class="tp-service-2-item-btn">
								<a href="<?php the_permalink(); ?>">
									<span>
										<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
											<path d="M0.980469 14.7205L13.487 2.21396M3.03906 1.61919C6.08974 4.67214 11.1754 4.52536 14.4098 1.29102C11.1754 4.52536 11.0271 9.61301 14.0778 12.666" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"/>
										</svg>
									</span>
								</a>
							</div>
						</div>
					<?php endwhile ?>
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
	</div>
</section>

<?php
get_footer();
?>