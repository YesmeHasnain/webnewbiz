<?php
/*
* Content display template
*/
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-headersearch">
		<div class="category1-time">
		<a href="<?php echo get_author_posts_url(get_the_author_meta("ID")); ?>" rel="author"> <?php printf( __(" %s", "gauthier"), get_the_author()); ?></a>
		<span><?php esc_attr_e(" - ", "gauthier"); ?></span>
		<?php echo esc_html(get_the_date()); ?>
			<div class="module9-view">
				<span class="view2">
					<?php if (function_exists("gauthier_get_post_views")) { ?>
					<?php echo esc_html(gauthier_get_post_views()); ?>
					<?php } else { ?>
					<?php } ?>
				</span>
				<?php if (function_exists("gauthier_reading_times")) { ?>
				<?php echo esc_html(gauthier_reading_times(get_the_ID())); ?>
				<?php } else { ?>
				<?php } ?>
			</div>
		</div>
		<div class="search-titlebig">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</div>
	</div>
	<div class="entry-content ctest">
		<?php the_content( __('Continue reading <span class="meta-nav">&rarr;</span>', "gauthier")); ?>
		<?php wp_link_pages([
		"before" => '<div class="page-links">' . __("Pages:", "gauthier"),
		"after" => "</div>",
		]); ?>
	</div>
</div>
<footer class="entry-meta">
	<?php the_tags(); ?>
</footer>