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
class amedea__contact_default extends Widget_Base {

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
		return 'contact_default';
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
		return esc_html__( 'Contact Default', 'amedea' );
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
		return 'eicon-email-field';
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
				'label' => esc_html__( 'Marquee', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Marquee Text', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Wanna spit it out? We will get back to you as soon as possible, we promise!', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
				
		$this->start_controls_section(
			'section_content1',
			[
				'label' => esc_html__( 'Let&#39;s talk', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'text_area_1',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Let&#39;s talk', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_2',
			[
				'label' => esc_html__( 'Text area', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Wanna spit it out? We will get back to you as soon as possible, we promise!', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content2',
			[
				'label' => esc_html__( 'Find Us', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'text_area_3',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Find Us', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_4',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Kounicova 51, Brno</br>Czech Republic', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content3',
			[
				'label' => esc_html__( 'Say Hello', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'text_area_5',
			[
				'label' => esc_html__( 'Say Hello', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Say Hello', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'text_area_6',
			[
				'label' => esc_html__( 'Heading', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'email@website.com<br/>+420 720 000 000', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content4',
			[
				'label' => esc_html__( 'Google Map', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'View on map', 'amedea' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'View on map', 'amedea' ),
			]
		);

		$this->add_control(
			'button_url',
			[
				'label' => esc_html__( 'GoogleMap URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
		
		$this->end_controls_section();
			
		$this->start_controls_section(
			'section_content5',
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
		$style    = $settings['style'];

	?>
	<section class="contact__container <?php echo esc_html($settings['style']); ?>">
		<div class="contact__page">
			<div class="content__inner">
	
				<div class="contact__default">
					<div class="text__inner inner_marquee">
						<div class="marquee__container marquee__contact">
							<div class="marquee">
								<div class="marquee__inner" aria-hidden="true">
									<span><?php echo esc_html($settings['content']); ?></span>
									<span><?php echo esc_html($settings['content']); ?></span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="text__inner">
						<p><?php echo sprintf($settings['text_area_2']); ?></p>
					</div>
					
					<div class="text__inner_second">
						<h5><?php echo esc_html($settings['text_area_3']); ?></h5>
						<p><?php echo sprintf($settings['text_area_4']); ?></p>
					</div>
					<div class="text__inner_third">
						<h5><?php echo esc_html($settings['text_area_5']); ?></h5>
						<p><?php echo sprintf($settings['text_area_6']); ?></p>
					</div>
					<?php if ( ! empty( $settings['button_url']['url'] ) ) { ?>
					<div class="text__inner_last">
						<p class="text__link">
							<a href="<?php echo esc_url($settings['button_url']['url']); ?>" target="_blank" class="">
								<?php echo esc_html($settings['button_text']); ?>
							</a>
						</p>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ( ! empty( $settings['image']['url'] ) ) { ?>
		<div class="contact__image">
			<img src="<?php echo esc_attr($settings['image']['url']); ?>" alt="" />
		</div>
		<?php } ?>
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
