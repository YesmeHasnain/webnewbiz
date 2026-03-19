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
class amedea__tilt_image extends Widget_Base {

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
		return 'tilt_image';
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
		return esc_html__( 'Hover Tilt Image', 'amedea' );
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
		return 'eicon-image-rollover';
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
		return [ 'imagesloaded' , 'anime' , 'amedea-tilt' ];
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
		return [ 'image-hover' ];
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
		return [ 'amedea-category' , 'amedea-grid-image-hover-category' ];
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
			'section_content',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content Text', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		$this->add_control(
			'content_hover',
			[
				'label' => esc_html__( 'Content Sub-Text', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&nbsp;', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
 	    $this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'amedea' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			]
		);
		
		
		$this->add_control(
			'link_url',
			[
				'label' => esc_html__( 'URL', 'amedea' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content1',
			[
				'label' => esc_html__( 'Effect', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'effect',
			array(
			  'label'       => esc_html__('Choose Tilt Effect', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'tilter--1',
			  'label_block' => true,
			  'options' => array(
				'tilter--1' => esc_html__('Tilt 1', 'amedea'),
				'tilter--2' => esc_html__('Tilt 2', 'amedea'),
				'tilter--3' => esc_html__('Tilt 3', 'amedea'),
				'tilter--4' => esc_html__('Tilt 4', 'amedea'),
				'tilter--5' => esc_html__('Tilt 5', 'amedea'),
				'tilter--6' => esc_html__('Tilt 6', 'amedea'),
				'tilter--7' => esc_html__('Tilt 7', 'amedea'),
				'tilter--8' => esc_html__('Tilt 8', 'amedea'),
				
			  )
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
		$effect    = $settings['effect'];
	?>
	<div class="tilt--image">
		<a href="<?php echo esc_url($settings['link_url']['url']); ?>" class="tilter <?php echo esc_html($settings['effect']); ?>">
			<figure class="tilter__figure">
				<img class="tilter__image" src="<?php echo esc_url($settings['image']['url']); ?>" alt="img01" />
				<div class="tilter__deco tilter__deco--shine"><div></div></div>
				<figcaption class="tilter__caption">
					<h3 class="tilter__title"><?php echo sprintf(wp_kses_post($settings['content'])); ?></h3>
					<p class="tilter__description"><?php echo esc_html($settings['content_hover']); ?></p>
				</figcaption>
				<?php if ($effect !== 'tilter--3') { ?>
				<svg class="tilter__deco tilter__deco--lines" viewBox="0 0 300 415">
					<path d="M20.5,20.5h260v375h-260V20.5z" />
				</svg>
				<?php } ?>
			</figure>
		</a>
	</div>
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
