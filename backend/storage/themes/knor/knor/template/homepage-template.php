<?php
/*
 * Template Name: HomePage Template
 *  */

get_header(); ?>

<div class="theme-page-content"> 

	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>

</div>
		
<?php get_footer(); ?>