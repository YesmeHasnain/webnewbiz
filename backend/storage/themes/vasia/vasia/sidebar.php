<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package vasia
 */

if ( ! is_active_sidebar( 'column-blog' ) ) {
	return;
}
?>

<aside id="blog-sidebar" class="widget-area widget-area-side col-lg-3 col-12">
	<?php dynamic_sidebar( 'column-blog' ); ?>
</aside><!-- #secondary -->
