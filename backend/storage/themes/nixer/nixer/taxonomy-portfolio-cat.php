<?php 
$nixer_redux_demo = get_option('redux_demo');
get_template_part( 'header/header', '9' );
?>

<?php
$term = get_queried_object();

$args = array(
    'post_type' => 'portfolio',
    'posts_per_page' => 8,
    'tax_query' => array(
        array(
            'taxonomy' => 'portfolio-cat',
            'field' => 'slug',
            'terms' => $term->slug,
        ),
    ),
);
$query = new WP_Query($args);
?>

<section class="tp-portfolio-col-2-ptb pt-235 pb-130 p-relative">
	<div class="tp-about-me-bg" data-background="assets/img/about/about-me/about-me-bg.png"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="tp-blog-heading portfolio p-relative pb-100 mb-155">
					<span class="tp-breadcrumb-subtitle">
						<?php if(isset($nixer_redux_demo['portcat-title']) && $nixer_redux_demo['portcat-title']!=''){?>
							<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['portcat-title']));?>
						<?php }else{?>
							<?php echo esc_html__( 'Portfolio Category', 'nixer' );
						}?>
					</span>
					<h3 class="tp-breadcrumb-title tp-title-anim"><?php printf( esc_html__( ' %s', 'nixer' ), single_term_title( '', false ) );?></h3>
				</div>
			</div>
		</div>
	</div>
	<div class="container container-1800">
		<div class="row">
			<?php
			$i = 0;
			while ($query->have_posts()) : $query->the_post();
				$post_id = get_the_ID();
				$i++;
			?>
				<div class="col-xxl-3 col-xl-4 col-md-6">
					<div class="tp-portfolio-6-item p-relative mb-30">
						<div class="tp-portfolio-6-item-thumb">
							<a href="<?php the_permalink(); ?>">
								<img class="ratio-49x58" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
							</a>
						</div>
						<div class="tp-portfolio-6-item-content">
							<div class="tp-portfolio-6-item-content-hide">
								<span><?php echo get_the_date('M Y'); ?></span>
								<h4 class="tp-portfolio-6-item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>

			<?php 
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			$big = 999999999;
			$pagination = array(
				'base'      	=> str_replace($big, '%#%', get_pagenum_link($big)),
				'format'    	=> '',
				'current'   	=> $paged,
				'total'     	=> $query->max_num_pages,
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
</section>

<?php
get_footer();
?>