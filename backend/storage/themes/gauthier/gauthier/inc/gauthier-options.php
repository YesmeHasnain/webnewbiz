<?php
    /**
     */
    if ( ! class_exists( 'Redux' ) ) {
        return;
    }
    // This is your option name where all the Redux data is stored.
    $opt_name = "redux_gauthier";
    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_gauthier/opt_name', $opt_name );
    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();
        global $wp_filesystem;
        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }
    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();
    
    if ( is_dir( $sample_patterns_path ) ) {
        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();
            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {
                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }
    /**
     * ---> SET ARGUMENTS
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */
    $theme = wp_get_theme(); // For use with some settings. Not necessary.
    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        'display_name'         => $theme->get( 'Name' ),
        'display_version'      => 'THEME OPTION',
        'menu_type'            => 'menu',
        'allow_sub_menu'       => true,
        'menu_title'           => __( 'Gauthier Options', 'gauthier' ),
        'page_title'           => __( 'gauthier Options', 'gauthier' ),
        'google_api_key'       => '',
        'google_update_weekly' => false,
        'font_display'     => false,
        'admin_bar'            => true,
        'admin_bar_icon'       => 'dashicons-admin-tool',
        'menu_icon'                 => 'dashicons-admin-tool',
        'admin_bar_priority'   => 50,
        'global_variable'      => '',
        'dev_mode'             => true,
        'update_notice'        => true,
        'customizer'           => true,
        'page_priority'        => 2,
        'page_parent'          => 'themes.php',
        'page_permissions'     => 'manage_options',
        'menu_icon'            => '',
        'last_tab'             => '',
        'page_icon'            => 'icon-themes',
        'page_slug'            => 'redux_gauthier',
        'save_defaults'        => true,
        'default_show'         => false,
        'default_mark'         => '',
        'show_import_export'   => true,
		 'show_options_object' => false,
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        'output_tag'           => true,
        'database'             => '',
        'use_cdn'              => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.reduxframework.com/',
        'title' => __( 'Documentation', 'gauthier' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
        'title' => __( 'Support', 'gauthier' ),
    );
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => 'reduxframework.com/extensions',
        'title' => __( 'Extensions', 'gauthier' ),
    );
    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/reduxframework',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/redux-framework',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'gauthier' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'gauthier' );
    }
    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'gauthier' );
    Redux::setArgs( $opt_name, $args );
    /*
     * ---> END ARGUMENTS
     */
    /*
     * ---> START HELP TABS
     */
    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'gauthier' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'gauthier' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'gauthier' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'gauthier' )
        )
    );
    Redux::set_help_tab( $opt_name, $tabs );
    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'gauthier' );
    Redux::set_help_sidebar( $opt_name, $content );
    /*
     * <--- END HELP TABS
     */
    /*
     * ---> START SECTIONS
     */
    /*
        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for

     */
    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'General Settings', 'gauthier' ),
        'id'               => 'basic',
        'desc'             => __( 'These are really basic fields!', 'gauthier' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Favicon', 'gauthier' ),
        'id'               => 'jp-Text',
        'subsection'       => true,
        'customizer_width' => '700px',
        'fields'           => array(
			array(
				'id'        => 'jp_favicon',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('Custom Favicon', 'gauthier' ),
				'compiler'  => 'false',
				'subtitle'  => __('Upload your logo', 'gauthier' ),
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/favicon.png'),
			),	
        )
    ) );
	
