<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
 * ACF in Admin Panel
 */
if ( ! function_exists( 'ziomm_acf_in_admin_panel' ) ) {
	function ziomm_acf_in_admin_panel() {
		return ziomm_get_theme_mod( 'acf_show_admin_panel' ) == 'show' ? true : false;
	}
}
add_filter( 'ziomm/acf_show_admin_panel', 'ziomm_acf_in_admin_panel' );

/**
 * Add body class
 */
if ( ! function_exists( 'ziomm_add_body_class' ) ) {
	function ziomm_add_body_class( $classes ) {
		$classes[] = '';
		if ( ! wp_is_mobile() ) {
			$classes[] = 'no-mobile';
		} else {
			$classes[] = 'is-mobile';
		}

		// preloader
		if ( ziomm_get_theme_mod( 'preloader_style' ) !== 'none' ) {
			$classes[] = 'animsition';
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ziomm_add_body_class' );

/**
 * Add html class
 */
if ( ! function_exists( 'ziomm_add_html_class' ) ) {
	function ziomm_add_html_class( $classes = '' ) {

		// header
		$acf_header = ziomm_get_theme_mod( 'page_custom_navigation', true );
		$classes .= ' vlt-is--header-' . ziomm_get_theme_mod( 'navigation_type', $acf_header );

		// footer
		$acf_footer = ziomm_get_theme_mod( 'page_custom_footer', true );
		$classes .= ' vlt-is--footer-' . ziomm_get_theme_mod( 'footer_template', $acf_footer );
		if ( ziomm_get_theme_mod( 'footer_fixed', $acf_footer ) == 'enable' ) {
			$classes .= ' vlt-is--footer-fixed';
		}
		if ( ziomm_get_theme_mod( 'footer_shape', $acf_footer ) == 'disable' ) {
			$classes .= ' vlt-is--footer-shape-disable';
		}

		// site protection
		$acf_site_protection = ziomm_get_theme_mod( 'page_custom_site_protection', true );
		$classes .= ( ziomm_get_theme_mod( 'site_protection', $acf_site_protection ) == 'show' && ! current_user_can( 'administrator' ) ) ? ' vlt-is--site-protection' : '';

		return apply_filters( 'ziomm/add_html_class', ziomm_sanitize_class( $classes ) );

	}
}

/**
 * Get post password form
 */
if ( ! function_exists( 'ziomm_get_post_password_form' ) ) {
	function ziomm_get_post_password_form() {
		global $post;
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		$output = '<form class="vlt-post-password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
		$output .= '<h4>' . esc_html__( 'Password Protected', 'ziomm' ) . '</h4>';
		$output .= '<p>' . esc_html__( 'This content is restricted access, please type the password below and get access.', 'ziomm' ) . '</p>';
		$output .= '<div class="vlt-form-group m-0">';
		$output .= '<input name="post_password" id="' . $label . '" type="password" size="20" class="vlt-form-control">';
		$output .= '<label for="' . $label . '" class="vlt-form-label">' . esc_attr__( 'Password:' , 'ziomm' ) . '</label>';
		$output .= '<button><i class="icon-unlock"></i></button>';
		$output .= '</div>';
		$output .= '</form>';
		return apply_filters( 'ziomm/get_post_password_form', $output );
	}
}
add_filter( 'the_password_form', 'ziomm_get_post_password_form' );

/**
 * Admin logo link
 */
if ( ! function_exists( 'ziomm_change_admin_logo_link' ) ) {
	function ziomm_change_admin_logo_link() {
		return home_url( '/' );
	}
}
add_filter( 'login_headerurl', 'ziomm_change_admin_logo_link' );

/**
 * Comment reply custom class
 */
if ( ! function_exists( 'ziomm_comment_reply_link' ) ) {
	function ziomm_comment_reply_link( $content ) {
		$extra_classes = 'vlt-comment-reply';
		return preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $extra_classes, $content );
	}
}
add_filter( 'comment_reply_link', 'ziomm_comment_reply_link' );

/**
 * Remove comment notes
 */
add_filter( 'comment_form_logged_in', '__return_empty_string' );

/**
 * Add comma to tag widget
 */
if ( ! function_exists( 'ziomm_filter_tag_cloud' ) ) {
	function ziomm_filter_tag_cloud( $args ) {
		$args['smallest'] = 14;
		$args['largest'] = 14;
		$args['unit'] = 'px';
		$args['orderby'] = 'count';
		$args['order'] = 'DESC';
		$args['separator']= '';
		return $args;
	}
}
add_filter ( 'widget_tag_cloud_args', 'ziomm_filter_tag_cloud' );

/**
 * Function that adds classes on body for version of the theme
 */
if ( ! function_exists( 'ziomm_theme_version_class' ) ) {
	function ziomm_theme_version_class( $classes ) {
		$current_theme = wp_get_theme();
		$theme_prefix = 'vlt';
		// is child theme activated?
		if ( $current_theme->parent() ) {
			// add child theme version
			$classes[] = $theme_prefix . '-child-theme-version-' . $current_theme->get( 'Version' );
			// get parent theme
			$current_theme = $current_theme->parent();
		}
		if ( $current_theme->exists() && $current_theme->get( 'Version' ) != '' ) {
			$classes[] = $theme_prefix . '-theme-version-' . $current_theme->get( 'Version' );
			$classes[] = $theme_prefix . '-theme-' . strtolower( $current_theme->get( 'Name' ) );
		}
		return $classes;
	}
}
add_filter( 'body_class', 'ziomm_theme_version_class' );

/**
 * Custom select for ACF
 */
if ( ! function_exists( 'ziomm_add_custom_select_to_acf' ) ) {
	function ziomm_add_custom_select_to_acf( $field, $type = null ) {

		// reset choices
		$field['choices'] = [];

		$args = [
			'post_type' => 'elementor_library',
			'posts_per_page' => -1,
		];

		if ( $type ) {

			$args[ 'tax_query' ] = [
				[
					'taxonomy' => 'elementor_library_type',
					'field' => 'slug',
					'terms' => $type,
				],
			];

		}

		$page_templates = get_posts( $args );

		$field['choices'][0] = esc_html__( 'Select a Template', 'ziomm' );

		if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ) {
			foreach ( $page_templates as $post ) {
				$field['choices'][$post->ID] = $post->post_title;
			}
		} else {
			$field['choices'][0] = esc_html__( 'Create a Template First', 'ziomm' );
		}

		// return the field
		return $field;

	}
}
add_filter( 'acf/load_field/name=footer_template', 'ziomm_add_custom_select_to_acf' );

/**
 * Remove cf7 autop
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Comment form fields order
 */
if ( ! function_exists( 'ziomm_comment_form_fields' ) ) {
	function ziomm_comment_form_fields( $comment_fields ) {
		if ( ziomm_get_theme_mod( 'comment_placement' ) == 'bottom' ) {
			$keys = array_keys( $comment_fields );
			if ( 'comment' == $keys[0] ) {
				$comment_fields['comment'] = array_shift( $comment_fields );
			}
		}
		return $comment_fields;
	}
}
add_filter( 'comment_form_fields', 'ziomm_comment_form_fields' );

/**
 * Kses allowed html
 */
if ( ! function_exists( 'ziomm_kses_allowed_html' ) ) {
	function ziomm_kses_allowed_html($tags, $context) {
		switch( $context ) {
			case 'ziomm_site_protection':
				$tags = [
					'p' => []
				];
				return $tags;
			case 'ziomm_error_title':
				$tags = [
					'span' => [
						'class' => []
					]
				];
				return $tags;
			case 'ziomm_error_subtitle':
				$tags = [
					'br' => []
				];
				return $tags;
			case 'ziomm_product_image':
				$tags = [
					'img' => [
						'width' => [],
						'height' => [],
						'src' => [],
						'class' => [],
						'alt' => [],
						'loading' => []
					]
				];
				return $tags;
			default:
				return $tags;
		}
	}
}
add_filter( 'wp_kses_allowed_html', 'ziomm_kses_allowed_html', 10, 2 );

/**
 * Disabling the scaling
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Override Elementor template
 */
add_filter( 'template_include', function( $template ) {
	if ( false !== strpos( $template, '/templates/canvas.php' ) ) {
		$template = ZIOMM_REQUIRE_DIRECTORY . 'template-canvas-page.php';
	}
	return $template;
}, 12 );