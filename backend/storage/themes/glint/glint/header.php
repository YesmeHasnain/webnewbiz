<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php

    $glint_logo             = cs_get_option('theme_logo');
    $glint_logo_id          = isset($glint_logo['id']) && !empty($glint_logo['id']) ? $glint_logo['id'] : '';
    $glint_logo_url         = isset($glint_logo['url']) ? $glint_logo['url'] : '';
    $glint_logo_alt         = get_post_meta($glint_logo_id, '_wp_attachment_image_alt', true);

    $preloader              = cs_get_option('enable_preloader', true);
    $scroll_to_top          = cs_get_option('enable_back_top_btn', true);
    $show_top_bar           = cs_get_option('enable_header_top_bar', false);
    $social_links           = cs_get_option('top_social');
    $top_banner_slider      = cs_get_option('top_banner_slider');

    $enable_header_search    = cs_get_option('enable_header_search');
    $enable_header_right_nav = cs_get_option('enable_header_right_nav');

    $top_btn_text           = '';
    $top_btn_link           = '';

    if (function_exists('cs_get_option')) :
        $top_btn_text = cs_get_option('top_btn_text');
        $top_btn_link = cs_get_option('top_btn_link');
    endif;

    wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <div id="page" class="site">
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'glint'); ?></a>

        <?php if ($preloader == true) : ?>
            <!--PLACEHOLDER AREA START-->
            <div class="preloader">
                <div class="lds-dual-ring"></div>
            </div>
            <!--PLACEHOLDER AREA END-->
        <?php endif; ?>

        <?php if ($show_top_bar == true) : ?>
            <div class="header-banner-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 align-self-center">
                            <div class="banner-comments">
                                <div class="banner-icon">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <div class="banner-carousel owl-carousel">

                                    <?php if (!empty($top_banner_slider)) : ?>
                                        <?php foreach ($top_banner_slider as $top_slide) : ?>

                                            <div class="banner-single-carousel">
                                                <p><?php echo esc_html($top_slide['top_banner_item']); ?></p>
                                            </div>

                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 text-right align-self-center">
                            <div class="banner-social-wrap">
                                <div class="banner-social">
                                    <ul>

                                        <?php if (!empty($social_links)) : ?>
                                            <?php foreach ($social_links as $link) : ?>

                                                <li><a href="<?php echo esc_url($link['header_social_link']); ?>"><i class="<?php echo esc_attr($link['header_social_icon']); ?>"></i></a></li>

                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                    </ul>
                                </div>
                                <div class="banner-contact">
                                    <a href="<?php echo esc_url($top_btn_link); ?>" class="contact-btn"><i class="fa fa-angle-double-right"></i> <?php echo esc_html($top_btn_text); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--::::: BANNER AREA END :::::::-->
        <?php endif; ?>

        <?php if ($enable_header_search == true) : ?>
            <div class="expanding__search__bar">
                <div class="expanding__search__close"><i class="fa fa-close"></i></div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <div class="expanding__search__content">
                                <h3><?php echo esc_html__('Search Here', 'glint'); ?></h3>
                                <form action="<?php echo esc_url(home_url('/')); ?>">
                                    <input type="search" name="s" placeholder="<?php echo esc_attr('Search...', 'glint'); ?>">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!--::::: HEADER AREA START :::::::-->
        <div class="header-area<?php if ($show_top_bar == true) : ?> header-main<?php endif; ?>" id="header">

            <?php if ($scroll_to_top == true) : ?>
                <a href="#" class="up-btn"><i class="fa fa-angle-up"></i></a>
            <?php endif; ?>

            <div class="container">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6 col-xs-6 col-sm-6 align-self-center">

                        <?php if (has_custom_logo() || !empty($glint_logo_url)) {

                            if (isset($glint_logo['url']) && !empty($glint_logo_url)) { ?>
                                <a href="<?php echo esc_url(site_url('/')) ?>" class="logo">
                                    <img src="<?php echo esc_url($glint_logo_url); ?>" alt="<?php echo esc_attr($glint_logo_alt) ?>">
                                </a>
                            <?php
                            } else {
                                the_custom_logo();
                            }
                        } else { ?>
                            <div class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a></div>
                        <?php } ?>
                    </div>

                    <div class="<?php if ($enable_header_search == false) : ?>col-6 col-lg-9 col-md-6 col-xs-6 col-sm-6<?php else : ?> col-6 col-lg-9 col-xs-6 col-sm-6 col-xl-8 <?php endif; ?> text-center align-self-center">
                        <div class="main-menu">
                            <div class="stellarnav">
                                <?php
                                wp_nav_menu([
                                    'menu'            => 'main-menu',
                                    'theme_location'  => 'mainmenu',
                                    'container'       => '',
                                    'container_class' => '',
                                    'menu_class'      => 'navbarmneuclass',
                                    'menu_id'         => 'scroll',
                                    'walker'          => new Glint_Nav_Menu_Walker(),
                                    'fallback_cb'     => 'Glint_Nav_Menu_Walker::fallback'
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-none d-xl-block col-xl-1 align-self-center text-right">
                        <div class="search-area">

                            <?php if ($enable_header_search == true) : ?>
                                <div class="search-box">
                                    <button class="search__bur__open"><i class="fa fa-search"></i></button>
                                </div>
                            <?php endif; ?>

                            <?php if ($enable_header_right_nav == true) : ?>
                                <div class="grid-menu" id="grid-side">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/icon/hamburger.svg" alt="<?php the_title_attribute(); ?>">
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-widgets-wrap" id="slide-widgets">
                <div class="side-widgets" id="side-content">
                    <div class="side-close" id="close-btn">
                        <i class="fa fa-times"></i>
                    </div>

                    <?php if (is_active_sidebar('header-widget-nav')) : ?>
                        <div class="header-top-widget">
                            <?php dynamic_sidebar('header-widget-nav'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!--::::: HEADER AREA END :::::::-->

        <div id="content" class="site-content">