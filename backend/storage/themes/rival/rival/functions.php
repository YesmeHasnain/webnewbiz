<?php
$rival_redux_demo = get_option('redux_demo');

//Custom fields:
require_once get_template_directory() . '/framework/wp_bootstrap_navwalker.php';
require_once get_template_directory() . '/framework/class-ocdi-importer.php';
//Theme Set up:
function rival_theme_setup() {
    /*
     * This theme uses a custom image size for featured images, displayed on
     * "standard" posts and pages.
     */
  	add_theme_support( 'custom-header' ); 
  	add_theme_support( 'custom-background' );
  	$lang = get_template_directory_uri() . '/languages';
    load_theme_textdomain('rival', $lang);
    add_theme_support( 'post-thumbnails' );
    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support( 'automatic-feed-links' );
    // Switches default core markup for search form, comment form, and comments
    // to output valid HTML5.
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
    add_theme_support( 'post-formats', array( 'gallery', 'video', 'image') );
    // This theme uses wp_nav_menu() in one location.
  	register_nav_menus( array(
      'primary' =>  esc_html__( 'Primary: Chosen menu in Left Menu', 'rival' ),
      'primary_right' =>  esc_html__( 'Primary Right: Chosen menu in Right Menu', 'rival' ),
  	) );
      // This theme uses its own gallery styles.
}
add_action( 'after_setup_theme', 'rival_theme_setup' );
if ( ! isset( $content_width ) ) $content_width = 900;

function rival_theme_scripts_styles() {
  	$rival_redux_demo = get_option('redux_demo');
  	$protocol = is_ssl() ? 'https' : 'http';
    wp_enqueue_style('font', get_template_directory_uri().'/assets/css/font.css');
    wp_enqueue_style('fontello', get_template_directory_uri().'/assets/css/fontello.css');
    wp_enqueue_style('base', get_template_directory_uri().'/assets/css/base.css');
    wp_enqueue_style('skeleton', get_template_directory_uri().'/assets/css/skeleton.css');
    wp_enqueue_style('rival-main', get_template_directory_uri().'/assets/css/main.css');
    wp_enqueue_style('magnific-popup', get_template_directory_uri().'/assets/css/magnific-popup.css');
    wp_enqueue_style('flexslider', get_template_directory_uri().'/assets/css/flexslider.css');
    wp_enqueue_style('rival-css', get_stylesheet_uri(), array(), '2023-06-15' );
    if(isset($rival_redux_demo['chosen-color']) && $rival_redux_demo['chosen-color']==1){
    wp_enqueue_style('color', get_template_directory_uri().'/framework/color.php');
    }
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    wp_enqueue_script('comment-reply' );
    wp_enqueue_script('jquery-migrate-1.2.1', get_template_directory_uri().'/assets/js/jquery-migrate-1.2.1.js',array(),false,true);
    wp_enqueue_script('jquery-flexslider', get_template_directory_uri().'/assets/js/jquery.flexslider-min.js',array(),false,true);
    wp_enqueue_script('jquery-easing', get_template_directory_uri().'/assets/js/jquery.easing.1.3.js',array(),false,true);
    wp_enqueue_script('jquery-smooth-scroll', get_template_directory_uri().'/assets/js/jquery.smooth-scroll.js',array(),false,true);
    wp_enqueue_script('jquery-quicksand', get_template_directory_uri().'/assets/js/jquery.quicksand.js',array(),false,true);
    wp_enqueue_script('modernizr-custom', get_template_directory_uri().'/assets/js/modernizr.custom.js',array(),false,true);
    wp_enqueue_script('jquery-magnific-popup', get_template_directory_uri().'/assets/js/jquery.magnific-popup.js',array(),false,true);
    wp_enqueue_script('Placeholders', get_template_directory_uri().'/assets/js/Placeholders.min.js',array(),false,true);
    wp_enqueue_script('jquery-parallax-1.1.3', get_template_directory_uri().'/assets/js/jquery.parallax-1.1.3.js',array(),false,true);
    wp_enqueue_script('rival-script', get_template_directory_uri().'/assets/js/script.js',array(),false,true);
}
add_action( 'wp_enqueue_scripts', 'rival_theme_scripts_styles' );

