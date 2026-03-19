<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
		return;
?>
<?php if ( have_comments() ) : ?>
	<div class="postbox__comment mb-100">
		<h3 class="postbox__comment-title"><?php comments_number( esc_html__('0 Comments', 'nixer'), esc_html__('1 Comment', 'nixer'), esc_html__('% Comments', 'nixer') ); ?></h3>
		<ul>
			<?php wp_list_comments('callback=nixer_theme_comment'); ?>
		</ul>
	</div>
<?php endif; ?>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
?>
	<div class="text-center">
		<ul class="pagination">
			<li>
				<?php paginate_comments_links( array(
					'prev_text' => wp_specialchars_decode('<i class="ti-angle-left"></i>',ENT_QUOTES), 
					'next_text' => wp_specialchars_decode('<i class="ti-angle-right"></i>',ENT_QUOTES)
				));?>
			</li>
		</ul>
	</div>
<?php endif;?>
<?php
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$comment_args = array(
				'class_container' => 'form-comment',
				'id_form' => 'form',
				'class_form' => 'row',
				'title_reply'=>esc_html__( 'Leave A Comment', 'nixer' ),
				'title_reply_before' =>'<h3 class="tp-postbox-details-form-title">',
				'title_reply_after' => '</h3>',
				'fields' => apply_filters( 'comment_form_default_fields', array(
						'author' 	=> '<div class="col-xl-4"><div class="tp-postbox-details-input-box"><div class="tp-postbox-details-input"><input type="text" placeholder="'.esc_attr__('Full Name', 'nixer').'"></div></div></div>',
						'email'		=> '<div class="col-xl-4"><div class="tp-postbox-details-input-box"><div class="tp-postbox-details-input"><input type="email" placeholder="'.esc_attr__('Email', 'nixer').'"></div></div></div>',
						'website'		=> '<div class="col-xl-4"><div class="tp-postbox-details-input-box"><div class="tp-postbox-details-input"><input type="text" placeholder="'.esc_attr__('Website', 'nixer').'"></div></div></div>',
				) ),
					'comment_field' => '<div class="col-xl-12"><div class="tp-postbox-details-input-box"><div class="tp-postbox-details-input"><textarea name="comment" id="message" placeholder="'.esc_attr__('Write A Comment', 'nixer').'"></textarea></div></div></div>', 
				'label_submit' => esc_html__( 'Post A Comment', 'nixer' ),
				'submit_button' => '<div class="tp-postbox-details-input-box"><button class="tp-btn %3$s" type="submit">%4$s</button></div>',
				'submit_field' => '%1$s %2$s',
				'comment_notes_before' => '',
				'comment_notes_after' => '',
		)
?>
<?php if ( comments_open() ) : ?>
	<div class="tp-postbox-details-form">
		<?php comment_form($comment_args); ?>
	</div>
<?php endif; ?> 