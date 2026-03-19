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

$post_class = 'vlt-post vlt-post--style-3';

?>

<div <?php post_class( $post_class ); ?>>

	<a href="<?php the_permalink(); ?>" class="vlt-post-permalink"></a>

	<div class="vp-portfolio__item-img-wrap">

		<div class="vp-portfolio__item-img">

			<?php if ( $args['image'] ) : ?>

				<?php echo wp_kses( $args['image'], $args['image_allowed_html'] ); ?>

			<?php endif; ?>

			<?php get_template_part( 'template-parts/post/partials/partial-post', 'date' ); ?>

		</div>

	</div>
	<!-- /.vp-portfolio__item-img-wrap -->