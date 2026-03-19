<?php

$sep_id  = 9576;
$section = 'blog';

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'blog_wide_layout',
  'label'       => esc_html__( 'Wide Layout', 'bazien' ),
	'section'     => $section,
	'default'     => '0',
	'priority'    => 10,
	'choices'     => array(
		'1'  => esc_html__( 'Enable', 'bazien' ),
		'0' => esc_html__( 'Disable', 'bazien' ),
	),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'select',
    'settings'    => 'blog_layout',
    'label'       => esc_html__( 'Blog Layout', 'bazien' ),
    'section'     => 'panel_header',
    'default'     => 'layout-1',
    'priority'    => 10,
    'section'     => $section,
    'choices'     => array(
        'layout-1'     => esc_html__( 'Layout 01', 'bazien' ),
        'layout-2'     => esc_html__( 'Layout 02', 'bazien' ),
        'layout-3'     => esc_html__( 'Layout 03', 'bazien' ),
        'layout-4'     => esc_html__( 'Layout 04', 'bazien' ),
        'layout-5'     => esc_html__( 'Layout 05', 'bazien' ),
    ),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'slider',
    'settings'    => 'blog_post_column_l',
    'label'       => esc_html__( 'Posts per row (Desktop screen)', 'bazien' ),
    'section'     => $section,
    'default'     => 3,
    'priority'    => 10,
    'choices'     => [
      'min'  => 1,
      'max'  => 6,
      'step' => 1,
    ],
		'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
		'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'slider',
    'settings'    => 'blog_post_column_m',
    'label'       => esc_html__( 'Posts per row (Tablet screen)', 'bazien' ),
    'section'     => $section,
    'default'     => 2,
    'priority'    => 10,
    'choices'     => [
      'min'  => 1,
      'max'  => 6,
      'step' => 1,
    ],
		'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
		'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'slider',
    'settings'    => 'blog_post_column_s',
    'label'       => esc_html__( 'Posts per row (Mobile screen)', 'bazien' ),
    'section'     => $section,
    'default'     => 1,
    'priority'    => 10,
    'choices'     => [
      'min'  => 1,
      'max'  => 6,
      'step' => 1,
    ],
    'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
		'active_callback'    => array(
        array(
            'setting'  => 'blog_layout',
            'operator' => 'contains',
            'value'    => array( 'layout-2', 'layout-4'),
        ),
    ),
) );

// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'blog_post_excerpt',
    'label'       => esc_html__( 'Show Excerpt', 'bazien' ),
    'section'     => $section,
    'default'     => 1,
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'toggle',
    'settings'    => 'blog_sidebar',
    'label'       => esc_html__( 'Blog Sidebar', 'bazien' ),
    'section'     => $section,
    'default'     => true,
    'priority'    => 10,
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_sidebar_position',
    'label'       => esc_html__( 'Sidebar Position', 'bazien' ),
    'section'     => $section,
    'default'     => 'right',
    'priority'    => 10,
    'choices'     => array(
        'left'    => esc_html__( 'Left', 'bazien' ),
        'right'   => esc_html__( 'Right', 'bazien' ),
    ),
    'active_callback'    => array(
        array(
            'setting'  => 'blog_sidebar',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );

// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
    'active_callback'    => array(
        array(
            'setting'  => 'blog_sidebar',
            'operator' => '==',
            'value'    => true,
        ),
    ),
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
    'type'        => 'radio-buttonset',
    'settings'    => 'blog_pagination',
    'label'       => esc_html__( 'Pagination', 'bazien' ),
    'section'     => $section,
    'default'     => 'default',
    'priority'    => 10,
    'choices'     => array(
        'default'           => esc_html__( 'Classic', 'bazien' ),
        'load_more_button'  => esc_html__( 'Load More', 'bazien' ),
        'infinite_scroll'   => esc_html__( 'Infinite', 'bazien' ),
    ),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
	'type'        => 'switch',
	'settings'    => 'limit_excerpt',
  'label'       => esc_html__( 'Limit Excerpt', 'bazien' ),
	'section'     => $section,
	'default'     => '0',
	'priority'    => 10,
	'choices'     => array(
		'1'  => esc_html__( 'Enable', 'bazien' ),
		'0' => esc_html__( 'Disable', 'bazien' ),
	),
) );
// ---------------------------------------------
Kirki::add_field( 'bazien', array(
    'type'        => 'separator',
    'settings'    => 'separator_'. $sep_id++,
    'section'     => $section,
) );
// ---------------------------------------------

Kirki::add_field( 'bazien', array(
  'type'     => 'number',
  'settings' => 'limit_excerpt_word',
  'section'  => $section,
  'label'       => esc_html__( 'Limit Excerpt Word', 'bazien' ),
  'default'  => 30,
  'priority' => 10,
  'choices'     => array(
      'min'  => 5,
      'max'  => 100,
      'step' => 1
  ),
  'active_callback'    => array(
      array(
          'setting'  => 'limit_excerpt',
          'operator' => '==',
          'value'    => 1,
      ),
  ),
) );
