<?php

// Create blog page options
CSF::createSection($xcency_theme_option, array(
	'title'  => esc_html__('Blog Page', 'xcency'),
	'id'     => 'blog_page_options',
	'icon'   => 'fa fa-pencil-square-o',
	'fields' => array(

		array(
			'id'      => 'blog_layout',
			'type'    => 'select',
			'title'   => esc_html__('Blog Layout', 'xcency'),
			'options' => array(
				'grid'          => esc_html__('Grid Full', 'xcency'),
				'grid-ls'       => esc_html__('Grid With Left Sidebar', 'xcency'),
				'grid-rs'       => esc_html__('Grid With Right Sidebar', 'xcency'),
				'left-sidebar'  => esc_html__('Left Sidebar', 'xcency'),
				'right-sidebar' => esc_html__('Right Sidebar', 'xcency'),
				'full-width'    => esc_html__('Full Width', 'xcency'),
			),
			'default' => 'right-sidebar',
			'desc'    => esc_html__('Select blog page layout.', 'xcency'),
		),

		array(
			'id'       => 'blog_banner',
			'type'     => 'switcher',
			'title'    => esc_html__('Enable Blog Banner', 'xcency'),
			'default'  => true,
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Enable or disable blog page banner.', 'xcency'),
		),

		array(
			'id'                    => 'blog_banner_background_options',
			'type'                  => 'background',
			'title'                 => esc_html__('Banner Background', 'xcency'),
			'background_gradient'   => true,
			'background_origin'     => false,
			'background_clip'       => false,
			'background_blend-mode' => false,
			'background_attachment' => false,
			'background_size'       => false,
			'background_position'   => false,
			'background_repeat'     => false,
			'dependency'            => array('blog_banner', '==', true),
			'output'                => '.banner-area.blog-banner',
			'desc'                  => esc_html__('If you want different banner background settings for blog page then select blog page banner background Options from here.', 'xcency'),
		),

		array(
			'id'         => 'blog_title',
			'type'       => 'text',
			'title'      => esc_html__('Banner Title', 'xcency'),
			'desc'       => esc_html__('Type blog banner title here.', 'xcency'),
			'dependency' => array('blog_banner', '==', true),
		),

		array(
			'id'       => 'post_author',
			'type'     => 'switcher',
			'title'    => esc_html__('Show Author Name', 'xcency'),
			'default'  => true,
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide / Show post author name.', 'xcency'),
		),

		array(
			'id'       => 'post_date',
			'type'     => 'switcher',
			'title'    => esc_html__('Show Post Date', 'xcency'),
			'default'  => true,
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide / Show post date.', 'xcency'),
		),

		array(
			'id'         => 'cmnt_number',
			'type'       => 'switcher',
			'title'      => esc_html__('Show Comment Number', 'xcency'),
			'default'    => true,
			'text_on'    => esc_html__('Yes', 'xcency'),
			'text_off'   => esc_html__('No', 'xcency'),
			'desc'       => esc_html__('Hide / Show post comment number.', 'xcency'),
			'dependency' => array('blog_layout', 'any', 'full-width,right-sidebar,left-sidebar'),
		),

		array(
			'id'         => 'show_category',
			'type'       => 'switcher',
			'title'      => esc_html__('Show Category Name', 'xcency'),
			'default'    => true,
			'text_on'    => esc_html__('Yes', 'xcency'),
			'text_off'   => esc_html__('No', 'xcency'),
			'desc'       => esc_html__('Hide / Show post category name.', 'xcency'),
			'dependency' => array('blog_layout', 'any', 'full-width,right-sidebar,left-sidebar'),
		),

		array(
			'id'       => 'read_more_button',
			'type'     => 'switcher',
			'title'    => esc_html__('Show Read More Button', 'xcency'),
			'default'  => true,
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide / Show post read more button.', 'xcency'),
		),

		array(
			'id'         => 'blog_read_more_text',
			'type'       => 'text',
			'title'      => esc_html__('Read More Button Text', 'xcency'),
			'desc'       => esc_html__('Type blog read more button here.', 'xcency'),
			'dependency' => array('read_more_button', '==', true),
		),
	)
));