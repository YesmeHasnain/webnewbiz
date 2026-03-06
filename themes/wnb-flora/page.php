<?php
get_header();
$is_elementor = false;
if (defined('ELEMENTOR_VERSION')) {
    $doc = \Elementor\Plugin::instance()->documents->get(get_the_ID());
    if ($doc && !is_bool($doc) && $doc->is_built_with_elementor()) $is_elementor = true;
}
if ($is_elementor) { while (have_posts()): the_post(); the_content(); endwhile; } else { ?>
<div id="primary" class="content-area"><main id="main" class="site-main">
<?php while (have_posts()): the_post(); get_template_part('template-parts/content', 'page'); if (comments_open() || get_comments_number()) comments_template(); endwhile; ?>
</main></div><?php } get_footer();