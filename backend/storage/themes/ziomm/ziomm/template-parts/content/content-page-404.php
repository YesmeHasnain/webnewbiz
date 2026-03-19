<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<article <?php post_class( 'vlt-page vlt-page--404 jarallax' ); ?>>

	<div class="container">

		<div class="vlt-page-error-content">

			<h1 class="vlt-heading" data-aos="fade"><?php echo wp_kses( ziomm_get_theme_mod( 'error_title' ), 'ziomm_error_title' ); ?></h1>

			<p data-aos="fade" data-aos-delay="100"><?php echo wp_kses( ziomm_get_theme_mod( 'error_subtitle' ), 'ziomm_error_subtitle' ); ?></p>

			<div data-aos="fade" data-aos-delay="200">

				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vlt-btn vlt-btn--primary vlt-btn--effect"><?php esc_html_e( 'Back to Home', 'ziomm' ); ?></a>

			</div>

		</div>

	</div>

</article>
<!-- /.vlt-page -->