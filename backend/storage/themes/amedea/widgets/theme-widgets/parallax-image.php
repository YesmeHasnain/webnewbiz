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
class amedea__parallax_image extends Widget_Base {

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
		return 'parallax_image';
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
		return esc_html__( 'Parallax Image(s)', 'amedea' );
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
		return [ ];
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
				'label' => esc_html__( 'Content', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		  
		$this->add_control(
			'content_text',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Are you ready?', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'content_size',
			array(
			  'label'       => esc_html__('Heading Style/Size', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'small',
			  'label_block' => true,
			  'options' => array(
				'small' => esc_html__('Small', 'amedea'),
				'large' => esc_html__('Large', 'amedea'),
				'light' => esc_html__('Light', 'amedea'),
			  )
			)
		  );

		$this->add_control(
			'content_position',
			array(
			  'label'       => esc_html__('Content Position', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'center',
			  'label_block' => true,
			  'options' => array(
				'top' => esc_html__('Top', 'amedea'),
				'center' => esc_html__('Center', 'amedea'),
			  )
			)
		  );	
		  
		  $this->add_control(
			'content_text2',
			[
				'label' => esc_html__( 'Top Position Content Subtitle', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
				
		$this->add_control( 'image', [
			'label' => __( 'Image', 'amedea' ),
			'type' => \Elementor\Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );
		
		$this->add_control(
			'position',
			array(
			  'label'       => esc_html__('Image Position', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'cover',
			  'label_block' => true,
			  'options' => array(
				'cover' => esc_html__('Cover', 'amedea'),
				'contain' => esc_html__('Contain', 'amedea'),
			  )
			)
		  );	

		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_content2',
			[
				'label' => esc_html__( 'Multiple Images', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'gallery',
			[
				'label' => esc_html__( 'Add Images', 'amedea' ),
				'type' => \Elementor\Controls_Manager::GALLERY,
				'show_label' => false,
				'default' => [],
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
		$image = $settings['image'];
		$style = $settings['style'];
		$position = $settings['position'];
		$content_position = $settings['content_position'];
		$content_size = $settings['content_size'];
		
		
	?>
	<?php switch ($content_position) {case 'center': default: ?>
	<main class="parallax <?php echo esc_html($settings['style']); ?>">
		<div class="bg bg--<?php switch ($position) {case 'cover': default: echo "cover"; break; case 'contain': echo "contain"; break;} ?>" style="background-image: url(<?php echo esc_html($settings['image']['url']); ?>)">
			<?php if ( ! empty( $settings['content_text'] ) ) { ?><h1 class="absolute-h"><?php echo esc_html($settings['content_text']); ?></h1><?php } ?>
			<?php foreach ( $settings['gallery'] as $image ) {
					echo '<img class="bg__img" src="' .($image['url']) . '" alt="." />';
				} ?>
		</div>
	</main>
	<?php break; case 'top': ?>
	<main class="parallax <?php echo esc_html($settings['style']); ?>">
		<div class="header-title">
			<?php if ( ! empty( $settings['content_text'] ) ) { ?>
					<h1 class="<?php echo esc_html($content_size); ?>">
					<?php echo sprintf($settings['content_text']); ?>
					<?php if ( ! empty( $settings['content_text2'] ) ) { ?><span><?php echo esc_html($settings['content_text2']); ?></span><?php } ?>
					</h1>
				<?php } ?>
		</div>
		<div class="bg bg--<?php switch ($position) {case 'cover': default: echo "cover"; break; case 'contain': echo "contain"; break;} ?>" style="background-image: url(<?php echo esc_html($settings['image']['url']); ?>)">
			<?php foreach ( $settings['gallery'] as $image ) {
					echo '<img class="bg__img" src="' .($image['url']) . '" alt="." />';
				} ?>
		</div>
	</main>
	<?php break;} ?>
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
