<?php
namespace PranaYogaSpace\Kits\Settings\Single;

use PranaYogaSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use PranaYogaSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Single settings.
 */
class Single extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'single';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Single', 'prana-yoga' );
	}

	/**
	 * Get control ID prefix.
	 *
	 * Retrieve the control ID prefix.
	 *
	 * @return string Control ID prefix.
	 */
	protected static function get_control_id_prefix() {
		$toggle_name = self::get_toggle_name();

		return parent::get_control_id_prefix() . "_{$toggle_name}";
	}

	/**
	 * Register toggle controls.
	 *
	 * Registers the controls of the kit settings tab toggle.
	 */
	protected function register_toggle_controls() {
		$this->add_control(
			'notice',
			array(
				'raw' => esc_html__( "If you use an 'Singular' template, then the settings will not be applied, if you set the template to 'All Singular', then these settings will be hidden.", 'prana-yoga' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label' => esc_html__( 'Layout', 'prana-yoga' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
				'type' => CmsmastersControls::CHOOSE_TEXT,
				'options' => array(
					'l-sidebar' => array(
						'title' => esc_html__( 'Left', 'prana-yoga' ),
						'description' => esc_html__( 'Left Sidebar', 'prana-yoga' ),
					),
					'fullwidth' => array(
						'title' => esc_html__( 'Full', 'prana-yoga' ),
						'description' => esc_html__( 'Full Width', 'prana-yoga' ),
					),
					'r-sidebar' => array(
						'title' => esc_html__( 'Right', 'prana-yoga' ),
						'description' => esc_html__( 'Right Sidebar', 'prana-yoga' ),
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'layout' ),
					'r-sidebar'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'elements_heading_control',
			array(
				'label' => esc_html__( 'Elements Order', 'prana-yoga' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'elements',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'media' => esc_html__( 'Media', 'prana-yoga' ),
					'title' => esc_html__( 'Title', 'prana-yoga' ),
					'meta_first' => esc_html__( 'Meta Data 1', 'prana-yoga' ),
					'meta_second' => esc_html__( 'Meta Data 2', 'prana-yoga' ),
					'content' => esc_html__( 'Content', 'prana-yoga' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'elements' ),
					array(
						'media',
						'title',
						'meta_first',
						'content',
						'meta_second',
					)
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'heading_visibility',
			array(
				'label' => esc_html__( 'Heading Visibility', 'prana-yoga' ),
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'prana-yoga' ),
				'label_on' => esc_html__( 'Show', 'prana-yoga' ),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'heading_visibility' ),
					'yes'
				),
			)
		);

		$this->add_control(
			'blocks_heading_control',
			array(
				'label' => esc_html__( 'Blocks Order', 'prana-yoga' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'blocks',
			array(
				'label_block' => true,
				'show_label' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
				'type' => CmsmastersControls::SELECTIZE,
				'options' => array(
					'nav' => esc_html__( 'Posts Navigation', 'prana-yoga' ),
					'author' => esc_html__( 'Author Box', 'prana-yoga' ),
					'more_posts' => esc_html__( 'More Posts', 'prana-yoga' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'blocks' ),
					array(
						'nav',
						'author',
						'more_posts',
					)
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'apply_settings',
			array(
				'label_block' => true,
				'show_label' => false,
				'type' => Controls_Manager::BUTTON,
				'text' => esc_html__( 'Save & Reload', 'prana-yoga' ),
				'event' => 'cmsmasters:theme_settings:apply_settings',
				'separator' => 'before',
			)
		);
	}

}
