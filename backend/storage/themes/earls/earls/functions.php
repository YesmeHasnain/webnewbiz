<?php

require_once get_template_directory() . '/includes/loader.php';

add_action( 'after_setup_theme', 'earls_setup_theme' );
add_action( 'after_setup_theme', 'earls_load_default_hooks' );


function earls_setup_theme() {

	load_theme_textdomain( 'earls', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'woocommerce' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
    
	// Set the default content width.
	$GLOBALS['content_width'] = 525;
	
	/*---------- Register image sizes ----------*/
	
	//Register image sizes
	add_image_size( 'earls_606x430', 606, 430, true ); //earls_606x430 Blog Grid
	add_image_size( 'earls_480x692', 480, 692, true ); //earls_480x692 Protfolio_carosuel
	add_image_size( 'earls_368x600', 368, 600, true ); //earls_368x600 Protfolio_carosuel
	add_image_size( 'earls_1170x470', 1170, 470, true ); //earls_1170x470 Our Blog
	add_image_size( 'earls_100x100', 100, 100, true ); //earls_100x100 portfolio Widget
	add_image_size( 'earls_520x592', 520, 592, true ); //earls_520x592 portfolio Grid
	add_image_size( 'earls_238x146', 238, 146, true ); //earls_238x146 product Tabs
	add_image_size( 'earls_288x391', 288, 391, true ); //earls_288x391 product Tabs
	add_image_size( 'earls_93x93', 93, 93, true ); //earls_93x93 menu tabs v2
	
	
	/*---------- Register image sizes ends ----------*/
	
	
	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'main_menu' => esc_html__( 'Main Menu', 'earls' ),
		'footer_menu' => esc_html__( 'Footer Menu', 'earls' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'      => 250,
		'height'     => 250,
		'flex-width' => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style();
	add_action( 'admin_init', 'earls_admin_init', 2000000 );
}

/**
 * [earls_admin_init]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */


function earls_admin_init() {
	remove_action( 'admin_notices', array( 'ReduxFramework', '_admin_notices' ), 99 );
}

/*---------- Sidebar settings ----------*/

/**
 * [earls_widgets_init]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */
function earls_widgets_init() {

	global $wp_registered_sidebars;
	$theme_options = get_theme_mod( 'earls' . '_options-mods' );
	register_sidebar( array(
		'name'          => esc_html__( 'Default Sidebar', 'earls' ),
		'id'            => 'default-sidebar',
		'description'   => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'earls' ),
		'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title"><h3>',
		'after_title'   => '</h3></div>',
	) );
	register_sidebar(array(
		'name' => esc_html__('Footer Widget', 'earls'),
		'id' => 'footer-sidebar',
		'description' => esc_html__('Widgets in this area will be shown in Footer Area.', 'earls'),
		'before_widget'=>'<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 p-0"><div id="%1$s" class="footer-widget %2$s">',
		'after_widget'=>'</div></div>',
		'before_title' => '<div class="footer___top__content"><img src="'.esc_url(get_template_directory_uri()).'/assets/images/icons/map.png" alt="Awesome Image"><span class="sub____title">',
		'after_title' => '</span></div>'
	));
	if ( class_exists( '\Elementor\Plugin' )){
		register_sidebar(array(
			'name' => esc_html__('Footer Widget 02', 'earls'),
			'id' => 'footer-sidebar2',
			'description' => esc_html__('Widgets in this area will be shown in Footer Area.', 'earls'),
			'before_widget'=>'<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 pt-40"><div id="%1$s" class="footer-widget %2$s">',
			'after_widget'=>'</div></div>',
			'before_title' => '<div class="footer___top__content"><img src="'.esc_url(get_template_directory_uri()).'/assets/images/icons/map.png" alt="Awesome Image"><span class="sub____title">',
			'after_title' => '</span></div>'
		));
		register_sidebar(array(
		  'name' => esc_html__( 'Shop Widget', 'earls' ),
		  'id' => 'shop-sidebar',
		  'description' => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'earls' ),
		  'before_widget'=>'<div id="%1$s" class="widget sidebar-widget %2$s">',
		  'after_widget'=>'</div>',
		  'before_title' => '<div class="widget-title"><h1>',
		  'after_title' => '</h1></div>'
		));
		register_sidebar(array(
		  'name' => esc_html__( 'Blog Listing', 'earls' ),
		  'id' => 'blog-sidebar',
		  'description' => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'earls' ),
		  'before_widget'=>'<div id="%1$s" class="widget sidebar-widget %2$s">',
		  'after_widget'=>'</div>',
		  'before_title' => '<div class="widget-title"><h3>',
		  'after_title' => '</h3></div>'
		));
	}
	if ( ! is_object( earls_WSH() ) ) {
		return;
	}

	$sidebars = earls_set( $theme_options, 'custom_sidebar_name' );

	foreach ( array_filter( (array) $sidebars ) as $sidebar ) {

		if ( earls_set( $sidebar, 'topcopy' ) ) {
			continue;
		}

		$name = $sidebar;
		if ( ! $name ) {
			continue;
		}
		$slug = str_replace( ' ', '_', $name );

		register_sidebar( array(
			'name'          => $name,
			'id'            => sanitize_title( $slug ),
			'before_widget' => '<div id="%1$s" class="%2$s widget sidebar-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title"><h3>',
			'after_title'   => '</h3></div>',
		) );
	}

	update_option( 'wp_registered_sidebars', $wp_registered_sidebars );
}

