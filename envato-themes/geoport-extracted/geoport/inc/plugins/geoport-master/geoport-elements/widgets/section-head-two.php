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

class Section_Head_Two extends Widget_Base {

	public function get_name() {
		return 'section-head-two';
	}

	public function get_title() {
		return 'Section Head 2';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-heading';    //   eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
	}

	public function get_categories() {
		return [ 'geoport-elements' ];    // category of the widget
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/

	protected function register_controls() {
		
		//start of a control box
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Section Head Two', 'geoport' ),   //section name for controler view
			]
		);

		// Title 
		$this->add_control(
			'title1_text_heading',
			[
				'label' => __( 'Title Settings', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'style3_section_title1',
			[
				'label' => esc_html__( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Title 1 Text Here', 'geoport' ),
			]
		);
		
		// Sub Title
		$this->add_control(
			'subtitle_text_heading',
			[
				'label' => __( 'Sub Title Settings', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'style3_sec_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Sub Title Text', 'geoport' ),
			]
		);

		// Description Settings
		$this->add_control(
			'hstyle3_text_heading',
			[
				'label' => __( 'Description Settings', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'style3_text_description',
			[
				'label' => esc_html__( 'Description Text', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Sub Title Text', 'geoport' ),
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text-left'    => [
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

		//End of a control box

		/* = Add Item Styling
		========================================*/

		$this->start_controls_section(
			'title_line1_style',
			[
				'label' => esc_html__( 'Title Line 1 Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		/* = Title 1 Styling
		========================================*/
		$this->add_control(
			'title1_text_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .section-title-three h2 span.tl1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title1_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title-three h2 span.tl1',
			]
		);
		$this->end_controls_section();


		/* = Sub Title Styling
		========================================*/
		$this->start_controls_section(
			'subtitle_style',
			[
				'label' => esc_html__( 'Sub Title Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'sub_title_text_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .section-title-three > span.style3_subtitle' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title-three > span.style3_subtitle',
			]
		);

		$this->end_controls_section();


		/* = Description Styling
		========================================*/
		$this->start_controls_section(
			'style3description_style',
			[
				'label' => esc_html__( 'Description Text Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'style3description_text_color',
			[
				'label' => __( 'Description Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .section-title-three > p span.tl2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style3description_typography',
				'label' => __( 'Description Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title-three > p span.tl2',
			]
		);

		$this->end_controls_section();

	}
	//end of control box 

	protected function render() {

		$settings = $this->get_settings_for_display();
		$settings = $this->get_settings();

		$text_align  = $settings['text_align'];

		$style3_sec_sub_title  = $settings['style3_sec_sub_title'];
		$style3_section_title1 = $settings['style3_section_title1'];
		$style3_text_description = $settings['style3_text_description'];
		
		$this->add_inline_editing_attributes( 'style3_sec_sub_title', 'basic' );
		$this->add_inline_editing_attributes( 'style3_section_title1', 'basic' );
		$this->add_inline_editing_attributes( 'style3_text_description', 'basic' );

		?>

		<div class="section-title-three <?php echo esc_attr( $text_align ); ?>">
			<?php if (!empty($style3_sec_sub_title)) { ?>
            	<span class="style3_subtitle" <?php echo $this->get_render_attribute_string( 'style3_sec_sub_title' ); ?>><?php echo esc_html( $style3_sec_sub_title ); ?></span>
            <?php } ?> 
            <?php if ( !empty( $style3_section_title1 ) ) { ?>
	            <h2>
	            	<?php if ( !empty( $style3_section_title1 ) ) { ?>
	            	<span class="tl1" <?php echo $this->get_render_attribute_string( 'style3_section_title1' ); ?>><?php echo esc_html( $style3_section_title1 ); ?></span>
	            	<?php } ?>
	            </h2>
            <?php } ?>
            <?php if ( !empty( $style3_text_description ) ) { ?>
	            <p>
	            	<?php if ( !empty( $style3_text_description ) ) { ?>
	            	<span class="tl2" <?php echo $this->get_render_attribute_string( 'style3_text_description' ); ?>><?php echo esc_html( $style3_text_description ); ?></span>
	            	<?php } ?>
	            </p>
            <?php } ?>
      </div>
	<?php
	}
}