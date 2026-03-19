<?php

/**
 * Template Name: Style 3
 * Template Post Type: post
 * @author: VLThemes
 * @version: 1.0.5
 */

get_header();

while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/single-post/layout/layout', 'style-3' );

endwhile;

get_footer(); ?>