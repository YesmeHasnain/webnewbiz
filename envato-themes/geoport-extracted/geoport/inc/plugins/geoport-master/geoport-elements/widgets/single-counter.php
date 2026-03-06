<?php
namespace Geoport\Widgets;

use Elementor\Utils;
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
class Single_Counter extends Widget_Base {

	public function get_name() {
		return 'single-counter';
	}

	public function get_title() {
		return 'Single Counter'; // title to show on elementor
	}

	public function get_icon() {
		return 'fal fa-sort-numeric-up'; // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
	}

	public function get_categories() {
		return [ 'geoport-elements' ]; // category of the widget
	}

   public function get_script_depends() {
		return [ 'geoport-elementor-editor' ];
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
				'label' => __( 'Single Counter', 'geoport' ),
			]
		);

		$this->add_control(
			'counter_version',
			[
				'label' => __( 'Section Head Version', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => __( 'Style 1', 'geoport' ),
					'2'  => __( 'Style 2', 'geoport' ),
					'3'  => __( 'Style 3', 'geoport' ),
				],
			]
		);

		$this->add_control(
			'counter_image',
			[
				'label' => __( 'Image', 'geoport' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'counter_number',
			[
				'label' => __( 'Number', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' => 100,
				'label_block' => true,
			]
		);
		$this->add_control(
			'prefix',
			[
				'label' => __( 'Number Prefix', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 1,
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'Plus', 'geoport' ),
			]
		);

		$this->add_control(
			'counter_title',
			[
				'label' => __( 'Counter Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' =>  __( 'Counter Title', 'geoport' ),
				'label_block' => true,
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
					'{{WRAPPER}} .only-counter span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_number',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .only-counter span',
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
					'{{WRAPPER}} .about-exp span.counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .about-exp span.counter-title',
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
		$counter_image = $settings['counter_image']['url'];
		$counter_number = $settings['counter_number'];
		$prefix = $settings['prefix'];
		$suffix = $settings['suffix'];
		$counter_title = $settings['counter_title'];
		$text_align = $settings['text_align'];
		$counter_version = $settings['counter_version'];
	?>

	<script type="text/javascript">
	   jQuery(document).ready(function(){
			jQuery(".counte").counterUp({
		      delay: 10,
		      time: 2000
		   });
	   });
	</script> 

	<?php
		$this->add_render_attribute( 'counter-title', 'class', 'counter-title' );

		if ( $counter_version == '2' ) {
	?>
	<div class="s-about-img <?php echo esc_attr( $text_align ); ?>">
		<div class="about-exp" data-background="<?php echo $counter_image; ?>" style="background-image: url(<?php echo $counter_image; ?>);">
			<h3 class="only-counter">
				<span class="ifinger-prefix"><?php echo $prefix; ?></span>
				<span class="count-style counte"><?php echo $counter_number; ?></span>
				<span class="ifinger-suffix"><?php echo $suffix; ?></span>
			</h3>
			<?php if ( $counter_title ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'counter-title' ); ?>>
				<?php echo $counter_title; ?>
			</span>
			<?php endif; ?>
		</div>
	</div>
	<?php } elseif ( $counter_version == '3' ) { ?>
	<div class="about-img">
        <img src="<?php echo esc_url( $counter_image ); ?>" alt="img">
        <div class="about-exp">
	    	<div class="about-exp-count">
	    		<h3 class="only-counter">
					<span class="ifinger-prefix"><?php echo $prefix; ?></span>
					<span class="count-style counte"><?php echo $counter_number; ?></span>
					<span class="ifinger-suffix"><?php echo $suffix; ?></span>
				</h3>
	    	</div>
	    	<?php if ( $counter_title ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'counter-title' ); ?>>
				<?php echo $counter_title; ?>
			</span>
			<?php endif; ?>
		</div>
    </div>
	<?php } else { ?>

	<div class="about-exp <?php echo esc_attr( $text_align ); ?>">
		<?php if ( !empty( $counter_image ) ) { ?>
			<div class="counter-icon">
	         	<img src="<?php echo $counter_image; ?>" alt="icon">
	        </div>
   		<?php } ?>
		<h3 class="only-counter">
			<span class="ifinger-prefix"><?php echo $prefix; ?></span>
			<span class="count-style counte"><?php echo $counter_number; ?></span>
			<span class="ifinger-suffix"><?php echo $suffix; ?></span>
		</h3>
		<?php if ( $counter_title ) : ?>
		<span <?php echo $this->get_render_attribute_string( 'counter-title' ); ?>>
			<?php echo $counter_title; ?>
		</span>
		<?php endif; ?>
	</div>

	<?php }
	}
}