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
class amedea__welcome_project extends Widget_Base {

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
		return 'welcome_project';
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
		return esc_html__( 'Welcome Project', 'amedea' );
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
			'text_area_2',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Are<br/>you<br/>ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
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
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content1',
			[
				'label' => esc_html__( 'Second Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
		
		$this->add_control(
			'text_area_4',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Are you ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content2',
			[
				'label' => esc_html__( 'Third Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'text_area_5',
			[
				'label' => esc_html__( 'Client', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Client', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_6',
			[
				'label' => esc_html__( 'Client Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Krasota Iskusstva', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_7',
			[
				'label' => esc_html__( 'Service', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Service', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_8',
			[
				'label' => esc_html__( 'Service Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'UI & UX Design</br> UI & UX Design<br/> UI & UX Design', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_9',
			[
				'label' => esc_html__( 'Category', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Category', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_10',
			[
				'label' => esc_html__( 'Category Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Design <br/>Development', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_11',
			[
				'label' => esc_html__( 'Date', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Date', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);

		$this->add_control(
			'text_area_12',
			[
				'label' => esc_html__( 'Date Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '2023', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content3',
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
	<section class="content__page welcome__page intro__page">
		<div class="content__image" style="background-image:url('<?php echo esc_attr($settings['image']['url']); ?>')">
			<div class="content__inner">
				<div class="content__text">
					<h1 class="text__title"><?php echo sprintf($settings['text_area_2']); ?></h1>
					<span class="text__intro__second"><?php echo sprintf($settings['text_area_1']); ?></span>
				</div>
				
				<div class="content__text text__second">
					<div class="grid__left">
						<p class="text__intro"><?php echo sprintf($settings['text_area_3']); ?></p>
						<span class="text__link"><?php echo sprintf($settings['text_area_4']); ?></span>
						<?php if ( ! empty( $settings['button_url']['url'] ) ) { ?>
						<p class="text__link">
							<a href="<?php echo esc_url($settings['button_url']['url']); ?>" target="_blank" class="animsition-link line-link read--more">
								&#9675; <?php echo esc_html($settings['button_text']); ?>
							</a>
						</p>
					<?php } ?>
					</div>
				</div>
				
				<div class="content__text content__info">
					<div class="content__client">
					<h4><?php echo esc_attr($settings['text_area_5']); ?></h4>
					<span><?php echo esc_attr($settings['text_area_6']); ?></span>
					</div>
					<div class="content__services">
					<h4><?php echo esc_attr($settings['text_area_7']); ?></h4>
					<span><?php echo sprintf($settings['text_area_8']); ?></span>
					</div>
					<div class="content__category">
					<h4><?php echo esc_attr($settings['text_area_9']); ?></h4>
					<span><?php echo sprintf($settings['text_area_10']); ?></span>
					</div>
					<div class="content__date">
					<h4><?php echo esc_attr($settings['text_area_11']); ?></h4>
					<span><?php echo esc_attr($settings['text_area_12']); ?></span>
					</div>
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
