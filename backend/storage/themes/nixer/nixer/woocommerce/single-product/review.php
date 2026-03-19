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
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$all_comments = get_approved_comments( get_the_ID() );
$last_comment_id = end( $all_comments )->comment_ID;
$is_last = ( get_comment_ID() == $last_comment_id );
$extra_class = $is_last ? '' : ' mb-35';
?>
<div <?php comment_class('tp-product-details-review-item-2' . $extra_class); ?> id="li-comment-<?php comment_ID(); ?>">
	<div class="row" id="comment-<?php comment_ID(); ?>">
		<div class="col-lg-8">
			<div class="tp-product-details-review-avater-2 d-flex">
				<div class="tp-product-details-review-avater-thumb">
					<a href="<?php echo esc_url( get_author_posts_url( get_comment()->user_id ) ); ?>">
						<?php echo get_avatar( $comment, 60 ); ?>
					</a>
				</div>
				<div class="tp-product-details-review-avater-content">
					
					<div class="tp-product-details-review-avater-rating d-flex align-items-center">
						<?php
							$rating = intval( get_comment_meta( get_comment_ID(), 'rating', true ) );
							if ( $rating && $rating > 0 ) {
								for ( $i = 1; $i <= 5; $i++ ) {
									echo '<span>';
									echo wp_kses_post($i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="fa-regular fa-star"></i>');
									echo '</span>';
								}
							}
						?>
					</div>

					<h3 class="tp-product-details-review-avater-title"><?php comment_author(); ?></h3>

					<span class="tp-product-details-review-avater-meta mb-10">
						<?php echo esc_html( date_i18n( 'd F, Y', get_comment_time( 'U' ) ) ); ?>
					</span>

					<div class="tp-product-details-review-avater-comment">
						<?php comment_text(); ?>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>