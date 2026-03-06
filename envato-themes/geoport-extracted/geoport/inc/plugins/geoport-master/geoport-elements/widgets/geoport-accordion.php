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

class Geoport_Accordion extends Widget_Base {   //this name is added to plugin.php of the root folder

	public function get_name() {
		return 'geoport-accordion';
	}

	public function get_title() {
		return 'Geoport Accordion';   // title to show on elementor
	}

	public function get_icon() {
		return 'eicon-accordion';    //   eicon-posts-ticker-> eicon ow asche icon to show on
	}

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
	}
	
   public function get_script_depends() {
		return [ 'geoport-elementor-editor' ];
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
				'label' => esc_html__( 'Accordion', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'accordion_items',
			[
				'label' => __( 'Accordion Items', 'geoport' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'title_icon_type',
						'label' => __( 'Icon Type', 'geoport' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'icon_font',
						'options' => [
							'icon_font' => __( 'Font Icon', 'geoport' ),
							'icon_text' => __( 'Text Icon', 'geoport' ),
						],
						'label_block' => true,
					],
					[
						'name' => 'title_icon',
						'label' => __( 'Title Icon', 'geoport' ),
						'type' => Controls_Manager::ICON,
						'default' => __( 'Title Here', 'geoport' ),
						'label_block' => true,
						'condition' => [
							'title_icon_type' => 'icon_font',
						],
					],
					[
						'name' => 'title_text',
						'label' => __( 'Title Icon', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( '01', 'geoport' ),
						'label_block' => true,
						'condition' => [
							'title_icon_type' => 'icon_text',
						],
					],
					[
						'name' => 'accordion_title',
						'label' => __( 'Title', 'geoport' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Tab Title', 'geoport' ),
						'label_block' => true,
					],
					[
						'name' => 'accordion_text',
						'label' => __( 'Description', 'geoport' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'geoport' ),
						'label_block' => true,
					],
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label' => __( 'Close Icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fas fa-plus',
				'label_block' => true,
			]
		);
		$this->add_control(
			'open_icon',
			[
				'label' => __( 'Open Icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fas fa-minus',
				'label_block' => true,
			]
		);

		$this->add_control(
			'open_event',
			[
				'label' => __( 'Open Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'click'  => __( 'Open by click', 'geoport' ),
					'hover'  => __( 'Open by hover', 'geoport' ),
				],
			]
		);

		$this->end_controls_section();

		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'full_accordion_style',
			[
				'label' => esc_html__( 'Full Accordion Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'accordion_border',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .faq-wrapper .card-border',
			]
		);
		$this->add_control(  
			'last_accor_border',
			[
				'label' => __( 'Last item border', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-border:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(  
			'accordion_border_radius',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-border' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'accordion_padding',
			[
				'label' => __( 'Padding', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'accordion_margin',
			[
				'label' => __( 'Margin', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .faq-wrapper .single-industries' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/* = Item Title Styling
		========================================*/
		$this->start_controls_section(
			'accordion_title_style',
			[
				'label' => esc_html__( 'Accordion Title Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#548ca6',
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .faq-wrapper .card-header h5 a',
			]
		);
		
		$this->end_controls_section();

		/* = Item Icon Styling
		========================================*/
		$this->start_controls_section(
			'accordion_icon_style',
			[
				'label' => esc_html__( 'Accordion Icon Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .faq-wrapper .card-header h5 a span' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Right Icon
		$this->add_control(
			'right_icon_heading',
			[
				'label' => __( 'Right Icon Settings', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'right_icon_size',
			[
				'label' => __( 'Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 i.open' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .faq-wrapper .card-header h5 i.close' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'right_icon_color',
			[
				'label' => __( 'Right Icon Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b2cdda',
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a.collapsed i.close' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'right_icon_active_color',
			[
				'label' => __( 'Right Icon Active Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .faq-wrapper .card-header h5 a i.open' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() { // to show on the fontend 

		$settings = $this->get_settings();
		$accordion_items = $settings['accordion_items'];
		$open_event = $settings['open_event'];
		$open_icon = $settings['open_icon'];
		$close_icon = $settings['close_icon'];	

		$uniqid = uniqid();

		if ( is_array($accordion_items) ) {
?>
<div class="faq-wrapper">
   <div class="accordion" id="accordionExample<?php echo esc_attr( $uniqid ); ?>">
   	<?php
			$i = 0;
			foreach ($accordion_items as $key => $value) {
				$i++;
				$title_icon = $value['title_icon'];
				$title_text = $value['title_text'];
				$accordion_title = $value['accordion_title'];
				$accordion_text = $value['accordion_text'];
				$u_id = mt_rand(100000, 999999);
				$tab_id = "$u_id";
   			if ( $i == 1 ) {
   				$sac = 'show active';
   			} else {
   				$sac = '';
   			}

   			$title_icon_type = $value['title_icon_type'];

   		if ( $open_event == 'hover' ) {
   	?>

   	<div class="single-industries">
        <div class="industries-top">
            <a href="!#">
            	<?php
            		if ( $title_icon_type == 'icon_text' ) {
            			echo '<span>'.esc_attr( $title_text ).'</span>';
            		} else {
	            		echo '<i class="'.esc_attr( $title_icon ).'"></i>';
	            	} 
            		echo esc_html( $accordion_title ); 

            		echo '<i class="open '.esc_attr( $open_icon ).'"></i>';
	            	echo '<i class="close '.esc_attr( $close_icon ).'"></i>';
            	?> 
         	</a>
        </div>
        <div class="industries-content">
            <?php echo $accordion_text; ?>
        </div>
   	</div>

   	<?php } else { ?>

      <div class="card card-border">
         <div class="card-header" id="heading<?php echo esc_attr( $tab_id ); ?>">
            <h5 class="mb-0">
               <a href="#" class="btn-link collapsed" data-toggle="collapse" data-target="#collapse<?php echo esc_attr( $tab_id ); ?>" aria-expanded="false"  aria-controls="collapse<?php echo esc_attr( $tab_id ); ?>">
                  <?php
	            		if ( $title_icon_type == 'icon_text' ) {
	            			echo '<span>'.esc_attr( $title_text ).'</span>';
	            		} else {
		            		echo '<i class="'.esc_attr( $title_icon ).'"></i>';
		            	} 
	            		echo esc_html( $accordion_title ); 

	            		echo '<i class="open '.esc_attr( $open_icon ).'"></i>';
	            		echo '<i class="close '.esc_attr( $close_icon ).'"></i>';
	            	?> 

               </a>
            </h5>
         </div>
         <div id="collapse<?php echo esc_attr( $tab_id ); ?>" class="collapse" aria-labelledby="heading<?php echo esc_attr( $tab_id ); ?>" data-parent="#accordionExample<?php echo esc_attr( $uniqid ); ?>">
            <div class="card-body">
               <?php echo $accordion_text; ?>
            </div>
         </div>
      </div>

      <?php } } ?>

    </div>
</div>

<?php }

	}
}