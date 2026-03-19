<?php 

    global $post;

    $relatedposts = get_posts( array( 
	
	'category__in' => wp_get_post_categories($post->ID), 
	'numberposts' => 3,
	'order'       => 'ASC',
	'post__not_in' => array($post->ID) ) 
	
	);
	
    if( $relatedposts ) : 

    echo '<div class="theme_related_post_Grid">';
    echo '<h2>'.esc_html__( 'Related Posts', 'knor' ).'</h2>';
	
    echo '<div class="theme_post_grid__Slider_Wrapperr">';
	echo '<div class="theme_post_grid__Slider related-posts-slider row">';
	
	
    foreach( $relatedposts as $post ) {
		
    setup_postdata($post); ?>
    
	
<div class="col-lg-4 col-sm-6">	


<div class="blog-post-tab-wrap post-block-item post-block-item-one">
	
	<div class="news-post-grid-thumbnail">
		<a href="<?php the_permalink(); ?>" class="news-post-grid-thumbnail-wrap">
			<img src="<?php echo esc_attr(esc_url(get_the_post_thumbnail_url(null, 'full'))); ?>" alt="<?php the_title_attribute(); ?>">
		</a>
	</div>
	
	<div class="news-post-grid-content grid-content-bottom">
	
		<div class="slider-category-box tab-cat-box">
		<?php require KNOR_THEME_DIR . '/template-parts/cat-alt-template.php'; ?>
		</div>	

		<h3 class="post-title">
			<a href="<?php the_permalink(); ?>"><?php echo esc_html( wp_trim_words(get_the_title(), 20,'') ); ?></a>
		</h3>
		

		<div class="slider-post-meta-items tab-large-col-meta">
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



						<li class="slider-meta-time"><?php echo knor_reading_time(); ?></li>

					</ul>
				</div>
			</div>

			<div class="slider-meta-right">
				<div class="post-fav-box">
					3k
				</div>
				<div class="post-comment-box">
					63
				</div>
			</div>
			
		</div>
	</div>
	

</div>
				
				
	
</div>	
	
    <?php } 
	
	wp_reset_postdata();

    echo '</div>'; 
	echo '</div>';
    echo '</div>';

    endif;