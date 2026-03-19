<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class OCDI_Demo_Importer {

	public function __construct() {
		add_filter( 'pt-ocdi/import_files',	array( $this, 'import_files_config' ) );
		add_filter( 'pt-ocdi/after_import',	array( $this, 'ocdi_after_import_setup' ) );
		add_filter( 'pt-ocdi/disable_pt_branding',	'__return_true' );
		add_action( 'init',	array( $this, 'bdevs_toolkit_rewrite_flush' ) );
	}


	public function import_files_config() {

		$home_prevs = array(
			'medinet_home_1' => array(
				'title' => __( 'Import Home Creative Agency', 'nixer' ),
				'page'  => __( 'Home Creative Agency', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/',
			),
			'medinet_home_2' => array(
				'title' => __( 'Import Home Digital Agency', 'nixer' ),
				'page'  => __( 'Home Digital Agency', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot2.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=111',
			),
			'medinet_home_3' => array(
				'title' => __( 'Import Home Creative Studio', 'nixer' ),
				'page'  => __( 'Home Creative Studio', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot3.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=113',
			),
			'medinet_home_4' => array(
				'title' => __( 'Import Home Personal Portfolio', 'nixer' ),
				'page'  => __( 'Home Personal Portfolio', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot4.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=115',
			),
			'medinet_home_5' => array(
				'title' => __( 'Import Home Law Firm Agency', 'nixer' ),
				'page'  => __( 'Home Law Firm Agency', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot5.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=117',
			),
			'medinet_home_6' => array(
				'title' => __( 'Import Home Creative Portfolio', 'nixer' ),
				'page'  => __( 'Home Creative Portfolio', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot6.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=119',
			),
			'medinet_home_7' => array(
				'title' => __( 'Import Home Interactive', 'nixer' ),
				'page'  => __( 'Home Interactive', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot7.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=121',
			),
			'medinet_home_8' => array(
				'title' => __( 'Import Home Horizontal', 'nixer' ),
				'page'  => __( 'Home Horizontal', 'nixer' ),
				'screenshot' => get_template_directory_uri() . '/sample-data/screenshot8.png',
				'preview_link' => 'https://shthemes.net/demosd/nixerwp/?page_id=123',
			),
		);

		$config = array();

		$import_path  = trailingslashit( get_template_directory() ) . 'sample-data/';

		foreach ( $home_prevs as $key => $prev ) {

			$contents_demo = $import_path . 'contents-demo.xml';
			$widget_settings = $import_path . 'widget-settings.json';
			$customizer_data = $import_path . 'customizer-data.dat';
			
			$config[] = array(
				'import_file_id'               => $key,
				'import_page_name'             => $prev['page'],
				'import_file_name'             => $prev['title'],
				'local_import_file'            => $contents_demo,
				'local_import_widget_file'     => $widget_settings,
				'local_import_customizer_file' => $customizer_data,
				'import_preview_image_url'   => $prev['screenshot'],
				'preview_url'                => $prev['preview_link'],
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'nixer' ),
			);
		}

		return $config;
	}

	public function ocdi_after_import_setup( $selected_file ) {

		$this->assign_menu_to_location();
		$this->assign_frontpage_id( $selected_file );
		$this->update_permalinks();
		update_option( 'basa_ocdi_importer_flash', true );
	}

	private function assign_menu_to_location() {

		$main_menu = get_term_by( 'name', 'Primary Menu.', 'primary' );

	    set_theme_mod( 'nav_menu_locations', array(
	            'primary' => $main_menu->term_id,
	        )
	    );
	}

	private function assign_frontpage_id( $selected_import ) {
		
		$front_page = get_page_by_title( $selected_import['import_page_name'] );
		$blog_page  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front',  $front_page->ID );
		update_option( 'page_for_posts', $blog_page->ID );
	}

	private function update_permalinks() {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	public function bdevs_toolkit_rewrite_flush() {
		
		if ( get_option( 'basa_ocdi_importer_flash' ) == true  ) {
			flush_rewrite_rules();
			delete_option( 'basa_ocdi_importer_flash' );
		}
	}
}

new OCDI_Demo_Importer;