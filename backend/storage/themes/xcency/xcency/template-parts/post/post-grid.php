<div class="row all-posts-wrapper">

	<?php
	if ( have_posts() ) :
		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
				<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
			</header>
		<?php
		endif;
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/post/post-item-wrapper');

		endwhile;

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>
</div>

<?php get_template_part( 'template-parts/post/post-pagination' );?>