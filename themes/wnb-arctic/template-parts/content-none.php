<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'wnb-arctic'); ?></h1>
    </header>
    <div class="page-content">
        <?php if (is_search()): ?>
            <p><?php esc_html_e('Sorry, no results matched your search. Please try again with different keywords.', 'wnb-arctic'); ?></p>
            <?php get_search_form(); ?>
        <?php else: ?>
            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'wnb-arctic'); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>
