<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$priority = 0;

/**
 * Blog general
 */
VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'sticky_sidebar',
	'section' => 'section_blog_general',
	'label' => esc_html__( 'Sticky Sidebar', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'enable' => esc_html__( 'Enable', 'ziomm' ),
		'disable' => esc_html__( 'Disable', 'ziomm' )
	),
	'default' => 'disable',
) );

/**
 * Blog page
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sb_1',
	'section' => 'section_blog',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'blog_layout',
	'section' => 'section_blog',
	'label' => esc_html__( 'Layout', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'default' => esc_html__( 'Default', 'ziomm' ),
		'grid' => esc_html__( 'Grid', 'ziomm' ),
		'masonry' => esc_html__( 'Masonry', 'ziomm' ),
		'list' => esc_html__( 'List', 'ziomm' )
	),
	'default' => 'default',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'blog_type_pagination',
	'section' => 'section_blog',
	'label' => esc_html__( 'Type Pagination', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'none' => esc_html__( 'None', 'ziomm' ),
		'paged' => esc_html__( 'Paged', 'ziomm' ),
		'numeric' => esc_html__( 'Numeric', 'ziomm' )
	),
	'default' => 'numeric',
) );

VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sb_2',
	'section' => 'section_blog',
	'default' => '<div class="kirki-separator">' . esc_html__( 'Page Title', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'text',
	'settings' => 'blog_title',
	'section' => 'section_blog',
	'label' => esc_html__( 'Blog Title', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => esc_html__( 'Blog', 'ziomm' ),
) );

/**
 * Archive page
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'sa_1',
	'section' => 'section_archive',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'archive_layout',
	'section' => 'section_archive',
	'label' => esc_html__( 'Layout', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'default' => esc_html__( 'Default', 'ziomm' ),
		'grid' => esc_html__( 'Grid', 'ziomm' ),
		'masonry' => esc_html__( 'Masonry', 'ziomm' ),
		'list' => esc_html__( 'List', 'ziomm' )
	),
	'default' => 'grid',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'archive_type_pagination',
	'section' => 'section_archive',
	'label' => esc_html__( 'Type Pagination', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'none' => esc_html__( 'None', 'ziomm' ),
		'paged' => esc_html__( 'Paged', 'ziomm' ),
		'numeric' => esc_html__( 'Numeric', 'ziomm' )
	),
	'default' => 'numeric',
) );

/**
 * Search page
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'ss_1',
	'section' => 'section_search',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'search_layout',
	'section' => 'section_search',
	'label' => esc_html__( 'Layout', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'default' => esc_html__( 'Default', 'ziomm' ),
		'grid' => esc_html__( 'Grid', 'ziomm' ),
		'masonry' => esc_html__( 'Masonry', 'ziomm' ),
		'list' => esc_html__( 'List', 'ziomm' )
	),
	'default' => 'grid',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'search_type_pagination',
	'section' => 'section_search',
	'label' => esc_html__( 'Type Pagination', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'none' => esc_html__( 'None', 'ziomm' ),
		'paged' => esc_html__( 'Paged', 'ziomm' ),
		'numeric' => esc_html__( 'Numeric', 'ziomm' )
	),
	'default' => 'numeric',
) );

/**
 * Single post
 */
VLT_Options::add_field( array(
	'type' => 'custom',
	'settings' => 'ssp_1',
	'section' => 'section_single_post',
	'default' => '<div class="kirki-separator">' . esc_html__( 'General', 'ziomm' ) . '</div>',
	'priority' => $priority++,
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'single_post_default_style',
	'section' => 'section_single_post',
	'label' => esc_html__( 'Default Style', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'none' => esc_html__( 'None', 'ziomm' ),
		'default' => esc_html__( 'Style 1', 'ziomm' ),
		'style-2' => esc_html__( 'Style 2', 'ziomm' ),
		'style-3' => esc_html__( 'Style 3', 'ziomm' ),
		'style-4' => esc_html__( 'Style 4', 'ziomm' ),
		'style-5' => esc_html__( 'Style 5', 'ziomm' ),
		'style-6' => esc_html__( 'Style 6', 'ziomm' ),
		'style-7' => esc_html__( 'Style 7', 'ziomm' )
	),
	'default' => 'none',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'about_author',
	'section' => 'section_single_post',
	'label' => esc_html__( 'About Author', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'hide',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'show_share_post',
	'section' => 'section_single_post',
	'label' => esc_html__( 'Post Share', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'hide',
) );

VLT_Options::add_field( array(
	'type' => 'select',
	'settings' => 'also_like_posts',
	'section' => 'section_single_post',
	'label' => esc_html__( 'Also Like Posts', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'choices' => array(
		'show' => esc_html__( 'Show', 'ziomm' ),
		'hide' => esc_html__( 'Hide', 'ziomm' )
	),
	'default' => 'hide',
) );

/**
 * Page 404
 */
VLT_Options::add_field( array(
	'type' => 'textarea',
	'settings' => 'error_title',
	'section' => 'section_404',
	'label' => esc_html__( 'Error Title', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => '404 Error  <span class="em-6">😿</span>',
) );

VLT_Options::add_field( array(
	'type' => 'textarea',
	'settings' => 'error_subtitle',
	'section' => 'section_404',
	'label' => esc_html__( 'Error Subtitle', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => 'Oops! The page you are looking for does not exist. <br>It might have been moved or deleted.',
) );

VLT_Options::add_field( array(
	'type' => 'background',
	'settings' => 'error_background',
	'section' => 'section_404',
	'label' => esc_html__( 'Error Background', 'ziomm' ),
	'priority' => $priority++,
	'transport' => 'auto',
	'default' => array(
		'background-color' => '#ffffff',
		'background-image' => '',
		'background-repeat' => 'no-repeat',
		'background-position' => 'center center',
		'background-size' => 'cover',
		'background-attachment' => 'scroll',
	),
	'output' => array(
		array(
			'element' => '.vlt-page--404'
		),
	),
) );