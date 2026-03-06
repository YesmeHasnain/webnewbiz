<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package flavor-oceanic
 */
?>
<div class="error-404-header-area">
  <?php get_header(); ?>
</div>

<div id="primary" class="error-404-content-area">
    <section class="error-404 not-found">
        <h1 class="page-title">404</h1>
        <p class="page-descr"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'flavor-oceanic' ); ?></p>
    </section><!-- .error-404 -->
</div><!-- #primary -->