// ->================================================================================================================
// -> START SCHEME
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Scheme', 'gauthier' ),
        'id'               => 'scheme',
        'desc'             => __( 'These are really basic fields!', 'gauthier' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Background', 'gauthier' ),
        'id'               => 'body_background',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'body-background',
                'type'     => 'background',
                'output'   => array( 'body'),
                'title'    => __( 'Body Background', 'gauthier' ),
                'subtitle' => __( 'Body background with image or color.', 'gauthier' ),
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Element Background', 'gauthier' ),
        'id'               => 'secondary_background',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'secondary-background',
                'type'     => 'background',
                'output'   => array( '
				.mega_main_menu.primary_menu > .menu_holder > .menu_inner > ul > li.nav_search_box > .mega_main_menu_searchform,
				.Sidebar1::-webkit-scrollbar-track,
				.minus, .plus ,
				.woocommerce ul.products li.product .onsale,
				.Sidebar1,
				.sosmed,
				.menu_holder.sticky_container ,
				footer.entry-meta,
				.header2-date,
				.widget14-titlebig, 
				.marquee-wrapper,
				.module2b-caption,
				.woocommerce-checkout #payment,
				.abs-definition,
				.module117-addtochart,
				.jmodule-maintitle2,
				.jmodule-subtitle2,
				.module2a-share,
				.wrapper-module7:nth-child(odd) .module7-thumb.standard:before,
				.wrapper-module7:nth-child(even) .module7-thumb.standard:before,
				.list_carousel3 .prev6, 
				.list_carousel3 .next6,
				.wrapper-module7:nth-child(odd) .text:before, 
				.wrapper-module7:nth-child(even) .text:before,
				.list_carousel5 .prev17:before, 
				.list_carousel5 .next17:before,
				.author-socmed-wrapper
				'),
                'title'    => __( 'Element Background', 'gauthier' ),
                'subtitle' => __( 'Use the same color background for other web element, ex.- icon background.', 'gauthier' ),
            ),
        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Elements Color', 'gauthier' ),
        'id'         => 'elementcolor',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'textcolor',
                'type'     => 'color',
                'output'   => array( 'body' ),
                'title'    => __( 'TEXT COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'linkcolor',
                'type'     => 'color',
                'output'   => array( 'a, a:link, a:visited' ),
                'title'    => __( 'LINK COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'hovercolor',
                'type'     => 'color',
                'output'   => array( '.widget-area .widget a:hover, a:hover' ),
                'title'    => __( 'HOVER COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #999).', 'gauthier' ),
            ),	
            array(
                'id'       => 'borderelements',
                'type'     => 'border',
                'title'    => __( 'FULL BORDER ELEMENTS', 'gauthier' ),
                'subtitle' => __( 'Color for border elements. Submit, input etc...', 'gauthier' ),
                'output'   => array( '.woocommerce .borderbox2' ),
                'all'      => false,
                'desc'     => __( 'This is the description field, again good for additional info.', 'gauthier' ),			
            ),
            array(
                'id'       => 'borderelements2',
                'type'     => 'border',
                'title'    => __( 'TOP and BOTTOM BORDER ELEMENTS', 'gauthier' ),
                'subtitle' => __( 'SET ONLY TOP and BOTTOM BORDER.', 'gauthier' ),
                'output'   => array( '.header7-wrapper, .nav-mainwrapper, .header7-nav' ),
                'all'      => false,
                'desc'     => __( 'Input number only for TOP and BOTTOM border.', 'gauthier' ),				
            ),			
            array(
                'id'       => 'borderelements4',
                'type'     => 'border',
                'title'    => __( 'BOTTOM BORDER ELEMENTS', 'gauthier' ),
                'subtitle' => __( 'SET ONLY BORDER BOTTOM.', 'gauthier' ),
                'output'   => array( '.metaview1, .metaview2, .metaview3,.author-info,input, button, textarea,.header7-wrapper' ),
                'all'      => false,
                'desc'     => __( 'Input number only for BOTTOM border.', 'gauthier' ),
            ),				
        )
    ) );	

