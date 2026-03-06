<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class CTA_Banner extends Base_Widget {

    public function get_name(): string { return 'wnb-cta-banner'; }
    public function get_title(): string { return __('CTA Banner', 'webnewbiz-builder'); }
    public function get_icon(): string { return 'eicon-call-to-action'; }
    public function get_style_depends(): array { return ['wnb-cta-banner']; }

    protected function register_controls(): void {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'webnewbiz-builder'),
        ]);

        $this->add_control('title', [
            'label'   => __('Title', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Ready to Get Started?', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('description', [
            'label'   => __('Description', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Contact us today and let us help you build something amazing.', 'webnewbiz-builder'),
        ]);

        $this->add_control('button_text', [
            'label'   => __('Button Text', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Contact Us', 'webnewbiz-builder'),
        ]);

        $this->add_control('button_link', [
            'label'   => __('Button Link', 'webnewbiz-builder'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#contact'],
        ]);

        $this->end_controls_section();

        // ─── Background ───
        $this->start_controls_section('section_bg', [
            'label' => __('Background', 'webnewbiz-builder'),
        ]);

        $this->add_control('bg_type', [
            'label'   => __('Type', 'webnewbiz-builder'),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'gradient' => __('Gradient', 'webnewbiz-builder'),
                'solid'    => __('Solid Color', 'webnewbiz-builder'),
                'image'    => __('Image', 'webnewbiz-builder'),
            ],
            'default' => 'gradient',
        ]);

        $this->add_control('bg_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'condition' => ['bg_type' => 'solid'],
            'selectors' => ['{{WRAPPER}} .wnb-cta' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('gradient_start', [
            'label'     => __('Gradient Start', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'condition' => ['bg_type' => 'gradient'],
        ]);

        $this->add_control('gradient_end', [
            'label'     => __('Gradient End', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e40af',
            'condition' => ['bg_type' => 'gradient'],
        ]);

        $this->add_control('gradient_angle', [
            'label'     => __('Angle', 'webnewbiz-builder'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 360]],
            'default'   => ['size' => 135],
            'condition' => ['bg_type' => 'gradient'],
        ]);

        $this->add_control('bg_image', [
            'label'     => __('Image', 'webnewbiz-builder'),
            'type'      => Controls_Manager::MEDIA,
            'condition' => ['bg_type' => 'image'],
        ]);

        $this->add_control('bg_overlay', [
            'label'     => __('Overlay', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(37, 99, 235, 0.85)',
            'condition' => ['bg_type' => 'image'],
        ]);

        $this->end_controls_section();

        // ─── Style ───
        $this->start_controls_section('section_style', [
            'label' => __('Typography', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typo',
            'selector' => '{{WRAPPER}} .wnb-cta-title',
        ]);

        $this->add_control('title_color', [
            'label'     => __('Title Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-cta-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('desc_color', [
            'label'     => __('Description Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.9)',
            'selectors' => ['{{WRAPPER}} .wnb-cta-desc' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // ─── Button Style ───
        $this->start_controls_section('section_btn_style', [
            'label' => __('Button', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_bg', [
            'label'     => __('Background', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-cta-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('btn_color', [
            'label'     => __('Text Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => ['{{WRAPPER}} .wnb-cta-btn' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        $style = '';
        if ($s['bg_type'] === 'gradient') {
            $angle = $s['gradient_angle']['size'] ?? 135;
            $style = "background: linear-gradient({$angle}deg, {$s['gradient_start']}, {$s['gradient_end']});";
        } elseif ($s['bg_type'] === 'image' && !empty($s['bg_image']['url'])) {
            $style = "background-image: url(" . esc_url($s['bg_image']['url']) . "); background-size: cover; background-position: center;";
        }
        ?>
        <div class="wnb-cta" style="<?php echo esc_attr($style); ?>">
            <?php if ($s['bg_type'] === 'image' && !empty($s['bg_overlay'])) : ?>
                <div class="wnb-cta-overlay" style="background: <?php echo esc_attr($s['bg_overlay']); ?>;"></div>
            <?php endif; ?>
            <div class="wnb-cta-content">
                <?php if (!empty($s['title'])) : ?>
                    <h2 class="wnb-cta-title"><?php echo esc_html($s['title']); ?></h2>
                <?php endif; ?>
                <?php if (!empty($s['description'])) : ?>
                    <p class="wnb-cta-desc"><?php echo esc_html($s['description']); ?></p>
                <?php endif; ?>
                <?php if (!empty($s['button_text'])) : ?>
                    <a class="wnb-cta-btn" href="<?php echo esc_url($s['button_link']['url'] ?? '#'); ?>">
                        <?php echo esc_html($s['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
