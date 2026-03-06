<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// TAXONOMY OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options     = array();

// -----------------------------------------
// Taxonomy Options                        -
// -----------------------------------------
$options[]   = array(
  'id'       => '_service_taxonomy_options',
  'taxonomy' => 'service_category',
  'fields'   => array(

    array(
      'id'    => 'service_cat_icon',
      'type'  => 'icon',
      'title' => 'Select Category Icon',
    ),

  ),
);

GEOPORTFramework_Taxonomy::instance( $options );
