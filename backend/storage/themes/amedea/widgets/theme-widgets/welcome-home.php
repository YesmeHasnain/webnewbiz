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
class amedea__welcome_home extends Widget_Base {

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
		return 'welcome_home';
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
		return esc_html__( 'Welcome Home', 'amedea' );
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
		return 'eicon-featured-image';
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
		return [ '' ];
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
		return [ 'amedea-category' , 'amedea-theme-widgets-category' ];
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
				'label' => esc_html__( 'Image', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
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
			'text_area_1',
			[
				'label' => esc_html__( 'Intro Text', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Are you ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_2',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Are<br/>you<br/>ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_3',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Are you ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content1',
			[
				'label' => esc_html__( 'Link', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button Text', 'amedea' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Button Text', 'amedea' ),
			]
		);

		$this->add_control(
			'button_url',
			[
				'label' => esc_html__( 'Button URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
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
		$image     = $settings['image'];
		//$style    = $settings['style'];
		
		
	?>
	<section class="content welcome__home">
		<div class="content__image" style="background-image:url('<?php echo esc_attr($settings['image']['url']); ?>')">
			<div class="content__inner">
				<div class="content__text">
					<p class="text__intro"><?php echo sprintf($settings['text_area_1']); ?></p>
					<h1 class="text__title"><?php echo sprintf($settings['text_area_2']); ?></h1>
				</div>
				<div class="content__text text__second">
					<p class="text__intro"><?php echo sprintf($settings['text_area_3']); ?></p>
					<?php if ( ! empty( $settings['button_url']['url'] ) ) { ?>
					<p class="text__link">
						<a href="<?php echo esc_url($settings['button_url']['url']); ?>" class="animsition-link link link--helike">
							<?php echo esc_html($settings['button_text']); ?>
						</a>
					</p>
					<?php } ?>
				</div>
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
	 *
	 * @access protected
	 */
	protected function content_template() {}
	
}