// ->================================================================================================================
   // -> START HEADER AREA	
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Header Area', 'gauthier' ),
        'id'         => 'select-select',
        'desc'             => __( 'SET THE HEADER!', 'gauthier' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-th-large'	
    ) );

	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Layout', 'gauthier' ),
        'id'               => 'basic-headerlayout',
        'subsection'       => true,
        'fields'     => array(
            array(
                'id'       => 'header_layout',
                'type'     => 'image_select',
                'title'    => __( 'HEADER LAYOUT', 'gauthier' ),
                'subtitle' => __( 'Choose the header layout', 'gauthier' ),
                'options'  => array(
                    'default' => array(
						'title' => 'Default',
                        'alt' => 'Default',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'style2' => array(
						'title' => 'Style 2',					
                        'alt' => '2 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    ),

                    'style3' => array(
						'title' => 'Style 3',						
                        'alt' => '3 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style3.png'
                    ),

                    'style4' => array(
						'title' => 'Style 4',						
                        'alt' => '4 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),

                    'style5' => array(
						'title' => 'Style 5',						
                        'alt' => '5 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),
                    'style6' => array(
						'title' => 'Style 6',						
                        'alt' => '6 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    ),
                    'style7' => array(
						'title' => 'Style 7',						
                        'alt' => '7 Column',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style4.png'
                    )
                ),
                'default'  => 'default'
            ),

        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header Logo', 'gauthier' ),
        'id'               => 'header-logo',
        'subsection'       => true,
        'fields'     => array(
			array(
				'id'        => 'opt_header_logo',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('IMAGE LOGO', 'gauthier' ),
				'compiler'  => 'false',
				'subtitle'  => __('If you dont upload image, then type your logos name bellow', 'gauthier' ),
			), 	
            array(
                'id'       => 'opt_header_text',
                'type'     => 'text',
                'title'    => __( 'TEXT LOGO', 'gauthier' ),
                'subtitle' => __( 'Enter your text logo here.', 'gauthier' ),
                'desc'     => __( 'If you dont use image as logo, then this text will appear.', 'gauthier' ),
				"default" => 'Gauthier',
				'allowed_html' => array(
					'strong' => array()
				)				
            ),	
            array(
                'id'       => 'logo_typography',
                'type'     => 'typography',
                'title'    => __( 'Text Logo Typography', 'gauthier' ),
                'subtitle' => __( 'Specify the header font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.gauthierlogo h1, .header7-logo h1, .header-style5logo h1'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '24px',
                    'line-height'   => '22px',					
                    'font-weight' => '500',
                ),
            ),	
       )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Navigation Layout', 'gauthier' ),
        'desc'             => __( 'Setting up the navigation layout', 'gauthier' ),
        'id'               => 'basic-navigation',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'header-bordertop',
                'type'     => 'border',
                'title'    => __( 'Top Navigation Border', 'gauthier' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'gauthier' ),
                'output'   => array( '.header-top' ),
                'all'      => false,
                'desc'     => __( 'This is the description field, again good for additional info.', 'gauthier' ),
            ),		
            array(
                'id'       => 'opt-header-border2',
                'type'     => 'border',
                'title'    => __( 'Main Navigation Border', 'gauthier' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'gauthier' ),
                'output'   => array( '.nav-mainwrapper, .header7-nav' ),
                'all'      => false,
                'desc'     => __( 'This is the description field, again good for additional info.', 'gauthier' ),
				'default'  => array(
					'border-style'  => 'solid', 
					'border-top' => '1px', 	
					'border-bottom' => '1px', 	
					'border-right' => '1px', 						
					'border-color' => 'transparent', 						
					),				
            ),
            array(
                'id'       => 'borderelements3',
                'type'     => 'border',
                'title'    => __( ' Menu Border', 'gauthier' ),
                'subtitle' => __( 'Border for Menu.', 'gauthier' ),
                'output'   => array( '.mega_main_menu > .menu_holder > .menu_inner > ul > li' ),
                'all'      => false,
                'desc'     => __( 'Input number only for LEFT border.', 'gauthier' ),
				'default'  => array(
					'border-style'  => 'solid', 
					'border-left' => '1px', 	
					'border-color' => 'transparent', 						
					),
            ),				
            array(
                'id'       => 'jp_headernavtop',
                'type'     => 'background',
                'output'   => array( '.header-top' ),
                'title'    => __( 'Top Navigation Background', 'gauthier' ),
                'subtitle' => __( 'Nav background with image or color.', 'gauthier' ),
                'default'   => '#FFFFFF',
            ),			
            array(
                'id'       => 'jp_headernavback',
                'type'     => 'background',
                'output'   => array( '.nav-mainwrapper' ),
                'title'    => __( 'Main Navigation Background', 'gauthier' ),
                'subtitle' => __( 'Nav background with image or color.', 'gauthier' ),
                'default'   => '#FFFFFF',
            ),			

        )
    ) );		
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Header FAQ', 'gauthier' ),
        'id'               => 'jp_headerfaq',
        'subsection'       => true,
            'fields' => array(
                array(
                    'id'       => 'faqj',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/FAQ-header.txt', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
    ) );	
// ->END OF HEADER AREA ================================================================================================================// 
   // -> START footer AREA
   Redux::setSection( $opt_name, array(
        'title'      => __( 'Footer Area', 'gauthier' ),
        'id'         => 'footer-select',
	    'icon'  => 'el el-website'	,
        'desc'     => __( 'Change your footer layout.', 'gauthier' ),
        'customizer_width' => '400px',
    ) );
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Footer Layout', 'gauthier' ),
        'id'               => 'basic-footerlayout',
        'subsection'       => true,
        'fields'     => array(
            array(
                'id'       => 'footer_layout',
                'type'     => 'image_select',
                'title'    => __( 'footer LAYOUT', 'gauthier' ),
                'subtitle' => __( 'Choose the footer layout', 'gauthier' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    'footer' => array(
						'title' => 'Default',	
                        'alt' => 'Default',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),				
                    'page-templates/footer-style2' => array(
						'title' => 'Style 2',						
                        'alt' => 'Style 2',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style3' => array(
						'title' => 'Style 3',						
                        'alt' => 'Style 3',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style4' => array(
						'title' => 'Style 4',						
                        'alt' => 'Style 4',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),

                    'page-templates/footer-style5' => array(
						'title' => 'Style 5',						
                        'alt' => 'Style 5',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),
                    'page-templates/footer-style6' => array(
						'title' => 'Style 6',						
                        'alt' => 'Style 6',
                        'img' => get_stylesheet_directory_uri() . '/images/footer-style2.png'
                    ),
                ),
                'default'  => 'footer'
            ),

        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Footer Text and Logo', 'gauthier' ),
        'id'               => 'jp_footertextlogo',
        'subsection'       => true,
        'fields'           => array(
			array(
				'id'        => 'opt_footer_logo',
				'type'      => 'media',
				'url'       => true,
				'title'     => __('Footer Logo', 'gauthier' ),
				'compiler'  => 'false',
				'subtitle'  => __('Upload your logo', 'gauthier' ),
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/logo.png'),
			),
            array(
                'id'       => 'footer_text',
                'type'     => 'textarea',
                'title'    => __( 'Footer Text', 'gauthier' ),
                'subtitle' => __( 'Enter your custom footer text here. You can also insert HTML and image', 'gauthier' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'gauthier' ),
				"default" => "&copy; Copyright ".date('Y').' - '.get_bloginfo('name'). '. All Rights Reserved',
				
            ),
        )
    ) );
	
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Main Footer Decoration', 'gauthier' ),
        'id'               => 'jp_mainfooterdecor',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'jp_footerscheme',
                'type'     => 'image_select',
                'title'    => __( 'Background Color Scheme', 'gauthier' ),
                'subtitle' => __( 'Define the scheme will handle the footer content color', 'gauthier' ),
                'options'  => array(
                    'dark' => array(
						'title' => 'Dark',
                        'alt' => 'Dark Footer',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'light' => array(
						'title' => 'Light',					
                        'alt' => 'Light Footer',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    ),					
                ),				
                'default'  => 'light'
            ),			
            array(
                'id'       => 'footer-background',
                'type'     => 'background',
                'output'   => array( '.footer-wrapinside, .footer7-subtitle2' ),
                'title'    => __( 'MAIN FOOTER BACKGROUND', 'gauthier' ),
                'subtitle' => __( 'footer background with image or color.', 'gauthier' ),
                'default'   => '#FFFFFF',
            ),	
            array(
                'id'       => 'footer2-border',
                'type'     => 'background',
                'output'   => array( '.footer-topinside .col-md-3.widget-area:before, .footer-topinside .col-md-3.widget-area:first-child:after' ),
                'title'    => __( 'BODER COLOR FOR FOOTER STYLE 2', 'gauthier' ),
                'subtitle' => __( 'footer background with image or color.', 'gauthier' ),
            ),			
            array(
                'id'       => 'jp_border2',
                'type'     => 'border',
                'title'    => __( 'FOOTER BORDER', 'gauthier' ),
                'subtitle' => __( 'Change options for footer border', 'gauthier' ),
                'output'   => array( '.footer-wrapinside' ),
                'all'      => false,
                'desc'     => __( 'If you want to hide the border, just put "0"(zero) value into the box.', 'gauthier' ),
            ),			
            array(
                'id'       => 'jp_footercolor1',
                'type'     => 'color',
                'output'   => array( '.footer-topinside aside.widget, .footer h3.widgettitle,.footer-payment2,.footer7-subtitle2,.footer7-subtitle2 h2,
.dark .footer-topinside aside.widget,.dark .footer h3.widgettitle, .dark .footer-payment2, .dark .footer7-subtitle2, .dark .footer7-subtitle2 h2				
				' ),
                'title'    => __( 'TEXT COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),			
            array(
                'id'       => 'jp_footerlinkcolor',
                'type'     => 'color',
                'output'   => array( '.footer-topinside aside.widget a, .footer-widgetinside a:link, .footer-widgetinside a:visited	' ),
                'title'    => __( 'LINK COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'jp_footerbovercolor',
                'type'     => 'color',
                'output'   => array( '.footer-topinside aside.widget a:hover,.footer-widgetinside a:hover' ),
                'title'    => __( 'HOVER COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'jp_footeractivecolor',
                'type'     => 'color',
                'output'   => array( '.footer-widgetinside a:active,
				.mega_main_menu.footer-links > .menu_holder > .menu_inner > .nav_logo > .mobile_toggle > .mobile_button, .mega_main_menu.footer-links > .menu_holder > .menu_inner > ul > li > .item_link a:active, 
				.mega_main_menu.footer-links > .menu_holder > .menu_inner > ul > li > .item_link *a:active' ),
                'title'    => __( 'ACTIVE COLOR', 'gauthier' ),
            ),			
        )
    ) );		


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Bottom Footer Decoration', 'gauthier' ),
        'id'               => 'jp_bottomfooterdecor',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'bottomfooter-background',
                'type'     => 'background',
                'output'   => array( '.footer-bottom-wrapper' ),
                'title'    => __( 'BOTTOM FOOTER BACKGROUND', 'gauthier' ),
                'subtitle' => __( 'footer background with image or color.', 'gauthier' ),
                'default'   => '#FFFFFF',
            ),			
            array(
                'id'       => 'jp_bottomborder2',
                'type'     => 'border',
                'title'    => __( 'FOOTER BORDER', 'gauthier' ),
                'subtitle' => __( 'Change options for footer border', 'gauthier' ),
                'output'   => array( '.footer-bottom-wrapper' ),
                'all'      => false,
                'desc'     => __( 'If you want to hide the border, just put "0"(zero) value into the box.', 'gauthier' ),
            ),
			
		
			
            array(
                'id'       => 'jp_bottomfootercolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper, .site-wordpress' ),
                'title'    => __( 'TEXT COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),			
            array(
                'id'       => 'jp_bottomfooterlinkcolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper .gauthier-nav li a' ),
                'title'    => __( 'LINK COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'jp_bottomfooterbovercolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper a:hover,.footer-bottom-wrapper .gauthier-nav li a:hover' ),
                'title'    => __( 'HOVER COLOR', 'gauthier' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'gauthier' ),
            ),
            array(
                'id'       => 'jp_bottomfooteractivecolor',
                'type'     => 'color',
                'output'   => array( '.footer-bottom-wrapper a' ),
                'title'    => __( 'ACTIVE COLOR', 'gauthier' ),
            ),			
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'FAQ Footer', 'gauthier' ),
        'id'               => 'jp_footerfaq',
        'subsection'       => true,
            'fields' => array(
                array(
                    'id'       => 'faqfooter',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/FAQ-footer.txt', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
    ) );	
// ->END OF footer AREA ================================================================================================================

// ->START OF TYPOGRAPHY ================================================================================================================

    Redux::setSection( $opt_name, array(
        'title'  => __( 'Typography', 'gauthier' ),
        'id'     => 'typography',
        'icon'   => 'el el-bold',
        'fields' => array(
            array(
                'id'       => 'gauthier_typographybody',
                'type'     => 'typography',
                'title'    => __( 'Body Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('body'),
            ),		
            array(
                'id'       => 'gauthier_typography',
                'type'     => 'typography',
                'title'    => __( 'h1 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
				'color' => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-header .entry-title, .entry-header h1.entry-title, .entry-content h1, h1,.entry-content>p:first-of-type:first-letter,.entry-content.ctest > div.first.column >p:first-of-type:first-letter'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '48px',
                    'line-height'   => '54px',	
                    'font-weight' => 'bold',
                ),
            ),
            array(
                'id'       => 'gauthier_typography2',
                'type'     => 'typography',
                'title'    => __( 'h2 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h2, h2'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '26px',
                    'line-height'   => '30px',	
                    'font-weight' => 'bold',
                ),				
            ),	
            array(
                'id'       => 'gauthier_typography3',
                'type'     => 'typography',
                'title'    => __( 'h3 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h3, h3'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '24px',
                    'line-height'   => '28px',	
                    'font-weight' => 'bold',
                ),				
            ),	
            array(
                'id'       => 'gauthier_typography4',
                'type'     => 'typography',
                'title'    => __( 'h4 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h4, h4'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '22px',
                    'line-height'   => '26px',	
                    'font-weight' => 'Normal',
                ),					
            ),	
            array(
                'id'       => 'gauthier_typography5',
                'type'     => 'typography',
                'title'    => __( 'h5 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h5, h5, h5 a'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '18px',
                    'line-height'   => '22px',	
                    'font-weight' => '500',
                ),					
            ),		
            array(
                'id'       => 'gauthier_typography6',
                'type'     => 'typography',
                'title'    => __( 'h6 Font', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.entry-content h6, h6,.sidebar .widget li,.widget_categories ul li, .widget_archive ul li'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '14px',
                    'line-height'   => '20px',	
                    'font-weight' => '500',
                ),					
            ),	
            array(
                'id'       => 'Widget_Title',
                'type'     => 'typography',
                'title'    => __( 'Widget Title', 'gauthier' ),
                'subtitle' => __( 'Specify the body font properties.', 'gauthier' ),
                'google'   => true,
                'text-transform'   => true,	
                'letter-spacing'   => true,					
                'output' => array('.widget-title span, .wp-block-group__inner-container h2, .wp-block-group__inner-container h3, .wp-block-group__inner-container h4, h2.widgettitle'),
                'default'  => array(
                    'font-family' => 'Syne',				
                    'font-size'   => '14px',
                    'line-height'   => '21px',	
                    'font-weight' => '500',
                ),	
            ),				

        )
    ) );
// ->END OF TYPOGRAPHY ================================================================================================================	
// -> START SIDEBAR STYLE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'  => __( 'Sidebar Style', 'gauthier' ),
        'id'     => 'jp_sidebargeneral',
        'icon'   => 'el el-list',
        'fields' => array(
            array(
                'id'       => 'jp_sidebar',
                'type'     => 'image_select',
                'title'    => __( 'SELECT SIDEBAR STYLE', 'gauthier' ),
                'subtitle' => __( '2 Styles Available', 'gauthier' ),				
                'options'  => array(
                    'right' => array(
						'title' => 'default',
                        'alt' => 'right',
                        'img' => get_stylesheet_directory_uri() . '/images/header-styledefault.png'
                    ),				
                    'left' => array(
						'title' => 'left',					
                        'alt' => 'left',
                        'img' => get_stylesheet_directory_uri() . '/images/header-style2.png'
                    )					
                ),
				
                'default'  => 'right'
            ),			
	
        )
    ) );
// ->END SIDEBAR STYLE ================================================================================================================
// ->START CATEGORY TEMPLATE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Category Template', 'gauthier' ),
        'id'         => 'jp_cattemplategeneral',
        'desc'             => __( 'You can display your category page with different style', 'gauthier' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-lines'	,
        'fields'           => array(
			array(
				'id' => 'jp_category1',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 1', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate1',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 1', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),			
			array(
				'id' => 'jp_category2',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 2', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate2',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 2', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),		
			array(
				'id' => 'jp_category3',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 3', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate3',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 3', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
			array(
				'id' => 'jp_category4',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 4', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate4',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 4', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),				
			array(
				'id' => 'jp_category5',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 5', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate5',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 5', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),
			array(
				'id' => 'jp_category6',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 6', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate6',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 6', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
			array(
				'id' => 'jp_category7',
				'type' => 'select',
				'multi'    => true,
				'data' => 'categories',
				'title' => __('Select Category 7', 'gauthier'),
				'subtitle' => __('Select 1 or more Category', 'gauthier'),
			),	
            array(
                'id'       => 'jp_cattemplate7',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for Category 7', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/category_default' => 'Default',
                    'page-templates/category1' => 'Layout 1',
                    'page-templates/category2' => 'Layout 2',
                    'page-templates/category3' => 'Layout 3',
                    'page-templates/category4' => 'Layout 4',
                    'page-templates/category5' => 'Layout 5',
                ),
                'default'  => 'page-templates/category_default',				
            ),	
        )
    ) );		
// ->END OF CATEGORY TEMPLATE ================================================================================================================	


// ->START TAGS TEMPLATE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'      => __( 'TAGS Template', 'gauthier' ),
        'id'         => 'jp_tagtemplategeneral',
        'desc'             => __( 'You can display your tag page with different style', 'gauthier' ),
        'customizer_width' => '400px',
	    'icon'  => 'el el-lines'	,
        'fields'           => array(
			array(
				'id' => 'jp_tag1',
				'type' => 'select',
				'multi'    => true,
				'data' => 'tags',
				'title' => __('Select tag 1', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate1',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 1', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),
			
			array(
				'id' => 'jp_tag2',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 2', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate2',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 2', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),

			array(
				'id' => 'jp_tag3',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 3', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate3',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 3', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),	


			array(
				'id' => 'jp_tag4',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 4', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate4',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 4', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),	

			array(
				'id' => 'jp_tag5',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 5', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate5',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 5', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),	

			array(
				'id' => 'jp_tag6',
				'type' => 'select',
				'data' => 'tags',
				'title' => __('Select tag 6', 'gauthier'),
				'subtitle' => __('Select 1 or more tag', 'gauthier'),
			),	
            array(
                'id'       => 'jp_tagtemplate6',
                'type'     => 'button_set',
                'title'    => __( 'Select Template for tag 6', 'gauthier' ),
                'subtitle' => __( 'Select template', 'gauthier' ),
                'options'  => array(
                    'page-templates/tag_default' => 'Default',
                    'page-templates/tag1' => 'Layout 1',
                    'page-templates/tag2' => 'Layout 2',
                    'page-templates/tag3' => 'Layout 3',
                    'page-templates/tag4' => 'Layout 4',
                    'page-templates/tag5' => 'Layout 5',
                ),
                'default'  => 'page-templates/tag_default',				
            ),			
			
        )
    ) );		
// ->END OF TAGS TEMPLATE ================================================================================================================	


// ->START SOCIAL MEDIA================================================================================================================
    Redux::setSection( $opt_name, array(
        'title' => __( 'Social Media', 'gauthier' ),
        'id'    => 'jp_generalsocmed',
        'desc'  => __( 'SOCIAL MEDIA', 'gauthier' ),
        'icon'  => 'el el-network'
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 1', 'gauthier' ),
        'id'         => 'jp_socmed1',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg1',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink1',
                'type'     => 'text',
                'title'    => __( 'Social media 1 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )				
            ),	
            array(
                'id'       => 'jp_socmedalt1',
                'type'     => 'text',
                'title'    => __( 'Social media 1 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 2', 'gauthier' ),
        'id'         => 'jp_socmed2',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg2',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink2',
                'type'     => 'text',
                'title'    => __( 'Social media 2 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )	
            ),	
            array(
                'id'       => 'jp_socmedalt2',
                'type'     => 'text',
                'title'    => __( 'Social media 2 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 3', 'gauthier' ),
        'id'         => 'jp_socmed3',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg3',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink3',
                'type'     => 'text',
                'title'    => __( 'Social media 3 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )	
		
            ),	
            array(
                'id'       => 'jp_socmedalt3',
                'type'     => 'text',
                'title'    => __( 'Social media 3 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 4', 'gauthier' ),
        'id'         => 'jp_socmed4',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg4',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink4',
                'type'     => 'text',
                'title'    => __( 'Social media 4 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )	
            ),	
            array(
                'id'       => 'jp_socmedalt4',
                'type'     => 'text',
                'title'    => __( 'Social media 4 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 5', 'gauthier' ),
        'id'         => 'jp_socmed5',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg5',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink5',
                'type'     => 'text',
                'title'    => __( 'Social media 5 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )	
            ),	
            array(
                'id'       => 'jp_socmedalt5',
                'type'     => 'text',
                'title'    => __( 'Social media 5 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );	
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Social Media 6', 'gauthier' ),
        'id'         => 'jp_socmed6',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'         => 'jp_socmedimg6',
                'type'     => 'text',
                'title'    => __( 'Social media Name', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Name', 'gauthier' )	
            ),
            array(
                'id'       => 'jp_socmedlink6',
                'type'     => 'text',
                'title'    => __( 'Social media 6 Link', 'gauthier' ),
                'subtitle' => __( 'Type Your Social Media Link', 'gauthier' )	
            ),	
            array(
                'id'       => 'jp_socmedalt6',
                'type'     => 'text',
                'title'    => __( 'Social media 6 tooltip', 'gauthier' ),
                'subtitle' => __( 'Type For Tooltip When Hovering', 'gauthier' )	
            ),			
        )
    ) );	
// ->START 404 MEDIA================================================================================================================
    Redux::setSection( $opt_name, array(
        'title' => __( '404', 'gauthier' ),
        'id'    => 'jp_404general',
        'icon'  => 'el el-compass',
        'fields'     => array(
            array(
                'id'         => 'jp_404image',
                'type'       => 'media',
                'title'      => __( '404 IMAGE', 'gauthier' ),
				'url'       => true,    
				'default'   => array('url' => get_stylesheet_directory_uri() . '/images/404.png'),				
                'mode'       => false,
                // Can be set to false to allow any media type, or can also be set to any mime type.
            ),
            array(
                'id'       => 'jp_404text1',
                'type'     => 'textarea',
                'title'    => __( 'MAIN TITLE FOR 404', 'gauthier' ),
				'default'  => 'Epic 404 - Article Not Found'				
		
            ),	
            array(
                'id'       => 'jp_404text2',
                'type'     => 'textarea',
                'title'    => __( 'SUBTITLE FOR 404', 'gauthier' ),
				'default'  => 'This is subtitle for the 404. Insert from the option panel'				
		
            ),			
        )
    ) );

// -> START SHOW/HIDE================================================================================================================
    Redux::setSection( $opt_name, array(
        'title'  => __( 'Show/Hide Elements', 'gauthier' ),
        'id'     => 'jp_showhidegeneral',
        'icon'   => 'el el-off',
        'fields' => array(
            array(
                'id'       => 'jp_showhidenav',
                'type'     => 'button_set',
                'title'    => __( 'HIDE/SHOW TOP NAVIGATION', 'gauthier' ),
                'subtitle' => __( 'Hide/show top navigation', 'gauthier' ),
                'options'  => array(
                    'show' => 'Show Element',
                    'hide' => 'Hide Element'
                ),
                'default'  => 'show'
            ),			
            array(
                'id'       => 'jp_showhiderelated',
                'type'     => 'button_set',
                'title'    => __( 'HIDE/SHOW RELATED POST', 'gauthier' ),
                'subtitle' => __( 'Hide/show related posts', 'gauthier' ),
                'options'  => array(
                    'show' => 'Show Element',
                    'hide' => 'Hide Element'
                ),
                'default'  => 'show'
            ),
        )
    ) );
// ->END SHOW/HIDE================================================================================================================	

    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'gauthier' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links


    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $field['msg']    = 'your custom error message';
                $return['error'] = $field;
            }

            if ( $warning == true ) {
                $field['msg']      = 'your custom warning message';
                $return['warning'] = $field;
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'gauthier' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'gauthier' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }
    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }