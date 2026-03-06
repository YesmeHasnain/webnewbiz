<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>
    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages(['before' => '<div class="page-links">', 'after' => '</div>']);
        ?>
    </div>
    <?php if (get_edit_post_link()): ?>
        <footer class="entry-footer">
            <?php edit_post_link(__('Edit', 'wnb-arctic'), '<span class="edit-link">', '</span>'); ?>
        </footer>
    <?php endif; ?>
</article>
