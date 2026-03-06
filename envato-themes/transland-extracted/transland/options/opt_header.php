<?php
// Header Section
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Header Settings', 'transland' ),
    'id'               => 'header_sec',
    'customizer_width' => '400px',
    'icon'             => 'el el-home',
    'fields'           => array(

        array (
            'title'     => esc_html__( 'Header Style', 'transland' ),
            'subtitle'  => esc_html__( 'Select your header style from this three design.', 'transland' ),
            'id'        => 'header_style',
            'type'      => 'image_select',
            'default'   => '1',
            'options'   => array (
                '1' => array (
                    'alt' => esc_html__( 'Header One', 'transland' ),
                    'img' => esc_url( TRANSLAND_DIR_IMG.'/opt/header1.png' ),
                ),
                '2' => array (
                    'alt' => esc_html__( 'Header Two', 'transland' ),
                    'img' => esc_url( TRANSLAND_DIR_IMG.'/opt/header2.png' ),
                ),
                '3' => array (
                    'alt' => esc_html__( 'Header Three', 'transland' ),
                    'img' => esc_url( TRANSLAND_DIR_IMG.'/opt/header3.png' ),
                ),
                '4' => array (
                    'alt' => esc_html__( 'Header Four', 'transland' ),
                    'img' => esc_url( TRANSLAND_DIR_IMG.'/opt/header4.png' ),
                ),
                '5' => array (
                    'alt' => esc_html__( 'Header Five', 'transland' ),
                    'img' => esc_url( TRANSLAND_DIR_IMG.'/opt/header5.png' ),
                ),
            )
        ),

        array(
            'title'     => esc_html__('Top Header Bar', 'transland'),
            'subtitle'  => esc_html__( 'are you want show top bar ?', 'transland' ),
            'id'        => 'top_header_opt',
            'type'      => 'switch',
            'default'  => false,
            'on'       => esc_html__('Show', 'transland'),
            'off'      => esc_html__('Hide', 'transland'),
        ),

        array(
            'id'      => 'top_divider_1',
            'type'    => 'divide',
            'required'    => array('top_header_opt', '!=', 'false' ),
        ),

        array(
            'title'     => esc_html__('Phone Level Text', 'transland'),
            'subtitle'  => esc_html__( 'Type text which you like?.', 'transland' ),
            'id'        => 'phone_text',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Phone Number',
        ),

        array(
            'title'     => esc_html__('Phone Number', 'transland'),
            'subtitle'  => esc_html__( 'Type phone number.', 'transland' ),
            'id'        => 'phone_number',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => '987-098-098-09',
        ),

        array(
            'id'      => 'top_divider_3',
            'type'    => 'divide',
            'required'    => array('top_header_opt', '!=', 'false' ),
        ),

        array(
            'title'     => esc_html__('Email Level Text', 'transland'),
            'subtitle'  => esc_html__( 'Type your text here.', 'transland' ),
            'id'        => 'email_text',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Send us mail',
        ),

        array(
            'title'     => esc_html__('Email Address', 'transland'),
            'subtitle'  => esc_html__( 'Type email address.', 'transland' ),
            'id'        => 'email_address',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'info@example.com',
        ),

        array(
            'id'      => 'top_divider_4',
            'type'    => 'divide',
            'required'    => array('top_header_opt', '!=', 'false' ),
        ),

        array(
            'title'     => esc_html__('Location Level Text', 'transland'),
            'subtitle'  => esc_html__( 'Type your text here.', 'transland' ),
            'id'        => 'office_text',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Visit our location',
        ),

        array(
            'title'     => esc_html__('Office Address - Location', 'transland'),
            'subtitle'  => esc_html__( 'Type phone number.', 'transland' ),
            'id'        => 'office_address',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Sun City, USA',
        ),

        array(
            'id'      => 'top_divider_8',
            'type'    => 'divide',
            'required'    => array('top_header_opt', '!=', 'false' ),
        ),

        array(
            'title'     => esc_html__('Time Hours Level Text', 'transland'),
            'subtitle'  => esc_html__( 'Type your text here.', 'transland' ),
            'id'        => 'time_text',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Opening Hours:',
        ),

        array(
            'title'     => esc_html__('Office Time Hours', 'transland'),
            'subtitle'  => esc_html__( 'Enter Days & Time.', 'transland' ),
            'id'        => 'office_hours',
            'type'      => 'text',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Mon-Fri 8am-5pm',
        ),

        array(
            'title'     => esc_html__('Welcome Text', 'transland'),
            'subtitle'  => esc_html__( 'Type your top bar welcome heading.', 'transland' ),
            'id'        => 'welcome_text',
            'type'      => 'textarea',
            'required'    => array('top_header_opt', '!=', 'false' ),
            'default'   => 'Welcome to Transland Logistic & Cargo Services Company',
            'args'   => array(
                'teeny'            => true,
                'textarea_rows'    => 10
            ),
        ),

        array(
            'id'      => 'top_divider_9',
            'type'    => 'divide',
        ),

    )
) );

