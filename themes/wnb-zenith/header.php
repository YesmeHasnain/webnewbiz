<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'wnb-zenith'); ?></a>

    <header id="masthead" class="site-header">
        <div class="site-header-container">
            <div class="site-branding">
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-link">
                        <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                    </a>
                <?php endif; ?>
                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()): ?>
                    <p class="site-description"><?php echo $description; ?></p>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu([
                    'theme_location' => 'header_menu',
                    'menu_id'        => 'primary-menu',
                    'fallback_cb'    => false,
                    'container'      => false,
                ]);
                ?>
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-bar"></span>
                    <span class="menu-bar"></span>
                    <span class="menu-bar"></span>
                </button>
            </nav>
        </div>
    </header>

    <div id="content" class="site-content">
