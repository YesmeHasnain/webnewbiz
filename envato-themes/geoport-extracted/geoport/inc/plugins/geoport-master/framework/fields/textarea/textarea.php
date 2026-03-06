<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Textarea
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class GEOPORTFramework_Option_textarea extends GEOPORTFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();
    echo $this->shortcode_generator();
    echo '<textarea name="'. $this->element_name() .'"'. $this->element_class() . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';
    echo $this->element_after();

  }

  public function shortcode_generator() {
    if( isset( $this->field['shortcode'] ) && GEOPORT_ACTIVE_SHORTCODE ) {
      echo '<a href="#" class="button button-primary geoport-shortcode geoport-shortcode-textarea">'. esc_html__( 'Add Shortcode', 'geoport' ) .'</a>';
    }
  }
}
