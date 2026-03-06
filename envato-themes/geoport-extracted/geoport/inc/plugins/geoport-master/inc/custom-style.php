<?php
function geoport_custom_styles(){
  if(function_exists( 'geoport_framework_init' ) ) {

    $geoport_master_core = geoport_master_global_var();
    
    /* Body Fonts */
    $body_fonts = geoport_get_option('geoport_body_font');
    if (!empty($body_fonts)) {
      $body_font = $body_fonts['family'];
    } else {
      $body_font = 'Karla';
    }

    $body_font_size = geoport_get_option('geoport_body_font_size');
    if (!empty($body_font_size)) {
      $body_font_size = $body_font_size;
    } else {
      $body_font_size = '16px';
    }
    
    $line_height = geoport_get_option('line_height');
    if (!empty($line_height)) {
      $line_height = $line_height;
    } else {
      $line_height = '26px';
    }

    /* Heading Fonts */
    $geoport_heading_font = geoport_get_option('geoport_heading_font');
    if (!empty($geoport_heading_font)) {
      $heading_font = $geoport_heading_font['family'];
    } else {
      $heading_font = 'Barlow Condensed';
    }
    
    
    // Logo 1
    $geoport_logo1_width = geoport_get_option( 'geoport_logo1_width' );
    if (!empty($geoport_logo1_width)) {
      $geoport_logo1_width = $geoport_logo1_width;
    } else {
      $geoport_logo1_width = '200px';
    }
    $geoport_logo1_pt = geoport_get_option( 'geoport_logo1_pt' );
    if (!empty($geoport_logo1_pt)) {
      $geoport_logo1_pt = $geoport_logo1_pt;
    } else {
      $geoport_logo1_pt = '20px';
    }
    $geoport_logo1_pb = geoport_get_option( 'geoport_logo1_pb' );
    if (!empty($geoport_logo1_pb)) {
      $geoport_logo1_pb = $geoport_logo1_pb;
    } else {
      $geoport_logo1_pb = '5px';
    }

    // Typography Settings

    echo "<style>
      body {
        font-family: {$body_font}, sans-serif;
      }
      body{
        font-size: $body_font_size;
        line-height: $line_height;
      }
      h1, h2, h3, h4, h5, h6 {
        font-family: {$heading_font}, sans-serif;
      }
      .header3 .logo,
      .header-style-two .logo,
      .default-header .logo {
        padding-top: $geoport_logo1_pt;
        max-width: $geoport_logo1_width;
        padding-bottom: $geoport_logo1_pb;
      }
    </style>";

    /* =================================================================
    = Geoport Styles
    ====================================================================*/
    $geoport_master_core -> Header1_Top_Color();
    $geoport_master_core -> Breadcrumb_Settings();
    $geoport_master_core -> Theme_Base_Color_Settings();
    $geoport_master_core -> Theme_Pageloader_Settings();
    $geoport_master_core -> Theme_scrollup_Settings();
    $geoport_master_core -> Theme_Footer_Settings();
  } 
}
add_filter('wp_head', 'geoport_custom_styles');
?>