<?php global $redux_gauthier;  
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */
if( isset($redux_gauthier['header_layout']) ){
  get_header($redux_gauthier['header_layout']);;	
} else{
    get_header();
}
?>
<div class="top-divider"> </div>
<div id="full-width" <?php body_class( 'woocommerce' ); ?>>
  <?php if ( have_posts() ) :?>
      <div class="customfrontpage-wrapper">
    <div id="content" role="main">
     <?php woocommerce_content(); ?>
     <?php endif; ?>
    </div>
    <!-- #content -->
  </div>
</div>
<?php 
if( isset($redux_gauthier['footer_layout']) ){
  get_template_part($redux_gauthier['footer_layout']);
} else{
    get_footer();
}
?>