<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

if ( post_password_required() ) {
	return;
}

?>

<div class="vlt-page-comments vlt-page-comments--style-1" id="comments">

	<?php if ( have_comments() ) : ?>

		<div class="vlt-page-comments__list">

			<div class="container">

				<div class="row">

					<div class="col-lg-8 offset-lg-2">

						<h2 class="vlt-page-comments__title">

							<?php comments_number( esc_html__( 'No comments', 'ziomm' ) , esc_html__( 'Comment:', 'ziomm' ) , esc_html__( 'Comments:', 'ziomm' ) ); ?>

						</h2>

						<ul class="vlt-comments">

							<?php

								wp_list_comments( array(
									'avatar_size' => 200,
									'style' => 'ul',
									'max_depth' => '3',
									'short_ping' => true,
									'reply_text' => '<i class="icon-reply"></i>' . esc_html__( 'Reply', 'ziomm' ),
									'callback' => 'ziomm_callback_custom_comment',
								) );

							?>

						</ul>

						<?php echo ziomm_get_comment_navigation(); ?>

					</div>

				</div>

			</div>

		</div>

	<?php endif; ?>

	<?php if ( comments_open() ) : ?>

		<div class="vlt-page-comments__form">

			<div class="container">

				<div class="row">

					<div class="col-lg-8 offset-lg-2">

						<?php

							$commenter = wp_get_current_commenter();

							$args = array(
								'title_reply' => esc_html__( 'Leave a comment:', 'ziomm' ),
								'title_reply_before' => '<h2 class="vlt-page-comments__title">',
								'title_reply_after' => '</h2>',
								'cancel_reply_before' => '',
								'cancel_reply_after' => '',
								'comment_notes_before' => '',
								'comment_notes_after' => '',
								'title_reply_to' => esc_html__( 'Leave a Reply', 'ziomm' ),
								'cancel_reply_link' => '<i class="icon-cross"></i>',
								'submit_button' => '<button type="submit" id="%2$s" class="%3$s">%4$s</button>',
								'class_submit' => 'vlt-btn vlt-btn--effect vlt-btn--primary',
								'label_submit' => esc_html__( 'Post a Comment', 'ziomm' ),
								'comment_field' => '<div class="vlt-form-group"><textarea id="comment" name="comment" rows="5" class="vlt-form-control"></textarea><label for="comment" class="vlt-form-label">' . esc_attr__( 'Comment', 'ziomm' ) . '</label></div>',
								'fields' => array(
									'cookies' => false,
									'author' => '<div class="vlt-form-row two-col"><div class="vlt-form-group"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" class="vlt-form-control"><label for="author" class="vlt-form-label">' . esc_attr__( 'Your name *', 'ziomm' ) . '</label></div>',
									'email' => '<div class="vlt-form-group"><input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" class="vlt-form-control"><label for="email" class="vlt-form-label">' . esc_attr__( 'Email address *', 'ziomm' ) . '</label></div></div>',
								),
							);

							comment_form( $args );

						?>

					</div>

				</div>

			</div>

		</div>

	<?php endif; ?>

</div>
<!-- /.vlt-page-comments -->