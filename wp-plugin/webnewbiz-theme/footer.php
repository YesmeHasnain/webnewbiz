    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="site-footer-container">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>
            <div class="site-info">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'webnewbiz-theme'); ?></p>
            </div>
        </div>
    </footer>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
