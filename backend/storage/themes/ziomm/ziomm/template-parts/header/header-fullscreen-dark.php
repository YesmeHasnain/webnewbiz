<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$acf_navbar = ziomm_get_theme_mod( 'page_custom_navigation', true );

$header_class = 'vlt-header vlt-header--fullscreen';

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

<div class="d-none d-lg-block">

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

								<a href="#" class="vlt-menu-burger js-fullscreen-menu-toggle">
									<i class="icon-menu"></i>
								</a>

							</div>
							<!-- /.vlt-navbar-buttons -->

						</div>

					</div>
					<!-- /.vlt-navbar-inner--right -->

				</div>
				<!-- /.vlt-navbar-inner -->

			</div>

		</div>
		<!-- /.vlt-navbar -->

	</header>
	<!-- /.vlt-header--fullscreen -->

	<nav class="vlt-nav vlt-nav--fullscreen vlt-nav--fullscreen-dark" data-submenu-effect="style-1">

		<div class="vlt-nav--fullscreen__background"></div>

		<div class="vlt-nav-table">

			<div class="vlt-nav-row">

				<div class="vlt-nav--fullscreen__header">

					<div class="container">

						<a href="#" class="vlt-menu-burger js-fullscreen-menu-toggle">
							<i class="icon-cross"></i>
						</a>

					</div>

				</div>

			</div>

			<div class="vlt-nav-row vlt-nav-row--full vlt-nav-row--center">

				<div class="container">

					<div class="vlt-nav--fullscreen__navigation">

						<?php get_template_part( 'template-parts/header/partials/partial', 'primary-menu' ); ?>

					</div>

				</div>

			</div>

			<div class="vlt-nav-row">

				<div class="vlt-nav--fullscreen__footer">

					<div class="container">

						<?php if ( ziomm_get_theme_mod( 'header_social_list' ) ) : ?>

							<div class="vlt-navbar-socials">

								<?php

									foreach ( ziomm_get_theme_mod( 'header_social_list' ) as $socialItem ):
										echo '<a class="vlt-social-icon vlt-social-icon--style-1" href="' . esc_url( $socialItem[ 'social_url' ] ) . '" target="_blank"><i class="' . ziomm_sanitize_class( $socialItem[ 'social_icon' ] ) . '"></i></a>';
									endforeach;

								?>

							</div>

						<?php endif; ?>

					</div>

				</div>

			</div>

		</div>

	</nav>
	<!-- /.vlt-nav -->

</div>
<!-- ./d-none d-lg-block -->