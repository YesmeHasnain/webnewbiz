<?php
  if ( !empty( geoport_get_option( 'lan1_btn_switch' ) ) && !empty( geoport_get_option( 'lan1_btn_shortcode' ) ) ) {
    echo do_shortcode( geoport_get_option( 'lan1_btn_shortcode' ) );
  }
?>