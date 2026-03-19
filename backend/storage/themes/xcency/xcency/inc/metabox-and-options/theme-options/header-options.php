<?php
// Create header Settings section
CSF::createSection( $xcency_theme_option, array(
	'title' => esc_html__( 'Header Settings', 'xcency' ),
	'id'    => 'header_options',
	'icon'  => 'fa fa-header',
) );


CSF::createSection( $xcency_theme_option, array(
	'parent' => 'header_options',
	'title'  => esc_html__( 'Header Options', 'xcency' ),
	'icon'   => 'fa fa-credit-card',
	'fields' => array(
		array(
			'id'            => 'site_default_header',
			'type'          => 'select',
			'title'         => esc_html__( 'Select Header', 'xcency' ),
			'placeholder'   => esc_html__( 'Default', 'xcency' ),
			'empty_message' => esc_html__( 'No Header Template Found. You can create header template from Xcency Headers > Add New.', 'xcency' ),
			'options'       => 'posts',
			'query_args'    => array(
				'post_type'      => 'xcency_header',
				'posts_per_page' => - 1,
			),
			'desc'          => esc_html__( 'Select site header from here. Selected template will be used for all pages by default.', 'xcency' ),
		),

		array(
			'type'       => 'notice',
			'id'         => 'site_header_notice',
			'style'      => 'warning',
			'content' => sprintf(
				'%s <a href="%s" target="_blank">%s</a> %s',
				esc_html__('Custom header selected. You can edit/create Header Template in the', 'xcency'),
				admin_url('edit.php?post_type=xcency_header'),
				esc_html__('Xcency Headers', 'xcency'),
				esc_html__('dashboard menu.', 'xcency')
			),
			'dependency' => array(
				'site_default_header',
				'!=',
				'',
			),
		),

		array(
			'id'           => 'header_default_logo',
			'type'         => 'media',
			'title'        => esc_html__( 'Header Logo', 'xcency' ),
			'library'      => 'image',
			'url'          => false,
			'button_title' => esc_html__( 'Upload Logo', 'xcency' ),
			'desc'         => esc_html__( 'Upload logo image', 'xcency' ),
			'dependency'   => array(
				'site_default_header',
				'==',
				'',
			),

		),

		array(
			'id'         => 'logo_image_size',
			'type'       => 'dimensions',
			'title'      => esc_html__( 'Logo Image Size', 'xcency' ),
			'output'     => '.site-branding img',
			'width'      => true,
			'height'     => true,
			'desc'       => esc_html__( 'Select logo image size.', 'xcency' ),
			'dependency' => array(
				'site_default_header',
				'==',
				'',
			),
		),
	)
) );