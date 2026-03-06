<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_get_icons' ) ) {
  function geoport_get_icons() {

    do_action( 'geoport_add_icons_before' );

    $jsons = apply_filters( 'geoport_add_icons_json', glob( GEOPORT_DIR . '/fields/icon/*.json' ) );

    if( ! empty( $jsons ) ) {

      foreach ( $jsons as $path ) {

        $object = geoport_get_icon_fonts( 'fields/icon/'. basename( $path ) );

        if( is_object( $object ) ) {

          echo ( count( $jsons ) >= 2 ) ? '<h4 class="geoport-icon-title">'. $object->name .'</h4>' : '';

          foreach ( $object->icons as $icon ) {
            echo '<a class="geoport-icon-tooltip" data-geoport-icon="'. $icon .'" data-title="'. $icon .'"><span class="geoport-icon geoport-selector"><i class="'. $icon .'"></i></span></a>';
          }

        } else {
          echo '<h4 class="geoport-icon-title">'. esc_html__( 'Error! Can not load json file.', 'geoport' ) .'</h4>';
        }

      }

    }

    do_action( 'geoport_add_icons' );
    do_action( 'geoport_add_icons_after' );

    die();
  }
  add_action( 'wp_ajax_geoport-get-icons', 'geoport_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_export_options' ) ) {
  function geoport_export_options() {

    header('Content-Type: plain/text');
    header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo geoport_encode_string( get_option( GEOPORT_OPTION ) );

    die();
  }
  add_action( 'wp_ajax_geoport-export-options', 'geoport_export_options' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'geoport_set_icons' ) ) {
  function geoport_set_icons() {

    echo '<div id="geoport-icon-dialog" class="geoport-dialog" title="'. esc_html__( 'Add Icon', 'geoport' ) .'">';
    echo '<div class="geoport-dialog-header geoport-text-center"><input type="text" placeholder="'. esc_html__( 'Search a Icon...', 'geoport' ) .'" class="geoport-icon-search" /></div>';
    echo '<div class="geoport-dialog-load"><div class="geoport-icon-loading">'. esc_html__( 'Loading...', 'geoport' ) .'</div></div>';
    echo '</div>';

  }
  add_action( 'admin_footer', 'geoport_set_icons' );
  add_action( 'customize_controls_print_footer_scripts', 'geoport_set_icons' );
}
