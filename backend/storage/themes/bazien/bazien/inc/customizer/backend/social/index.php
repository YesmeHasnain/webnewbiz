<?php

// ============================================
// Panel
// ============================================

// no panel


// ============================================
// Sections
// ============================================

Kirki::add_section( 'social_media', array(
    'title'          => esc_html__( 'Social Media', 'bazien' ),
    'priority'       => 65,
    'capability'     => 'edit_theme_options',
) );


// ============================================
// Controls
// ============================================

$sep_id  = 98795;
$section = 'social_media';

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'facebook_link',
    'label'       => esc_html__( 'Facebook', 'bazien' ),
    'section'     => $section,
    'default'     => '#',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'twitter_link',
    'label'       => esc_html__( 'Twitter', 'bazien' ),
    'section'     => $section,
    'default'     => '#',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'pinterest_link',
    'label'       => esc_html__( 'Pinterest', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'linkedin_link',
    'label'       => esc_html__( 'LinkedIn', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'googleplus_link',
    'label'       => esc_html__( 'Google+', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'rss_link',
    'label'       => esc_html__( 'RSS', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'tumblr_link',
    'label'       => esc_html__( 'Tumblr', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'instagram_link',
    'label'       => esc_html__( 'Instagram', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'youtube_link',
    'label'       => esc_html__( 'Youtube', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'vimeo_link',
    'label'       => esc_html__( 'Vimeo', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'behance_link',
    'label'       => esc_html__( 'Behance', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'dribbble_link',
    'label'       => esc_html__( 'Dribbble', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'flickr_link',
    'label'       => esc_html__( 'Flickr', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'git_link',
    'label'       => esc_html__( 'Git', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'skype_link',
    'label'       => esc_html__( 'Skype', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'weibo_link',
    'label'       => esc_html__( 'Weibo', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'foursquare_link',
    'label'       => esc_html__( 'Foursquare', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'soundcloud_link',
    'label'       => esc_html__( 'Soundcloud', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );

Kirki::add_field( 'bazien', array(
    'type'        => 'text',
    'settings'    => 'snapchat_link',
    'label'       => esc_html__( 'Snapchat', 'bazien' ),
    'section'     => $section,
    'default'     => '',
    'priority'    => 10,
) );
