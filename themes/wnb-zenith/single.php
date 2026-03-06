<?php
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()):
            the_post();
            get_template_part('template-parts/content', get_post_type());
            the_post_navigation([
                'prev_text' => '<span class="nav-subtitle">&laquo;</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-title">%title</span> <span class="nav-subtitle">&raquo;</span>',
            ]);
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
        endwhile;
        ?>
    </main>
</div>
<?php
get_footer();
