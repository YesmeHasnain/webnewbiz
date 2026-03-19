<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$size = 'ziomm-800x605_crop';

$post_class = 'vlt-post vlt-post--style-2';

?>

<article <?php post_class( $post_class ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

		<div class="vlt-post-media">

			<a href="<?php the_permalink(); ?>">

				<?php the_post_thumbnail( $size, array( 'loading' => 'lazy' ) ); ?>

			</a>

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'date' ); ?>

		</div>

	<?php endif; ?>

	<div class="vlt-post-content">

		<header class="vlt-post-header">

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'title' ); ?>

		</header>
		<!-- /.vlt-post-header -->

		<div class="vlt-post-excerpt">

			<?php echo ziomm_get_trimmed_content( 22 ); ?>

		</div>
		<!-- /.vlt-post-excerpt -->

		<footer class="vlt-post-footer">

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'read-more-link' ); ?>

		</footer>
		<!-- /.vlt-post-footer -->

	</div>
	<!-- /.vlt-post-content -->

</article>
<!-- /.vlt-post -->