<?php
namespace Geoport\Widgets;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Background;


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
class Slider_Three extends Widget_Base {

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
        return 'slider-three';
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
        return __( 'Slider Three', 'geoport' );
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
        return 'fal fa-sliders-h';
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
        return [ 'Slider' ];
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
        return [ 'slider-init-js' ];
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
    protected function register_controls()
    {

        // Slider
        $this->start_controls_section(
            'tg_slider_area',
            [
                'label' => esc_html__('Slider Area', 'geoport'),
            ]
        );

        $slider = new \Elementor\Repeater();

        $slider->add_control(
            'slider_img',
            [
                'label' => esc_html__('Choose Image', 'geoport'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $slider->add_control(
            'slider_title',
            [
                'label' => esc_html__('Title', 'geoport'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $slider->add_control(
            'slider_sub_title',
            [
                'label' => esc_html__('Sub Title', 'geoport'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $slider->add_control(
            'list_btn_one_text', [
                'label' => __( 'Button One Text', 'geoport' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Contact With Us' , 'geoport' ),
                'label_block' => true,
            ]
        );

        $slider->add_control(
            'list_btn_one_link', [
                'label' => __( 'Button One Link', 'geoport' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '#' , 'geoport' ),
                'label_block' => true,
            ]
        );

        $slider->add_control(
            'list_btn_two_text', [
                'label' => __( 'Button Two Text', 'geoport' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Learn More' , 'geoport' ),
                'label_block' => true,
            ]
        );

        $slider->add_control(
            'list_btn_two_link', [
                'label' => __( 'Button Two Link', 'geoport' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( '#' , 'geoport' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'slider_info_lists',
            [
                'label' => esc_html__('Slider Lists', 'geoport'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slider->get_controls(),
                'default' => [
                    [
                        'slider_title' => esc_html__('Providing flexible service Levels', 'geoport'),
                        'slider_sub_title' => esc_html__('Air Freight Services', 'geoport'),
                    ],
                    [
                        'slider_title' => esc_html__('Providing flexible service Levels', 'geoport'),
                        'slider_sub_title' => esc_html__('GeoPort Logistics Ltd.', 'geoport'),
                    ],
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'slider_three_options',
            [
                'label' => __( 'Slider Options', 'spondtech' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
              'slider_autoplay',
              [
                 'label'   => __( 'Slider Autoplay', 'spondtech' ),
                 'type'    => Controls_Manager::SELECT,
                 'default' => 'false',
                 'options' => [
                    'false'   => __( 'No', 'spondtech' ),
                    'true'    => __( 'Yes', 'spondtech' ),
                 ],
              ]
        );

        $this->add_control(
              'slider_autoplay_speed',
              [
                 'label'   => __( 'Slider Autoplay Speed', 'spondtech' ),
                 'type'    => Controls_Manager::TEXT,
                 'default' => '10000',
              ]
        );

        $this->add_control(
              'slider_animation',
              [
                 'label'   => __( 'Slider Animation', 'spondtech' ),
                 'type'    => Controls_Manager::SELECT,
                 'default' => 'true',
                 'options' => [
                    'false'   => __( 'No', 'spondtech' ),
                    'true'    => __( 'Yes', 'spondtech' ),
                 ],
              ]
        );

        $this->add_control(
              'slider_arrows',
              [
                 'label'   => __( 'Slider Arrows', 'spondtech' ),
                 'type'    => Controls_Manager::SELECT,
                 'default' => 'true',
                 'options' => [
                    'false'   => __( 'No', 'spondtech' ),
                    'true'    => __( 'Yes', 'spondtech' ),
                 ],
              ]
        );

        $this->add_responsive_control(
            'arrow_button_bg_color',
            [
                'label' => __( 'Arrow Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-active .slick-arrow' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_button_text_color',
            [
                'label' => __( 'Arrow Icon Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#a49a92',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-active .slick-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_button_hover_bg_color',
            [
                'label' => __( 'Arrow Hover Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-active .slick-arrow:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_button_hover_text_color',
            [
                'label' => __( 'Arrow Hover Icon Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-active .slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // style tab here
        $this->start_controls_section(
            '_section_style_item',
            [
                'label' => __('Item Style', 'geoport'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_bg_color',
            [
                'label' => __( 'Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-active .single-slider.t-slider-bg::before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


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
                    '{{WRAPPER}} .slider-area .slider-content.t-slider-content h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .slider-area .slider-content.t-slider-content h2',
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
                    '{{WRAPPER}} .slider-area .slider-content.t-slider-content span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub-title',
                'selector' => '{{WRAPPER}} .slider-area .slider-content.t-slider-content span',
            ]
        );

        $this->end_controls_section();

        /* = Item Styling
        ========================================*/
        $this->start_controls_section(
            'contact_list_item_style',
            [
                'label' => esc_html__( 'Contact Button', 'geoport' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'contact_button_bg_color',
            [
                'label' => __( 'Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5e14',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_border_color',
            [
                'label' => __( 'Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5e14',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn' => 'border: 1px solid {{VALUE}};',
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
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn:hover' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.orange-btn:hover' => 'border: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();  


        /* = Item Styling
        ========================================*/
        $this->start_controls_section(
            'learn_button_style',
            [
                'label' => esc_html__( 'Learn Button', 'geoport' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'learn_button_bg_color',
            [
                'label' => __( 'Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_text_color',
            [
                'label' => __( 'Text Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_border_color',
            [
                'label' => __( 'Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_bg_color',
            [
                'label' => __( 'Hover Background Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_text_color',
            [
                'label' => __( 'Hover Text Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'learn_button_hover_border_color',
            [
                'label' => __( 'Hover Border Color', 'geoport' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slider-area.three .slider-content.t-slider-content .slider-btn a.btn.transparent-btn' => 'border-color: {{VALUE}};',
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
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $e_uniqid     = uniqid();
        $unique_id = 'slider-' . uniqid();

        // JavaScript options
        $slider_options = [
            'autoplay' => $settings['slider_autoplay'] === 'true',
            'autoplaySpeed' => (int) $settings['slider_autoplay_speed'],
            'fade' => $settings['slider_animation'] === 'true',
            'arrows' => $settings['slider_arrows'] === 'true'
        ];

        ?>

        <section class="slider-area position-relative three">
            <div class="slider-active <?php echo esc_attr($unique_id); ?>">
                <?php foreach ( $settings['slider_info_lists'] as $key => $item ) : ?>
                    <div class="single-slider t-slider-bg d-flex align-items-center" style="background-image:url(<?php echo esc_url($item['slider_img']['url']); ?>)">
                        <div class="container-fluid slider-container-p">
                            <div class="row">
                                <div class="col-xl-9 col-lg-12">
                                    <div class="slider-content t-slider-content">
                                        <span data-animation="fadeInUp" data-delay=".2s"><?php echo esc_html($item['slider_sub_title']); ?></span>
                                        <h2 data-animation="fadeInUp" data-delay=".4s"><?php echo esc_html($item['slider_title']); ?></h2>
                                        <div class="slider-btn">
                                            <a href="<?php echo esc_url( $item['list_btn_one_link'] ); ?>" class="btn orange-btn" data-animation="fadeInLeft" data-delay=".6s"><i class="fal fa-paper-plane"></i><?php echo esc_html__( $item['list_btn_one_text'] ); ?></a>
                                            <a href="<?php echo esc_url( $item['list_btn_two_link'] ); ?>" class="btn transparent-btn" data-animation="fadeInRight" data-delay=".6s"><i class="fal fa-angle-right"></i><?php echo esc_html__( $item['list_btn_two_text'] ); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <script>
                jQuery(document).ready(function($) {
                    var sliderOptions = <?php echo json_encode($slider_options); ?>;
                    initSlider('.<?php echo esc_js($unique_id); ?>', sliderOptions);
                });
            </script>
        </section>

    <?php
    }
}