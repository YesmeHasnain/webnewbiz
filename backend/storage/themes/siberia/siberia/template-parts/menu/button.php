<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
<div class="ms-fs-wrapper">
    <div class="container-menu">
        <div class="action-menu">
            <div class="open-event">
                <div class="text">
                    <span><?php esc_html_e('Menu', 'siberia'); ?></span>
                    <span><?php esc_html_e('Close', 'siberia'); ?></span>
                </div>
                <div class="burger">
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
            <div class="close-event"></div>
        </div>
    </div>
    <div class="ms-fs-menu">
        <div class="ms-fs-container">
            <?php if ( has_nav_menu( 'primary-menu' ) ) {  siberia_primary_menu(); } ?>
        </div>
        <?php if ( is_active_sidebar( 'socials' ) ) : ?>
            <div class="ms-fs-socials">
                <?php dynamic_sidebar( 'socials' ); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>