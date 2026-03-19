	<article <?php post_class('post-block-style-wrapper post-block-template-one'); ?>>

        <div class="post-block-style-inner">

        	<?php if(has_post_thumbnail()): ?>
            <div class="post-block-media-wrap">
				<a href="<?php the_permalink(); ?>" class="post-block-media-wrap">
					<img class="img-fluid" src="<?php echo esc_attr(esc_url(get_the_post_thumbnail_url(null, 'full'))); ?>" alt="<?php the_title_attribute(); ?>">
				</a>
            </div>
            <?php endif; ?>

            <div class="post-block-content-wrap">

	            <div class="post-top-meta-list">

					<div class="post-category-box">
						<?php require KNOR_THEME_DIR . '/template-parts/cat-alt-template.php'; ?>
					</div>

					<div class="post-meta-date-box">
						<?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
					</div>

		    	</div>


                <div class="post-item-title">
                    <h2 class="post-title">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a>
                    </h2>
                </div>

                <div class="post-excerpt-box">
                    <p><?php echo esc_html( wp_trim_words(get_the_excerpt(), 22 ,'...') );?><a href="<?php the_permalink(); ?>" class="blog-read-more-btn">Read More</a></p>
                </div>

            </div>
        </div>
    </article>

