        </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="site-footer-container">
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2')): ?>
            <div class="footer-widgets">
                <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('footer-2')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="site-info">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer_menu',
                    'menu_id'        => 'footer-menu',
                    'fallback_cb'    => false,
                    'container'      => false,
                    'depth'          => 1,
                ]);
                ?>
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'wnb-vivid'); ?></p>
            </div>
        </div>
    </footer>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
