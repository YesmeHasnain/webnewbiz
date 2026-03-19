<?php
/*
Description:Single page for elementor library
 */
get_header();



//page content
while (have_posts()): the_post();

    the_content();

endwhile;


get_footer();?>