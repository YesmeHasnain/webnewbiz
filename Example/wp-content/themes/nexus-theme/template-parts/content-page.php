<?php
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header>
    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div>' ) );
        ?>
    </div>
    <footer class="entry-footer">
        <?php edit_post_link( sprintf( wp_kses( __( 'Edit <span class="screen-reader-text">%s</span>', 'nexus-theme' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ), '<span class="edit-link">', '</span>' ); ?>
    </footer>
</article>
