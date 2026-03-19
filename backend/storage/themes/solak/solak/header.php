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

    if( class_exists('ReduxFramework') ){
        $solak_display_cursor_dot    =  solak_opt('solak_display_cursor_dot');
        $solak_display_cursor_drag   =  solak_opt('solak_display_cursor_drag');
        // Cursor followr
        if( $solak_display_cursor_dot ){
            echo '<div class="cursor-follower"></div>';
        }

        // slider drag cursor
        if( $solak_display_cursor_drag ){
            echo '<div class="slider-drag-cursor"><img src="'.get_template_directory_uri().'/assets/img/icon/arrow.svg"></div>';
        }
    }

    /**
    *
    * Preloader
    *
    * Hook solak_preloader_wrap
    *
    * @Hooked solak_preloader_wrap_cb 10
    *
    */
    do_action( 'solak_preloader_wrap' );

    /**
    *
    * solak header
    *
    * Hook solak_header
    *
    * @Hooked solak_header_cb 10
    *
    */
    do_action( 'solak_header' );


    /**
    *
    * solak breadcrumb
    *
    * Hook solak_breadcrumb
    *
    * @Hooked solak_breadcrumb_cb 10
    *
    */
    do_action( 'solak_breadcrumb' );