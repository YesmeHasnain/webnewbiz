<?php global $redux_gauthier;  
/* Template Name: Template Page Style 2 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single2-wrapper <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
	<?php while (have_posts()):
     the_post(); ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<?php get_template_part("page-templates/content2", get_post_format()); ?>
			<?php comments_template("", true); ?>
			<?php endwhile; ?>
		</div>
	</div>
	<div class="sidebar">
		<div class="single2-widget">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
} ?>
