<?php
/**
 * Template part for displaying a message when no posts are found.
 */
?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'webnewbiz-theme'); ?></h1>
    </header>
    <div class="page-content">
        <?php if (is_search()) : ?>
            <p><?php esc_html_e('Sorry, no results matched your search. Please try again with different keywords.', 'webnewbiz-theme'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'webnewbiz-theme'); ?></p>
        <?php endif; ?>
    </div>
</section>
