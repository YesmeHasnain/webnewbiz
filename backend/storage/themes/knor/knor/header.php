<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package knor
 */
 
$knor_preloader = knor_get_option('preloader_enable', false);
 
 
?>
<!DOCTYPE html>
  <html <?php language_attributes(); ?>> 
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<?php wp_head(); ?>
    </head>
	
	
    <body <?php body_class(); ?> >
		
		<?php wp_body_open(); ?>

		<!-- Theme Preloader -->
		<?php if($knor_preloader == true) :?>
		<div id="preloader">
			<div class="spinner"></div>
		</div>
		<?php endif; ?>


		<div class="body-inner-content">
      
		<?php
		
		// Select Header Style
		
		$knor_nav_global = knor_get_option( 'nav_menu' ); // Global
		$knor_nav_style =  get_post_meta( get_the_ID(), 'knor_post_meta', true ); // Post Metabox

		if( is_page() && !empty( $knor_nav_style  ) ) {
		 
			get_template_part( 'template-parts/headers/'.$knor_nav_style['nav_menu'].'' ); 
		
		} elseif ( class_exists( 'CSF' ) && !empty( $knor_nav_global ) ) {
			
			get_template_part( 'template-parts/headers/'.$knor_nav_global.'' ); 
			
		} else {
			
			get_template_part( 'template-parts/headers/nav-style-one' ); 
			
		}
	
		?>
		