<?php

include_once 'config.php';

if ( ! function_exists( 'pulse_theme_setup' ) ) :
  function pulse_theme_setup() {
    load_theme_textdomain( 'pulse-theme', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    register_nav_menus( array(
      'header_menu'  => __( 'Header Menu', 'pulse-theme' ),
      'footer_menu'  => __( 'Footer Menu', 'pulse-theme' ),
      'sidebar_menu' => __( 'Secondary Menu', 'pulse-theme' ),
    ) );

    add_theme_support( 'html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );

    add_theme_support( 'custom-background', apply_filters( 'pulse_theme_custom_background_args', array(
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
  }
endif;
add_action( 'after_setup_theme', 'pulse_theme_setup' );
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

// Elementor compatibility
add_action( 'after_switch_theme', 'pulse_theme_set_elementor_settings' );
function pulse_theme_set_elementor_settings() {
    update_option('elementor_disable_typography_schemes', '');
    update_option('elementor_disable_color_schemes', '');
}

add_action('pre_option_elementor_element_cache_ttl', function () {
    return 'disable';
});

function pulse_theme_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'pulse_theme_content_width', 1200 );
}
add_action( 'after_setup_theme', 'pulse_theme_content_width', 0 );

function pulse_theme_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'pulse-theme' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here for blog sidebar.', 'pulse-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer 1', 'pulse-theme' ),
        'id'            => 'sidebar-2',
        'description'   => __( 'Footer widget area 1.', 'pulse-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer 2', 'pulse-theme' ),
        'id'            => 'sidebar-3',
        'description'   => __( 'Footer widget area 2.', 'pulse-theme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'pulse_theme_widgets_init' );

function pulse_theme_scripts() {
    wp_enqueue_style( 'pulse-theme-font', 'https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'pulse-theme-style', get_stylesheet_uri(), array(), PULSE_THEME_VERSION );
    wp_enqueue_style( 'pulse-theme-main', get_template_directory_uri() . '/assets/css/main.css', array(), PULSE_THEME_VERSION );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'pulse-theme-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), PULSE_THEME_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'pulse_theme_scripts' );

function pulse_theme_comment( $comment, $args, $depth ) {
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
            ?>
            <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <p><?php _e( 'Pingback:', 'pulse-theme' ); ?> <?php comment_author_link(); ?></p>
            <?php
            break;
        default : ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <article id="comment-<?php comment_ID(); ?>" class="comment">
                <header class="comment-meta comment-author vcard clear">
                    <div class="avatar_container">
                        <?php echo get_avatar( $comment, 50 ); ?>
                    </div>
                    <div class="comment_info">
                        <?php
                        if(get_comment_author_url( $comment->comment_ID)){
                            printf( '<div class="author"><a href="%1$s" rel="external nofollow" class="url" target="_blank"><span>%2$s</span></a></div>',
                                get_comment_author_url( $comment->comment_ID),
                                get_comment_author($comment->comment_ID)
                            );
                        } else {
                            printf( '<div class="author">%1$s</div>',
                                get_comment_author($comment->comment_ID)
                            );
                        }
                        printf( '<time datetime="%1$s">%2$s</time>',
                            get_comment_time( 'c' ),
                            get_comment_date()
                        );
                        ?>
                    </div>
                </header>
                <section class="comment-content comment">
                    <?php if ( '0' == $comment->comment_approved ) : ?>
                        <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'pulse-theme' ); ?></p>
                    <?php endif; ?>
                    <div id="pulse_theme-comment<?php comment_ID(); ?>">
                        <?php comment_text(); ?>
                    </div>
                </section>
                <div class="reply">
                    <?php if($comment->get_children()): ?>
                        <span class="view_all_comments show">View all <?php echo count($comment->get_children()); ?> replies</span>
                    <?php endif; ?>
                    <div class="reply_div">
                        <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'pulse-theme' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    </div>
                </div>
            </article>
        <?php
        break;
    endswitch;
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
