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

class amedea__push_gallery2 extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'push_gallery2';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Push Gallery 2', 'amedea' );
	}
	
	/**
	* Retrieve the widget icon.
	*
	* @since 1.0.0
	*
	* @return string Widget icon.
	*/

	public function get_icon() {
		return 'eicon-slider-album';
	}

	/**
	* Retrieve the list of scripts the widget depended on.
	*
	* Used to set scripts dependencies required to run the widget.
	*
	* @since 1.0.0
	*
	* @return array Widget scripts dependencies.
	*/

	public function get_script_depends() {
		return [ 'gsap' , 'flip' , 'imagesloaded' , 'amedea-push-gallery2' ];
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
		return [ 'push-gallery' ];
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
	* @return array Widget categories.
	*/

	public function get_categories() {
		return [ 'amedea-category' , 'amedea-push-gallery-category' ];
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
		
		$this->add_control(
			'sitetitle',
			[
				'label' => esc_html__( 'Site title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
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
			  'label'       => esc_html__('Version', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'image',
			  'label_block' => true,
			  'options' => array(
				'image' => esc_html__('Image', 'amedea'),
				'video' => esc_html__('Video', 'amedea'),
			  )
			)
		  );
							
		
		$this->add_control(
		  'images',
		  array(
			'label'     => sprintf('Items<br/><br/><small><strong>*Max 15 items</strong></small>', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'title' => esc_html__( 'Title', 'amedea' ),
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'version' => 'image',

					],
					[
						'title' => esc_html__( 'Title', 'amedea' ),
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'version' => 'image',

					],
				],
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
		$settings	= $this->get_settings_for_display();
		$images	    = $settings['images'];		
		$style	    = $settings['style'];
	?>

<section class="push-gallery--container <?php echo esc_attr($settings['style']); ?>">
	<div class="push-gallery--content">
		<div class="push-gallery--grid push-gallery--grid--rounded">
			<?php $i = 1; if (is_array($images) || is_object($images)) { foreach($images as $key => $image): ?>
			<?php if ( 'video' == $image['version'] ) { ?>
			<div class="push-gallery--grid__item push-gallery--position-<?php echo esc_html($i); ?>">
				<video class="push-gallery--grid__img push-gallery--grid__video" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
					<source src="<?php echo esc_url($image['image']['url']); ?>" type="video/mp4">
				</video>
			</div>
			<?php } else { ?>
			<div class="push-gallery--grid__item push-gallery--position-<?php echo esc_html($i); ?>">
				<div class="push-gallery--grid__img" style="background-image:url(<?php echo esc_html($image['image']['url']); ?>)"></div>
			</div>
			<?php } ?>
			<?php $i++; endforeach; } ?>	
		</div>
		<div class="push-gallery--title">
			<?php echo esc_html($settings['sitetitle']); ?>
		</div>
	</div>
	<div class="push-gallery--fullscreen push-gallery--fullscreen--rounded"></div>
</section>

	<?php
	
	}

	/**
	* Render the widget output in the editor.
	*
	* Written as a Backbone JavaScript template and used to generate the live preview.
	*
	* @since 1.0.0
	*/

	protected function content_template() {}
}