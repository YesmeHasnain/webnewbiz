<?php	
namespace Amedea\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class amedea__animated_hover_menu2 extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	 
	public function get_name() {
		return 'animated_hover_menu2';
	}
	
	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	 
	public function get_title() {
		return esc_html__( 'Animated Hover Menu 2', 'amedea' );
	}
	
	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	 
	public function get_icon() {
		return 'eicon-button';
	}
	
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Return array Widget categories.
	 */
	 
	public function get_categories() {
		return [ 'amedea-category' , 'amedea-animated-hover-menu-category' ];
	}
	
	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	 
	public function get_script_depends() {
		return [ ];
	}
	
	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	 
	public function get_style_depends() {
		return [ 'animated-hover-menu6' ];
	}
	
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */

	protected function register_controls() {

		$this->start_controls_section(
			'section_content0',
			[
				'label' => esc_html__( 'Color', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'style',
			array(
			  'label'       => esc_html__('Text Color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'dark',
			  'label_block' => true,
			  'options' => array(
				'light' => esc_html__('Light', 'amedea'),
				'dark' => esc_html__('Dark', 'amedea'),
			  )
			)
		  );
		
		$this->add_control(
			'colormenu',
			array(
			  'label'       => esc_html__('Link color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);	
		
		$this->add_control(
			'colormenuhover',
			array(
			  'label'       => esc_html__('Hover color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);	
		
		$this->add_control(
			'colordeco',
			array(
			  'label'       => esc_html__('Subtitle color', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);	
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'amedea' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'amedea' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '*', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'button_url',
			[
				'label' => esc_html__( 'Button URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
		
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$this->add_control(
		  'images',
		  array(
			'label'     => esc_html__('Items', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default'   => array(
			  array(
				'title'   => esc_html__('Title', 'amedea'),
				'subtitle' => esc_html__('Subtitle', 'amedea'),
				'button_url' => '',
				'image' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			  ),
			),
			'title_field' => '<span>{{ title }}</span>',
		  )
		);
		
		$this->end_controls_section();
		
	}
		
	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings       = $this->get_settings();
		$style    = $settings['style'];
		$images    = $settings['images'];
	?>

	<style>.animated-hover-menu-6{--color-menu:<?php echo esc_html($settings['colormenu']); ?>;--color-menu-hover:<?php echo esc_html($settings['colormenuhover']); ?>;--color-menu-deco:<?php echo esc_html($settings['colordeco']); ?>;}</style>
	<nav class="animated-hover-menu-6">
	<?php $i = 0; if (is_array($images) || is_object($images)) { foreach($images as $key => $image): ?>
	<div class="animated-hover-menu-6__item">
		<a class="animated-hover-menu-6__item-link" href="<?php echo esc_url($image['button_url']['url']); ?>"><?php echo esc_html($image['title']); ?></a>
		<img class="animated-hover-menu-6__item-img" src="<?php echo esc_url($image['image']['url']); ?>" alt="<?php echo esc_html($image['title']); ?>" />
		<div class="ahm-marquee">
			<div class="ahm-marquee__inner" aria-hidden="true">
				<span><?php echo esc_html($image['subtitle']); ?></span>
				<span><?php echo esc_html($image['subtitle']); ?></span>
				<span><?php echo esc_html($image['subtitle']); ?></span>
				<span><?php echo esc_html($image['subtitle']); ?></span>
			</div>
		</div>
	</div>
	<?php $i++; endforeach; } ?>
	</nav>
			
	<?php

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
		
	}
