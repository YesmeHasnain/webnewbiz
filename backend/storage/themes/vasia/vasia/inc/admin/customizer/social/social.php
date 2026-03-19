<?php

$social_list  = array (
	''   => '',
	'facebook'   => esc_html__( 'Facebook', 'vasia' ),
	'twitter'    => esc_html__( 'Twitter', 'vasia' ),
	'google'     => esc_html__( 'Google+', 'vasia' ),
	'instagram'  => esc_html__( 'Instagram', 'vasia' ),
	'pinterest'  => esc_html__( 'Pinterest', 'vasia' ),
	'whatsapp'   => esc_html__( 'Whatsapp', 'vasia' ),
	'rss'        => esc_html__( 'RSS', 'vasia' ),
	'tumblr'     => esc_html__( 'Tumblr', 'vasia' ),
	'youtube'    => esc_html__( 'Youtube', 'vasia' ),
	'vimeo'      => esc_html__( 'Vimeo', 'vasia' ),
	'behance'    => esc_html__( 'Behance', 'vasia' ),
	'dribbble'   => esc_html__( 'Dribbble', 'vasia' ),
	'flickr'     => esc_html__( 'Flickr', 'vasia' ),
	'github'     => esc_html__( 'GitHub', 'vasia' ),
	'skype'      => esc_html__( 'Skype', 'vasia' ),
	'snapchat'   => esc_html__( 'Snapchat', 'vasia' ),
	'wechat'     => esc_html__( 'WeChat', 'vasia' ),
	'weibo'      => esc_html__( 'Weibo', 'vasia' ),
	'foursquare' => esc_html__( 'Foursquare', 'vasia' ),
	'soundcloud' => esc_html__( 'Soundcloud', 'vasia' ),
	'vk'         => esc_html__( 'VK', 'vasia' ),
);



Kirki::add_section( 'social', array(
    'priority'    => 55,
    'title'       => esc_html__( 'Social', 'vasia' ),
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'social_sharing_part',
	'section'     => 'social',
	'default'         => '<div class="customize-title-divider">' . __( 'Social sharing', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'sortable',
	'settings'    => 'social_sharing',
	'label'       => esc_html__( 'Social sharing in Single page', 'vasia' ),
	'section'     => 'social',
	'default'     => [
		'facebook',
		'pinterest',
		'twitter'
	],
	'choices'     => [
		'facebook' => esc_html__( 'Facebook', 'vasia' ),
		'pinterest' => esc_html__( 'Pinterest', 'vasia' ),
		'twitter' => esc_html__( 'Twitter', 'vasia' ),
		'whatsapp' => esc_html__( 'Whatsapp', 'vasia' ),
		'email' => esc_html__( 'Email', 'vasia' ),
		'vk' => esc_html__( 'VK', 'vasia' ),
		'linkedin' => esc_html__( 'LinkedIn', 'vasia' ),
		'telegram' => esc_html__( 'Telegram', 'vasia' ),
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'social_list_part',
	'section'     => 'social',
	'default'         => '<div class="customize-title-divider">' . __( 'Social List', 'vasia' ) . '</div>',
] );

Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Social list', 'vasia' ),
	'section'     => 'social',
	'priority'    => 10,
	'row_label' => [
		'type'  => 'field',
		'value' => esc_attr__( 'Element', 'vasia' ),
		'field' => 'name',
	],
	'button_label' => esc_html__('Add new', 'vasia' ),
	'settings'     => 'social_list',
	'fields' => [
		'name' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Social', 'vasia' ),
			'description' => esc_html__( 'Select a social network', 'vasia' ),
			'default'     => '',
			'choices'     => $social_list,
		],
		'url'  => [
			'type'        => 'text',
			'label'       => esc_html__( 'Social URL', 'vasia' ),
			'default'     => '',
		],
	]
] );