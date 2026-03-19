<?php
namespace Amedea\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Elementor
 *
 * Elementor widget
 *
 * @since 1.0.0
 */
class amedea__on_scroll_grid4 extends Widget_Base {

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
		return 'on-scroll-grid4';
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
		return esc_html__( 'On-Scroll Grid #4', 'amedea' );
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
		return 'eicon-gallery-group';
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
		return [ 'gsap' , 'scrolltrigger' , 'lenis' , 'imagesloaded' , 'amedea-on-scroll-grid4' ];
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
		return [ 'on-scroll-grid' ];
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

	public function get_categories() {
		return [ 'amedea-category' , 'amedea-on-scroll-grid-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
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

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Are you ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
				
		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'amedea' ),
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
				'subtitle'   => esc_html__('Subtitle', 'amedea'),
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
		$settings = $this->get_settings_for_display();
		$images     = $settings['images'];
		$style    = $settings['style'];
		
	?>
	<section class="grid--content grid--content-v4 <?php echo esc_attr($settings['style']); ?>">
		<?php $i = 1; if (is_array($images) || is_object($images)) { foreach($images as $key => $image):  ?>
		<figure class="grid--content__item grid--4-style--<?php echo esc_html($i); ?>">
		    <div class="grid--content__item-img">
				<div class="grid--content__item-img-inner" style="background-image:url(<?php echo esc_url($image['image']['url']); ?>);"></div>
			</div>
			<figcaption class="grid--content__item-caption">
				<h3><?php echo esc_html($image['title']); ?></h3>
				<span><?php echo esc_html($image['subtitle']); ?></span>
			</figcaption>
		</figure>
		<?php $i++; endforeach; } ?>
	</section>
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
