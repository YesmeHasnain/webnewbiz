<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Location_Box extends Widget_Base {

	public function get_name() {
		return 'location-box';
	}

	public function get_title() {
		return 'Location Box';   // title to show on geoport
	}

	public function get_icon() {
		return 'eicon-google-maps'; // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Location Box', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'location_version',
			[
				'label' => __( 'Location Style Version', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => __( 'Style 1', 'geoport' ),
					'2'  => __( 'Style 2', 'geoport' ),
				],
			]
		);

		$this->add_control(
			'icon_img',
			[
				'label' => esc_html__( 'Icon Image', 'geoport' ),
				'type'  => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'item_title',
			[
				'label' => esc_html__( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Item Title', 'geoport'),
				'label_block' => true,
			]
		);
		$this->add_control(
			'item_desc',
			[
				'label' => esc_html__( 'Description', 'geoport' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( '12/A, Haska City Hall, London, UK', 'geoport'),
				'label_block' => true,
			]
		);
		$this->add_control(
			'link_text',
			[
				'label' => esc_html__( 'Help Center', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Help Center', 'geoport'),
				'label_block' => true,
			]
		);
		$this->add_control(
			'custom_link',
			[
				'label' => __( 'Link', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-domain.com', 'geoport' ),
			]
		);
		$this->add_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'text-left' => [
						'title' => __( 'Left', 'geoport' ),
						'icon' => 'fa fa-align-left',
					],
					'text-center' => [
						'title' => __( 'Center', 'geoport' ),
						'icon' => 'fa fa-align-center',
					],
					'text-right' => [
						'title' => __( 'Right', 'geoport' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'text-center',
				'toggle' => true,
			]
		);
		$this->end_controls_section();
		//End of a control box


		/* = Item Styling
		========================================*/
		$this->start_controls_section(
			'full_item_style',
			[
				'label' => esc_html__( 'Full item Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_style_normal',
			[
				'label' => __( 'Normal State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'tab_border_color',
			[
				'label' => __( 'Border color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-location-box' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .single-location-box .location-box-btn a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-location-box',
			]
		);
		$this->add_responsive_control(
			'item_border_radious',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'deafult' => '',
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single-location-box' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'link_border_style',
			[
				'label' => __( 'Link border style', 'geoport' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_link_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .location-box-btn a',
			]
		);
		$this->end_controls_tab();

		/* = Button Hover Styling
		========================================*/
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover State', 'geoport' ),
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
			'full_h_border_color',
			[
				'label' => __( 'Hover border color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-location-box:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .single-location-box:hover .location-box-btn a' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/* = Item Title Styling
		========================================*/
		$this->start_controls_section(
			'item_title_style',
			[
				'label' => esc_html__( 'Item Title Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_title_style' );
		$this->start_controls_tab(
			'title_tab_style_normal',
			[
				'label' => __( 'Normal State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'title_color',
			[
				'label' => __( 'Box title color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .location-box-content h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .location-box-content h4',
			]
		);
		$this->end_controls_tab();


		$this->start_controls_tab(
			'title_tab_style_hover',
			[
				'label' => __( 'Hover State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'title_h_color',
			[
				'label' => __( 'Box title color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .single-location-box:hover .location-box-content h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_h_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-location-box:hover .location-box-content h4',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/* = Item Desc Styling
		========================================*/
		$this->start_controls_section(
			'item_desc_style',
			[
				'label' => esc_html__( 'Item Description Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_desc_style' );
		$this->start_controls_tab(
			'desc_tab_style_normal',
			[
				'label' => __( 'Normal State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'desc_color',
			[
				'label' => __( 'Box description color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#568ea5',
				'selectors' => [
					'{{WRAPPER}} .location-box-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Description typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .location-box-content p',
			]
		);
		$this->end_controls_tab();


		$this->start_controls_tab(
			'desc_tab_style_hover',
			[
				'label' => __( 'Hover State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'desc_h_color',
			[
				'label' => __( 'Box description color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#568ea5',
				'selectors' => [
					'{{WRAPPER}} .single-location-box:hover .location-box-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_h_typography',
				'label' => __( 'Description typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-location-box:hover .location-box-content p',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/* = Item Link Styling
		========================================*/
		$this->start_controls_section(
			'item_link_style',
			[
				'label' => esc_html__( 'Item Link Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_link_style' );
		$this->start_controls_tab(
			'link_tab_style_normal',
			[
				'label' => __( 'Normal State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'link_color',
			[
				'label' => __( 'Box link color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#568ea5',
				'selectors' => [
					'{{WRAPPER}} .location-box-btn a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'label' => __( 'Link typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .location-box-btn a',
			]
		);
		$this->end_controls_tab();


		$this->start_controls_tab(
			'link_tab_style_hover',
			[
				'label' => __( 'Hover State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'link_h_color',
			[
				'label' => __( 'Box link hover color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-location-box:hover .location-box-btn a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-location-box-two:hover .location-box-btn a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_h_typography',
				'label' => __( 'Link hover typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-location-box:hover .location-box-btn a',
				'selector' => '{{WRAPPER}} .single-location-box-two:hover .location-box-btn a',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	//end of control box 

	protected function render() { //to show on the fontend 
		
		$settings = $this->get_settings();
		$location_version = $settings['location_version'];

		$text_align = $settings['text_align'];
		$icon_img = $settings['icon_img']['url'];

		$item_title = $settings['item_title'];
		$item_desc = $settings['item_desc'];

		$this->add_inline_editing_attributes( 'item_title', 'none' );
		$this->add_inline_editing_attributes( 'item_desc', 'none' );

		$link_text = $settings['link_text'];
		$custom_link = $settings['custom_link'];

		$data_type = $custom_link;
	?>

	<?php if ( $location_version == '2' ) { ?>
	    <div class="single-location-box-two <?php echo esc_attr( $text_align ); ?>">
	        <div class="location-box-content">
	         	<?php if (!empty( $icon_img )) { ?>
		            <div class="location-box-icon">
			            <img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
		            </div>
	            <?php } ?>
	            <?php if (!empty( $item_title )) { ?>
		            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
		            	<?php echo esc_html( $item_title ); ?>
		            </h4>
	         	<?php } ?>
	            	<p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
		        <div class="location-box-btn">
		            <a href="<?php echo esc_url($data_type); ?>"><?php echo esc_html( $link_text ); ?> <span><i class="fa fa-arrow-right"></i></span></a>
		        </div>
	        </div>
	    </div>

	<?php } else { ?> 
	    <div class="single-location-box <?php echo esc_attr( $text_align ); ?>">
	        <div class="location-box-content">
	         	<?php if (!empty( $icon_img )) { ?>
		            <div class="location-box-icon">
			            <img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
		            </div>
	            <?php } ?>
	            <?php if (!empty( $item_title )) { ?>
		            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
		            	<?php echo esc_html( $item_title ); ?>
		            </h4>
	         	<?php } ?>
	            	<p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
	        </div>
	        <div class="location-box-btn">
	            <a href="<?php echo esc_url($data_type); ?>"><?php echo esc_html( $link_text ); ?></a>
	        </div>
	    </div>

	<?php } ?>

	<?php
	}	
}