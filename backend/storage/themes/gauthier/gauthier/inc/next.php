<?php $nextPost = get_next_post();
	if ($nextPost) {
		$args = [
		"posts_per_page" => 1,
		"include" => $nextPost->ID,
		];
		$nextPost = get_posts($args);
		foreach ($nextPost as $post) {
			setup_postdata($post); ?>
			<div class="postnext-top"><?php esc_html_e("NEXT ARTICLES", "gauthier"); ?></div>
			        <div class="prevnext-meta">
            <div class="submeta-singlepost">

				<?php gauthier_breadcrumb(); ?>
				<div class="head-divider"></div>
				<div class="head-date">
                <?php echo get_the_date(); ?>
				</div>
			</div>
            <div class="adt-comment">
                <div class="features-onsinglepost">
                    <?php if (function_exists("sharing_display")) {echo sharing_display();} ?>
                </div>
            </div>
        </div>
				<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
				<span><?php echo wp_trim_words( get_the_content(), 33, '' ); ?></span>
			<?php wp_reset_postdata();
		}
	}
?>
				