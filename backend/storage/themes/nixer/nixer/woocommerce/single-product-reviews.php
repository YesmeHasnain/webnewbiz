<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div class="tp-product-details-review-wrapper tp-product-details-review-wrapper-2">
	<div id="comments">
		<h3 class="tp-product-details-review-title-2 text-cap">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'nixer' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'nixer' );
			}
			?>
		</h3>
		<div class="row">
			<div class="col-xl-12">
				<div class="tp-product-details-review-item-wrapper-2">
					<?php if ( have_comments() ) : ?>
						<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
						<?php
						if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
							echo '<nav class="woocommerce-pagination">';
							paginate_comments_links(
								apply_filters(
									'woocommerce_comment_pagination_args',
									array(
										'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
										'next_text' => is_rtl() ? '&larr;' : '&rarr;',
										'type'      => 'list',
									)
								)
							);
							echo '</nav>';
						endif;
						?>
					<?php else : ?>
						<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'nixer' ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
				<div class="col-lg-12">
					<div class="tp-product-details-review-form pt-55">
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							/* translators: %s is product title */
							'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'nixer' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'nixer' ), get_the_title() ),
							/* translators: %s is product title */
							'title_reply_to'      => esc_html__( 'Add a Review to %s', 'nixer' ),
							'title_reply_before'  => '<h3 class="tp-product-details-review-form-title">',
							'title_reply_after'   => '</h3>',
							'comment_notes_after' => '',
							'label_submit'        => esc_html__( 'Send Message', 'nixer' ),
							'logged_in_as'        => '',
							'comment_field'       => '',
						);

						$name_email_required = (bool) get_option( 'require_name_email', 1 );
						$fields              = array(
							'author' => array(
								'label'        => __( 'Your Name', 'nixer' ),
								'type'         => 'text',
								'value'        => $commenter['comment_author'],
								'required'     => $name_email_required,
								'autocomplete' => 'name',
							),
							'email'  => array(
								'label'        => __( 'Your Email', 'nixer' ),
								'type'         => 'email',
								'value'        => $commenter['comment_author_email'],
								'required'     => $name_email_required,
								'autocomplete' => 'email',
							),
						);

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'nixer' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="tp-product-details-review-form-rating tp-product-details-review-form-rating-icon d-flex align-items-center"><label for="rating" id="comment-form-rating-label">' . esc_html__( 'Your rating', 'nixer' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'nixer' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'nixer' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'nixer' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'nixer' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'nixer' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'nixer' ) . '</option>
							</select></div>';
						}
						


						$comment_form['fields'] = array();
						$comment_form['fields']['wrapper_open'] = '<div class="contact-form-box"><div class="row">';
						foreach ( $fields as $key => $field ) {
							$field_html  = '<div class="col-md-6 mb-30 comment-form-' . esc_attr( $key ) . '">';

							$field_html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" autocomplete="' . esc_attr( $field['autocomplete'] ) . '" placeholder="' . esc_attr( $field['label'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></div>';

							$comment_form['fields'][ $key ] = $field_html;
						}
						$comment_form['fields']['wrapper_close'] = '</div></div>';

						$comment_form['comment_field'] .= '<div class="col-md-12 mb-45"><textarea id="comment" name="comment" cols="30" rows="10" placeholder="Write your review here..." required></textarea></div>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>
			<?php else : ?>
				<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'nixer' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
