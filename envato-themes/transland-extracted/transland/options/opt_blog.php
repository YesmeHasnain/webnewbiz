<?php

Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Blog Settings', 'transland'),
	'id'        => 'blog_page',
	'icon'      => 'dashicons dashicons-admin-post',
));


Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Title-Bar', 'transland'),
	'id'        => 'blog_titlebar_settings',
	'icon'      => 'dashicons dashicons-admin-post',
    'subsection' => true,
	'fields'    => array(
		array(
			'title'     => esc_html__('Blog Page Title', 'transland'),
			'subtitle'  => esc_html__('Give here the blog page title', 'transland'),
			'desc'      => esc_html__('This text will be show on blog page banner', 'transland'),
			'id'        => 'blog_title',
			'type'      => 'text',
			'default'   => 'News'
		),
	)
));


Redux::setSection('transland_opt', array(
    'title'     => esc_html__('Layout Style', 'transland'),
    'id'        => 'blog_layout_settings',
    'icon'      => 'dashicons dashicons-align-left',
    'subsection' => true,
    'fields'    => array(
        array(
            'title'     => esc_html__('Select Blog Layout Style', 'transland'),
            'id'        => 'blog_layout_style',
            'type'      => 'image_select',
            'default'   => '1',
            'options'   => array(
                '1' => array(
                    'alt' => esc_html__('Right Sidebar - Default', 'transland'),
                    'img' => esc_url(TRANSLAND_DIR_IMG.'/opt/right-sidebar.png')
                ),
                '2' => array(
                    'alt' => esc_html__('Left Sidebar', 'transland'),
                    'img' => esc_url(TRANSLAND_DIR_IMG.'/opt/left-sidebar.png')
                ),
            )
        ),

    )
));


Redux::setSection('transland_opt', array(
	'title'     => esc_html__('Blog Single', 'transland'),
	'id'        => 'blog_single_opt',
	'icon'      => 'dashicons dashicons-media-document',
	'subsection' => true,
	'fields'    => array(
        array(
			'title'     => esc_html__( 'Post Meta', 'transland' ),
			'subtitle'  => esc_html__( 'Show/hide post meta on blog archive page', 'transland' ),
			'id'        => 'is_post_meta',
			'type'      => 'switch',
            'on'        => esc_html__( 'Show', 'transland' ),
            'off'       => esc_html__( 'Hide', 'transland' ),
            'default'   => '1',
		),
	)
));

// blog Share Options
Redux::setSection('transland_opt', array(
    'title'     => esc_html__('Blog Social Share', 'transland'),
    'id'        => 'blog_share_opt',
    'subsection'=> true,
    'icon'      => 'dashicons dashicons-share',
    'fields'    => array(

        array(
            'title'     => esc_html__( 'Social Share', 'transland' ),
            'id'        => 'is_social_share',
            'type'      => 'switch',
            'on'        => esc_html__( 'Enabled', 'transland' ),
            'off'       => esc_html__( 'Disabled', 'transland' ),
            'default'   => '0'
        ),

        array(
            'id' => 'blog_share_start',
            'type' => 'section',
            'title' => __('Share Options', 'transland'),
            'subtitle' => __('Enable/Disable social media share options as you want.', 'transland'),
            'required' => array('is_social_share','=','1'),
            'indent' => true,
        ),

        array(
            'title'    => esc_html__('Title', 'transland'),
            'id'       => 'share_heading',
            'type'     => 'text',
            'compiler' => true,
            'default'  => esc_html__('Share on', 'transland'),
        ),

        array(
            'id'       => 'is_post_fb',
            'type'     => 'switch',
            'title'    => esc_html__('Facebook', 'transland'),
            'default'  => true,
            'on'       => esc_html__('Show', 'transland'),
            'off'      => esc_html__('Hide', 'transland'),
        ),

        array(
            'id'       => 'is_post_twitter',
            'type'     => 'switch',
            'title'    => esc_html__('Twitter', 'transland'),
            'default'  => true,
            'on'       => esc_html__('Show', 'transland'),
            'off'      => esc_html__('Hide', 'transland'),
        ),

        array(
            'id'       => 'is_post_linkedin',
            'type'     => 'switch',
            'title'    => esc_html__('Linkedin', 'transland'),
            'on'       => esc_html__('Show', 'transland'),
            'off'      => esc_html__('Hide', 'transland'),
            'default'  => true,
        ),

        array(
            'id'       => 'is_post_pinterest',
            'type'     => 'switch',
            'title'    => esc_html__('Pinterest', 'transland'),
            'default'  => true,
            'on'       => esc_html__('Show', 'transland'),
            'off'      => esc_html__('Hide', 'transland'),
        ),

        array(
            'id'     => 'post_share_end',
            'type'   => 'section',
            'indent' => false,
        ),
    )
));


