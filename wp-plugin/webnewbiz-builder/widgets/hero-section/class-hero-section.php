<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Hero_Section extends Base_Widget {

    public function get_name(): string {
        return 'wnb-hero-section';
    }

    public function get_title(): string {
        return __('Hero Section', 'webnewbiz-builder');
    }

    public function get_icon(): string {
        return 'eicon-single-page';
    }

    public function get_style_depends(): array {
        return ['wnb-hero-section'];
    }

    protected function register_controls(): void {
        // ─── Content Tab ───
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('subtitle', [
            'label'   => __('Subtitle', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Welcome to Our Business', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('title', [
            'label'   => __('Title', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Build Something Amazing Today', 'webnewbiz-builder'),
            'rows'    => 3,
        ]);

        $this->add_control('description', [
            'label'   => __('Description', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('We provide professional solutions to help your business grow and succeed in the digital world.', 'webnewbiz-builder'),
        ]);

        $this->add_control('button_text', [
            'label'   => __('Button Text', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Get Started', 'webnewbiz-builder'),
        ]);

        $this->add_control('button_link', [
            'label'   => __('Button Link', 'webnewbiz-builder'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#contact'],
        ]);

        $this->add_control('button2_text', [
            'label'   => __('Second Button Text', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => '',
            'description' => __('Leave empty to hide.', 'webnewbiz-builder'),
        ]);

        $this->add_control('button2_link', [
            'label' => __('Second Button Link', 'webnewbiz-builder'),
            'type'  => Controls_Manager::URL,
            'default' => ['url' => '#about'],
            'condition' => ['button2_text!' => ''],
        ]);

        $this->end_controls_section();

        // ─── Background ───
        $this->start_controls_section('section_background', [
            'label' => __('Background', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('background_image', [
            'label'   => __('Background Image', 'webnewbiz-builder'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('overlay_color', [
            'label'   => __('Overlay Color', 'webnewbiz-builder'),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(0, 0, 0, 0.4)',
        ]);

        $this->add_responsive_control('min_height', [
            'label'   => __('Min Height', 'webnewbiz-builder'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 300, 'max' => 1200, 'step' => 10], 'vh' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 700, 'unit' => 'px'],
            'size_units' => ['px', 'vh'],
            'selectors' => ['{{WRAPPER}} .wnb-hero' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('content_align', [
            'label'   => __('Alignment', 'webnewbiz-builder'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'left'   => ['title' => __('Left', 'webnewbiz-builder'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'webnewbiz-builder'), 'icon' => 'eicon-text-align-center'],
                'right'  => ['title' => __('Right', 'webnewbiz-builder'), 'icon' => 'eicon-text-align-right'],
            ],
            'default'   => 'center',
            'selectors' => ['{{WRAPPER}} .wnb-hero-content' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Title ───
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .wnb-hero-title',
        ]);

        $this->add_control('title_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-hero-title' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Subtitle ───
        $this->start_controls_section('section_style_subtitle', [
            'label' => __('Subtitle', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'subtitle_typography',
            'selector' => '{{WRAPPER}} .wnb-hero-subtitle',
        ]);

        $this->add_control('subtitle_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.85)',
            'selectors' => ['{{WRAPPER}} .wnb-hero-subtitle' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Description ───
        $this->start_controls_section('section_style_desc', [
            'label' => __('Description', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'desc_typography',
            'selector' => '{{WRAPPER}} .wnb-hero-description',
        ]);

        $this->add_control('desc_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.9)',
            'selectors' => ['{{WRAPPER}} .wnb-hero-description' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Button ───
        $this->start_controls_section('section_style_button', [
            'label' => __('Button', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_bg_color', [
            'label'     => __('Background', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => ['{{WRAPPER}} .wnb-hero-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('btn_text_color', [
            'label'     => __('Text Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-hero-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('btn_border_radius', [
            'label'      => __('Border Radius', 'webnewbiz-builder'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .wnb-hero-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .wnb-hero-btn',
        ]);

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        $bg = $s['background_image']['url'] ?? '';
        $overlay = $s['overlay_color'] ?? 'rgba(0,0,0,0.4)';
        ?>
        <div class="wnb-hero"<?php if ($bg) echo ' style="background-image: url(' . esc_url($bg) . ');"'; ?>>
            <div class="wnb-hero-overlay" style="background: <?php echo esc_attr($overlay); ?>;"></div>
            <div class="wnb-hero-content">
                <?php if (!empty($s['subtitle'])) : ?>
                    <p class="wnb-hero-subtitle"><?php echo esc_html($s['subtitle']); ?></p>
                <?php endif; ?>
                <?php if (!empty($s['title'])) : ?>
                    <h1 class="wnb-hero-title"><?php echo esc_html($s['title']); ?></h1>
                <?php endif; ?>
                <?php if (!empty($s['description'])) : ?>
                    <p class="wnb-hero-description"><?php echo esc_html($s['description']); ?></p>
                <?php endif; ?>
                <div class="wnb-hero-buttons">
                    <?php if (!empty($s['button_text'])) : ?>
                        <a class="wnb-hero-btn wnb-hero-btn-primary" href="<?php echo esc_url($s['button_link']['url'] ?? '#'); ?>">
                            <?php echo esc_html($s['button_text']); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($s['button2_text'])) : ?>
                        <a class="wnb-hero-btn wnb-hero-btn-secondary" href="<?php echo esc_url($s['button2_link']['url'] ?? '#'); ?>">
                            <?php echo esc_html($s['button2_text']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