add_action( 'widgets_init', 'earls_widgets_init' );

/*---------- Sidebar settings ends ----------*/

/*---------- Gutenberg settings ----------*/

function earls_gutenberg_editor_palette_styles() {
    add_theme_support( 'editor-color-palette', array(
        array(
            'name' => esc_html__( 'strong yellow', 'earls' ),
            'slug' => 'strong-yellow',
            'color' => '#f7bd00',
        ),
        array(
            'name' => esc_html__( 'strong white', 'earls' ),
            'slug' => 'strong-white',
            'color' => '#fff',
        ),
		array(
            'name' => esc_html__( 'light black', 'earls' ),
            'slug' => 'light-black',
            'color' => '#242424',
        ),
        array(
            'name' => esc_html__( 'very light gray', 'earls' ),
            'slug' => 'very-light-gray',
            'color' => '#797979',
        ),
        array(
            'name' => esc_html__( 'very dark black', 'earls' ),
            'slug' => 'very-dark-black',
            'color' => '#000000',
        ),
    ) );
	
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name' => esc_html__( 'Small', 'earls' ),
			'size' => 10,
			'slug' => 'small'
		),
		array(
			'name' => esc_html__( 'Normal', 'earls' ),
			'size' => 15,
			'slug' => 'normal'
		),
		array(
			'name' => esc_html__( 'Large', 'earls' ),
			'size' => 24,
			'slug' => 'large'
		),
		array(
			'name' => esc_html__( 'Huge', 'earls' ),
			'size' => 36,
			'slug' => 'huge'
		)
	) );
	
}
add_action( 'after_setup_theme', 'earls_gutenberg_editor_palette_styles' );

/*---------- Gutenberg settings ends ----------*/

/*---------- Enqueue Styles and Scripts ----------*/