// Logo
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Logo', 'transland' ),
    'id'               => 'logo_setting',
    'subsection'       => true,
    'icon'             => 'el el-upload',
    'fields'           => array(

        array(
            'title'     => esc_html__('Select Your Logo Type', 'transland'),
            'subtitle'  => esc_html__( 'which type logo you want for your site ?', 'transland' ),
            'id'        => 'logo_select',
            'type'      => 'select',
            'options'  => array(
                '1' => 'Text',
                '2' => 'Image',
            ),
            'default'  => '2',
        ),

        array(
            'title'     => esc_html__('Text Logo', 'transland'),
            'subtitle'  => esc_html__( 'Type your logo text , it is a text logo.', 'transland' ),
            'id'        => 'main_text_logo',
            'type'      => 'text',
            'default'   => 'transland',
            'required'  => array( 
                array('logo_select','equals','1')
            ),
        ),

        array(
            'title'     => esc_html__('Logo Text Color', 'transland'),
            'subtitle'  => esc_html__('Select Logo color', 'transland'),
            'id'        => 'logo_text_color',
            'type'      => 'color',
            'required'  => array( 
                array('logo_select','equals','1')
            ),
        ),

        array(
            'title'     => esc_html__('Main Logo Upload', 'transland'),
            'subtitle'  => esc_html__( 'Upload here a image file for your logo', 'transland' ),
            'id'        => 'main_logo',
            'type'      => 'media',
            'compiler'  => true,
            'required'  => array( 
                array('logo_select','equals','2')
            ),
            'default'   => array(
                'url'   => TRANSLAND_DIR_IMG.'/logo.svg'
            ),
        ),

        array(
            'title'     => esc_html__( 'Logo dimensions', 'transland' ),
            'subtitle'  => esc_html__( 'Set a custom height width for your upload logo.', 'transland' ),
            'id'        => 'logo_dimensions',
            'required'  => array( 
                array('logo_select','equals','2')
            ),            
            'type'      => 'dimensions',
            'units'     => array( 'em','px','%' ),
            'output'    => '.logo > img'
        ),

    )
) );

// banner Section
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Banner', 'transland' ),
    'id'               => 'banner_sec',
    'subsection'       => true,
    'icon'             => 'el el-picture',
    'fields'           => array(

        array(
            'id'      => 'is_breadcrumb',
            'type'    => 'switch',
            'title'   => esc_html__( 'Breadcrumb Option', 'transland' ),
            'on'      => esc_html__('Show', 'transland'),
            'off'     => esc_html__('Hide', 'transland'),
            'default' => false,
        ),

        array(
            'title'     => esc_html__( 'Banner Image Type', 'transland' ),
            'id'        => 'is_banner_img',
            'type'      => 'switch',
            'on'        => esc_html__( 'Show', 'transland' ),
            'off'       => esc_html__( 'Hide', 'transland' ),
            'default'   => '1'
        ),

        array(
            'id' => 'banner_opt_start',
            'type' => 'section',
            'title' => __('Banner Options', 'transland'),
            'subtitle' => __('Enable/Disable Header Banner Options as you want.', 'transland'),
            'required' => array('is_banner_img','=','1'),
            'indent' => true,
        ),

        array(
            'title'     => esc_html__('Header Banner Image Upload', 'transland'),
            'subtitle'  => esc_html__( 'Upload here a jpg/png file for header background image.', 'transland' ),
            'id'        => 'header_banner_img',
            'type'      => 'media',
            'compiler'  => true,
            'default'   => array(
                'url'   => TRANSLAND_DIR_IMG.'/page-banner.jpg'
            ),
        ),

        array(
            'title'     => esc_html__('Banner Overlay Color', 'transland'),
            'id'        => 'banner_overlay_color',
            'type'      => 'color',
        ),

        array(
            'id' => 'banner_overlay_color_opacity',
            'type' => 'slider',
            'title' => esc_html__('Banner Overlay Color Opacity', 'transland'),
            "min" => 0,
            "step" => .1,
            "max" => 1,
            'resolution' => 0.1,
            'display_value' => 'label'
        ),

        array(
            'id'     => 'banner_opt_end',
            'type'   => 'section',
            'indent' => false,
        ),

        array(
            'id' => 'banner_opt_color_start',
            'type' => 'section',
            'title' => __('Banner Color', 'transland'),
            'required' => array('is_banner_img','=','0'),
            'indent' => true,
        ),

        array(
            'title'     => esc_html__('Banner Color', 'transland'),
            'subtitle'  => esc_html__( 'Choice your solid banner color', 'transland' ),
            'id'        => 'banner_color',
            'type'      => 'color'
        ),

        array(
            'id'     => 'banner_opt_color_end',
            'type'   => 'section',
            'indent' => false,
        ),

    )
) );

