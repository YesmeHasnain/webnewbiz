<?php
/*
Template Name: Blank Page Builder
Template Post Type: page, event,elementor_library
Description:One Page Builder with container.
 */
get_header();

//custom header
if (class_exists('Kirki')) {
    do_action('mancink-custom-header', 'mancink_header_start');
} else {?>

	<!--Fall back if no kirki plugin installed-->
	<!--HOME START-->
	<div class="default-header clearfix">
		<!--HEADER START-->
		<?php get_template_part('loop/menu', 'normal');?>
		<!--HEADER END-->
	</div>
	<!--/home end-->
	<!--HOME END-->

<?php }

//page content
while (have_posts()): the_post();

    the_content();

endwhile;

//custom footer
if (class_exists('Kirki')) {
    do_action('mancink-custom-footer', 'mancink_footer_start');
} else {
    //fallback if no kirki installed
    get_template_part('loop/bottom', 'footer');
}

get_footer();?>