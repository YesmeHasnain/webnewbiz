<?php global $redux_gauthier;  
/*
 * Template Name: Post Style 3
 * Template Post Type: post
 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 1300, 550, true,true,true ); ?>
<?php if ($image) { ?>
<div class="feature3-postimg"> <img src="<?php echo esc_html($image) ?>"/>
    <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
</div>
<?php } else { ?>
<?php } ?>
<div class="single2-wrapper <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_sidebar", true) ); ?> <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
    <div class="scrollBar1"></div>
    <?php while (have_posts()):
     the_post(); ?>
    <div id="primary" <?php body_class("site-content"); ?>>
        <div id="content" role="main">
            <?php get_template_part("page-templates/content3", get_post_format()); ?>
            <!-- related post -->
            <?php get_template_part("inc/related-post2"); ?>
            <?php endwhile; ?>
        </div>
        <!-- #content --> 
    </div>
    <div class="sidebar">
        <div class="single2-widget">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
<?php if (function_exists("gauthier_set_post_views")) { ?>
<?php gauthier_set_post_views(); ?>
<?php } else { ?>
<?php } ?>
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
} ?>