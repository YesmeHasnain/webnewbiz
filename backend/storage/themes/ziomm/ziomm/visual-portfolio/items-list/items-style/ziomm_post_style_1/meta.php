<?php
/**
 * Item meta template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

	<div class="vlt-post-content">

		<header class="vlt-post-header">

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'meta-large' ); ?>

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'title' ); ?>

		</header>
		<!-- /.vlt-post-header -->

		<?php if ( $opts[ 'post_1_show_excerpt' ] ) : ?>

			<div class="vlt-post-excerpt">

				<?php echo ziomm_get_trimmed_content( $opts[ 'post_1_excerpt' ] ); ?>

			</div>
			<!-- /.vlt-post-excerpt -->

		<?php endif; ?>

		<?php if ( $opts[ 'post_1_show_read_more' ] ) : ?>

			<footer class="vlt-post-footer">

				<?php get_template_part( 'template-parts/post/partials/partial-post', 'read-more-link' ); ?>

			</footer>
			<!-- /.vlt-post-footer -->

		<?php endif; ?>

	</div>
	<!-- /.vlt-post-content -->

</div>
<!-- /.vlt-post -->