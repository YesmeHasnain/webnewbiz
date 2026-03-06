<?php
?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'momentum-theme' ); ?></h1>
    </header>
    <div class="page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :
            printf( '<p>' . wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'momentum-theme' ), array( 'a' => array( 'href' => array() ) ) ) . '</p>', esc_url( admin_url( 'post-new.php' ) ) );
        elseif ( is_search() ) :
            ?>
            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'momentum-theme' ); ?></p>
            <?php get_search_form();
        else : ?>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'momentum-theme' ); ?></p>
            <?php get_search_form();
        endif; ?>
    </div>
</section>
