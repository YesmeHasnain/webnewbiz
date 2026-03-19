<?php
/**
 * Item image template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_class = 'vlt-post vlt-post--style-4';

?>

<div <?php post_class( $post_class ); ?>>

	<?php if ( $args['image'] ) : ?>

		<div class="vp-portfolio__item-img-wrap">

			<div class="vp-portfolio__item-img">

				<?php if ( $args['url'] ) { ?>

					<a href="<?php echo esc_url( $args['url'] ); ?>">
						<?php echo wp_kses( $args['image'], $args['image_allowed_html'] ); ?>
					</a>

				<?php } else {
					echo wp_kses( $args['image'], $args['image_allowed_html'] );
				}
				?>

				<?php get_template_part( 'template-parts/post/partials/partial-post', 'date' ); ?>

			</div>

		</div>
		<!-- /.vp-portfolio__item-img-wrap -->

	<?php endif; ?>