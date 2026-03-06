<?php
/**
 * The template for displaying home page content.
 * Template Name: Blank Page Default
 * @package geoport
 */
get_header(); 

do_action('geoport_breadcrum');

?>

<!-- Page-section - start
================================================== -->
<div class="primary-bg">
    <div class="inner-blog page-details pt-120 pb-80">
        <div class="container">
            <div class="row">
	            <div class="col-lg-12">
					<?php while(have_posts()) : the_post(); ?> 
						<?php the_content(); ?>
					<?php endwhile; ?>
	          	</div>
          	</div>
        </div>
    </div>
</div>

<?php get_footer(); ?>