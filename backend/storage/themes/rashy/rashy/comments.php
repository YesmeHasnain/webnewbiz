<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Rashy
 * @since Rashy 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area mt-5">

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title mb-4">
			<?php
				comments_number(
					esc_html__( '0 Comments', 'rashy' ),
					esc_html__( '1 Comment', 'rashy' ),
					esc_html__( '% Comments', 'rashy' )
				);
			?>
		</h3>

		<?php rashy_comment_nav(); ?>

		<ol class="comment-list list-unstyled mb-4">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'callback'   => 'rashy_list_comment',
				) );
			?>
		</ol>

		<?php rashy_comment_nav(); ?>

	<?php endif; ?>

	<?php
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments alert alert-info">
			<?php esc_html_e( 'Comments are closed.', 'rashy' ); ?>
		</p>
	<?php endif; ?>

	<?php
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = $req ? " aria-required='true'" : '';

	$comment_args = array(

		'title_reply' => '<h4 class="comment-reply-title mb-3">' . esc_html__( 'Leave a Comment', 'rashy' ) . '</h4>',

		'comment_field' => '
			<div class="mb-3">
				<textarea
					id="comment"
					name="comment"
					class="form-control form-group "
					rows="6"
					placeholder="' . esc_attr__( 'Your Comment', 'rashy' ) . '"
					required
				></textarea>
			</div>
		',

		'fields' => array(

			'author' => '
				<div class="mb-3">
					<input
						type="text"
						id="author"
						name="author"
						class="form-control form-group "
						placeholder="' . esc_attr__( 'Name *', 'rashy' ) . '"
						value="' . esc_attr( $commenter['comment_author'] ) . '"
						' . $aria_req . '
					/>
				</div>
			',

			'email' => '
				<div class="mb-3">
					<input
						type="email"
						id="email"
						name="email"
						class="form-control form-group "
						placeholder="' . esc_attr__( 'Email *', 'rashy' ) . '"
						value="' . esc_attr( $commenter['comment_author_email'] ) . '"
						' . $aria_req . '
					/>
				</div>
			',

			'url' => '
				<div class="mb-3">
					<input
						type="url"
						id="url"
						name="url"
						class="form-control form-group "
						placeholder="' . esc_attr__( 'Website', 'rashy' ) . '"
						value="' . esc_attr( $commenter['comment_author_url'] ) . '"
					/>
				</div>
			',
		),

		'comment_notes_before' => '
			<p class="comment-notes text-muted mb-3">
				' . esc_html__( 'Your email address will not be published.', 'rashy' ) . '
			</p>
		',

		'comment_notes_after' => '',

		'label_submit' => esc_html__( 'Post Comment', 'rashy' ),

		'class_submit' => 'btn btn-primary',

	);

	comment_form( $comment_args );
	?>

</div>