<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$footer_class = 'vlt-footer vlt-footer--template';
$acf_footer = ziomm_get_theme_mod( 'page_custom_footer', true );

if ( ziomm_get_theme_mod( 'footer_fixed', $acf_footer ) == 'enable' ) {
	$footer_class .= ' vlt-footer--fixed';
}

$footer_template = ziomm_get_theme_mod( 'footer_template', $acf_footer );

?>

<footer class="<?php echo ziomm_sanitize_class( $footer_class ); ?>">

	<?php echo ziomm_render_elementor_template( $footer_template ); ?>

</footer>
<!-- /.vlt-footer -->