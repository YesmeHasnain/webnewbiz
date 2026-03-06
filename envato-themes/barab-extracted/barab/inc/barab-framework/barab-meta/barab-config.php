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
function barab_set_checkbox_default_for_new_post( $default ) {
	return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}

add_action( 'cmb2_admin_init', 'barab_register_metabox' );

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */

function barab_register_metabox() {

	$prefix = '_barab_';

	$prefixpage = '_barabpage_';
	
	$barab_post_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'blog_post_control',
		'title'         => esc_html__( 'Post Thumb Controller', 'barab' ),
		'object_types'  => array( 'post' ), // Post type
		'closed'        => true
	) );

    $barab_post_meta->add_field( array(
        'name' => esc_html__( 'Post Format Video', 'barab' ),
        'desc' => esc_html__( 'Use This Field When Post Format Video', 'barab' ),
        'id'   => $prefix . 'post_format_video',
        'type' => 'text_url',
    ) );

	$barab_post_meta->add_field( array(
		'name' => esc_html__( 'Post Format Audio', 'barab' ),
		'desc' => esc_html__( 'Use This Field When Post Format Audio', 'barab' ),
		'id'   => $prefix . 'post_format_audio',
        'type' => 'oembed',
    ) );
	$barab_post_meta->add_field( array(
		'name' => esc_html__( 'Post Thumbnail For Slider', 'barab' ),
		'desc' => esc_html__( 'Use This Field When You Want A Slider In Post Thumbnail', 'barab' ),
		'id'   => $prefix . 'post_format_slider',
        'type' => 'file_list',
    ) );
	
	$barab_page_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_meta_section',
		'title'         => esc_html__( 'Page Meta', 'barab' ),
		'object_types'  => array( 'page', 'barab_event' ), // Post type
        'closed'        => true
    ) );

    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Area', 'barab' ),
		'desc' => esc_html__( 'check to display page breadcrumb area.', 'barab' ),
		'id'   => $prefix . 'page_breadcrumb_area',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','barab'),
            '2'     => esc_html__('Hide','barab'),
        )
    ) );


    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Page Breadcrumb Settings', 'barab' ),
		'id'   => $prefix . 'page_breadcrumb_settings',
        'type' => 'select',
        'default'   => 'global',
        'options'   => array(
            'global'   => esc_html__('Global Settings','barab'),
            'page'     => esc_html__('Page Settings','barab'),
        )
	) );

    $barab_page_meta->add_field( array(
        'name'    => esc_html__( 'Breadcumb Image', 'barab' ),
        'desc'    => esc_html__( 'Upload an image or enter an URL.', 'barab' ),
        'id'      => $prefix . 'breadcumb_image',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => __( 'Add File', 'barab' ) // Change upload button text. Default: "Add or Upload File"
        ),
        'preview_size' => 'large', // Image size to use when previewing in the admin.
    ) );

    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title', 'barab' ),
		'desc' => esc_html__( 'check to display Page Title.', 'barab' ),
		'id'   => $prefix . 'page_title',
        'type' => 'select',
        'default' => '1',
        'options'   => array(
            '1'   => esc_html__('Show','barab'),
            '2'     => esc_html__('Hide','barab'),
        )
	) );

    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Page Title Settings', 'barab' ),
		'id'   => $prefix . 'page_title_settings',
        'type' => 'select',
        'options'   => array(
            'default'  => esc_html__('Default Title','barab'),
            'custom'  => esc_html__('Custom Title','barab'),
        ),
        'default'   => 'default'
    ) );

    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Custom Page Title', 'barab' ),
		'id'   => $prefix . 'custom_page_title',
        'type' => 'text'
    ) );

    $barab_page_meta->add_field( array(
		'name' => esc_html__( 'Breadcrumb', 'barab' ),
		'desc' => esc_html__( 'Select Show to display breadcrumb area', 'barab' ),
		'id'   => $prefix . 'page_breadcrumb_trigger',
        'type' => 'switch_btn',
        'default' => barab_set_checkbox_default_for_new_post( true ),
    ) );

    $barab_layout_meta = new_cmb2_box( array(
		'id'            => $prefixpage . 'page_layout_section',
		'title'         => esc_html__( 'Page Layout', 'barab' ),
        'context' 		=> 'side',
        'priority' 		=> 'high',
        'object_types'  => array( 'page' ), // Post type
        'closed'        => true
	) );

	$barab_layout_meta->add_field( array(
		'desc'       => esc_html__( 'Set page layout container,container fluid,fullwidth or both. It\'s work only in template builder page.', 'barab' ),
		'id'         => $prefix . 'custom_page_layout',
		'type'       => 'radio',
        'options' => array(
            '1' => esc_html__( 'Container', 'barab' ),
            '2' => esc_html__( 'Container Fluid', 'barab' ),
            '3' => esc_html__( 'Fullwidth', 'barab' ),
        ),
	) );

	// code for body class//

    $barab_layout_meta->add_field( array(
	'name' => esc_html__( 'Insert Your Body Class', 'barab' ),
	'id'   => $prefix . 'custom_body_class',
	'type' => 'text'
    ) );

    $barab_product_meta = new_cmb2_box( array(
        'id'            => $prefixpage . 'product_meta_section_extra_description',
        'title'         => esc_html__( 'Product Extra Description Area', 'barab' ),
        'object_types'  => array( 'product' ), // Post type
        'closed'        => true,
        'context'       => 'side',
        'priority'      => 'default'
    ) );

    $barab_product_meta->add_field( array(
        'name' => esc_html__( 'Extra Description', 'barab' ),
        'id'   => $prefix . 'extra_description',
        'type' => 'textarea'
    ) );

}

