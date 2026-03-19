<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$format = get_post_format();

if ( false == $format ) {
	$format = 'standard';
}

$column_sidebar_class = 'col-lg-4';
$column_content_class = is_active_sidebar( 'blog_sidebar' ) ? 'col-lg-8' : 'col-lg-8 offset-lg-2';

if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) {
	$column_sidebar_class .= ' sticky-parent';
}

$sidebar_class = 'vlt-sidebar vlt-sidebar--right';

?>

<main class="vlt-main">

	<?php

		if ( has_post_thumbnail() ) {

			get_template_part( 'template-parts/single-post/media/media', 'style-4' );

		}

	?>

	<div class="vlt-page-content vlt-page-content--padding-bottom vlt-page-content--padding-top-sm">

		<div class="container">

			<div class="row">

				<div class="<?php echo ziomm_sanitize_class( $column_content_class ); ?>">

					<article <?php post_class( 'vlt-single-post' ); ?>>

						<header class="vlt-single-post__header">

							<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'meta' ); ?>

							<?php get_template_part( 'template-parts/single-post/partials/partial-post', 'title' ); ?>

						</header>
						<!-- /.vlt-single-post__header -->

						<?php if ( has_post_thumbnail() && $format !== 'standard' ) : ?>

							<div class="vlt-single-post__media">

								<?php

									if ( $format == 'standard' ) {

										get_template_part( 'template-parts/post/media/post-media', 'image' );

									} elseif ( $format == 'gallery' ) {

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

					<?php

						if ( ziomm_get_theme_mod( 'also_like_posts' ) == 'show' ) {
							get_template_part( 'template-parts/single-post/sections/section', 'also-like-2-columns' );
						}

					?>

					<?php

						if ( comments_open() || get_comments_number() ) {
							comments_template( '/alternative-comments.php' );
						}

					?>

				</div>

				<?php if ( is_active_sidebar( 'blog_sidebar' ) ) : ?>

					<div class="<?php echo ziomm_sanitize_class( $column_sidebar_class ); ?>">

						<?php if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) : ?>

							<div class="sticky-column">

						<?php endif; ?>

							<div class="<?php echo ziomm_sanitize_class( $sidebar_class ); ?>">

								<?php get_sidebar(); ?>

							</div>

						<?php if ( ziomm_get_theme_mod( 'sticky_sidebar' ) == 'enable' ) : ?>

							</div>

						<?php endif; ?>

					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>
	<!-- /.vlt-page-content -->

</main>
<!-- /.vlt-main -->