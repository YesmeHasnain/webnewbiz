<?php
/*
 * Theme Metabox Options
 * @package Glint
 * @since 1.0.0
 * */

if (!defined('ABSPATH')) {
    exit(); // exit if access directly

}

if (class_exists('CSF')) {

    $allowed_html = glint_function('Functions')->kses_allowed_html(array(
        'mark',
    ));

    $prefix = 'glint';

    /*-------------------------------------
    Page Options
    -------------------------------------*/

    $glint_group_meta = 'glint_page_meta';

    CSF::createMetabox($glint_group_meta, array(
        'title'     => 'Page Options',
        'post_type' => 'page',
    ));

    // breadcrumb
    CSF::createSection($glint_group_meta, array(
        'title'  => 'Breadcrumb',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => esc_html__('Breadcrumb', 'glint'),
            ),

            array(
                'id'      => 'enable_title',
                'type'    => 'switcher',
                'title'   => esc_html__('Enable?', 'glint'),
                'desc'    => esc_html__('If you want to display page title bar, select on.', 'glint'),
                'default' => true,
            ),

            array(
                'id'         => 'custom_title',
                'type'       => 'text',
                'title'      => esc_html__('Custom Page Title', 'glint'),
                'desc'       => esc_html__('Your Cuatom Page Title.', 'glint'),
                'dependency' => array(
                    'enable_title',
                    '==',
                    'true',
                ),

            ),

            array(
                'id'         => 'title_bg',
                'type'       => 'upload',
                'title'      => esc_html__('Page Title Background Image', 'glint'),
                'dependency' => array(
                    'enable_title',
                    '==',
                    'true',
                ),
            ),

            array(
                'id'               => 'breadcrumb_bg',
                'title'            => esc_html__('Breadcrumb Image', 'glint'),
                'type'             => 'media',
                'desc'             => wp_kses(__('you can set <mark>background</mark> for breadcrumb', 'glint'), $allowed_html),
                'background_color' => false,
                'dependency'       => array(
                    'enable_title',
                    '==',
                    'true',
                ),
            ),

        ),

    ));

    CSF::createSection($glint_group_meta, array(
        'title'  => 'Enable Dark Mode',
        'fields' => array(
            array(
                'type'    => 'subheading',
                'content' => esc_html__('Enable Dark Mode', 'glint'),
            ),

            array(
                'id'      => 'enable_dark',
                'type'    => 'switcher',
                'title'   => esc_html__('Enable Dark mode in page?', 'glint'),
                'desc'    => esc_html__('If you want to display page dark version, select on.', 'glint'),
                'default' => false,
            ),

        ),

    ));
} //endif
