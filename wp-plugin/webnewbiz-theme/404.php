<?php
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Page Not Found', 'webnewbiz-theme'); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('The page you are looking for could not be found. Try searching or go back to the homepage.', 'webnewbiz-theme'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    </main>
</div>
<?php
get_footer();
