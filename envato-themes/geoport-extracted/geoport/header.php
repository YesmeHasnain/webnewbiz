<?php
/**
 * The header for our theme.
 *
 * @package geoport
 */
?>
<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    <meta charset="<?php bloginfo('charset');?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php 
        if ( ! function_exists( 'wp_body_open' ) ) {
            function wp_body_open() {
                do_action( 'wp_body_open' );
            }
        }
        if( function_exists( 'geoport_framework_init' ) ) {
            $preloader = geoport_get_option('geoport_preloader_enable');
            if (!empty($preloader)) {
                do_action( 'geoport_preloading' );
            } 
        }
    ?>

    <!-- Start of Header
    ============================================= -->
    <?php do_action( 'geoport_header_style' ); ?> 
    <!-- End of  Header 
    ============================================= -->

    <main>