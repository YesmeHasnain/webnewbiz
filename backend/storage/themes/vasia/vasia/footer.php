<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package vasia
 */

?>
</main>
	<?php 
		$footer_style = rdt_get_option('footer_style', 1);
	?>
	<footer id="footer" class="site-footer style-<?php echo esc_attr($footer_style); ?>">

		<?php do_action( 'vasia_footer' ) ?>

	</footer><!-- #colophon -->

	<?php do_action( 'vasia_after_site' ) ?>

</div><!-- #page -->
<div class="vasia-close-side"></div>
<?php do_action('vasia_float_right_position'); ?>
<?php wp_footer(); ?>
</body>
</html>
