<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'vlt-review-item' ); ?>>

	<div class="vlt-review-item__inner">

		<?php if ( 0 != $args['avatar_size'] && get_avatar( $comment ) ) : ?>
			<a class="vlt-review-avatar" href="<?php echo get_comment_author_url(); ?>"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></a>
			<!-- /.vlt-review-avatar -->
		<?php endif; ?>

		<div class="vlt-review-content">

			<div class="vlt-review-header">

				<h6 class="vlt-review-name"><?php comment_author(); ?></h6>

				<div class="vlt-review-metas">

					<?php do_action( 'woocommerce_review_before_comment_meta', $comment ); ?>

				</div>
				<!-- /.vlt-review-metas -->

			</div>
			<!-- /.vlt-review-header -->

			<div class="vlt-review-text vlt-content-markup">

				<?php

					do_action( 'woocommerce_review_before_comment_text', $comment );
					do_action( 'woocommerce_review_comment_text', $comment );
					do_action( 'woocommerce_review_after_comment_text', $comment );

				?>

			</div>
			<!-- /.vlt-review-text -->

		</div>
		<!-- /.vlt-review-content -->

	</div>
	<!-- /.vlt-review-item__inner -->