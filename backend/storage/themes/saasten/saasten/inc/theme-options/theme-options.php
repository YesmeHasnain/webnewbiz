<?php
/*
 * Theme Options
 * @package Saasten
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
    exit(); // exit if access directly
}

if( class_exists( 'CSF' ) ) {

  //
  // Set a unique slug-like ID
  $prefix = 'saasten';

  //
  // Create options
  CSF::createOptions( $prefix.'_theme_options', array(
    'menu_title' => esc_html__('Theme Option','saasten'),
    'menu_slug'  => 'saasten-theme-option',
    'menu_type' => 'menu',
    'enqueue_webfont'         => true,
    'show_footer' => false,
    'framework_title'      => esc_html__('Saasten Theme Options','saasten'),
  ) );

  //
  // Create a section
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('General','saasten'),
    'icon'  => 'fa fa-wrench',
    'fields' => array(

		array(
			'type' => 'subheading',
			'content' => '<h3>' . esc_html__('Site Logo', 'saasten') . '</h3>',
		) ,
			
		array(
			'id' => 'theme_logo',
			'title' => esc_html__('Main Logo','saasten'),
			'type' => 'media',
			'library' => 'image',
			'desc' => esc_html__('Upload Your Static Logo Image on Header Static', 'saasten')
		), 


		array(
			'id' => 'theme_logo_mobile',
			'title' => esc_html__('Main Logo for Small Devices','saasten'),
			'type' => 'media',
			'library' => 'image',
			'desc' => esc_html__('Upload Your Static Logo Image on Header Static', 'saasten')
		), 
		
		
		array(
			'id' => 'logo_width',
			'type' => 'slider',
			'title' => esc_html__('Set Logo Width','saasten'),
			'min' => 0,
			'max' => 300,
			'step' => 1,
			'unit' => 'px',
			'default' => 102,
			'desc' => esc_html__('Set Logo Width in px. Default Width 184px.', 'saasten') ,
		) ,
		
	  
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Preloader','saasten').'</h3>'
      ),
	  
	  
      array(
        'id' => 'preloader_enable',
        'title' => esc_html__('Enable Preloader','saasten'),
        'type' => 'switcher',
        'desc' => esc_html__('Enable or Disable Preloader', 'saasten') ,
        'default' => true,
      ),
	  
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Back Top Options','saasten').'</h3>'
      ),
	  
	  
      array(
        'id' => 'back_top_enable',
        'title' => esc_html__('Scroll Top Button','saasten'),
        'type' => 'switcher',
        'desc' => esc_html__('Enable or Disable scroll button', 'saasten') ,
        'default' => true,
      ),

    )
  ) );

  /*-------------------------------------------------------
     ** Entire Site Header  Options
   --------------------------------------------------------*/
  
    CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Header','saasten'),
    'id' => 'header_options',
    'icon' => 'fa fa-header',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Header Layout','saasten').'</h3>'
      ),
        //
        // nav select
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
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Header Buttons','saasten').'</h3>'
      ),
	  

	  
  array(
        'id' => 'header_btn_text_1',
        'type' => 'text',
        'title' => 'Header Button 1 Text',
        'default' => esc_html__('Lets Talk', 'saasten') ,
      ) ,

  array(
      'id' => 'header_btn_link_1',
      'type' => 'text',
      'title' => 'Header Button 1 Link',
      'default' => esc_html__('#', 'saasten') ,
  ) ,

   array(
        'id' => 'header_btn_text_2',
        'type' => 'text',
        'title' => 'Header Button 2 Text',
        'default' => esc_html__('Lets Talk', 'saasten') ,
      ) ,

  array(
      'id' => 'header_btn_link_2',
      'type' => 'text',
      'title' => 'Header Button 2 Link',
      'default' => esc_html__('#', 'saasten') ,
  ) ,




	       	
		
	array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Search Bar & Login Option','saasten').'</h3>'
      ),
	  
      array(
        'id' => 'search_bar_enable',
        'title' => esc_html__('Search Bar Display In Header','saasten'),
        'type' => 'switcher',
		'desc' => esc_html__('Enable or Disable Search Bar', 'saasten') ,
        'default' => true,
      ),
	  
	  array(
        'id' => 'login_btn_enable',
        'title' => esc_html__('Show Login Button','saasten'),
        'type' => 'switcher',
		'desc' => esc_html__('Enable or Disable Login Button', 'saasten') ,
        'default' => true,
      ), 
	  
	  array(
        'id' => 'register_btn_enable',
        'title' => esc_html__('Show Register Button','saasten'),
        'type' => 'switcher',
		'desc' => esc_html__('Enable or Disable Register Button', 'saasten') ,
        'default' => true,
      ),
	  
	  
		
	array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Social Options','saasten').'</h3>'
     ),	
	
      array(
        'id' => 'header_social_enable',
        'title' => esc_html__('Do You want to Show Header Social Icons','saasten'),
        'type' => 'switcher',
		'desc' => esc_html__('Enable or Disable Social Bar', 'saasten') ,
        'default' => false,
      ),
	  
		
	array(
        'id'     => 'social-icon',
        'type'   => 'repeater',
        'title'  => esc_html__('Repeater','saasten'),
        'dependency' => array('header_social_enable','==','true'),
        'fields' => array(
          array(
            'id'    => 'icon',
            'type'  => 'icon',
            'title' => esc_html__('Pick Up Your Social Icon','saasten'),
          ),
          array(
            'id'    => 'link',
            'type'  => 'text',
            'title' => esc_html__('Inter Social Url','saasten'),
          ),

        ),
      ),	
		
		

    )
  ));
  
   
    /*-------------------------------------
     ** Typography Options
     -------------------------------------*/
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Typography', 'saasten') ,
		'id' => 'typography_options',
		'icon' => 'fa fa-font',
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Body', 'saasten') . '</h3>'
            ) ,

            array(
                'id' => 'body-typography',
                'type' => 'typography',
                //'output' => 'body',
                'default' => array(
					'color' => '#4E4E4E',
                    'font-family' => 'Manrope',
                    'font-weight' => '400',
                    'font-size' => '16',
                    'line-height' => '26',
					'letter-spacing' => false,
                    'subset' => 'latin-ext',
                    'type' => 'google',
                    'unit' => 'px',
                ) ,

            ) ,

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h1', 'saasten') . '</h3>'
            ) ,

            array(
                'id' => 'heading-one-typo',
                'type' => 'typography',

                //'output' => 'h1',
                'default' => array(
                    'color' => '#000000',
                    'font-family' => 'Manrope',
                    'font-weight' => '700',
					'font-size' => '42',
                    'line-height' => '50',
                    'subset' => 'latin-ext',
                    'type' => 'google',
                    'unit' => 'px',
                    'letter-spacing' => false,
                ) ,
                'extra-styles' => array(
                    '300',
                    '400',
                    '500',
                    '600',
                    '800',
                    '900'
                ) ,
            ) ,


        )
    ));
  
  
  
  

  /*-------------------------------------------------------
     ** Pages and Template
   --------------------------------------------------------*/

   // blog optoins
    CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Blog','saasten'),
    'id' => 'blog_page',
    'icon' => 'fa fa-bookmark',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Blog Options','saasten').'</h3>'
      ),
	  
	  	array(
			'id'         => 'blog_title',
			'type'       => 'text',
			'title'      => esc_html__('Blog Page Title','saasten'),
			'default'    => esc_html__('Blog Page','saasten'),
			'desc'       => esc_html__('Type Blog Page Title','saasten'),
		),
		
		array(
			'id' => 'page-spacing-blog',
			'type' => 'spacing',
			'title' => esc_html__('Blog Page Spacing','saasten'),
			'output' => '.main-container.blog-spacing',
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
			'id' => 'blog_breadcrumb_enable',
			'title' => esc_html__('Breadcrumb', 'saasten') ,
			'type' => 'switcher',
			'desc' => esc_html__('Enable Breadcrumb', 'saasten') ,
			'default' => true,
		) ,
			
		

	 
    )
  ));
  
  
    // category 
	
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Category','saasten'),
    'id' => 'cat_page',
    'icon' => 'fa fa-list-ul',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' => '<h3>' . esc_html__('Theme Category Options. You can Customize Each Catgeory by Editing Individually.', 'saasten') . '</h3>'
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
		
		array(
			'id' => 'page-spacing-category',
			'type' => 'spacing',
			'title' => esc_html__('Category Page Spacing','saasten'),
			'output' => '.main-container.cat-page-spacing',
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
  ));
  
  

  // blog single optoins
    CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Single','saasten'),
    'id' => 'single_page',
    'icon' => 'fa fa-pencil-square-o',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Blog Single Page Option','saasten').'</h3>'
      ),
	  
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
		
		array(
			'id' => 'page-spacing-single',
			'type' => 'spacing',
			'title' => esc_html__('Single Blog Spacing','saasten'),
			'output' => '.single-one-bwrap',
			'output_mode' => 'padding', // or margin, relative
			'default' => array(
				'top' => '40',
				'right' => '0',
				'bottom' => '80',
				'left' => '0',
				'unit' => 'px',
			) ,
		) ,
		
		array(
			'id'         => 'blog_prev_title',
			'type'       => 'text',
			'title'      => esc_html__('Previous Post Text','saasten'),
			'default'    => esc_html__('Previous Post','saasten'),
			'desc'       => esc_html__('Type Previous Post Link Title','saasten'),
		),
		
		array(
			'id'         => 'blog_next_title',
			'type'       => 'text',
			'title'      => esc_html__('Next Post Text','saasten'),
			'default'    => esc_html__('Next Post','saasten'),
			'desc'       => esc_html__('Type Next Post Link Title','saasten'),
		),
			
		array(
			'id' => 'blog_single_cat',
			'title' => esc_html__('Show Catgeory','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Show Category Name','saasten'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_single_author',
			'title' => esc_html__('Show Author','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Single Post Author','saasten'),
			'default' => true,
		),

		array(
			'id' => 'blog_nav',
			'title' => esc_html__('Show Navigation','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Post Navigation','saasten'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_tags',
			'title' => esc_html__('Show Tags','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Show Post Tags','saasten'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_related',
			'title' => esc_html__('Show Related Posts','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Related Posts','saasten'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_views',
			'title' => esc_html__('Show Post Views','saasten'),
			'type' => 'switcher',
			'desc' => esc_html__('Post views','saasten'),
			'default' => false,
		),


    )
  ));


  /*-------------------------------------------------------
       ** Footer  Options
  --------------------------------------------------------*/
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Footer','saasten'),
    'id' => 'footer_options',
    'icon' => 'fa fa-copyright',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Footer Options','saasten').'</h3>'
      ),
	  
	array(
        'id' => 'footer_nav',
        'title' => esc_html__('Footer Right Menu','saasten'),
        'type' => 'switcher',
		'desc' => esc_html__('You can set Yes or No to show Footer menu','saasten'),
        'default' => false,
      ),

    array(
          'id'    => 'footer-shortcode',
          'type'  => 'wp_editor',
          'title' => 'Insert Footer Subscriber Shortcode',
        ),
        	  
	  
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Footer Copyright Area Options','saasten').'</h3>'
      ),

      array(
        'id' => 'copyright_text',
        'title' => esc_html__('Copyright Area Text','saasten'),
        'type' => 'textarea',
        'desc' => esc_html__('Footer Copyright Text','saasten'),
      ),


	  
    )
  ));


  // Backup section
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Backup','saasten'),
    'id'    => 'backup_options',
    'icon'  => 'fa fa-window-restore',
    'fields' => array(
        array(
            'type' => 'backup',
        ),   
    )
  ) );
  

}