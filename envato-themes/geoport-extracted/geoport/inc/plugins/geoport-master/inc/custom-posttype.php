<?php

/*------------------------------------------------------------------------------------------------------------------*/
/*  Services Custom Post
/*------------------------------------------------------------------------------------------------------------------*/

function service_init() {
    $service_gutenberg = geoport_get_option('geoport_service_gutenberg');
    if ( $service_gutenberg == 1 ) {
        $guten = true;
    } else {
       $guten = false; 
    }
    $labels = array(
        'name'               => _x( 'Services', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Service', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Services', 'geoport' ),
        'name_admin_bar'     => __( 'Service', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Service', 'geoport' ),
        'new_item'           => __( 'New Service', 'geoport' ),
        'edit_item'          => __( 'Edit Service', 'geoport' ),
        'view_item'          => __( 'View Service', 'geoport' ),
        'all_items'          => __( 'All Services', 'geoport' ),
        'search_items'       => __( 'Search Services', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Services:', 'geoport' ),
        'not_found'          => __( 'No Service found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Service found in Trash.', 'geoport' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'taxonomies'         => array( 'post_tag' ),
        'menu_icon'          => 'dashicons-admin-post',
        'show_in_rest'       => $guten,
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );

  register_post_type('service', $args);
}
add_action('init', 'service_init');

/* Service Texonomy
=====================================================*/

function create_service_taxonomies() {
  $labels = array(
    'name'          => _x( 'Service Category', 'Taxonomy plural name', 'geoport' ),
    'singular_name'     => _x( 'Service Category', 'Taxonomy singular name', 'geoport' ),
    'search_items'      => __( 'Search Service Category', 'geoport' ),
    'popular_items'     => __( 'Popular Service Category', 'geoport' ),
    'all_items'       => __( 'All Categories', 'geoport' ),
    'parent_item'     => __( 'Parent Service Category', 'geoport' ),
    'parent_item_colon'   => __( 'Parent Service Category', 'geoport' ),
    'edit_item'       => __( 'Edit Service Category', 'geoport' ),
    'update_item'     => __( 'Update Service Category', 'geoport' ),
    'add_new_item'      => __( 'Add New Category', 'geoport' ),
    'new_item_name'     => __( 'New Service Menu Name', 'geoport' ),
    'add_or_remove_items' => __( 'Add or remove Service Category', 'geoport' ),
    'choose_from_most_used' => __( 'Choose from most used text-domain', 'geoport' ),
    'menu_name'       => __( 'Category', 'geoport' ),
  );

  $args = array(
    'labels'            => $labels,
    'public'            => true,
    'show_in_nav_menus' => true,
    'show_admin_column' => true,
    'hierarchical'      => true,
    'show_tagcloud'     => true,
    'show_ui'           => true,
    'query_var'         => true,
    'rewrite'           => true,
    'query_var'         => true,
    'capabilities'      => array(),
  );

  register_taxonomy( 'service_category', array( 'service' ), $args );
}
add_action( 'init', 'create_service_taxonomies' );

function update_service_post_slug( $args, $post_type ) {
    $service_post_slug = geoport_get_option('service_post_slug');
    if ( 'service' === $post_type ) {
        $my_args = array(
            'rewrite' => array( 'slug' => $service_post_slug, 'with_front' => false )
        );
        return array_merge( $args, $my_args );
    }
    return $args;
}
add_filter( 'register_post_type_args', 'update_service_post_slug', 10, 2 );

/*--------------------------------------------------------------------------------------------------------------*/
/*  Testimonial Custom Post
/*--------------------------------------------------------------------------------------------------------------*/
function testimonial_init() {

    $testi_gutenberg = geoport_get_option('geoport_testimonial_gutenberg');
    if ( $testi_gutenberg == 1 ) {
        $testiguten = true;
    } else {
       $testiguten = false; 
    }

    $labels = array(
        'name'               => _x( 'Testimonial', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Testimonial', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Testimonial', 'geoport' ),
        'name_admin_bar'     => __( 'Testimonial', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Testimonial', 'geoport' ),
        'new_item'           => __( 'New Testimonial', 'geoport' ),
        'edit_item'          => __( 'Edit Testimonial', 'geoport' ),
        'view_item'          => __( 'View Testimonial', 'geoport' ),
        'all_items'          => __( 'All Testimonials', 'geoport' ),
        'search_items'       => __( 'Search Testimonial', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Testimonial:', 'geoport' ),
        'not_found'          => __( 'No Testimonial found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Testimonial found in Trash.', 'geoport' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'taxonomies'         => array( 'post_tag' ),
        'menu_icon'          => 'dashicons-admin-post',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => $testiguten,
    );

  register_post_type( 'testimonial', $args );
}
add_action( 'init', 'testimonial_init' );

/*--------------------------------------------------------------------------------------------------------------*/
/*  Contact Tab
/*--------------------------------------------------------------------------------------------------------------*/
function contact_tab_init() {

    $contact_tab_gutenberg = geoport_get_option( 'geoport_contact_tab_gutenberg' );
    if ( $contact_tab_gutenberg == 1 ) {
        $testiguten = true;
    } else {
       $testiguten = false;
    }

    $labels = array(
        'name'               => _x( 'Contact Tab', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Contact Tab', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Contact Tab', 'geoport' ),
        'name_admin_bar'     => __( 'Contact Tab', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Contact Tab', 'geoport' ),
        'new_item'           => __( 'New Contact Tab', 'geoport' ),
        'edit_item'          => __( 'Edit Contact Tab', 'geoport' ),
        'view_item'          => __( 'View Contact Tab', 'geoport' ),
        'all_items'          => __( 'All Contact Tabs', 'geoport' ),
        'search_items'       => __( 'Search Contact Tab', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Contact Tab:', 'geoport' ),
        'not_found'          => __( 'No Contact Tab found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Contact Tab found in Trash.', 'geoport' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'taxonomies'         => array( 'post_tag' ),
        'menu_icon'          => 'dashicons-admin-post',
        'supports'           => array( 'title', 'thumbnail' ),
        'show_in_rest'       => $testiguten,
    );

    register_post_type( 'contact_tab', $args );
}
add_action( 'init', 'contact_tab_init' );

/*------------------------------------------------------------------------------------------------------------------*/
/*  Team Custom Post
/*------------------------------------------------------------------------------------------------------------------*/
function team_init() {

    $team_gutenberg = geoport_get_option('geoport_team_gutenberg');
    if ( $team_gutenberg == 1 ) {
        $teamguten = true;
    } else {
       $teamguten = false; 
    }

    $labels = array(
        'name'               => _x( 'Team', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Team', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Team', 'geoport' ),
        'name_admin_bar'     => __( 'Team', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Team', 'geoport' ),
        'new_item'           => __( 'New Team', 'geoport' ),
        'edit_item'          => __( 'Edit Team', 'geoport' ),
        'view_item'          => __( 'View Team', 'geoport' ),
        'all_items'          => __( 'All Teams', 'geoport' ),
        'search_items'       => __( 'Search Team', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Team:', 'geoport' ),
        'not_found'          => __( 'No Team found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Team found in Trash.', 'geoport' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-post',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'       => $teamguten,
    );

  register_post_type('team', $args);
}
add_action('init', 'team_init');

function update_team_post_slug( $args, $post_type ) {
    $team_post_slug = geoport_get_option('team_post_slug');
    if ( 'team' === $post_type ) {
        $my_args = array(
            'rewrite' => array( 'slug' => $team_post_slug, 'with_front' => false )
        );
        return array_merge( $args, $my_args );
    }
    return $args;
}
add_filter( 'register_post_type_args', 'update_team_post_slug', 10, 2 );

/*------------------------------------------------------------------------------------------------------------------*/
/*  Our Journey Post
/*------------------------------------------------------------------------------------------------------------------*/

function journey_init() {
    $journey_gutenberg = geoport_get_option('geoport_journey_gutenberg');
    if ( $journey_gutenberg == 1 ) {
        $guten = true;
    } else {
       $guten = false; 
    }
    $labels = array(
        'name'               => _x( 'Journeys', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Journey', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Journeys', 'geoport' ),
        'name_admin_bar'     => __( 'Journey', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Journey', 'geoport' ),
        'new_item'           => __( 'New Journey', 'geoport' ),
        'edit_item'          => __( 'Edit Journey', 'geoport' ),
        'view_item'          => __( 'View Journey', 'geoport' ),
        'all_items'          => __( 'All Journeys', 'geoport' ),
        'search_items'       => __( 'Search Journeys', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Journeys:', 'geoport' ),
        'not_found'          => __( 'No Service found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Service found in Trash.', 'geoport' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-post',
        'show_in_rest'       => $guten,
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );

  register_post_type('journey', $args);
}
add_action('init', 'journey_init');

/*----------------------------------------------------------------------------------------------------*/ 
/*  Job List Custom Post
/*----------------------------------------------------------------------------------------------------*/ 
function geoport_job_list_init() {

    $labels = array(
        'name'               => _x( 'Job List', 'post type general name', 'geoport' ),
        'singular_name'      => _x( 'Job List', 'post type singular name', 'geoport' ),
        'menu_name'          => __( 'Job List', 'geoport' ),
        'name_admin_bar'     => __( 'Job List', 'geoport' ),
        'add_new'            => __( 'Add New', 'geoport' ),
        'add_new_item'       => __( 'Add New Job List', 'geoport' ),
        'new_item'           => __( 'New Job List', 'geoport' ),
        'edit_item'          => __( 'Edit Job List', 'geoport' ),
        'view_item'          => __( 'View Job List', 'geoport' ),
        'all_items'          => __( 'All Job Lists', 'geoport' ),
        'search_items'       => __( 'Search Job List', 'geoport' ),
        'parent_item_colon'  => __( 'Parent Job List:', 'geoport' ),
        'not_found'          => __( 'No Job List found.', 'geoport' ),
        'not_found_in_trash' => __( 'No Job List found in Trash.', 'geoport' )
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-editor-justify',
        'supports'           => array( 'title' ),
    );
    register_post_type('geoport_job_list', $args);
}
add_action('init', 'geoport_job_list_init');