<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$header_class = 'vlt-header vlt-header--slide';
$navbar_class = 'vlt-navbar vlt-navbar--main';

?>

<div class="d-none d-lg-block">

	<header class="<?php echo ziomm_sanitize_class( $header_class ); ?>">

		<div class="<?php echo ziomm_sanitize_class( $navbar_class ); ?>">

			<div class="vlt-navbar-inner">

				<div class="vlt-navbar-inner--top">

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vlt-navbar-logo vlt-navbar-logo--small">

						<?php if ( ziomm_get_theme_mod( 'header_logo_small' ) ) : ?>

							<?php

								echo wp_get_attachment_image( ziomm_get_theme_mod( 'header_logo_small' ), 'full', false, array( 'loading' => 'lazy', 'class' => 'black' ) );

								if ( ziomm_get_theme_mod( 'header_logo_small_white' ) ) {
									echo wp_get_attachment_image( ziomm_get_theme_mod( 'header_logo_small_white' ), 'full', false, array( 'loading' => 'lazy', 'class' => 'white' ) );
								}

							?>

						<?php else: ?>

							<h2><?php echo substr( get_bloginfo( 'name' ), 0, 1 ); ?></h2>

						<?php endif; ?>

					</a>
					<!-- .vlt-navbar-logo -->

				</div>
				<!-- /.vlt-navbar-inner--top -->

				<div class="vlt-navbar-inner--center">

					<a href="#" class="vlt-menu-burger js-slide-menu-toggle">
						<i class="icon-menu"></i>
					</a>

				</div>
				<!-- /.vlt-navbar-inner--center -->

				<div class="vlt-navbar-inner--bottom">

					<?php if ( ziomm_get_theme_mod( 'header_contact_link' ) ) : ?>

						<a href="<?php echo esc_url( ziomm_get_theme_mod( 'header_contact_link' ) ); ?>">
							<i class="icon-envelope"></i>
						</a>

					<?php endif; ?>

				</div>
				<!-- /.vlt-navbar-inner--bottom -->

			</div>

		</div>
		<!-- /.vlt-navbar -->

	</header>
	<!-- /.vlt-header--slide -->

	<nav class="vlt-nav vlt-nav--slide" data-submenu-effect="style-2">

		<div class="vlt-nav--slide__background"></div>

		<div class="container">

			<div class="row">

				<div class="col-11 offset-1">

					<div class="vlt-nav-table vlt-nav-table--row">

						<div class="vlt-nav-row vlt-nav-row--full vlt-nav-row--center">

							<div class="vlt-nav--slide__navigation">

								<?php get_template_part( 'template-parts/header/partials/partial', 'primary-menu' ); ?>

							</div>

						</div>

						<div class="vlt-nav vlt-nav-row--bottom">

							<?php if ( ziomm_get_theme_mod( 'header_social_list' ) ) : ?>

								<div class="vlt-navbar-socials text-right">

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

		</div>

	</nav>

</div>
<!-- ./d-none d-lg-block -->