<?php
/**
 * The main template file — blog/archive listing.
 * Elementor-aware for pages that use Elementor.
 */

get_header();

$is_elementor = false;
if (defined('ELEMENTOR_VERSION') && is_singular()) {
    $document = \Elementor\Plugin::instance()->documents->get(get_the_ID());
    if ($document && !is_bool($document) && $document->is_built_with_elementor()) {
        $is_elementor = true;
    }
}

if ($is_elementor) :
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
else :
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php if (have_posts()) : ?>
                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', get_post_type());
                endwhile;

                the_posts_navigation();
            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </main>
    </div>
    <?php
    get_sidebar();
endif;

get_footer();
