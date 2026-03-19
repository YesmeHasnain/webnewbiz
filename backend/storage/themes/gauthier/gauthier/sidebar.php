<?php
/**
 * The sidebar containing the main widget area.
 */
?>
<?php if ( is_active_sidebar( 'gauthier-sidebar' ) ) : ?>
  <?php dynamic_sidebar( 'gauthier-sidebar' ); ?>
<?php endif; ?>