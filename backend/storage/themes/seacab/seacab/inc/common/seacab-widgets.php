<?php 

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function seacab_widgets_init() {

    $footer_style_2_switch = get_theme_mod( 'footer_style_2_switch', false );

    /**
     * blog sidebar
     */
    register_sidebar( [
        'name'          => esc_html__( 'Blog Sidebar', 'seacab' ),
        'id'            => 'blog-sidebar',
        'description'          => esc_html__( 'Set Your Blog Widget', 'seacab' ),
        'before_widget' => '<div id="%1$s" class="sidebar__single %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="sidebar__widget-head mb-35"><h3 class="sidebar__title">',
        'after_title'   => '</h3></div>',
    ] );


    $footer_widgets = get_theme_mod( 'footer_widget_number', 4 );

    // footer default
    for ( $num = 1; $num <= $footer_widgets; $num++ ) {
        register_sidebar( [
            'name'          => sprintf( esc_html__( 'Footer %1$s', 'seacab' ), $num ),
            'id'            => 'footer-' . $num,
            'description'   => sprintf( esc_html__( 'Footer column %1$s', 'seacab' ), $num ),
            'before_widget' => '<div id="%1$s" class="footer__widget footer-col-'.$num.' %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-widget__title">',
            'after_title'   => '</h3>',
        ] );
    }

    // footer 2
    if ( $footer_style_2_switch ) {
        for ( $num = 1; $num <= $footer_widgets; $num++ ) {

            register_sidebar( [
                'name'          => sprintf( esc_html__( 'Footer Style 2 : %1$s', 'seacab' ), $num ),
                'id'            => 'footer-2-' . $num,
                'description'   => sprintf( esc_html__( 'Footer Style 2 : %1$s', 'seacab' ), $num ),
                'before_widget' => '<div id="%1$s" class="footer__widget footer__widget-2 footer-col-2-'.$num.' %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="footer-widget__title">',
                'after_title'   => '</h3>',
            ] );
        }
    }
}
add_action( 'widgets_init', 'seacab_widgets_init' );