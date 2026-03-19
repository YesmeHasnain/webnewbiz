<?php

//Integrations
require_once VASIA_THEME_DIR . '/inc/admin/themepanel/vendor/merlin/autoload.php';
include_once VASIA_THEME_DIR . '/inc/admin/themepanel/themepanel.php';
require_once VASIA_THEME_DIR . '/inc/integrations/vasia_megamenu/megamenu.php';

// Backoffice
if(is_customize_preview()){
	require_once VASIA_THEME_DIR . '/inc/admin/customizer/customizer.php';
}
require_once VASIA_THEME_DIR . '/inc/admin/dashboard/dashboard.php';

//Helpers
require_once VASIA_THEME_DIR . '/inc/helpers/theme-configs.php';
include_once VASIA_THEME_DIR . '/inc/helpers/ajax-search.php';
include_once VASIA_THEME_DIR . '/inc/helpers/conditionals.php';
include_once VASIA_THEME_DIR . '/inc/helpers/global.php';
include_once VASIA_THEME_DIR . '/inc/helpers/woocommerce.php';

//Frontend
include_once VASIA_THEME_DIR . '/inc/frontend/header.php';
include_once VASIA_THEME_DIR . '/inc/frontend/global.php';
include_once VASIA_THEME_DIR . '/inc/frontend/css-generator.php';
include_once VASIA_THEME_DIR . '/inc/frontend/footer.php';
include_once VASIA_THEME_DIR . '/inc/frontend/posts.php';

if ( is_woocommerce_activated() ) {
include_once VASIA_THEME_DIR . '/inc/frontend/woocommerce/wc-global.php';
include_once VASIA_THEME_DIR . '/inc/frontend/woocommerce/wc-single-product.php';
include_once VASIA_THEME_DIR . '/inc/frontend/woocommerce/wc-catalog-product.php';
include_once VASIA_THEME_DIR . '/inc/frontend/woocommerce/swatches-variant.php';
include_once VASIA_THEME_DIR . '/inc/frontend/woocommerce/variant-gallery.php';
};

if( ! function_exists( 'vasia_enqueue_styles' ) ) {
    function vasia_enqueue_styles() {
    	wp_enqueue_style( 'vasia-style', get_stylesheet_uri(), array(), VASIA_VERSION );
		wp_style_add_data( 'vasia-style', 'rtl', 'replace' );

        wp_enqueue_style( 'bootstrap', VASIA_THEME_URI . '/assets/css/bootstrap-rt.css', array(), '4.0.0');
        wp_enqueue_style( 'slick', VASIA_THEME_URI . '/assets/css/slick.css', array(), '1.5.9' );
        wp_enqueue_style( 'mgf', VASIA_THEME_URI . '/assets/css/magnific-popup.css', array(), '1.1.0' );
        wp_enqueue_style( 'vasia-theme', VASIA_THEME_URI . '/assets/css/theme.css', array(), VASIA_VERSION);
		wp_enqueue_style( 'rt-icons', VASIA_THEME_URI . '/assets/css/roadthemes-icon.css', array(), VASIA_VERSION );
    }
    add_action( 'wp_enqueue_scripts', 'vasia_enqueue_styles', 10 );
}

