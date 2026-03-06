<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Style for header
 *
 *
 * @since 1.0.0
 */

class Banner_One extends Widget_Base {

	public function get_name() {
		return 'banner-one';
	}

	public function get_title() {
		return 'Banner';   // title to show on geoport
	}

	public function get_icon() {
		return 'fal fa-file-image';    // eicon-posts-ticker-> eicon ow asche icon to show on elelmentor
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
				'label' => esc_html__( 'Banner', 'geoport' ),   //section name for controler view
			]
		);

        $this->add_control(
            'tg_banner_image',
            [
                'label' => esc_html__('Choose Image', 'geoport'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'tg_title',
            [
                'label' => esc_html__('Title', 'geoport'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Transportation', 'geoport'),
                'placeholder' => esc_html__('Type Heading Text', 'geoport'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tg_sub_title',
            [
                'label' => esc_html__('Sub Title', 'geoport'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Continental', 'geoport'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tg_description',
            [
                'label' => esc_html__('Description', 'geoport'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Transport or transportation is the movement of humans, animals and goods from one location to another.', 'geoport'),
                'placeholder' => esc_html__('Type Description Text', 'geoport'),
                'label_block' => true,
            ]
        );
		

        $this->add_control(
            'tg_banner_btn_text',
            [
                'label' => esc_html__('Button One Text', 'tpcore'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Book a free consult', 'tpcore'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tg_banner_btn_link',
            [
                'label' => esc_html__('Button One link', 'tpcore'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'tpcore'),
                'show_external' => false,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tg_banner_btn2_text',
            [
                'label' => esc_html__('Button Two Text', 'tpcore'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Pricing plan', 'tpcore'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'tg_banner_btn2_link',
            [
                'label' => esc_html__('Button Two link', 'tpcore'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__('https://your-link.com', 'tpcore'),
                'show_external' => false,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
            ]
        );

		
		$this->end_controls_section();

		//End of a control box


        // style tab here
        $this->start_controls_section(
            '_section_style_content',
            [
                'label' => __('Title / Content', 'geoport'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        // Title
        $this->add_control(
            '_heading_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Title', 'geoport'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'geoport'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .slider-area.slider-bg .slider-content h2',
            ]
        );

        // Subtitle
        $this->add_control(
            '_heading_subtitle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Subtitle', 'geoport'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Text Color', 'geoport'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub-title',
                'selector' => '{{WRAPPER}} .slider-area.slider-bg .slider-content span',
            ]
        );


        // Description
        $this->add_control(
            '_heading_description',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __('Description', 'geoport'),
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'geoport'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description',
                'selector' => '{{WRAPPER}} .slider-area.slider-bg .slider-content p',
            ]
        );


        $this->end_controls_section();


        /* = Item Styling
        ========================================*/
        $this->start_controls_section(
            'contact_list_item_style',
            [
                'label' => esc_html__( 'Quote Button', 'geoport' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'contact_button_bg_color',
            [
                'label' => __( 'Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D00C27',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_text_color',
            [
                'label' => __( 'Text Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_border_color',
            [
                'label' => __( 'Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D00C27',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn' => 'border: 1px solid {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_hover_bg_color',
            [
                'label' => __( 'Hover Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn:hover' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_hover_text_color',
            [
                'label' => __( 'Text Hover Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_hover_border_color',
            [
                'label' => __( 'Hover Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5e14',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.red-btn:hover' => 'border: 1px solid {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();  


        /* = Item Styling
        ========================================*/
        $this->start_controls_section(
            'learn_button_style',
            [
                'label' => esc_html__( 'Learn More Button', 'geoport' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'learn_button_bg_color',
            [
                'label' => __( 'Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#cee2ff',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_text_color',
            [
                'label' => __( 'Text Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#001d67',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_border_color',
            [
                'label' => __( 'Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#cee2ff',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_bg_color',
            [
                'label' => __( 'Hover Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D00C27',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn:hover' => 'background: {{VALUE}} !important ;',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_text_color',
            [
                'label' => __( 'Hover Text Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn:hover' => 'color: {{VALUE}} !important ;',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_border_color',
            [
                'label' => __( 'Hover Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#D00C27',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.slider-bg .slider-content .slider-btn a.btn.gray-btn' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();



	}
	//end of control box 

	//to show on the fontend
	protected function render() {
		$settings = $this->get_settings_for_display();

		?>


            <!-- slider-area -->
            <section class="slider-area slider-bg d-flex align-items-center" style="background-image:url(<?php echo $settings['tg_banner_image']['url']; ?>)">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="slider-content">
                                <h2 class="wow fadeInUp" data-wow-delay="0.2s"><span><?php echo esc_html__( $settings['tg_sub_title'] ); ?></span> <?php echo esc_html__( $settings['tg_title'] ); ?></h2>
                                <p class="wow fadeInUp" data-wow-delay="0.4s"><?php echo esc_html__( $settings['tg_description'] ); ?></p>
                                <div class="slider-btn">
                                    <a href="#" class="btn red-btn wow fadeInLeft" data-wow-delay="0.6s"><i class="fal fa-paper-plane"></i>Request Quotes</a>
                                    <a href="#" class="btn gray-btn wow fadeInRight" data-wow-delay="0.6s"><i class="fal fa-angle-right"></i>Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- slider-area-end -->

	<?php 
	}	
}