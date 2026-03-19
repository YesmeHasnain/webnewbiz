<?php

/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

 /**
 * Only return default value if we don't have a post ID (in the 'post' query variable)
 *
 * @param  bool  $default On/Off (true/false)
 * @return mixed          Returns true or '', the blank default
 */
function solak_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_action( 'cmb2_admin_init', 'solak_register_metabox' );

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */

function solak_register_metabox() {

	$prefix = '_solak_';

	$prefixpage = '_solakpage_';
	
	$solak_post_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'blog_post_control',
		'title'         => esc_html__( 'Post Thumb Controller', 'solak' ),
		'object_types'  => array( 'post' ), // Post type
		'closed'        => true
	) );

    $solak_post_meta->add_field( array(
        'name' => esc_html__( 'Post Format Video', 'solak' ),
        'desc' => esc_html__( 'Use This Field When Post Format Video', 'solak' ),
        'id'   => $prefix . 'post_format_video',
        'type' => 'text_url',
    ) );

	$solak_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Audio', 'solak' ),
		'desc' => esc_html__( 'Use This Field When Post Format Audio', 'solak' ),
		'id'   => $prefix . 'post_format_audio',
        'type' => 'oembed',
    ) );
	$solak_post_meta->add_field( array(
		'name' => esc_html__( 'Post Thumbnail For Slider', 'solak' ),
		'desc' => esc_html__( 'Use This Field When You Want A Slider In Post Thumbnail', 'solak' ),
		'id'   => $prefix . 'post_format_slider',
        'type' => 'file_list',
    ) );
	
	$solak_page_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_meta_section',
		'title'         => esc_html__( 'Page Meta', 'solak' ),
		'object_types'  => array( 'page', 'solak_event' ), // Post type
        'closed'        => true
    ) );

    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Area', 'solak' ),
		'desc' => esc_html__( 'check to display page breadcrumb area.', 'solak' ),
		'id'   => $prefix . 'page_breadcrumb_area',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','solak'),
            '2'     => esc_html__('Hide','solak'),
        )
    ) );


    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Settings', 'solak' ),
		'id'   => $prefix . 'page_breadcrumb_settings',
        'type' => 'select',
        'default'   => 'global',
        'options'   => array(
            'global'   => esc_html__('Global Settings','solak'),
            'page'     => esc_html__('Page Settings','solak'),
        )
	) );

    $solak_page_meta->add_field( array(
        'name'    => esc_html__( 'Breadcumb Image', 'solak' ),
        'desc'    => esc_html__( 'Upload an image or enter an URL.', 'solak' ),
        'id'      => $prefix . 'breadcumb_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => __( 'Add File', 'solak' ) // Change upload button text. Default: "Add or Upload File"
        ),
        'preview_size' => 'large', // Image size to use when previewing in the admin.
    ) );

    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title', 'solak' ),
		'desc' => esc_html__( 'check to display Page Title.', 'solak' ),
		'id'   => $prefix . 'page_title',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','solak'),
            '2'     => esc_html__('Hide','solak'),
        )
	) );

    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title Settings', 'solak' ),
		'id'   => $prefix . 'page_title_settings',
        'type' => 'select',
        'options'   => array(
            'default'  => esc_html__('Default Title','solak'),
            'custom'  => esc_html__('Custom Title','solak'),
        ),
        'default'   => 'default'
    ) );

    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Custom Page Title', 'solak' ),
		'id'   => $prefix . 'custom_page_title',
        'type' => 'text'
    ) );

    $solak_page_meta->add_field( array(
		'name' => esc_html__( 'Breadcrumb', 'solak' ),
		'desc' => esc_html__( 'Select Show to display breadcrumb area', 'solak' ),
		'id'   => $prefix . 'page_breadcrumb_trigger',
        'type' => 'switch_btn',
        'default' => solak_set_checkbox_default_for_new_post( true ),
    ) );

    $solak_layout_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_layout_section',
		'title'         => esc_html__( 'Page Layout', 'solak' ),
        'context' 		=> 'side',
        'priority' 		=> 'high',
        'object_types'  => array( 'page' ), // Post type
        'closed'        => true
	) );

	$solak_layout_meta->add_field( array(
		'desc'       => esc_html__( 'Set page layout container,container fluid,fullwidth or both. It\'s work only in template builder page.', 'solak' ),
		'id'         => $prefix . 'custom_page_layout',
		'type'       => 'radio',
        'options' => array(
            '1' => esc_html__( 'Container', 'solak' ),
            '2' => esc_html__( 'Container Fluid', 'solak' ),
            '3' => esc_html__( 'Fullwidth', 'solak' ),
        ),
	) );

	// code for body class//

    $solak_layout_meta->add_field( array(
	'name' => esc_html__( 'Insert Your Body Class', 'solak' ),
	'id'   => $prefix . 'custom_body_class',
	'type' => 'text'
    ) );

}

add_action( 'cmb2_admin_init', 'solak_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function solak_register_taxonomy_metabox() {

    $prefix = '_solak_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$solak_term_meta = new_cmb2_box( array(
		'id'               => $prefix.'term_edit',
		'title'            => esc_html__( 'Category Metabox', 'solak' ),
		'object_types'     => array( 'term' ),
		'taxonomies'       => array( 'category'),
	) );
	$solak_term_meta->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'solak' ),
		'id'       => $prefix.'term_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$solak_term_meta->add_field( array(
		'name' => esc_html__( 'Category Image', 'solak' ),
		'desc' => esc_html__( 'Set Category Image', 'solak' ),
		'id'   => $prefix.'term_avatar',
        'type' => 'file',
        'text'    => array(
			'add_upload_file_text' => esc_html__('Add Image','solak') // Change upload button text. Default: "Add or Upload File"
		),
	) );


	/**
	 * Metabox for the user profile screen
	 */
	$solak_user = new_cmb2_box( array(
		'id'               => $prefix.'user_edit',
		'title'            => esc_html__( 'User Profile Metabox', 'solak' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta as post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
    $solak_user->add_field( array(
		'name' => esc_html__( 'Author Designation', 'solak' ),
		'desc' => esc_html__( 'Use This Field When Author Designation', 'solak' ),
		'id'   => $prefix . 'author_desig',
        'type' => 'text',
    ) );
	$solak_user->add_field( array(
		'name'     => esc_html__( 'Social Profile', 'solak' ),
		'id'       => $prefix.'user_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );

	$group_field_id = $solak_user->add_field( array(
        'id'          => $prefix .'social_profile_group',
        'type'        => 'group',
        'description' => __( 'Social Profile', 'solak' ),
        'options'     => array(
            'group_title'       => __( 'Social Profile {#}', 'solak' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Social Profile', 'solak' ),
            'remove_button'     => __( 'Remove Social Profile', 'solak' ),
            'closed'         => true
        ),
    ) );

    $solak_user->add_group_field( $group_field_id, array(
        'name'        => __( 'Icon Class', 'solak' ),
        'id'          => $prefix .'social_profile_icon',
        'type'        => 'text', // This field type
    ) );

    $solak_user->add_group_field( $group_field_id, array(
        'desc'       => esc_html__( 'Set social profile link.', 'solak' ),
        'id'         => $prefix . 'lawyer_social_profile_link',
        'name'       => esc_html__( 'Social Profile link', 'solak' ),
        'type'       => 'text'
    ) );
}
