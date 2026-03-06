<?php

/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
*/


// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit;
}

if ( ! is_active_sidebar( 'barab-blog-sidebar' ) ) {
    return;
}

// Check if it's a single post and set the class accordingly
$sidebar_class = is_single() ? 'col-xxl-3 col-lg-4' : 'col-xxl-3 col-lg-4';

echo '<div class="' . esc_attr( $sidebar_class ) . '">';
    echo '<aside class="sidebar-area">';
        dynamic_sidebar( 'barab-blog-sidebar' );
    echo '</aside>';
echo '</div>';
