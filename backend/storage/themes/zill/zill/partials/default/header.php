<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<header id="site-header" class="site-header site-header--default" role="banner">
    <div class="container">
        <div class="site-branding">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            }
            else {
                ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'zill' ); ?>" rel="home">
                    <img src="<?php echo esc_url(zill_get_theme_mod('logo_default', get_theme_file_uri('/assets/images/logo.svg'))) ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="105" height="105"/>
                </a>
            <?php } ?>
        </div>

        <nav class="site-navigation" role="navigation">
            <?php
            if(has_nav_menu('main-nav')){
                echo '<button type="button" class="site-nav-toggleicon"><i class="lastudioicon-menu-8-1"></i></button>';
                wp_nav_menu( [
                    'theme_location' => 'main-nav',
                    'container'      => false,
                    'link_before'    => '<span>',
                    'link_after'     => '</span>',
                ] );
            }
            ?>
        </nav>
    </div>
</header>