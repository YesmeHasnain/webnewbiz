<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package geoport
 */

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
	return;
}

if( function_exists( 'geoport_framework_init' ) ) {
    if ( is_home() || is_front_page() ) {
        $blog_layout = geoport_get_option('blog_layout');
    } elseif ( is_single() ) {
        $blog_layout = geoport_get_option('blog_single_layout');
    } else {
       $blog_layout = geoport_get_option('blog_layout'); 
    }

    if ( $blog_layout == 'left-sidebar' ) {
        $sidebar_class = 'sidebar-left';
    } elseif ( $blog_layout == 'right-sidebar' ) {
        $sidebar_class = 'sidebar-right';
     } elseif ( $blog_layout == 'full-width' ) {
        $sidebar_class = 'sidebar-default';
    } else {
        $sidebar_class = 'sidebar-default';
    }
} else {
    $sidebar_class = 'sidebar-default';
}

?>

<!-- End Blog Sidebar -->
<div class="col-lg-4">
    <aside class="blog-sidebar <?php echo esc_attr( $sidebar_class ); ?>">
        <?php dynamic_sidebar( 'right-sidebar' ); ?>
    </aside>
</div>