<?php
// Create Footer section

CSF::createSection( $xcency_theme_option, array(
	'id'    => 'footer_options',
	'title'  => esc_html__( 'Footer Options', 'xcency' ),
	'icon'   => 'fa fa-wordpress',
	'fields' => array(
		array(
			'id'            => 'site_default_footer',
			'type'          => 'select',
			'title'         => esc_html__( 'Select Footer', 'xcency' ),
			'placeholder'   => esc_html__( 'Default', 'xcency' ),
			'empty_message' => esc_html__( 'No Footer Template Found. You can create footer template from Xcency Footers > Add New.', 'xcency' ),
			'options'       => 'posts',
			'query_args'    => array(
				'post_type'      => 'xcency_footer',
				'posts_per_page' => -1,
			),
			'desc'          => esc_html__( 'Select a Footer template from here.', 'xcency' ),
		),

		array(
			'type'       => 'notice',
			'id'            => 'site_footer_notice',
			'style'      => 'warning',
			'content' => sprintf(
				'%s <a href="%s" target="_blank">%s</a> %s',
				esc_html__('Custom footer selected. You can edit/create Header Template in the', 'xcency'),
				admin_url('edit.php?post_type=xcency_footer'),
				esc_html__('Xcency Footers', 'xcency'),
				esc_html__('dashboard menu.', 'xcency')
			),
			'dependency' => array(
				'site_default_footer', '!=', '',
			),
		),

		array(
			'id'                    => 'footer_bg_image',
			'type'                  => 'background',
			'title'                 => esc_html__( 'Footer Background', 'xcency' ),
			'background_image'   => false,
			'background_gradient'   => false,
			'background_origin'     => false,
			'background_clip'       => false,
			'background_blend-mode' => false,
			'background_attachment' => false,
			'background_size'       => false,
			'background_position'   => false,
			'background_repeat'     => false,
			'output'                => '.footer-widget-area,.footer-bottom-area',
			'desc'                  => esc_html__( 'Select footer background color.', 'xcency' ),
			'dependency' => array(
				'site_default_footer', '==', '',
			),
		),

		array(
			'id'      => 'footer_widget_column',
			'type'    => 'select',
			'title'   => esc_html__( 'Widget Column', 'xcency' ),
			'desc'    => esc_html__( 'Select widget area column number.', 'xcency' ),
			'options' => array(
				'col-lg-12' => esc_html__( '1 Column', 'xcency' ),
				'col-lg-6'  => esc_html__( '2 Column', 'xcency' ),
				'col-lg-4'  => esc_html__( '3 Column', 'xcency' ),
				'col-lg-3'  => esc_html__( '4 Column', 'xcency' ),
			),
			'default' => 'col-lg-3',
			'dependency' => array(
				'site_default_footer', '==', '',
			),
		),


		array(
			'id'            => 'footer_info_left_text',
			'type'          => 'wp_editor',
			'title'         => esc_html__( 'Footer Bottom Left Info Text', 'xcency' ),
			'desc'          => esc_html__( 'Type footer bottom left info text here.', 'xcency' ),
			'tinymce'       => true,
			'quicktags'     => true,
			'media_buttons' => false,
			'height'        => '100px',
			'dependency' => array(
				'site_default_footer', '==', '',
			),
		),

		array(
			'id'            => 'copyright_text',
			'type'          => 'wp_editor',
			'title'         => esc_html__( 'Copyright Text', 'xcency' ),
			'desc'          => esc_html__( 'Type site copyright text here.', 'xcency' ),
			'tinymce'       => true,
			'quicktags'     => true,
			'media_buttons' => false,
			'height'        => '100px',
			'dependency' => array(
				'site_default_footer', '==', '',
			),
		),

		array(
			'id'       => 'go_to_top_button',
			'type'     => 'switcher',
			'title'    => esc_html__( 'Enable Go Top Button', 'xcency' ),
			'default'  => false,
			'text_on'  => esc_html__( 'Yes', 'xcency' ),
			'text_off' => esc_html__( 'No', 'xcency' ),
			'desc'     => esc_html__( 'Enable or disable go to top button.', 'xcency' ),
		),

		array(
			'id'    => 'go_top_icon',
			'type'  => 'icon',
			'title' => esc_html__( 'Go Top Icon', 'xcency' ),
			'desc'  => esc_html__( 'Select icon', 'xcency' ),
			'default'  => 'fas fa-arrow-up',
			'dependency' => array(
				'go_to_top_button',
				'==',
				'true',
				'all'
			),
		),
	)
) );