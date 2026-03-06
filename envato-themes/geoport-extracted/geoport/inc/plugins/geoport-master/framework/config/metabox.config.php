<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// METABOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options      = array();

// -----------------------------------------
// Page Metabox Options                    -
// -----------------------------------------
$options[]    = array(
  'id'        => '_custom_page_options',
  'title'     => __( 'Custom Page Options', 'geoport' ),
  'post_type' => 'page',
  'context'   => 'normal',
  'priority'  => 'default',
  'sections'  => array(

    // begin: a section
    array(
      'name'  => 'header_settings',
      'title' => __( 'Header Settings', 'geoport' ),
      'icon'  => 'fal fa-heading',

      // begin: fields
      'fields' => array(

        array(
          'type'    => 'subheading',
          'content' => __( 'Upload Custom Logo', 'geoport' ),
        ),
        array(
          'id'          => 'page_custom_logo_switch',
          'type'        => 'switcher',
          'title'       => __( 'Upload Custom Logo?', 'geoport' ),
        ),
        array(
          'id'          => 'page_custom_logo_upload',
          'type'        => 'image',
          'title'       => __( 'Upload Page Logo', 'geoport' ),
        ),
        array(
          'id'          => 'page_custom_sticky_logo_upload',
          'type'        => 'image',
          'title'       => __( 'Upload Page Sticky Logo', 'geoport' ),
        ),


        array(
          'type'    => 'heading',
          'content' => __( 'Page header settings', 'geoport' ),
        ),
        array(
          'id'          => 'header_style',
          'type'        => 'image_select',
          'title'       => __( 'Select Header', 'geoport' ),
          'options'     => array(
            'style1'    => GEOPORT_PLG_URL. 'assets/imgs/header1.jpg',
            'style2'    => GEOPORT_PLG_URL. 'assets/imgs/header2.jpg',
            'style3'    => GEOPORT_PLG_URL. 'assets/imgs/header3.jpg',
          ),
          'desc'   => __( 'Will be this page header style if you select','geoport' ),
        ),

        array(
          'id'          => 'header_custom_style',
          'type'        => 'select',
          'title'       => __( 'Enable Custom Color Settings', 'dustrial-master' ),
          'options'     => array(
            'customcolor2'    => __( 'No', 'dustrial-master' ),
            'customcolor1'    => __( 'Yes', 'dustrial-master' ),
          ),
        ),
        array(
          'id'      => 'header_custom_bgcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Area Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menucolor',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Hover Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_active_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'Menu Text Active Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_dropmenu_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'DropDown Menu Hover Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_bgcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Menu Area Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_textcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Menu Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_text_hover_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Menu Text Hover Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_text_dropcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Dropdown Menu Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_text_activecolor',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Dropdown Menu Hover Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_responsive_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Responsive Menu Icon Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'sticky_header_custom_responsive_icon_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Responsive Menu Icon Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),



        array(
          'id'      => 'header_custom_menu_buttonbg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_border_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Border Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_hover_buttonbg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Hover Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_hover_button_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Hover Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_hover_border_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Hover Border Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_boxshadow_color',
          'type'    => 'color_picker',
          'title'   => __( 'Track Order Button Boxshadow', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_sticky_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Track Order Button Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_custom_menu_button_sticky_color',
          'type'    => 'color_picker',
          'title'   => __( 'Sticky Track Order Button Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),



        array(
          'id'      => 'header_top_parts_bgcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Top Part Background Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_top_parts_txtcolor',
          'type'    => 'color_picker',
          'title'   => __( 'Top Part Text Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),
        array(
          'id'      => 'header_top_parts_hovercolor',
          'type'    => 'color_picker',
          'title'   => __( 'Top Part Link Hover Color', 'dustrial-master' ),
          'desc'    => __( 'Pick Color.', 'dustrial-master' ),
          'default' => '',
        ),


        array(
          'type'    => 'heading',
          'content' => __( 'Page footer settings', 'geoport' ),
        ),
        array(
          'id'          => 'footer_style',
          'type'        => 'image_select',
          'title'       => __( 'Select Footer', 'geoport' ),
          'options'     => array(
            'style1'    => GEOPORT_PLG_URL. 'assets/imgs/footer1.jpg',
            'style2'    => GEOPORT_PLG_URL. 'assets/imgs/footer2.jpg',
          ),
          'desc'   => __( 'Will be this page footer style if you select','geoport' ),
        ),

      ), // end: fields
    ), // end: a section

    // begin: a section
    array(
      'name'  => 'page_breadcrumb_settings',
      'title' => __( 'Breadcrumb Settings', 'geoport' ),
      'icon'  => 'fal fa-image',
      'fields' => array(

        array(
          'type'    => 'subheading',
          'content' => __( 'This page breadcrumb settings', 'geoport' ),
        ),
        
        array(
          'id'          => 'page_breadcrumb_switch',
          'type'        => 'switcher',
          'title'       => __( 'Enable breadcrumb for this page?', 'geoport' ),
        ),
        array(
          'id'         => 'page_breadcrumb_bg_img',
          'type'       => 'image',
          'title'      => __( 'Breadcrumb background image', 'geoport' ),
          'default'    => GEOPORT_PLG_URL. 'assets/imgs/breadcrumb.jpg',
          'dependency' => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'page_breadcrumb_bg_color',
          'type'    => 'color_picker',
          'title'   => __( 'Breatcrumb background color', 'geoport' ),
          'after'   => '<p class="cs-text-muted">'.__( 'It is breadcrumb background color.', 'geoport' ).'</p>',
          'default' => '#000e30',
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'breadcrumb_bg_img_opacity',
          'type'    => 'select',
          'title'   => __( 'Breadcrumb Background Image Opacity', 'geoport' ),
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
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'breadcrumb_pt',
          'type'    => 'text',
          'title'   => __( 'Breatcrumb padding top', 'geoport' ),
          'after'   => '<p class="cs-text-muted">'.__( 'Breadcrumb padding top with px ( like as 350px ).', 'geoport' ).'</p>',
          'default' => '400px',
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'breadcrumb_pb',
          'type'    => 'text',
          'title'   => __( 'Breadcrumb padding bottom', 'geoport' ),
          'after'   => '<p class="cs-text-muted">'.__( 'Breadcrumb padding bottom with px ( like as 350px ).', 'geoport' ).'</p>',
          'default' => '75px',
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'breadcrumb_font_color',
          'type'    => 'color_picker',
          'title'   => __( 'Breatcrumb font color', 'geoport' ),
          'after'   => '<p class="cs-text-muted">'.__( 'It is breadcrumb font color.', 'geoport' ).'</p>',
          'default' => '#fff',
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'      => 'breadcrumb_hover_link_color',
          'type'    => 'color_picker',
          'title'   => __( 'Breatcrumb hover link color', 'geoport' ),
          'after'   => '<p class="cs-text-muted">'.__( 'It is breadcrumb link hover font color.', 'geoport' ).'</p>',
          'default' => '#ff5e14',
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),
        array(
          'id'          => 'page_breadcrumb_title',
          'type'        => 'text',
          'title'       => __( 'Breadcrumb title', 'geoport' ),
          'dependency'   => array( 'page_breadcrumb_switch', '==', 'true' ),
        ),

      ),
    ),
    // end: a section

  ),
);


// -----------------------------------------
// Default Post Settings                   -
// -----------------------------------------
$options[]    = array(
  'id'        => '_johanspond_post',
  'title'     => __( 'Post Settings', 'geoport' ),
  'post_type' => 'post',
  'context'   => 'normal',
  'priority'  => 'default',
  'sections'  => array(

    array(
      'name'   => 'johanspond_default_post',
      'fields' => array(

        array(
          'id'             => 'post_format_type',
          'type'           => 'select',
          'title'          => __( 'Custom Post Format', 'geoport' ),
          'options'        => array(
            'johanspond-video'   => __( 'Video', 'geoport' ),
            'johanspond-audio'   => __( 'Audio', 'geoport' ),
            'johanspond-quote'   => __( 'Quote', 'geoport' ),
            'johanspond-gallery' => __( 'Gallery', 'geoport' ),
          ),
          'default_option' => 'Select Custom Post Type',
        ),
        array(
          'id'    => 'video_link',
          'type'  => 'text',
          'title' => __( 'Put Video Link', 'geoport' ),
          'dependency'   => array( 'post_format_type', '==', 'johanspond-video' ),
        ),
        array(
          'id'    => 'audio_link',
          'type'  => 'text',
          'title' => __( 'Put Audio Link', 'geoport' ),
          'dependency'   => array( 'post_format_type', '==', 'johanspond-audio' ),
        ),
        array(
          'id'    => 'gallery_list',
          'type'  => 'gallery',
          'title' => __( 'Select gallery images', 'geoport' ),
          'dependency'   => array( 'post_format_type', '==', 'johanspond-gallery' ),
        ),
        array(
          'id'    => 'johanspond_quote_icon',
          'type'  => 'image',
          'title' => __( 'Quote icon img', 'geoport' ),
          'dependency'   => array( 'post_format_type', '==', 'johanspond-quote' ),
        ),

      ),
    ),

  ),
);


// -----------------------------------------
// Testimonial Post Settings               -
// -----------------------------------------
$options[]    = array(
  'id'        => '_ifinger_testimonial',
  'title'     => __( 'Testimonial settings', 'geoport' ),
  'post_type' => 'testimonial',
  'context'   => 'normal',
  'priority'  => 'high',
  'sections'  => array(

    array(
      'name'   => 'ifinger_testimonial_post',
      'fields' => array(

        array(
          'id'    => 'rate_giver_name',
          'type'  => 'text',
          'title' => __( 'Rate Giver Name', 'geoport' ),
          'label' => __( 'Put name who give rate', 'geoport' ),
        ),
        array(
          'id'    => 'rate_giver_designation',
          'type'  => 'text',
          'title' => __( 'Rate Giver Designation', 'geoport' ),
          'label' => __( 'Put Designation who give rate', 'geoport' ),
        ),
        array(
          'id'    => 'rate_giver_organization',
          'type'  => 'text',
          'title' => __( 'Rate Giver Organization', 'geoport' ),
          'label' => __( 'Put Organization Name', 'geoport' ),
        ),
        array(
          'id'    => 'rate_giver_organization_link',
          'type'  => 'text',
          'title' => __( 'Rate Giver Organization Link', 'geoport' ),
          'label' => __( 'Put Organization Link', 'geoport' ),
        ),
        array(
          'id'       => 'rewiew_setting',
          'type'     => 'select',
          'title'    => __( 'Select Rating', 'geoport' ),
          'options'  => array(
            '1' => __( '1 Star', 'geoport' ),
            '2' => __( '2 Star', 'geoport' ),
            '3' => __( '3 Star', 'geoport' ),
            '4' => __( '4 Star', 'geoport' ),
            '5' => __( '5 Star', 'geoport' ),
          ),
          'default'  => '5',
        ),
        array(
          'id'    => 'rewiew_color',
          'type'  => 'color_picker',
          'title' => __( 'Rating color', 'geoport' ),
          'default'  => '#ffaa6a',
        ),

      ),
    ),

  ),
);

// -----------------------------------------
// Contact Tab Settings                    -
// -----------------------------------------
$options[]    = array(
  'id'        => '_ifinger_contact_tab',
  'title'     => __( 'Contact Tab Settings', 'geoport' ),
  'post_type' => 'contact_tab',
  'context'   => 'normal',
  'priority'  => 'high',
  'sections'  => array(

    array(
      'name'   => 'ifinger_contact_tab',
      'fields' => array(

        array(
          'id'          => 'title_icon',
          'type'        => 'icon',
          'title'       => __( 'Title Icon', 'geoport' ),
        ),

        array(
          'id'    => 'info_title',
          'type'  => 'text',
          'title' => __( 'Information Title', 'geoport' ),
          'label' => __( 'Contact Information Title', 'geoport' ),
        ),
        array(
          'id'              => 'contact_info_list',
          'type'            => 'group',
          'title'           => __( 'Add New List', 'geoport' ),
          'button_title'    => __( 'Add New', 'geoport' ),
          'accordion_title' => __( 'Add Info List', 'geoport' ),
          'fields'          => array(
            array(
              'id'          => 'info_text',
              'type'        => 'text',
              'title'       => __( 'Information Text', 'geoport' ),
            ),
            array(
              'id'          => 'info_icon',
              'type'        => 'icon',
              'title'       => __( 'List Icon', 'geoport' ),
            ),
          )
        ),
        array(
          'id'    => 'tab_map_embeded_code',
          'type'  => 'textarea',
          'title' => __( 'Map Embeded Code', 'geoport' ),
          'sanitize' => false,
          'info' => __( 'Google map location embeded code put here', 'geoport' ),
        ),

      ),
    ),

  ),
);



// -----------------------------------------
// Team Post Settings                      -
// -----------------------------------------
$options[]    = array(
  'id'        => '_geoport_team',
  'title'     => __( 'Team settings', 'geoport' ),
  'post_type' => 'team',
  'context'   => 'normal',
  'priority'  => 'high',
  'sections'  => array(

    array(
      'name'   => 'ifinger_team_post',
      'fields' => array(
        array(
          'id'          => 'team_designation',
          'type'        => 'text',
          'title'       => 'Team Member Designation',
        ),
        array(
          'id'              => 'team_member_info',
          'type'            => 'group',
          'title'           => 'Team Member Information',
          'button_title'    => 'Add New',
          'accordion_title' => 'Add New Information',
          'fields'          => array(
            array(
              'id'          => 'title',
              'type'        => 'text',
              'title'       => 'Info Title',
            ),
            array(
              'id'          => 'text',
              'type'        => 'text',
              'title'       => 'Info Text',
            ),
          )
        ),

        array(
          'id'              => 'team_social',
          'type'            => 'group',
          'title'           => 'Social Field',
          'button_title'    => 'Add New',
          'accordion_title' => 'Add New Social',
          'fields'          => array(
            array(
              'id'          => 'icon',
              'type'        => 'icon',
              'title'       => 'Icon',
            ),
            array(
              'id'          => 'link',
              'type'        => 'text',
              'title'       => 'Link',
            ),
          )
        ),

      ),
    ),


  ),
);


// -----------------------------------------
// Team Post Settings                      -
// -----------------------------------------
$options[]    = array(
  'id'        => '_geoport_journey',
  'title'     => __( 'Journey settings', 'geoport' ),
  'post_type' => 'journey',
  'context'   => 'normal',
  'priority'  => 'high',
  'sections'  => array(

    array(
      'name'   => 'ifinger_journeypost',
      'fields' => array(
        array(
          'id'    => 'journey_date',
          'type'  => 'text',
          'title' => __( 'Journey date', 'geoport' ),
        ),
      ),
    ),

  ),
);

// -----------------------------------------
// Job List Post Settings                      -
// -----------------------------------------
$options[]    = array(
  'id'        => '_geoport_joblist',
  'title'     => __( 'Jon list Settings', 'bao' ),
  'post_type' => 'geoport_job_list',
  'context'   => 'normal',
  'priority'  => 'high',
  'sections'  => array(

    array(
      'name'   => 'ifinger_joblist_post',
      'fields' => array(
        array(
          'id'              => 'job_list',
          'type'            => 'group',
          'title'           =>  __( 'Job List Details', 'bao' ),
          'button_title'    =>  __( 'Add New', 'bao' ),
          'accordion_title' =>  __( 'Add New List', 'bao' ),
          'fields'          => array(
            array(
              'id'          => 'text2',
              'type'        => 'text',
              'title'       =>  __( 'Experience', 'bao' ),
            ),
            array(
              'id'          => 'text3',
              'type'        => 'text',
              'title'       =>  __( 'Qualification', 'bao' ),
            ),
            array(
              'id'          => 'text4',
              'type'        => 'text',
              'title'       =>  __( 'Salary', 'bao' ),
            ),
            array(
              'id'          => 'btn_txt',
              'type'        => 'text',
              'title'       =>  __( 'Apply Button', 'bao' ),
            ),
            array(
              'id'          => 'btn_url',
              'type'        => 'text',
              'title'       =>  __( 'Apply Button Link', 'bao' ),
            ),
          ),
        ),
      ),
    ),

  ),
);

GEOPORTFramework_Metabox::instance( $options );