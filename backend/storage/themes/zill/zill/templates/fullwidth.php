<?php
/**
 * Template Name: Zill Full Width
 * Template Post Type: post, page, product
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zill WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header();


if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
    while (have_posts()) :
        the_post();

        $settings = get_post_meta(get_the_ID(), '_elementor_page_settings', true);
        $show_title = (isset($settings['hide_title']) && $settings['hide_title'] == 'yes') ? false : true;

        if ($show_title && apply_filters('zill/filter/enable_page_title', true)){
            the_title('<div class="page-header page-header--default"><div class="container page-header-inner"><h1 class="entry-title">', '</h1></div></div>');
        }


        the_content();

        wp_reset_postdata();

    endwhile;
}

get_footer();