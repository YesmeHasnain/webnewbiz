<?php global $redux_gauthier;  
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
        <div id="content" role="main">
            <?php get_template_part("page-templates/content2", get_post_format()); ?>
            <?php endwhile; ?>
        </div>
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