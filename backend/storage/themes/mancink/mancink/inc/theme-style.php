<?php
function mancink_theme_styles()
{
  // Register the style for the theme
  wp_enqueue_style(
    'bootstrap',
    get_template_directory_uri() . '/css/bootstrap.min.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'fontawesome',
    get_template_directory_uri() . '/css/font-awesome.min.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-magnificpopup',
    get_template_directory_uri() . '/css/magnific-popup.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-preloader',
    get_template_directory_uri() . '/css/preloader.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-animate',
    get_template_directory_uri() . '/css/animate.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-magiccss',
    get_template_directory_uri() . '/css/magic.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-slick',
    get_template_directory_uri() . '/css/slick.css',
    array(),
    '1',
    'all'
  );
  wp_enqueue_style(
    'mancink-slicknav',
    get_template_directory_uri() . '/css/slicknav.css',
    array(),
    '1',
    'all'
  );

  wp_enqueue_style(
    'mancink-styles',
    get_stylesheet_directory_uri() . '/style.css',
    array(),
    '1',
    'all'
  );
}


/*
Register Google Fonts
*/
function mancink_fonts_url()
{
  $font_url = '';

  /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
  if ('off' !== _x('on', 'Google font: on or off', 'mancink')) {
    $font_url = add_query_arg('family', urlencode('Playfair Display:400,400i|Big Shoulders Text:900|Alegreya Sans:400,400i,700'), "//fonts.googleapis.com/css");
  }
  return $font_url;
}
/*
Enqueue scripts and styles.
*/
function mancink_fonts_style()
{
  wp_enqueue_style('mancink-fonts', mancink_fonts_url(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'mancink_fonts_style');


//for google font  in editor
function mancink_fonts_editor_style()
{
  $font_url = add_query_arg('family', urlencode('Playfair Display:400,400i|Big Shoulders Text:900|Alegreya Sans:400,400i,700'), "//fonts.googleapis.com/css");
}
add_action('after_setup_theme', 'mancink_fonts_editor_style');


/**
 * Enqueue editor styles for Gutenberg
 */

function mancink_editor_styles()
{
  wp_enqueue_style('mancink_add_editor_styles', get_template_directory_uri() . '/custom-editor-style.css');
  wp_enqueue_style('mancink-fonts', mancink_fonts_url(), array(), '1.0.0');
}
add_action('enqueue_block_editor_assets', 'mancink_editor_styles');