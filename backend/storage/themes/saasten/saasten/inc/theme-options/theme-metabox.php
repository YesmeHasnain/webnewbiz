<?php
/*
 * Theme Metabox
 * @package Saasten
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
    exit(); // exit if access directly
}

if ( class_exists('CSF') ){

    $prefix = 'saasten';

	/*-------------------------------------
		Category Taxonomy Options
	-------------------------------------*/
	
	
// Create taxonomy options
  CSF::createTaxonomyOptions( $prefix, array(
	'title'     => esc_html__('Catgeory Options','saasten'),
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
	  'title'       => esc_html__('Select Category Color','saasten'),
	  'default' => '#0073FF',
	  	  
	),
	
	array(
	
	  'id'          => 'catbg-color',
	  'type'        => 'color',
	  'title'       => esc_html__('Select Category Background Color','saasten'),
	  'default' => '#0073ff1a',
	  	  
	),

	


	array(
	  'id'    => 'cat-bg',
	  'type'  => 'upload',
	  'title' => esc_html__('Upload','saasten'),
	),

	   array(
		'id' => 'saasten_cat_layout',
		'type' => 'image_select',
		'title' => esc_html__('Select Category Layout','saasten'),
		'options' => array(
			'catt-one' => SAASTEN_IMG . '/admin/page/style1.png',
			'catt-two' => SAASTEN_IMG . '/admin/page/style2.png',
		),
		'default' => 'catt-one'
	),

    )
  ) );
	
	
	/*-------------------------------------
		Post Format Options
	-------------------------------------*/
	CSF::createMetabox('theme_postvideo_options',array(
		'title' => esc_html__('Video Post Format Options','saasten'),
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
				'title' => esc_html__('Upload Video For Post','saasten'),
				'desc' => esc_html__('Upload Video Post','saasten'),
			)
		)
	));

	
	/*-------------------------------------
       Page Options
   -------------------------------------*/
   	  $post_metabox = 'saasten_post_meta';
	  
	  CSF::createMetabox( $post_metabox, array(
	    'title'     => esc_html__('Page Options','saasten'),
	    'post_type' => 'page',
	  ) );

	  //
	  // Create a section
	  CSF::createSection( $post_metabox, array(
	    'title'  => 'Nav Menu Option',
	    'fields' => array(
	     array(
                'type'    => 'subheading',
                'content' => esc_html__('Nav Menu Option','saasten'),
	       ),
	      //
		
		array(
            'id' => 'nav_menu',
            'type' => 'image_select',
            'title' => esc_html__('Select Header Navigation Style','saasten'),
            'options' => array(
                'nav-style-one' => SAASTEN_IMG . '/admin/header-style/style1.png',
                'nav-style-two' => SAASTEN_IMG . '/admin/header-style/style2.png',
            ),
            'default' => 'nav-style-one'
        ),
		
		
		array(
			'id' => 'page_title_enable',
			'title' => esc_html__('Show Page Title','saasten'),
			'type' => 'switcher',
			'default' => true,
			'desc' => esc_html__('Show Page Title Bar', 'saasten') ,
		),
		
		
		array(
			'id' => 'page-spacing-padding',
			'type' => 'spacing',
			'title' => esc_html__('Theme Page Spacing', 'saasten') ,
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
		
		
		
		
		

	    )
	  ) );	
	  
	/*-------------------------------------
       Post Options
   -------------------------------------*/
   	  $single_blog_metabox = 'saasten_blog_post_meta';
	  
	  CSF::createMetabox( $single_blog_metabox, array(
	    'title'     => esc_html__('Post Options', 'saasten') ,
	    'post_type' => 'post',
	  ) );

	  //
	  // Create a section
	  CSF::createSection( $single_blog_metabox, array(
	    'title'  => esc_html__('Single Post Layout Option', 'saasten') ,
	    'fields' => array(
	     array(
                'type'    => 'subheading',
                'content' => esc_html__('Single Post Layout Option','saasten'),
	       ),
	      //
		
		array(
				'id' => 'saasten_single_blog_layout',
				'type' => 'image_select',
				'title' => esc_html__('Select Single Blog Style','saasten'),
				'options' => array(
					'single-one' => SAASTEN_IMG . '/admin/page/blog-1.png',
					'single-two' => SAASTEN_IMG . '/admin/page/blog-2.png',
				),
				'default' => 'single-one'
			),
		

	    )
	  ) );	
	  





}//endif