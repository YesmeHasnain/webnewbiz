<?php
namespace PranaYogaSpace\Kits\Settings\Footer;

use PranaYogaSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use PranaYogaSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Footer settings.
 */
class Footer extends Settings_Tab_Base {

	/**
	 * Get toggle name.
	 *
	 * Retrieve the toggle name.
	 *
	 * @return string Toggle name.
	 */
	public static function get_toggle_name() {
		return 'footer';
	}

	/**
	 * Get title.
	 *
	 * Retrieve the toggle title.
	 */
	public function get_title() {
		return esc_html__( 'Footer', 'prana-yoga' );
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
				'raw' => esc_html__( "If you use a 'Footer' template, then the settings will not be applied, if you set the template to sitewide, then these settings will be hidden.", 'prana-yoga' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type' => 'ui',
			)
		);

		$this->add_control(
			'type',
			array(
				'label' => esc_html__( 'Type', 'prana-yoga' ),
				'label_block' => false,
				'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'horizontal' => array(
						'title' => esc_html__( 'Horizontal', 'prana-yoga' ),
						'icon' => 'eicon-ellipsis-h',
					),
					'vertical' => array(
						'title' => esc_html__( 'Vertical', 'prana-yoga' ),
						'icon' => 'eicon-ellipsis-v',
					),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'type' ),
					'horizontal'
				),
				'toggle' => false,
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label' => esc_html__( 'Alignment', 'prana-yoga' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'center' => array(
						'title' => esc_html__( 'Centered', 'prana-yoga' ),
						'icon' => 'eicon-text-align-center',
					),
					'space-between' => array(
						'title' => esc_html__( 'Justified', 'prana-yoga' ),
						'icon' => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'alignment' ) . ': {{VALUE}};',
				),
				'toggle' => true,
				'condition' => array( $this->get_control_id_parameter( '', 'type' ) => 'horizontal' ),
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
					'copyright' => esc_html__( 'Copyright', 'prana-yoga' ),
					'nav' => esc_html__( 'Navigation', 'prana-yoga' ),
					'social' => esc_html__( 'Social Icons', 'prana-yoga' ),
					'info' => esc_html__( 'Short Info', 'prana-yoga' ),
					'logo' => esc_html__( 'Logo', 'prana-yoga' ),
					'html' => esc_html__( 'Custom HTML', 'prana-yoga' ),
				),
				'default' => $this->get_default_setting(
					$this->get_control_name_parameter( '', 'elements' ),
					array( 'copyright' )
				),
				'multiple' => true,
			)
		);

		$this->add_responsive_control(
			'elements_gap',
			array(
				'label' => esc_html__( 'Gap Between', 'prana-yoga' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'size_units' => array(
					'px',
					'%',
					'vw',
				),
				'selectors' => array(
					':root' => '--' . $this->get_control_prefix_parameter( '', 'elements_gap' ) . ': {{SIZE}}{{UNIT}};',
				),
				'condition' => array( $this->get_control_id_parameter( '', 'elements!' ) => '' ),
			)
		);

		$this->add_controls_group( 'container', self::CONTROLS_CONTAINER );

		$this->add_controls_group( 'content', self::CONTROLS_CONTENT );

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
