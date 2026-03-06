<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Text sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_text' ) ) {
  function geoport_sanitize_text( $value, $field ) {
    return wp_filter_nohtml_kses( $value );
  }
  add_filter( 'geoport_sanitize_text', 'geoport_sanitize_text', 10, 2 );
}

/**
 *
 * Textarea sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_textarea' ) ) {
  function geoport_sanitize_textarea( $value ) {

    global $allowedposttags;
    return wp_kses( $value, $allowedposttags );

  }
  add_filter( 'geoport_sanitize_textarea', 'geoport_sanitize_textarea' );
}

/**
 *
 * Checkbox sanitize
 * Do not touch, or think twice.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_checkbox' ) ) {
  function geoport_sanitize_checkbox( $value ) {

    if( ! empty( $value ) && $value == 1 ) {
      $value = true;
    }

    if( empty( $value ) ) {
      $value = false;
    }

    return $value;

  }
  add_filter( 'geoport_sanitize_checkbox', 'geoport_sanitize_checkbox' );
  add_filter( 'geoport_sanitize_switcher', 'geoport_sanitize_checkbox' );
}

/**
 *
 * Image select sanitize
 * Do not touch, or think twice.
 *
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_image_select' ) ) {
  function geoport_sanitize_image_select( $value ) {

    if( isset( $value ) && is_array( $value ) ) {
      if( count( $value ) ) {
        $value = $value;
      } else {
        $value = $value[0];
      }
    } else if ( empty( $value ) ) {
      $value = '';
    }

    return $value;

  }
  add_filter( 'geoport_sanitize_image_select', 'geoport_sanitize_image_select' );
}

/**
 *
 * Group sanitize
 * Do not touch, or think twice.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_group' ) ) {
  function geoport_sanitize_group( $value ) {
    return ( empty( $value ) ) ? '' : $value;
  }
  add_filter( 'geoport_sanitize_group', 'geoport_sanitize_group' );
}

/**
 *
 * Title sanitize
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_title' ) ) {
  function geoport_sanitize_title( $value ) {
    return sanitize_title( $value );
  }
  add_filter( 'geoport_sanitize_title', 'geoport_sanitize_title' );
}

/**
 *
 * Text clean
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_sanitize_clean' ) ) {
  function geoport_sanitize_clean( $value ) {
    return $value;
  }
  add_filter( 'geoport_sanitize_clean', 'geoport_sanitize_clean', 10, 2 );
}
