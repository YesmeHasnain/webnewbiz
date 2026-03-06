<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Working_Steps extends Widget_Base {

	public function get_name() {
		return 'working-steps';
	}

	public function get_title() {
		return 'Working Step';   // title to show on geoport
	}

	public function get_icon() {
		return 'eicon-info-box';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Working Steps', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'working_steps_item',
			[
				'label' => __( 'Working Steps', 'geoport' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [

					[
						'name' => 'step_title',
						'label' => __( 'Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => __(  'Stpe Title Here', 'geoport' ),
						'label_block' => true,
					],
					[
						'name' => 'step_desc',
						'label' => __( 'Box description', 'geoport' ),
						'type' => Controls_Manager::TEXTAREA,
						'default' => __(  'Stpe Short Description Here', 'geoport' ),
						'label_block' => true,
					],
					[
						'name' => 'step_link',
						'label' => __( 'Link', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'description' => __( 'If have link', 'geoport' ),
					],

				],
				'title_field' => '{{{ step_title }}}',
				'label_block' => true,
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

		/* = Item Styling
		========================================*/
		$this->add_control(
			'title_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'title_heading',
			[
				'label' => __( 'Title Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'title_color',
			[
				'label' => __( 'Title text color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .wprocess-content h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .wprocess-content h4',
			]
		);

		// description style
		$this->add_control(
			'desc_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'desc_heading',
			[
				'label' => __( 'Description Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'desc_color',
			[
				'label' => __( 'Description color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#568ea5',
				'selectors' => [
					'{{WRAPPER}} .wprocess-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .wprocess-content p',
			]
		);

		// Number style
		$this->add_control(
			'num_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'number_heading',
			[
				'label' => __( 'Number Style', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'number_box_width',
			[
				'label' => __( 'Verticle line width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'number_box_height',
			[
				'label' => __( 'Verticle line width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'num_color',
			[
				'label' => __( 'Number color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'num_bg_color',
			[
				'label' => __( 'Number background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#34ccff',
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span' => 'background: {{VALUE}};',
					'{{WRAPPER}} .wprocess-list-num span::after' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .wprocess-list-num span',
			]
		);
		$this->add_control(
			'number_box_radius',
			[
				'label' => __( 'Box border radius', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'number_border_color',
			[
				'label' => __( 'Number border color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d7ebff',
				'selectors' => [
					'{{WRAPPER}} .wprocess-list-num span::before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_border_radius',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .video-thumb a, .pluse-animation .popup-video::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'number_verticle_line_width',
			[
				'label' => __( 'Verticle line width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .work-process::before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_verticle_line_color',
			[
				'label' => __( 'Number border color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d7ebff',
				'selectors' => [
					'{{WRAPPER}} .work-process::before' => 'background: {{VALUE}};',
				],
			]
		);
	}
	//end of control box 

	protected function render() {				//to show on the fontend 
		
		$settings = $this->get_settings_for_display();
	?>

	<div class="work-process">
      <ul>
			<?php 
				$working_steps = $settings['working_steps_item'];
				$working_steps_count = count( $working_steps );

				if ( $working_steps_count < 10 ) {
					$number = "0";
				} else {
					$number = '';
				}
				$i = 0;
				foreach ( $working_steps as $value ) {
				$i++;
			?>
	      <li class="wow fadeInUp" data-wow-delay="0.2s">

            <div class="wprocess-list-num">
               <span><?php echo $number.$i; ?></span>
            </div>

            <div class="wprocess-content">
             	<?php if ( !empty( $value['step_link']) ) { ?>
                 	<h4><a href="<?php echo esc_url( $value['step_link'] ); ?>"><?php echo esc_html( $value['step_title'] ); ?></a></h4>
             	<?php } else { ?>
             		<h4><?php echo esc_html( $value['step_title'] ); ?></h4> 
             	<?php } ?>
                 <p><?php echo esc_html($value['step_desc']); ?></p>
            </div>

	      </li>
	      <?php } ?>
    	</ul>
   </div>
	<?php
	}
}