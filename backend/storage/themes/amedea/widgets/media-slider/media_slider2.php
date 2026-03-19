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
class amedea__media_slider2 extends Widget_Base {

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
		return 'media_slider2';
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
		return esc_html__( 'Media Slider', 'amedea' );
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
		return 'eicon-slider-device';
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
		return [ 'gsap' , 'observer' , 'imagesloaded' , 'amedea-media-slider2' ];
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
		return [ 'media-slider' ];
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
		return [ 'amedea-category' , 'amedea-media-reveal-hover-category' ];
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
		  'image',
			[
				'label' => __( 'Media', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		$repeater->add_control(
			'version',
			array(
			  'label'       => esc_html__('Choose version : Media', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'video' => esc_html__('Video', 'amedea'),
				'image' => esc_html__('Image', 'amedea'),
			  )
			)
		);
		
		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
		  'images',
		  array(
			'label'     => esc_html__('Items', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'title' => esc_html__( 'Item 1', 'amedea' ),
						'version' => 'image',
						'image' => \Elementor\Utils::get_placeholder_image_src(),
					],
					[
						'title' => esc_html__( 'Item 2', 'amedea' ),
						'version' => 'image',
						'image' =>  \Elementor\Utils::get_placeholder_image_src(),
					],
				],
			'title_field' => '<span>{{ title }}</span>',
		  )
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content2',
			[
				'label' => esc_html__( 'Management', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'scroll',
			[
				'label' => esc_html__( 'Text for : Scroll', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'scroll', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'drag',
			[
				'label' => esc_html__( 'Text for : Drag', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'drag', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
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
	<style>.ms--slides-nav::before {content: '<?php echo esc_html($settings['scroll']); ?> / <?php echo esc_html($settings['drag']); ?>';}</style>
	<section class="ms--container <?php echo esc_attr($settings['style']); ?>">
		<div class="ms--navigation">
			<nav class="ms--slides-nav">
				<button class="ms--slides-nav__item ms--slides-nav__item--prev">&larr;</button>
				<button class="ms--slides-nav__item ms--slides-nav__item--next">&rarr;</button>
			</nav>
		</div>
		<div class="ms--slides">
			<?php $i = 0; if (is_array($images) || is_object($images)) { foreach($images as $key => $image):  ?>
			<?php if ($image['version'] == "video" ) { ?>
			<div class="ms--slide">
				<div class="ms--slide__img">
					<video class="ms--slider__video" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
						<source src="<?php echo esc_url($image['image']['url']); ?>" type="video/mp4">
					</video>
				</div>
			</div>
			<?php } else { ?>
			<div class="ms--slide">
				<div class="ms--slide__img" style="background-image:url(<?php echo esc_html($image['image']['url']); ?>)"></div>
			</div>
			<?php } ?>
			<?php $i++; endforeach; } ?>
			<div class="ms--deco"></div>
			<div class="ms--deco"></div>
		</div>
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
