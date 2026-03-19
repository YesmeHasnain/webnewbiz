<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$acf_navbar = ziomm_get_theme_mod( 'page_custom_navigation', true );

$header_class = 'vlt-header vlt-header--mobile';

if ( ziomm_get_theme_mod( 'navigation_opaque', $acf_navbar ) == 'enable' ) {
	$header_class .= apply_filters( 'vlthemes/navigation_opaque', ' vlt-header--opaque' );
}

if ( ziomm_get_theme_mod( 'navigation_dark', $acf_navbar ) == 'enable' ) {
	$header_class .= apply_filters( 'vlthemes/navigation_dark', ' vlt-header--dark' );
}

$navbar_class = 'vlt-navbar vlt-navbar--main';

if ( ziomm_get_theme_mod( 'navigation_transparent', $acf_navbar ) == 'enable' ) {
	$navbar_class .= apply_filters( 'vlthemes/navigation_transparent', ' vlt-navbar--transparent' );
}

if ( ziomm_get_theme_mod( 'navigation_transparent_always', $acf_navbar ) == 'enable' ) {
	$navbar_class .= apply_filters( 'vlthemes/navigation_transparent_always', ' vlt-navbar--transparent-always' );
}

if ( ziomm_get_theme_mod( 'navigation_sticky', $acf_navbar ) == 'enable' ) {
	$navbar_class .= apply_filters( 'vlthemes/navigation_sticky', ' vlt-navbar--sticky' );

	if ( ziomm_get_theme_mod( 'navigation_hide_on_scroll', $acf_navbar ) == 'enable' ) {

		$navbar_class .= apply_filters( 'vlthemes/navigation_hide_on_scroll', ' vlt-navbar--hide-on-scroll' );

	}

}

if ( ziomm_get_theme_mod( 'navigation_white_text_on_top', $acf_navbar ) == 'enable' ) {
	$navbar_class .= apply_filters( 'vlthemes/navigation_white_text_on_top', ' vlt-navbar--white-text-on-top' );
}

?>

<div class="d-lg-none d-sm-block">

	<header class="<?php echo ziomm_sanitize_class( $header_class ); ?>">

		<div class="<?php echo ziomm_sanitize_class( $navbar_class ); ?>">

			<div class="container">

				<div class="vlt-navbar-inner">

					<div class="vlt-navbar-inner--left">

						<div class="d-flex align-items-stretch justify-content-center h-100">

							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vlt-navbar-logo">

								<?php if ( ziomm_get_theme_mod( 'header_logo' ) ) : ?>

									<?php

										echo wp_get_attachment_image( ziomm_get_theme_mod( 'header_logo' ), 'full', false, array( 'loading' => 'lazy', 'class' => 'black' ) );

										if ( ziomm_get_theme_mod( 'header_logo_white' ) ) {
											echo wp_get_attachment_image( ziomm_get_theme_mod( 'header_logo_white' ), 'full', false, array( 'loading' => 'lazy', 'class' => 'white' ) );
										}

									?>

								<?php else: ?>

									<h2><?php bloginfo( 'name' ); ?></h2>

								<?php endif; ?>

							</a>
							<!-- .vlt-navbar-logo -->

						</div>

					</div>
					<!-- /.vlt-navbar-inner--left -->

					<div class="vlt-navbar-inner--right">

						<div class="d-flex align-items-center justify-content-center h-100">

							<div class="vlt-navbar-buttons">

								<a href="#" class="vlt-menu-burger js-mobile-menu-toggle"><i class="icon-menu"></i></a>

							</div>

						</div>

					</div>
					<!-- /.vlt-navbar-inner--right -->

				</div>
				<!-- /.vlt-navbar-inner -->

			</div>

			<nav class="vlt-nav vlt-nav--mobile" data-submenu-effect="style-2">

				<div class="vlt-nav--mobile__navigation">

					<div class="container">

						<?php get_template_part( 'template-parts/header/partials/partial', 'primary-menu' ); ?>

					</div>

				</div>

			</nav>
			<!-- /.vlt-nav -->

		</div>
		<!-- /.vlt-navbar -->

	</header>
	<!-- /.vlt-header--mobile -->

</div>
<!-- ./d-none d-lg-block -->