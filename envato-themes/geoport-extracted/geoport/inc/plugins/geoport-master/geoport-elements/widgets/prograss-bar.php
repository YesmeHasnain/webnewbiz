<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Progress_Bar extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve progress widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'progress-bar';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve progress widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Progress Bar', 'geoport' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve progress widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-skill-bar';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the progress widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'geoport-elements' ];
	}

	/**
	 * Register progress widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_progress',
			[
				'label' => __( 'Progress Bar', 'geoport' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'geoport' ),
				'default' => __( 'My Skill', 'geoport' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'percent',
			[
				'label' => __( 'Percentage', 'geoport' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
					'unit' => '%',
				],
				'label_block' => true,
			]
		);

		$this->add_control( 'display_percentage', [
			'label' => __( 'Display Percentage', 'geoport' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'show',
			'options' => [
				'show' => __( 'Show', 'geoport' ),
				'hide' => __( 'Hide', 'geoport' ),
			],
		] );
		$this->end_controls_section();


		// Bar Style
		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => __( 'Progress Bar', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'bar_bg_color',
			[
				'label' => __( 'Background Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0e296c',
				'selectors' => [
					'{{WRAPPER}} .single-skill .progress' => 'background-color: {{VALUE}};',
				],
				'description' => __( 'Full bar background color', 'geoport' ),
			]
		);
		$this->add_control(
			'prograss_bar_persantage_color',
			[
				'label' => __( 'Animate bg Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-skill .geoport-progress-bar' => 'background-color: {{VALUE}};',
				],
				'description' => __( 'Animation bar background color', 'geoport' ),
			]
		);
		$this->end_controls_section();


		// title Style
		$this->start_controls_section(
			'prograss_title',
			[
				'label' => __( 'Title Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'terms' => [
						[
							'name' => 'title',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} label.prograss-title' => 'color: {{VALUE}};',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'title',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} label.prograss-title',
				'conditions' => [
					'terms' => [
						[
							'name' => 'title',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);
		$this->end_controls_section();


		// Persantage Style
		$this->start_controls_section(
			'prograss_persantage',
			[
				'label' => __( 'Persantage Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'display_percentage' => 'show',
				],
			]
		);
		$this->add_control(
			'persant_color',
			[
				'label'   => __( 'Color', 'geoport' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ff5e14',
				'selectors' => [
					'{{WRAPPER}} .single-skill .progress-bar span' => 'color: {{VALUE}};',
				],
				'condition' => [
					'display_percentage' => 'show',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'persant_typography',
				'selector' => '{{WRAPPER}} .single-skill .progress-bar span',
				'condition' => [
					'display_percentage' => 'show',
				],
			]
		);
		$this->end_controls_section();

	}

	/**
	 * Render progress widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$persantage = $settings['percent']['size'];

		$this->add_render_attribute( 'wrapper', 'class', 'single-skill' );
		$this->add_render_attribute( 'progress-percentage', 'class', 'geoport-progress-percentage' );

		$this->add_render_attribute( 'geoport-progress-bar', [
			'class' => 'progress-bar',
			'role' => 'progressbar',
			'data-wow-duration' => '1s',
			'data-wow-delay' => '.3s',
			'aria-valuenow' => $settings['percent']['size'],
			'aria-valuemin' => '0',
			'aria-valuemax' => '100',
		] );

		$this->add_render_attribute( 'geoport-progress-bar', [
			'class' => 'geoport-progress-bar wow slideInLeftS'
		] );

		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['title'] ) ) { ?>
         <label class="prograss-title"><?php echo $settings['title']; ?></label>
         <?php } ?>
         <div class="progress">
            <div <?php echo $this->get_render_attribute_string( 'geoport-progress-bar' ); ?> style="width: <?php echo esc_attr( $persantage ); ?>%;">
            	<?php if ( 'hide' !== $settings['display_percentage'] ) { ?>
            		<span <?php echo $this->get_render_attribute_string( 'progress-percentage' ); ?>>
            			<?php echo $settings['percent']['size']; ?>%
            		</span>
            	<?php } ?>
            </div>
         </div>
      </div>	
		<?php
	}
}