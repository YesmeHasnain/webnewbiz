<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Xcency Admin Pages
 *
 */
if ( ! class_exists( 'Xcency_Admin' ) ) {

	class Xcency_Admin{
		private static $instance = null;

		public static function init() {
			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {

			add_action( 'init', array( $this, 'xcency_create_tgmpa_page' ), 1 );
			add_action( 'admin_menu', array( $this, 'xcency_create_admin_page' ), 1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'xcency_admin_page_enqueue_scripts' ) );

			add_filter( 'ocdi/plugin_page_setup', array( $this, 'xcency_pt_ocdi_page_setup' ) );

		}

		public function xcency_create_admin_page() {
			add_menu_page( esc_html__( 'Xcency', 'xcency' ), esc_html__( 'Xcency', 'xcency' ), 'manage_options', 'xcency', array( $this, 'xcency_admin_page_dashboard' ), 'dashicons-screenoptions', 2 );
			add_submenu_page( 'xcency', esc_html__( 'Welcome', 'xcency' ), esc_html__( 'Welcome & Support', 'xcency' ), 'manage_options', 'xcency', array( $this, 'xcency_admin_page_dashboard' ) );
		}

		public function xcency_admin_page_dashboard() {
			require_once XCENCY_INC_DIR .'admin/page-dashboard.php';
		}

		public function xcency_create_tgmpa_page() {
			require_once XCENCY_INC_DIR .'admin/class-tgm-plugin-activation.php';
			require_once XCENCY_INC_DIR .'admin/page-tgmpa.php';
		}

		public function xcency_admin_page_enqueue_scripts() {
			wp_enqueue_style( 'xcency-admin', get_theme_file_uri( 'inc/admin/assets/css/admin.css' ), array(), XCENCY_VERSION, 'all' );
		}

		public function xcency_pt_ocdi_page_setup( $args ) {

			$args['parent_slug'] = 'xcency';
			$args['menu_slug']   = 'xcency-import-demo';
			$args['menu_title']  = esc_html__( 'Import Demo', 'xcency' );
			$args['capability']  = 'manage_options';

			return $args;

		}

	}

	Xcency_Admin::init();
}