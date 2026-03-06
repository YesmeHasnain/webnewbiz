<?php
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="error-404 not-found">
            <div class="error-404-content">
                <h1 class="error-code">404</h1>
                <h2 class="page-title"><?php esc_html_e('Page Not Found', 'wnb-slate'); ?></h2>
                <p class="error-description"><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'wnb-slate'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="error-home-link"><?php esc_html_e('Back to Homepage', 'wnb-slate'); ?></a>
            </div>
        </section>
    </main>
</div>
<?php
get_footer();
