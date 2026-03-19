<?php 
$nixer_redux_demo = get_option('redux_demo');
require_once get_template_directory() . '/framework/class-ocdi-importer.php';
require_once get_template_directory() . '/framework/wp_bootstrap_navwalker.php';
function nixer_theme_setup(){
/*
 * This theme uses a custom image size for featured images, displayed on
 * "standard" posts and pages.
 */
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
	$lang = get_template_directory_uri() . '/languages';
	load_theme_textdomain('nixer', $lang);
	add_theme_support( 'post-thumbnails' );
	add_filter('wpcf7_autop_or_not', '__return_false');
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	// This theme uses wp_nav_menu() in one location. 
	register_nav_menus( array(
	'primary' 		=>  esc_html__( 'Primary Menu.', 'nixer' ),
	'secondary' 	=>  esc_html__( 'Secondary Menu.', 'nixer' ),
	'tertiary' 		=>  esc_html__( 'Home Law Firm Agency Menu.', 'nixer' ),
	'quaternary' 	=>  esc_html__( 'Home Creative Portfolio Menu.', 'nixer' ),
	));
}
add_action( 'after_setup_theme', 'nixer_theme_setup' );
if ( ! isset( $content_width ) ) $content_width = 900;
function nixer_theme_scripts_styles(){
	$nixer_redux_demo = get_option('redux_demo');
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style('bootstrap', get_template_directory_uri().'/assets/css/bootstrap.css');
	wp_enqueue_style('swiper-bundle', get_template_directory_uri().'/assets/css/swiper-bundle.css');
	wp_enqueue_style('magnific-popup', get_template_directory_uri().'/assets/css/magnific-popup.css');
	wp_enqueue_style('font-awesome-pro', get_template_directory_uri().'/assets/css/font-awesome-pro.css');
	wp_enqueue_style('spacing', get_template_directory_uri().'/assets/css/spacing.css');
	wp_enqueue_style('animate', get_template_directory_uri().'/assets/css/animate.css');
	wp_enqueue_style('nixer-main', get_template_directory_uri().'/assets/css/main.css');
	wp_enqueue_style('nixer-css', get_stylesheet_uri(), array(), '2025-03-13');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	wp_enqueue_script('comment-reply');
	wp_enqueue_script('bootstrap-bundle', get_template_directory_uri().'/assets/js/bootstrap-bundle.js', array(), false, true);
	wp_enqueue_script('gsap', get_template_directory_uri().'/assets/js/gsap.js', array(), false, true);
	wp_enqueue_script('three', get_template_directory_uri().'/assets/js/three.js', array(), false, true);
	wp_enqueue_script('hover-effect', get_template_directory_uri().'/assets/js/hover-effect.umd.js', array(), false, true);
	wp_enqueue_script('imagesloaded-pkgd', get_template_directory_uri().'/assets/js/imagesloaded-pkgd.js', array(), false, true);
	wp_enqueue_script('gsap-scroll-to-plugin', get_template_directory_uri().'/assets/js/gsap-scroll-to-plugin.js', array(), false, true);
	wp_enqueue_script('gsap-scroll-smoother', get_template_directory_uri().'/assets/js/gsap-scroll-smoother.js', array(), false, true);
	wp_enqueue_script('gsap-scroll-trigger', get_template_directory_uri().'/assets/js/gsap-scroll-trigger.js', array(), false, true);
	wp_enqueue_script('gsap-split-text', get_template_directory_uri().'/assets/js/gsap-split-text.js', array(), false, true);
	wp_enqueue_script('chroma', get_template_directory_uri().'/assets/js/chroma.min.js', array(), false, true);
	wp_enqueue_script('webgl', get_template_directory_uri().'/assets/js/webgl.js', array(), false, true);
	wp_enqueue_script('tween-max', get_template_directory_uri().'/assets/js/tween-max.js', array(), false, true);
	wp_enqueue_script('scroll-magic', get_template_directory_uri().'/assets/js/scroll-magic.js', array(), false, true);
	wp_enqueue_script('slick', get_template_directory_uri().'/assets/js/slick.js', array(), false, true);
	wp_enqueue_script('swiper-bundle', get_template_directory_uri().'/assets/js/swiper-bundle.js', array(), false, true);
	wp_enqueue_script('magnific-popup', get_template_directory_uri().'/assets/js/magnific-popup.js', array(), false, true);
	wp_enqueue_script('purecounter', get_template_directory_uri().'/assets/js/purecounter.js', array(), false, true);
	wp_enqueue_script('isotope-pkgd', get_template_directory_uri().'/assets/js/isotope-pkgd.js', array(), false, true);
	wp_enqueue_script('nixer-main', get_template_directory_uri().'/assets/js/main.js', array(), false, true);
	wp_enqueue_script('tp-cursor', get_template_directory_uri().'/assets/js/tp-cursor.js', array(), false, true);
	wp_enqueue_script('nice-select', get_template_directory_uri().'/assets/js/nice-select.js', array(), false, true);

}
add_action( 'wp_enqueue_scripts', 'nixer_theme_scripts_styles' );
// Widget Sidebar
function nixer_widgets_init()
{
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'nixer' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Appears in the sidebar section of the site.', 'nixer' ),
		'before_widget' => '<div class="sidebar__widget mb-65 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="sidebar__widget-title">',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'nixer_widgets_init' );
