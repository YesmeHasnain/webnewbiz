<?php

add_action( 'cmb2_admin_init', 'vasia_register_page_metabox' );

function vasia_register_page_metabox() {
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'vasia_page_metabox',
		'title'         => esc_html__( 'Page Options' , 'vasia' ),
		'object_types'  => array( 'page' ), // Post type
	) );

	$cmb_demo->add_field( array(
	    'name'             => esc_html__( 'Select header for this page' , 'vasia' ),
	    'desc'             => esc_html__( 'Default: get header from themeoption' , 'vasia' ),
	    'id'               => 'page_custom_header',
	    'type'             => 'select',
	    'default'          => 'default',
	    'options'          => array(
	        'default'     => __( 'Default', 'vasia' ),
	        '1'           => __( 'Header 1', 'vasia' ),
	        '2'           => __( 'Header 2', 'vasia' ),
	        '3'           => __( 'Header 3', 'vasia' ),
	        '4'           => __( 'Header 4', 'vasia' ),
	        '5'           => __( 'Header 5', 'vasia' ),
	        '6'           => __( 'Header 6', 'vasia' ),
	        '8'           => __( 'Header 8', 'vasia' ),
	        '10'           => __( 'Header 10', 'vasia' ),
	        '11'           => __( 'Header 11', 'vasia' ),
	        '12'           => __( 'Header 12', 'vasia' ),
	        '14'           => __( 'Header 14', 'vasia' ),
	        '15'           => __( 'Header 15', 'vasia' ),
	        '16'           => __( 'Header 16', 'vasia' ),
	   
	    ),
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Disable page title' , 'vasia' ),
	    'desc' => esc_html__( 'Disable page title for this page' , 'vasia' ),
	    'id'   => 'page_custom_title',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Disable breadcrumb' , 'vasia' ),
	    'desc' => esc_html__( 'Disable breadcrumb for this page' , 'vasia' ),
	    'id'   => 'page_custom_breadcrumb',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name'    => esc_html__( 'Image for page title' , 'vasia' ),
	    'desc'    => esc_html__( 'Upload an image.' , 'vasia' ),
	    'id'      => 'page_custom_title_image',
	    'type'    => 'file',
	    // Optional:
	    'options' => array(
	        'url' => false, // Hide the text input for the url
	    ),
	    'text'    => array(
	        'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
	    ),
	    // query_args are passed to wp.media's library query.
	    'query_args' => array(
	        'type' => array(
	            'image/gif',
	            'image/jpeg',
	            'image/png',
	        ),
	    ),
	    'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );

}


add_action( 'cmb2_admin_init', 'vasia_register_post_metabox' );

function vasia_register_post_metabox() {
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'rt_post_metabox',
		'title'         => esc_html__( 'Post Options', 'vasia' ),
		'object_types'  => array( 'post' ), // Post type
	) );

	$cmb_demo->add_field( array(
	    'name'             => esc_html__( 'Select header for this page', 'vasia' ),
	    'desc'             => esc_html__( 'Default: get header from themeoption', 'vasia' ),
	    'id'               => 'page_custom_header',
	    'type'             => 'select',
	    'default'          => 'default',
	    'options'          => array(
	        'default'     => __( 'Default', 'vasia' ),
	        '1'           => __( 'Header 1', 'vasia' ),
	        '2'           => __( 'Header 2', 'vasia' ),
	        '3'           => __( 'Header 3', 'vasia' ),
	        '4'           => __( 'Header 4', 'vasia' ),
	        '5'           => __( 'Header 5', 'vasia' ),
	        '6'           => __( 'Header 6', 'vasia' ),
	        '8'           => __( 'Header 8', 'vasia' ),
	        '10'           => __( 'Header 10', 'vasia' ),
	        '11'           => __( 'Header 11', 'vasia' ),
	        '12'           => __( 'Header 12', 'vasia' ),
	        '14'           => __( 'Header 14', 'vasia' ),
	        '15'           => __( 'Header 15', 'vasia' ),
	        '16'           => __( 'Header 16', 'vasia' ),
	        
	    ),
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Hide the post title', 'vasia' ),
	    'desc' => esc_html__( 'The post title will be hidden in single post page.', 'vasia' ),
	    'id'   => 'post_hide_title',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Hide the featured image', 'vasia' ),
	    'desc' => esc_html__( 'The post featured image will be hidden in single post page.', 'vasia' ),
	    'id'   => 'post_hide_featured_image',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name'    => esc_html__( 'Image for page title', 'vasia' ),
	    'desc'    => esc_html__( 'Upload an image.', 'vasia' ),
	    'id'      => 'page_custom_title_image',
	    'type'    => 'file',
	    // Optional:
	    'options' => array(
	        'url' => false, // Hide the text input for the url
	    ),
	    'text'    => array(
	        'add_upload_file_text' => esc_html__( 'Add File', 'vasia' ) // Change upload button text. Default: "Add or Upload File"
	    ),
	    // query_args are passed to wp.media's library query.
	    'query_args' => array(
	        'type' => array(
	            'image/gif',
	            'image/jpeg',
	            'image/png',
	        ),
	    ),
	    'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );

}