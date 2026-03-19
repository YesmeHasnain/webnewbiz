	<div <?php post_class('post-block-wrapper-latest post-block-style-latest blog-block-latest-single-item'); ?>>


		<?php if(has_post_thumbnail()): ?>

	    <div class="post-thumbnail latest-post-thumbnail-wrap blog-single-post__thumb">
			<a href="<?php the_permalink(); ?>" class="latest-post-block-thumbnail">
				<img class="img-fluid" src="<?php echo esc_attr(esc_url(get_the_post_thumbnail_url(null, 'full'))); ?>" alt="<?php the_title_attribute(); ?>">
			</a>
		</div>

		<?php endif; ?>

		<!-- Blog Meta -->
		<div class="blog-single-meta">
			<li>
			  <span><img src="<?php echo SAASTEN_IMG ."/author-icon.svg"; ?>" /></span>
			  <span> by <?php echo get_the_author_link(); ?></span>
			</li>
			<li>
			  <span><img src="<?php echo SAASTEN_IMG ."/calendar-icon.svg"; ?>" /></span>
			  <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
			</li>
		</div>

		<div class="latest-post-block-content">
			<h3 class="post-title">
				<a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a>
			</h3>
			<div class="post-excerpt-box">
				<p><?php echo esc_html( wp_trim_words(get_the_excerpt(), 35 ,'') );?></p>
			</div>
		</div>
		
	</div>