if( ! function_exists( 'vasia_enqueue_scripts' ) ) {
    function vasia_enqueue_scripts() {
        // Load required scripts.
        wp_enqueue_script( 'slick', VASIA_THEME_URI . '/assets/js/vendor/slick.min.js' , array(), '1.5.9', true);
        wp_enqueue_script( 'jq-countdown', VASIA_THEME_URI . '/assets/js/vendor/jquery.countdown.min.js' , array(), '2.2.0', true);
        wp_enqueue_script( 'mgf', VASIA_THEME_URI . '/assets/js/vendor/jquery.magnific-popup.min.js', array(), '1.1.0', true);
		if(rdt_get_option('lazyload_active', 1)){
			wp_enqueue_script( 'lazysizes', VASIA_THEME_URI . '/assets/js/vendor/lazysizes.js' , array(), '4.0.0', true);
		}
        wp_enqueue_script( 'vasia-theme', VASIA_THEME_URI . '/assets/js/theme.js' , array( 'jquery','imagesloaded' ), VASIA_VERSION, true);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( is_singular( 'product' ) ) {
	        wp_enqueue_script( 'zoom' );
	        wp_enqueue_script( 'photoswipe' );
	        wp_enqueue_script( 'photoswipe-ui-default' );
	    }
		wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_localize_script( 'vasia-theme', 'vasiaVars', array( 
        	'ajax_url'       => admin_url('admin-ajax.php'), 
        	'time_out'       => 1000,
        	'cartConfig'     => rdt_get_option('header_elements_cart_minicart' ,'off-canvas'),
        	'productLayout'  => rdt_get_option('single_product_layout' ,'simple'),
        	'load_more'      => esc_html__( 'Load more', 'vasia' ),
            'loading'        => esc_html__( 'Loading...', 'vasia' ),
            'no_more_item'   => esc_html__( 'All items loaded', 'vasia' ),
            'text_day'       => esc_html__( 'day', 'vasia' ),
            'text_day_plu'   => esc_html__( 'days', 'vasia' ),
            'text_hour'      => esc_html__( 'hour', 'vasia' ),
            'text_hour_plu'  => esc_html__( 'hours', 'vasia' ),
            'text_min'       => esc_html__( 'min', 'vasia' ),
            'text_min_plu'   => esc_html__( 'mins', 'vasia' ),
            'text_sec'       => esc_html__( 'sec', 'vasia' ),
            'text_sec_plu'   => esc_html__( 'secs', 'vasia' ),
            'required_message' => __('Please fill all required fields.','vasia'), 
            'valid_email' => __('Please provide a valid email address.','vasia'), 
        	)
    	);
        
    }    
    
}
add_action( 'wp_enqueue_scripts', 'vasia_enqueue_scripts', 100 );

function vasia_admin_scripts() {
	wp_enqueue_script( 'vasia-admin-scripts', VASIA_THEME_URI . '/assets/js/admin/admin.js', array(), array(), true );
}
add_action('admin_init','vasia_admin_scripts', 100);

/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function vasia_menus() {

	$locations = array(
		'primary'  => __( 'Primary Menu', 'vasia' ),
		// Start demo
		'secondary'  => __( 'Secondary Menu', 'vasia' ),
		// End demo
		'topbar'   => __( 'Top bar Menu', 'vasia' ),
		'vertical' => __( 'Vertical Menu', 'vasia' ),
		'footer'   => __( 'Footer Menu (Use in bottom footer)', 'vasia' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'vasia_menus' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function vasia_widget_areas_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'vasia' ),
			'id'            => 'column-blog',
			'description'   => esc_html__( 'Add widgets here.', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'vasia' ),
			'id'            => 'column-shop',
			'description'   => esc_html__( 'Always show filters from Shop Filter.', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop filter', 'vasia' ),
			'id'            => 'shop-filter',
			'description'   => esc_html__( 'Widget area shows filters in sidebar or above products', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 1', 'vasia' ),
			'id'            => 'sidebar-footer-column-1',
			'description'   => esc_html__( 'Footer column 1', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 2', 'vasia' ),
			'id'            => 'sidebar-footer-column-2',
			'description'   => esc_html__( 'Footer column 2', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 3', 'vasia' ),
			'id'            => 'sidebar-footer-column-3',
			'description'   => esc_html__( 'Footer column 3', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 4', 'vasia' ),
			'id'            => 'sidebar-footer-column-4',
			'description'   => esc_html__( 'Footer column 4', 'vasia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);
}
add_action( 'widgets_init', 'vasia_widget_areas_init' );


/**
 * Load custom control for elementor.
 */
// NeedToCheck : check elementor used
add_action( 'elementor/controls/controls_registered', 'init_controls');

function init_controls() {

  // Include Control files
  require_once( VASIA_THEME_DIR . '/inc/elementor/custom-controls/vasia-choose.php' );

  // Register control
  \Elementor\Plugin::$instance->controls_manager->register_control( 'vasia-choose', new Vasia_Choose());

}

// Disable emoji scripts
if ( ! is_admin() ) {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}