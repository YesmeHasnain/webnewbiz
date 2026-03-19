<?php
/*
 * Template Name: Nixer Page Templates White
 * Description: A Page Template with a Page Builder design.
 */
$nixer_redux_demo = get_option('redux_demo');
get_header();
?>
	<main>
		<?php if (have_posts()){ ?>
			<?php while (have_posts()) : the_post()?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		<?php }else {
			echo esc_html__( 'Nixer Page Templates White', 'nixer' );
		}?>
	</main>
<?php 
get_footer();
?>