<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

get_header();

?>

<main class="vlt-main">

	<?php get_template_part( 'template-parts/page-title/page-title', 'page' ); ?>

	<div class="vlt-page-content vlt-page-content--padding">

		<?php

			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content/content', 'page' );

			endwhile;
		?>

	</div>
	<!-- /.vlt-page-content -->

	<?php

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	?>

</main>
<!-- /.vlt-main -->

<?php get_footer(); ?>