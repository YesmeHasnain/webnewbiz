<?php
/*
 * Theme Options
 * @package Knor
 * @since 1.0.0
 * */

if ( !defined('ABSPATH') ){
    exit(); // exit if access directly
}

if( class_exists( 'CSF' ) ) {

  //
  // Set a unique slug-like ID
  $prefix = 'knor';

  //
  // Create options
  CSF::createOptions( $prefix.'_theme_options', array(
    'menu_title' => esc_html__('Theme Options','knor'),
    'menu_slug'  => 'knor-theme-option',
    'menu_type' => 'menu',
    'theme'     => 'dark',
    'class'    => 'knor-options-wrapper',
    'enqueue_webfont'         => true,
    'show_footer' => false,
    'framework_title'      => esc_html__('Knor Options','knor'),
  ) );

  //
  // Create a section
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('General','knor'),
    'icon'  => 'fa fa-sliders',
    'fields' => array(


    array(
        'id'      => 'theme_layout',
        'type'    => 'select',
        'title'   => __( 'Select Layout', 'knor' ),
        'options' => array(
            'full'  => __( 'Full Width', 'knor' ),
            'boxed' => __( 'Boxed', 'knor' ),
        ),
        'default' => 'full',
    ),

    array(
        'id'    => 'box_layout_width',
        'type'  => 'number',
        'title' => 'Boxed Container Width',
        'desc'       => esc_html__( 'Set the boxed outer container width.', 'knor' ),
        'unit'        => 'px',
        //'output'      => '.header-login-btn a',
        'output_mode' => 'max-width',
        'default'     => 1280,
        'dependency' => array( 'theme_layout', '==', 'boxed' ),
    ),

array(
    'id'                    => 'box_layout_bg',
    'type'                  => 'background',
    'title'                 => esc_html__( 'Boxed Background', 'knor' ),
    'desc'                  => esc_html__( 'Set the boxed inner container background.', 'knor' ),
    'dependency' => array( 'theme_layout', '==', 'boxed' ),
    //'output'                => 'footer.theme-footer-wrapper',
    //'background_gradient'   => true,
    //'background_origin'     => true,
    //'background_clip'       => true,
    //'background_blend_mode' => true,
    'default'               => array(
        'background-color'              => '#5F5AFF',
        //'background-gradient-color'     => '#555',
        //'background-gradient-direction' => 'to right',
        'background-size'               => 'cover',
        'background-position'           => 'center center',
        'background-repeat'             => 'no-repeat',
    ),
),


    array(
        'id'    => 'theme_layout_width',
        'type'  => 'number',
        'title' => 'Container Width',
        'desc'       => esc_html__( 'Set the container maximum width.', 'knor' ),
        'unit'        => 'px',
        //'output'      => '.header-login-btn a',
        'output_mode' => 'max-width',
        'default'     => 1340,
    ),


array(
    'id'                    => 'theme_body_bg',
    'type'                  => 'background',
    'title'                 => esc_html__( 'Body Background', 'knor' ),
    'desc'                  => esc_html__( 'Set the <body> background color or image.', 'knor' ),
    'output'                => 'body',
    //'background_gradient'   => true,
    //'background_origin'     => true,
    //'background_clip'       => true,
    //'background_blend_mode' => true,
    'default'               => array(
        'background-color'              => '#ffffff',
        //'background-gradient-color'     => '#555',
        //'background-gradient-direction' => 'to right',
        'background-size'               => 'cover',
        'background-position'           => 'center center',
        'background-repeat'             => 'no-repeat',
    ),
),



    array(
        'id' => 'preloader_enable',
        'title' => esc_html__('Preloader On/Off','knor'),
        'type' => 'switcher',
        //'desc' => esc_html__('Enable or Disable Preloader', 'knor') ,
        'default' => true,
    ),

    array(
      'id'     => 'preloader_bg_color',
      'type'   => 'color',
      'title'  => 'Preloader Background Color',
      'default' => '#FFFFFF',
      'dependency' => array( 'preloader_enable', '==', 'true' ),
    ),

    array(
      'id'     => 'preloader_color',
      'type'   => 'color',
      'title'  => 'Preloader Color',
      'default' => '#5F5AFF',
      'dependency' => array( 'preloader_enable', '==', 'true' ),
    ),


    array(
        'id' => 'back_top_enable',
        'title' => esc_html__('Back To Top On/Off','knor'),
        'type' => 'switcher',
        //'desc' => esc_html__('Enable or Disable scroll button', 'knor') ,
        'default' => true,
    ),


    array(
        'id' => 'theme_scroll_feature_enable',
        'title' => esc_html__('Smooth Scroll On/Off','knor'),
        'type' => 'switcher',
        //'desc' => esc_html__('Enable or Disable scroll button', 'knor') ,
        'default' => false,
    ),

    array(
        'id'       => 'custom_css',
        'type'     => 'code_editor',
        'title'    => 'Custom CSS Editor',
        'settings' => array(
            'theme' => 'mbo',
            'mode'  => 'css',
        ),
        'default'  => '.theme-custom-element{ color: #ffbc00; }',
    ),



    )
  ) );


  /*-------------------------------------------------------
     ** Entire Site Branding  Options
   --------------------------------------------------------*/

  //
  // Create a section
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Branding','knor'),
    'icon'  => 'fa fa-picture-o',
    'fields' => array(

        array(
            'type' => 'subheading',
            'content' => esc_html__( 'Logo Settings', 'knor' ),
        ) ,
            
        array(
            'id' => 'theme_logo',
            'title' => esc_html__('Primary Logo','knor'),
            'type' => 'media',
            'library' => 'image',
            'desc' => esc_html__('Set the default logo.', 'knor')
        ), 

         array(
            'id' => 'theme_logo_secondary',
            'title' => esc_html__('Secondary Logo','knor'),
            'type' => 'media',
            'library' => 'image',
            'desc' => esc_html__('Set logo for transparent headers.', 'knor')
        ), 

        array(
            'id' => 'logo_height',
            'type' => 'slider',
            'title' => esc_html__('Set Logo Height','knor'),
            'min' => 0,
            'max' => 300,
            'step' => 1,
            'unit' => 'px',
            'default' => 34,
            'desc' => esc_html__('Set logo height in px. Default height 34px.', 'knor') ,
        ) ,
           

        array(
            'id' => 'theme_logo_mobile',
            'title' => esc_html__('Mobile Logo','knor'),
            'type' => 'media',
            'library' => 'image',
            'desc' => esc_html__('Set logo for mobile devices. If left blank, Primary Logo will be used.', 'knor')
        ), 

        array(
            'id' => 'theme_logo_mobile_secondary',
            'title' => esc_html__('Mobile Secondary Logo','knor'),
            'type' => 'media',
            'library' => 'image',
            'desc' => esc_html__('Set logo for mobile devices on transparent headers. If left blank, Secondary Logo will be used.', 'knor')
        ), 

         array(
            'id' => 'theme_logo_sticky',
            'title' => esc_html__('Sticky Logo','knor'),
            'type' => 'media',
            'library' => 'image',
            'desc' => esc_html__('Set logo for sticky header.', 'knor')
        ), 



    )
  ) );


  /*-------------------------------------------------------
     ** Entire Site Header  Options
   --------------------------------------------------------*/
  
    CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Header','knor'),
    'id' => 'header_options',
    'icon' => 'fa fa-window-maximize',
    'fields' => array(


    array(
        'type' => 'subheading',
        'content' => esc_html__( 'Header Style', 'knor' ),
    ) ,
        



        //
        // nav select
       array(
            'id' => 'nav_menu',
            'type' => 'image_select',
            'title' => esc_html__('Header Layout','knor'),
            'options' => array(
                'nav-style-one' => KNOR_IMG . '/admin/header-style/header-1.svg',
                'nav-style-two' => KNOR_IMG . '/admin/header-style/header-2.svg',
            ),
			
            'default' => 'nav-style-one'
        ),
	

    array(
      'id'     => 'header_bg_color',
      'type'   => 'color',
      'title'  => 'Header Background',
      'default' => '#FFFFFF',
      'desc' => esc_html__('Set the header background.', 'knor') ,
    ),


    array(
        'id' => 'header_border_enable',
        'title' => esc_html__('Header Border','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Set a 1px border bottom to header.', 'knor') ,
        'default' => false,
    ),

     array(
      'id'     => 'header_border_color',
      'type'   => 'color',
      'title'  => 'Header Border Color',
      'default' => '#E6E9EC',
      'desc' => esc_html__('Set the header border color.', 'knor') ,
      'dependency' => array( 'header_border_enable', '==', 'true' ),
    ),


     array(
        'id' => 'header_sticky_enable',
        'title' => esc_html__('Sticky Header','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Set the header to fixed on top after scroll.', 'knor') ,
        'default' => false,
    ),

     array(
        'id' => 'header_transparent_enable',
        'title' => esc_html__('Transparent Header','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Set header as transparent background.', 'knor') ,
        'default' => false,
    ),

    array(
        'id' => 'header_transparent_border_enable',
        'title' => esc_html__('Transparent Header: Border','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Set a 1px border bottom to transparent header.', 'knor') ,
        'default' => false,
        'dependency' => array( 'header_transparent_enable', '==', 'true' ),
    ),

     array(
      'id'     => 'header_transparent_border_color',
      'type'   => 'color',
      'title'  => 'Transparent Header: Border Color',
      'default' => '#E6E9EC',
      'desc' => esc_html__('Set the transparent header border color.', 'knor') ,
      'dependency' => array( 'header_transparent_border_enable', '==', 'true' ),
    ),

     array(
        'id' => 'header_wide_enable',
        'title' => esc_html__('Wide Header','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Stretches the header container to full screen width.', 'knor') ,
        'default' => false,
    ),


    array(
        'type' => 'subheading',
        'content' => esc_html__( 'Header Extras', 'knor' ),
    ) ,
        



    array(
        'id' => 'search_bar_enable',
        'title' => esc_html__('Search On/Off','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Add a search icon on the right side of the header.', 'knor') ,
        'default' => false,
    ),
      

	array(
        'id' => 'cta_btn_one',
        'title' => esc_html__('Call to Action Button 1 : On/Off','knor'),
        'type' => 'switcher',
		'desc' => esc_html__('Add call to action button on the right side of the header.', 'knor') ,
        'default' => true,
      ), 
	  
    array(
        'id'         => 'cta_btn_one_text',
        'type'       => 'text',
        'title'      => esc_html__('Button 1 Text', 'knor') ,
        'default'    => esc_html__('Login', 'knor') ,
        'dependency' => array( 'cta_btn_one', '==', 'true' ),
    ),
        
    array(
        'id'         => 'cta_btn_one_link',
        'type'       => 'text',
        'title'      => esc_html__('Button 1 Link', 'knor') ,
        'default'    => esc_html__('#', 'knor') ,
        'dependency' => array( 'cta_btn_one', '==', 'true' ),
    ), 
	  
	
    array(
        'id'      => 'btn_1_style',
        'type'    => 'select',
        'title'   => __( 'Button 1 Type', 'knor' ),
        'options' => array(
            'btn_bordered'  => __( 'Bordered', 'knor' ),
            'btn_flat' => __( 'Flat', 'knor' ),
        ),
        'default' => 'btn_flat',
        'dependency' => array( 'cta_btn_one', '==', 'true' ),
    ),


    array(
        'id' => 'cta_btn_two',
        'title' => esc_html__('Call to Action Button 2 : On/Off','knor'),
        'type' => 'switcher',
        'desc' => esc_html__('Add call to action 2nd button on the right side of the header.', 'knor') ,
        'default' => true,
      ), 
      
    array(
        'id'         => 'cta_btn_two_text',
        'type'       => 'text',
        'title'      => esc_html__('Button 2 Text', 'knor') ,
        'default'    => esc_html__('Login', 'knor') ,
        'dependency' => array( 'cta_btn_two', '==', 'true' ),
    ),
        
    array(
        'id'         => 'cta_btn_two_link',
        'type'       => 'text',
        'title'      => esc_html__('Button 1 Link', 'knor') ,
        'default'    => esc_html__('#', 'knor') ,
        'dependency' => array( 'cta_btn_two', '==', 'true' ),
    ), 
      
    
    array(
        'id'      => 'btn_2_style',
        'type'    => 'select',
        'title'   => __( 'Button 2 Type', 'knor' ),
        'options' => array(
            'btn_bordered'  => __( 'Bordered', 'knor' ),
            'btn_flat' => __( 'Flat', 'knor' ),
        ),
        'default' => 'btn_flat',
        'dependency' => array( 'cta_btn_two', '==', 'true' ),
    ),


    array(
        'type' => 'subheading',
        'content' => esc_html__( 'Menu', 'knor' ),
    ) ,
        

    array(
        'id'      => 'theme_menu_align',
        'type'    => 'select',
        'title'   => __( 'Menu Alignment', 'knor' ),
        'options' => array(
            'left'  => __( 'Left', 'knor' ),
            'center' => __( 'Center', 'knor' ),
            'right' => __( 'Right', 'knor' ),
        ),
        'default' => 'center',
    ),



    array(
        'id'      => 'theme-menu-font',
        'type'    => 'typography',
        'title'   => esc_html__( 'Menu Typography', 'knor' ),
        //'output'  => 'h1',
        'default' => array(
            'font-size' => '17',
            'unit'      => 'px',
            'type'      => 'google',
        ),
    ),

    array(
    'id'     => 'menu_hover_color',
    'type'   => 'color',
    'title'  => esc_html__( 'Menu Text Hover Color', 'knor' ),
),

    array(
        'id'      => 'theme-dropdown-menu-font',
        'type'    => 'typography',
        'title'   => esc_html__( 'Menu Dropdown Typography', 'knor' ),
        //'output'  => 'h1',
        'default' => array(
            'font-size' => '17',
            'unit'      => 'px',
            'type'      => 'google',
        ),
    ),

    array(
        'id'     => 'dropdown_menu_hover_color',
        'type'   => 'color',
        'title'  => esc_html__( 'Dropdown Menu Text Hover Color', 'knor' ),
        
    ),

    array(
      'id'     => 'dropdown_menu_bg_color',
      'type'   => 'color',
      'title'  => 'Dropdown Background Color',
      'default' => '#111111',
      'desc' => esc_html__('Set the dropdown menu background color.', 'knor') ,
    ),


    array(
        'type' => 'subheading',
        'content' => esc_html__( 'Mobile Menu', 'knor' ),
    ) ,



    array(
      'id'     => 'mobile_menu_bg_color',
      'type'   => 'color',
      'title'  => 'Mobile menu Background Color',
      'default' => '#111111',
      'desc' => esc_html__('Set the mobile menu background color.', 'knor') ,
    ),


    array(
        'id' => 'mobile_logo_height',
        'type' => 'slider',
        'title' => esc_html__('Set Mobile Logo Height','knor'),
        'min' => 0,
        'max' => 300,
        'step' => 1,
        'unit' => 'px',
        'default' => 34,
        'desc' => esc_html__('Set mobile logo height in px. Default height 34px.', 'knor') ,
    ) ,





    )
  ) );

  
   
    /*-------------------------------------
     ** Typography Options
     -------------------------------------*/
    CSF::createSection($prefix . '_theme_options', array(
        'title' => esc_html__('Typography', 'knor') ,
		'id' => 'typography_options',
		'icon' => 'fa fa-font',
        'fields' => array(

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Body', 'knor') . '</h3>'
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
                'content' => '<h3>' . esc_html__('Heading h1', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-one-typo',
                'type' => 'typography',

                //'output' => '.elementor-widget-heading h1.elementor-heading-title, h1',
                'default' => array(
                    'color' => '#0A0E15',
                    'font-family' => 'Manrope',
                    'font-weight' => '600',
					'font-size' => '64',
                    'line-height' => '90',
                    'text_transform' => 'capitalize',
                    'type' => 'google',
                    'unit' => 'px',
                ) ,
            ) ,

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h2', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-two-typo',
                'type' => 'typography',

                //'output' => '.elementor-widget-heading h2.elementor-heading-title, h2',

                'default' => array(
                    'color' => '#0A0E15',
                    'font-family' => 'Manrope',
                    'font-weight' => '600',
					'font-size' => '60',
                    'line-height' => '80',
                    'text_transform' => 'capitalize',
                    'type' => 'google',
                    'unit' => 'px',
                ) ,
            ) ,

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h3', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-three-typo',
                'type' => 'typography',

                //'output' => 'h3',
                'default' => array(
                    'color' => '#000000',
                    'font-family' => 'Manrope',
                    'font-weight' => '700',
					'font-size' => '24',
                    'line-height' => '28',
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

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h4', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-four-typo',
                'type' => 'typography',

                //'output' => 'h4',
                'default' => array(
                    'color' => '#000000',
                    'font-family' => 'Manrope',
                    'font-weight' => '700',
					'font-size' => '18',
                    'line-height' => '28',
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

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h5', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-five-typo',
                'type' => 'typography',

                //'output' => 'h5',
                'default' => array(
                    'color' => '#000000',
                    'font-family' => 'Manrope',
                    'font-weight' => '700',
					'font-size' => '14',
                    'line-height' => '24',
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

            array(
                'type' => 'subheading',
                'content' => '<h3>' . esc_html__('Heading h6', 'knor') . '</h3>'
            ) ,

            array(
                'id' => 'heading-six-typo',
                'type' => 'typography',
                //'output' => 'h6',
                'default' => array(
                    'color' => '#000000',
                    'font-family' => 'Manrope',
                    'font-weight' => '700',
					'font-size' => '14',
                    'line-height' => '28',
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
    'title'  => esc_html__('Blog','knor'),
    'id' => 'blog_page',
    'icon' => 'fa fa-bookmark',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Blog Options','knor').'</h3>'
      ),
	  
	  	array(
			'id'         => 'blog_title',
			'type'       => 'text',
			'title'      => esc_html__('Blog Page Title','knor'),
			'default'    => esc_html__('Blog Page','knor'),
			'desc'       => esc_html__('Type Blog Page Title','knor'),
		),
		
		array(
			'id' => 'page-spacing-blog',
			'type' => 'spacing',
			'title' => esc_html__('Blog Page Spacing','knor'),
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
			'title' => esc_html__('Breadcrumb', 'knor') ,
			'type' => 'switcher',
			'desc' => esc_html__('Enable Breadcrumb', 'knor') ,
			'default' => true,
		) ,
			
		

	 
    )
  ));
  
  
    // category 
	
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Category','knor'),
    'id' => 'cat_page',
    'icon' => 'fa fa-list-ul',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' => '<h3>' . esc_html__('Theme Category Options. You can Customize Each Catgeory by Editing Individually.', 'knor') . '</h3>'
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
		
		array(
			'id' => 'page-spacing-category',
			'type' => 'spacing',
			'title' => esc_html__('Category Page Spacing','knor'),
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
    'title'  => esc_html__('Single','knor'),
    'id' => 'single_page',
    'icon' => 'fa fa-pencil-square-o',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Blog Single Page Option','knor').'</h3>'
      ),
	  
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
		
		array(
			'id' => 'page-spacing-single',
			'type' => 'spacing',
			'title' => esc_html__('Single Blog Spacing','knor'),
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
			'title'      => esc_html__('Previous Post Text','knor'),
			'default'    => esc_html__('Previous Post','knor'),
			'desc'       => esc_html__('Type Previous Post Link Title','knor'),
		),
		
		array(
			'id'         => 'blog_next_title',
			'type'       => 'text',
			'title'      => esc_html__('Next Post Text','knor'),
			'default'    => esc_html__('Next Post','knor'),
			'desc'       => esc_html__('Type Next Post Link Title','knor'),
		),
			
		array(
			'id' => 'blog_single_cat',
			'title' => esc_html__('Show Catgeory','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Show Category Name','knor'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_single_author',
			'title' => esc_html__('Show Author','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Single Post Author','knor'),
			'default' => true,
		),

		array(
			'id' => 'blog_nav',
			'title' => esc_html__('Show Navigation','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Post Navigation','knor'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_tags',
			'title' => esc_html__('Show Tags','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Show Post Tags','knor'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_related',
			'title' => esc_html__('Show Related Posts','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Related Posts','knor'),
			'default' => true,
		),
		
		array(
			'id' => 'blog_views',
			'title' => esc_html__('Show Post Views','knor'),
			'type' => 'switcher',
			'desc' => esc_html__('Post views','knor'),
			'default' => false,
		),


    )
  ));


  /*-------------------------------------------------------
       ** Footer  Options
  --------------------------------------------------------*/
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Footer','knor'),
    'id' => 'footer_options',
    'icon' => 'fa fa-copyright',
    'fields' => array(
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Footer Options','knor').'</h3>'
      ),
	  
	array(
        'id' => 'footer_nav',
        'title' => esc_html__('Footer Right Menu','knor'),
        'type' => 'switcher',
		'desc' => esc_html__('You can set Yes or No to show Footer menu','knor'),
        'default' => false,
      ),
	  
	  
      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Footer Copyright Area Options','knor').'</h3>'
      ),

      array(
        'id' => 'copyright_text',
        'title' => esc_html__('Copyright Area Text','knor'),
        'type' => 'textarea',
        'desc' => esc_html__('Footer Copyright Text','knor'),
      ),


      array(
        'type' => 'subheading',
        'content' =>'<h3>'.esc_html__('Footer Styling','knor').'</h3>'
      ),

    array(
    'id'                    => 'theme_footer_bg',
    'type'                  => 'background',
    'title'                 => esc_html__( 'Set Footer Background', 'knor' ),
    'desc'                  => esc_html__( 'Default: Set Footer Background.', 'knor' ),
    'output'                => 'footer.theme-footer-wrapper',
    'background_gradient'   => true,
    'background_origin'     => true,
    'background_clip'       => true,
    'background_blend_mode' => true,
    'default'               => array(
        'background-color'              => '#0a0e15',
        'background-gradient-direction' => 'to right',
        'background-size'               => 'cover',
        'background-position'           => 'center center',
        'background-repeat'             => 'no-repeat',
    ),
),


    array(
      'id'     => 'theme_footer_title_color',
      'type'   => 'color',
      'title'  => 'Footer Widget Title Color',
      'default' => '#ffffff',
      'output' => '.footer-widget.widget h4',
      'output_mode' => 'color',

    ),

    array(
      'id'     => 'theme_footer_link_color',
      'type'   => 'color',
      'title'  => 'Footer Widget Link Color',
      'default' => '#ffffff',
      'output' => '.footer-widget ul li a',
      'output_mode' => 'color',

    ),

    array(
      'id'     => 'theme_footer_text_color',
      'type'   => 'color',
      'title'  => 'Footer Widget Text Color',
      'default' => '#ffffff',
      'output' => 'footer.theme-footer-wrapper .widget_text p',
      'output_mode' => 'color',

    ),





	  
    )
  ));


  // Backup section
  CSF::createSection( $prefix.'_theme_options', array(
    'title'  => esc_html__('Backup','knor'),
    'id'    => 'backup_options',
    'icon'  => 'fa fa-window-restore',
    'fields' => array(
        array(
            'type' => 'backup',
        ),   
    )
  ) );
  

}