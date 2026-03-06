<?php
if (post_password_required()) {
    return;
}
?>
<div id="comments" class="comments-area">
    <?php if (have_comments()): ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                _nx('%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'wnb-bloom'),
                number_format_i18n($comment_count)
            );
            ?>
        </h2>
        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size' => 48,
            ]);
            ?>
        </ol>
        <?php the_comments_navigation(); ?>
        <?php if (!comments_open()): ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'wnb-bloom'); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <?php comment_form(); ?>
</div>
