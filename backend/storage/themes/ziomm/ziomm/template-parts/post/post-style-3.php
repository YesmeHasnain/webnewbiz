<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$size = 'ziomm-800x605_crop';

$post_class = 'vlt-post vlt-post--style-3';

?>

<article <?php post_class( $post_class ); ?>>

	<a href="<?php the_permalink(); ?>" class="vlt-post-permalink"></a>

	<?php if ( has_post_thumbnail() ) : ?>

		<div class="vlt-post-media">

			<?php the_post_thumbnail( $size, array( 'loading' => 'lazy' ) ); ?>

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'date' ); ?>

		</div>

	<?php endif; ?>

	<div class="vlt-post-content">

		<header class="vlt-post-header">

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'title-arrow' ); ?>

		</header>
		<!-- /.vlt-post-header -->

	</div>
	<!-- /.vlt-post-content -->

</article>
<!-- /.vlt-post -->