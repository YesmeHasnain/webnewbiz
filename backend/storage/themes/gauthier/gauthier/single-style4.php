<?php global $redux_gauthier;  
/*
 * Template Name: Post Style 4
 * Template Post Type: post
 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single2-wrapper <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_sidebar", true) ); ?> <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
    <div class="scrollBar1"></div>
    <?php while (have_posts()):
     the_post(); ?>
    <div id="primary" <?php body_class("site-content"); ?>>
        <div id="content" class="entry-content" role="main">
            <?php get_template_part("page-templates/content4", get_post_format()); ?>
            <!-- .nav-single -->
            <nav class="nav-single"> <span class="nav-previous">
                <?php get_template_part("inc/prev"); ?>
                </span> <span class="nav-next">
                <?php get_template_part("inc/next"); ?>
                </span> </nav>
            <?php comments_template("", true); ?>
            <!-- related post -->
            <?php get_template_part("inc/related-post"); ?>
            <?php endwhile;?>
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
<!-- #primary -->
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
} ?>