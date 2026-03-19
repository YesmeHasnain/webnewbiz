<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly

}

// Control core classes for avoid errors
if (class_exists('CSF')) {

    $allowed_html = glint_function('Functions')->kses_allowed_html(array(
        'mark',
    ));
    //
    // Set a unique slug-like ID
    $glintOptions = 'glint_theme_options';

    //
    // Create options
    CSF::createOptions($glintOptions, array(
        'menu_title'      => 'Glint Options',
        'menu_slug'       => 'glint-theme-option',
        'framework_title' => 'Glint Options 2.0',
    ));

    CSF::createSection($glintOptions, array(
        'title' => esc_html__('Global Option', 'glint'),
        'id'    => 'general_options',
        'icon'  => 'fa fa-home',
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'general_options',
        'title'  => esc_html__('Preloader', 'glint'),
        'icon'   => 'fa fa-spinner',
        'fields' => array(

            array(
                'id'       => 'enable_preloader',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Pre Loader', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable Site Pre loader.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'         => 'preloader_bg_color',
                'type'       => 'color',
                'title'      => 'Preloader Background Color',
                'default'    => '#000',
                'dependency' => array(
                    'enable_preloader',
                    '==',
                    'true',
                ),

            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'general_options',
        'title'  => esc_html__('ScrollUp Button', 'glint'),
        'icon'   => 'fa fa-arrow-circle-up',
        'fields' => array(

            array(
                'id'       => 'enable_back_top_btn',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Scroll Top Button', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable Scroll Top Button.', 'glint'),
                'default'  => true,
            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'general_options',
        'title'  => esc_html__('Custom CSS', 'glint'),
        'icon'   => 'fa fa-pencil',
        'fields' => array(

            array(
                'id'       => 'custom_css',
                'type'     => 'code_editor',
                'title'    => esc_html__('Custom CSS Code', 'glint'),
                'settings' => array(
                    'theme' => 'mbo',
                    'mode'  => 'css',
                ),
                'default'  => '.element{ color: #ffbc00; }',
            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'title' => esc_html__('Theme Color', 'glint'),
        'id'    => 'color_options',

        'icon'   => 'fa fa-paint-brush',
        'fields' => array(

            array(
                'id'      => 'theme_main_color',
                'type'    => 'color',
                'title'   => 'Select Theme Accent Color',
                'default' => '#08D665',
                'desc'    => esc_html__('Theme Primary Color.', 'glint'),

            ),

        ),
    ));

    // Create typography section
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Typography', 'glint'),
        'id'     => 'typography_options',
        'icon'   => 'fa fa-font',
        'fields' => array(

            array(
                'id'      => 'body_typography',
                'type'    => 'typography',
                'title'   => esc_html__('Body Typography', 'glint'),
                'output'  => 'body',
                'default' => array(
                    'font-family' => 'Rubik',
                    'font-size'   => '16',
                    'line-height' => '27',
                    'type'        => 'google',
                    'subset'      => 'latin-ext',
                    'unit'        => 'px',

                ),

                'extra_styles' => 'true',

                'subtitle' => esc_html__('Set body typography.', 'glint'),
            ),

            array(
                'id'      => 'heading_typography',
                'type'    => 'typography',
                'title'   => esc_html__('Heading Typography', 'glint'),
                'output'  => 'h1,h2,h3,h4,h5,h6',
                'default' => array(
                    'font-family'  => 'Oswald',
                    'type'         => 'google',

                ),
                'extra_styles' => 'true',

                'subtitle' => esc_html__('Set Heading Typography.', 'glint'),
            ),

            array(
                'id'      => 'post_title_typography',
                'type'    => 'typography',
                'title'   => esc_html__('Post Title Typography', 'glint'),
                'default' => array(
                    'font-family' => 'Oswald',
                    'font-size'   => '30',
                    'line-height' => '40',
                    'type'        => 'google',
                    'subset'      => 'latin-ext',
                    'unit'        => 'px',

                ),

                'extra_styles' => 'true',

                'subtitle' => esc_html__('These settings control the typography for Post Title.', 'glint'),
            ),

        ),
    ));

    // Create header section
    CSF::createSection($glintOptions, array(
        'title' => esc_html__('Header Option', 'glint'),
        'id'    => 'header_options',
        'icon'  => 'fa fa-header',
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'header_options',
        'title'  => esc_html__('Top Bar', 'glint'),
        'icon'   => 'fa fa-credit-card-alt',
        'fields' => array(

            array(
                'id'       => 'enable_header_top_bar',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Header Topbar', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable header search.', 'glint'),
                'default'  => false,
            ),

            array(
                'id'         => 'top_btn_text',
                'type'       => 'text',
                'title'      => 'Header Right Button text',
                'default'    => 'Contact Me',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

            ),

            array(
                'id'         => 'top_btn_link',
                'type'       => 'text',
                'title'      => 'Header Right Button URL',
                'default'    => '#',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

            ),
            array(
                'id'         => 'mobile_phone_text',
                'type'       => 'text',
                'title'      => 'Mobile Menu Phone',
                'default'    => '+8801712458614',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

            ),
            array(
                'id'         => 'mobile_location_url',
                'type'       => 'text',
                'title'      => 'Mobile Location URL',
                'default'    => 'https://www.google.com/maps',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

            ),

            array(
                'id'         => 'top_social',
                'type'       => 'group',
                'title'      => 'Top Social Links',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

                'fields' => array(

                    array(
                        'id'      => 'header_social_icon',
                        'type'    => 'icon',
                        'title'   => 'Social Icon',
                        'default' => 'fa fa-facebook',
                    ),

                    array(
                        'id'    => 'header_social_link',
                        'type'  => 'text',
                        'title' => 'Social Link',
                    ),

                ),
                'default' => array(
                    array(
                        'header_social_icon' => 'fa fa-facebook',
                        'header_social_link' => '#',
                    ),

                ),
            ),

            array(
                'id'         => 'enable_header_top_news_slider',
                'type'       => 'switcher',
                'title'      => esc_html__('Enable Header Top News Slider', 'glint'),
                'text_on'    => esc_html__('Yes', 'glint'),
                'text_off'   => esc_html__('No', 'glint'),
                'desc'       => esc_html__('Enable or disable header top News Slider.', 'glint'),
                'default'    => true,
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

            ),

            array(
                'id'         => 'top_banner_slider',
                'type'       => 'repeater',
                'title'      => 'Add Top Banner Slide Item',
                'dependency' => array(
                    'enable_header_top_bar',
                    '==',
                    'true',
                ),

                'fields' => array(

                    array(
                        'id'    => 'top_banner_item',
                        'type'  => 'text',
                        'title' => 'Add Top banner slide Title item',
                    ),

                ),
                'default' => array(
                    array(
                        'top_banner_item' => 'Fox News Anchor Bret Baier...',
                    ),

                ),
            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'header_options',
        'title'  => esc_html__('Header', 'glint'),
        'icon'   => 'fa fa-search-plus',
        'fields' => array(

            array(
                'id'       => 'enable_header_sticky',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Header Sticky', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable header Sticky.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'       => 'enable_header_search',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Header Search', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable header search.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'       => 'enable_header_right_nav',
                'type'     => 'switcher',
                'title'    => esc_html__('Enable Header Right Panel Menu', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable header user Menu.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'      => 'header_background',
                'type'    => 'color',
                'title'   => 'Header Background',
                'default' => 'transparent',
                'desc'    => esc_html__('Header background Color.', 'glint'),

            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'parent' => 'header_options',
        'title'  => esc_html__('Logo', 'glint'),
        'icon'   => 'fa fa-camera',
        'fields' => array(

            array(
                'id'      => 'theme_logo',
                'type'    => 'media',
                'title'   => esc_html__('Site Logo', 'glint'),
                'library' => 'image',
                'desc'    => wp_kses(__('Note: This logo will overwrite <mark>Customizer>Site Identity</mark> uploaded logo', 'glint'), $allowed_html),

            ),

            array(
                'id'      => 'logo_height',
                'type'    => 'text',
                'title'   => 'Logo Height',
                'default' => '50',
                'desc'    => esc_html__('Give Logo height in px. Default height is 50.', 'glint'),
            ),

        ),
    ));

    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Navigation', 'glint'),
        'id'     => 'nav_options',
        'icon'   => 'fa fa-list',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Navigation', 'glint') . '</h3>',
            ),

            array(
                'id'      => 'nav_typography',
                'type'    => 'typography',
                'title'   => esc_html__('Menu Typography', 'glint'),
                'output'  => '.stellarnav.light li a',
                'default' => array(
                    'font-family' => 'Rubik',
                    'font-size'   => '14',
                    'line-height' => '27',
                    'type'        => 'google',
                    'subset'      => 'latin-ext',
                    'unit'        => 'px',
                ),
                'extra_styles' => 'true',
                'subtitle' => esc_html__('Set menu typography.', 'glint'),
            ),

            array(
                'id'      => 'nav_spacing',
                'type'    => 'spacing',
                'title'   => 'Menu Item Spacing',
                'desc'    => esc_html__('You can define spacing Top, Right, Bottom, Left, or Units.', 'glint'),
                'default' => array(
                    'top'    => '30',
                    'right'  => '15',
                    'bottom' => '30',
                    'left'   => '15',
                    'unit'   => 'px',
                ),
            ),
        ),
    ));

    /*-------------------------------------------------------
     ** Pages and Template
    --------------------------------------------------------*/
    CSF::createSection($glintOptions, array(
        'id'    => 'pages_and_template',
        'title' => esc_html__('Pages & Template', 'glint'),
        'icon'  => 'fa fa-columns',
    ));

    // blog optoins
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Blog Page', 'glint'),
        'id'     => 'blog_page',
        'icon'   => 'fa fa-comment',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Blog Page Option', 'glint') . '</h3>',
            ),
            array(
                'id'      => 'glint_blog_layout',
                'type'    => 'image_select',
                'title'   => esc_html__('Select Page Layout', 'glint'),
                'options' => array(
                    'default'       => GLINT_CS_IMAGES . '/page/D.png',
                    'left-sidebar'  => GLINT_CS_IMAGES . '/page/L.png',
                    'right-sidebar' => GLINT_CS_IMAGES . '/page/R.png',
                ),
                'default' => 'right-sidebar',
            ),

            array(
                'id'       => 'enable_post_meta',
                'type'     => 'switcher',
                'title'    => esc_html__('Show Meta Info?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display post meta.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'       => 'enable_social_share',
                'type'     => 'switcher',
                'title'    => esc_html__('Social Share Menu?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display social share menu.', 'glint'),
                'default'  => false,
            ),

        ),
    ));

    // blog single optoins
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Blog Single Page', 'glint'),
        'id'     => 'single_page',
        'icon'   => 'fa fa-paperclip',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Blog Single Page Option', 'glint') . '</h3>',
            ),
            array(
                'id'      => 'glint_single_page_layout',
                'type'    => 'image_select',
                'title'   => esc_html__('Select Page Layout', 'glint'),
                'options' => array(
                    'default'       => GLINT_CS_IMAGES . '/page/D.png',
                    'left-sidebar'  => GLINT_CS_IMAGES . '/page/L.png',
                    'right-sidebar' => GLINT_CS_IMAGES . '/page/R.png',
                ),
                'default' => 'right-sidebar',
            ),

            array(
                'id'       => 'enable_post_meta_single',
                'type'     => 'switcher',
                'title'    => esc_html__('Show Meta Info?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display post meta.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'       => 'enable_post_tags_single',
                'type'     => 'switcher',
                'title'    => esc_html__('Releted Tags?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display related tags.', 'glint'),
                'default'  => true,
            ),

            array(
                'id'       => 'enable_social_share_single',
                'type'     => 'switcher',
                'title'    => esc_html__('Social Share Menu?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display social share menu.', 'glint'),
                'default'  => false,
            ),

            array(
                'id'       => 'enable_post_nav_single',
                'type'     => 'switcher',
                'title'    => esc_html__('Next Post Navigation?', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Turn on to display post navigation.', 'glint'),
                'default'  => true,
            ),

        ),
    ));

    // archive page optoins
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Archive  Page', 'glint'),
        'id'     => 'archive_page',
        'icon'   => 'fa fa-archive',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Archive Page Option', 'glint') . '</h3>',
            ),
            array(
                'id'      => 'glint_archive_layout',
                'type'    => 'image_select',
                'title'   => esc_html__('Select Page Layout', 'glint'),
                'options' => array(
                    'default'       => GLINT_CS_IMAGES . '/page/D.png',
                    'left-sidebar'  => GLINT_CS_IMAGES . '/page/L.png',
                    'right-sidebar' => GLINT_CS_IMAGES . '/page/R.png',
                ),
                'default' => 'right-sidebar',
            ),

        ),
    ));

    // search page optoins
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Search  Page', 'glint'),
        'id'     => 'search_page',
        'icon'   => 'fa fa-search-plus',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Search Page Option', 'glint') . '</h3>',
            ),
            array(
                'id'      => 'glint_search_layout',
                'type'    => 'image_select',
                'title'   => esc_html__('Select Page Layout', 'glint'),
                'options' => array(
                    'default'       => GLINT_CS_IMAGES . '/page/D.png',
                    'left-sidebar'  => GLINT_CS_IMAGES . '/page/L.png',
                    'right-sidebar' => GLINT_CS_IMAGES . '/page/R.png',
                ),
                'default' => 'right-sidebar',
            ),

        ),
    ));

    // blog single optoins
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Page Spacing', 'glint'),
        'id'     => 'general_page',
        'icon'   => 'fa fa-ellipsis-h',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('General Page Option', 'glint') . '</h3>',
            ),
            array(
                'id'      => 'page_spacing_enable',
                'title'   => esc_html__('Page Spacing Disable', 'glint'),
                'type'    => 'switcher',
                'default' => false,
            ),
        ),
    ));

    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Error  Page', 'glint'),
        'id'     => 'error_page',
        'icon'   => 'fa fa-exclamation',
        'parent' => 'pages_and_template',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => '<h3>' . esc_html__('Search Page Option', 'glint') . '</h3>',
            ),

            array(
                'id'               => 'error_bg',
                'title'            => esc_html__('404 Image', 'glint'),
                'type'             => 'background',
                'desc'             => wp_kses(__('You can set <mark>background</mark> for error page', 'glint'), $allowed_html),
                'default'          => array(
                    'background-size'     => 'background-size',
                    'background-position' => 'center bottom',
                    'background-repeat'   => 'no-repeat',
                ),
                'background_color' => false,
            ),

            array(
                'id'         => 'error_main_title',
                'title'      => esc_html__('Error Page Heading', 'glint'),
                'type'       => 'text',
                'info'       => wp_kses(__('you can change <mark>title</mark> of 404 page', 'glint'), $allowed_html),
                'attributes' => array(
                    'placeholder' => esc_html__('404! Not Found...', 'glint'),
                ),
            ),

            array(
                'id'         => 'error_text',
                'title'      => esc_html__('Error Page Title', 'glint'),
                'type'       => 'text',
                'info'       => wp_kses(__('you can change <mark>title</mark> of 404 page', 'glint'), $allowed_html),
                'attributes' => array(
                    'placeholder' => esc_html__('Oops! That page can not be found.', 'glint'),
                ),
            ),

            array(
                'id'         => 'button_text',
                'title'      => esc_html__('Go Back Button Text', 'glint'),
                'type'       => 'text',
                'info'       => wp_kses(__('you can change <mark>button text</mark> of 404 page', 'glint'), $allowed_html),
                'attributes' => array(
                    'placeholder' => esc_html__('Go Back To Home', 'glint'),
                ),
            ),

        ),
    ));

    // Create Footer section
    CSF::createSection($glintOptions, array(
        'title'  => esc_html__('Footer Options', 'glint'),
        'id'     => 'footer_options',
        'icon'   => 'fa fa-copyright',
        'fields' => array(

            array(
                'id'            => 'copyright_text',
                'type'          => 'wp_editor',
                'title'         => esc_html__('Copyright Text', 'glint'),
                'desc'          => esc_html__('Please type your copyright text. You can use all HTML tags.', 'glint'),
                'tinymce'       => true,
                'quicktags'     => true,
                'media_buttons' => false,
                'height'        => '100px',
                'default'       => wp_kses(__('&copy; Glint 2025. All Rights Reserved | Powered by: <a target="_blank" href="#">QuomodoTheme</a>', 'glint'), array(
                    'a'      => array(
                        'href'   => array(),
                        'target' => array(),
                    ),
                    'br'     => array(),
                    'em'     => array(),
                    'strong' => array(),
                    'small'  => array(),
                )),
            ),

            array(
                'id'       => 'show_brands',
                'type'     => 'switcher',
                'title'    => esc_html__('Show Footer Brands', 'glint'),
                'text_on'  => esc_html__('Yes', 'glint'),
                'text_off' => esc_html__('No', 'glint'),
                'desc'     => esc_html__('Enable or disable brands section.', 'glint'),
                'default'  => false,
            ),

            array(
                'id'         => 'brands_gallery',
                'type'       => 'repeater',
                'title'      => 'Repeater',
                'dependency' => array(
                    'show_brands',
                    '==',
                    'true',
                ),
                'fields'     => array(
                    array(
                        'id'    => 'brand_thumb',
                        'type'  => 'media',
                        'title' => 'Upload Brand Image',
                    ),

                ),
            ),

        ),
    ));
}
