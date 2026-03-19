<?php
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function startli_body_classes( $classes ) {
  // Adds a class of hfeed to non-singular pages.
  if ( ! is_singular() ) {
    $classes[] = 'hfeed';
  }

  return $classes;
}
add_filter( 'body_class', 'startli_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function startli_pingback_header() {
  if ( is_singular() && pings_open() ) {
    echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
  }
}

add_action( 'wp_head', 'startli_pingback_header' );
/**  kses_allowed_html */
function startli_prefix_kses_allowed_html($tags, $context) {
  switch($context) {
    case 'startli': 
      $tags = array( 
        'a' => array('href' => array()),
        'b' => array()
      );
      return $tags;
    default: 
      return $tags;
  }
}
add_filter( 'wp_kses_allowed_html', 'startli_prefix_kses_allowed_html', 10, 2);

/*
Register Fonts theme google font
*/
function startli_studio_fonts_url() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'startli' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Archivo:300;400;500;600;700;|Titillium Web:400;600;700' ), "//fonts.googleapis.com/css" );
    }
    return $font_url;
}


function startli_studio_scripts() {
    wp_enqueue_style( 'studio-fonts', startli_studio_fonts_url(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'startli_studio_scripts' );

//Favicon Icon
function startli_site_icon() {
 if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) {     
    global $startli_option;
     
    if(!empty($startli_option['rs_favicon']['url']))
    {?>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url(($startli_option['rs_favicon']['url'])); ?>"> 
  <?php 
    }
  }
}
add_filter('wp_head', 'startli_site_icon');


//excerpt for specific section
function startli_wpex_get_excerpt( $args = array() ) {
  // Defaults
  $defaults = array(
    'post'            => '',
    'length'          => 48,
    'readmore'        => false,
    'readmore_text'   => esc_html__( 'read more', 'startli' ),
    'readmore_after'  => '',
    'custom_excerpts' => true,
    'disable_more'    => false,
  );
  // Apply filters
  $defaults = apply_filters( 'startli_wpex_get_excerpt_defaults', $defaults );
  // Parse args
  $args = wp_parse_args( $args, $defaults );
  // Apply filters to args
  $args = apply_filters( 'startli_wpex_get_excerpt_args', $defaults );
  // Extract
  extract( $args );
  // Get global post data
  if ( ! $post ) {
    global $post;
  }

  $post_id = $post->ID;
  if ( $custom_excerpts && has_excerpt( $post_id ) ) {
    $output = $post->post_excerpt;
  } 
  else { 
    $readmore_link = '<a href="' . get_permalink( $post_id ) . '" class="readmore">' . $readmore_text . $readmore_after . '</a>';    
    if ( ! $disable_more && strpos( $post->post_content, '<!--more-->' ) ) {
      $output = apply_filters( 'the_content', get_the_content( $readmore_text . $readmore_after ) );
    }    
    else {     
      $output = wp_trim_words( strip_shortcodes( $post->post_content ), $length );      
      if ( $readmore ) {
        $output .= apply_filters( 'startli_wpex_readmore_link', $readmore_link );
      }
    }
  }
  // Apply filters and echo
  return apply_filters( 'startli_wpex_get_excerpt', $output );
}

//Demo content file include here

function startli_import_files() {
  return array(
    array(
      'import_file_name'           => 'Startli Default Demo',
      'categories'                 => array( 'Main Demo' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/default/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/default/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/01.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli',     
      
    ),
    array(
      'import_file_name'           => 'Startli Startup',
      'categories'                 => array( 'Startup' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/startup/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/startup/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/02.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-startup/',     
      
    ), 
    array(
      'import_file_name'           => 'Corporate',
      'categories'                 => array( 'Corporate' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/corporate/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/corporate/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/03.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-corporate/',     
      
    ), 

    array(
      'import_file_name'           => 'HR Demo',
      'categories'                 => array( 'HR Demo' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/hr/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/hr/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/05.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-hr',     
      
    ), 
    //building
    array(
      'import_file_name'           => 'It Demo',
      'categories'                 => array( 'IT Solution' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/it/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/it/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/06.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-it/',     
      
    ), 
    //architecute
    array(
      'import_file_name'           => 'Marketing',
      'categories'                 => array( 'Marketing' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/marketing/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/marketing/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/07.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-marketing',     
      
    ), 
    //handyman
    array(
      'import_file_name'           => 'Insurance',
      'categories'                 => array( 'Insurance' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/insurance/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/insurance/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/09.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-insurance/',     
      
    ),
    

     array(
      'import_file_name'           => 'Finance',
      'categories'                 => array( 'Finance' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/finance/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/finance/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/08.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-finance/',     
      
    ), 

    //tax
    array(
      'import_file_name'           => 'Tax',
      'categories'                 => array( 'Tax Advisor' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/tax/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/tax/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/04.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-tax/',     
      
    ), 

    //solar
    array(
      'import_file_name'           => 'Strategy',
      'categories'                 => array( 'Business Stratey' ),
      'import_file_url'            => 'https://reactheme.com/products/demo-data/startli/strategy/startli-content.xml',
             
      'import_redux'               => array(
        array(
          'file_url'    => 'https://reactheme.com/products/demo-data/startli/strategy/startli-options.json',
          'option_name' => 'startli_option',
        ),
      ),

      'import_preview_image_url'   => 'https://themewant.com/products/wordpress/landing/startli/assets/images/demos/10.webp',
     'import_notice'              => esc_html__( 'Caution: For importing demo data please click on "Import Demo Data" button. During demo data installation please do not refresh the page.', 'startli' ),
      'preview_url'                => 'https://themewant.com/products/wordpress/startli-strategy/',     
      
    ), 


    
  );
}

add_filter( 'pt-ocdi/import_files', 'startli_import_files' );

function startli_after_import_setup($selected_import) {
  // Assign menus to their locations.
	$main_menu     = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
  $menu_single     = get_term_by( 'name', 'Onepage Menu', 'nav_menu' );
	set_theme_mod( 'nav_menu_locations', array(
      'menu-1' => $main_menu->term_id, 
      'menu-2' => $menu_single->term_id,      
    )
  );
  if ( 'Startli Default Demo' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Startli Startup' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Corporate' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'HR Demo' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('HR Demo');
  }

  if ( 'It Demo' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Marketing' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Insurance' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }
    
  if ( 'Finance' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Tax' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }

  if ( 'Strategy' == $selected_import['import_file_name'] ) {
    $front_page_id = get_page_by_title('Main Home');
  }


  
  $blog_page_id  = get_page_by_title( 'News & Media' );
  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID ); 

  //Import Revolution Slider
  //Import Revolution Slider
  if ( class_exists( 'RevSlider' ) ) {
    $slider_array = array(
      get_template_directory()."/inc/demo-data/sliders/slider-2.zip",   
      get_template_directory()."/inc/demo-data/sliders/corporate.zip",  
      get_template_directory()."/inc/demo-data/sliders/it-slider.zip",   
      get_template_directory()."/inc/demo-data/sliders/hr-slider.zip", 
      get_template_directory()."/inc/demo-data/sliders/tax.zip",   
      get_template_directory()."/inc/demo-data/sliders/mak-slider.zip",                        
                                  
    );
    $slider = new RevSlider();
    foreach($slider_array as $filepath){
      $slider->importSliderFromPost(true,true,$filepath);  
    }
  }
  
}
add_action( 'pt-ocdi/after_import', 'startli_after_import_setup' );

add_filter( 'use_widgets_block_editor', '__return_false' );

function startli_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'startli_mime_types');
update_option('elementor_disable_color_schemes', 'yes');
update_option('elementor_disable_typography_schemes', 'yes');