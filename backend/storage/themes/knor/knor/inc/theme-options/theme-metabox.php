<?php
/*
 * Theme Metabox
 * @package Knor
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
    exit(); // exit if access directly
}

if ( class_exists('CSF') ){

    $prefix = 'knor';

	/*-------------------------------------
		Category Taxonomy Options
	-------------------------------------*/
	
	
// Create taxonomy options
  CSF::createTaxonomyOptions( $prefix, array(
	'title'     => esc_html__('Catgeory Options','knor'),
    'taxonomy'  => 'category',
    'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
  ) );

  //
  // Create a section
  CSF::createSection( $prefix, array(
    'fields' => array(

	array(
	
	  'id'          => 'cat-color',
	  'type'        => 'color',
	  'title'       => esc_html__('Select Category Color','knor'),
	  'default' => '#0073FF',
	  	  
	),
	
	array(
	
	  'id'          => 'catbg-color',
	  'type'        => 'color',
	  'title'       => esc_html__('Select Category Background Color','knor'),
	  'default' => '#0073ff1a',
	  	  
	),

	


	array(
	  'id'    => 'cat-bg',
	  'type'  => 'upload',
	  'title' => esc_html__('Upload','knor'),
	),

	   array(
		'id' => 'knor_cat_layout',
		'type' => 'image_select',
		'title' => esc_html__('Select Category Layout','knor'),
		'options' => array(
			'catt-one' => KNOR_IMG . '/admin/page/style1.png',
			'catt-two' => KNOR_IMG . '/admin/page/style2.png',
		),
		'default' => 'catt-one'
	),

    )
  ) );
	
	
	/*-------------------------------------
		Post Format Options
	-------------------------------------*/
	CSF::createMetabox('theme_postvideo_options',array(
		'title' => esc_html__('Video Post Format Options','knor'),
		'post_type' => 'post',
		'post_formats' => 'video',
		'data_type'          => 'serialize',
		'context'            => 'advanced',
		'priority'           => 'default',
	));
	
	CSF::createSection('theme_postvideo_options',array(
		'fields' => array(
			array(
				'id' => 'textm',
				'type' => 'text',
				'title' => esc_html__('Upload Video For Post','knor'),
				'desc' => esc_html__('Upload Video Post','knor'),
			)
		)
	));

	

$team_metabox = 'knor_team_meta';


CSF::createMetabox( $team_metabox, array(
    'title'        => esc_html__( 'Team Options', 'knor' ),
    'post_type'    => array( 'theme_team' ),
    'show_restore' => true,
) );

//
// Create a section
//
CSF::createSection( $team_metabox, array(
    'title'  => esc_html__( 'Team Position', 'knor' ),
    'icon'   => 'fas fa-rocket',
    'fields' => array(
        array(
            'id'       => 'team_subtitle',
            'type'     => 'text',
            'title'    => esc_html__( 'Designation', 'knor' ),
            'subtitle' => esc_html__( 'Add Team Designation here', 'knor' ),
            'default'  => esc_html__( 'Full Stack Developer', 'knor' ),
        ),
    ),
) );


	/*-------------------------------------
       Page Options
   -------------------------------------*/
   	  $post_metabox = 'knor_post_meta';
	  
	  CSF::createMetabox( $post_metabox, array(
	    'title'     => esc_html__('Page Options','knor'),
	    'post_type' => 'page',
	  ) );

	  //
	  // Create a section
	  CSF::createSection( $post_metabox, array(
	    'title'  => 'Nav Menu Option',
	    'fields' => array(
	     array(
                'type'    => 'subheading',
                'content' => esc_html__('Nav Menu Option','knor'),
	       ),
	      //
		
		array(
            'id' => 'nav_menu',
            'type' => 'image_select',
            'title' => esc_html__('Select Header Navigation Style','knor'),
            'options' => array(
                'nav-style-one' => KNOR_IMG . '/admin/header-style/style1.png',
                'nav-style-two' => KNOR_IMG . '/admin/header-style/style2.png',
            ),
            'default' => 'nav-style-one'
        ),
		
		
		array(
			'id' => 'page_title_enable',
			'title' => esc_html__('Show Page Title','knor'),
			'type' => 'switcher',
			'default' => true,
			'desc' => esc_html__('Show Page Title Bar', 'knor') ,
		),
		
		
		array(
			'id' => 'page-spacing-padding',
			'type' => 'spacing',
			'title' => esc_html__('Theme Page Spacing', 'knor') ,
			'output' => 'body.page .main-container',
			'output_mode' => 'padding', // or margin, relative
			'default' => array(
				'top' => '80',
				'right' => '0',
				'bottom' => '80',
				'left' => '0',
				'unit' => 'px',
			) ,
		) ,

		array(
            'id'       => 'page_description',
            'type'     => 'text',
            'title'    => esc_html__( 'Page Short ', 'knor' ),
            'subtitle' => esc_html__( 'Insert page short description here', 'knor' ),
            'default'  => esc_html__( 'Page Short Description', 'knor' ),
        ),
		
		
		
		
		

	    )
	  ) );	
	  
	/*-------------------------------------
       Post Options
   -------------------------------------*/
   	  $single_blog_metabox = 'knor_blog_post_meta';
	  
	  CSF::createMetabox( $single_blog_metabox, array(
	    'title'     => esc_html__('Post Options', 'knor') ,
	    'post_type' => 'post',
	  ) );

	  //
	  // Create a section
	  CSF::createSection( $single_blog_metabox, array(
	    'title'  => esc_html__('Single Post Layout Option', 'knor') ,
	    'fields' => array(
	     array(
                'type'    => 'subheading',
                'content' => esc_html__('Single Post Layout Option','knor'),
	       ),
	      //
		
		array(
				'id' => 'knor_single_blog_layout',
				'type' => 'image_select',
				'title' => esc_html__('Select Single Blog Style','knor'),
				'options' => array(
					'single-one' => KNOR_IMG . '/admin/page/blog-1.png',
					'single-two' => KNOR_IMG . '/admin/page/blog-2.png',
				),
				'default' => 'single-one'
			),
		

	    )
	  ) );	
	  





}//endif