function nixer_search_form( $form ) {
	$form = '
		<form action="'.esc_url(home_url('/')).'">
			<div class="sidebar__search-input-2">
				<input type="text" name="s" placeholder="'.esc_attr__('Search', 'nixer').'" value="' . get_search_query() . '">
				<button type="submit">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
						<path d="M1 17.0001L4.86666 13.1334M2.77727 8.1111C2.77727 12.0385 5.96102 15.2222 9.88837 15.2222C13.8157 15.2222 16.9995 12.0385 16.9995 8.1111C16.9995 4.18375 13.8157 1 9.88837 1C5.96102 1 2.77727 4.18375 2.77727 8.1111Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</div>
		</form>
	'; 
	return $form;
}
add_filter( 'get_search_form', 'nixer_search_form' );
/*Contact form 7 remove span*/
add_filter('nixer_wpcf7_form_elements', function($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
	$content = str_replace('<br />', '', $content);
	return $content;
});
function nixer_customize_max_mega_menu($args) {
    if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
        $args['walker'] = new nixer_wp_bootstrap_navwalker();
    }
    return $args;
}
add_filter('max_mega_menu_nav_menu_args', 'nixer_customize_max_mega_menu', 10, 1);

function nixer_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'nixer_add_woocommerce_support' );
function set_custom_woocommerce_pages() {
	if ( class_exists( 'WooCommerce' ) ) {

		$shop_page = get_posts( array(
			'post_type'   => 'page',
			'title'       => 'Shop Sidebar',
			'posts_per_page' => 1
		) );
		if ( $shop_page ) {
			update_option( 'woocommerce_shop_page_id', $shop_page[0]->ID );
		}

		$cart_page = get_posts( array(
			'post_type'   => 'page',
			'title'       => 'Cart Page',
			'posts_per_page' => 1
		) );
		if ( $cart_page ) {
			update_option( 'woocommerce_cart_page_id', $cart_page[0]->ID );
		}

		$checkout_page = get_posts( array(
			'post_type'   => 'page',
			'title'       => 'Checkout Page',
			'posts_per_page' => 1
		) );
		if ( $checkout_page ) {
			update_option( 'woocommerce_checkout_page_id', $checkout_page[0]->ID );
		}

		$myaccount_page = get_posts( array(
			'post_type'   => 'page',
			'title'       => 'My Account Page',
			'posts_per_page' => 1
		) );
		if ( $myaccount_page ) {
			update_option( 'woocommerce_myaccount_page_id', $myaccount_page[0]->ID );
		}
	}
}
add_action( 'after_setup_theme', 'set_custom_woocommerce_pages' );

function nixer_inline_svg_from_url($file_url) {
    if (!preg_match('/\.svg$/i', $file_url)) {
        return $file_url;
    }

    if (strpos($file_url, home_url()) === false) {
        $response = wp_remote_get($file_url);
        if (is_wp_error($response)) {
            return $file_url;
        }
        $svg = wp_remote_retrieve_body($response);
        if (empty($svg)) {
            return $file_url;
        }
        return $svg;
    }

    global $wp_filesystem;
    if (empty($wp_filesystem)) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();
    }

    $upload_dir = wp_upload_dir();
    $base_url   = $upload_dir['baseurl'];
    $base_dir   = $upload_dir['basedir'];

    if (strpos($file_url, $base_url) === 0) {
        $file_path = str_replace($base_url, $base_dir, $file_url);
    } else {
        $parsed_url = parse_url($file_url);
        $file_path  = ABSPATH . ltrim($parsed_url['path'], '/');
    }

    if (!file_exists($file_path)) {
        return $file_url;
    }

    $svg_content = $wp_filesystem->get_contents($file_path);
    if (empty($svg_content) || strpos($svg_content, '<svg') === false) {
        return $file_url;
    }

    return $svg_content;
}


