<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
        <?php if ('post' === get_post_type()): ?>
            <div class="entry-meta">
                <span class="posted-on"><?php echo get_the_date(); ?></span>
            </div>
        <?php endif; ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>
</article>
