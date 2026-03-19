<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package saasten
 */

get_header();

?>


	<?php 

	//Single Blog Template
	
	$saasten_singleb_global = saasten_get_option( 'saasten_single_blog_layout' ); //for globally	
	$saasten_single_post_style = get_post_meta( get_the_ID(),'saasten_blog_post_meta', true );

	$theme_post_meta_single = isset($saasten_single_post_style['saasten_single_blog_layout']) && !empty($saasten_single_post_style['saasten_single_blog_layout']) ? $saasten_single_post_style['saasten_single_blog_layout'] : '';
	
	if( is_single() && !empty( $saasten_single_post_style  ) ) {
	 
		get_template_part( 'template-parts/single/'.$theme_post_meta_single.'' ); 
	
	} elseif ( class_exists( 'CSF' ) && !empty( $saasten_singleb_global ) ) {
		
		get_template_part( 'template-parts/single/'.$saasten_singleb_global.'' );  
		
	} else {
		
		get_template_part( 'template-parts/single/single-one' );  
	}
		
	?>


<?php get_footer(); ?>
