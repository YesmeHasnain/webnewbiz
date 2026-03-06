<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()):
            the_title('<h1 class="entry-title">', '</h1>');
        else:
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()): ?>
            <div class="entry-meta">
                <span class="posted-on"><?php echo get_the_date(); ?></span>
                <span class="byline"><?php the_author(); ?></span>
            </div>
        <?php endif; ?>
    </header>

    <?php if (has_post_thumbnail()): ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if (is_singular()):
            the_content();
            wp_link_pages(['before' => '<div class="page-links">', 'after' => '</div>']);
        else:
            the_excerpt();
        endif;
        ?>
    </div>

    <footer class="entry-footer">
        <?php
        if ('post' === get_post_type()) {
            $categories = get_the_category_list(', ');
            if ($categories) {
                printf('<span class="cat-links">%s</span>', $categories);
            }
        }
        edit_post_link(__('Edit', 'wnb-bloom'), '<span class="edit-link">', '</span>');
        ?>
    </footer>
</article>