function earls_enqueue_scripts() {
	$options = earls_WSH()->option();
	
    //styles
    wp_enqueue_style( 'font-awesome-all', get_template_directory_uri() . '/assets/css/font-awesome-all.css' );
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/assets/css/flaticon.css' );
	wp_enqueue_style( 'owl', get_template_directory_uri() . '/assets/css/owl.css' );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css' );
	wp_enqueue_style( 'jquery-fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/css/animate.css' );
	wp_enqueue_style( 'color', get_template_directory_uri() . '/assets/css/color.css' );
	wp_enqueue_style( 'nice-select', get_template_directory_uri() . '/assets/css/nice-select.css' );
	wp_enqueue_style( 'global', get_template_directory_uri() . '/assets/css/global.css' );
	wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/assets/css/jquery-ui.css' );
	wp_enqueue_style( 'swiper', get_template_directory_uri() . '/assets/css/swiper.min.css' );
	wp_enqueue_style( 'timePicker', get_template_directory_uri() . '/assets/css/timePicker.css' );
	wp_enqueue_style( 'earls-main', get_stylesheet_uri() );
	wp_enqueue_style( 'earls-main-style', get_template_directory_uri() . '/assets/css/style.css' );
	wp_enqueue_style( 'earls-custom', get_template_directory_uri() . '/assets/css/custom.css' );
	wp_enqueue_style( 'earls-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css' );
	wp_enqueue_style( 'earls-responsive', get_template_directory_uri() . '/assets/css/responsive.css' );
	
    //scripts
	wp_enqueue_script( 'jquery-ui-core');
	wp_enqueue_script( 'popper', get_template_directory_uri().'/assets/js/popper.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri().'/assets/js/bootstrap.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'owl', get_template_directory_uri().'/assets/js/owl.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'timePicker', get_template_directory_uri().'/assets/js/timePicker.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'wow', get_template_directory_uri().'/assets/js/wow.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'jquery-fancybox', get_template_directory_uri().'/assets/js/jquery.fancybox.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'appear', get_template_directory_uri().'/assets/js/appear.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'jquery-countto', get_template_directory_uri().'/assets/js/jquery.countTo.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'scrollbar', get_template_directory_uri().'/assets/js/scrollbar.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'jquery-nice-select', get_template_directory_uri().'/assets/js/jquery.nice-select.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'nav-tool', get_template_directory_uri().'/assets/js/nav-tool.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'parallax-scroll', get_template_directory_uri().'/assets/js/parallax-scroll.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'swiper-bundle', get_template_directory_uri().'/assets/js/swiper-bundle.min.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'jquery-ui', get_template_directory_uri().'/assets/js/jquery-ui.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'countdown', get_template_directory_uri().'/assets/js/countdown.js', array( 'jquery' ), '2.1.2', true );
	wp_enqueue_script( 'earls-main-script', get_template_directory_uri().'/assets/js/script.js', array(), false, true );
	if( is_singular() ) wp_enqueue_script('comment-reply');
}
add_action( 'wp_enqueue_scripts', 'earls_enqueue_scripts' );

/*---------- Enqueue styles and scripts ends ----------*/

/*---------- Google fonts ----------*/

function earls_fonts_url() {
	
	$fonts_url = '';
	
		
		$font_families['Tangerine']      = 'Tangerine:400,700';
		$font_families['Oswald']      = 'Oswald:200,300,400,500,600,700';
		$font_families['Josefin+Sans']      = 'Josefin Sans:100,200,300,400,500,600,700,';
		

		$font_families = apply_filters( 'EARLS/includes/classes/header_enqueue/font_families', $font_families );

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$protocol  = is_ssl() ? 'https' : 'http';
		$fonts_url = add_query_arg( $query_args, $protocol . '://fonts.googleapis.com/css' );

		return esc_url_raw($fonts_url);

}

function earls_theme_styles() {
    wp_enqueue_style( 'earls-theme-fonts', earls_fonts_url(), array(), null );
}

add_action( 'wp_enqueue_scripts', 'earls_theme_styles' );
add_action( 'admin_enqueue_scripts', 'earls_theme_styles' );

/*---------- Google fonts ends ----------*/

/*---------- More functions ----------*/

// 1) earls_set function

/**
 * [earls_set description]
 *
 * @param  array $data [description]
 *
 * @return [type]       [description]
 */
if ( ! function_exists( 'earls_set' ) ) {
	function earls_set( $var, $key, $def = '' ) {

		if ( is_object( $var ) && isset( $var->$key ) ) {
			return $var->$key;
		} elseif ( is_array( $var ) && isset( $var[ $key ] ) ) {
			return $var[ $key ];
		} elseif ( $def ) {
			return $def;
		} else {
			return false;
		}
	}
}


//Contact Form 7 List
function get_contact_form_7_list()
{
	$contact_forms = array();
	$cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
	if (!empty($cf7)) {
		foreach ($cf7 as $cform) {
			if (isset($cform)) {
				if (isset($cform->ID) && isset($cform->post_title)) {
					$contact_forms[$cform->ID] = $cform->post_title;
				}
			}
		}
	}
    return $contact_forms;
}

// 2) earls_add_editor_styles function

function earls_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'earls_add_editor_styles' );

// 3) Add specific CSS class by filter body class.

$options = earls_WSH()->option(); 
if( earls_set($options, 'boxed_wrapper') ){

add_filter( 'body_class', function( $classes ) {
    $classes[] = 'boxed_wrapper';
    return $classes;
} );
}

add_filter('doing_it_wrong_trigger_error', function () {return false;}, 10, 0);


//Related Products

function earls_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'earls_related_products_args', 20 );
  function earls_related_products_args( $args ) {
	$args['posts_per_page'] = 4; // 4 related products
	$args['columns'] = 1; // arranged in 2 columns
	return $args;
}