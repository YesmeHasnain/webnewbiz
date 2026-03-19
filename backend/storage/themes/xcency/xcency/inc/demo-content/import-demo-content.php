<?php
/**
 * Import demo data.
 */
// Disable regenerating images while importing media
add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
add_filter( 'ocdi/disable_pt_branding', '__return_true' );

function xcency_ocdi_confirmation_dialog_options ( $options ) {
	return array_merge( $options, array(
		'width'       => 400,
		'dialogClass' => 'wp-dialog',
		'resizable'   => false,
		'height'      => 'auto',
		'modal'       => true,
	) );
}
add_filter( 'ocdi/confirmation_dialog_options', 'xcency_ocdi_confirmation_dialog_options', 10, 1 );

function xcency_import_demo_files() {
	$demos = array(
		array(
			'name' => 'Home One',
			'image' => 'demo-1.jpg',
			'preview' => 'https://xcency.quintexbd.com/',
		),
		array(
			'name' => 'Home Two',
			'image' => 'demo-2.jpg',
			'preview' => 'https://xcency.quintexbd.com/home-two/',
		),
		array(
			'name' => 'Home Three',
			'image' => 'demo-3.jpg',
			'preview' => 'https://xcency.quintexbd.com/home-three/',
		),
	);

	$demo_files = array();

	foreach ($demos as $demo) {
		$demo_files[] = array(
			'import_file_name'            => $demo['name'],
			'local_import_file'           => trailingslashit(get_template_directory()) . '/inc/demo-content/content.xml',
			'local_import_widget_file'    => trailingslashit(get_template_directory()) . '/inc/demo-content/widgets.wie',
			'local_import_customizer_file'=> trailingslashit(get_template_directory()) . '/inc/demo-content/customizer.dat',
			'local_import_json'           => array(
				array(
					'file_path'   => trailingslashit(get_template_directory()) . '/inc/demo-content/theme-option.json',
					'option_name' => 'xcency_theme_options',
				),
			),
			'import_preview_image_url'    => trailingslashit(get_template_directory_uri()) . '/inc/demo-content/images/' . $demo['image'],
			'import_notice'               => esc_html__('Please click on the Import button only once and wait, it can take a couple of minutes.', 'xcency'),
			'preview_url'                 => $demo['preview'],
		);
	}

	return $demo_files;
}

add_filter('ocdi/import_files', 'xcency_import_demo_files');

/**
 * Adding local_import_json and import_json param supports.
 */
if (!function_exists('xcency_after_content_import_execution')) {
	function xcency_after_content_import_execution($selected_import_files, $import_files, $selected_index) {

		$downloader = new OCDI\Downloader();

		if (!empty($import_files[$selected_index]['import_json'])) {

			foreach ( $import_files[$selected_index]['import_json'] as $index => $import ) {
				$file_path = $downloader->download_file($import['file_url'], 'demo-import-file-' . $index . '-' . date('Y-m-d__H-i-s') . '.json');
				$file_raw  = OCDI\Helpers::data_from_file($file_path);
				update_option($import['option_name'], json_decode($file_raw, true));
			}

		} else if (!empty($import_files[$selected_index]['local_import_json'])) {

			foreach ( $import_files[$selected_index]['local_import_json'] as $index => $import ) {
				$file_path = $import['file_path'];
				$file_raw  = OCDI\Helpers::data_from_file($file_path);
				update_option($import['option_name'], json_decode($file_raw, true));
			}
		}

	}

	add_action('ocdi/after_content_import_execution', 'xcency_after_content_import_execution', 3, 99);
}



/* After Import */
if (!function_exists('xcency_after_import')) {
	function xcency_after_import($selected_import) {

		// Set Menu
		$main_menu = get_term_by('name', esc_html__('Xcency Main Menu', 'xcency'), 'nav_menu');
		set_theme_mod('nav_menu_locations', array(
			'main-menu' => $main_menu->term_id,
		));

		// Map import names to home page titles
		$home_pages = array(
			'Home One'          => 'Home One',
			'Home Two'          => 'Home Two',
			'Home Three'        => 'Home Three',
		);

		// Determine the home page based on import file name
		$import_name = $selected_import['import_file_name'];
		$home_page_title = isset($home_pages[$import_name]) ? $home_pages[$import_name] : 'Home One';
		$home_page = get_page_by_title(esc_html($home_page_title));

		// Set Front page
		if (isset($home_page->ID)) {
			update_option('page_on_front', $home_page->ID);
			update_option('show_on_front', 'page');
		}

		// Set Post Page
		$blog_page = get_page_by_title(esc_html__('Blog', 'xcency'));
		if (isset($blog_page->ID)) {
			update_option('page_for_posts', $blog_page->ID);
		}
	}
	add_action('ocdi/after_import', 'xcency_after_import');
}