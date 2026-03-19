<div class="main-content-inner category-layout-one">
  
	<?php while ( have_posts() ) : the_post(); ?>
	
	<div <?php post_class('post-block-wrapper-latest post-block-style-latest blog-block-latest-single-item'); ?>>
		
		<?php if(has_post_thumbnail()): ?>
		<div class="post-thumbnail latest-post-thumbnail-wrap">
			<a href="<?php the_permalink(); ?>" class="latest-post-block-thumbnail">
				<img class="img-fluid" src="<?php echo esc_attr(esc_url(get_the_post_thumbnail_url(null, 'full'))); ?>" alt="<?php the_title_attribute(); ?>">
			</a>
		</div>
		<?php endif; ?>
	
		<div class="latest-post-block-content">
		
			<div class="slider-category-box tab-cat-box">
				<?php require SAASTEN_THEME_DIR . '/template-parts/cat-alt-template.php'; ?>
			</div>
	
			<h3 class="post-title">
				<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a>
			</h3>
			
			<div class="post-excerpt-box">
				<p><?php echo esc_html( wp_trim_words(get_the_excerpt(), 28 ,'') );?></p>
			</div>
			
			<div class="slider-post-meta-items tab-small-col-meta">
				<div class="slider-meta-left">

					<div class="slider-meta-left-author">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
					</div>
					
					<div class="slider-meta-left-content">

						<h4 class="post-author-name">
							<?php echo get_the_author_link(); ?>
						</h4>

						<ul class="slider-bottom-meta-list">
							<li class="slider-meta-date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></li>
						</ul>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
	
	<?php endwhile; ?>

</div>