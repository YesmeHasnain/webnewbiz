<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

if ( ! is_active_sidebar( 'offcanvas_sidebar' ) ) {
	return;
}

$acf_navbar = ziomm_get_theme_mod( 'page_custom_navigation', true );

$offcanvas_sidebar_class = 'vlt-offcanvas-sidebar';

if ( ziomm_get_theme_mod( 'navigation_dark', $acf_navbar ) == 'enable' ) {
	$offcanvas_sidebar_class .= apply_filters( 'vlthemes/navigation_dark', ' vlt-offcanvas-sidebar--dark' );
}

?>

<div class="<?php echo ziomm_sanitize_class( $offcanvas_sidebar_class ); ?>">

	<a href="#" class="vlt-menu-burger vlt-menu-burger--opened js-offcanvas-sidebar-close">
		<i class="icon-cross"></i>
	</a>

	<div class="vlt-offcanvas-sidebar__inner">

		<div class="vlt-sidebar">

			<?php

				if ( is_active_sidebar( 'offcanvas_sidebar' ) ) {
					dynamic_sidebar( 'offcanvas_sidebar' );
				}

			?>

		</div>

	</div>

</div>
<!-- /.vlt-offcanvas-sidebar -->