// Comment Form
function nixer_theme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<?php
	if(get_avatar($comment,$size='130' )!=''){?>
		<li>
			<div class="postbox__comment-box d-flex">
				<div class="postbox__comment-info ">
					<div class="postbox__comment-avater mr-20">
						<img src="<?php echo get_avatar_url($comment, array('size' => 90)); ?>" alt="<?php printf(get_comment_author()) ?>">
					</div>  
				</div>
				<div class="postbox__comment-text">
					<div class="postbox__comment-name d-flex align-items-center">
						<h5 class="text-cap"><?php echo esc_html(get_comment_author()); ?></h5>
						<span class="post-meta"><?php echo get_comment_date( get_option( 'date_format' ) ) ?></span>
					</div>
					<?php comment_text(); ?>
					<?php
					$reply_link = get_comment_reply_link(array_merge($args, array(
					    'depth'     => $depth,
					    'max_depth' => $args['max_depth']
					)));

					if (!empty($reply_link)) {
					    echo '<div class="postbox__comment-reply">';
					    echo str_replace('</a>', '<span><svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none"><path d="M1 8.5L8 1.5M8 1.5H1M8 1.5V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>', $reply_link);
					    echo '</div>';
					} ?>
				</div>
			</div>
		</li>
	<?php }else{?>
		<li>
			<div class="postbox__comment-box d-flex">
				<div class="postbox__comment-text">
					<div class="postbox__comment-name d-flex align-items-center">
						<h5 class="text-cap"><?php echo esc_html(get_comment_author()); ?></h5>
						<span class="post-meta"><?php echo get_comment_date( get_option( 'date_format' ) ) ?></span>
					</div>
					<?php comment_text(); ?>
					<?php
					$reply_link = get_comment_reply_link(array_merge($args, array(
					    'depth'     => $depth,
					    'max_depth' => $args['max_depth']
					)));

					if (!empty($reply_link)) {
					    echo '<div class="postbox__comment-reply">';
					    echo str_replace('</a>', '<span><svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none"><path d="M1 8.5L8 1.5M8 1.5H1M8 1.5V8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>', $reply_link);
					    echo '</div>';
					} ?>
				</div>
			</div>
		</li>
	<?php }?>
<?php
}
function nixer_excerpt() {
	$nixer_redux_demo = get_option('redux_demo');
	if(isset($nixer_redux_demo['blog_excerpt'])){
	$limit = $nixer_redux_demo['blog_excerpt'];
	}else{
	$limit = 35;
	}
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'.';
	} else {
	$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	return $excerpt;
}

function nixer_excerpt_2() {
	$nixer_redux_demo = get_option('redux_demo');
	if(isset($nixer_redux_demo['blog_excerpt'])){
	$limit = $nixer_redux_demo['blog_excerpt'];
	}else{
	$limit = 15;
	}
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'.';
	} else {
	$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	return $excerpt;
}
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */
/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/framework/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'nixer_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function nixer_theme_register_required_plugins(){
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		// This is an example of how to include a plugin from the WordPress Plugin Repository.
		array(
            'name'      => esc_html__( 'One Click Demo Import', 'nixer' ),
            'slug'      => 'one-click-demo-import',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Classic Editor', 'nixer' ),
            'slug'      => 'classic-editor',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Classic Widgets', 'nixer' ),
            'slug'      => 'classic-widgets',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'Contact Form 7', 'nixer' ),
            'slug'      => 'contact-form-7',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'Elementor', 'nixer' ),
            'slug'      => 'elementor',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'Max Mega Menu', 'nixer' ),
            'slug'      => 'megamenu',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'User Registration & Membership', 'nixer' ),
            'slug'      => 'user-registration',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'Widget Importer & Exporter', 'nixer' ),
            'slug'      => 'widget-importer-&-exporter',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'WooCommerce', 'nixer' ),
            'slug'      => 'woocommerce',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'YITH WooCommerce Wishlist', 'nixer' ),
            'slug'      => 'yith-woocommerce-wishlist',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'WP Maximum Execution Time Exceeded', 'nixer' ),
            'slug'      => 'wp-maximum-execution-time-exceeded',
            'required'  => true,
        ),  
        array(
            'name'                     => esc_html__( 'Nixer Common', 'nixer' ),
            'slug'                     => 'nixer-common',
            'required'                 => true,
            'source'                   => get_template_directory() . '/framework/plugins/nixer-common.zip',
        ),
        array(
            'name'                     => esc_html__( 'Nixer Elementor', 'nixer' ),
            'slug'                     => 'nixer-elementor',
            'required'                 => true,
            'source'                   => get_template_directory() . '/framework/plugins/nixer-elementor.zip',
        ),
	);
	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'nixer' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'nixer' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'nixer' ), // %s = plugin name.
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'nixer' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'nixer' ), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'nixer' ), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'nixer' ), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is nixerly inactive: %1$s.', 'The following required plugins are nixerly inactive: %1$s.', 'nixer' ), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is nixerly inactive: %1$s.', 'The following recommended plugins are nixerly inactive: %1$s.', 'nixer' ), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'nixer' ), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'nixer' ), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'nixer' ), // %1$s = plugin name(s).
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'nixer' ),
			'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'nixer' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'nixer' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'nixer' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'nixer' ), // %s = dashboard link.
			'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);
	tgmpa( $plugins, $config );
}
?>