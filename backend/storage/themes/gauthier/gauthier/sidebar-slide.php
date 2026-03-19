<?php
/**
 * The sidebar containing the main widget area.
 */
?>
<?php if ( is_active_sidebar( 'gauthier-slidebar' ) ) : ?>
  <?php dynamic_sidebar( 'gauthier-slidebar' ); ?>
<?php endif; ?>