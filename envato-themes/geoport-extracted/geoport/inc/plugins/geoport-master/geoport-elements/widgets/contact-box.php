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

class Contact_Box extends Widget_Base {

	public function get_name() {
		return 'contact-box';
	}

	public function get_title() {
		return 'Contact Box';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-address-card'; // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Contact Box', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'icon_type',
			[
				'label' => __( 'Icon Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon'  => __( 'Icon', 'geoport' ),
					'image'  => __( 'Image', 'geoport' ),
				],
				'default' => [ 'icon' ],
			]
		);
		$this->add_control(
			'icon_font',
			[
				'label' => esc_html__( 'Font Icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => 'fal fa-map',
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'icon_img',
			[
				'label' => esc_html__( 'Icon Image', 'geoport' ),
				'type'  => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition' => [
					'icon_type' => 'image',
				],
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
				'label' => esc_html__( 'Link Text', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Link Text', 'geoport'),
				'label_block' => true,
			]
		);
		$this->add_control(
			'custom_link',
			[
				'label' => __( 'Link', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'geoport' ),
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
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-contact-box',
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
					'{{WRAPPER}} .single-contact-box' => 'border-radius: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .contact-box-btn a',
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
					'{{WRAPPER}} .single-contact-box:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .single-contact-box:hover .contact-box-btn a' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();


		/* = Item Icon Styling
		========================================*/
		$this->start_controls_section(
			'item_icon_style',
			[
				'label' => esc_html__( 'Item Icon Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_icon_style' );
		$this->start_controls_tab(
			'icon_tab_style_normal',
			[
				'label' => __( 'Normal State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'icon_box_width',
			[
				'label' => __( 'Icon Box Width', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 110,
				],
				'selectors' => [
					'{{WRAPPER}} .contact-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_height',
			[
				'label' => __( 'Icon Box Height', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 110,
				],
				'selectors' => [
					'{{WRAPPER}} .contact-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_line_height',
			[
				'label' => __( 'Icon Box Line Height', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 110,
				],
				'selectors' => [
					'{{WRAPPER}} .contact-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_bg_color',
			[
				'label' => __( 'Icon box background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffefef',
				'selectors' => [
					'{{WRAPPER}} .contact-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_box_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .contact-icon',
			]
		);
		$this->add_control(
			'icon_box_border_radious',
			[
				'label' => __( 'Border radius', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .contact-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_icon_color',
			[
				'label' => __( 'Icon box icon color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .contact-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .contact-box-big-icon i' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_box_icon_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .contact-icon i',
			]
		);
		$this->end_controls_tab();


		$this->start_controls_tab(
			'icon_tab_style_hover',
			[
				'label' => __( 'Hover State', 'geoport' ),
			]
		);
		$this->add_responsive_control(
			'icon_box_h_bg_color',
			[
				'label' => __( 'Icon box hover background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-contact-box:hover .contact-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_hover_icon_color',
			[
				'label' => __( 'Icon box hover icon color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .single-contact-box:hover .contact-icon i' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .contact-box-content h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .contact-box-content h4',
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
					'{{WRAPPER}} .single-contact-box:hover .contact-box-content h4' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_h_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-contact-box:hover .contact-box-content h4',
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
					'{{WRAPPER}} .contact-box-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Description typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .contact-box-content p',
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
					'{{WRAPPER}} .single-contact-box:hover .contact-box-content p' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_h_typography',
				'label' => __( 'Description typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-contact-box:hover .contact-box-content p',
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
					'{{WRAPPER}} .contact-box-btn a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'label' => __( 'Link typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .contact-box-btn a',
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
					'{{WRAPPER}} .single-contact-box:hover .contact-box-btn a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_h_typography',
				'label' => __( 'Link hover typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-contact-box:hover .contact-box-btn a',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	//end of control box 

	protected function render() { //to show on the fontend 
		
		$settings = $this->get_settings();

		$text_align = $settings['text_align'];

		$icon_type = $settings['icon_type'];
		$icon_font = $settings['icon_font'];
		$icon_img = $settings['icon_img']['url'];

		$item_title = $settings['item_title'];
		$item_desc = $settings['item_desc'];

		$this->add_inline_editing_attributes( 'item_title', 'none' );
		$this->add_inline_editing_attributes( 'item_desc', 'none' );

		$link_text = $settings['link_text'];
		$custom_link = $settings['custom_link'];

		$data_type = $custom_link;
      if(filter_var($data_type, FILTER_VALIDATE_EMAIL)){
        $href_value = 'email';
      } elseif ( preg_match('/^[0-9\-\(\)\/\+\s]*$/', $data_type ) ) {
        $href_value = 'phone';
      } elseif (filter_var($data_type, FILTER_VALIDATE_URL)) {
        $href_value = 'url';
      } else {
        $href_value = '';
      }
	?>

      <div class="single-contact-box <?php echo esc_attr( $text_align ); ?>">
         <div class="contact-box-content">
            <div class="contact-icon">
               <?php if ( $icon_type == 'image' ) { ?>
	             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } elseif ( $icon_type == 'icon' ) { ?>
	         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
	         	<?php } else { ?>
						<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
	         	<?php } ?>
            </div>
            <?php if (!empty( $item_title )) { ?>
            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
            	<?php echo esc_html( $item_title ); ?>
            </h4>
         	<?php } ?>
            <p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
         </div>
         <div class="contact-box-btn">
         	<?php if (!empty($href_value == 'email')) { ?>
            	<a href="mailto:<?php echo sanitize_email( $data_type ); ?>">
            <?php } elseif (!empty( $href_value == 'phone' )) { $phone_no = str_replace(" ", "", $data_type); ?>
            	<a href="tel:<?php echo esc_attr($phone_no); ?>">
            <?php } elseif (!empty( $href_value == 'url' )) { ?>
            	<a href="<?php echo esc_url($data_type); ?>">
            <?php } else { ?>
            	<a>
            <?php } ?>
            	<?php if ( $icon_type == 'image' ) { ?>
	             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } elseif ( $icon_type == 'icon' ) { ?>
	         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
	         	<?php } else { ?>
						<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
	            <?php } echo esc_html( $link_text ); ?>
         		</a>
         </div>
         <div class="contact-box-big-icon">
            <?php if ( $icon_type == 'image' ) { ?>
             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
         	<?php } elseif ( $icon_type == 'icon' ) { ?>
         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
         	<?php } else { ?>
					<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
         	<?php } ?>
         </div>
      </div>

	<?php
	}	
}
