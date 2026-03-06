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

class Single_Icon_Item extends Widget_Base {

	public function get_name() {
		return 'single-icon-item';
	}

	public function get_title() {
		return 'Icon Box';   // title to show on geoport
	}

	public function get_icon() {
		return 'eicon-featured-image';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Single Icon Box', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'icon_box_type',
			[
				'label' => __( 'Icon Box Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'normal_box'  => __( 'Default Item', 'geoport' ),
					'service_box'  => __( 'Service Item', 'geoport' ),
					'feature_box'  => __( 'Feature Item', 'geoport' ),
					'about_box'  => __( 'About Item', 'geoport' ),
				],
				'default' => [ 'image' ],

			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => __( 'Icon Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'icon'  => __( 'Icon', 'geoport' ),
					'image'  => __( 'Image', 'geoport' ),
				],
				'default' => [ 'image' ],

			]
		);

		$this->add_control(
			'icon_font',
			[
				'label' => esc_html__( 'Font Icon', 'geoport' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
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
				'default' => __( 'Vehicles traveling on these networks may include automobiles, buses, trains, trucks, helicopters, watercraft.', 'geoport'),
				'label_block' => true,
			]
		);

		$this->add_control(
			'link_type',
			[
				'label' => __( 'Item link Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom_link',
				'options' => [
					'custom_link'  => __( 'Custom link', 'geoport' ),
					'post_link'  => __( 'Post Link', 'geoport' ),
				],
				'default' => [ 'image' ],

			]
		);

		$this->add_control(
			'custom_link',
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
				'condition' => [
					'link_type' => 'custom_link',
				],
			]
		);

		$this->add_control(
			'post_type_link',
			[
				'label' => __( 'Select Post Type', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'service',
				'options' => [
					'default'  => __( 'Default Posts', 'geoport' ),
					'service'  => __( 'Service Posts', 'geoport' ),
				],
				'condition' => [
					'link_type' => 'post_link',
				],
			]
		);
		$this->add_control(
			'default_post_link',
			[
				'label' => __( 'Default Link', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'options' => default_posts_options(),
				'condition' => [
					'post_type_link' => 'default',
				],
				'conditions' => [
					'relation' => 'in',
					'terms' => [
						[
							'name' => 'link_type',
							'operator' => '==',
							'value' => 'post_link',
						],
						[
							'name' => 'post_type_link',
							'operator' => '==',
							'value' => 'default',
						],
					],
				],
			]
		);
		$this->add_control(
			'service_post_link',
			[
				'label' => __( 'Service Link', 'geoport' ),
				'type' => Controls_Manager::SELECT,
				'options' => service_posts_options(),
				'conditions' => [
					'relation' => 'in',
					'terms' => [
						[
							'name' => 'link_type',
							'operator' => '==',
							'value' => 'post_link',
						],
						[
							'name' => 'post_type_link',
							'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
							'value' => 'service',
						],
					],
				],
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
				'default' => 'text-left',
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

		$this->add_control(
			'item_bg_color',
			[
				'label' => __( 'Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-features' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
            'item_padding_arround',
            [
                'label' => esc_html__('Padding', 'geoport'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'top' => 45,
                    'right' => 45,
                    'bottom' => 45,
                    'left' => 45,
                ],
                'selectors' => [
                    '{{WRAPPER}} .single-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'icon_box_type' => 'feature_box',
				],
            ]
        );

        $this->add_responsive_control(
            'item_padding3_arround',
            [
                'label' => esc_html__('Padding', 'geoport'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'top' => 45,
                    'right' => 35,
                    'bottom' => 45,
                    'left' => 35,
                ],
                'selectors' => [
                    '{{WRAPPER}} .single-about-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'icon_box_type' => 'about_box',
				],
            ]
        );

        $this->add_responsive_control(
            'item_padding2_arround',
            [
                'label' => esc_html__('Padding', 'geoport'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'top' => 60,
                    'right' => 20,
                    'bottom' => 55,
                    'left' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .single-services' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
				'condition' => [
					'icon_box_type' => 'service_box',
				],
            ]
        );


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-features',
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
					'{{WRAPPER}} .single-faq-box' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'service_icon_box_hover_color',
			[
				'label' => __( 'Service box hover color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-services:hover ' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .single-services:hover::before ' => 'border-color: transparent transparent {{VALUE}} transparent;',
				],
				'condition' => [
					'icon_box_type' => 'service_box',
				],
			]
		);
		$this->add_control(
			'feature_box_hover_color',
			[
				'label' => __( 'Feature box hover color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .features-content h4 a:hover, .features-content .read-more > a:hover ' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon_box_type' => 'feature_box',
				],
			]
		);
		$this->end_controls_section();


		/* = Icon or image style = */
		$this->start_controls_section(
			'item_icon_style',
			[
				'label' => esc_html__( 'Icon style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'icon_style',
			[
				'label' => __( 'Icon style', 'geoport' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'image_style',
			[
				'label' => __( 'Image style', 'geoport' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'deafult' => '',
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .services-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .features-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .single-faq-box .faq-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .single-about-box .about-box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-faq-box .faq-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .services-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .features-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .watermark-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box .about-box-icon i' => 'color: {{VALUE}};',
				],
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);
		$this->add_responsive_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'deafult' => '',
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .single-faq-box .faq-icon img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'counter_icon_type' => 'image',
				],
			]
		);
		$this->add_control(
			'icon_box_color',
			[
				'label' => __( 'Icon or image box background color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .services-icon i ' => 'background: {{VALUE}};',
					'{{WRAPPER}} .single-faq-box .faq-icon ' => 'background: {{VALUE}};',
					'{{WRAPPER}} .single-faq-box .faq-icon::before ' => 'background: {{VALUE}};',
					'{{WRAPPER}} .single-faq-box .faq-icon::after ' => 'background: {{VALUE}};',
					'{{WRAPPER}} .single-features:hover .features-content h4::before ' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();


		/* = Title style = */
		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq-box-content h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .item-box-content h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .item-box-content h4 a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-features .features-content h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box .about-box-content h4' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box  about-box-content h4 a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-features .features-content h4 a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box .about-box-content h4 a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_border_color',
			[
				'label' => __( 'Title Border Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffe0e0',
				'selectors' => [
					'{{WRAPPER}} .single-features .features-content h4::before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .single-about-box .about-box-icon::before' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_border_hover_color',
			[
				'label' => __( 'Title Hover Border Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-features:hover .features-content h4::before' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .single-features .features-content h4',
			]
		);
		$this->end_controls_section();


		/* = Description style = */
		$this->start_controls_section(
			'desc_style',
			[
				'label' => esc_html__( 'Text style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .faq-box-content p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .features-content p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .item-box-content p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-about-box .about-box-content p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .typography_style p',
			]
		);

		$this->add_control(
			'desc_padding_arround',
			[
				'label' => __( 'Padding', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .features-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();


		/* = Item Hover style = */
		$this->start_controls_section(
			'hover_style',
			[
				'label' => esc_html__( 'Read More Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'link_readmore_color',
			[
				'label' => __( 'Read More Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-features .features-content .read-more > a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Read More Hover Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-icon-box:hover .item-box-content h4 a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .single-features .features-content .read-more > a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}
	//end of control box 

	protected function render() { //to show on the fontend 
		
		$settings = $this->get_settings();

		$icon_box_type = $settings['icon_box_type'];
		$text_align = $settings['text_align'];
		$icon_type = $settings['icon_type'];

		$icon_font = $settings['icon_font'];
		$icon_img = $settings['icon_img']['url'];

		$item_title = $settings['item_title'];
		$item_desc = $settings['item_desc'];
		
		$this->add_inline_editing_attributes( 'item_title', 'none' );
		$this->add_inline_editing_attributes( 'item_desc', 'basic' );


		$link_type   = $settings['link_type'];
		$custom_link = $settings['custom_link']['url'];
		$target      = $settings['custom_link']['is_external'] ? ' target="_self"' : '';
		$nofollow    = $settings['custom_link']['nofollow'] ? ' rel="nofollow"' : '';

		$post_type_link = $settings['post_type_link'];

		if ( $post_type_link == 'default' ) {
			$post_link = $settings['default_post_link'];
		} else {
			$post_link = $settings['service_post_link'];
		}

		if( !empty( $post_link ) ){
		   $link_source_post = get_page_link( $post_link );
		} else {
		   $link_source_post = '';
		}

		if ( $link_type == 'post_link' ) {
			$link_source = $link_source_post;
		} else {
			$link_source = $custom_link;
		}

		if ( $icon_box_type == 'service_box' ) {
	?>
		
		<div class="single-services <?php echo esc_attr( $text_align ); ?>">
         <div class="services-icon">
            <?php if ( $icon_type == 'image' ) { ?>
             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
         	<?php } elseif ( $icon_type == 'icon' ) { ?>
         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
         	<?php } else { ?>
					<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
         	<?php } ?>
         </div>
         <div class="services-content typography_style">
            <?php if (!empty( $link_source )) { ?>
            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
            	<?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.esc_html( $item_title ).'</a>'; ?>
            </h4>
         	<?php } else { ?>
         	<h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>><?php echo wp_kses_stripslashes( $item_title ); ?></h4>
         	<?php } ?>
            <p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
         </div>
      </div>

		<?php } elseif ( $icon_box_type == 'feature_box' ) { ?>

      <div class="single-features <?php echo esc_attr( $text_align ); ?>">
      	<?php if ( $icon_type == 'image' ) { ?>
         	<div class="features-img">
             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
            </div>
         <?php } elseif ( $icon_type == 'icon' ) { ?>
         	<div class="features-icon">
         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
         	</div>
         <?php } else { ?>
         	<div class="features-icon">
					<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
				</div>
         <?php } ?>
         <div class="features-content typography_style">
            <?php if (!empty( $link_source )) { ?>
            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
            	<?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.esc_html( $item_title ).'</a>'; ?>
            </h4>
         	<?php } else { ?>
         	<h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>><?php echo wp_kses_stripslashes( $item_title ); ?></h4>
         	<?php } ?>
            <p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
            <?php if (!empty( $link_source )) { ?>
            <div class="read-more">
            <?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.__( 'Read More', 'geoport' ).'</a>'; ?>
           	</div>
           	<?php } ?>
         </div>
      </div>

      <?php } elseif ( $icon_box_type == 'about_box' ) { ?>

      	<div class="single-about-box <?php echo esc_attr( $text_align ); ?>">
            <div class="about-box-icon">
               <?php if ( $icon_type == 'image' ) { ?>
	             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } elseif ( $icon_type == 'icon' ) { ?>
	         		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
	         	<?php } else { ?>
						<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } ?>
            </div>
            <div class="about-box-content typography_style">
               <?php if (!empty( $link_source )) { ?>
	            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
	            	<?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.esc_html( $item_title ).'</a>'; ?>
	            </h4>
	         	<?php } else { ?>
	         	<h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>><?php echo wp_kses_stripslashes( $item_title ); ?></h4>
	         	<?php } ?>
               <p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
            </div>
            <div class="watermark-icon">
           	 <?php if ( $icon_type == 'image' ) { ?>
	             	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } elseif ( $icon_type == 'icon' ) { ?>
	         		<i class="about-hover-icon <?php echo esc_attr( $icon_font ); ?>"></i>
	         	<?php } else { ?>
						<img class="about-hover-icon" src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
	         	<?php } ?>
            </div>
         </div>

		<?php } else { ?>

      <div class="single-icon-box <?php echo esc_attr( $text_align ); ?>">
        	<?php if ( !empty($icon_font) || !empty($icon_img)) { ?>
            <div class="icon-box">
               <?php if ( $icon_type == 'image' ) { ?>
                	<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
            	<?php } elseif ( $icon_type == 'icon' ) { ?>
            		<i class="<?php echo esc_attr( $icon_font ); ?>"></i>
            	<?php } else { ?>
						<img src="<?php echo esc_attr( $icon_img ); ?>" alt="<?php echo esc_attr_e( 'img', 'geoport' ); ?>">
            	<?php } ?>
            </div>
         <?php } ?>
         <div class="item-box-content typography_style">
         	<?php if (!empty( $link_source )) { ?>
            <h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>>
            	<?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.esc_html( $item_title ).'</a>'; ?>
            </h4>
         	<?php } else { ?>
         	<h4 <?php echo $this->get_render_attribute_string( 'item_title' ); ?>><?php echo wp_kses_stripslashes( $item_title ); ?></h4>
         	<?php } ?>
            <p <?php echo $this->get_render_attribute_string( 'item_desc' ); ?>><?php echo wp_kses_stripslashes( $item_desc ); ?></p>
            <?php if (!empty( $link_source )) { ?>
            <div class="read-more">
	            <?php echo '<a href="' . $link_source . '"' . $target . $nofollow . ' class="icon-box-btn">'.__( 'Read More', 'geoport' ).'</a>'; ?>
	           	</div>
           	<?php } ?>
         </div>
      </div>
	<?php }
	
	}	
}