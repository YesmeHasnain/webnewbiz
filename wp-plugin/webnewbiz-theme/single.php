<?php
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <span class="byline"> &mdash; <?php the_author(); ?></span>
                    </div>
                </header>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail"><?php the_post_thumbnail('large'); ?></div>
                <?php endif; ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                <footer class="entry-footer">
                    <?php
                    $categories = get_the_category_list(', ');
                    if ($categories) echo '<span class="cat-links">' . $categories . '</span>';
                    $tags = get_the_tag_list('', ', ');
                    if ($tags) echo '<span class="tag-links"> | ' . $tags . '</span>';
                    ?>
                </footer>
            </article>
            <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            the_post_navigation([
                'prev_text' => '&larr; %title',
                'next_text' => '%title &rarr;',
            ]);
        endwhile;
        ?>
    </main>
</div>
<?php
get_sidebar();
get_footer();
