<?php
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;
        if ( 'post' === get_post_type() ) : ?>
        <div class="entry-meta">
            <?php pulse_theme_posted_on(); ?>
        </div>
        <?php endif; ?>
    </header>
    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content();
        else :
            the_excerpt();
        endif;
        wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div>' ) );
        ?>
    </div>
    <footer class="entry-footer">
        <?php pulse_theme_entry_footer(); ?>
    </footer>
</article>
