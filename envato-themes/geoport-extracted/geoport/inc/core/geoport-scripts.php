<?php if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

function geoport_body_fonts_url() {
  $font_url = '';
  /*
  Translators: If there are characters in your language that are not supported
  by chosen font(s), translate this to 'off'. Do not translate into your own language.
  */
  if ( 'off' !== _x( 'on', 'Google font: on or off', 'geoport' ) ) {

    if( function_exists( 'geoport_framework_init' ) ) {

      $body_typo_data = geoport_get_option('geoport_body_font');
      $heading_typo_data = geoport_get_option('geoport_heading_font');

      if( !empty($body_typo_data) || !empty($heading_typo_data)) {
        $body_font = $body_typo_data['family'];
        $heading_font = $heading_typo_data['family'];
        
        $font_url = add_query_arg( 
          'family', urlencode( $body_font.':400,400i,700,700i|'. $heading_font .':300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap' ), "//fonts.googleapis.com/css" 
        );
      } else {
        $font_url = add_query_arg( 
          'family', urlencode( 'Karla:400,400i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap' ), "//fonts.googleapis.com/css" 
        );
      }
    } else {
      $font_url = add_query_arg( 
        'family', urlencode( 'Karla:400,400i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap' ), "//fonts.googleapis.com/css" 
      );
    }
  }
  return $font_url;
}

/** Gutenberg optimization enqueue files.
--------------------------------------------------------------------------------------------------- */
add_action('enqueue_block_editor_assets', 'geoport_action_enqueue_block_editor_assets' );
function geoport_action_enqueue_block_editor_assets() {
  wp_enqueue_style( 'geoport-body-fonts',  geoport_body_fonts_url(), '', '1.0.0', 'screen' );
  wp_enqueue_style('geoport-gutenberg-editor-custom', GEOPORT_CSS . '/gutenberg/gutenberg-editor-custom.css' );
  wp_enqueue_style('geoport-gutenberg-custom', GEOPORT_CSS . '/gutenberg/gutenberg-custom.css' );
}

function geoport_scripts() {

  /** lifestyleblog Fonts Load.
  --------------------------------------------------------------------------------------------------- */
  wp_enqueue_style( 'geoport-body-fonts',  geoport_body_fonts_url(), '', '1.0.0', 'screen' );

  /**  css include.
  --------------------------------------------------------------------------------------------------- */
  wp_enqueue_style( 'bootstrap', GEOPORT_CSS . 'bootstrap.min.css' );
  wp_enqueue_style( 'animate', GEOPORT_CSS . 'animate.min.css' );
  wp_enqueue_style( 'magnific-popup', GEOPORT_CSS . 'magnific-popup.css' );
  wp_enqueue_style( 'meanmenu', GEOPORT_CSS . 'meanmenu.css' );
  wp_enqueue_style( 'slick', GEOPORT_CSS . 'slick.css' );
  wp_enqueue_style( 'dashicons' );
  wp_enqueue_style( 'geoport-gutenberg-custom', GEOPORT_CSS . '/gutenberg/gutenberg-custom.css' );
  wp_enqueue_style( 'geoport-main', GEOPORT_CSS . 'geoport-main.css' );
  wp_enqueue_style( 'geoport-responsive', GEOPORT_CSS . 'geoport-responsive.css' );
  //Geoport Core style
  wp_enqueue_style( 'geoport-style', get_stylesheet_uri() );

  /**  js include.
  --------------------------------------------------------------------------------------------------- */
  wp_enqueue_script( 'bootstrap', GEOPORT_JS . 'bootstrap.min.js', array('jquery'), '4.0.0', true );
  wp_enqueue_script( 'slick', GEOPORT_JS . 'slick.min.js', array('jquery'), '', true );
  wp_register_script( 'slider-init-js', GEOPORT_JS . 'slider-init.js', array('jquery', 'slick'), null, true );
  wp_enqueue_script( 'jquery.meanmenu', GEOPORT_JS . 'jquery.meanmenu.min.js', array('jquery'), '', true );
  wp_enqueue_script( 'imagesloaded' );
  wp_enqueue_script( 'wow', GEOPORT_JS . 'wow.min.js', array('jquery'), '', true );
  wp_enqueue_script( 'aos', GEOPORT_JS . 'aos.js', array('jquery'), '', true );
  wp_enqueue_script( 'jquery.counterup', GEOPORT_JS . 'jquery.counterup.min.js', array('jquery'), '1.0', true );
  wp_enqueue_script( 'jquery.waypoints', GEOPORT_JS . 'jquery.waypoints.min.js', array('jquery'), '2.0.3', true );
  wp_enqueue_script( 'jquery.magnific-popup', GEOPORT_JS . 'jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true );
  wp_enqueue_script( 'geoport-main', GEOPORT_JS . 'geoport-main.js', array( 'jquery'), '1.0.0', true );

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script( 'comment-reply' );
  }

}
add_action( 'wp_enqueue_scripts', 'geoport_scripts' );