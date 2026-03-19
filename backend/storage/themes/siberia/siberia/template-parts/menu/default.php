<button class="main-header__nav-trigger js-main-header__nav-trigger" aria-label="Toggle menu" aria-expanded="false" aria-controls="main-header-nav">
<i class="main-header__nav-trigger-icon" aria-hidden="true"></i>
<span><?php esc_html_e('Menu', 'siberia'); ?></span>
</button>
<nav class="main-header__nav js-main-header__nav main-header__default" id="main-header-nav" aria-labelledby="primary-menu">
    <?php if ( has_nav_menu( 'primary-menu' ) ) {  siberia_primary_menu(); } ?>
</nav>