<?php
// Video Post Meta
$xcency_video_post_meta = 'xcency_video_post_format_meta';

CSF::createMetabox( $xcency_video_post_meta, array(
	'title'        => esc_html__('Video Post Format Options', 'xcency' ),
	'post_type'    => 'post',
	'post_formats' => array('video'),
) );

CSF::createSection( $xcency_video_post_meta, array(
	'fields' => array(

		array(
			'id'    => 'post_video_url',
			'type'  => 'text',
			'title' => esc_html__('Video URL', 'xcency' ),
			'desc'    => esc_html__( 'Paste video URL here', 'xcency' ),
		),

	)
));

// Audio Post Meta
$xcency_audio_post_meta = 'audio_post_format_meta';

CSF::createMetabox( $xcency_audio_post_meta, array(
	'title'        => esc_html__('Audio Post Format Options', 'xcency' ),
	'post_type'    => 'post',
	'post_formats' => array('audio'),
) );

CSF::createSection( $xcency_audio_post_meta, array(
	'fields' => array(

		array(
			'id'    => 'audio_embed_code',
			'type'  => 'code_editor',
			'settings' => array(
				'theme'  => 'monokai',
				'mode'   => 'htmlmixed',
			),
			'title' => esc_html__('Audio Embed Code', 'xcency' ),
			'desc'    => esc_html__( 'Paste sound cloud audio embed code here', 'xcency' ),
		),

	)
));


// Gallery Post Meta
$xcency_gallery_post_meta = 'gallery_post_format_meta';

CSF::createMetabox( $xcency_gallery_post_meta, array(
	'title'        => esc_html__('Gallery Post Format Options', 'xcency' ),
	'post_type'    => 'post',
	'post_formats' => array('gallery'),
) );

CSF::createSection( $xcency_gallery_post_meta, array(
	'fields' => array(

		array(
			'id'          => 'post_gallery_images',
			'type'        => 'gallery',
			'title' => esc_html__('Gallery Images', 'xcency' ),
			'add_title'   => esc_html__('Upload Gallery Images', 'xcency'),
			'edit_title'  => esc_html__('Edit Gallery Images', 'xcency'),
			'clear_title' => esc_html__('Remove Gallery Images', 'xcency'),
			'desc'    => esc_html__( 'Upload gallery images from here', 'xcency' ),
		),

	)
));