<?php
get_header();

$is_built_with_elementor = FALSE;

if ( defined('ELEMENTOR_VERSION') ) {
    $el_page = Elementor\Plugin::instance()->documents->get( get_the_ID() );
    if ( !is_bool($el_page) && $el_page->is_built_with_elementor() ) {
        $is_built_with_elementor = TRUE;
    }
}

if ( !$is_built_with_elementor ) {
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', 'page');
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
            endwhile;
            ?>
        </main>
    </div>
    <?php
} else {
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
}

get_footer();
