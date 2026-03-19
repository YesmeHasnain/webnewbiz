<?php global $redux_gauthier;  
/*
 * Template Name: Post Style 5
 * Template Post Type: post
 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single-wrapper">
    <div class="scrollBar1"></div>
    <?php while (have_posts()):
     the_post(); ?>
		<?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 1260, 555, true,true,true ); ?>
		<?php if ($image) { ?>
			<div id="feature5" class="feature-postimg">
			<img src="<?php echo esc_html($image) ?>"/>
        <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
		</div>	
	<?php } else { ?>
<?php } ?>	 
    <div class="site-content nosidebar">

            <?php get_template_part("page-templates/content5", get_post_format()); ?>            
            <!-- related post -->
            <?php get_template_part("inc/related-post2"); ?>
            <?php endwhile; ?>

        <!-- #content --> 
    </div>
</div>
<?php if (function_exists("gauthier_set_post_views")) { ?>
<?php gauthier_set_post_views(); ?>
<?php } else { ?>
<?php } ?>
<!-- #primary -->
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
} ?>