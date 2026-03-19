<?php
namespace PranaYogaSpace\Kits\Traits\ControlsGroups;

use PranaYogaSpace\Kits\Controls\Controls_Manager as CmsmastersControls;
use PranaYogaSpace\Kits\Settings\Base\Settings_Tab_Base;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Navigation Item trait.
 *
 * Allows to use a group of controls for navigation item.
 */
trait Nav_Item {

	/**
	 * Group of controls for navigation item.
	 *
	 * @param string $key Controls key.
	 * @param array $args Controls args.
	 */
	protected function controls_group_nav_item( $key = '', $args = array() ) {
		list(
			$states,
			$condition,
			$conditions
		) = $this->get_controls_group_required_args( $args, array(
			'states' => array(), // Controls states
			'condition' => array(), // Controls condition
			'conditions' => array(), // Controls conditions
		) );

		$default_args = array(
			'condition' => $condition,
			'conditions' => $conditions,
		);

		$this->add_var_group_control(
			$this->get_control_name_parameter( $key ),
			Settings_Tab_Base::VAR_TYPOGRAPHY,
			$default_args
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'states_tabs_heading_control' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'States', 'prana-yoga' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			)
		);

		$this->start_controls_tabs(
			$this->get_control_name_parameter( $key, 'states_tabs' ),
			$default_args
		);

		foreach ( $states as $state_key => $state_label ) {
			$this->start_controls_tab(
				$this->get_control_name_parameter( $key, "states_{$state_key}_tab" ),
				array( 'label' => $state_label )
			);

			$this->add_control(
				$this->get_control_name_parameter( $key, "{$state_key}_colors_color" ),
				array(
					'label' => esc_html__( 'Color', 'prana-yoga' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, "{$state_key}_colors_color" ) . ': {{VALUE}};',
					),
				)
			);

			$this->add_control(
				$this->get_control_name_parameter( $key, "{$state_key}_colors_bg" ),
				array(
					'label' => esc_html__( 'Background', 'prana-yoga' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, "{$state_key}_colors_bg" ) . ': {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'padding' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => esc_html__( 'Padding', 'prana-yoga' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'em',
						'%',
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'padding_top' ) . ': {{TOP}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( $key, 'padding_right' ) . ': {{RIGHT}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( $key, 'padding_bottom' ) . ': {{BOTTOM}}{{UNIT}};' .
							'--' . $this->get_control_prefix_parameter( $key, 'padding_left' ) . ': {{LEFT}}{{UNIT}};',
					),
					'separator' => 'before',
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_type' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => _x( 'Divider Type', 'Divider Control', 'prana-yoga' ),
					'type' => Controls_Manager::SELECT,
					'options' => array(
						'' => _x( 'Default', 'Divider Control', 'prana-yoga' ),
						'none' => _x( 'None', 'Divider Control', 'prana-yoga' ),
						'solid' => _x( 'Solid', 'Divider Control', 'prana-yoga' ),
						'double' => _x( 'Double', 'Divider Control', 'prana-yoga' ),
						'dotted' => _x( 'Dotted', 'Divider Control', 'prana-yoga' ),
						'dashed' => _x( 'Dashed', 'Divider Control', 'prana-yoga' ),
						'groove' => _x( 'Groove', 'Divider Control', 'prana-yoga' ),
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_type' ) . ': {{VALUE}};',
					),
				)
			)
		);

		$this->add_responsive_control(
			$this->get_control_name_parameter( $key, 'divider_width' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => _x( 'Width', 'Divider Control', 'prana-yoga' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_width' ) . ': {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_type!' ) => array(
							'',
							'none',
						),
					),
				)
			)
		);

		$this->add_control(
			$this->get_control_name_parameter( $key, 'divider_color' ),
			array_merge_recursive(
				$default_args,
				array(
					'label' => _x( 'Color', 'Divider Control', 'prana-yoga' ),
					'type' => Controls_Manager::COLOR,
					'dynamic' => array(),
					'selectors' => array(
						':root' => '--' . $this->get_control_prefix_parameter( $key, 'divider_color' ) . ': {{VALUE}};',
					),
					'condition' => array(
						$this->get_control_id_parameter( $key, 'divider_type!' ) => 'none',
					),
				)
			)
		);
	}

}
