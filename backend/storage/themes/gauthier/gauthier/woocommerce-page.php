<?php global $redux_gauthier;  
/**
 * Template Name: Template for WOO PAGES
 */
if( isset($redux_gauthier['header_layout']) ){
  get_header($redux_gauthier['header_layout']);
} else{
    get_header();
}
?>
<div class="single2-wrapper woocommerce">
  <?php while ( have_posts() ) : the_post(); ?>
  <div id="primary" class="site-content nosidebar">
    <div id="content" role="main">
      <?php get_template_part( 'page-templates/content-woo', get_post_format() ); ?>
      <?php endwhile; // end of the loop. ?>
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