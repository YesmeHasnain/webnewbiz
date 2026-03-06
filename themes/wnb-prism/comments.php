<?php if (post_password_required()) return; ?>
<div id="comments" class="comments-area">
<?php if (have_comments()): ?><h2 class="comments-title"><?php printf(_n('%s Comment', '%s Comments', get_comments_number()), number_format_i18n(get_comments_number())); ?></h2>
<ol class="comment-list"><?php wp_list_comments(['style' => 'ol', 'short_ping' => true, 'avatar_size' => 48]); ?></ol>
<?php the_comments_navigation(); endif; comment_form(); ?>
</div>