<?php

return array(
	'title'      => esc_html__( 'Single Post Settings', 'earls' ),
	'id'         => 'single_post_setting',
	'desc'       => '',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'single_source_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Single Post Source Type', 'earls' ),
			'options' => array(
				'd' => esc_html__( 'Default', 'earls' ),
				'e' => esc_html__( 'Elementor', 'earls' ),
			),
			'default' => 'd',
		),
		
		array(
			'id'       => 'single_default_st',
			'type'     => 'section',
			'title'    => esc_html__( 'Post Default', 'earls' ),
			'indent'   => true,
			'required' => [ 'single_source_type', '=', 'd' ],
		),
		array(
			'id'      => 'single_post_date',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Date', 'earls' ),
			'desc'    => esc_html__( 'Enable to show post publish date on posts detail page', 'earls' ),
			'default' => true,
		),
		array(
			'id'      => 'single_post_author',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Author', 'earls' ),
			'desc'    => esc_html__( 'Enable to show author on posts detail page', 'earls' ),
			'default' => true,
		),
		array(
			'id'      => 'single_post_comments',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Comments', 'earls' ),
			'desc'    => esc_html__( 'Enable to show number of comments on posts single page', 'earls' ),
			'default' => true,
		),
		//Social Sharing
		array(
			'id'      => 'facebook_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Facebook Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Facebook', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'twitter_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Twitter Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Twitter', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'linkedin_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Linkedin Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Linkedin', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'pinterest_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Pinterest Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Pinterest', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'reddit_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Reddit Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Reddit', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'tumblr_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Tumblr Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Tumblr', 'earls' ),
			'default' => false,
		),
		array(
			'id'      => 'digg_sharing',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Digg Post Share', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Post Share to Digg', 'earls' ),
			'default' => false,
		),
		//Author Box
		array(
			'id'      => 'single_post_author_box',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable/Disable Author Box Info', 'earls' ),
			'desc'    => esc_html__( 'Enable to show Author Box Info', 'earls' ),
			'default' => false,
		),
		
		array(
			'id'       => 'single_section_default_ed',
			'type'     => 'section',
			'indent'   => false,
			'required' => [ 'single_source_type', '=', 'd' ],
		),
	),
);





