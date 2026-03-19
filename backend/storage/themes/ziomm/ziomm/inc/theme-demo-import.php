<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

/**
 * Demo import
 */
if ( ! function_exists( 'ziomm_demo_import_files' ) ) {
	function ziomm_demo_import_files() {
		return array(
			array(
				'import_file_name' => esc_html__( 'Demo Import', 'ziomm' ),
				'local_import_file' => ZIOMM_REQUIRE_DIRECTORY . 'inc/demo/demo-content.xml',
				'local_import_widget_file' => ZIOMM_REQUIRE_DIRECTORY . 'inc/demo/widgets.wie',
				'local_import_customizer_file' => ZIOMM_REQUIRE_DIRECTORY . 'inc/demo/customizer.dat'
			),
		);
	}
}
add_filter( 'ocdi/import_files', 'ziomm_demo_import_files' );

/**
 * Disable regenerate thumbnails
 */
add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

/**
 * After setup function
 */
if ( ! function_exists( 'ziomm_after_import_setup' ) ) {
	function ziomm_after_import_setup() {

		global $wp_rewrite;

		// Set menus
		$primary_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		$contact_menu = get_term_by( 'name', 'Contact Menu', 'nav_menu' );
		$footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );


		set_theme_mod( 'nav_menu_locations', array(
			'primary-menu' => $primary_menu->term_id,
			'contact-menu' => $contact_menu->term_id,
			'footer-menu' => $footer_menu->term_id
		) );

		// Set pages
		$front_page = get_page_by_title( 'Home Creative Agency' );
		if ( isset( $front_page->ID ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		// Update option
		update_option( 'date_format', 'M j, Y' );

		// Update permalink
		$wp_rewrite->set_permalink_structure( '/%postname%/' );

		// Import Revolution Slider
		if ( class_exists( 'RevSlider' ) ) {

			$revo_slider = new RevSlider();

			$slider_aliases = $revo_slider->getAllSliderAliases();
			$slider_array = [
				'landing-page',
				'home-creative-agency'
			];

			foreach ( $slider_array as $slider ) {
				if ( ! in_array( $slider, $slider_aliases ) ) {
					$path = ZIOMM_REQUIRE_DIRECTORY . 'inc/demo/sliders/' . $slider . '.zip';
					if ( file_exists( $path ) ) {
						$revo_slider->importSliderFromPost( true, true, $path );
					}
				}
			}

		}

		// Set default vars for Elementor
		if( class_exists( '\Elementor\Plugin' ) ) {
			$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend();
			$kit->update_settings( [
				'container_width' => [
					'size' => '1200',
					'unit' => 'px'
				],
				'space_between_widgets' => [
					'column' => '0',
					'row' => '0',
					'unit' => 'px'
				],
				'global_image_lightbox' => '',
			] );
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// Set default options for Elementor
		$elementor_options = array(
			'elementor_experiment-container' => 'inactive',
			'elementor_experiment-container_grid' => 'inactive',
			'elementor_experiment-e_swiper_latest' => 'inactive',
			'elementor_experiment-e_optimized_css_loading' => 'inactive',
			'elementor_experiment-e_font_icon_svg' => 'inactive',
			'elementor_unfiltered_files_upload' => true,
			'elementor_disable_color_schemes' => 'yes',
			'elementor_disable_typography_schemes' => 'yes'
		);

		foreach( $elementor_options as $key => $value ) {
			update_option( $key, $value );
		}

		$cpt_support = get_option( 'elementor_cpt_support' );

		// Check if option DOESN'T exist in db
		if ( ! $cpt_support ) {
			$cpt_support = [ 'page', 'post', 'portfolio' ]; // create array of our default supported post types
			update_option( 'elementor_cpt_support', $cpt_support ); // write it to the database
		}

		// If it DOES exist, but portfolio is NOT defined
		else if ( ! in_array( 'portfolio', $cpt_support ) ) {
			$cpt_support[] = 'portfolio'; // append to array
			update_option( 'elementor_cpt_support', $cpt_support ); // update database
		}

	}
}
add_action( 'ocdi/after_import', 'ziomm_after_import_setup' );