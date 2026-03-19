<?php global $redux_gauthier;  
/*
 * Template Name: Post Style 6
 * Template Post Type: post
 */
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="scrollBar1"></div>
<div class="<?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
    <?php get_template_part("page-templates/content6", get_post_format()); ?>
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