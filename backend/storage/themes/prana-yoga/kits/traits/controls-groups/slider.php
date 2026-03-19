<?php
namespace PranaYogaSpace\Kits\Traits\ControlsGroups;

use PranaYogaSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use PranaYogaSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Slider trait.
 *
 * Allows to use a group of controls for slider.
 */
trait Slider {

	/**
	 * Group of controls for slider.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_slider( $key = '', $args = array() ) {
		list(
			$columns_available,
			$condition,
			$conditions
		) = $this->get_controls_group_required_args( $args, array(
			'columns_available' => true, // Enable columns controls
			'condition' => array(), // Controls condition
			'conditions' => array(), // Controls conditions
		) );

		$default_args = array(
			'condition' => $condition,
			'conditions' => $conditions,
		);

		if ( $columns_available ) {
			$this->add_responsive_control(
				$this->get_control_name_parameter( $key, 'slides_per_view' ),
				array_merge_recursive(
					$default_args,
					array(
						'label' => esc_html__( 'Slides Per View', 'prana-yoga' ),
						'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 5,
						'step' => 1,
						'default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'slides_per_view' ),
							4
						),
						'tablet_default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'slides_per_view_tablet' ),
							2
						),
						'mobile_default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'slides_per_view_mobile' ),
							1
						),
					)
				)
			);

			$this->add_responsive_control(
				$this->get_control_name_parameter( $key, 'slides_to_scroll' ),
				array_merge_recursive(
					$default_args,
					array(
						'label' => esc_html__( 'Slides to Scroll', 'prana-yoga' ),
						'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'prana-yoga' ) . '<br />' . esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 5,
						'step' => 1,
						'default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'slides_to_scroll' ),
							1
						),
						'condition' => array(
							$this->get_control_id_parameter( $key, 'slides_per_view!' ) => 1,
						),
					)
				)
			);

			$this->add_responsive_control(
				$this->get_control_name_parameter( $key, 'space_between' ),
				array_merge_recursive(
					$default_args,
					array(
						'label' => esc_html__( 'Space Between', 'prana-yoga' ),
						'description' => esc_html__( 'Distance between slides in px.', 'prana-yoga' ) . '<br />' . esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
						'type' => Controls_Manager::NUMBER,
						'min' => 0,
						'max' => 100,
						'step' => 1,
						'default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'space_between' ),
							0
						),
						'tablet_default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'space_between_tablet' ),
							0
						),
						'mobile_default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'space_between_mobile' ),
							0
						),
					)
				)
			);
		}

		$this->add_control(
			$this->get_control_name_parameter( $key, 'autoplay' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Autoplay', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'autoplay' ),
						'no'
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'autoplay_speed' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Autoplay Speed', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 500,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'autoplay_speed' ),
						5000
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'autoplay' ) => 'yes',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'animation_speed' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Animation Speed', 'prana-yoga' ) . ' (ms)',
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::NUMBER,
					'step' => 100,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'animation_speed' ),
						500
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'pause_on_hover' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Pause On Hover', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'pause_on_hover' ),
						'no'
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'autoplay' ) => 'yes',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'autoplay_reverse' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Autoplay Reverse', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'render_type' => 'none',
					'frontend_available' => true,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'autoplay_reverse' ),
						'no'
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'autoplay' ) => 'yes',
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'infinite' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Infinite Loop', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'infinite' ),
						'no'
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'mousewheel' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Mousewheel Control', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'mousewheel' ),
						'no'
					),
				)
			)
		);

		if ( $columns_available ) {
			$this->add_control(
				$this->get_control_name_parameter( $key, 'centered_slides' ),
				array_merge_recursive(
					$default_args,
					array(
						'label' => esc_html__( 'Centered Slides', 'prana-yoga' ),
						'description' => esc_html__( 'Turn on for a slider with an even number of slides only.', 'prana-yoga' ) . '<br />' . esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => $this->get_default_setting(
							$this->get_control_name_parameter( $key, 'centered_slides' ),
							'no'
						),
						'condition' => array(
							$this->get_control_id_parameter( $key, 'slides_per_view!' ) => 1,
						),
					)
				)
			);
		}

		$this->add_control(
			$this->get_control_name_parameter( $key, 'free_mode' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Free Mode / No Fixed Positions', 'prana-yoga' ),
					'description' => esc_html__( 'If enable then slides will not have fixed positions.', 'prana-yoga' ) . '<br />' . esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'free_mode' ),
						'no'
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'arrows' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Arrows', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => Controls_Manager::SWITCHER,
					'label_off' => esc_html__( 'Hide', 'prana-yoga' ),
					'label_on' => esc_html__( 'Show', 'prana-yoga' ),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'arrows' ),
						'no'
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'navigation' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Navigation', 'prana-yoga' ),
					'description' => esc_html__( 'This setting will be applied after save and reload.', 'prana-yoga' ),
					'type' => CmsmastersControls::CHOOSE_TEXT,
					'options' => array(
						'none' => esc_html__( 'None', 'prana-yoga' ),
						'bullets' => esc_html__( 'Bullets', 'prana-yoga' ),
						'progressbar' => esc_html__( 'Progress', 'prana-yoga' ),
						'fraction' => esc_html__( 'Fraction', 'prana-yoga' ),
					),
					'default' => $this->get_default_setting(
						$this->get_control_name_parameter( $key, 'navigation' ),
						'bullets'
					),
				)
			)
		);
	}

}
