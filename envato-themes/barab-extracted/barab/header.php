<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>

<?php
    wp_body_open();

    /**
    *
    * Preloader
    *
    * Hook barab_preloader_wrap
    *
    * @Hooked barab_preloader_wrap_cb 10
    *
    */
    do_action( 'barab_preloader_wrap' );

    /**
    *
    * barab header
    *
    * Hook barab_header
    *
    * @Hooked barab_header_cb 10
    *
    */
    do_action( 'barab_header' );


    /**
    *
    * barab breadcrumb
    *
    * Hook barab_breadcrumb
    *
    * @Hooked barab_breadcrumb_cb 10
    *
    */
    do_action( 'barab_breadcrumb' );