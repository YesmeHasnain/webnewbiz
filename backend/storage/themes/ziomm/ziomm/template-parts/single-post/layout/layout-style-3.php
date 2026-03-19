<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$format = get_post_format();

if ( false == $format ) {
	$format = 'standard';
}

?>

<main class="vlt-main">

	<?php

		if ( has_post_thumbnail() ) {

			get_template_part( 'template-parts/single-post/media/media', 'style-2' );

		}

	?>

	<div class="vlt-page-content vlt-page-content--padding-bottom vlt-page-content--padding-top-sm">

		<div class="container">

			<div class="row">

				<div class="col-lg-8 offset-lg-2">

					<article <?php post_class( 'vlt-single-post' ); ?>>

						<?php if ( has_post_thumbnail() && $format !== 'standard' ) : ?>

							<div class="vlt-single-post__media">

								<?php

									if ( $format == 'gallery' ) {

										get_template_part( 'template-parts/post/media/post-media', 'gallery' );

									} else {

										get_template_part( 'template-parts/single-post/partials/partial-post', 'media' );

									}

								?>

							</div>
							<!-- /.vlt-single-post__media -->

						<?php endif; ?>

						<div class="vlt-single-post__content clearfix">

							<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'content' ); ?>

						</div>
						<!-- /.vlt-single-post__content -->

						<footer class="vlt-single-post__footer">

							<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'footer' ); ?>

						</footer>
						<!-- /.vlt-single-post__footer -->

						<?php

							if ( ziomm_get_theme_mod( 'about_author' ) == 'show' ) {
								get_template_part( 'template-parts/single-post/sections/section', 'about-author' );
							}

						?>

					</article>

				</div>

			</div>

		</div>

	</div>
	<!-- /.vlt-page-content -->

	<?php

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}

	?>

	<?php

		if ( ziomm_get_theme_mod( 'also_like_posts' ) == 'show' ) {
			get_template_part( 'template-parts/single-post/sections/section', 'also-like-3-columns' );
		}

	?>

</main>
<!-- /.vlt-main -->