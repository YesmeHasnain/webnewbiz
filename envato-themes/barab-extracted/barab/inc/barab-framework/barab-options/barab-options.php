<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "barab_opt";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*  
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }


    $alowhtml = array(
        'p' => array(
            'class' => array()
        ),
        'span' => array()
    );


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
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire 
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        // 'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => esc_html__( 'Barab Options', 'barab' ),
        'page_title'           => esc_html__( 'Barab Options', 'barab' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => false,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

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
            'title'   => esc_html__( 'Theme Information 1', 'barab' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'barab' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => esc_html__( 'Theme Information 2', 'barab' ),
            'content' => esc_html__( '<p>This is the tab content, HTML is allowed.</p>', 'barab' )
        )
    );
    Redux::set_help_tab( $opt_name, $tabs );

    // Set the help sidebar
    $content = esc_html__( '<p>This is the sidebar content, HTML is allowed.</p>', 'barab' );
    Redux::set_help_sidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */


    // -> START General Fields

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'General', 'barab' ),
        'id'               => 'barab_general',
        'customizer_width' => '450px',
        'icon'             => 'el el-cog',
        'fields'           => array(
            array(
                'id'    => 'theme_2',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Global Color', 'barab'),
            ),
            array(
                'id'       => 'barab_theme_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Theme Color', 'barab' ),
            ),
            array(
                'id'       => 'barab_theme_color2',
                'type'     => 'color',
                'title'    => esc_html__( 'Theme Color 2', 'barab' ),
            ),
            array(
                'id'       => 'barab_theme_color3',
                'type'     => 'color',
                'title'    => esc_html__( 'Theme Color 3', 'barab' ),
            ),
            array(
                'id'       => 'barab_heading_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Heading Color (H1-H6)', 'barab' ),
            ),
            array(
                'id'       => 'barab_body_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Body Color (Default Text Color)', 'barab' ),
            ),
            array(
                'id'       => 'barab_link_color',
                'type'     => 'link_color',
                'title'    => esc_html__( 'Links Color', 'barab' ), 
                'output'   => array( 'color'    =>  'a' ),
            ),
   
        )

    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Typography', 'barab' ),
        'id'               => 'barab_typography',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_theme_body_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'Body Font Family', 'barab' ),
                'google'      => true, 
                'font-size' => false,
                'line-height' => false,
                'subsets' => false,
                'text-align' => false,
                'color' => false,
                'font-style' => false,
                'font-weight' => false,
                'output'      => array(''),
            ),
            array(
                'id'       => 'barab_theme_heading_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'Heading Font Family', 'barab' ),
                'google'      => true, 
                'font-size' => false,
                'line-height' => false,
                'subsets' => false,
                'text-align' => false,
                'color' => false,
                'font-style' => false,
                'font-weight' => false,
                'output'      => array(''),
            ),
            array(
                'id'    => 'info_11',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Heading Fonts', 'barab'),
            ),
            array(
                'id'       => 'barab_theme_h1_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H1 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h1'),
            ),
            array(
                'id'       => 'barab_theme_h2_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H2 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h2'),
            ),
            array(
                'id'       => 'barab_theme_h3_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H3 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h3'),
            ),
            array(
                'id'       => 'barab_theme_h4_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H4 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h4'),
            ),
            array(
                'id'       => 'barab_theme_h5_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H5 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h5'),
            ),
            array(
                'id'       => 'barab_theme_h6_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'H6 Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('h6'),
            ),
            array(
                'id'    => 'info_22',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Paragraph Fonts', 'barab'),
            ),
            array(
                'id'       => 'barab_theme_p_font',
                'type'     => 'typography',
                'title'    => esc_html__( 'P Font', 'barab' ),
                'google'      => true, 
                'font-style' => true,
                'text-transform' => true,
                'subsets' => false,
                'text-align' => false,
                'color' => true,
                'output'      => array('p'),
            ),
           
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Back To Top', 'barab' ),
        'id'               => 'barab_backtotop',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_display_bcktotop',
                'type'     => 'switch',
                'title'    => esc_html__( 'Back To Top Button', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display back to top button.', 'barab' ),
                'default'  => true,
                'on'       => esc_html__( 'Enabled', 'barab' ),
                'off'      => esc_html__( 'Disabled', 'barab' ),
            ),
            array(
                'id'       => 'barab_bcktotop_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Color', 'barab' ),
                'required' => array('barab_display_bcktotop','equals','1'),
                'output'   => array( '--theme-color' =>'.scroll-top:after' ),
            ),
            array(
                'id'       => 'barab_bcktotop_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Background Color', 'barab' ),
                'required' => array('barab_display_bcktotop','equals','1'),
                'output'   => array( 'background-color' =>'.scroll-top svg' ),
            ),
            array(
                'id'       => 'barab_bcktotop_circle_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Circle Scroll Color', 'barab' ),
                'required' => array('barab_display_bcktotop','equals','1'),
                'output'   => array( '--theme-color' => '.scroll-top .progress-circle path' ),
            ),
           
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Preloader', 'barab' ),
        'id'               => 'barab_preloader',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_display_preloader', 
                'type'     => 'switch',
                'title'    => esc_html__( 'Preloader', 'barab' ),
                'subtitle' => esc_html__( 'Switch Enabled to Display Preloader.', 'barab' ),
                'default'  => true,
                'on'       => esc_html__('Enabled','barab'),
                'off'      => esc_html__('Disabled','barab'),
            ), 
            array(
                'id'       => 'barab_preloader_btn_text', 
                'type'     => 'text',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'Cancel Preloader', 'barab' ),
                'title'    => esc_html__( 'Preloader Button Text', 'barab' ),
                'required' => array( 'barab_display_preloader', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_preloader_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Preloader Logo', 'barab' ),
                'required' => array( 'barab_display_preloader', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_preloader_text', 
                'type'     => 'text',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'Barab', 'barab' ),
                'title'    => esc_html__( 'Preloader Text', 'barab' ),
                'required' => array( 'barab_display_preloader', 'equals', '1' ),
            ),

        )
    )); 

    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Popup Search', 'barab' ),
        'id'               => 'barab_popup_search',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_popup_search_text', 
                'type'     => 'text',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'What are you looking for?', 'barab' ),
                'title'    => esc_html__( 'Popup Search Placeholder Text', 'barab' ),
            ),
            array(
                'id'       => 'barab_popup_search_icon', 
                'type'     => 'text',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( '<i class="fal fa-search"></i>', 'barab' ),
                'title'    => esc_html__( 'Popup Search Placeholder Icon', 'barab' ),
            ),
        )
    )); 

    /* End General Fields */

    /* Admin Lebel Fields */
    Redux::setSection( $opt_name, array(
        'title'             => esc_html__( 'Admin Label', 'barab' ),
        'id'                => 'barab_admin_label',
        'customizer_width'  => '450px',
        'subsection'        => true,
        'fields'            => array(
            array(
                'title'     => esc_html__( 'Admin Login Logo', 'barab' ),
                'subtitle'  => esc_html__( 'It belongs to the back-end of your website to log-in to admin panel.', 'barab' ),
                'id'        => 'barab_admin_login_logo',
                'type'      => 'media',
            ),
            array(
                'title'     => esc_html__( 'Custom CSS For admin', 'barab' ),
                'subtitle'  => esc_html__( 'Any CSS your write here will run in admin.', 'barab' ),
                'id'        => 'barab_theme_admin_custom_css',
                'type'      => 'ace_editor',
                'mode'      => 'css',
                'theme'     => 'chrome',
                'full_width'=> true,
            ),
        ),
    ) );

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header', 'barab' ),
        'id'               => 'barab_header',
        'customizer_width' => '400px',
        'icon'             => 'el el-credit-card',
        'fields'           => array(
            array(
                'id'       => 'barab_header_options', 
                'type'     => 'button_set',
                'default'  => '1',
                'options'  => array(
                    "1"   => esc_html__('Prebuilt','barab'),
                    "2"      => esc_html__('Header Builder','barab'),
                ),
                'title'    => esc_html__( 'Header Options', 'barab' ),
                'subtitle' => esc_html__( 'Select header options.', 'barab' ),
            ),
            array(
                'id'       => 'barab_header_select_options',
                'type'     => 'select',
                'data'     => 'posts',
                'args'     => array(
                    'post_type'     => 'barab_header',
                    'posts_per_page' => -1,
                ),
                'title'    => esc_html__( 'Header', 'barab' ),
                'subtitle' => esc_html__( 'Select header.', 'barab' ),
                'required' => array( 'barab_header_options', 'equals', '2' )
            ),
          
            array(
                'id'       => 'barab_header_search_switcher',
                'type'     => 'switch', 
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'barab' ),
                'off'      => esc_html__( 'Hide', 'barab' ),
                'title'    => esc_html__( 'Show Search Icon?', 'barab' ),
                'required' => array( 'barab_header_options', 'equals', '1' )
            ),
            array(
                'id'       => 'barab_header_cart_switcher',
                'type'     => 'switch', 
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'barab' ),
                'off'      => esc_html__( 'Hide', 'barab' ),
                'title'    => esc_html__( 'Show Cart Icon?', 'barab' ),
                'required' => array( 'barab_header_options', 'equals', '1' )
            ),
            array(
                'id'       => 'barab_btn_text', 
                'type'     => 'text',
                'validate' => 'html',
                'default'  => esc_html__( 'RESERVE A TABLE', 'barab' ),
                'title'    => esc_html__( 'Button Text', 'barab' ),
                'required' => array( 'barab_header_options', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_btn_url',
                'type'     => 'text',
                'default'  => esc_html__( '#', 'barab' ),
                'title'    => esc_html__( 'Button URL?', 'barab' ),
                'required' => array( 'barab_header_options', 'equals', '1' ),
            ),
          
        ),
    ) );
    // -> END Basic Fields

    // -> START Header Topbar
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Topbar', 'barab' ),
        'id'               => 'barab_header_topbar_option',
        'subsection'       => true,
        'required' => array( 'barab_header_options', 'equals', '1' ),
        'fields'           => array(
            array(
                'id'       => 'barab_header_topbar_switcher',
                'type'     => 'switch', 
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'barab' ),
                'off'      => esc_html__( 'Hide', 'barab' ),
                'title'    => esc_html__( 'Show Header Topbar?', 'barab' ),
            ),
            array(
                'id'       => 'barab_topbar_content1', 
                'type'     => 'textarea',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( '8502 Preston Rd. Inglewood, Maine 98380', 'barab' ),
                'title'    => esc_html__( 'Content 1', 'barab' ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_topbar_content2', 
                'type'     => 'textarea',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'info@barab.com', 'barab' ),
                'title'    => esc_html__( 'Content 2', 'barab' ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_topbar_content3', 
                'type'     => 'textarea',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'Opening Hour: Mon to Sat - 9am to 5pm', 'barab' ),
                'title'    => esc_html__( 'Content 3', 'barab' ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_topbar_phone', 
                'type'     => 'textarea',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( '+263 6547 9875', 'barab' ),
                'title'    => esc_html__( 'Phone Text', 'barab' ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_header_social_switcher',
                'type'     => 'switch', 
                'default'  => 1,
                'on'       => esc_html__( 'Show', 'barab' ),
                'off'      => esc_html__( 'Hide', 'barab' ),
                'title'    => esc_html__( 'Show Header Social?', 'barab' ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
            ),
            array(
                'id'          => 'barab_social_links',
                'type'        => 'slides',
                'title'       => esc_html__('Social Profile Links', 'barab'),
                'subtitle'    => esc_html__('Add social icon and url.', 'barab'),
                'show'        => array(
                    'title'          => true,
                    'description'    => false,
                    'progress'       => false,
                    'facts-number'   => false,
                    'facts-title1'   => false,
                    'facts-title2'   => false,
                    'facts-number-2' => false,
                    'facts-title3'   => false,
                    'facts-number-3' => false,
                    'url'            => true,
                    'project-button' => false,
                    'image_upload'   => false,
                ),
                'placeholder'   => array(
                    'icon'          => esc_html__( 'Icon (example: fa fa-facebook) ','barab'),
                    'title'         => esc_html__( 'Social Icon Class', 'barab' ),
                ),
                'required' => array( 'barab_header_topbar_switcher', 'equals', '1' ),
                'required' => array( 'barab_header_social_switcher', 'equals', '1' ),
            ),
        )
    ) );
    // -> End Header Topbar

    // -> START Header Logo
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Logo', 'barab' ),
        'id'               => 'barab_header_logo_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_site_logo',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Logo', 'barab' ),
                'compiler' => 'true',
                'subtitle' => esc_html__( 'Upload your site logo for header ( recommendation png format ).', 'barab' ),
            ),
            array(
                'id'       => 'barab_site_logo_dimensions',
                'type'     => 'dimensions',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Dimensions (Width/Height).', 'barab'),
                'output'   => array('.header-logo .logo img'),
                'subtitle' => esc_html__('Set logo dimensions to choose width, height, and unit.', 'barab'),
            ),
            array(
                'id'       => 'barab_site_logomargin_dimensions',
                'type'     => 'spacing',
                'mode'     => 'margin',
                'output'   => array('.header-logo .logo img'),
                'units_extended' => 'false',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Top and Bottom Margin.', 'barab'),
                'left'     => false,
                'right'    => false,
                'subtitle' => esc_html__('Set logo top and bottom margin.', 'barab'),
                'default'            => array(
                    'units'           => 'px'
                )
            ),
            array(
                'id'       => 'barab_text_title',
                'type'     => 'text',
                'validate' => 'html',
                'title'    => esc_html__( 'Text Logo', 'barab' ),
                'subtitle' => esc_html__( 'Write your logo text use as logo ( You can use span tag for text color ).', 'barab' ),
            )
        )
    ) );
    // -> End Header Logo

    // -> START Header Menu
    Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Header Style', 'barab' ),
        'id'               => 'barab_header_menu_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'    => 'sticky_info',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Header Sticky On/Off', 'barab'),
            ),
            array(
                'id'       => 'barab_header_sticky',
                'type'     => 'switch',
                'title'    => esc_html__( 'Header Sticky ON/OFF', 'barab' ),
                'subtitle' => esc_html__( 'ON / OFF Header Sticky ( Default settings ON ).', 'barab' ),
                'default'  => '1',
                'on'       => 'ON',
                'off'      => 'OFF',
            ),
            array( 
                'id'    => 'info_2',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Background', 'barab'),
            ),
            array(
                'id'       => 'barab_menu_icon',
                'type'     => 'switch',
                'title'    => esc_html__( 'Navbar Sub-menu Icon Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show menu icon ( Default settings SHOW ).', 'barab' ),
                'default'  => '1',
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'barab_menu_icon_class',
                'type'     => 'text',
                'validate' => 'html',
                'default'  => esc_html__( 'f2e7', 'barab' ),
                'title'    => esc_html__( 'Sub Menu Icon', 'barab' ),
                'subtitle' => esc_html__( 'If you change icon need to use Font-Awesome Unicode icon ( Example: f0c9 | f2e7 ).', 'barab' ),
                'required' => array( 'barab_menu_icon', 'equals', '1' )
            ),
            array(
                'id'    => 'info_2',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Background', 'barab'),
            ),
            array(
                'id'       => 'barab_header_topbar_bg',
                'type'     => 'color',
                'title'    => esc_html__( 'Header Topbar Backgound', 'barab' ),
                'output'   => array( 'background-color'  =>  '.prebuilt .header-top' ),
            ),
            array(
                'id'       => 'barab_header_menu_bg',
                'type'     => 'color',
                'title'    => esc_html__( 'Header Menu Backgound', 'barab' ),
                'output'   => array( 'background-color'  =>  '.prebuilt' ),
            ),
            array(
                'id'    => 'info_3',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Menu Style', 'barab'),
            ),
            array(
                'id'       => 'barab_header_menu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Menu Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .main-menu>ul>li>a' ),
            ),
            array(
                'id'       => 'barab_header_menu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Menu Hover Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Menu Hover Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .main-menu>ul>li>a:hover' ),
            ),
            array(
                'id'       => 'barab_header_submenu_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Submenu Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .main-menu ul.sub-menu li a' ),
            ),
            array(
                'id'       => 'barab_header_submenu_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Hover Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Submenu Hover Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .main-menu ul.sub-menu li a:hover' ),
            ),
            array(
                'id'       => 'barab_header_submenu_icon_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Submenu Icon Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Icon Hover Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .main-menu ul.sub-menu li a:before, .prebuilt .main-menu ul li.menu-item-has-children > a:after' ),
            ),

            array(
                'id'    => 'info_4',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Button Style', 'barab'),
            ),
            array(
                'id'       => 'barab_btn_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .th-btn' ), 
            ),
            array(
                'id'       => 'barab_btn_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Background', 'barab' ),
                'output'   => array( '--theme-color'    =>  '.prebuilt .th-btn' ),
            ),
            array(
                'id'       => 'barab_btn_color2',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Hover Color', 'barab' ),
                'output'   => array( 'color'    =>  '.prebuilt .th-btn:hover' ),
            ),
            array(
                'id'       => 'barab_btn_bg_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Hover Background', 'barab' ),
                'output'   => array( '--title-color'    =>  '.prebuilt .th-btn' ),
            ),

        )
    ) );
    // -> End Header Menu

     // -> START Mobile Menu
     Redux::setSection( $opt_name, array(
        'title'            => esc_html__( 'Mobile Menu', 'barab' ), 
        'id'               => 'barab_mobile_menu_option',
        'subsection'       => true,
        'fields'           => array(
            array(
                'id'       => 'barab_menu_menu_show',
                'type'     => 'switch',
                'title'    => esc_html__( 'Mobile Logo Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show mobile menu logo ( Default settings SHOW ).', 'barab' ),
                'default'  => '1',
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'barab_mobile_logo', 
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Logo', 'barab' ),
                'compiler' => 'true',
                'subtitle' => esc_html__( 'Upload your mobile logo for mobile menu ( recommendation png format ).', 'barab' ),
                'required' => array( 
                    array('barab_menu_menu_show','equals','1') 
                )
            ),
            array(
                'id'       => 'barab_mobile_logo_dimensions',
                'type'     => 'dimensions',
                'units'    => array('px'),
                'title'    => esc_html__('Logo Dimensions (Width/Height).', 'barab'),
                'output'   => array('.th-menu-wrapper .mobile-logo img'),
                'subtitle' => esc_html__('Set logo dimensions to choose width, height, and unit.', 'barab'),
                'required' => array( 
                    array('barab_menu_menu_show','equals','1') 
                )
            ),
            array(
                'id'       => 'barab_mobile_menu_bg',
                'type'     => 'color',
                'title'    => esc_html__( 'Logo Background', 'barab' ),
                'subtitle' => esc_html__( 'Set logo backgorund', 'barab' ),
                'output'   => array( 'background-color'    =>  '.th-menu-wrapper .mobile-logo' ),
                'required' => array( 
                    array('barab_menu_menu_show','equals','1') 
                )
            ),
    
        )
    ) );
    // -> End Mobile Menu

    // -> START Blog Page
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Blog', 'barab' ),
        'id'         => 'barab_blog_page',
        'icon'  => 'el el-blogger',
        'fields'     => array(

            array(
                'id'       => 'barab_blog_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Blog Page Layout', 'barab' ),
                'subtitle' => esc_html__( 'Choose blog layout from here. If you use this option then you will able to change three type of blog layout ( Default Left Sidebar Layour ). ', 'barab' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'barab_blog_grid',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Blog Post Column', 'barab' ),
                'subtitle' => esc_html__( 'Select your blog post column from here. If you use this option then you will able to select three type of blog post layout ( Default Two Column ).', 'barab' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/1column.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2column.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3column.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'barab_blog_page_title_switcher',
                'type'     => 'switch',
                'default'  => 1,
                'on'       => esc_html__('Show','barab'),
                'off'      => esc_html__('Hide','barab'),
                'title'    => esc_html__('Blog Page Title', 'barab'),
                'subtitle' => esc_html__('Control blog page title show / hide. If you use this option then you will able to show / hide your blog page title ( Default Setting Show ).', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_page_title_setting',
                'type'     => 'button_set',
                'title'    => esc_html__('Blog Page Title Setting', 'barab'),
                'subtitle' => esc_html__('Control blog page title setting. If you use this option then you can able to show default or custom blog page title ( Default Blog ).', 'barab'),
                'options'  => array(
                    "predefine"   => esc_html__('Default','barab'),
                    "custom"      => esc_html__('Custom','barab'),
                ),
                'default'  => 'predefine',
                'required' => array("barab_blog_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'barab_blog_page_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Custom Title', 'barab'),
                'subtitle' => esc_html__('Set blog page custom title form here. If you use this option then you will able to set your won title text.', 'barab'),
                'required' => array('barab_blog_page_title_setting','equals','custom')
            ),
            array(
                'id'            => 'barab_blog_postExcerpt',
                'type'          => 'slider',
                'title'         => esc_html__('Blog Posts Excerpt', 'barab'),
                'subtitle'      => esc_html__('Control the number of characters you want to show in the blog page for each post.. If you use this option then you can able to control your blog post characters from here ( Default show 10 ).', 'barab'),
                "default"       => 28,
                "min"           => 0,
                "step"          => 1,
                "max"           => 100,
                'resolution'    => 1,
                'display_value' => 'text',
            ),
            array(
                'id'       => 'barab_blog_readmore_setting',
                'type'     => 'button_set',
                'title'    => esc_html__( 'Read More Text Setting', 'barab' ),
                'subtitle' => esc_html__( 'Control read more text from here.', 'barab' ),
                'options'  => array(
                    "default"   => esc_html__('Default','barab'),
                    "custom"    => esc_html__('Custom','barab'),
                ),
                'default'  => 'default', 
            ),
            array(
                'id'       => 'barab_blog_custom_readmore',
                'type'     => 'text',
                'title'    => esc_html__('Read More Text', 'barab'),
                'subtitle' => esc_html__('Set read moer text here. If you use this option then you will able to set your won text.', 'barab'),
                'default'  => esc_html__( 'Read More', 'barab' ),
                'required' => array('barab_blog_readmore_setting','equals','custom')
            ),
            array(
                'id'       => 'barab_blog_title_color',
                'output'   => array( '.th-blog .blog-title a'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Blog Title Color.', 'barab' ),
            ),
            array(
                'id'       => 'barab_blog_title_hover_color',
                'output'   => array( '.th-blog .blog-title a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Title Hover Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Blog Title Hover Color.', 'barab' ),
            ),
            array(
                'id'       => 'barab_blog_contant_color',
                'output'   => array( '.th-blog .blog-content p.blog-text'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Excerpt / Content Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Blog Excerpt / Content Color.', 'barab' ),
            ),
            array(
                'id'    => 'blog_info_1',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Button', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_read_more_button_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Color', 'barab' ),
                'output'   => array( '--white-color'    =>  '.th-blog .blog-content .th-btn.style2' ), 
            ),
            array(
                'id'       => 'barab_blog_read_more_button_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Background Color', 'barab' ),
                'output'   => array( '--title-color'    =>  '.th-blog .blog-content .th-btn.style2' ),
            ),
            array(
                'id'       => 'barab_blog_read_more_button_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Color', 'barab' ),
                'output'   => array( '--white-color'    =>  '.th-blog .blog-content .th-btn.style2:hover' ), 
            ),
            array(
                'id'       => 'barab_blog_read_more_button_bg_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Background Hover Color', 'barab' ),
                'output'   => array( '--theme-color'    =>  '.th-blog .blog-content .th-btn.style2:hover' ),
            ),

            array(
                'id'    => 'blog_info_2',
                'type'  => 'info',
                'style' => 'success',
                'title' => __('Pagination', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_pagination_color',
                'output'   => array( '.th-pagination a'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Color', 'barab'),
                'subtitle' => esc_html__('Set Blog Pagination Color.', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_pagination_bg_color',
                'output'   => array( 'background-color' => '.th-pagination a'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Background', 'barab'),
                'subtitle' => esc_html__('Set Blog Pagination Backgorund Color.', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_pagination_hover_color',
                'output'   => array( '.th-pagination a:hover, .th-pagination a.active'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Hover & Active Color', 'barab'),
                'subtitle' => esc_html__('Set Blog Pagination Hover & Active Color.', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_pagination_bg_hover_color',
                'output'   => array( '--theme-color' => '.th-pagination a:hover, .th-pagination a.active'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Pagination Hover & Active Background', 'barab'),
                'subtitle' => esc_html__('Set Blog Pagination Background Hover & Active Color.', 'barab'),
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Single Blog Page', 'barab' ),
        'id'         => 'barab_post_detail_styles',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'barab_blog_single_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Layout', 'barab' ),
                'subtitle' => esc_html__( 'Choose blog single page layout from here. If you use this option then you will able to change three type of blog single page layout ( Default Left Sidebar Layour ). ', 'barab' ),
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'barab_post_details_title_position',
                'type'     => 'button_set',
                'default'  => 'header',
                'options'  => array(
                    'header'        => esc_html__('On Header','barab'),
                    'below'         => esc_html__('Below Thumbnail','barab'),
                ),
                'title'    => esc_html__('Blog Post Title Position', 'barab'),
                'subtitle' => esc_html__('Control blog post title position from here.', 'barab'),
            ),
            array(
                'id'       => 'barab_post_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__('Blog Details Custom Title', 'barab'),
                'subtitle' => esc_html__('This title will show in Breadcrumb title.', 'barab'),
                'required' => array('barab_post_details_title_position','equals','below')
            ),
            array(
                'id'       => 'barab_display_post_tags',
                'type'     => 'switch',
                'title'    => esc_html__( 'Tags', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Tags.', 'barab' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_post_details_share_options',
                'type'     => 'switch',
                'title'    => esc_html__('Share Options', 'barab'),
                'subtitle' => esc_html__('Control post share options from here. If you use this option then you will able to show or hide post share options.', 'barab'),
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
                'default'   => false,
            ),
            array(
                'id'       => 'barab_post_details_author_box',
                'type'     => 'switch',
                'title'    => esc_html__('Author Box', 'barab'),
                'subtitle' => esc_html__('Switch On to Display Author Box. Set author bio & social links', 'barab'),
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
                'default'  => true,
            ),
            array(
                'id'       => 'barab_post_details_post_navigation',
                'type'     => 'switch',
                'title'    => esc_html__('Post Navigation', 'barab'),
                'subtitle' => esc_html__('Switch On to Display Post Navigation.', 'barab'),
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
                'default'  => true, 
            ),
           
        )
    ));

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Meta Data', 'barab' ),
        'id'         => 'barab_common_meta_data',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'barab_display_post_author',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post author', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Author.', 'barab' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_display_post_date',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Date', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Date.', 'barab' ),
                'default'  => true,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_display_post_cate',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Category', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Category.', 'barab' ),
                'default'  => false,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_display_post_comments',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Comment', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Comment Number.', 'barab' ),
                'default'  => false,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_display_post_min',
                'type'     => 'switch',
                'title'    => esc_html__( 'Post Minute Read', 'barab' ),
                'subtitle' => esc_html__( 'Switch On to Display Post Minute Read', 'barab' ),
                'default'  => false,
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_post_read_min_text',
                'type'     => 'text',
                'title'    => esc_html__('Post Minute Read Text', 'barab'),
                'default'  => esc_html__( 'min read', 'barab' ),
                'required' => array( 'barab_display_post_min', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_post_read_min_count',
                'type'     => 'text',
                'title'    => esc_html__('Per minute read word count', 'barab'),
                'default'  => esc_html__( '150', 'barab' ),
                'required' => array( 'barab_display_post_min', 'equals', '1' ),
            ),
            array(
                'id'       => 'barab_blog_meta_icon_color',
                'output'   => array( '.blog-meta a i'),
                'type'     => 'color',
                'title'    => esc_html__('Blog Meta Icon Color', 'barab'),
                'subtitle' => esc_html__('Set Blog Meta Icon Color.', 'barab'),
            ),
            array(
                'id'       => 'barab_blog_meta_text_color',
                'output'   => array( '.blog-meta a,.blog-meta span'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Text Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Blog Meta Text Color.', 'barab' ),
            ),
            array(
                'id'       => 'barab_blog_meta_text_hover_color',
                'output'   => array( '.blog-meta a:hover'),
                'type'     => 'color',
                'title'    => esc_html__( 'Blog Meta Hover Text Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Blog Meta Hover Text Color.', 'barab' ),
            ),
        )
    ));

    /* End blog Page */

    // -> START Breadcrumb Option
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Breadcrumb', 'barab' ),
        'id'         => 'barab_breadcrumb',
        'icon'  => 'el el-file',
        'fields'     => array(
            array(
                'id'       => 'barab_page_title_switcher',
                'type'     => 'switch',
                'title'    => esc_html__('Title', 'barab'),
                'subtitle' => esc_html__('Switch enabled to display page title. Fot this option you will able to show / hide page title.  Default setting Enabled', 'barab'),
                'default'  => '1',
                'on'        => esc_html__('Enabled','barab'),
                'off'       => esc_html__('Disabled','barab'),
            ),
            array(
                'id'       => 'barab_page_title_tag',
                'type'     => 'select',
                'options'  => array(
                    'h1'        => esc_html__('H1','barab'),
                    'h2'        => esc_html__('H2','barab'),
                    'h3'        => esc_html__('H3','barab'),
                    'h4'        => esc_html__('H4','barab'),
                    'h5'        => esc_html__('H5','barab'),
                    'h6'        => esc_html__('H6','barab'),
                ),
                'default'  => 'h1',
                'title'    => esc_html__( 'Title Tag', 'barab' ),
                'subtitle' => esc_html__( 'Select page title tag. If you use this option then you can able to change title tag H1 - H6 ( Default tag H1 )', 'barab' ),
                'required' => array("barab_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'barab_allHeader_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Title Color', 'barab' ),
                'subtitle' => esc_html__( 'Set Title Color', 'barab' ),
                'output'   => array( 'color' => '.breadcumb-title' ),
                'required' => array("barab_page_title_switcher","equals","1")
            ),
            array(
                'id'       => 'barab_allHeader_spacing',
                'type'     => 'spacing',
                'title'    => esc_html__('Breadcrumb Section Top and Bottom Padding.', 'barab'),
                'mode'     => 'padding',
                'output'   => array('.breadcumb-wrapper'),
                'units_extended' => 'false',
                'units'    => array('px', 'em'),
                'left'     => false,
                'right'    => false,
                'default'            => array(
                    'units'           => 'px'
                )
            ),
            array(
                'id'       => 'barab_enable_breadcrumb',
                'type'     => 'switch',
                'title'    => esc_html__( 'Breadcrumb Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show breadcrumb from all pages and posts ( Default settings hide ).', 'barab' ),
                'default'  => '1',
                'on'       => 'Show',
                'off'      => 'Hide',
            ),
            array(
                'id'       => 'barab_breadcrumb_home_text', 
                'type'     => 'text',
                'rows'     => 2,
                'validate' => 'html',
                'default'  => esc_html__( 'Home', 'barab' ),
                'title'    => esc_html__( 'Breadcrumb Home Text Change', 'barab' ),
                 'required' => array("barab_enable_breadcrumb","equals","1"),
            ),

            array(
                'id'       => 'barab_allHeader_breadcrumbtextcolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Color', 'barab' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text color here.If you user this option then you will able to set page breadcrumb color.', 'barab' ),
                'required' => array("barab_enable_breadcrumb","equals","1"),
                'output'   => array( 'color' => '.breadcumb-wrapper .breadcumb-content ul li a' ),
            ),
            array(
                'id'       => 'barab_allHeader_breadcrumbtextactivecolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Active Color', 'barab' ),
                'subtitle' => esc_html__( 'Choose page header breadcrumb text active color here.If you user this option then you will able to set page breadcrumb active color.', 'barab' ),
                'required' => array( "barab_enable_breadcrumb", "equals", "1" ),
                'output'   => array( 'color' => '.breadcumb-wrapper .breadcumb-content ul li:last-child' ),
            ),
            array(
                'id'       => 'barab_allHeader_dividercolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Breadcrumb Divider Color', 'barab' ),
                'subtitle' => esc_html__( 'Choose breadcrumb divider color.', 'barab' ),
                'required' => array( "barab_enable_breadcrumb", "equals", "1" ),
                'output'   => array( 'color'=>'.breadcumb-wrapper .breadcumb-content ul li:after' ),
            ),
            array(
                'id'       => 'barab_allHeader_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Breadcrumb Background', 'barab' ),
                'subtitle' => esc_html__( 'Setting page header background. If you use this option then you will able to set Background Color, Background Image, Background Repeat, Background Size, Background Attachment, Background Position.', 'barab' ),
                'output'   => array( 'background' => '.breadcumb-wrapper' ),
            ),
            array(
                'id'       => 'barab_shoppage_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background For Shop Pages', 'barab' ),
                'output'   => array( 'background' => '.custom-woo-class' ),
            ),
            array(
                'id'       => 'barab_archivepage_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background For Archive Pages', 'barab' ),
                'output'   => array( 'background' => '.custom-archive-class' ),
            ),
            array(
                'id'       => 'barab_searchpage_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background For Search Pages', 'barab' ),
                'output'   => array( 'background' => '.custom-search-class' ),
            ),
            array(
                'id'       => 'barab_errorpage_bg',
                'type'     => 'background',
                'title'    => esc_html__( 'Background For Error Pages', 'barab' ),
                'output'   => array( 'background' => '.custom-error-class' ),
            ),
          
        ),
    ) );
    /* End Breadcrumb option */

    // -> START Pages Option
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Page', 'barab' ),
        'id'         => 'barab_pages',
        'icon'  => 'el el-file',
        'fields'     => array(
            array(
                'id'       => 'barab_page_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Select layout', 'barab' ),
                'subtitle' => esc_html__( 'Choose your page layout. If you use this option then you will able to choose three type of page layout ( Default no sidebar ). ', 'barab' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'barab_page_layoutopt',
                'type'     => 'button_set',
                'title'    => esc_html__('Sidebar Settings', 'barab'),
                'subtitle' => esc_html__('Set page sidebar. If you use this option then you will able to set three type of sidebar ( Default no sidebar ).', 'barab'),
                //Must provide key => value pairs for options
                'options' => array(
                    '1' => esc_html__( 'Page Sidebar', 'barab' ),
                    '2' => esc_html__( 'Blog Sidebar', 'barab' )
                 ),
                'default' => '1',
                'required'  => array('barab_page_sidebar','!=','1')
            ),
        ),
    ) );
    /* End Pages option */

    // -> START 404 Page
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( '404 Page', 'barab' ),
        'id'         => 'barab_404_page',
        'icon'       => 'el el-ban-circle',
        'fields'     => array(
            array(
                'id'       => 'barab_error_img',
                'type'     => 'media',
                'url'      => true,
                'title'    => esc_html__( 'Error Image', 'barab' ),
                'compiler' => 'true',
                'subtitle' => esc_html__( 'Upload your error image ( recommendation png or svg format ).', 'barab' ),
            ),
            array(
                'id'       => 'barab_error_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Title', 'barab' ),
                'default'  => esc_html__( 'Error 404', 'barab' ),
            ),
            array(
                'id'       => 'barab_error_title_color',
                'type'     => 'color',
                'output'   => array( '.error-title' ),
                'title'    => esc_html__( 'Title Color', 'barab' ),
                'validate' => 'color'
            ),  
            array(
                'id'       => 'barab_error_description',
                'type'     => 'text',
                'title'    => esc_html__( 'Page Description', 'barab' ),
                'default'  => esc_html__( 'Oops! The page you’re looking for doesn’t exist', 'barab' ),
            ),
            array(
                'id'       => 'barab_error_desc_color',
                'type'     => 'color',
                'output'   => array( '.error-text' ),
                'title'    => esc_html__( 'Description Color', 'barab' ),
                'validate' => 'color'
            ),
            array(
                'id'       => 'barab_error_btn_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Button Text', 'barab' ),
                'default'  => esc_html__( 'Back To Home', 'barab' ),
            ),
            array(
                'id'       => 'barab_error_btn_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Color', 'barab' ),
                'output'   => array( '--white-color'    =>  '.th-btn.error-btn' ),
            ),
            array(
                'id'       => 'barab_error_btn_bg_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Background', 'barab' ),
                'output'   => array( 'background'    =>  '.th-btn.error-btn' ),
            ),
            array(
                'id'       => 'barab_error_btn_color2',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Hover Color', 'barab' ),
                'output'   => array( '--white-color'    =>  '.th-btn.error-btn:hover' ),
            ),
            array(
                'id'       => 'barab_error_btn_bg_color2',
                'type'     => 'color',
                'title'    => esc_html__( 'Button Hover Background', 'barab' ),
                'output'   => array( '--theme-color'    =>  '.th-btn.error-btn:hover' ),
            ),

        ),
    ) );

    /* End 404 Page */
    // -> START Woo Page Option

    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Woocommerce Page', 'barab' ),
        'id'         => 'barab_woo_page_page',
        'icon'  => 'el el-shopping-cart',
        'fields'     => array(
            array(
                'id'       => 'barab_shop_container',
                'type'     => 'switch',
                'title'    => esc_html__( 'Shop Page Container set', 'barab' ),
                'subtitle' => esc_html__( 'Set shop page layout container or full-width', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__('Container','barab'),
                'off'      => esc_html__('Full-Width','barab')
            ),
            array(
                'id'       => 'barab_woo_shoppage_sidebar', 
                'type'     => 'image_select',
                'title'    => esc_html__( 'Set Shop Page Sidebar.', 'barab' ),
                'subtitle' => esc_html__( 'Choose shop page sidebar. (Need to add widget in sidebar option)', 'barab' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'barab_woo_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Column', 'barab' ),
                'subtitle' => esc_html__( 'Set your woocommerce product column.', 'barab' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '2' => array(
                        'alt' => esc_attr__('2 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('3 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '4' => array(
                        'alt' => esc_attr__('4 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '6' => array(
                        'alt' => esc_attr__('6 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/6col.png')
                    ),
                ),
                'default'  => '4'
            ),
            array(
                'id'       => 'barab_woo_product_perpage',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Per Page', 'barab' ),
                'default' => '12'
            ),
            array(
                'id'       => 'barab_single_shop_container',
                'type'     => 'switch',
                'title'    => esc_html__( 'Single Product Container set', 'barab' ),
                'subtitle' => esc_html__( 'Set single product page layout container or full-width', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__('Container','barab'),
                'off'      => esc_html__('Full-Width','barab')
            ),
            array(
                'id'       => 'barab_woo_singlepage_sidebar',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Product Single Page sidebar', 'barab' ),
                'subtitle' => esc_html__( 'Choose product single page sidebar.', 'barab' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => esc_attr__('1 Column','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/no-sideber.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('2 Column Left','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/left-sideber.png')
                    ),
                    '3' => array(
                        'alt' => esc_attr__('2 Column Right','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/right-sideber.png' )
                    ),

                ),
                'default'  => '1'
            ),
            array(
                'id'       => 'barab_product_details_title_position',
                'type'     => 'button_set',
                'default'  => 'below',
                'options'  => array(
                    'header'        => esc_html__('On Header','barab'),
                    'below'         => esc_html__('Below Thumbnail','barab'),
                ),
                'title'    => esc_html__('Product Details Title Position', 'barab'),
                'subtitle' => esc_html__('Control product details title position from here.', 'barab'),
            ),
            array(
                'id'       => 'barab_product_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Details Title', 'barab' ),
                'default'  => esc_html__( 'Shop Details', 'barab' ),
                'required' => array('barab_product_details_title_position','equals','below'),
            ),
            array(
                'id'       => 'barab_product_details_custom_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Product Details Title', 'barab' ),
                'default'  => esc_html__( 'Shop Details', 'barab' ),
                'required' => array('barab_product_details_title_position','equals','below'),
            ),
            array(
                'id'       => 'barab_woo_relproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Related product Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show related product in single page (Default Settings Show)', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__('Show','barab'),
                'off'      => esc_html__('Hide','barab')
            ),
            array(
                'id'       => 'barab_woo_relproduct_subtitle',
                'type'     => 'text',
                'title'    => esc_html__( 'Related products Subtitle', 'barab' ),
                'default'  => esc_html__( 'Similar Products', 'barab' ),
                'required' => array('barab_woo_relproduct_display','equals',true),
            ),
            array(
                'id'       => 'barab_woo_relproduct_title',
                'type'     => 'text',
                'title'    => esc_html__( 'Related products Title', 'barab' ),
                'default'  => esc_html__( 'Related products', 'barab' ),
                'required' => array('barab_woo_relproduct_display','equals',true),
            ),
            array(
                'id'       => 'barab_woo_relproduct_slider', 
                'type'     => 'switch',
                'title'    => esc_html__( 'Related product Sldier On/Off', 'barab' ),
                'subtitle' => esc_html__( 'Slider On/Off related product slider in single page (Default Settings Slider On)', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__('Slider On','barab'),
                'off'      => esc_html__('Slider Off','barab'),
                'required' => array('barab_woo_relproduct_display','equals',true),
            ),
            array(
                'id'       => 'barab_woo_relproduct_slider_arrow', 
                'type'     => 'switch',
                'title'    => esc_html__( 'Related product Sldier Arrow On/Off', 'barab' ),
                'subtitle' => esc_html__( 'Slider arrow On/Off related product slider in single page (Default Settings Slider On)', 'barab' ),
                'default'  => '0',
                'on'       => esc_html__('Arrow On','barab'),
                'off'      => esc_html__('Arrow Off','barab'),
                'required' => array('barab_woo_relproduct_slider','equals',true),
            ),
            array(
                'id'       => 'barab_woo_relproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Related products number', 'barab' ),
                'subtitle' => esc_html__( 'Set how many related products you want to show in single product page.', 'barab' ),
                'default'  => 5,
                'required' => array('barab_woo_relproduct_display','equals',true)
            ),

            array(
                'id'       => 'barab_woo_related_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Related Product Column', 'barab' ),
                'subtitle' => esc_html__( 'Set your woocommerce related product column. it works if slider is off', 'barab' ),
                'required' => array('barab_woo_relproduct_display','equals',true),
                'required' => array('barab_woo_relproduct_slider','equals',false),
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '3'
            ),
            array(
                'id'       => 'barab_woo_upsellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Upsell product Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show upsell product in single page (Default Settings Show)', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__('Show','barab'),
                'off'      => esc_html__('Hide','barab'),
            ),
            array(
                'id'       => 'barab_woo_upsellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Upsells products number', 'barab' ),
                'subtitle' => esc_html__( 'Set how many upsells products you want to show in single product page.', 'barab' ),
                'default'  => 3,
                'required' => array('barab_woo_upsellproduct_display','equals',true),
            ),

            array(
                'id'       => 'barab_woo_upsell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Upsells Product Column', 'barab' ),
                'subtitle' => esc_html__( 'Set your woocommerce upsell product column.', 'barab' ),
                'required' => array('barab_woo_upsellproduct_display','equals',true),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
            array(
                'id'       => 'barab_woo_crosssellproduct_display',
                'type'     => 'switch',
                'title'    => esc_html__( 'Cross sell product Hide/Show', 'barab' ),
                'subtitle' => esc_html__( 'Hide / Show cross sell product in single page (Default Settings Show)', 'barab' ),
                'default'  => '1',
                'on'       => esc_html__( 'Show', 'barab' ),
                'off'      => esc_html__( 'Hide', 'barab' ),
            ),
            array(
                'id'       => 'barab_woo_crosssellproduct_num',
                'type'     => 'text',
                'title'    => esc_html__( 'Cross sell products number', 'barab' ),
                'subtitle' => esc_html__( 'Set how many cross sell products you want to show in single product page.', 'barab' ),
                'default'  => 3,
                'required' => array('barab_woo_crosssellproduct_display','equals',true),
            ),

            array(
                'id'       => 'barab_woo_crosssell_product_col',
                'type'     => 'image_select',
                'title'    => esc_html__( 'Cross sell Product Column', 'barab' ),
                'subtitle' => esc_html__( 'Set your woocommerce cross sell product column.', 'barab' ),
                'required' => array( 'barab_woo_crosssellproduct_display', 'equals', true ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '6' => array(
                        'alt' => esc_attr__('2 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri() .'/assets/img/2col.png')
                    ),
                    '4' => array(
                        'alt' => esc_attr__('3 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/3col.png' )
                    ),
                    '3' => array(
                        'alt' => esc_attr__('4 Columns','barab'),
                        'img' => esc_url( get_template_directory_uri(). '/assets/img/4col.png')
                    ),
                    '2' => array(
                        'alt' => esc_attr__('6 Columns','barab'),
                        'img' => esc_url(  get_template_directory_uri() .'/assets/img/6col.png' )
                    ),

                ),
                'default'  => '4'
            ),
        ),
    ) );

    /* End Woo Page option */

    // -> START Subscribe
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Subscribe', 'barab' ),
        'id'         => 'barab_subscribe_page',
        'icon'       => 'el el-eject',
        'fields'     => array(

            array(
                'id'       => 'barab_subscribe_apikey',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp API Key', 'barab' ),
                'subtitle' => esc_html__( 'Set mailchimp api key.', 'barab' ),
            ),
            array(
                'id'       => 'barab_subscribe_listid',
                'type'     => 'text',
                'title'    => esc_html__( 'Mailchimp List ID', 'barab' ),
                'subtitle' => esc_html__( 'Set mailchimp list id.', 'barab' ),
            ),
        ),
    ) );

    /* End Subscribe */

    // -> START Footer Media
    Redux::setSection( $opt_name , array(
       'title'            => esc_html__( 'Footer', 'barab' ),
       'id'               => 'barab_footer',
       'desc'             => esc_html__( 'barab Footer', 'barab' ),
       'customizer_width' => '400px',
       'icon'              => 'el el-photo',
   ) );

   Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Pre-built Footer / Footer Builder', 'barab' ),
        'id'         => 'barab_footer_section',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'barab_footer_builder_trigger',
                'type'     => 'button_set',
                'default'  => 'prebuilt',
                'options'  => array(
                    'footer_builder'        => esc_html__('Footer Builder','barab'),
                    'prebuilt'              => esc_html__('Pre-built Footer','barab'),
                ),
                'title'    => esc_html__( 'Footer Builder', 'barab' ),
            ),
            array(
                'id'       => 'barab_footer_builder_select',
                'type'     => 'select',
                'required' => array( 'barab_footer_builder_trigger','equals','footer_builder'),
                'data'     => 'posts',
                'args'     => array(
                    'post_type'     => 'barab_footerbuild',
                    'posts_per_page' => -1,
                ),
                'on'       => esc_html__( 'Enabled', 'barab' ),
                'off'      => esc_html__( 'Disable', 'barab' ),
                'title'    => esc_html__( 'Select Footer', 'barab' ),
                'subtitle' => esc_html__( 'First make your footer from footer custom types then select it from here.', 'barab' ),
            ),
            array(
                'id'       => 'barab_footerwidget_enable',
                'type'     => 'switch',
                'title'    => esc_html__( 'Footer Widget', 'barab' ),
                'default'  => 1,
                'on'       => esc_html__( 'Enabled', 'barab' ),
                'off'      => esc_html__( 'Disable', 'barab' ),
                'required' => array( 'barab_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
                'id'       => 'barab_footer_background',
                'type'     => 'background',
                'title'    => esc_html__( 'Footer Widget Background', 'barab' ),
                'subtitle' => esc_html__( 'Set footer background.', 'barab' ),
                'output'   => array( '.prebuilt-foo' ),
                'required' => array( 'barab_footerwidget_enable','=','1' ),
            ),
            array(
                'id'       => 'barab_footer_widget_title_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Widget Title Color', 'barab' ),
                'required' => array('barab_footerwidget_enable','=','1'),
                'output'   => array( '.footer-widget .widget_title' ),
            ),
            array(
                'id'       => 'barab_footer_widget_anchor_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Widget Anchor Color', 'barab' ),
                'required' => array('barab_footerwidget_enable','=','1'),
                'output'   => array( '.footer-widget a' ),
            ),
            array(
                'id'       => 'barab_footer_widget_anchor_hov_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Widget Anchor Hover Color', 'barab' ),
                'required' => array('barab_footerwidget_enable','=','1'),
                'output'   => array( '--theme-color'    =>  '.footer-widget a:hover' ),
            ),

        ),
    ) );


    // -> START Footer Bottom
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Footer Bottom', 'barab' ),
        'id'         => 'barab_footer_bottom',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'barab_disable_footer_bottom',
                'type'     => 'switch',
                'title'    => esc_html__( 'Footer Bottom?', 'barab' ),
                'default'  => 1,
                'on'       => esc_html__('Enabled','barab'),
                'off'      => esc_html__('Disable','barab'),
                'required' => array('barab_footer_builder_trigger','equals','prebuilt'),
            ),
            array(
                'id'       => 'barab_footer_bottom_background2',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Bottom Background Color', 'barab' ),
                'required' => array( 'barab_disable_footer_bottom','=','1' ),
                'output'   => array( 'background-color'   =>   '.prebuilt-foo .copyright-wrap' ),
            ),
            array(
                'id'       => 'barab_copyright_text',
                'type'     => 'text',
                'title'    => esc_html__( 'Copyright Text', 'barab' ),
                'subtitle' => esc_html__( 'Add Copyright Text', 'barab' ),
                'default'  => sprintf( '<i class="fal fa-copyright"></i> %s By <a href="%s">%s</a>. All Rights Reserved.',date('Y'),esc_url(esc_url( home_url('/') )),__( 'Barab','barab' ) ),
                'required' => array( 'barab_disable_footer_bottom','equals','1' ),
            ),
            array(
                'id'       => 'barab_footer_copyright_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Copyright Text Color', 'barab' ),
                'subtitle' => esc_html__( 'Set footer copyright text color', 'barab' ),
                'required' => array( 'barab_disable_footer_bottom','equals','1'),
                'output'    => array('--white-color' => '.prebuilt-foo .copyright-text'),
            ),
            array(
                'id'       => 'barab_footer_copyright_acolor',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Copyright Ancor Color', 'barab' ),
                'subtitle' => esc_html__( 'Set footer copyright ancor color', 'barab' ),
                'required' => array( 'barab_disable_footer_bottom','equals','1'),
                'output'    => array('color' => '.prebuilt-foo  .copyright-text a'),
            ),
            array(
                'id'       => 'barab_footer_copyright_a_hover_color',
                'type'     => 'color',
                'title'    => esc_html__( 'Footer Copyright Ancor Hover Color', 'barab' ),
                'subtitle' => esc_html__( 'Set footer copyright ancor Hover color', 'barab' ),
                'required' => array( 'barab_disable_footer_bottom','equals','1'),
                'output'    => array('color' => '.prebuilt-foo .copyright-text a:hover'),
            ), 

        )
    ));

    /* End Footer Media */

    // -> START Custom Css
    Redux::setSection( $opt_name, array(
        'title'      => esc_html__( 'Custom Css', 'barab' ),
        'id'         => 'barab_custom_css_section',
        'icon'  => 'el el-css',
        'fields'     => array(
            array(
                'id'       => 'barab_css_editor',
                'type'     => 'ace_editor',
                'title'    => esc_html__('CSS Code', 'barab'),
                'subtitle' => esc_html__('Paste your CSS code here.', 'barab'),
                'mode'     => 'css',
                'theme'    => 'monokai',
            )
        ),
    ) );

    /* End custom css */



    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'barab' ),
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
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'barab' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'barab' ),
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