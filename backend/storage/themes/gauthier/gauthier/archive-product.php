<?php global $redux_gauthier;  
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;
if( isset($redux_gauthier['header_layout']) ){
  get_header($redux_gauthier['header_layout']);;	
} else{
    get_header();
}
?>
<div class="single2-wrapper <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?> woocommerce">
  <?php if ( have_posts() ) :?>
  <div id="primary" class="site-content">
 <?php woocommerce_breadcrumb(); ?> 
    <div id="content" role="main">
     <?php woocommerce_content(); ?>
     <?php endif; ?>
    </div>
    <!-- #content -->
  </div>
  <div class="sidebar <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
    <div class="single2-widget">
		  <?php if (is_active_sidebar('gauthier-woowidget')) :?>
		  <?php dynamic_sidebar( 'gauthier-woowidget' ); ?>
		  <?php endif; ?>  	
    </div>
  </div>
</div>
<?php 
if( isset($redux_gauthier['footer_layout']) ){
  get_template_part($redux_gauthier['footer_layout']);
} else{
    get_footer();
}
?>