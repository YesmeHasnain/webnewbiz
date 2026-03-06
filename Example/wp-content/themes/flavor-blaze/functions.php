<?php
include_once 'config.php';

if ( ! function_exists( 'blaze_theme_setup' ) ) :
  function blaze_theme_setup() {
    load_theme_textdomain( 'flavor-blaze', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus( array(
      'header_menu'   => __('Header Menu', 'flavor-blaze' ),
      'footer_menu'   => __('Footer Menu', 'flavor-blaze' ),
      'sidebar_menu'  => __('Secondary Menu', 'flavor-blaze' ),
    ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-background', apply_filters( 'blaze_custom_background_args', array(
      'default-color' => 'ffffff',
      'default-image' => '',
    ) ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-logo', array(
      'height'      => 250,
      'width'       => 250,
      'flex-width'  => true,
      'flex-height' => true,
    ) );
    require get_template_directory() . '/inc/class-wc-theme-support.php';
  }
endif;
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
add_action( 'after_switch_theme', 'blaze_set_elementor_settings' );
add_action( 'after_setup_theme', 'blaze_theme_setup' );

function blaze_set_elementor_settings() {
    update_option('elementor_disable_typography_schemes', '');
    update_option('elementor_disable_color_schemes', '');
}

add_action('pre_option_elementor_element_cache_ttl', function () { return 'disable'; });

function blaze_content_width() {
  $GLOBALS['content_width'] = apply_filters( 'blaze_content_width', 640 );
}
add_action( 'after_setup_theme', 'blaze_content_width', 0 );

function blaze_widgets_init() {
  register_sidebar( array( 'name' => __( 'Sidebar Widget1', 'flavor-blaze' ), 'id' => 'sidebar-1', 'description' => __( 'Add widgets here.', 'flavor-blaze' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
  register_sidebar( array( 'name' => __( 'Sidebar Widget2', 'flavor-blaze' ), 'id' => 'sidebar-4', 'description' => __( 'Add widgets here.', 'flavor-blaze' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
  register_sidebar( array( 'name' => __( 'Sidebar Widget3', 'flavor-blaze' ), 'id' => 'sidebar-5', 'description' => __( 'Add widgets here.', 'flavor-blaze' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
  register_sidebar( array( 'name' => __( 'Footer 1', 'flavor-blaze' ), 'id' => 'sidebar-2', 'description' => __( 'Add widgets here.', 'flavor-blaze' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
  register_sidebar( array( 'name' => __( 'Footer 2', 'flavor-blaze' ), 'id' => 'sidebar-3', 'description' => __( 'Add widgets here.', 'flavor-blaze' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
}
add_action( 'widgets_init', 'blaze_widgets_init' );

function blaze_scripts() {
  wp_enqueue_style( 'flavor-blaze-font', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap', array(), blaze_VERSION );
  wp_enqueue_script( 'jquery' );
  if ( blaze_DEV === FALSE ) {
      if ( blaze_DEBUG === FALSE ) {
          wp_enqueue_style( 'flavor-blaze-style', get_template_directory_uri() . '/assets/css/styles.min.css', array(), blaze_VERSION );
          wp_enqueue_script( 'flavor-blaze-script', get_template_directory_uri() . '/assets/js/scripts.min.js', array(), blaze_VERSION );
      } else {
          wp_enqueue_style( 'flavor-blaze-style', get_template_directory_uri() . '/assets/css/styles.css', array(), blaze_VERSION );
          wp_enqueue_script( 'flavor-blaze-script', get_template_directory_uri() . '/assets/js/scripts.js', array(), blaze_VERSION );
      }
  } else {
      wp_enqueue_style( 'flavor-blaze-style', get_stylesheet_uri() );
      wp_enqueue_script( 'flavor-blaze-script', get_template_directory_uri() . '/assets/js/script.js', array(), blaze_VERSION, true );
      wp_enqueue_script( 'flavor-blaze-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), blaze_VERSION, true );
      wp_enqueue_script( 'flavor-blaze-skip-link', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), blaze_VERSION, true );
  }
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_action( 'wp_enqueue_scripts', 'blaze_scripts' );

// Load custom CSS injected by Webnewbiz (stored in wp_options)
function blaze_load_custom_css() {
    $custom_css = get_option( 'webnewbiz_custom_css', '' );
    if ( $custom_css ) {
        wp_add_inline_style( 'flavor-blaze-style', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'blaze_load_custom_css', 20 );

// Mobile menu toggle script (inline)
function blaze_mobile_menu_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggle = document.querySelector('.menu-toggle');
        var nav = document.querySelector('.main-navigation');
        if (toggle && nav) {
            toggle.addEventListener('click', function() {
                nav.classList.toggle('toggled');
                var expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !expanded);
            });
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'blaze_mobile_menu_script' );

function blaze_comment( $comment, $args, $depth ) {
  switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' : ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <p><?php _e( 'Pingback:', 'flavor-blaze' ); ?> <?php comment_author_link(); ?></p>
      <?php break;
    default : ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <header class="comment-meta comment-author vcard clear">
              <div class="avatar_container"><?php echo get_avatar( $comment, 50 ); ?></div>
              <div class="comment_info">
                  <?php
                  if(get_comment_author_url( $comment->comment_ID)){
                    printf( '<div class="author"><a href="%1$s" rel="external nofollow" class="url" target="_blank"><span>%2$s</span></a></div>', get_comment_author_url( $comment->comment_ID), get_comment_author($comment->comment_ID) );
                  } else {
                    printf( '<div class="author">%1$s</div>', get_comment_author($comment->comment_ID) );
                  }
                  printf( '<time datetime="%1$s">%2$s</time>', get_comment_time( 'c' ), sprintf( __( '%1$s', 'flavor-blaze' ), get_comment_date() ) );
                  ?>
              </div>
            </header>
            <section class="comment-content comment">
              <?php if ( '0' == $comment->comment_approved ) : ?>
                  <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'flavor-blaze' ); ?></p>
              <?php endif; ?>
                <div id="blaze-comment<?php comment_ID(); ?>"><?php comment_text(); ?></div>
            </section>
            <div class="reply">
            <?php if($comment->get_children()): ?>
                <span class="view_all_comments show">View all <?php echo count($comment->get_children()); ?> replies</span>
            <?php endif; ?>
                <div class="reply_div">
                  <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'flavor-blaze' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div>
            </div>
        </article>
      <?php break;
  endswitch;
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( class_exists('woocommerce') && defined('ELEMENTOR_VERSION') ) {
    require 'woo/cart_checkout.php';
    new CartCheckout();
}
