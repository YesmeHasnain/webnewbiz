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
class amedea__heading_details extends Widget_Base {

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
		return 'heading_details';
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
		return esc_html__( 'Heading Details', 'amedea' );
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
		return 'eicon-animation-text';
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
		return [ 'scroll-trigger' ];
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
				'label' => esc_html__( 'Heading Details', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Heading', 'amedea' ),
				'placeholder' => esc_html__( 'Type your title here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras fringilla ipsum id nibh lacinia pretium.', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->add_control(
			'content2',
			[
				'label' => esc_html__( 'Content Second', 'amedea' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'amedea' ),
				'placeholder' => esc_html__( 'Type your content here', 'amedea' ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content2',
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
		
		$this->start_controls_section(
			'section_content3',
			[
				'label' => esc_html__( 'Heading Size', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'size',
			array(
			  'label'       => esc_html__('Size of the Heading', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'medium',
			  'label_block' => true,
			  'options' => array(
				'small' => esc_html__('Small', 'amedea'),
				'medium' => esc_html__('Medium', 'amedea'),
				'large' => esc_html__('Large', 'amedea'),
				'extralarge' => esc_html__('Extra Large', 'amedea'),
			  )
			)
		  );
		  
		  $this->add_control(
			'size2',
			array(
			  'label'       => esc_html__('Size of the Sub-Heading', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'small',
			  'label_block' => true,
			  'options' => array(
				'small' => esc_html__('Small', 'amedea'),
				'medium' => esc_html__('Medium', 'amedea'),
				'large' => esc_html__('Large', 'amedea'),
			  )
			)
		  );	

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content4',
			[
				'label' => esc_html__( 'Position', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'position',
			array(
			  'label'       => esc_html__('Position of the DIV', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'center',
			  'label_block' => true,
			  'options' => array(
				'center' => esc_html__('Center', 'amedea'),
				'left' => esc_html__('Left', 'amedea'),
				'right' => esc_html__('Right', 'amedea'),
			  )
			)
		  );	
		  
		  $this->add_control(
			'align',
			array(
			  'label'       => esc_html__('Align', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'horizontal',
			  'label_block' => true,
			  'options' => array(
				'horizontal' => esc_html__('Horizontal', 'amedea'),
				'vertical' => esc_html__('Vertical', 'amedea'),
			  )
			)
		  );
		  
		  $this->add_control(
			'margin',
			array(
			  'label'       => esc_html__('Margin', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => '20vh-auto-5vh',
			  'label_block' => true,
			  'options' => array(
				'20vh-auto-5vh' => esc_html__('20vh auto 5vh', 'amedea'),
				'20vh-auto-20vh' => esc_html__('20vh auto 20vh', 'amedea'),
				'20vh-20vh-5vh-20vh' => esc_html__('20vh 20vh 5vh 20vh', 'amedea'),
				'custom' => esc_html__('Custom', 'amedea'),
			  )
			)
		  );	

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content5',
			[
				'label' => esc_html__( 'Color Management', 'amedea' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'colorchange',
			array(
			  'label'       => esc_html__('On-Scroll Color Change', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::SELECT,
			  'default'     => 'scroll-color-disabled',
			  'label_block' => true,
			  'options' => array(
				'scroll-color' => esc_html__('Enabled', 'amedea'),
				'scroll-color-disabled' => esc_html__('Disabled', 'amedea'),
			  )
			)
		  );	
		 
		 $this->add_control(
			'beforecolor',
			array(
			  'label'       => esc_html__('Color Before Scroll', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
			  )
		);

		 $this->add_control(
			'aftercolor',
			array(
			  'label'       => esc_html__('Color After Scroll', 'amedea'),
			  'type'        => \Elementor\Controls_Manager::COLOR
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
		$style    = $settings['style'];
		$position    = $settings['position'];
		$align    = $settings['align'];	
		$size    = $settings['size'];	
		$size2    = $settings['size2'];	
	?>
	<section class="heading--details <?php echo esc_html($settings['style']); ?> <?php echo esc_html($settings['colorchange']); ?>"
	<?php if ( 'scroll-color' == $settings['colorchange'] ) { echo 'data-color-first="'.$settings['beforecolor'].'" data-color-second="'.$settings['aftercolor'].'"'; } ?>>
	<?php
    switch ($align) {
    case 'horizontal': default: ?>
	<div class="project project--horizontal project--details project--<?php echo esc_html($settings['position']); ?> project--margin--<?php echo esc_html($settings['margin']); ?>">
		<?php if ( ! empty( $settings['title'] ) ) { ?>
		<span class="project__label project__label--default heading__small"><?php if ( ! empty( $settings['title'] ) ) { ?><i class="wrapper--heading"></i><?php } ?><?php echo esc_html($settings['title']); ?></span>
		<?php } ?>
		<div class="content__in">
		<h1 class="heading__details size__<?php echo esc_html($settings['size']); ?>"><?php echo sprintf($settings['content']); ?></h1>
		<?php if ( ! empty( $settings['size2'] ) ) { ?>
		<span class="sub--size subsize__<?php echo esc_html($settings['size2']); ?>"><?php echo sprintf($settings['content2']); ?></span>
		<?php } ?>
		<?php if ( ! empty( $settings['button_url']['url'] ) ) { ?>
				<p class="text__link">
					<a href="<?php echo esc_url($settings['button_url']['url']); ?>" class="animsition-link line-link read--more" data-animsition-out-class="fade-out-down" data-animsition-out-duration="1000">
						<?php echo esc_html($settings['button_text']); ?>
					</a>
				</p>
				<?php } ?>
		</div>
	</div>
	<?php
    break;
    case 'vertical': ?>	
	<div class="project project--vertical project--details project--<?php echo esc_html($settings['position']); ?> project--margin--<?php echo esc_html($settings['margin']); ?>">
		<div class="content__in text__<?php echo esc_html($settings['position']); ?>">
		<?php if ( ! empty( $settings['title'] ) ) { ?>
		<span class="project__label project__label--default heading__small"><?php if ( ! empty( $settings['title'] ) ) { ?><i class="wrapper--heading"></i><?php } ?><?php echo esc_html($settings['title']); ?></span>
		<?php } ?>
		<h1 class="heading__details size__<?php echo esc_html($settings['size']); ?>"><?php echo sprintf($settings['content']); ?></h1>
		<?php if ( ! empty( $settings['size2'] ) ) { ?>
		<span class="sub--size subsize__<?php echo esc_html($settings['size2']); ?>"><?php echo sprintf($settings['content2']); ?></span>
		<?php } ?>
		<?php if ( ! empty( $settings['button_url']['url'] ) ) { ?>
				<p class="text__link">
					<a href="<?php echo esc_url($settings['button_url']['url']); ?>" class="animsition-link line-link read--more" data-animsition-out-class="fade-out-down" data-animsition-out-duration="1000">
						&#9675; <?php echo esc_html($settings['button_text']); ?>
					</a>
				</p>
				<?php } ?>
		</div>
	</div>
	<?php break; } ?>
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
