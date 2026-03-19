<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
 * Register sidebars
 */
if ( ! function_exists( 'ziomm_register_sidebar' ) ) {
	function ziomm_register_sidebar() {

		register_sidebar( array(
			'name' => esc_html__( 'Blog Sidebar', 'ziomm' ),
			'id' => 'blog_sidebar',
			'description' => esc_html__( 'Blog Widget Area', 'ziomm' ),
			'before_widget' => '<div id="%1$s" class="vlt-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="vlt-widget__title">',
			'after_title' => '</h5>'
		) );

		register_sidebar( array(
			'name' => esc_html__( 'Offcanvas Sidebar', 'ziomm' ),
			'id' => 'offcanvas_sidebar',
			'description' => esc_html__( 'Offcanvas Widget Area', 'ziomm' ),
			'before_widget' => '<div id="%1$s" class="vlt-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h5 class="vlt-widget__title">',
			'after_title' => '</h5>'
		) );

		// Shop Sidebar
		if ( ZIOMM_WOOCOMMERCE ) {
			register_sidebar( array(
				'name' => esc_html__( 'Shop Sidebar', 'ziomm' ),
				'id' => 'shop_sidebar',
				'description' => esc_html__( 'Shop Widget Area', 'ziomm' ),
				'before_widget' => '<div id="%1$s" class="vlt-widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h5 class="vlt-widget__title">',
				'after_title' => '</h5>'
			) );
		}

		// Custom Sidebars
		if ( ziomm_get_theme_mod( 'custom_sidebars' ) ) {
			foreach ( ziomm_get_theme_mod( 'custom_sidebars' ) as $sidebar ) {
				if ( ! empty( $sidebar[ 'sidebar_title' ] && ! empty( $sidebar[ 'sidebar_description' ] ) ) ) {
					register_sidebar( array(
						'name' => esc_html( $sidebar[ 'sidebar_title' ] ),
						'id' => strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '_', $sidebar[ 'sidebar_title' ] ) ) ),
						'description' => esc_html( $sidebar[ 'sidebar_description' ] ),
						'before_widget' => '<div id="%1$s" class="vlt-widget %2$s">',
						'after_widget' => '</div>',
						'before_title' => '<h5 class="vlt-widget__title">',
						'after_title' => '</h5>'
					) );
				}
			}
		}

	}
}
add_action( 'widgets_init', 'ziomm_register_sidebar' );

/**
 * Site protection
 */
if ( ! function_exists( 'ziomm_site_protection' ) ) {
	function ziomm_site_protection() {
		$acf_page_custom_site_protection = ziomm_get_theme_mod( 'page_custom_site_protection', true );
		if ( ziomm_get_theme_mod( 'site_protection', $acf_page_custom_site_protection ) == 'show' && ! current_user_can( 'administrator' ) ) :
			echo '<div class="vlt-site-protection"><div>';
			echo wp_kses( ziomm_get_theme_mod( 'site_protection_content' ), 'ziomm_site_protection' );
			echo '</div></div>';
		endif;
	}
}
add_action( 'wp_body_open', 'ziomm_site_protection' );

/**
 * Change admin logo
 */
if ( ! function_exists( 'ziomm_change_admin_logo' ) ) {
	function ziomm_change_admin_logo() {
		if ( ! ziomm_get_theme_mod( 'login_logo_image' ) ) {
			return;
		}
		$image_url = ziomm_get_theme_mod( 'login_logo_image' );
		$image_w = ziomm_get_theme_mod( 'login_logo_image_width' );
		$image_h = ziomm_get_theme_mod( 'login_logo_image_height' );
		echo '<style type="text/css">
			h1 a {
				background: transparent url(' . esc_url( $image_url ) . ') 50% 50% no-repeat !important;
				width:' . esc_attr( $image_w ) . '!important;
				height:' . esc_attr( $image_h ) . '!important;
				background-size: cover !important;
			}
		</style>';
	}
}
add_action( 'login_head', 'ziomm_change_admin_logo' );

/**
 * Prints Tracking code
 */
if ( ! function_exists( 'ziomm_print_tracking_code' ) ) {
	function ziomm_print_tracking_code() {
		$tracking_code = ziomm_get_theme_mod( 'tracking_code' );
		if ( ! empty( $tracking_code ) ) {
			echo '' . $tracking_code;
		}
	}
}
add_action( 'wp_head', 'ziomm_print_tracking_code' );