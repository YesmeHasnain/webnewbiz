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

class amedea__animated_intro3 extends Widget_Base {

	/**
	* Retrieve the widget name.
	*
	* @since 1.0.0
	*
	* @return string Widget name.
	*/

	public function get_name() {
		return 'animated_intro3';
	}
	
	/**
	* Retrieve the widget title.
	*
	* @since 1.0.0
	*
	* @return string Widget title.
	*/

	public function get_title() {
		return esc_html__( 'Animated Intro 3', 'amedea' );
	}
	
	/**
	* Retrieve the widget icon.
	*
	* @since 1.0.0
	*
	* @return string Widget icon.
	*/

	public function get_icon() {
		return 'eicon-lottie';
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
		return [ 'gsap' , 'imagesloaded' , 'amedea-animated-intro3' ];
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
		return [ 'animated-intro' ];
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
		return [ 'amedea-category' , 'amedea-animated-intro-category' ];
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
				'label' => esc_html__( 'Configuration', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Subtitle', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
			
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'title2',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
			
		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Media', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'placeholder' => esc_html__( 'Required*', 'amedea' ),
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
		
		$repeater->add_control(
			'button_url',
			[
				'label' => esc_html__( 'Button URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
	
		$this->add_control(
		  'images',
		  array(
			'label'     => esc_html__('Content', 'amedea'),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default' => [
					[
						'title2' => esc_html__( 'Item 1', 'amedea' ),
						'version' => 'image',
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'button_url' => '',
					],
					[
						'title2' => esc_html__( 'Item 1', 'amedea' ),
						'version' => 'image',
						'image' => \Elementor\Utils::get_placeholder_image_src(),
						'button_url' => '',
					],
				],
			'title_field' => '<span>{{ title2 }}</span>',
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
<section class="animated-intro-container <?php echo esc_attr($settings['style']); ?>">
	<div data-debug>
		<div></div>
	</div>
	<div class="animated-intro-content">
		<div class="animated-intro-scene">
			<div class="animated-intro-group">
			<?php $i = 1; if (is_array($images) || is_object($images)) { foreach($images as $key => $image) :  ?>
				<div class="animated-intro-card">
					<?php if ( 'video' == $image['version'] ) { ?>
					<div class="animated-intro-card__img">
						<video class="animated-intro__video" xmlns="https://www.w3.org/1999/xhtml" width="auto" height="auto" autoplay muted loop>
							<source src="<?php echo esc_url($image['image']['url']); ?>" type="video/mp4">
						</video>
					</div>
					<?php } else { ?>
					<div class="animated-intro-card__img" style="background-image: url(<?php echo esc_url($image['image']['url']); ?>)">
						<?php if ( ! empty( $image['button_url']['url'] ) ) { ?><a href="<?php echo esc_url($image['button_url']['url']); ?>"><?php } ?>
							<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" />
						<?php if ( ! empty( $image['button_url']['url'] ) ) { ?></a><?php } ?>
					</div>
					<?php } ?>
				</div>
			<?php $i++; endforeach; } ?>
          </div>
		</div>
		<div class="animated-intro-headings">
			<h1 class="animated-intro-headings__main"><?php echo esc_html($settings['title']); ?></h1>
			<h4 class="animated-intro-headings_subtitle"><?php echo esc_html($settings['subtitle']); ?></h4>
		</div>
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
	*/

	protected function content_template() {}
}