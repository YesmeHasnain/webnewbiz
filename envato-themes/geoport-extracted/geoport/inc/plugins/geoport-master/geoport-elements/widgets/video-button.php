<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Video_Button extends Widget_Base {

	public function get_name() {
		return 'video-button';
	}

	public function get_title() {
		return 'Video Button'; // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-play-circle'; // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
	}

	public function get_categories() {
		return [ 'geoport-elements' ]; // category of the widget
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
				'label' => esc_html__( 'Video Setting', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'video_link',
			[
				'label' 		=> esc_html__( 'Video link', 'geoport' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
				'default' 		=> 'https://www.youtube.com/watch?v=uemObN8_dcw',
				'description' 	=> __( 'Enter any video link from external sources', 'geoport' ),
			]
		);
		$this->add_control(
			'video_icon',
			[
				'label' => esc_html__( 'Video icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => 'fas fa-play',
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
		//Video Button Style
		$this->add_control(
			'video_btn_style',
			[
				'label' => __( 'Button Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'video_btn_typo',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .cta-video .popup-video',
			]
		);
		$this->add_responsive_control(
			'btn_width',
			[
				'label' => __( 'Button Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 135,
				],
				'selectors' => [
					'{{WRAPPER}} .cta-video .popup-video' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_height',
			[
				'label' => __( 'Button Height', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 135,
				],
				'selectors' => [ '{{WRAPPER}} .cta-video .popup-video' => 'height: {{SIZE}}{{UNIT}};' ],
			]
		);
		$this->add_responsive_control(
			'btn_line_height',
			[
				'label' => __( 'Icon Line Height', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 135,
				],
				'selectors' => [ '{{WRAPPER}} .cta-video .popup-video' => 'line-height: {{SIZE}}{{UNIT}};' ],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => __( 'Button background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .cta-video .popup-video' => 'background: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'btn_font_color',
			[
				'label' => __( 'Button icon color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cta-video .popup-video' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'video_btn_border',
				'label' => __( 'Button Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .cta-video .popup-video',
			]
		);

		//Wave style
		$this->add_control(
			'btn_border_wave_style',
			[
				'label' => __( 'Wave Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wave_border',
				'label' => __( 'Wave Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .cta-video .popup-video::before, {{WRAPPER}} .cta-video .popup-video::after',
			]
		);
		$this->add_responsive_control(
			'btn_wave_before_height',
			[
				'label' => __( 'Wave 1 Arround Gap', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 135,
				],
				'selectors' => [ 
					'{{WRAPPER}} .cta-video .popup-video::before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
			]
		);
		$this->add_responsive_control(
			'btn_wave_after_height',
			[
				'label' => __( 'Wave 2 Arround Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 140,
				],
				'selectors' => [ '{{WRAPPER}} .cta-video .popup-video::after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ],
			]
		);
	}
	//end of control box 

	protected function render() {
		$settings = $this->get_settings_for_display();
	?>
	   <div class="cta-video text-center">
	      <a href="<?php echo esc_url( $settings['video_link'] ); ?>" class="popup-video"><i class="<?php echo esc_attr($settings['video_icon']); ?>"></i></a>
	   </div>
	<?php
	}

	protected function content_template() { 
	?>
	   <div class="cta-video text-center">
	      <a href="{{{ settings.video_link }}}" class="popup-video"><i class="{{{ settings.video_icon }}}"></i></a>
	   </div>
	<?php
	}
}