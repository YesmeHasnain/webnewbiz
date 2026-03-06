<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use \Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

/**
 * Elementor gcounter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class Geoport_Counter extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve gcounter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'counter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve gcounter widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Counter', 'geoport' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve gcounter widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Retrieve the list of scripts the counter widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jquery-numerator' ];
	}


	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'counter' ];
	}

	/**
	 * Get widget Category.
	 *
	 * Retrieve the list of Category the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget Category.
	 */

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
	}

	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_counter',
			[
				'label' => __( 'Counter', 'geoport' ),
			]
		);

		$repeater = new Repeater();

        $repeater->add_control(
            'counter_image',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => __('Image', 'geoport'),
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'counter_number',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Number', 'geoport'),
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'prefix',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Number Prefix', 'geoport'),
                'default' => '',
                'placeholder' => __( 'Plus', 'geoport' ),
            ]
        );

        $repeater->add_control(
            'suffix',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Number Suffix', 'tpcore'),
                'default' => '',
            ]
        );

        $repeater->add_control(
            'duration',
            [
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Animation Duration', 'geoport' ),
				'default' => 2000,
				'min' => 100,
				'step' => 100,
            ]
        );

        $repeater->add_control(
            'thousand_separator',
            [
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Thousand Separator', 'geoport' ),
				'default' => 'yes',
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
            ]
        );


        $repeater->add_control(
            'thousand_separator_char',
            [
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Separator', 'geoport' ),
				'condition' => [
					'thousand_separator' => 'yes',
				],
				'options' => [
					''  => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
				],
            ]
        );

        $repeater->add_control(
            'title',
            [
				'label' => __( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Cool Number', 'geoport' ),
				'placeholder' => __( 'Cool Number', 'geoport' ),                
            ]
        );

        $this->add_control(
            'counter_items',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => esc_html__('Counter Item', 'geoport'),
                'default' => [
                    [
                        'counter_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'counter_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'counter_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'counter_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ]
            ]
        );


		$this->add_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'text-left' => [
						'title' => __( 'Left', 'geoport' ),
						'icon' => 'fa fa-align-left',
					],
					'text-center' => [
						'title' => __( 'Center', 'geoport' ),
						'icon' => 'fa fa-align-center',
					],
					'text-right' => [
						'title' => __( 'Right', 'geoport' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'text-center',
				'toggle' => true,
			]
		);

		$this->add_control(
			'counter_columns',
			[
				'label' => __( 'Counter Columns', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'6'  => __( 'Columns 2', 'geoport' ),
					'4'  => __( 'Columns 3', 'geoport' ),
					'3'  => __( 'Columns 4', 'geoport' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number',
			[
				'label' => __( 'Number', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-counter-number-wrapper span.count' => 'color: {{VALUE}};',
					'{{WRAPPER}} .geoport-counter span.elementor-counter-number-prefix' => 'color: {{VALUE}};',
					'{{WRAPPER}} .geoport-counter span.elementor-counter-number-suffix' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_number',
				'selector' => '{{WRAPPER}} .elementor-counter-number-wrapper span.count',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#bfc4df',
				'selectors' => [
					'{{WRAPPER}} .elementor-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'selector' => '{{WRAPPER}} .elementor-counter-title',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$counter_items = $settings['counter_items'];
		$text_align = $settings['text_align'];
		$counter_columns = $settings['counter_columns'];
	?>
	<div class="row counter-area">
	<script type="text/javascript">
	   jQuery(document).ready(function(){
			jQuery('.count').counterUp({
		      delay: 10,
		      time: 2000
		   });
	   });
	</script> 
	<?php
		foreach ( $counter_items as $key => $value ) {

		$this->add_render_attribute( 'counter', [
			'class' => 'elementor-counter-number',
			'data-duration' => $value['duration'],
			'data-to-value' => $value['counter_number'],
		] );

		if ( ! empty( $value['thousand_separator'] ) ) {
			$delimiter = empty( $value['thousand_separator_char'] ) ? ',' : $value['thousand_separator_char'];
			$this->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
		}

		$this->add_render_attribute( 'counter-title', 'class', 'elementor-counter-title' );
		?>

		<div class="col-lg-<?php echo esc_attr( $counter_columns ); ?> col-sm-6 single-counter-item">
			<div class="elementor-counter <?php echo esc_attr( $text_align ); ?> geoport-counter">
				<?php if ( !empty( $value['counter_image']['url'] ) ) { ?>
					<div class="counter-icon">
		         		<img src="<?php echo $value['counter_image']['url']; ?>" alt="icon">
		        	</div>
		      	<?php } ?>
				<div class="elementor-counter-number-wrapper">
					<span class="elementor-counter-number-prefix"><?php echo $value['prefix']; ?></span>
					<span class="count"><?php echo $value['counter_number']; ?></span>
					<span class="elementor-counter-number-suffix"><?php echo $value['suffix']; ?></span>
				</div>
				<?php if ( $value['title'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'counter-title' ); ?>><?php echo $value['title']; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php 
	}
}