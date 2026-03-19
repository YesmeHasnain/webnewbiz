<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

if ( ! function_exists( 'ziomm_dynamic_css' ) ) {
	function ziomm_dynamic_css( $styles ) {

		$colors = ziomm_get_hsl_variables( '--vlt-accent-1', ziomm_get_theme_mod( 'accent_colors' )[ 'first' ] );
		$colors .= ziomm_get_hsl_variables( '--vlt-accent-2', ziomm_get_theme_mod( 'accent_colors' )[ 'second' ] );

		$styles .= ':root {' . $colors . '}';

		return $styles;
	}
}
add_filter( 'kirki_ziomm_customize_dynamic_css', 'ziomm_dynamic_css' );