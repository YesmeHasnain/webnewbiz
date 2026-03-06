<?php
get_header();
?>
<section id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', 'search' );
            endwhile;
            the_posts_navigation();
        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
        ?>
    </main>
</section>
<?php
get_footer();