add_action( 'cmb2_admin_init', 'barab_register_taxonomy_metabox' );
/**
 * Hook in and add a metabox to add fields to taxonomy terms
 */
function barab_register_taxonomy_metabox() {

    $prefix = '_barab_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$barab_term_meta = new_cmb2_box( array(
		'id'               => $prefix.'term_edit',
		'title'            => esc_html__( 'Category Metabox', 'barab' ),
		'object_types'     => array( 'term' ),
		'taxonomies'       => array( 'category'),
	) );
	$barab_term_meta->add_field( array(
		'name'     => esc_html__( 'Extra Info', 'barab' ),
		'id'       => $prefix.'term_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$barab_term_meta->add_field( array(
		'name' => esc_html__( 'Category Icon', 'barab' ),
		'desc' => esc_html__( 'Set Category Icon', 'barab' ),
		'id'   => $prefix.'term_avatar',
        'type' => 'file',
        'text'    => array(
			'add_upload_file_text' => esc_html__('Add Icon','barab') // Change upload button text. Default: "Add or Upload File"
		),
	) );


	/**
	 * Metabox for the user profile screen
	 */
	$barab_user = new_cmb2_box( array(
		'id'               => $prefix.'user_edit',
		'title'            => esc_html__( 'User Profile Metabox', 'barab' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta as post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
    $barab_user->add_field( array(
		'name' => esc_html__( 'Author Designation', 'barab' ),
		'desc' => esc_html__( 'Use This Field When Author Designation', 'barab' ),
		'id'   => $prefix . 'author_desig',
        'type' => 'text',
    ) );
	$barab_user->add_field( array(
		'name'     => esc_html__( 'Social Profile', 'barab' ),
		'id'       => $prefix.'user_extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );

	$group_field_id = $barab_user->add_field( array(
        'id'          => $prefix .'social_profile_group',
        'type'        => 'group',
        'description' => __( 'Social Profile', 'barab' ),
        'options'     => array(
            'group_title'       => __( 'Social Profile {#}', 'barab' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Social Profile', 'barab' ),
            'remove_button'     => __( 'Remove Social Profile', 'barab' ),
            'closed'         => true
        ),
    ) );

    $barab_user->add_group_field( $group_field_id, array(
        'name'        => __( 'Icon Class', 'barab' ),
        'id'          => $prefix .'social_profile_icon',
        'type'        => 'text', // This field type
    ) );

    $barab_user->add_group_field( $group_field_id, array(
        'desc'       => esc_html__( 'Set social profile link.', 'barab' ),
        'id'         => $prefix . 'lawyer_social_profile_link',
        'name'       => esc_html__( 'Social Profile link', 'barab' ),
        'type'       => 'text'
    ) );
}
