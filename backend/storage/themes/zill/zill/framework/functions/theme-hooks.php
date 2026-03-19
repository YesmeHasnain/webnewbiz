<?php
/**
 * This file includes helper functions used throughout the theme.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * ACTIONS
 */
add_action( 'wp_body_open',     'zill_add_pageloader_icon', 1);

/**
 * FILTERS
 */
add_filter( 'body_class',       'zill_body_classes', 20 );
add_filter( 'excerpt_length',   'zill_change_excerpt_length', 20);
add_filter( 'excerpt_more',     'zill_change_excerpt_more', 20);