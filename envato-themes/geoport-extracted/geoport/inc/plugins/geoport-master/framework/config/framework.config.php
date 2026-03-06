<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings           = array(
  'menu_title'      => __( 'Geoport Options', 'geoport' ),
  'menu_type'       => 'menu', // menu, submenu, options, theme, etc.
  'menu_slug'       => 'geoport-framework',
  'ajax_save'       => true,
  'show_reset_all'  => false,
  'framework_title' => __( 'Geoport <small>by Johanspond</small>', 'geoport' ),
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options        = array();

// ===================================================================
// Genaral Settings =
// ===================================================================

$options[]   = array(
  'name'     => 'general_setting',
  'title'    => __( 'General Settings', 'geoport' ),
  'icon'     => 'fal fa-home-heart',
  'sections' => array(
    // Logo & Site Icon
    array(
      'name'     => 'logo_and_site_icon',
      'title'    => 'Logo & Icon Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'       => 'heading',
          'content'    => __( 'Favicon Settings', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_site_icon',
          'type'       => 'image',
          'title'      => __( 'Upload Favicon Icon', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/favicon.png',
          'desc'       => __( 'Site Icons should be square and Recommended size is 32 × 32 pixels.', 'geoport' ),
        ),
        array(
          'type'       => 'heading',
          'content'    => __( 'Logo Settings', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_logo1_img',
          'type'       => 'image',
          'title'      => __( 'Uplaod Logo', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/logo1.png',
          'desc'       => __( 'Recommended image size is 200x60 png file', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_logo1_sticky',
          'type'       => 'image',
          'title'      => __( 'Upload Sticky Logo', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/logo1-sticky.png',
          'desc'       => __( 'Recommended image size is 200x60 png file', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_logo1_width',
          'type'       => 'text',
          'title'      => __( 'Logo Width', 'geoport' ),
          'default'    => '200px',
          'desc'       => __( 'Put logo width as like as default value', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_logo1_pt',
          'type'       => 'text',
          'title'      => __( 'Logo Padding Top', 'geoport' ),
          'default'    => '5px',
          'desc'       => __( 'Put logo padding top like as default value', 'geoport' ),
        ),
         array(
          'id'         => 'geoport_logo1_pb',
          'type'       => 'text',
          'title'      => __( 'Logo Padding Bottom', 'geoport' ),
          'default'    => '5px',
          'desc'       => __( 'Put logo padding top like as default value', 'geoport' ),
        ),
      )
    ),
    // Breadcrumb
    array(
      'name'     => 'breadcrumb_settings',
      'title'    => 'Breadcrumb Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(

        array(
          'type'       => 'heading',
          'content'    => __( 'Breadcrumb Settings', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_breadcrumb_switch',
          'type'       => 'switcher',
          'title'      => __( 'Breadcrumb Enable/Disable Switch', 'geoport' ),
          'default'    => true,
        ),
        array(
          'id'         => 'breadcrumb_bg_condition',
          'type'       => 'select',
          'title'      => __( 'Breadcrumb Background Type', 'geoport' ),
          'desc'       => __( 'Select breadcrumb background image type color or image.', 'geoport' ),
          'options'    => array(
            'image'    => 'Image',
            'color'    => 'Color',
          ),
          'default'    => 'image',
        ),

        array(
          'id'         => 'breadcrumb_bg_img',
          'type'       => 'image',
          'title'      => __( 'Breadcrumb Background Image', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/breadcrumb.jpg',
          'desc'       => __( 'Upload Breadcrumb Background Image. Recommended image size is 1920x400 jpg/png file, for secondary header', 'geoport' ),
          'dependency' => array( 'breadcrumb_bg_condition', '==', 'image' ),
        ),
        array(
          'id'         => 'breadcrumb_overlay_color',
          'type'       => 'color_picker',
          'title'      => __( 'Breadcrumb image overlay color', 'geoport' ),
          'desc'      => __( 'Choose breadcrumb overlay color.', 'geoport' ),
          'default'    => '#000e30',
          'dependency' => array( 'breadcrumb_bg_condition', '==', 'image' ),
        ),
        array(
          'id'      => 'breadcrumb_bg_img_opacity',
          'type'    => 'select',
          'title'   => __( 'Breadcrumb Image Opacity', 'geoport' ),
          'desc'       => __( 'Select breadcrumb background image opacity.', 'geoport' ),
          'options'      => array(
            '0' => '0',
            '0.2' => '0.2',
            '0.3' => '0.3',
            '0.4' => '0.4',
            '0.5' => '0.5',
            '0.6' => '0.6',
            '0.7' => '0.7',
            '0.8' => '0.8',
            '0.9' => '0.9',
            '1'   => '1',
          ),
          'default'      => '0.7',
          'dependency'   => array( 'breadcrumb_bg_condition', '==', 'image' ),
        ),
        array(
          'id'         => 'breadcrumb_bg_color',
          'type'       => 'color_picker',
          'title'      => __( 'Breadcrumb Background Color', 'geoport' ),
          'desc'       => __( 'Choose breadcrumb background color.', 'geoport' ),
          'default'    => '#000e30',
          'dependency' => array( 'breadcrumb_bg_condition', '!=', 'image' ),
        ),
        array(
          'id'      => 'breadcrumb_pt',
          'type'    => 'text',
          'title'   => __( 'Breadcrumb Section Padding Top', 'geoport' ),
          'desc'   	=> __( 'Breadcrumb padding top with px ( like as 350px ).', 'geoport' ),
          'default' => '260px',
        ),
        array(
          'id'      => 'breadcrumb_pb',
          'type'    => 'text',
          'title'   => __( 'Breadcrumb Section Padding Bottom', 'geoport' ),
          'desc'   	=> __( 'Breadcrumb padding bottom with px ( like as 350px ).', 'geoport' ),
          'default' => '115px',
        ),
        array(
          'id'      => 'breadcrumb_title_font',
          'type'    => 'text',
          'title'   => __( 'Breadcrumb Title Font Size', 'geoport' ),
          'desc'   	=> __( 'Breadcrumb title font size with px ( like as 42px ).', 'geoport' ),
          'default' => '42px',
        ),
        array(
          'id'             => 'geoport_breadcrumb_font_weight',
          'type'           => 'select',
          'title'          => 'Breadcrumb Font Weight',
          'desc'           => __( 'Choose default breadcrumb font weight', 'geoport' ),
          'class'          => 'chosen',
          'options'        => array(
            '700'        => '700',
            '600'        => '600',
            '500'        => '500',
            '400'        => '400',
            '300'        => '300',
          ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),
        array(
          'id'             => 'geoport_breadcrumb_text_transform',
          'type'           => 'select',
          'title'          => 'Breadcrumb Text Transform',
          'desc'           => __( 'Choose default breadcrumb text transform', 'geoport' ),
          'class'          => 'chosen',
          'options'        => array(
            'capitalize'   => 'Capitalized',
            'lowercase'    => 'Lowercase',
            'uppercase'    => 'Uppercase',
            'unset'        => 'Default',
          ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),
        array(
          'id'      => 'breadcrumb_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Breadcrumb Font Color', 'geoport' ),
          'desc'   	=> __( 'Choose breadcrumb font color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'id'      => 'breadcrumb_hover_link_color',
          'type'    => 'color_picker',
          'title'   => __( 'Breadcrumb Hover Link Color', 'geoport' ),
          'desc'   	=> __( 'Choose breadcrumb hover link font color.', 'geoport' ),
          'default' => '#ff5e14',
        ),
        array(
          'id'         => 'geoport_breadcrumb_navigation_level',
          'type'       => 'switcher',
          'title'      => __( 'Breadcrumb Meta Switch', 'geoport' ),
          'desc'       => __( 'Enable/Disable breadcrumb meta navigation label.', 'geoport' ),
          'default'    => true,
        ),
        array(
          'type'       => 'heading',
          'content'    => __( 'Breadcrumb Responsive Settings', 'geoport' ),
        ),
        array(
          'id'      => 'breadcrumb_m_title_font',
          'type'    => 'text',
          'title'   => __( 'Breadcrumb Title Font Size', 'geoport' ),
          'desc'   => __( 'Breadcrumb padding bottom with px ( like as 350px ).', 'geoport' ),
          'default' => '42px',
        ),
        array(
          'id'      => 'breadcrumb_m_pt',
          'type'    => 'text',
          'title'   => __( 'Mobile padding top', 'geoport' ),
          'desc'   => __( 'Breadcrumb mobile device padding top with px ( like as 350px ).', 'geoport' ),
          'default' => '260px',
        ),
        array(
          'id'      => 'breadcrumb_m_pb',
          'type'    => 'text',
          'title'   => __( 'Mobile padding bottom', 'geoport' ),
          'desc'   => __( 'Breadcrumb mobile device padding bottom with px ( like as 50px ).', 'geoport' ),
          'default' => '115px',
        ),

      )
    ),

    // Pageloader & scroll to top
    array(
      'name'     => 'switcher_settings',
      'title'    => 'Switcher Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(

        array(
          'type'       => 'heading',
          'content'    => __( 'Preloader Settings', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_preloader_enable',
          'type'       => 'switcher',
          'title'      => __( 'Preloader Switcher', 'geoport' ),
          'desc'       => __( 'Enable/Disable site preloader.', 'geoport' ),
          'default'    => true,
        ),
        array(
          'type'       => 'subheading',
          'content'    => __( 'Spine Preloader', 'geoport' ),
          'dependency' => array( 'geoport_preloader_enable', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_spine_preloader_enable',
          'type'       => 'switcher',
          'title'      => __( 'Spine Preloader Switcher', 'geoport' ),
          'desc'       => __( 'Enable/Disable Spine Preloader.', 'geoport' ),
          'default'    => true,
          'dependency' => array( 'geoport_preloader_enable', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_preloader_circle_color',
          'type'       => 'color_picker',
          'title'      => __( 'Preloader circle color', 'geoport' ),
          'desc'       => __( 'Choose preloader circle color.', 'geoport' ),
          'default'    => 'rgba(0, 0, 0, 0.2)',
          'dependency' => array( 'geoport_preloader_enable|geoport_spine_preloader_enable', '==|==', 'true|true' ),
        ),
        array(
          'id'         => 'geoport_preloader_circle_spine_color',
          'type'       => 'color_picker',
          'title'      => __( 'Preloader circle spine color', 'geoport' ),
          'desc'       => __( 'Choose preloader circle spine color.', 'geoport' ),
          'default'    => '#ff5e14',
          'dependency' => array( 'geoport_preloader_enable|geoport_spine_preloader_enable', '==|==', 'true|true' ),
        ),

        // Text Preloader
        array(
          'type'       => 'subheading',
          'content'    => __( 'Text Preloader', 'geoport' ),
          'dependency' => array( 'geoport_preloader_enable', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_text_preloader_enable',
          'type'       => 'switcher',
          'title'      => __( 'Text Preloader Switcher', 'geoport' ),
          'default'    => true,
          'dependency' => array( 'geoport_preloader_enable', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_preloader_text',
          'type'       => 'text',
          'title'      => __( 'Preloader Text', 'geoport' ),
          'default'    =>  __( 'Geoport', 'geoport' ),
          'desc'       =>  __( 'Put loading texty without space', 'geoport' ),
          'dependency' => array( 'geoport_preloader_enable|geoport_text_preloader_enable', '==|==', 'true|true' ),
        ),
        array(
          'id'         => 'geoport_preloader_color',
          'type'       => 'color_picker',
          'title'      => __( 'Preloader text color', 'geoport' ),
          'desc'       =>  __( 'Choose preloader color', 'geoport' ),
          'default'    => '#000000',
          'dependency' => array( 'geoport_preloader_enable|geoport_text_preloader_enable', '==|==', 'true|true' ),
        ),
        array(
          'id'         => 'geoport_preloader_watermark_color',
          'type'       => 'color_picker',
          'title'      => __( 'Preloader text bg watermark color', 'geoport' ),
          'desc'       =>  __( 'Choose preloader watermark text color', 'geoport' ),
          'default'    => 'rgba(0, 0, 0, 0.2)',
          'dependency' => array( 'geoport_preloader_enable|geoport_text_preloader_enable', '==|==', 'true|true' ),
        ),
        array(
          'id'         => 'geoport_preloader_bg_color',
          'type'       => 'color_picker',
          'title'      => __( 'Preloader background color', 'geoport' ),
          'desc'       =>  __( 'Choose preloader background color', 'geoport' ),
          'default'    => '#ffffff',
          'dependency' => array( 'geoport_preloader_enable|geoport_text_preloader_enable', '==|==', 'true|true' ),
        ),

        // Scroll To Top Settings
        array(
          'type'       => 'heading',
          'content'    => __( 'Scroll to Top', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_scroll_top',
          'type'       => 'switcher',
          'title'      => __( 'Scroll to top on?', 'geoport' ),
          'desc'       =>  __( 'Enable/Disable scroll to top button.', 'geoport' ),
        ),
        array(
          'id'         => 'geoport_scroll_top_icon',
          'type'       => 'icon',
          'title'      => __( 'Scroll to top icon', 'geoport' ),
          'desc'       =>  __( 'Choose scroll to top icon.', 'geoport' ),
          'default'    => 'far fa-level-up',
          'dependency' => array( 'geoport_scroll_top', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_scroll_top_bg_color',
          'type'       => 'color_picker',
          'title'      => __( 'Scroll to top background color', 'geoport' ),
          'desc'       =>  __( 'Choose scroll to top background color.', 'geoport' ),
          'default'    => '#ff5e14',
          'dependency' => array( 'geoport_scroll_top', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_scroll_top_font_color',
          'type'       => 'color_picker',
          'title'      => __( 'Scroll to top font color', 'geoport' ),
          'desc'       =>  __( 'Choose scroll to top font color.', 'geoport' ),
          'default'    => '#ffffff',
          'dependency' => array( 'geoport_scroll_top', '==', 'true' ),
        ),
        array(
          'id'         => 'geoport_scroll_top_border_radius',
          'type'       => 'number',
          'title'      => __( 'Border Radius', 'geoport' ),
          'default'    => '50',
          'desc'       => __( 'Put border radius 1-100, it count as a persantage', 'geoport' ),
          'validate'   => 'numeric',
          'dependency' => array( 'geoport_scroll_top', '==', 'true' ),
        ),

      )
    ),

  ),
);



// ===================================================================
// Typography Settings =
// ===================================================================

$options[]   = array(
  'name'     => 'typography_setting',
  'title'    => __( 'Typography Settings', 'geoport' ),
  'icon'     => 'fal fa-text-height',
  'sections' => array(

    // Body Typography
    array(
      'name'     => 'body_typography',
      'title'    => 'Body Typography',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'heading',
          'content' => __( 'Site Font Settings', 'geoport' ),
        ),
        array(
          'id'           => 'geoport_body_font',
          'type'         => 'typography',
          'title'        => __( 'Body Font', 'geoport' ),
          'desc'         =>  __( 'Select body font family.', 'geoport' ),
          'default'   => array(
            'family'  => 'Karla',
            'font'    => 'google', // this is helper for output ( google, websafe, custom )
          ),
          'variant'   => false,
        ),
        array(
          'id'        => 'geoport_body_font_size',
          'type'      => 'text',
          'title'     => __( 'Body Font Size', 'geoport' ),
          'desc'       =>  __( 'Select body font size.', 'geoport' ),
          'default'   => '16px',
        ),
        array(
          'id'        => 'line_height',
          'type'      => 'text',
          'desc'       =>  __( 'Select body font line height.', 'geoport' ),
          'title'     => __( 'Line Height', 'geoport' ),
          'default'   => '26px',
        ),

      )
    ),
    // Heading Typography
    array(
      'name'     => 'heading_typography',
      'title'    => 'Heading Typography',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'heading',
          'content' => __( 'Heading Font Settings', 'geoport' ),
        ),
        array(
          'id'           => 'geoport_heading_font',
          'type'         => 'typography',
          'title'        => __( 'Heading Font', 'geoport' ),
          'desc'       =>  __( 'Select heading font family.', 'geoport' ),
          'default'   => array(
            'family'  => 'Montserrat',
            'font'    => 'google',
          ),
          'variant'   => false,
        ),
        
      )
    ),
    // Color Typography
    array(
      'name'     => 'color_typography',
      'title'    => 'Color Typography',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'heading',
          'content' => __( 'Site Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'geoport_base_color',
          'type'    => 'color_picker',
          'title'   => __( 'Theme Base Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website default color.', 'geoport' ),
          'default' => '#ff5e14',
        ),
        array(
          'type'    => 'heading',
          'content' => __( 'Default Button Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'geoport_page_base_btn',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button background color.', 'geoport' ),
          'default' => '#34ccff',
        ),
        array(
          'id'      => 'geoport_page_base_btn_text',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button text color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'id'      => 'geoport_page_base_btn_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Icon Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button icon color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'id'      => 'geoport_page_base_btn_hover_bg',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Hover Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button hover background color.', 'geoport' ),
          'default' => '#34ccff',
        ),
        array(
          'id'      => 'geoport_page_base_btn_hover_text',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Hover Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button hover text color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'id'      => 'geoport_page_base_btn_hover_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Page Button Hover Icon Color', 'geoport' ),
          'desc'    =>  __( 'Choose your website button hover  icon color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'type'    => 'heading',
          'content' => __( 'Slider Button Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'geoport_slider_btn_one_bg',
          'type'    => 'color_picker',
          'title'   => __( 'Button One Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button one background color.', 'geoport' ),
          'default' => '#d00c27',
        ),
        array(
          'id'      => 'geoport_slider_btn_one_hover_bg',
          'type'    => 'color_picker',
          'title'   => __( 'Button One Hover Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button one hover background color.', 'geoport' ),
          'default' => '#cee2ff',
        ),
        array(
          'id'      => 'geoport_slider_btn_one_text',
          'type'    => 'color_picker',
          'title'   => __( 'Button One Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button one text color.', 'geoport' ),
          'default' => '#fff',
        ),
        array(
          'id'      => 'geoport_slider_btn_one_hover_text',
          'type'    => 'color_picker',
          'title'   => __( 'Button One Hover Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button one hover text color.', 'geoport' ),
          'default' => '#001d67',
        ),
        array(
          'id'      => 'geoport_slider_btn_two_bg',
          'type'    => 'color_picker',
          'title'   => __( 'Button Two Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button two background color.', 'geoport' ),
          'default' => '#cee2ff',
        ),
        array(
          'id'      => 'geoport_slider_btn_two_hover_bg',
          'type'    => 'color_picker',
          'title'   => __( 'Button Two Hover Background Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button two hover background color.', 'geoport' ),
          'default' => '#d00c27',
        ),
        array(
          'id'      => 'geoport_slider_btn_two_bg_text',
          'type'    => 'color_picker',
          'title'   => __( 'Button Two Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button two text color.', 'geoport' ),
          'default' => '#001d67',
        ),
        array(
          'id'      => 'geoport_slider_btn_two_hover_bg_text',
          'type'    => 'color_picker',
          'title'   => __( 'Button Two Hover Text Color', 'geoport' ),
          'desc'    =>  __( 'Choose slider button two hover text color.', 'geoport' ),
          'default' => '#fff',
        ),
      )
    ),

  ),
);



// ===================================================================
// Header Settings =
// ===================================================================
$options[]   = array(
  'name'     => 'header_setting',
  'title'    => __( 'Header Top Settings', 'geoport' ),
  'icon'     => 'fal fa-heading',
  'sections' => array(

    // Header top part 1
    array(
      'name'     => 'header_one',
      'title'    => 'Header Top Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'heading',
          'content' =>  __( 'Header Settings', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Part On?', 'geoport' ),
          'desc'    => __( 'Enable/Disable Header Top Part.', 'geoport' ),
          'default'    => true,
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Left List', 'geoport' ),
        ),
        array(
          'id'      => 'top_header_left_menus',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Left List On?', 'geoport' ),
          'desc'    => __( 'Enable/Disable Header Left Info', 'geoport' ),
          'default'    => true,
        ),  
        array(
          'id'              => 'header1_left_list',
          'type'            => 'group',
          'title'           =>  __( 'Header top left menu', 'geoport' ),
          'button_title'    =>  __( 'Add New', 'geoport' ),
          'accordion_title' =>  __( 'Add New List', 'geoport' ),
          'fields'          => array(
            array(
              'id'          => 'list_text',
              'type'        => 'text',
              'title'       =>  __( 'Text', 'geoport' ),
            ),
            array(
              'id'          => 'list_link',
              'type'        => 'text',
              'title'       =>  __( 'Link', 'geoport' ),
            ),
            array(
              'id'          => 'list_icon',
              'type'        => 'icon',
              'title'       =>  __( 'Icon', 'geoport' ),
            ),
          ),
          'default'   => array(
            array(
              'list_text'         => 'Info & Quote',
              'list_link'         => '#',
              'list_icon'         => '',
            ),
            array(
              'list_text'         => 'Refund Policy',
              'list_link'         => '#',
              'list_icon'         => '',
            ),
          ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Right List', 'geoport' ),
        ),
        array(
          'id'      => 'top_header_rightlist',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Right List On?', 'geoport' ),
          'desc'    => __( 'Enable/Disable Header Right Info', 'geoport' ),
          'default'    => true,
        ),        
        array(
          'id'              => 'header1_right_list',
          'type'            => 'group',
          'title'           =>  __( 'Header top right menu', 'geoport' ),
          'button_title'    =>  __( 'Add New', 'geoport' ),
          'accordion_title' =>  __( 'Add New List', 'geoport' ),
          'fields'          => array(
            array(
              'id'          => 'list_text',
              'type'        => 'text',
              'title'       =>  __( 'Text', 'geoport' ),
            ),
            array(
              'id'          => 'list_link',
              'type'        => 'text',
              'title'       =>  __( 'Link', 'geoport' ),
            ),
            array(
              'id'          => 'list_icon',
              'type'        => 'icon',
              'title'       =>  __( 'Icon', 'geoport' ),
            ),
          ),
          'default'   => array(
            array(
              'list_text'         => 'Career',
              'list_link'         => '#',
              'list_icon'         => 'far fa-briefcase',
            ),
            array(
              'list_text'         => 'Terms & Conditions',
              'list_link'         => '#',
              'list_icon'         => '',
            ),
          ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Contact List', 'geoport' ),
        ),
        array(
          'id'      => 'top_header_left_off',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Contact On?', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top left contact info.', 'geoport' ),
          'default' => true,
        ),        
        array(
          'id'              => 'header3_left_list',
          'type'            => 'group',
          'title'           =>  __( 'Header top left menu', 'geoport' ),
          'button_title'    =>  __( 'Add New', 'geoport' ),
          'accordion_title' =>  __( 'Add New List', 'geoport' ),
          'fields'          => array(
            array(
              'id'          => 'list_text',
              'type'        => 'text',
              'title'       =>  __( 'Text', 'geoport' ),
            ),
            array(
              'id'          => 'list_link',
              'type'        => 'text',
              'title'       =>  __( 'Link', 'geoport' ),
            ),
            array(
              'id'          => 'list_icon',
              'type'        => 'icon',
              'title'       =>  __( 'Icon', 'geoport' ),
            ),
          ),
          'default'   => array(
            array(
              'list_text'         => 'info@webmail.com',
              'list_link'         => 'info@webmail.com',
              'list_icon'         => 'far fa-envelope',
            ),
            array(
              'list_text'         => '+767 575 765 60',
              'list_link'         => '+76757576560',
              'list_icon'         => 'far fa-phone',
            ),
          ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Header Social Buttons', 'geoport' ),
        ),
        array(
          'id'      => 'top_header_off',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Social Info On?', 'geoport' ),
          'desc'    => __( 'Enable/Disable Header Social Info', 'geoport' ),
          'default'    => true,
        ),

        // Color Settings
        array(
          'type'       => 'subheading',
          'content'    => __( 'Header Top Part Color', 'geoport' ),
        ),
        array(
          'id'         => 'h1top_bg_color',
          'type'       => 'color_picker',
          'title'      => __( 'Top Background Color', 'geoport' ),
          'default'    => 'transparent',
        ),
        array(
          'id'         => 'h1top_font_color',
          'type'       => 'color_picker',
          'title'      => __( 'Top Text Color', 'geoport' ),
          'default'    => '#ffffff',
        ),
        array(
          'id'         => 'h1top_hover_font_color',
          'type'       => 'color_picker',
          'title'      => __( 'Top link hover color', 'geoport' ),
          'default'    => '#34ccff',
        ),
        array(
          'id'         => 'h1top_border_color',
          'type'       => 'color_picker',
          'title'      => __( 'Top border color', 'geoport' ),
          'default'    => 'rgba(255,255,255,.2)',
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Responsive ( Medium Device ) Settings', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_1medium_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top left menu on medium device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_2medium_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Right Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right menu on medium device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_3medium_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Social Icon', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right social icon on medium device', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Responsive ( Small Device ) Settings', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_1small_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top left menu on small device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_2small_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Right Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right menu on small device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_3small_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Social Icon', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right social icon on small device', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Top Header Responsive ( Extra Small Device ) Settings', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_1exsmall_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top left menu on extra small device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_2exsmall_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Right Menu', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right menu on extra small device', 'geoport' ),
        ),
        array(
          'id'      => 'top_header1_3exsmall_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Header Top Left Social Icon', 'geoport' ),
          'desc'    => __( 'Enable/Disable header top right social icon on extra small device', 'geoport' ),
        ),
      )
    ),

    // Default Header Settings
    array(
      'name'     => 'default_header_settings',
      'title'    => 'Header Style Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(

        array(
          'type'    => 'heading',
          'content' => __( 'Choose Header Style', 'geoport' ),
        ),
        array(
          'id'          => 'default_header_style',
          'type'        => 'image_select',
          'title'       => __( 'Select Default Header Style', 'geoport' ),
          'options'     => array(
            'style1'    => GEOPORT_PLG_URL. 'assets/imgs/header1.jpg',
            'style2'    => GEOPORT_PLG_URL. 'assets/imgs/header2.jpg',
            'style3'    => GEOPORT_PLG_URL. 'assets/imgs/header3.jpg',
          ),
          'desc'   => __( 'This is all default page header style if you select','geoport'),
        ),
        array(
          'id'          => 'default_h2_top',
          'type'        => 'text',
          'title'       =>  __( 'Header 2 padding top', 'geoport' ),
          'default'     => '290px',
          'dependency'  => array( 'default_header_style_style2', '==', 'true' ),
          'desc'        => __( 'When select header 2, you need to breadcrumb top padding reset if you feel need', 'geoport' ),
        ),
        array(
          'id'          => 'default_h2_bottom',
          'type'        => 'text',
          'title'       =>  __( 'Header 2 padding bottom', 'geoport' ),
          'default'     => '135px',
          'dependency'  => array( 'default_header_style_style2', '==', 'true' ),
          'desc'        => __( 'When select header 2, you need to breadcrumb bottom padding reset if you feel need', 'geoport' ),
        ),
        array(
          'id'          => 'default_h3_top',
          'type'        => 'text',
          'title'       =>  __( 'Header 3 padding top', 'geoport' ),
          'default'     => '325px',
          'dependency'  => array( 'default_header_style_style3', '==', 'true' ),
          'desc'        => __( 'When select header 3, you need to breadcrumb top padding reset if you feel need', 'geoport' ),
        ),
        array(
          'id'          => 'default_h3_bottom',
          'type'        => 'text',
          'title'       =>  __( 'Header 3 padding bottom', 'geoport' ),
          'default'     => '135px',
          'dependency'  => array( 'default_header_style_style3', '==', 'true' ),
          'desc'        => __( 'When select header 3, you need to breadcrumb bottom padding reset if you feel need', 'geoport' ),
        ),

      )
    ),
  ),
);



// ===================================================================
//  Team Settings
// ===================================================================
$options[]      = array(
  'name'        => 'socials_setting',
  'title'       => __( 'Social Profile Settings', 'geoport' ),
  'icon'        => 'fal fa-users',
  'fields'      => array(

    array(
      'type'    => 'subheading',
      'content' => __( 'Social Profile Settings', 'geoport' ),
    ),
    array(
      'id'              => 'header1_social_btn',
      'type'            => 'group',
      'title'           =>  __( 'Header Social List', 'geoport' ),
      'button_title'    =>  __( 'Add New', 'geoport' ),
      'accordion_title' =>  __( 'Add New Social Button', 'geoport' ),
      'fields'          => array(
        array(
          'id'          => 'social_icon',
          'type'        => 'icon',
          'title'       =>  __( 'Social Icon', 'geoport' ),
        ),
        array(
          'id'          => 'social_link',
          'type'        => 'text',
          'title'       =>  __( 'Link', 'geoport' ),
        ),
      ),
      'default'   => array(
        array(
          'social_icon'         => 'fab fa-facebook-f',
          'social_link'         => '#',
        ),
        array(
          'social_icon'         => 'fab fa-twitter',
          'social_link'         => '#',
        ),
        array(
          'social_icon'         => 'fab fa-behance',
          'social_link'         => '#',
        ),
        array(
          'social_icon'         => 'fab fa-linkedin-in',
          'social_link'         => '#',
        ),
        array(
          'social_icon'         => 'fab fa-youtube',
          'social_link'         => '#',
        ),
      ),
    ),

  )
);



// ===================================================================
// Menu Settings =
// ===================================================================
$options[]   = array(
  'name'     => 'geoport_menu',
  'title'    => __( 'Menu Settings', 'geoport' ),
  'icon'     => 'fal fa-bars',
  'sections' => array(

    // Menu 1
    array(
      'name'     => 'menu_1',
      'title'    => 'Menu Color Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'subheading',
          'content' => __( 'Menu Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Area Background Color', 'geoport' ),
          'default' => 'transparent',
          'desc'    => __( 'Pick Menu Area Background Color', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Menu Text Color', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_hover_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Hover Text Color', 'geoport' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Menu Hover Text Color', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_active_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Active Color', 'geoport' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Menu Text Active Color', 'geoport' ),
        ),
        array(
          'id'      => 'menu_font_size',
          'type'    => 'text',
          'title'   => __( 'Menu Text Font Size', 'geoport' ),
          'default' => __( '16px', 'geoport' ),
          'desc'    => __( 'Menu Text Font Size. Default font-size:15px', 'geoport' ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),
        array(
          'id'             => 'menu_font_weight',
          'type'           => 'select',
          'title'          => 'Menu Text Font Weight',
          'desc'           => __( 'Select Menu Text Font Weight', 'geoport' ),
          'class'          => 'chosen',
          'options'        => array(
            '700'        => '700',
            '600'        => '600',
            '500'        => '500',
            '400'        => '400',
            '300'        => '300',
          ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),
        array(
          'id'             => 'menu_text_transform',
          'type'           => 'select',
          'title'          => 'Menu Text Transform',
          'desc'           => __( 'Select Menu Text Transform', 'geoport' ),
          'class'          => 'chosen',
          'options'        => array(
            'capitalize'   => 'Capitalized',
            'uppercase'    => 'Uppercase',
            'lowercase'    => 'Lowercase',
            'unset'        => 'Default',
          ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),

        // Menu 1 Submenu
        array(
          'type'    => 'subheading',
          'content' => __( 'DropDown Menu Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'submenu1_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Background Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick DropDown Menu Area Background Color', 'geoport' ),
        ),
        array(
          'id'      => 'submenu1_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Text Color', 'geoport' ),
          'default' => '#244b5a',
          'desc'    => __( 'Pick DropDown Menu Text Color', 'geoport' ),
        ),
        array(
          'id'             => 'menu_drop_transform',
          'type'           => 'select',
          'title'          => 'Dropdown Menu Text Transform',
          'desc'           => __( 'Select DropDown Menu Text Transform', 'geoport' ),
          'class'          => 'chosen',
          'options'        => array(
            'capitalize'   => 'Capitalized',
            'uppercase'    => 'Uppercase',
            'lowercase'    => 'Lowercase',
            'unset'        => 'Default',
          ),
          'attributes'     => array(
            'style'        => 'width: 150px;'
          ),
        ),
        array(
          'id'      => 'drops1_menus_hover_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Hover Background Color', 'geoport' ),
          'desc'    => __( 'Pick Dropdown menu hover background color.', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick DropDown Menu Hover Background Color', 'geoport' ),
        ),
        array(
          'id'      => 'submenu1_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Hover Text Color', 'geoport' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick DropDown Menu Hover Text Color', 'geoport' ),
        ),
        array(
          'id'      => 'submenu1_border_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Border Color', 'geoport' ),
          'default' => '#eceef0',
          'desc'    => __( 'Pick DropDown Menu Border Color', 'geoport' ),
        ),
      )
    ),


    // All Menu
    array(
      'name'     => 'sticky_menu',
      'title'    => 'Sticky Menu Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        // Sticky menu
        array(
          'type'    => 'subheading',
          'content' => __( 'Sticky Menu Settings', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_menu_switch',
          'type'    => 'switcher',
          'title'   =>  __( 'Sticky Menu Off?', 'geoport' ),
          'desc'    => __( 'Enable/Disable Sticky menu.', 'geoport' ),
          'default' =>  true,
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Sticky Menu Color Settings', 'chariton' ),
        ),
        array(
          'id'      => 'menu1_sticky_menu_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Background Color', 'chariton' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Sticky Menu Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_menu1_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Color', 'chariton' ),
          'default' => '#568ea5',
          'desc'    => __( 'Pick Sticky Menu Text Color.', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_menu1_hover_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Hover Color', 'chariton' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Sticky Menu Text Hover Color.', 'geoport' ),
        ),

        // Sticky Menu 1 Submenu
        array(
          'type'    => 'subheading',
          'content' => __( 'Sticky DropDown Menu Color Settings', 'chariton' ),
        ),
        array(
          'id'      => 'sticky_submenu1_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky DropDown Menu Background Color', 'chariton' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Sticky Dropdown Menu Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_submenu1_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky DropDown Menu Text Color', 'chariton' ),
          'default' => '#568ea5',
          'desc'    => __( 'Pick Sticky Dropdown Menu Text Color.', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_drops1_menus_hover_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Hover Background Color', 'geoport' ),
          'desc'    => __( 'Pick Dropdown Menu Hover Background Color.', 'geoport' ),
          'default' => '#ffffff',
        ),
        array(
          'id'      => 'sticky_submenu1_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky DropDown Menu Hover Font Color', 'chariton' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Sticky Dropdown Menu Hover Font Color.', 'geoport' ),
        ),
        array(
          'id'      => 'sticky_submenu1_border_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky DropDown Menu Border Color', 'chariton' ),
          'default' => '#eceef0',
          'desc'    => __( 'Pick Sticky Dropdown Menu Border Color.', 'geoport' ),
        ),
       )
    ),

    // Menu Responsive Settings
    array(
      'name'     => 'responsive_settings_menu',
      'title'    => 'Responsive Menu Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        // Menu Responsive
        array(
          'type'    => 'subheading',
          'content' => __( 'Responsive Menu Color Settings', 'geoport' ),
        ),
        array(
          'id'      => 'responsive_menu1_bacg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Responsive Menu Background Color', 'geoport' ),
          'default' => '#001d67',
          'desc'    => __( 'Pick Responsive Menu Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'responsive_menu1_bgtext_color',
          'type'    => 'color_picker',
          'title'   => __( 'Responsive Menu Text Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Responsive Menu Text Color.', 'geoport' ),
        ),
        array(
          'id'      => 'responsive_menu1_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Responsive Menu Icon color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Responsive Menu Icon Color.', 'geoport' ),
        ),
        array(
          'id'      => 'stickyresponsive_menu1_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Responsive Menu Icon color', 'geoport' ),
          'default' => '#001d67',
          'desc'    => __( 'Pick Sticky Responsive Menu Icon Color.', 'geoport' ),
        ),
       )
    ),

    // Track Order Settings
    array(
      'name'     => 'trackorders_settings_menu',
      'title'    => 'Track Order Button Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        // Menu button
        array(
          'type'    => 'subheading',
          'content' => __( 'Track Order Button Settings', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_switch',
          'type'    => 'switcher',
          'title'   =>  __( 'Track Order Button Switcher', 'geoport' ),
          'default' =>  true,
          'desc'    => __( 'Enable/Disable Track Order Button.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_icon',
          'type'    => 'icon',
          'title'   => __( 'Track Order Button Icon', 'geoport' ),
          'default' => 'fal fa-truck',
          'desc'    => __( 'Choose Track Order Button Icon.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_text',
          'type'    => 'text',
          'title'   => __( 'Track Order Button Text', 'geoport' ),
          'default' =>  __( 'Track Your Order', 'geoport' ),
          'desc'    => __( 'Input Track Order Button Text.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_link',
          'type'    => 'text',
          'title'   => __( 'Track Order Button Link', 'geoport' ),
          'default' => '#',
          'desc'    => __( 'Insert Track Order Button Link.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_target_link',
          'type'    => 'select',
          'title'   => __( 'Open Button Link', 'geoport' ),
          'desc'    => __( 'Open Track Order Button Link.', 'geoport' ),
          'options'      => array(
            '_self' => 'Self',
            '_blank' => 'Blank',
          ),
          'default'        => '_self'
        ),
        array(
          'id'      => 'menu1_btn_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Background Color', 'geoport' ),
          'default' => 'transparent',
          'desc'    => __( 'Pick Track Order Button Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Font Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Track Order Button Text Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_border_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Border Color', 'geoport' ),
          'default' => '#d7edff',
          'desc'    => __( 'Pick Track Order Button Border Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_hf_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Hover Font Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Track Order Button Hover Text Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_hb_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Hover Background Color', 'geoport' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Track Order Button Hover Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_btn_hb_boxshadow_color',
          'type'    => 'color_picker',
          'title'   => __( 'Button Hover Boxshadow', 'geoport' ),
          'default'    => 'rgba(255, 94, 20, 0.4)',
          'desc'    => __( 'Pick Track Order Button Boxshadow Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_stickybtn_hbg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Button Background Color', 'geoport' ),
          'default' => '#ff5e14',
          'desc'    => __( 'Pick Track Order Button Sticky Background Color.', 'geoport' ),
        ),
        array(
          'id'      => 'menu1_stickybtn_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Button Font Color', 'geoport' ),
          'default' => '#ffffff',
          'desc'    => __( 'Pick Track Order Button Sticky Text Color.', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Track Order Button Responsive Settings', 'geoport' ),
        ),
        array(
          'id'      => 'menu_track1_1medium_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Order Button ( Medium Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable track order button on medium device', 'geoport' ),
        ),
        array(
          'id'      => 'menu_track1_1small_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Order Button ( Small Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable track order button on small device', 'geoport' ),
        ),
        array(
          'id'      => 'menu_track1_1exsmall_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Order Button ( Extra Small Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable track order button on extra small device', 'geoport' ),
        ),
       )
    ),

    // Language Menu Settings
    array(
      'name'     => 'language_menu',
      'title'    => 'Language Settings',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        // Language button
        array(
          'type'    => 'subheading',
          'content' => __( 'Language Button Settings', 'geoport' ),
        ),
        array(
          'id'      => 'lan1_btn_switch',
          'type'    => 'switcher',
          'title'   =>  __( 'Language Button Switcher', 'geoport' ),
          'desc'    => __( 'Enable/Disable language button.', 'geoport' ),
        ),
        array(
          'id'         => 'lan1_btn_shortcode_images',
          'type'       => 'image',
          'title'      => __( 'Language Default Icon', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/lan/english.png',
          'desc'       => __( 'Upload your best size icon for default language.', 'geoport' ),
        ),
        array(
          'id'      => 'lan1_btn_sitem_color',
          'type'    => 'color_picker',
          'title'   => __( 'Language Item Active Color', 'geoport' ),
          'default' => '#ded6d6',
        ),
        array(
          'id'      => 'lan1_btn_shortcode',
          'type'    => 'textarea',
          'title'   => __( 'Language Button Shortcode', 'geoport' ),
          'desc'    => __( 'Insert language button shortcode. Use this shortcode for polylang flags [geoportpolylang]', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' =>  __( 'Language Button Responsive Settings', 'geoport' ),
        ),
        array(
          'id'      => 'menu_lang1_1medium_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Language Button ( Medium Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable language button on medium device', 'geoport' ),
        ),
        array(
          'id'      => 'menu_lang1_1small_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Language Button ( Small Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable language button on small device', 'geoport' ),
        ),
        array(
          'id'      => 'menu_lang1_1exsmall_device',
          'type'    => 'switcher',
          'title'   =>  __( 'Enable Language Button ( Extra Small Device )', 'geoport' ),
          'desc'    => __( 'Enable/Disable language button on extra small device', 'geoport' ),
        ),
       )
    ),

  ),
);


// ===================================================================
// Blog Settings = 
// ===================================================================
$options[]      = array(
  'name'        => 'blog_setting',
  'title'       =>  __( 'Blog Page Settings', 'geoport' ),
  'icon'        => 'fal fa-blog',
   // begin: fields
  'fields'      => array(

    array(
      'type'         => 'heading',
      'content'      =>  __( 'Blog Page Setting', 'geoport' ),
    ),

    array(
      'id'      => 'blog_page_breadcrumb',
      'type'    => 'text',
      'title'   => __( 'Post Details Beradcrumb Title', 'geoport' ),
      'default' => __( 'Blog Posts', 'geoport' ),
      'desc'    => __( 'Change blog post details breadcrumb title text.', 'geoport' ),
    ),
  
    array(
      'id'           => 'blog_layout',
      'type'         => 'image_select',
      'title'        =>  __( 'Page Layout Style', 'geoport' ),
      'options'      => array(
        'left-sidebar'  => GEOPORT_PLG_URL. 'assets/imgs/sidebar_l.jpg',
        'right-sidebar' => GEOPORT_PLG_URL. 'assets/imgs/sidebar_r.jpg',
        'full-width'    => GEOPORT_PLG_URL. 'assets/imgs/fullwidth.jpg',
        ),
    ),
    array(
      'id'      => 'blog_post_date',
      'type'    => 'switcher',
      'title'   => __( 'Post date meta on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_post_views',
      'type'    => 'switcher',
      'title'   => __( 'Post view meta on?', 'geoport' ),
    ),
    array(
      'id'      => 'blog_post_comments',
      'type'    => 'switcher',
      'title'   => __( 'Post comment meta on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_post_admin',
      'type'    => 'switcher',
      'title'   => __( 'Post meta admin on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_post_cats_admin',
      'type'    => 'switcher',
      'title'   => __( 'Post Categories on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_post_readm_admin',
      'type'    => 'switcher',
      'title'   => __( 'Post Readmore Button on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'geoport_post_excerpt_length',
      'type'    => 'text',
      'title'   =>  __( 'Blog post content excerpt length', 'geoport' ),
      'default' => '50',
    ),
    array(
      'id'      => 'geoport_blog_heading_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Blog Heading Text color', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Blog Heading Text color.', 'geoport' ),
    ),


    array(
      'type'    => 'subheading',
      'content' =>  __( 'Blog Single Page Settings', 'geoport' ),
    ),

    array(
      'id'           => 'blog_single_layout',
      'type'         => 'image_select',
      'title'        =>  __( 'Single Layout Style', 'geoport' ),
      'options'      => array(
        'left-sidebar'  => GEOPORT_PLG_URL. 'assets/imgs/sidebar_l.jpg',
        'right-sidebar' => GEOPORT_PLG_URL. 'assets/imgs/sidebar_r.jpg',
        'full-width'    => GEOPORT_PLG_URL. 'assets/imgs/fullwidth.jpg',
        ),
    ),

    array(
      'id'      => 'blog_single_post_admin',
      'type'    => 'switcher',
      'title'   => __( 'Post meta admin on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_single_post_date',
      'type'    => 'switcher',
      'title'   => __( 'Post meta date on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'      => 'blog_single_post_cats',
      'type'    => 'switcher',
      'title'   => __( 'Post categories on?', 'geoport' ),
      'default' => true,
    ),
    array(
      'id'           => 'geoport_post_details_tag_enable',
      'type'         => 'switcher',
      'title'        => __( 'Post details tags On/Off', 'geoport' ),
      'default'      => true,
    ),
    array(
      'id'           => 'geoport_post_details_share_enable',
      'type'         => 'switcher',
      'title'        => __( 'Post details share On/Off', 'geoport' ),
    ),
    array(
      'id'           => 'blog_single_rp_switch',
      'type'         => 'switcher',
      'title'        => __( 'Post details related post On/Off', 'geoport' ),
    ),
    array(
      'id'       => 'rp_grid_columns',
      'type'     => 'select',
      'title'    => __( 'Select Related Post Columns', 'geoport' ),
      'options'  => array(
        '6' => __( '2 Columns', 'geoport' ),
        '4' => __( '3 Columns', 'geoport' ),
        '3' => __( '4 Columns', 'geoport' ),
      ),
      'default'  => '6',
      'dependency'   => array( 'blog_single_rp_switch', '==', 'true' ),
    ),

    array(
      'type'    => 'subheading',
      'content' =>  __( 'Blog Sidebar Color Settings', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_sidebar_widgets_color',
      'type'    => 'color_picker',
      'title'   => __( 'Blog Widgets Title color', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Blog widget title color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_sidebar_widgets_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Blog Widgets Text color', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Blog widget Text color.', 'geoport' ),
    ),
  
  )
);



// ===================================================================
// Service Settings = 
// ===================================================================
$options[]      = array(
  'name'        => 'service_setting',
  'title'       =>  __( 'Service Page Settings', 'geoport' ),
  'icon'        => 'fal fa-briefcase',
   // begin: fields
  'fields'      => array(

    array(
      'type'    => 'subheading',
      'content' =>  __( 'Service Details Page', 'geoport' ),
    ),

    array(
      'id'           => 'service_details_layout',
      'type'         => 'image_select',
      'title'        =>  __( 'Service Single Page Layout', 'geoport' ),
      'options'      => array(
        'left-sidebar'  => GEOPORT_PLG_URL. 'assets/imgs/sidebar_l.jpg',
        'right-sidebar' => GEOPORT_PLG_URL. 'assets/imgs/sidebar_r.jpg',
        'full-width'    => GEOPORT_PLG_URL. 'assets/imgs/fullwidth.jpg',
        ),
      'desc'       => __( 'Choose your service single post layout style.', 'geoport' ),
    ),
    array(
      'id'      => 'service_post_details_thumbnail',
      'type'    => 'switcher',
      'title'   => __( 'Service Post Thumbnail on?', 'geoport' ),
      'desc'    => __( 'Enable/Disable service post Thumbnail.', 'geoport' ),
      'default' => false,
    ),
    array(
      'id'      => 'prev_and_next_post_switch',
      'type'    => 'switcher',
      'title'   => __( 'Service Pagination switch on?', 'geoport' ),
      'desc'    => __( 'Previous and next post switching', 'geoport' ),
      'desc'    => __( 'Enable/Disable service single pagination.', 'geoport' ),
      'default' => true,
    ),

    array(
      'type'    => 'subheading',
      'content' =>  __( 'Download Brochures Widgets Settings', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_bg_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Background Color', 'geoport' ),
      'default' => '#ff8400',
      'desc'    => __( 'Download Brochures widget area background color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_title_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Title Color', 'geoport' ),
      'default' => '#ffffff',
      'desc'    => __( 'Download Brochures widget title color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_subtitle_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Sub Title Color', 'geoport' ),
      'default' => '#ffffff',
      'desc'    => __( 'Download Brochures widget sub title color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btnbg_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Background', 'geoport' ),
      'default' => '#f3f9ff',
      'desc'    => __( 'Download Brochures widget button background color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btntext_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Text', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Download Brochures widget button text color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btnicon_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Icon', 'geoport' ),
      'default' => '#ff8400',
      'desc'    => __( 'Download Brochures widget button icon color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btnarrow_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Arrow', 'geoport' ),
      'default' => '#c4cde3',
      'desc'    => __( 'Download Brochures widget button arrow icon color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btnhover_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Hover Background', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Download Brochures widget button background color.', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_brochures_widgets_btnhover_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Download Brochures Button Hover Text', 'geoport' ),
      'default' => '#fff',
      'desc'    => __( 'Download Brochures widget button hover text color.', 'geoport' ),
    ),



    array(
      'type'    => 'subheading',
      'content' =>  __( 'Get In Touch Widgets Settings', 'geoport' ),
    ),
    array(
      'id'      => 'geoport_touch_widgets_bg_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Background Color', 'geoport' ),
      'default' => '#001d67',
      'desc'    => __( 'Get in touch widget area background color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_title_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Title Color', 'geoport' ),
      'default' => '#ffffff',
      'desc'    => __( 'Get in touch widget title color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_subtitle_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Sub Title Color', 'geoport' ),
      'default' => '#a8b6d9',
      'desc'    => __( 'Get in touch widget sub title color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_btnbg_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Button Background', 'geoport' ),
      'default' => '#ff8400',
      'desc'    => __( 'Get in touch widget button background color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_btntext_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Button Text', 'geoport' ),
      'default' => '#ffffff',
      'desc'    => __( 'Get in touch widget button text color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_btnhover_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Button Hover Background', 'geoport' ),
      'default' => '#ff8400',
      'desc'    => __( 'Get in touch widget button hover background color.', 'geoport' ),
    ),

    array(
      'id'      => 'geoport_touch_widgets_btnhovertext_color',
      'type'    => 'color_picker',
      'title'   => __( 'Get In Touch Button Hover Text', 'geoport' ),
      'default' => '#ffffff',
      'desc'    => __( 'Get in touch widget button hover text color.', 'geoport' ),
    ),

  
  )
);



// ===================================================================
//  Team Settings
// ===================================================================
$options[]      = array(
  'name'        => 'team_setting',
  'title'       => __( 'Team Settings', 'geoport' ),
  'icon'        => 'fal fa-users',
  'fields'      => array(
    
    array(
      'type'    => 'subheading',
      'content' => __( 'Team Single Page Settings', 'geoport' ),
    ),
    array(
      'id'         => 'team_details_breadcrumb_title',
      'type'       => 'text',
      'title'      => __( 'Team Breadcrumb Title', 'geoport' ),
      'default'    => __( 'Team Details', 'geoport' ),
      'desc'       => __( 'Team single page breadcrumb title.', 'geoport' ),
    ),
    array(
      'id'         => 'team_post_slug',
      'type'       => 'text',
      'title'      => __( 'Team Post Slug', 'geoport' ),
      'default'    => 'team',
      'desc'       => __( 'Change the team post type slug but when you change the slug you need to update your permalink again.', 'geoport' ),
    ),
    array(
      'id'         => 'chariton_team_gutenberg',
      'type'       => 'switcher',
      'title'      => __( 'Gutenberg Enable/Disable Switch', 'geoport' ),
      'desc'       => __( 'Enable/Disable Gutenberg on the team post type.', 'geoport' ),
      'default'    => true,
    ),
  )
);

/*-----------------------------------------------------------------------------------------*
*   Geoport Shop Settings
* -----------------------------------------------------------------------------------------*/

$options[]      = array(
  'name'        => 'shop_setting',
  'title'       => __( 'WooCommerce Settings', 'geoport' ),
  'icon'        => 'fa fa-shopping-bag',
  'fields'      => array(

    array(
      'type'    => 'heading',
      'content' => __( 'Shop Page Settings', 'geoport' ),
    ),
    array(
      'id'    => 'shop_posts_per_page',
      'type'  => 'text',
      'title' => __( 'Products per page', 'geoport' ),
      'default' => '12',
    ),
    array(
      'id'           => 'shop_layout',
      'type'         => 'image_select',
      'title'        => __( 'Shop Page Layout', 'geoport' ),
      'options'      => array(
        'left-sidebar'  => GEOPORT_PLG_URL. 'assets/imgs/sidebar_l.jpg',
        'right-sidebar' => GEOPORT_PLG_URL. 'assets/imgs/sidebar_r.jpg',
        'full-width'    => GEOPORT_PLG_URL. 'assets/imgs/fullwidth.jpg',
        ),
      'desc'       => __( 'Choose page layout style.', 'geoport' ),
    ),
    array(
      'id'           => 'product_col_layout',
      'type'         => 'image_select',
      'title'        => __( 'Product column layout', 'geoport' ),
      'options'      => array(
        '2' => GEOPORT_PLG_URL. 'assets/imgs/columns-2.png',
        '3' => GEOPORT_PLG_URL. 'assets/imgs/columns-3.png',
        '4' => GEOPORT_PLG_URL. 'assets/imgs/columns-4.png',
        ),
    ),

    array(
      'type'    => 'subheading',
      'content' => __( 'Shop Single Page Settings', 'geoport' ),
    ),
    array(
      'id'           => 'related_products',
      'type'         => 'switcher',
      'title'        => __( 'Enable/Disable Related Product', 'geoport' ),
      'default'      => true,
      'desc'    => __( 'Enable/Disable related product on product details page.', 'geoport' ),
    ),
    array(
      'id'           => 'related_product_col_layout',
      'type'         => 'image_select',
      'title'        => __( 'Related product column layout', 'geoport' ),
      'options'      => array(
        '2' => GEOPORT_PLG_URL. 'assets/imgs/columns-2.png',
        '3' => GEOPORT_PLG_URL. 'assets/imgs/columns-3.png',
        '4' => GEOPORT_PLG_URL. 'assets/imgs/columns-4.png',
      ),
    ),
    array(
      'id'           => 'related_products_per_page',
      'type'         => 'text',
      'title'        => __( 'Related product per page', 'geoport' ),
      'default'      => 3,
      'desc'    => __( 'How much related product display in single page', 'geoport' ),
    ),

    array(
      'type'    => 'subheading',
      'content' => __( 'Shop Global Color Settings', 'geoport' ),
    ),
    array(
      'id'      => 'shop_base_color',
      'type'    => 'color_picker',
      'title'   => __( 'Shop base color', 'geoport' ),
      'desc'    => __( 'Change your all shop base color.', 'geoport' ),
      'default' => '#ff5e14',
    ),

    array(
      'type'    => 'subheading',
      'content' => __( 'Shop Button Color Settings', 'geoport' ),
    ),
    array(
      'id'      => 'shop_btnbg_color',
      'type'    => 'color_picker',
      'title'   => __( 'Button Background color', 'geoport' ),
      'desc'    => __( 'Change shop button background color.', 'geoport' ),
      'default' => '#ff5e14',
    ),
    array(
      'id'      => 'shop_btn_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Button Text color', 'geoport' ),
      'desc'    => __( 'Change shop button text color.', 'geoport' ),
      'default' => '#ffffff',
    ),

    array(
      'id'      => 'shop_btnbg_hover_color',
      'type'    => 'color_picker',
      'title'   => __( 'Button Hover Background color', 'geoport' ),
      'desc'    => __( 'Change shop button hover background color.', 'geoport' ),
      'default' => '#ff5e14',
    ),
    array(
      'id'      => 'shop_btnbg_hover_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Button Hover Text color', 'geoport' ),
      'desc'    => __( 'Change shop button hover text color.', 'geoport' ),
      'default' => '#ffffff',
    ),

    array(
      'type'    => 'subheading',
      'content' => __( 'Shop Pagination Color Settings', 'geoport' ),
    ),
    array(
      'id'      => 'shop_pagination_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Background color', 'geoport' ),
      'desc'    => __( 'Change shop pagination background color.', 'geoport' ),
      'default' => '#fdf5f5',
    ),
    array(
      'id'      => 'shop_pagination_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Text color', 'geoport' ),
      'desc'    => __( 'Change shop pagination text color.', 'geoport' ),
      'default' => '#001d67',
    ),

    array(
      'id'      => 'shop_pagination_hover_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Hover Background color', 'geoport' ),
      'desc'    => __( 'Change shop pagination hover background color.', 'geoport' ),
      'default' => '#ff5e14',
    ),
    array(
      'id'      => 'shop_pagination_hover_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Hover Text color', 'geoport' ),
      'desc'    => __( 'Change shop pagination hover text color.', 'geoport' ),
      'default' => '#ffffff',
    ),  

    array(
      'id'      => 'shop_pagination_active_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Active Background color', 'geoport' ),
      'desc'    => __( 'Change shop pagination active background color.', 'geoport' ),
      'default' => '#ff5e14',
    ),
    array(
      'id'      => 'shop_pagination_active_text_color',
      'type'    => 'color_picker',
      'title'   => __( 'Pagination Active Text color', 'geoport' ),
      'desc'    => __( 'Change shop pagination active text color.', 'geoport' ),
      'default' => '#ffffff',
    ),    
  
  )
);






// ===================================================================
// 404 Page Settings = 
// ===================================================================

$options[]      = array(
  'name'        => '404_page',
  'title'       =>  __( '404 Page Settings', 'geoport' ),
  'icon'        => 'fal fa-exclamation-triangle',
  // begin: fields
  'fields'      => array(

    array(
      'type'    => 'heading',
      'content' => __( '404 Page Settings', 'geoport' ),
    ),

    array(
      'id'      => '404_breadcrumb_title',
      'type'    => 'text',
      'title'   =>  __( '404 Breadcrumb Title', 'geoport' ),
      'desc'    => __( '404 page breadcrumb title.', 'geoport' ),
      'default' => __( '404 Error', 'geoport' ),
    ),
    array(
      'id'      => '404_text',
      'type'    => 'text',
      'title'   =>  __( '404 Text', 'geoport' ),
      'desc'    => __( '404 page text.', 'geoport' ),
      'default' => __( '404', 'geoport' ),
    ),
    array(
      'id'      => '404_page_title',
      'type'    => 'text',
      'title'   =>  __( '404 Page Title', 'geoport' ),
      'desc'    => __( '404 page Title.', 'geoport' ),
      'default' => __( 'Sorry Page Not Found', 'geoport' ),
    ),
    array(
      'id'      => '404_btn_txt',
      'type'    => 'text',
      'title'   =>  __( '404 Button Text', 'geoport' ),
      'desc'    => __( '404 page button title.', 'geoport' ),
      'default' => __( ' Go Back Home', 'geoport' ),
    ), 
    array(
      'id'      => '404_btn2_txt',
      'type'    => 'text',
      'title'   =>  __( '404 Button 2 Text', 'geoport' ),
      'desc'    => __( '404 page button two title.', 'geoport' ),
      'default' => __( 'Subscribe', 'geoport' ),
    ), 
    array(
      'id'      => '404_btn2_link',
      'type'    => 'text',
      'title'   =>  __( '404 Button 2 Link', 'geoport' ),
      'desc'    => __( '404 page button two link.', 'geoport' ),
      'default' => '#',
    ),
    array(
      'id'      => 'geoport_404_image',
      'type'    => 'image',
      'title'   => __( 'Upload 404 Image', 'geoport' ),
      'desc'    => __( 'Upload 404 page image.', 'geoport' ),
    ),
  
  )
);



// ===================================================================
// Footer Settings =
// ===================================================================
$options[]   = array(
  'name'     => 'footer_settings',
  'title'    => __( 'Footer Settings', 'geoport' ),
  'icon'     => 'fal fa-bars',
  'sections' => array(

    // All Menu
    array(
      'name'     => 'copyright_footer',
      'title'    => 'Copyright Footer',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        // Sticky menu
        array(
          'type'    => 'subheading',
          'content' => __( 'Copyright Settings', 'geoport' ),
        ),
        array(
          'id'        => 'copyrights',
          'type'      => 'textarea',
          'title'     =>  __( 'Copyright Text', 'geoport' ),
          'default'   => '&copy; 2024, Geoport. Theme Developed by <a href="https://ifingerstudio.com" title="johanspond"> Johanspond</a>',
          'sanitize'  => false,
        ),
        array(
          'id'      => 'footer_copyright_background_color',
          'type'    => 'color_picker',
          'title'   => __( 'Copyright Background Color', 'geoport' ),
          'default' => '#001d67',
        ),
        array(
          'id'      => 'footer_copyright_text_color',
          'type'    => 'color_picker',
          'title'   => __( 'Copyright Text Color', 'geoport' ),
          'default' => '#a7b6d3',
        ),
        array(
          'id'      => 'footer_copyright_link_color',
          'type'    => 'color_picker',
          'title'   => __( 'Copyright Link Color', 'geoport' ),
          'default' => '#ff5e14',
        ),
        array(
          'id'      => 'footer_copyright_linkhv_color',
          'type'    => 'color_picker',
          'title'   => __( 'Copyright Link Hover Color', 'geoport' ),
          'default' => '#ff5e14',
        ),
       )
    ),

    // Footer 1
    array(
      'name'     => 'footer_1',
      'title'    => 'Footer Style One ( Default )',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer Columns Settings', 'geoport' ),
        ),
        array(
          'id'             => 'footer_widget1_columns',
          'type'           => 'select',
          'title'          => 'Select Footer 1 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '4',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'id'             => 'footer_widget2_columns',
          'type'           => 'select',
          'title'          => 'Select Footer 2 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '2',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'id'             => 'footer_widget3_columns',
          'type'           => 'select',
          'title'          => 'Select Footer 3 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '3',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'id'             => 'footer_widget4_columns',
          'type'           => 'select',
          'title'          => 'Select Footer 4 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '3',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer Widget Settings', 'geoport' ),
        ),
        array(
          'id'      => 'footer_background_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer background color', 'geoport' ),
          'default' => '#001447',
        ),
        array(
          'id'      => 'ft_fonts_color',
          'type'    => 'color_picker',
          'title'   => __( 'Widget Title Text Color', 'geoport' ),
          'default' => '#ffffff',
        ),
        array(
          'id'      => 'ft_fonts_size',
          'type'    => 'text',
          'title'   => __( 'Widget Title Font Size', 'geoport' ),
          'default' => '24px',
          'info'    => __( 'Put widget title fonts size with px like (34px)', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer Content Settings', 'geoport' ),
        ),
        array(
          'id'      => 'footer_fonts_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer Content color', 'geoport' ),
          'default' => '#a7b6d3',
        ),
        array(
          'id'      => 'footer_hover_link_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer link hover color', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer Social Settings', 'geoport' ),
        ),
        array(
          'id'         => 'footer_left_social_font_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social fonts color', 'geoport' ),
          'default'    => '#ffffff',
        ),
        array(
          'id'         => 'footer_left_social_bg_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social background color', 'geoport' ),
          'default'    => '#1d2b5a',
        ),
        array(
          'id'         => 'footer_left_social_h_font_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social hover fonts color', 'geoport' ),
          'default'    => '#ffffff',
        ),
        array(
          'id'         => 'footer_left_social_h_bg_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social hover background color', 'geoport' ),
          'default'    => '#34ccff',
        ),
      )
    ),

    // Footer 2
    array(
      'name'     => 'footer_2',
      'title'    => 'Footer Style Two',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'id'         => 'footer2_bg_img',
          'type'       => 'image',
          'title'      =>  __( 'Footer 2 background image', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/full_footer_bg.jpg',
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer 2 Columns', 'geoport' ),
        ),
        array(
          'id'             => 'footer2_widget1_columns',
          'type'           => 'select',
          'title'          => 'Select Widget 1 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '5',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'id'             => 'footer2_widget2_columns',
          'type'           => 'select',
          'title'          => 'Select Widget 2 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '3',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),
        array(
          'id'             => 'footer2_widget3_columns',
          'type'           => 'select',
          'title'          => 'Select Widget 3 Columns',
          'options'        => array(
            '2'   => '2',
            '3'   => '3',
            '4'   => '4',
            '5'   => '5',
            '6'   => '6',
            '7'   => '7',
            '8'   => '8',
          ),
          'default_option' => '4',
          'desc'          => __( 'Total footer area columns is 12', 'geoport' )
        ),

        array(
          'type'    => 'subheading',
          'content' => __( 'Footer 2 Color', 'geoport' ),
        ),
        array(
          'id'      => 'footer2_background_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer background color', 'geoport' ),
          'default' => '#001447',
        ),
        array(
          'id'      => 'ft2_fonts_color',
          'type'    => 'color_picker',
          'title'   => __( 'Widget Title Text Color', 'geoport' ),
          'default' => '#ffffff',
        ),
        array(
          'id'      => 'ft2_fonts_size',
          'type'    => 'text',
          'title'   => __( 'Widget Title Font Size', 'geoport' ),
          'default' => '24px',
          'info'    => __( 'Put widget title fonts size with px like (34px)', 'geoport' ),
        ),
        array(
          'type'    => 'subheading',
          'content' => __( 'Footer Content Settings', 'geoport' ),
        ),
        array(
          'id'      => 'footer2_fonts_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer Content color', 'geoport' ),
          'default' => '#a7b6d3',
        ),
        array(
          'id'      => 'footer2_hover_link_color',
          'type'    => 'color_picker',
          'title'   => __( 'Footer link hover color', 'geoport' ),
        ),

        array(
          'type'    => 'subheading',
          'content' => __( 'Footer 2 Social Color', 'geoport' ),
        ),
        array(
          'id'         => 'footer2_left_social_font_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social fonts color', 'geoport' ),
          'default'    => '#ffffff',
        ),
        array(
          'id'         => 'footer2_left_social_bg_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social background color', 'geoport' ),
          'default'    => '#1d2b5a',
        ),
        array(
          'id'         => 'footer2_left_social_h_font_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social hover fonts color', 'geoport' ),
          'default'    => '#ffffff',
        ),
        array(
          'id'         => 'footer2_left_social_h_bg_color',
          'type'       => 'color_picker',
          'title'      =>  __( 'Footer Social hover background color', 'geoport' ),
          'default'    => '#34ccff',
        ),
        array(
          'id'         => 'footer2_left_bg_img',
          'type'       => 'image',
          'title'      =>  __( 'Footer left background image', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/footer_bg.jpg',
        ),
      )
    ),
  
    // Default
    array(
      'name'     => 'default_footer',
      'title'    => 'Default Footer',
      'icon'     => 'fal fa-plus',
      'fields'   => array(
        array(
          'type'    => 'heading',
          'content' => __( 'Default footer settings', 'geoport' ),
        ),
        array(
          'id'          => 'default_footer_style',
          'type'        => 'image_select',
          'title'       => __( 'Select Default Footer', 'geoport' ),
          'options'     => array(
            'style1'    => GEOPORT_PLG_URL. 'assets/imgs/footer1.jpg',
            'style2'    => GEOPORT_PLG_URL. 'assets/imgs/footer2.jpg',
          ),
          'desc'   => __( 'This is all default page footer style if you select','geoport'),
        ),
      )
    ),

  ),
);


// ------------------------------
// a seperator                  -
// ------------------------------
$options[] = array(
  'name'   => 'seperator_1',
  'title'  => 'A Seperator',
  'icon'   => 'fa fa-bookmark'
);

// ------------------------------
// backup                       -
// ------------------------------
$options[]   = array(
  'name'     => 'backup_section',
  'title'    => 'Backup',
  'icon'     => 'fa fa-shield',
  'fields'   => array(

    array(
      'type'    => 'notice',
      'class'   => 'warning',
      'content' => 'You can save your current options. Download a Backup and Import.',
    ),

    array(
      'type'    => 'backup',
    ),

  )
);

GEOPORTFramework::instance( $settings, $options );
