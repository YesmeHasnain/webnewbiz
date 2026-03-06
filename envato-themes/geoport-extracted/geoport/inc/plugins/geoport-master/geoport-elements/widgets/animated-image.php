<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Animated_Image extends Widget_Base {

	public function get_name() {
		return 'animated_image';
	}

	public function get_title() {
		return 'Animated Image';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-file-image';    // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Animated Image', 'geoport' ),   //section name for controler view
			]
		);
		$this->add_control(
			'anim_img',
			[
				'label' => esc_html__( 'Image', 'geoport' ),
				'type'  => Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'img_link',
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
			'img_entrance_animation',
			[
				'label' => __( 'Image Animation', 'geoport' ),
				'type' => Controls_Manager::ANIMATION,
				'label_block' => true,
			]
		);
		$this->add_control(
			'img_entrance_animation_delay',
			[
				'label' => __( 'Animation Delay', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0.2s',
				'options' => ifinger_anim_delay(),
				'label_block' => true,
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'geoport' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'geoport' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'geoport' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .anim-image' => 'text-align: {{VALUE}};',
				],
			]
		);		
		
		$this->end_controls_section();

		//End of a control box

		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'full_imgstyle',
			[
				'label' => esc_html__( 'Image style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'img_bg_color',
			[
				'label' => __( 'Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .anim-image img' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'img_margin_arround',
			[
				'label' => __( 'Margin', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .anim-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'img_padding_arround',
			[
				'label' => __( 'Padding', 'geoport' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .anim-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'img_border_arround',
				'label' => __( 'Border', 'geoport' ),
				'selector' => '{{WRAPPER}} .anim-image img',
			]
		);
		$this->add_responsive_control(
			'img_border_radious',
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
					'{{WRAPPER}} .anim-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'large',
			]
		);

		$this->end_controls_section();
	}
	//end of control box 

	//to show on the fontend
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['anim_img']['url'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'anim-image' );

		$img_anim        = $settings['img_entrance_animation'];
		$img_eanim_delay = $settings['img_entrance_animation_delay'];
		$anim_img        = $settings['anim_img']['url'];
		
		$img_link        = $settings['img_link']['url'];
		$target          = $settings['img_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow        = $settings['img_link']['nofollow'] ? ' rel="nofollow"' : '';

		if (!empty($img_anim)) {
			$img_anim = 'data-wow-delay="'.esc_attr( $img_eanim_delay ).'"';
		} else {
			$img_anim = '';	
		}
		if ( ! empty( $settings['img_entrance_animation'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'wow ' . $settings['img_entrance_animation'] );
		}
		$this->add_render_attribute( 'wrapper', 'class', 'anim-image' );
	?>
   	<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> <?php echo $img_anim; ?>>
   		<?php if (!empty( $img_link )) { ?>
   			<a href="<?php echo esc_attr( $img_link ); ?>" <?php echo $target . $nofollow ?>>
   				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'anim_img' ); ?>
   			</a>
   		<?php } else { ?>
        	<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'anim_img' ); ?>
      	<?php } ?>
     </div>
	<?php 
	}	
}