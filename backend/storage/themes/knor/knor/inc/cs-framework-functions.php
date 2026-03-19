<?php

/**
 *
 * Get Knor Theme options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'knor_get_option' ) ) {
	function knor_get_option( $option = '', $default = null ) {
		$options = get_option( 'knor_theme_options' ); // Attention: Set your unique id of the framework
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}

/**
 *
 * Get get switcher option
 *  for theme options
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'knor_get_switcher_option' )) {

	function knor_get_switcher_option( $option = '', $default = null ) {
		$options = get_option( 'knor_theme_options' ); // Attention: Set your unique id of the framework
		$return_val =  ( isset( $options[$option] ) ) ? $options[$option] : $default;
		$return_val =  (is_null($return_val) || '1' == $return_val ) ? true : false;;
		return $return_val;
	}
}

if ( ! function_exists( 'knor_switcher_option' )) {

	function knor_switcher_option( $option = '', $default = null ) {
		$options = get_option( 'knor_theme_options' ); // Attention: Set your unique id of the framework
		$return_val =  ( isset( $options[$option] ) ) ? $options[$option] : $default;
		$return_val =  ( '1' == $return_val ) ? true : false;;
		return $return_val;
	}
}

/**
 *
 * Get customize option
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! function_exists( 'knor_get_customize_option' ) ) {

	function knor_get_customize_option( $option = '', $default = null ) {
		$options = get_option( 'knor_customize_options' ); // Attention: Set your unique id of the framework
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}
}