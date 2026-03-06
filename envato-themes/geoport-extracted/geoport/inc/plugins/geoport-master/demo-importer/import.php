<?php
/*------------------------------------------------------------------------------------------------------------------*/
/* Geoport Demo Import
/*------------------------------------------------------------------------------------------------------------------*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class JohanspondDemoimport {

	public function __construct(){
		// Action Hook
		add_action( 'pt-ocdi/after_content_import_execution', array( $this, 'johanspond_theme_options_import' ), 3, 99 );
		add_action( 'pt-ocdi/after_import', array( $this, 'johanspond_import_menu_setup' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'johanspond_import_page_setup' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'johanspond_import_page_setup' ) );
		add_action( 'pt-ocdi/after_import', array( $this, 'johanspond_rev_slider_import_setup' ) );

		// Filter Hook
		add_filter( 'pt-ocdi/import_files', array( $this, 'johanspond_import_files' ) );
		add_filter( 'pt-ocdi/plugin_page_setup', array( $this, 'johanspond_oneclick_admin_page' ) );
		add_filter( 'pt-ocdi/plugin_intro_text', array( $this, 'johanspond_ocdi_plugin_intro_text' ) );
		add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
		add_filter( 'pt-ocdi/confirmation_dialog_options', array( $this, 'geoport_ocdi_confirmation_dialog_options' ), 10, 1 );
	}

	/**
	* Demo containes file loading methos
	*/
	public function johanspond_import_files() {
		return array(
	        //Home 1 Setup
			array(
				'import_file_name'             => 'Home 1 Demo',
				'categories'                   => array( 'Home 1', 'Home 1' ),
				'local_import_file'            => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/demo-content.xml',
				'local_import_widget_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/widgets.wie',
				'local_import_customizer_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/geoport-export.dat',
				'local_import_csf'           => array(
					array(
						'file_path'   => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/options.txt',
						'option_name' => '_geoport_options',
					),
				),
				'import_preview_image_url'     => GEOPORT_PLG_DEMO_URL . 'img/01.home.jpg',
				'preview_url'                  => 'http://pluginspoint.com/geoport/',
				'import_notice'                => __( 'Are you sure? you want to setup home 1 as a front page ?', 'geoport' ),
			),
			//Home 2 Setup
			array(
				'import_file_name'             => 'Home 2 Demo',
				'categories'                   => array( 'Home 2', 'Home 2' ),
				'local_import_file'            => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/demo-content.xml',
				'local_import_widget_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/widgets.wie',
				'local_import_customizer_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/geoport-export.dat',
				'local_import_csf'           => array(
					array(
						'file_path'   => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/options.txt',
						'option_name' => '_geoport_options',
					),
				),
				'import_preview_image_url'     => GEOPORT_PLG_DEMO_URL . 'img/02.home.jpg',
				'preview_url'                  => 'http://pluginspoint.com/geoport/home-2/',
				'import_notice'                => __( 'Are you sure? you want to setup home 2 as a front page ?', 'geoport' ),
			),
			//Home 3 Setup
			array(
				'import_file_name'             => 'Home 3 Demo',
				'categories'                   => array( 'Home 3', 'Home 3' ),
				'local_import_file'            => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/demo-content.xml',
				'local_import_widget_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/widgets.wie',
				'local_import_customizer_file'     => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/geoport-export.dat',
				'local_import_csf'           => array(
					array(
						'file_url'   => trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/options.txt',
						'option_name' => '_geoport_options',
					),
				),
				'import_preview_image_url'     => GEOPORT_PLG_DEMO_URL . 'img/03.home.jpg',
				'preview_url'                  => 'http://pluginspoint.com/geoport/home-3/',
				'import_notice'                => __( 'Are you sure? you want to setup home 3 as a front page ?', 'geoport' ),
			),
		);
	}


	/**
	* Assign CodeStar Framework import data
	*/
	public function johanspond_theme_options_import( $selected_import_files, $import_files, $selected_index ) {

		if ( !class_exists( 'GEOPORTFramework' ) ) {
			return;
		}

		function cs_decode_string( $string ) {
			return unserialize( $string );
		}

		$downloader = new OCDI\Downloader();

		if( ! empty( $import_files[$selected_index]['local_import_csf'] ) ) {

			foreach( $import_files[$selected_index]['local_import_csf'] as $index => $import ) {
				$file_path = $import['file_path'];
				$file_raw  = OCDI\Helpers::data_from_file( $file_path );
				update_option( $import['option_name'], cs_decode_string( $file_raw, true ) );
			}
		}
	    // Put info to log file.
		$ocdi       = OCDI\OneClickDemoImport::get_instance();
		$log_path   = $ocdi->get_log_file_path();

		OCDI\Helpers::append_to_file( 'Codestar Framework files loaded.'. $logs, $log_path );
	}


	/**
	* Assign menus to their locations.
	*/
	public function johanspond_import_menu_setup( $selected_import ) {
		$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

		set_theme_mod( 'nav_menu_locations', array(
				'primary' => $main_menu->term_id,
			)
		);
	}


	/**
	* Assign front page and posts page (blog page).
	*/
	public function johanspond_import_page_setup( $selected_import ) {

	    // Assign front page and posts page (blog page).
		if ( 'Home 1 Demo' === $selected_import['import_file_name'] ) {
			$front_page_id = get_page_by_title( 'Home 1' );
		} elseif ( 'Home 2 Demo' === $selected_import['import_file_name'] ) {
			$front_page_id = get_page_by_title( 'Home 2' );
		} elseif ( 'Home 3 Demo' === $selected_import['import_file_name'] ) {
			$front_page_id = get_page_by_title( 'Home 3' );
		} else {
			$front_page_id = get_page_by_title( 'Home 1' );
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );

		$blog_page_id  = get_page_by_title( 'Blog' );
		update_option( 'page_for_posts', $blog_page_id->ID );

	}


	/**
	* Assign Revolution Slider Import.
	*/
	public function johanspond_rev_slider_import_setup( $selected_import ) {
		$slider_files = array(
			trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/banner.zip',
			trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/slider-1.zip',
			trailingslashit( GEOPORT_PLG_DEMO_PATH ) . 'demo/slider-2.zip'
		);
		if ( class_exists( 'RevSlider' ) && count( $slider_files ) > 0 ) {
			$slider = new RevSlider();
			foreach( $slider_files as $slider_file ){
				$slider->importSliderFromPost( true, true, $slider_file );
			}
		}
	}


	/**
	* Install Demos Menu - Menu Edited
	*/
	public function johanspond_oneclick_admin_page( $default_settings ) {
		$default_settings['parent_slug'] = 'themes.php';
		$default_settings['page_title']  = esc_html__( 'Install Demos', 'geoport' );
		$default_settings['menu_title']  = esc_html__( 'Install Demos', 'geoport' );
		$default_settings['capability']  = 'import';
		$default_settings['menu_slug']   = 'install_demos';
		return $default_settings;
	}


	// Model Popup - Width Increased
	public function geoport_ocdi_confirmation_dialog_options ( $options ) {
	  return array_merge( $options, array(
	    'width'       => 600,
	    'dialogClass' => 'wp-dialog',
	    'resizable'   => false,
	    'height'      => 'auto',
	    'modal'       => true,
	  ) );
	}


	public function johanspond_ocdi_plugin_intro_text( $default_text ) {
		$auto_install = admin_url('themes.php?page=install_demos');
		$manual_install = admin_url('themes.php?page=install_demos&import-mode=manual');
		$default_text .= '<h1>Install Demos</h1>
		<div class="geoport-core_intro-text vtdemo-one-click">
		<div id="poststuff">

		<div class="postbox important-notes">
		<h3><span>Important notes:</span></h3>
		<div class="inside">
		<ol>
		<li>Please note, this import process will take time. So, please be patient.</li>
		<li>Please make sure you\'ve installed recommended plugins before you import this content.</li>
		<li>All images are demo purposes only. So, images may repeat in your site content.</li>
		</ol>
		</div>
		</div>

		<div class="postbox vt-support-box vt-error-box">
		<h3><span>Don\'t Edit Parent Theme Files:</span></h3>
		<div class="inside">
		<p>Don\'t edit any files from parent theme! Use only a <strong>Child Theme</strong> files for your customizations!</p>
		<p>If you get future updates from our theme, you\'ll lose edited customization from your parent theme.</p>
		</div>
		</div>

		<div class="postbox vt-support-box">
		<h3><span>Need Support?</span> <a href="https://themeforest.net/user/johanspond" target="_blank" class="cs-section-video"><i class="fal fa-hand-point-right"></i> <span>How to?</span></a></h3>
		<div class="inside">
		<p>Have any doubts regarding this installation or any other issues? Please feel free to send us a mail to our support mail johanspond@gmail.com</p>
		<a href="https://themeforest.net/user/johanspond" class="button-primary" target="_blank">Docs</a>
		<a href="https://themeforest.net/user/johanspond/" class="button-primary" target="_blank">Support</a>
		<a href="https://themeforest.net/user/johanspond/" class="button-primary" target="_blank">Item Page</a>
		</div>
		</div>
		<div class="nav-tab-wrapper vt-nav-tab">
		<a href="'. $auto_install .'" class="nav-tab vt-mode-switch vt-auto-mode nav-tab-active">Auto Import</a>
		<a href="'. $manual_install .'" class="nav-tab vt-mode-switch vt-manual-mode">Manual Import</a>
		</div>

		</div>
		</div>';

		return $default_text;
	}

} //End Of Class

function geoport_demo_importer_init(){
	$johanspond_init = new JohanspondDemoimport(); //Initialization of class
}
add_action('plugins_loaded', 'geoport_demo_importer_init');