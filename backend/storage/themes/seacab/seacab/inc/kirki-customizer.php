<?php
/**
 * seacab customizer
 *
 * @package seacab
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Added Panels & Sections
 */
function seacab_customizer_panels_sections( $wp_customize ) {

    //Add panel
    $wp_customize->add_panel( 'seacab_customizer', [
        'priority' => 10,
        'title'    => esc_html__( 'Seacab Customizer', 'seacab' ),
    ] );

    /**
     * Customizer Section
     */
    $wp_customize->add_section( 'header_top_setting', [
        'title'       => esc_html__( 'Header Info Setting', 'seacab' ),
        'description' => '',
        'priority'    => 10,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'header_social', [
        'title'       => esc_html__( 'Header Social', 'seacab' ),
        'description' => '',
        'priority'    => 11,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'section_header_logo', [
        'title'       => esc_html__( 'Header Setting', 'seacab' ),
        'description' => '',
        'priority'    => 12,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'blog_setting', [
        'title'       => esc_html__( 'Blog Setting', 'seacab' ),
        'description' => '',
        'priority'    => 13,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'header_side_setting', [
        'title'       => esc_html__( 'Side Info', 'seacab' ),
        'description' => '',
        'priority'    => 14,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'breadcrumb_setting', [
        'title'       => esc_html__( 'Breadcrumb Setting', 'seacab' ),
        'description' => '',
        'priority'    => 15,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'blog_setting', [
        'title'       => esc_html__( 'Blog Setting', 'seacab' ),
        'description' => '',
        'priority'    => 16,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'footer_setting', [
        'title'       => esc_html__( 'Footer Settings', 'seacab' ),
        'description' => '',
        'priority'    => 16,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'color_setting', [
        'title'       => esc_html__( 'Color Setting', 'seacab' ),
        'description' => '',
        'priority'    => 17,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( '404_page', [
        'title'       => esc_html__( '404 Page', 'seacab' ),
        'description' => '',
        'priority'    => 18,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'tutor_course_settings', [
        'title'       => esc_html__( 'Tutor Course Settings ', 'seacab' ),
        'description' => '',
        'priority'    => 19,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'event_settings', [
        'title'       => esc_html__( 'Event Settings ', 'seacab' ),
        'description' => '',
        'priority'    => 19,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'learndash_course_settings', [
        'title'       => esc_html__( 'Learndash Course Settings ', 'seacab' ),
        'description' => '',
        'priority'    => 20,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'typo_setting', [
        'title'       => esc_html__( 'Typography Setting', 'seacab' ),
        'description' => '',
        'priority'    => 21,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );

    $wp_customize->add_section( 'tutor_course_settings', [
        'title'       => esc_html__( 'Tutor Course Settings ', 'seacab' ),
        'description' => '',
        'priority'    => 23,
        'capability'  => 'edit_theme_options',
        'panel'       => 'seacab_customizer',
    ] );
}

add_action( 'customize_register', 'seacab_customizer_panels_sections' );

function _header_top_fields( $fields ) {
    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_topbar_switch',
        'label'    => esc_html__( 'Topbar Swicher', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_preloader',
        'label'    => esc_html__( 'Preloader On/Off', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_backtotop',
        'label'    => esc_html__( 'Back To Top On/Off', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_header_right',
        'label'    => esc_html__( 'Header Right On/Off', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];    

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_sticky_hide',
        'label'    => esc_html__( 'Header Sticky On/Off', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_header_lang',
        'label'    => esc_html__( 'language On/Off', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    // contact button
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_button_text',
        'label'    => esc_html__( 'Button Text', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( 'Contact Us', 'seacab' ),
        'priority' => 10,
        'active_callback' => [
            [
                'setting'  => 'seacab_header_right',
                'operator' => '==',
                'value'    => true,
            ],
        ],
    ];

    $fields[] = [
        'type'     => 'link',
        'settings' => 'seacab_button_link',
        'label'    => esc_html__( 'Button URL', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
        'active_callback' => [
            [
                'setting'  => 'seacab_header_right',
                'operator' => '==',
                'value'    => true,
            ],
        ],
    ];

    // phone button
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_phone_button_text',
        'label'    => esc_html__( 'Phone Button Text', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( 'Contact Us', 'seacab' ),
        'priority' => 10,
        'active_callback' => [
            [
                'setting'  => 'seacab_header_right',
                'operator' => '==',
                'value'    => true,
            ],
        ],
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_phone_button_link',
        'label'    => esc_html__( 'Phone Button URL', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
        'active_callback' => [
            [
                'setting'  => 'seacab_header_right',
                'operator' => '==',
                'value'    => true,
            ],
        ],
    ];


    // phone
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_phone_num',
        'label'    => esc_html__( 'Phone Number', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( '+5204654544', 'seacab' ),
        'priority' => 10,
    ];    

    // email
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_mail_id',
        'label'    => esc_html__( 'Mail ID', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( 'demo@example.com', 'seacab' ),
        'priority' => 10,
    ];    

    // email
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_address',
        'label'    => esc_html__( 'Address', 'seacab' ),
        'section'  => 'header_top_setting',
        'default'  => esc_html__( '24/21, 2nd Rangpur, Sapla', 'seacab' ),
        'priority' => 10,
    ];

    return $fields;

}
add_filter( 'kirki/fields', '_header_top_fields' );

/*
Header Social
 */
function _header_social_fields( $fields ) {
    // header section social
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_topbar_fb_url',
        'label'    => esc_html__( 'Facebook Url', 'seacab' ),
        'section'  => 'header_social',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_topbar_twitter_url',
        'label'    => esc_html__( 'Twitter Url', 'seacab' ),
        'section'  => 'header_social',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_topbar_youtube_url',
        'label'    => esc_html__( 'Youtube Url', 'seacab' ),
        'section'  => 'header_social',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_topbar_pinterest_url',
        'label'    => esc_html__( 'Pinterest Url', 'seacab' ),
        'section'  => 'header_social',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_topbar_linkedin_url',
        'label'    => esc_html__( 'Linkedin Url', 'seacab' ),
        'section'  => 'header_social',
        'default'  => esc_html__( '#', 'seacab' ),
        'priority' => 10,
    ];


    return $fields;
}
add_filter( 'kirki/fields', '_header_social_fields' );

/*
Header Settings
 */
function _header_header_fields( $fields ) {
    $fields[] = [
        'type'        => 'radio-image',
        'settings'    => 'choose_default_header',
        'label'       => esc_html__( 'Select Header Style', 'seacab' ),
        'section'     => 'section_header_logo',
        'placeholder' => esc_html__( 'Select an option...', 'seacab' ),
        'priority'    => 10,
        'multiple'    => 1,
        'choices'     => [
            'header-style-1'   => get_template_directory_uri() . '/inc/img/header/header-1.png',
            'header-style-2'   => get_template_directory_uri() . '/inc/img/header/header-2.png',
        ],
        'default'     => 'header-style-1',
    ];

    $fields[] = [
        'type'        => 'image',
        'settings'    => 'logo',
        'label'       => esc_html__( 'Header Logo', 'seacab' ),
        'description' => esc_html__( 'Upload Your Logo.', 'seacab' ),
        'section'     => 'section_header_logo',
        'default'     => get_template_directory_uri() . '/assets/images/resources/logo-1.png',
    ];

    $fields[] = [
        'type'        => 'image',
        'settings'    => 'seconday_logo',
        'label'       => esc_html__( 'Header Secondary Logo', 'seacab' ),
        'description' => esc_html__( 'Header Logo Black', 'seacab' ),
        'section'     => 'section_header_logo',
        'default'     => get_template_directory_uri() . '/assets/images/resources/footer-logo.png',
    ];

    $fields[] = [
        'type'        => 'image',
        'settings'    => 'preloader_logo',
        'label'       => esc_html__( 'Preloader Logo', 'seacab' ),
        'description' => esc_html__( 'Upload Preloader Logo.', 'seacab' ),
        'section'     => 'section_header_logo',
        'default'     => get_template_directory_uri() . '/assets/images/loader.png',
    ];

    return $fields;
}
add_filter( 'kirki/fields', '_header_header_fields' );

/*
Header Side Info
 */
function _header_side_fields( $fields ) {
    // side info settings
    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_side_hide',
        'label'    => esc_html__( 'Side Info On/Off', 'seacab' ),
        'section'  => 'header_side_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];  
    $fields[] = [
        'type'        => 'image',
        'settings'    => 'seacab_side_logo',
        'label'       => esc_html__( 'Logo Side', 'seacab' ),
        'description' => esc_html__( 'Logo Side', 'seacab' ),
        'section'     => 'header_side_setting',
        'default'     => get_template_directory_uri() . '/assets/images/resources/footer-logo.png',
    ];
    $fields[] = [
        'type'     => 'textarea',
        'settings' => 'seacab_extra_address',
        'label'    => esc_html__( 'Office Address', 'seacab' ),
        'section'  => 'header_side_setting',
        'default'  => esc_html__( '12/A, Mirnada City Tower, NYC', 'seacab' ),
        'priority' => 10,
    ];
    $fields[] = [
        'type'     => 'textarea',
        'settings' => 'seacab_extra_phone',
        'label'    => esc_html__( 'Phone Number', 'seacab' ),
        'section'  => 'header_side_setting',
        'default'  => esc_html__( '+666 888 0000', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'textarea',
        'settings' => 'seacab_extra_email',
        'label'    => esc_html__( 'Email ID', 'seacab' ),
        'section'  => 'header_side_setting',
        'default'  => esc_html__( 'demo@example.com', 'seacab' ),
        'priority' => 10,
    ];
    return $fields;
}
add_filter( 'kirki/fields', '_header_side_fields' );

/*
_header_page_title_fields
 */
function _header_page_title_fields( $fields ) {
    // Breadcrumb Setting
    $fields[] = [
        'type'        => 'image',
        'settings'    => 'breadcrumb_bg_img',
        'label'       => esc_html__( 'Breadcrumb Background Image', 'seacab' ),
        'description' => esc_html__( 'Breadcrumb Background Image', 'seacab' ),
        'section'     => 'breadcrumb_setting',
        'default'     => get_template_directory_uri() . '/assets/img/page-title/page-title.jpg',
    ];
    $fields[] = [
        'type'        => 'color',
        'settings'    => 'seacab_breadcrumb_bg_color',
        'label'       => __( 'Breadcrumb BG Color', 'seacab' ),
        'description' => esc_html__( 'This is a Breadcrumb bg color control.', 'seacab' ),
        'section'     => 'breadcrumb_setting',
        'default'     => '#222',
        'priority'    => 10,
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'breadcrumb_info_switch',
        'label'    => esc_html__( 'Breadcrumb Info switch', 'seacab' ),
        'section'  => 'breadcrumb_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'breadcrumb_switch',
        'label'    => esc_html__( 'Breadcrumb Hide', 'seacab' ),
        'section'  => 'breadcrumb_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    return $fields;
}
add_filter( 'kirki/fields', '_header_page_title_fields' );

/*
Header Social
 */
function _header_blog_fields( $fields ) {
// Blog Setting
    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_btn_switch',
        'label'    => esc_html__( 'Blog BTN On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_page_sidebar_hide',
        'label'    => esc_html__( 'Blog Page Sidebar On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_cat',
        'label'    => esc_html__( 'Blog Category Meta On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_author',
        'label'    => esc_html__( 'Blog Author Meta On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];
    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_date',
        'label'    => esc_html__( 'Blog Date Meta On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];
    $fields[] = [
        'type'     => 'switch',
        'settings' => 'seacab_blog_comments',
        'label'    => esc_html__( 'Blog Comments Meta On/Off', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => '1',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_blog_btn',
        'label'    => esc_html__( 'Blog Button text', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => esc_html__( 'Read More', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'breadcrumb_blog_title',
        'label'    => esc_html__( 'Blog Title', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => esc_html__( 'Blog', 'seacab' ),
        'priority' => 10,
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'breadcrumb_blog_title_details',
        'label'    => esc_html__( 'Blog Details Title', 'seacab' ),
        'section'  => 'blog_setting',
        'default'  => esc_html__( 'Blog Details', 'seacab' ),
        'priority' => 10,
    ];
    return $fields;
}
add_filter( 'kirki/fields', '_header_blog_fields' );

/*
Footer
 */
function _header_footer_fields( $fields ) {
    // Footer Setting
    $fields[] = [
        'type'        => 'radio-image',
        'settings'    => 'choose_default_footer',
        'label'       => esc_html__( 'Choose Footer Style', 'seacab' ),
        'section'     => 'footer_setting',
        'default'     => '5',
        'placeholder' => esc_html__( 'Select an option...', 'seacab' ),
        'priority'    => 10,
        'multiple'    => 1,
        'choices'     => [
            'footer-style-1'   => get_template_directory_uri() . '/inc/img/footer/footer-1.png',
            'footer-style-2'   => get_template_directory_uri() . '/inc/img/footer/footer-2.png',
        ],
        'default'     => 'footer-style-1',
    ];

    $fields[] = [
        'type'        => 'select',
        'settings'    => 'footer_widget_number',
        'label'       => esc_html__( 'Widget Number', 'seacab' ),
        'section'     => 'footer_setting',
        'default'     => '4',
        'placeholder' => esc_html__( 'Select an option...', 'seacab' ),
        'priority'    => 10,
        'multiple'    => 1,
        'choices'     => [
            '4' => esc_html__( 'Widget Number 4', 'seacab' ),
            '3' => esc_html__( 'Widget Number 3', 'seacab' ),
            '2' => esc_html__( 'Widget Number 2', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'        => 'color',
        'settings'    => 'seacab_footer_bg_color',
        'label'       => __( 'Footer BG Color', 'seacab' ),
        'description' => esc_html__( 'This is a Footer bg color control.', 'seacab' ),
        'section'     => 'footer_setting',
        'default'     => '#0c0f16',
        'priority'    => 10,
    ];

    $fields[] = [
        'type'     => 'switch',
        'settings' => 'footer_style_2_switch',
        'label'    => esc_html__( 'Footer Style 2 On/Off', 'seacab' ),
        'section'  => 'footer_setting',
        'default'  => '0',
        'priority' => 10,
        'choices'  => [
            'on'  => esc_html__( 'Enable', 'seacab' ),
            'off' => esc_html__( 'Disable', 'seacab' ),
        ],
    ];

    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_copyright',
        'label'    => esc_html__( 'Copy Right', 'seacab' ),
        'section'  => 'footer_setting',
        'default'  => esc_html__( 'Copyright &copy; 2022 TwinkleTheme. All Rights Reserved', 'seacab' ),
        'priority' => 10,
    ];  
      
    return $fields;
}
add_filter( 'kirki/fields', '_header_footer_fields' );

// color
function seacab_color_fields( $fields ) {
    // Color Settings
    $fields[] = [
        'type'        => 'color',
        'settings'    => 'seacab_site_primary_color',
        'label'       => __( 'Primary Color', 'seacab' ),
        'description' => __( 'Site main color.', 'seacab' ),
        'section'     => 'color_setting',
        'default'     => '#e82f51',
        'priority'    => 10,
    ];

    return $fields;
}
add_filter( 'kirki/fields', 'seacab_color_fields' );

// 404
function seacab_404_fields( $fields ) {
    // 404 settings
    $fields[] = [
        'type'        => 'image',
        'settings'    => 'seacab_404_bg',
        'label'       => esc_html__( '404 Image.', 'seacab' ),
        'description' => esc_html__( '404 Image.', 'seacab' ),
        'section'     => '404_page',
    ];
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_error_title',
        'label'    => esc_html__( 'Not Found Title', 'seacab' ),
        'section'  => '404_page',
        'default'  => esc_html__( '404', 'seacab' ),
        'priority' => 10,
    ];
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_error_subtitle',
        'label'    => esc_html__( 'Not Found Subtitle', 'seacab' ),
        'section'  => '404_page',
        'default'  => esc_html__( 'Page not found', 'seacab' ),
        'priority' => 10,
    ];
    $fields[] = [
        'type'     => 'textarea',
        'settings' => 'seacab_error_desc',
        'label'    => esc_html__( '404 Description Text', 'seacab' ),
        'section'  => '404_page',
        'default'  => esc_html__( 'Oops! The page you are looking for does not exist. It might have been moved or deleted', 'seacab' ),
        'priority' => 10,
    ];
    $fields[] = [
        'type'     => 'text',
        'settings' => 'seacab_error_link_text',
        'label'    => esc_html__( '404 Link Text', 'seacab' ),
        'section'  => '404_page',
        'default'  => esc_html__( 'Back To Home', 'seacab' ),
        'priority' => 10,
    ];
    return $fields;
}
add_filter( 'kirki/fields', 'seacab_404_fields' );

/**
 * Added Fields
 */
function seacab_typo_fields( $fields ) {
    // typography settings
    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_body_setting',
        'label'       => esc_html__( 'Body Font', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'body',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h_setting',
        'label'       => esc_html__( 'Heading h1 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h1',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h2_setting',
        'label'       => esc_html__( 'Heading h2 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h2',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h3_setting',
        'label'       => esc_html__( 'Heading h3 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h3',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h4_setting',
        'label'       => esc_html__( 'Heading h4 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h4',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h5_setting',
        'label'       => esc_html__( 'Heading h5 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h5',
            ],
        ],
    ];

    $fields[] = [
        'type'        => 'typography',
        'settings'    => 'typography_h6_setting',
        'label'       => esc_html__( 'Heading h6 Fonts', 'seacab' ),
        'section'     => 'typo_setting',
        'default'     => [
            'font-family'    => '',
            'variant'        => '',
            'font-size'      => '',
            'line-height'    => '',
            'letter-spacing' => '0',
            'color'          => '',
        ],
        'priority'    => 10,
        'transport'   => 'auto',
        'output'      => [
            [
                'element' => 'h6',
            ],
        ],
    ];
    return $fields;
}

add_filter( 'kirki/fields', 'seacab_typo_fields' );

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function seacab_THEME_option( $name ) {
    $value = '';
    if ( class_exists( 'seacab' ) ) {
        $value = Kirki::get_option( seacab_get_theme(), $name );
    }

    return apply_filters( 'seacab_THEME_option', $value, $name );
}

/**
 * Get config ID
 *
 * @return string
 */
function seacab_get_theme() {
    return 'seacab';
}