<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('zill_setup_metaboxes')){
	function zill_setup_metaboxes(){
		if(!function_exists('lastudio_kit_post_meta')){
			return;
		}
		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'zill-portfolio-settings',
			'title'         => esc_html__( 'Portfolio Settings', 'zill' ),
			'page'          => array( 'la_portfolio' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
                '_la_bg' => array(
                    'type'               => 'media',
                    'title'              => esc_html__( 'Background Image', 'zill' ),
                    'description'        => esc_html__( 'This option will work on some cases.', 'zill' ),
                    'library_type'       => 'image',
                    'multi_upload'       => false,
                    'value_format'       => 'id',
                    'upload_button_text' => esc_html__( 'Set Image', 'zill' ),
                ),
				'_pf_gallery' => array(
					'type'               => 'media',
					'title'              => esc_html__( 'Image Gallery', 'zill' ),
					'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery.', 'zill' ),
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Set Gallery Images', 'zill' ),
				),
				'_pf_description' => array(
					'type'        => 'wysiwyg',
					'title'       => esc_html__( 'Short Description', 'zill' ),
					'rows'        => 5
				),
				'_pf_client' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Client', 'zill' ),
				),
				'_pf_date' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Date', 'zill' ),
				),
				'_pf_awards' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Awards', 'zill' ),
				),
				'_pf_link1' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Demo Link 1', 'zill' ),
				),
				'_pf_link2' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Demo Link 2', 'zill' ),
				),
			),
		) );

		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'zill-post-settings',
			'title'         => esc_html__( 'Post Settings', 'zill' ),
			'page'          => array( 'post' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'fields'        => array(
				'_la_bg' => array(
					'type'               => 'media',
					'title'              => esc_html__( 'Background Image', 'zill' ),
					'description'        => esc_html__( 'This option will work on some cases.', 'zill' ),
					'library_type'       => 'image',
					'multi_upload'       => false,
					'value_format'       => 'id',
					'upload_button_text' => esc_html__( 'Set Image', 'zill' ),
				),
				'post_formats' => array(
					'type' => 'component-tab-horizontal',
					'ignore_save'   => true,
				),
				'gallery_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Gallery', 'zill' ),
				),
				'_la_gallery_images' => array(
					'type'               => 'media',
					'parent'             => 'gallery_tab',
					'title'              => esc_html__( 'Image Gallery', 'zill' ),
					'description'        => esc_html__( 'Choose image(s) for the gallery. This setting is used for your gallery post formats.', 'zill' ),
					'library_type'       => 'image',
					'multi_upload'       => true,
					'value_format'       => 'id',
					'upload_button_text' => esc_html__( 'Set Gallery Images', 'zill' ),
				),
				'link_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Link', 'zill' ),
				),
				'_la_link_text' => array(
					'type'        => 'text',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link Text', 'zill' ),
					'description' => esc_html__( 'Enter your text. This setting is used for your link post formats.', 'zill' ),
				),
				'_la_link' => array(
					'type'        => 'text',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link URL', 'zill' ),
					'description' => esc_html__( 'Enter your external url. This setting is used for your link post formats.', 'zill' ),
				),
				'_la_link_target' => array(
					'type'        => 'select',
					'parent'      => 'link_tab',
					'title'       => esc_html__( 'Link Target', 'zill' ),
					'description' => esc_html__( 'Choose your target for the url. This setting is used for your link post formats.', 'zill' ),
					'value'       => '_blank',
					'options'     => array(
						'_blank' => esc_html__('Blank', 'zill'),
						'_self'  => esc_html__('Self', 'zill'),
					),
				),
				'quote_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Quote', 'zill' ),
				),
				'_la_quote_text' => array(
					'type'        => 'textarea',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Quote', 'zill' ),
					'description' => esc_html__( 'Enter your quote. This setting is used for your quote post formats.', 'zill' ),
				),
				'_la_quote_cite' => array(
					'type'        => 'text',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Cite', 'zill' ),
					'description' => esc_html__( 'Enter the quote source. This setting is used for your quote post formats.', 'zill' ),
				),
				'_la_quote_color' => array(
					'type'        => 'colorpicker',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Text Color', 'zill' ),
				),
				'_la_quote_bg' => array(
					'type'        => 'colorpicker',
					'parent'      => 'quote_tab',
					'title'       => esc_html__( 'Background Color', 'zill' ),
				),
				'video_audio_tab' => array(
					'type'      => 'settings',
					'ignore_save'   => true,
					'parent'    => 'post_formats',
					'title'     => esc_html__( 'Video & Audio', 'zill' ),
				),
				'_la_video_link' => array(
					'type'        => 'text',
					'parent'      => 'video_audio_tab',
					'title'       => esc_html__( 'Link Video URL', 'zill' ),
					'description' => esc_html__( 'Insert Youtube or Vimeo video link', 'zill' ),
				),
			),
		) );
	}
}

add_action( 'after_setup_theme', 'zill_setup_metaboxes', 6 );
