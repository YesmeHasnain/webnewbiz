<?php

$args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 1,
    'exclude' => '',
    'include' => '',
    'meta_key' => '',
    'meta_value' => '',
    'authors' => '',
    'child_of' => 0,
    'parent' => -1,
    'exclude_tree' => '',
    'number' => '',
    'offset' => 0,
    'post_type' => 'page',
    'post_status' => 'publish'
); 
$pages = get_pages($args);
$page_array = array();
$page_array[''] = esc_html__( 'None', 'vasia' );
foreach($pages as $item){
	$page_array[$item->ID] = $item->post_title;
}

Kirki::add_section( 'header_account', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Account', 'vasia' ),
    'panel'       => 'header',
) );

Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'he_account_design',
	'label'       => esc_html__( 'Select account design', 'vasia' ),
	'section'     => 'header_account',
	'default'     => 'both-inline',
	'choices'     => [
		'only-icon'   => get_template_directory_uri() . '/assets/images/customizer/account-1.jpg',	
		'both-ver'   => get_template_directory_uri() . '/assets/images/customizer/account-2.jpg',	
		'only-text'   => get_template_directory_uri() . '/assets/images/customizer/account-3.jpg',	
		'both-inline'   => get_template_directory_uri() . '/assets/images/customizer/account-4.jpg',
	],
	'transport' => 'postMessage'
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'he_account_popup_custom',
	'section'     => 'header_account',
	'default'         => '<div class="customize-title-divider">' . __( 'Login/Register popup', 'vasia' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'he_account_popup',
	'label'       => esc_html__( 'Active login/register popup', 'vasia' ),
	'section'     => 'header_account',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'vasia' ),
		'off' => esc_html__( 'No', 'vasia' ),
	],
] );

Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'he_account_reg_redirect',
	'label'       => esc_html__( 'Redirect after register', 'vasia' ),
	'section'     => 'header_account',
	'default'     => '',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => $page_array,
] );