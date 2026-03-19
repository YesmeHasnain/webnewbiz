<?php
//Single Post

CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Single Post / Post Details', 'xcency' ),
	'id'     => 'single_post_options',
	'icon'   => 'fa fa-pencil',
	'fields' => array(

		array(
			'id'      => 'single_post_default_layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Layout', 'xcency' ),
			'options' => array(
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'xcency' ),
				'full-width'    => esc_html__( 'Full Width', 'xcency' ),
				'right-sidebar' => esc_html__( 'Right Sidebar', 'xcency' ),
			),
			'default' => 'right-sidebar',
			'desc'    => esc_html__( 'Select single post layout', 'xcency' ),
		),


		array(
			'id'         => 'single_post_default_sidebar',
			'type'       => 'select',
			'title'      => esc_html__( 'Sidebar', 'xcency' ),
			'options'    => 'xcency_sidebars',
			'default' => 'sidebar',
			'dependency' => array( 'single_post_default_layout', '!=', 'full-width' ),
			'desc'       => esc_html__( 'Select default sidebar for all posts. You can override this settings on individual post.', 'xcency' ),
		),

		array(
			'id'         => 'post_banner_title',
			'type'       => 'text',
			'title'      => esc_html__('Banner Default Title', 'xcency'),
			'desc'       => esc_html__('Default banner title for all post.', 'xcency'),
			'dependency' => array( 'show_default_title', '==', 'false' ),
		),

		array(
			'id'       => 'show_default_title',
			'type'     => 'switcher',
			'title'    => esc_html__('Show Post Title On Banner?', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Show post title on single post banner area. Default title is "Blog" for all single post.', 'xcency'),
			'default'  => false
		),

		array(
			'id'         => 'single_post_title_tag',
			'type'       => 'button_set',
			'title'      => esc_html__( 'Post Title Tag', 'xcency' ),
			'options'    => array(
				'h1'   => esc_html__( 'H1', 'xcency' ),
				'h2'   => esc_html__( 'H2', 'xcency' ),
				'h3'   => esc_html__( 'H3', 'xcency' ),
				'h4'   => esc_html__( 'H4', 'xcency' ),
				'h5'   => esc_html__( 'H5', 'xcency' ),
				'h6'   => esc_html__( 'H6', 'xcency' ),
			),
			'default'    => 'h1',
			'desc'       => esc_html__( 'Select single post title tag.', 'xcency' ),
		),

		array(
			'id'       => 'single_post_breadcrumb',
			'type'     => 'switcher',
			'title'    => esc_html__('Enable Breadcrumb', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show banner breadcrumb on single post.', 'xcency'),
			'default'  => false
		),

		array(
			'id'       => 'single_post_author',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Author Name', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show author name on post details page.', 'xcency'),
			'default'  => true
		),

		array(
			'id'       => 'single_post_date',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Date', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show date on post details page.', 'xcency'),
			'default'  => true
		),

		array(
			'id'       => 'single_post_cmnt',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Comments Number', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show comments number on post details page.', 'xcency'),
			'default'  => true,
		),

		array(
			'id'       => 'single_post_cat',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Categories', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show categories on post details page.', 'xcency'),
			'default'  => true
		),

		array(
			'id'       => 'single_post_tag',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Tags', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show tags on post details page.', 'xcency'),
			'default'  => true
		),

		array(
			'id'       => 'post_share',
			'type'     => 'switcher',
			'title'    => esc_html__('Post Share icons', 'xcency'),
			'text_on'  => esc_html__('Yes', 'xcency'),
			'text_off' => esc_html__('No', 'xcency'),
			'desc'     => esc_html__('Hide or show social share icons on post details page.', 'xcency'),
			'default'  => true
		),
	)
) );