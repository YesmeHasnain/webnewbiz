<?php
if ( post_password_required() ) {
    return;
}
?>
<div id="comments" class="comments-area">
    <?php
    if ( have_comments() ) :
        ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf( esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'clarity-theme' ), '<span>' . get_the_title() . '</span>' );
            } else {
                printf( esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'clarity-theme' ) ),
                    number_format_i18n( $comment_count ), '<span>' . get_the_title() . '</span>' );
            }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ul class="comment-list">
            <?php wp_list_comments(array('callback' => 'clarity_theme_comment', 'style' => 'ul')); ?>
        </ul>

        <?php
        the_comments_navigation();
        if ( ! comments_open() ) :
            ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'clarity-theme' ); ?></p>
        <?php endif;
    endif;

    $commenter = wp_get_current_commenter();
    $fields = array(
        'author' => '<div class="clear comment_fields"><p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" required placeholder="' . __('Name*', 'clarity-theme') . '" /></p>',
        'email'  => '<p class="comment-form-email"><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" required placeholder="' . __('Email*', 'clarity-theme') . '" /><span>Your email will not be published.</span></p>',
        'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" placeholder="' . __('Website', 'clarity-theme') . '" /></p></div>'
    );
    $defaults = array(
        'title_reply' => '',
        'fields'      => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => '<div class="comment-form-comment"><textarea placeholder="' . __('Write a comment...', 'clarity-theme') . '" id="comment" name="comment" cols="45" rows="5" required></textarea></div>'
    );
    comment_form($defaults);
    ?>
</div>
