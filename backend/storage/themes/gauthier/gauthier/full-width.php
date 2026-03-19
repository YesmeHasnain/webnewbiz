<?php
/* Template Name: Full-width Page Template, No Sidebar */
if (isset($redux_gauthier["header_layout"])) {
	get_header($redux_gauthier["header_layout"]);
} else {
	get_header();
}
?>
<div class="single2-wrapper">
	<?php while (have_posts()):
		the_post(); ?>
		<div id="primary" class="site-content nosidebar">
			<div id="content" role="main">
				<?php get_template_part("page-templates/content5", get_post_format()); ?>
				<nav class="nav-single">
					<div class="assistive-text">
						<?php _e("Post navigation", "gauthier"); ?>
					</div>
					<span class="nav-previous">
						<?php previous_post_link(
							"%link",
							'<span class="meta-nav">' .
							_x("&larr;", "Previous post link", "gauthier") .
							"</span> %title"
						); ?>
					</span> <span class="nav-next">
						<?php next_post_link(
							"%link",
							'%title <span class="meta-nav">' .
							_x("&rarr;", "Next post link", "gauthier") .
							"</span>"
						); ?>
					</span>
				</nav>
				<?php comments_template("", true); ?>
				<?php
			endwhile; ?>
		</div>
	</div>
</div>
<?php if (isset($redux_gauthier["footer_layout"])) {
	get_template_part($redux_gauthier["footer_layout"]);
} else {
	get_footer();
}
?>