function rival_move_comment_field_to_bottom( $fields ) {
$comment_field = $fields['comment'];
unset( $fields['comment'] );
$fields['comment'] = $comment_field;
return $fields;
}
add_filter( 'comment_form_fields', 'rival_move_comment_field_to_bottom');

//Custom Excerpt Function
function rival_do_shortcode($content) {
    global $shortcode_tags;
    if (empty($shortcode_tags) || !is_array($shortcode_tags))
        return $content;
    $pattern = get_shortcode_regex();
    return preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $content );
} 
// Widget Sidebar
function rival_widgets_init() {
  	register_sidebar( array(
      'name'          => esc_html__( 'Primary Sidebar', 'rival' ),
      'id'            => 'sidebar-1',        
  		'description'   => esc_html__( 'Appears in the sidebar section of the site.', 'rival' ),        
  		'before_widget' => '<div class="widget %2$s %2$s" id="%2$s">',        
  		'after_widget'  => '</div>',        
  		'before_title'  => '<h2>',        
  		'after_title'   => '</h2>'
    ) );
    register_sidebar( array(
      'name'          => esc_html__( 'Projects Sidebar', 'rival' ),
      'id'            => 'sidebar-projects',        
      'description'   => esc_html__( 'Appears in the sidebar section of the site.', 'rival' ),        
      'before_widget' => '<div class=" %2$s %2$s" id="%2$s">',        
      'after_widget'  => '</div>',        
      'before_title'  => '',        
      'after_title'   => ''
    ) );
    register_sidebar( array(
      'name'          => esc_html__( 'Footer One Widget', 'rival' ),
      'id'            => 'footer-area-1',
      'description'   => esc_html__( 'Footer Widget that appears on the Footer.', 'rival' ),
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => ' ',
      'after_title'   => ' ',
    ) );
    register_sidebar( array(
      'name'          => esc_html__( 'Footer Two Widget', 'rival' ),
      'id'            => 'footer-area-2',
      'description'   => esc_html__( 'Footer Widget that appears on the Footer.', 'rival' ),
      'before_widget' => '<div id="%1$s">',
      'after_widget'  => '</div>',
      'before_title'  => ' <h5 class="footer-widget-title"> ',
      'after_title'   => ' </h5>',
    ) );
    register_sidebar( array(
      'name'          => esc_html__( 'Footer Three Widget', 'rival' ),
      'id'            => 'footer-area-3',
      'description'   => esc_html__( 'Footer Widget that appears on the Footer.', 'rival' ),
      'before_widget' => '<div id="%1$s">',
      'after_widget'  => '</div>',
      'before_title'  => ' <h5 class="footer-widget-title">',
      'after_title'   => ' </h5>',
    ) );
}
add_action( 'widgets_init', 'rival_widgets_init' );
//function tag widgets
function rival_tag_cloud_widget($args) {
  	$args['number'] = 0; //adding a 0 will display all tags
  	$args['largest'] = 18; //largest tag
  	$args['smallest'] = 11; //smallest tag
  	$args['unit'] = 'px'; //tag font unit
  	$args['format'] = 'list'; //ul with a class of wp-tag-cloud
  	$args['exclude'] = array(20, 80, 92); //exclude tags by ID
  	return $args;
}
add_filter( 'widget_tag_cloud_args', 'rival_tag_cloud_widget' );
function rival_excerpt() {
  $rival_redux_demo = get_option('redux_demo');
  if(isset($rival_redux_demo['blog_excerpt'])){
    $limit = $rival_redux_demo['blog_excerpt'];
  }else{
    $limit = 40;
  }
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}
function rival_excerpt2() {
  $rival_redux_demo = get_option('redux_demo');
  if(isset($rival_redux_demo['blog_home_excerpt'])){
    $limit = $rival_redux_demo['blog_home_excerpt'];
  }else{
    $limit = 17;
  }
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}
function rival_excerpt3() {
  $rival_redux_demo = get_option('redux_demo');
  if(isset($rival_redux_demo['blog_excerpt3'])){
    $limit = $rival_redux_demo['blog_excerpt3'];
  }else{
    $limit = 15;
  }
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}
function rival_excerpt4() {
  $rival_redux_demo = get_option('redux_demo');
  if(isset($rival_redux_demo['blog_excerpt4'])){
    $limit = $rival_redux_demo['blog_excerpt4'];
  }else{
    $limit = 18;
  }
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

function rival_search_form( $form ) {
    $form = '
    <form action="' . esc_url(home_url('/')) . '" class="error-page__form">
        <div class="error-page__form-input">
            <input type="search" name="s" value="' . get_search_query() . '" placeholder="'.esc_attr__('Search here', 'rival').'">
            <button type="submit"><i class="far fa-search"></i></button>
        </div>
    </form>
        
	';
    return $form;
}
add_filter( 'get_search_form', 'rival_search_form' );
function rival_theme_comment($comment, $args, $depth) {
    //echo 's';
  $GLOBALS['comment'] = $comment; ?>
  <?php if(get_avatar($comment,$size='35' )!=''){?>
    <li>
       <div class="comment"> 
          <div class="img">
             <?php echo get_avatar($comment,$size='35' ); ?>
          </div>  
          <div class="commentContent">
             <div class="commentsInfo">
                <div class="author"><?php printf( get_comment_author_link()) ?></div>
                <div class="date"><?php comment_time('F j, Y'); ?></div>
             </div>
             <?php comment_text(); ?>
          </div>
          <div class="reply-btn">
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
          </div>
       </div>
    </li>
    <?php }else{?>
    <li class="nopd">
       <div class="comment">
          <div class="commentContent">
             <div class="commentsInfo">
                <div class="author"><?php printf( get_comment_author_link()) ?></div>
                <div class="date"><?php comment_time('F j, Y'); ?></div>
             </div>
             <?php comment_text(); ?>
          </div>
          <div class="reply-btn">
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
          </div>
       </div>
    </li>
  <?php }?>



<?php
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
 * rivallude the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/framework/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'rival_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one rivalluded with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
 
 
function rival_theme_register_required_plugins() {
    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to rivallude a plugin from the WordPress Plugin Repository.
      array(
            'name'      => esc_html__( 'One Click Demo Import', 'rival' ),
            'slug'      => 'one-click-demo-import',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Classic Editor', 'rival' ),
            'slug'      => 'classic-editor',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Classic Widgets', 'rival' ),
            'slug'      => 'classic-widgets',
            'required'  => true,
        ),
        array(
            'name'      => esc_html__( 'Widget Importer & Exporter', 'rival' ),
            'slug'      => 'widget-importer-&-exporter',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Contact Form 7', 'rival' ),
            'slug'      => 'contact-form-7',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'SVG Support', 'rival' ),
            'slug'      => 'svg-support',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'WP Maximum Execution Time Exceeded', 'rival' ),
            'slug'      => 'wp-maximum-execution-time-exceeded',
            'required'  => true,
        ), 
        array(
            'name'      => esc_html__( 'Elementor', 'rival' ),
            'slug'      => 'elementor',
            'required'  => true,
        ), 
        array(
            'name'                     => esc_html__( 'Rival Common', 'rival' ),
            'slug'                     => 'rival-common',
            'required'                 => true,
            'source'                   => get_template_directory() . '/framework/plugins/rival-common.zip',
        ),
        array(
            'name'                     => esc_html__( 'Rival Elementor', 'rival' ),
            'slug'                     => 'rival-elementor',
            'required'                 => true,
            'source'                   => get_template_directory() . '/framework/plugins/rival-elementor.zip',
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
            'page_title'                      => esc_html__( 'Install Required Plugins', 'rival' ),
            'menu_title'                      => esc_html__( 'Install Plugins', 'rival' ),
            'installing'                      => esc_html__( 'Installing Plugin: %s', 'rival' ), // %s = plugin name.
            'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'rival' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'rival' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'rival' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'rival' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'rival' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'rival' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'rival' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'rival' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'rival' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'rival' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'rival' ),
            'return'                          => esc_html__( 'Return to Required Plugins Installer', 'rival' ),
            'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'rival' ),
            'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'rival' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );
    tgmpa( $plugins, $config );
}
?>