// Navbar styling
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Navbar', 'transland' ),
    'id'               => 'navbar_styling',
    'subsection'       => true,
    'icon'             => 'el el-lines',
    'fields'           => array(

        array(
            'title'     => esc_html__('Menu Item Color', 'transland'),
            'subtitle'  => esc_html__('Menu item Text color', 'transland'),
            'id'        => 'menu_text_color',
            'type'      => 'color',
        ),

        array(
            'title'     => esc_html__('Menu Item Hover Color', 'transland'),
            'subtitle'  => esc_html__('Menu item Text color', 'transland'),
            'id'        => 'menu_hover_text_color',
            'type'      => 'color',
        ),

        array(
            'title'     => esc_html__('Menu Active Color', 'transland'),
            'subtitle'  => esc_html__('Menu item active and hover text color', 'transland'),
            'id'        => 'menu_active_text_color',
            'type'      => 'color',
        ),

        array(
            'title'     => esc_html__('Sub Menu Background Color', 'transland'),
            'id'        => 'sub_menu_bg_color',
            'type'      => 'color',
        ),

        array(
            'title'     => esc_html__('Menu Item Margin', 'transland'),
            'subtitle'  => esc_html__('Margin around menu item (li).', 'transland'),
            'id'        => 'menu_item_margin',
            'type'      => 'spacing',
            'mode'      => 'margin',
            'units'     => array( 'em', 'px' ),
        ),

    )
));

// Menu action button
Redux::setSection('transland_opt', array(
    'title'            => esc_html__( 'Action Button', 'transland' ),
    'id'               => 'cta_btn_opt',
    'subsection'       => true,
    'icon'             => 'el el-link',
    'fields'           => array(
        
        array(
            'title'     => esc_html__('Button Visibility', 'transland'),
            'id'        => 'is_menu_btn',
            'type'      => 'switch',
            'on'        => esc_html__('Show', 'transland'),
            'off'       => esc_html__('Hide', 'transland'),
        ),

        array(
            'title'     => esc_html__('Button Label', 'transland'),
            'subtitle'  => esc_html__('Leave the button label field empty to hide the button.', 'transland'),
            'id'        => 'menu_btn_label',
            'type'      => 'text',
            'default'   => esc_html__('Get A Quote', 'transland'),
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'title'     => esc_html__('Button URL', 'transland'),
            'id'        => 'menu_btn_url',
            'type'      => 'text',
            'default'   => '#',
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'title'     => esc_html__('Font Size', 'transland'),
            'id'        => 'menu_btn_size',
            'type'      => 'spinner',
            'default'   => '14',
            'min'       => '12',
            'step'      => '1',
            'max'       => '50',
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'title'     => esc_html__('Button Colors', 'transland'),
            'subtitle'  => esc_html__('Button style attributes on normal', 'transland'),
            'id'        => 'button_colors',
            'type'      => 'section',
            'indent'    => true,
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'title'     => esc_html__('Text color', 'transland'),
            'id'        => 'menu_btn_font_color',
            'type'      => 'color',
            'output'    => array('header .header-promo-btn a, header.header-1 .top-bar .d-btn'),
            'required'  => array('is_menu_btn', '=', '1')
        ),
            
        array(
            'title'     => esc_html__('Background Color', 'transland'),
            'id'        => 'menu_btn_bg_color',
            'type'      => 'color',
            'mode'      => 'background',
            'output'    => array('header .header-promo-btn a, header.header-1 .top-bar .d-btn'),
            'required'  => array('is_menu_btn', '=', '1')
        ),

        // Button color on hover stats
        array(
            'title'     => esc_html__('Hover Text Color', 'transland'),
            'subtitle'  => esc_html__('Text color on hover stats.', 'transland'),
            'id'        => 'menu_btn_hover_font_color',
            'type'      => 'color',
            'output'    => array('header .header-promo-btn a:hover, header.header-1 .top-bar .d-btn:hover'),
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'title'     => esc_html__('Hover Background Color', 'transland'),
            'subtitle'  => esc_html__('Background color on hover stats.', 'transland'),
            'id'        => 'menu_btn_hover_bg_color',
            'type'      => 'color',
            'output'    => array(
                'background' => 'header.header-1 .top-bar .d-btn:hover, header .header-promo-btn a:hover',
            ),
            'required'  => array('is_menu_btn', '=', '1')
        ),

        array(
            'id'     => 'button_colors-end',
            'type'   => 'section',
            'indent' => false,
        ),

        array(
            'title'     => esc_html__('Button Padding', 'transland'),
            'subtitle'  => esc_html__('Padding around the menu donate button.', 'transland'),
            'id'        => 'menu_btn_padding',
            'type'      => 'spacing',
            'output'    => array( 'header .header-promo-btn a, header.header-1 .top-bar .d-btn' ),
            'mode'      => 'padding',
            'units'     => array( 'em', 'px', '%' ), 
            'units_extended' => 'true',
            'required'  => array('is_menu_btn', '=', '1')
        ),
    )
));