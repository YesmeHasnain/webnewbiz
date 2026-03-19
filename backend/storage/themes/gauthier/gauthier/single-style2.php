<?php global $redux_gauthier;  
/*
 * Template Name: Post Style 2
 * Template Post Type: post
 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single2-wrapper related-styledefault <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_sidebar", true) ); ?> <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
    <div class="scrollBar1"></div>
    <?php while (have_posts()):
     the_post(); ?>
    <div class="primary-wrapper">
        <div id="primary" <?php body_class("site-contentdefault"); ?>>
            <div id="content" role="main">
                <?php get_template_part("page-templates/content", get_post_format()); ?>
                <!-- related post -->
                <?php endwhile; ?>
            </div>
        </div>
        <div class="right-sidebar">
            <div class="metaview-wrapper">
                <?php if (function_exists("gauthier_get_post_views")) { ?>
                <span class="metaview1"><?php echo esc_html(gauthier_get_post_views()); ?></span>
                <?php } else { ?>
                <?php } ?>
                <?php if (function_exists("gauthier_reading_times")) { ?>
                <span class="metaview2"><?php echo esc_html(gauthier_reading_times(get_the_ID())); ?></span>
                <?php } ?>
                <span class="metaview3"> <a class="link-comments" href="#respond">
                <?php comments_number(__('0 Comments','gauthier'),__('1 Comment','gauthier'),__('% Comments','gauthier')); ?>
                </a> </span> </div>
            <?php if(get_post_meta( get_the_ID(), 'adv_vert_id' , true )){ ?>
            <div class="adv-sidebar"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_vertlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_vert_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
        </div>
    </div>
    <?php get_template_part("inc/related-post"); ?>
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