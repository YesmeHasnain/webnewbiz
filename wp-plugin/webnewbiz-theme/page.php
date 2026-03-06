<?php
/**
 * The template for displaying all pages.
 * Elementor-aware: if the page is built with Elementor, we just call the_content().
 */

get_header();

$is_elementor = false;
if (defined('ELEMENTOR_VERSION')) {
    $document = \Elementor\Plugin::instance()->documents->get(get_the_ID());
    if ($document && !is_bool($document) && $document->is_built_with_elementor()) {
        $is_elementor = true;
    }
}

if ($is_elementor) :
    // Elementor handles everything — full-width, no wrapper
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
else :
    // Standard WordPress page layout
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
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </main>
    </div>
    <?php
    get_sidebar();
endif;

get_footer();
