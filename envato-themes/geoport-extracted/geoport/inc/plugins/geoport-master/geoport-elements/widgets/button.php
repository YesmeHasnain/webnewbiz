<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Geoport_Button extends Widget_Base {

	public function get_name() {
		return 'geoport-button';
	}

	public function get_title() {
		return 'Geoport Button';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-link';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
	}

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/

	protected function register_controls() {
		
		//start of a control box
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Geoport Button Setting', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button text', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Apply Here',
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'geoport' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'geoport' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Button icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'btn_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'geoport' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'geoport' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'geoport' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'geoport' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);
		
		$this->end_controls_section();

		//End  of a control box

		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} a.geoport-btn',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} a.geoport-btn',
			]
		);
		$this->add_responsive_control(
			'geoport_btn_margin',
			[
				'label' => __( 'Margin', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'geoport_btn_padding',
			[
				'label' => __( 'Padding', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'btn_font_color',
			[
				'label' => __( 'Font color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_bg_color',
			[
				'label' => __( 'Background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} a.geoport-btn',
			]
		);
		$this->add_control(
			'btn_border_radius',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', 'geoport' ),
				'selector' => '{{WRAPPER}} a.geoport-btn',
			]
		);

		$this->end_controls_tab();

		/* = Button Hover Styling
		========================================*/
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'geoport' ),
			]
		);
		$this->add_control(
			'hover_heading',
			[
				'label' => __( 'Hover State', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'btn_h_font_color',
			[
				'label' => __( 'Font color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_h_bg_color',
			[
				'label' => __( 'Background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.geoport-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'h_border',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} a.geoport-btn:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'hover_box_shadow',
				'label' => __( 'Hover Box Shadow', 'geoport' ),
				'selector' => '{{WRAPPER}} a.geoport-btn:hover',
			]
		);
		

	}
	//end of control box 

	protected function render() {	//to show on the fontend  
		
		$settings = $this->get_settings_for_display();  

		$this->add_render_attribute( 'wrapper', 'class', 'geoport-button-wrapper' );
		$button_text = $settings['button_text'];
		$button_link = $settings['button_link']['url'];
		$button_icon = $settings['button_icon'];

		$this->add_inline_editing_attributes( 'button_text' );

		$target   = $settings['button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['button_link']['nofollow'] ? ' rel="nofollow"' : '';
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
		<?php 
		echo '<a href="' . $button_link . '"' . $target . $nofollow . ' class="geoport-btn btn"><i class="'.esc_attr( $button_icon ).'">
			</i>'.esc_html( $button_text ).'</a>';
		?>
		</div>
	<?php
	}	
}