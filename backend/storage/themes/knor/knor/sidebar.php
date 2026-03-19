<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package knor
 */
?>


<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
   <div class="col-lg-4 col-md-12">
      <div id="sidebar" class="sidebar blog-sidebar">
         <?php dynamic_sidebar( 'sidebar-1' ); ?>
      </div> 
   </div>
<?php } ?>