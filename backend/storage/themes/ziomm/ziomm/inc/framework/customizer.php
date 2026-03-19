<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
* Add config
*/
VLT_Options::add_config( array(
	'capability' => 'edit_theme_options',
	'option_type' => 'theme_mod',
) );

$first_level = 10;
$second_level = 10;

/**
 * General
 */
VLT_Options::add_panel( 'panel_core', array(
	'title' => esc_html__( 'Core Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-admin-generic',
) );

VLT_Options::add_section( 'core_general', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'General Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-admin-generic',
) );

VLT_Options::add_section( 'core_site_protection', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'Site Protection', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-shield',
) );

VLT_Options::add_section( 'core_selection', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'Selection', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-editor-underline',
) );

VLT_Options::add_section( 'core_scrollbar', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'Scrollbar', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-sort',
) );

VLT_Options::add_section( 'core_sidebars', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'Custom Sidebars', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-format-aside',
) );

VLT_Options::add_section( 'core_login_logo', array(
	'panel' => 'panel_core',
	'title' => esc_html__( 'Login Page', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-lock',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-core.php';

/**
 * Header
 */
VLT_Options::add_panel( 'panel_header', array(
	'title' => esc_html__( 'Header Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-arrow-up-alt',
) );

VLT_Options::add_section( 'section_header_general', array(
	'panel' => 'panel_header',
	'title' => esc_html__( 'Header General', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-admin-generic',
) );

VLT_Options::add_section( 'section_header_fullscreen', array(
	'panel' => 'panel_header',
	'title' => esc_html__( 'Header Fullscreen', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-arrow-right',
) );

VLT_Options::add_section( 'section_header_slide', array(
	'panel' => 'panel_header',
	'title' => esc_html__( 'Header Slide', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-arrow-right',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-header.php';

/**
 * Footer
 */
VLT_Options::add_section( 'section_footer_general', array(
	'title' => esc_html__( 'Footer Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-arrow-down-alt',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-footer.php';

/**
 * Pages
 */
VLT_Options::add_panel( 'panel_page', array(
	'title' => esc_html__( 'Page Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-admin-page',
) );

VLT_Options::add_section( 'section_blog_general', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'General Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-admin-generic',
) );

VLT_Options::add_section( 'section_blog', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'Blog Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-admin-post',
) );

VLT_Options::add_section( 'section_archive', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'Archive Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-category',
) );

VLT_Options::add_section( 'section_search', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'Search Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-search',
) );

VLT_Options::add_section( 'section_single_post', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'Single Post', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-welcome-write-blog',
) );

VLT_Options::add_section( 'section_404', array(
	'panel' => 'panel_page',
	'title' => esc_html__( 'Page 404', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-warning',
) );

require ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-pages.php';

/**
 * Portfolio
 */
VLT_Options::add_section( 'section_single_portfolio', array(
	'panel' => '',
	'title' => esc_html__( 'Portfolio Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-grid-view',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-portfolio.php';

/**
 * WooCommerce
 */
if ( ZIOMM_WOOCOMMERCE ) {

	VLT_Options::add_section( 'section_shop_general', array(
		'panel' => 'woocommerce',
		'title' => esc_html__( 'Shop General', 'ziomm' ),
		'priority' => $second_level++,
	) );

	VLT_Options::add_section( 'section_single_product', array(
		'panel' => 'woocommerce',
		'title' => esc_html__( 'Single Product', 'ziomm' ),
		'priority' => $second_level++,
	) );

	require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-woocommerce.php';

}

/**
 * Typography
 */
VLT_Options::add_panel( 'panel_typography', array(
	'title' => esc_html__( 'Typography Options', 'ziomm' ),
	'priority' => $first_level++,
	'icon' => 'dashicons-editor-bold',
) );

VLT_Options::add_section( 'typography_fonts', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'General Fonts', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-editor-bold',
) );

VLT_Options::add_section( 'typography_text', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Text Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-text',
) );

VLT_Options::add_section( 'typography_headings', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Heading Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-editor-textcolor',
) );

VLT_Options::add_section( 'typography_blockquote', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Blockquote Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-editor-quote',
) );

VLT_Options::add_section( 'typography_buttons', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Button Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-admin-links',
) );

VLT_Options::add_section( 'typography_input', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Input Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-edit',
) );

VLT_Options::add_section( 'typography_widget', array(
	'panel' => 'panel_typography',
	'title' => esc_html__( 'Widget Options', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-welcome-widgets-menus',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-typography.php';

/**
 * Google map
 */
VLT_Options::add_section( 'section_google_map', array(
	'title' => esc_html__( 'Google Map', 'ziomm' ),
	'priority' => $second_level++,
	'icon' => 'dashicons-location',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-google-map.php';

/**
 * Advanced
 */
VLT_Options::add_section( 'section_advanced', array(
	'title' => esc_html__( 'Advanced', 'ziomm' ),
	'priority' => 9999,
	'icon' => 'dashicons-star-filled',
) );

require_once ZIOMM_REQUIRE_DIRECTORY . 'inc/framework/setup/section-advanced.php';