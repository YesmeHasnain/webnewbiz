<?php

/**
 * Template Name: Canvas Page
 * @author: VLThemes
 * @version: 1.0.5
 */

get_header( 'empty' );

?>

<main class="vlt-main">

	<div class="vlt-page-content">

		<?php

			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content/content', 'custom-page' );

			endwhile;

		?>

	</div>
	<!-- /.vlt-page-content -->

</main>
<!-- /.vlt-main -->

<?php get_footer( 'empty' ); ?>