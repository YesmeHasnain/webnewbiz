<?php

$xcency_team_meta = 'xcency_team_meta';

// Create a metabox
CSF::createMetabox( $xcency_team_meta, array(
	'title'     => esc_html__( 'Social Profiles Options', 'xcency' ),
	'post_type' => 'xcency_team',
	'data_type' => 'serialize',
) );


CSF::createSection( $xcency_team_meta, array(
	'fields' => array(
		array(
			'id'           => 'member_social_profile',
			'type'         => 'group',
			'title'        => esc_html__( 'Member Social Profile', 'xcency' ),
			'desc'         => esc_html__( 'Add member social profile icons here.', 'xcency' ),
			'button_title' => esc_html__( 'Add Social Profile', 'xcency' ),
			'fields'       => array(
				array(
					'id'    => 'site_name',
					'type'  => 'text',
					'title' => esc_html__( 'Site Name', 'xcency' ),
					'desc'  => esc_html__( 'Type social site name here.', 'xcency' ),
				),

				array(
					'id'    => 'site_icon',
					'type'  => 'icon',
					'title' => esc_html__( 'Icon', 'xcency' ),
					'desc'  => esc_html__( 'Select icon', 'xcency' ),
				),

				array(
					'id'    => 'site_url',
					'type'  => 'text',
					'title' => esc_html__( 'Profile Link', 'xcency' ),
					'desc'  => esc_html__( 'Type social site url here.', 'xcency' ),
				),
			),

			'default' => array(
				array(
					'site_name' => esc_html__( 'Twitter', 'xcency' ),
					'site_icon' => 'fab fa-x-twitter',
					'site_url'  => '#',
				),
			),
		),

	)
) );