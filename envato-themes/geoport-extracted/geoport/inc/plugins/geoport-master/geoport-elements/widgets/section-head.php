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

class Section_Head extends Widget_Base {

	public function get_name() {
		return 'section-head';
	}

	public function get_title() {
		return 'Section Head';   // title to show on geoport
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
				'label' => esc_html__( 'Section Header', 'geoport' ),   //section name for controler view
			]
		);

		$this->add_control(
			'section_head_version',
			[
				'label' => __( 'Section Head Version', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1'  => __( 'Style 1', 'geoport' ),
					'2'  => __( 'Style 2', 'geoport' ),
				],
			]
		);

		// Title 
		$this->add_control(
			'title1_text_heading',
			[
				'label' => __( 'Title', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'section_title1',
			[
				'label' => esc_html__( 'Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Title 1 Text Here', 'geoport' ),
			]
		);
		$this->add_control(
			'title1_animation',
			[
				'label' => __( 'Title Animation', 'geoport' ),
				'type' => Controls_Manager::ANIMATION,
				'label_block' => true,
			]
		);
		$this->add_control(
			'title1_anim_delay',
			[
				'label' => __( 'Animation Delay', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0.2s',
				'options' => ifinger_anim_delay(),
				'label_block' => true,
			]
		);

		// Title 2 
		$this->add_control(
			'title2_text_heading',
			[
				'label' => __( 'Title 2', 'geoport' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'section_title2',
			[
				'label' => esc_html__( 'Title 2', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Title 2 Text Here', 'geoport' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'title2_animation',
			[
				'label' => __( 'Title Animation', 'geoport' ),
				'type' => Controls_Manager::ANIMATION,
				'label_block' => true,
			]
		);
		$this->add_control(
			'title2_anim_delay',
			[
				'label' => __( 'Animation Delay', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0.2s',
				'options' => ifinger_anim_delay(),
				'label_block' => true,
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
			'sec_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'geoport' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Sub Title Text', 'geoport' ),
			]
		);
		$this->add_control(
			'subtitle_animation',
			[
				'label' => __( 'Sub Title Animation', 'geoport' ),
				'type' => Controls_Manager::ANIMATION,
				'label_block' => true,
			]
		);
		$this->add_control(
			'subtitle_anim_delay',
			[
				'label' => __( 'Animation Delay', 'geoport' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0.2s',
				'options' => ifinger_anim_delay(),
				'label_block' => true,
			]
		);
		$this->add_control(
			'background_icon_img',
			[
				'label' => esc_html__( 'Background Icon Image', 'geoport' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition' => [
					'section_head_version' => '3',
				],
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'geoport' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'geoport' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'geoport' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'geoport' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .section-title' => 'text-align: {{VALUE}};',
				],
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
					'{{WRAPPER}} .section-title h2 span.tl1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title1_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title h2 span.tl1',
			]
		);
		$this->end_controls_section();

		/* = Title 2 Styling
		========================================*/
		$this->start_controls_section(
			'title_line2_style',
			[
				'label' => esc_html__( 'Title Line 2 Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title2text_color',
			[
				'label' => __( 'Title 2 Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#001d67',
				'selectors' => [
					'{{WRAPPER}} .section-title h2 span.tl2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title2_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title h2 span.tl2',
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
					'{{WRAPPER}} .section-title > span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_title_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title > span',
			]
		);
		$this->add_control(
			'hide_line',
			[
				'label' => __( 'Hide Line', 'geoport' ),
				'type'  => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'geoport' ),
				'label_off' => __( 'Hide', 'geoport' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'subtitle_line_color',
			[
				'label' => __( 'Line Banckground Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#34ccff',
				'selectors' => [
					'{{WRAPPER}} .section-title > span.line::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .s-section-title > span.line::after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hide_line' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/* = Watermark Styling
		========================================*/
		$this->start_controls_section(
			'watermark_style',
			[
				'label' => esc_html__( 'Watermark Style', 'geoport' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'section_head_version' => '2',
				],
			]
		);
		$this->add_control(
			'watermark_text_color',
			[
				'label' => __( 'Text Color', 'geoport' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .section-title .bg-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'section_head_version' => '2',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'watermark_typography',
				'label' => __( 'Typography', 'geoport' ),
				'selector' => '{{WRAPPER}} .section-title .bg-title',
				'condition' => [
					'section_head_version' => '2',
				],
			]
		);

	}
	//end of control box 

	protected function render() {

		$settings = $this->get_settings_for_display();

		$settings = $this->get_settings();

		$section_head_version = $settings['section_head_version'];
		$hide_line = $settings['hide_line'];

		$t1_anim  = $settings['title1_animation'];
		$t1_delay = $settings['title1_anim_delay'];
		$t2_anim  = $settings['title2_animation'];
		$t2_delay = $settings['title2_anim_delay'];

		$st_anim  = $settings['subtitle_animation'];
		$st_delay = $settings['subtitle_anim_delay'];

		$sec_sub_title  = $settings['sec_sub_title'];
		$section_title1 = $settings['section_title1'];
		$section_title2 = $settings['section_title2'];
		
		$this->add_inline_editing_attributes( 'sec_sub_title', 'basic' );
		$this->add_inline_editing_attributes( 'section_title1', 'basic' );
		$this->add_inline_editing_attributes( 'section_title2', 'basic' );


		if (!empty($t1_anim)) {
			$title1_anim = 'class="wow '.esc_attr( $t1_anim ).'" data-wow-delay="'.esc_attr( $t1_delay ).'"';
		} else {
			$title1_anim = '';	
		} if (!empty( $t2_anim )) {
			$title2_anim = 'class="wow '.esc_attr( $t2_anim ).'" data-wow-delay="'.esc_attr( $t2_delay ).'"';
		} else {
			$title2_anim = '';
		} 

		if ( $hide_line == 'yes' ) {
			$this->add_render_attribute( 'sec_sub_title', 'class', 'line wow '.$st_anim );
		} else {
			$this->add_render_attribute( 'sec_sub_title', 'class', 'wow '.$st_anim );
		}
		if (!empty($st_anim)) {
			$subtitle_anim = ' data-wow-delay="'.esc_attr( $st_delay ).'"';
		} else {
			$subtitle_anim = '';
		}

		if ( $section_head_version == 2 ) {
			$version_class = 's-section-title';
		} else {
			$version_class = '';
		}

		?>
		<div class="section-title <?php echo esc_attr( $version_class ); ?>">
			<?php if (!empty($sec_sub_title)) { ?>
            <span <?php echo $this->get_render_attribute_string( 'sec_sub_title' ); ?><?php echo $subtitle_anim; ?>><?php echo esc_html( $sec_sub_title ); ?></span>
            <?php } if ( !empty( $section_title1 ) || !empty( $section_title2 ) ) { ?>
            <h2>
            	<?php if ( !empty( $section_title1 ) ) { ?>
            	<span class="tl1" <?php echo $this->get_render_attribute_string( 'section_title1' ); ?><?php echo $title1_anim; ?>><?php echo esc_html( $section_title1 ); ?></span>
            	<?php } if ( !empty( $section_title2 ) ) { ?>
            	<span class="tl2" <?php echo $this->get_render_attribute_string( 'section_title2' ); ?><?php echo $title2_anim; ?>><?php echo esc_html( $section_title2 ); ?></span>
            	<?php } ?>
            </h2>
            <?php if ( $section_head_version == 2 ) { ?>
            <h2 class="bg-title"><?php echo esc_html( $sec_sub_title ); ?></h2>
        	<?php } } ?>
      </div>
	<?php
	}
}