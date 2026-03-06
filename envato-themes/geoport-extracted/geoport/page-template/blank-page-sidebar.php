<?php
/**
 * The template for displaying home page content.
 * Template Name: Blank Page with breadcrumb
 * @package geoport
 */
get_header(); 

do_action('geoport_breadcrum');

?>

<?php while(have_posts()) : the_post(); ?> 

	<?php the_content(); ?>
	
<?php endwhile; ?>

<?php get_footer(); ?>