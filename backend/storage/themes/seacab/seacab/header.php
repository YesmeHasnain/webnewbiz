<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package seacab
 */
?>

<!doctype html>
<html <?php language_attributes();?>>
<head>
	<meta charset="<?php bloginfo( 'charset' );?>">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ): ?>
    <?php endif;?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head();?>
</head>

<body <?php body_class();?>>

    <?php wp_body_open();?>


    <?php
        $seacab_preloader = get_theme_mod( 'seacab_preloader', false );
        $seacab_backtotop = get_theme_mod( 'seacab_backtotop', false );

        $seacab_preloader_logo = get_template_directory_uri() . '/assets/images/loader.png';

        $preloader_logo = get_theme_mod('preloader_logo', $seacab_preloader_logo);

    ?>

    <?php if ( !empty( $seacab_preloader ) ): ?>
    <!-- pre loader area start -->
    <div class="preloader">
        <img class="preloader__image" width="60" src="<?php echo esc_url($preloader_logo); ?>" alt="<?php echo esc_attr__('logo','seacab'); ?>" />
    </div>
    <!-- pre loader area end -->
    <?php endif;?>

    <?php if ( !empty( $seacab_backtotop ) ): ?>
    <!-- back to top start -->
    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>
    <!-- back to top end -->
    <?php endif;?>

    
    <!-- header start -->
    <?php do_action( 'seacab_header_style' );?>
    <!-- header end -->
    
    <!-- wrapper-box start -->
    <?php do_action( 'seacab_before_main_content' );?>