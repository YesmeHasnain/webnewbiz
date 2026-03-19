<?php
//Team Options
CSF::createSection( $xcency_theme_option, array(
	'title'  => esc_html__( 'Team Options', 'xcency' ),
	'id'     => 'team_options',
	'icon'   => 'fa fa-users',
	'fields' => array(

		array(
			'id'      => 'team_default_layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Team Layout', 'xcency' ),
			'options' => array(
				'full-width'    => esc_html__( 'Full Width', 'xcency' ),
				'left-sidebar'  => esc_html__( 'Left Sidebar', 'xcency' ),
				'right-sidebar' => esc_html__( 'Right Sidebar', 'xcency' ),
			),
			'default' => 'full-width',
			'desc'    => esc_html__( 'Select team layout.', 'xcency' ),
		),

		array(
			'id'         => 'team_default_sidebar',
			'type'       => 'select',
			'title'      => esc_html__( 'Sidebar', 'xcency' ),
			'options'    => 'xcency_sidebars',
			'default'    => 'team-sidebar',
			'dependency' => array( 'team_default_layout', '!=', 'full-width' ),
			'desc'       => esc_html__( 'Select default sidebar for all team members. You can override this settings on individual team member.', 'xcency' ),
		),

		array(
			'id'    => 'team_url_slug',
			'type'  => 'text',
			'default' => 'team',
			'title' => esc_html__( 'URL Slug', 'xcency' ),
			'desc'  => esc_html__( 'Change team slug on URL. Don\'t forget to reset permalink after change this.', 'xcency' ),
		),

	)
) );