<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Features_Grid extends Base_Widget {

    public function get_name(): string { return 'wnb-features-grid'; }
    public function get_title(): string { return __('Features Grid', 'webnewbiz-builder'); }
    public function get_icon(): string { return 'eicon-posts-grid'; }
    public function get_style_depends(): array { return ['wnb-features-grid']; }

    protected function register_controls(): void {
        // ─── Content ───
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'webnewbiz-builder'),
        ]);

        $this->add_control('heading', [
            'label'   => __('Section Heading', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Why Choose Us', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('subheading', [
            'label'   => __('Section Subheading', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('We deliver exceptional results with our proven approach.', 'webnewbiz-builder'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label'   => __('Icon', 'webnewbiz-builder'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-star', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label'   => __('Title', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Feature Title', 'webnewbiz-builder'),
        ]);
        $repeater->add_control('description', [
            'label'   => __('Description', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('A brief description of this feature and why it matters.', 'webnewbiz-builder'),
        ]);

        $this->add_control('features', [
            'label'   => __('Features', 'webnewbiz-builder'),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                ['title' => __('Professional Design', 'webnewbiz-builder'), 'description' => __('Beautiful, modern designs that make your business stand out.', 'webnewbiz-builder'), 'icon' => ['value' => 'fas fa-palette', 'library' => 'fa-solid']],
                ['title' => __('Fast Performance', 'webnewbiz-builder'), 'description' => __('Lightning-fast loading speeds for the best user experience.', 'webnewbiz-builder'), 'icon' => ['value' => 'fas fa-bolt', 'library' => 'fa-solid']],
                ['title' => __('24/7 Support', 'webnewbiz-builder'), 'description' => __('Round-the-clock support whenever you need assistance.', 'webnewbiz-builder'), 'icon' => ['value' => 'fas fa-headset', 'library' => 'fa-solid']],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->add_responsive_control('columns', [
            'label'   => __('Columns', 'webnewbiz-builder'),
            'type'    => Controls_Manager::SELECT,
            'options' => ['2' => '2', '3' => '3', '4' => '4'],
            'default' => '3',
            'selectors' => ['{{WRAPPER}} .wnb-features-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'],
        ]);

        $this->end_controls_section();

        // ─── Style: Cards ───
        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Background', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-feature-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .wnb-feature-card',
        ]);

        $this->add_control('card_border_radius', [
            'label'      => __('Border Radius', 'webnewbiz-builder'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top' => '12', 'right' => '12', 'bottom' => '12', 'left' => '12', 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .wnb-feature-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('card_padding', [
            'label'      => __('Padding', 'webnewbiz-builder'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .wnb-feature-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Icon ───
        $this->start_controls_section('section_style_icon', [
            'label' => __('Icon', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('icon_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => ['{{WRAPPER}} .wnb-feature-icon i' => 'color: {{VALUE}};', '{{WRAPPER}} .wnb-feature-icon svg' => 'fill: {{VALUE}};'],
        ]);

        $this->add_responsive_control('icon_size', [
            'label'     => __('Size', 'webnewbiz-builder'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 16, 'max' => 80]],
            'default'   => ['size' => 40, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .wnb-feature-icon i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .wnb-feature-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ─── Style: Title ───
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'feature_title_typo',
            'selector' => '{{WRAPPER}} .wnb-feature-title',
        ]);

        $this->add_control('feature_title_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => ['{{WRAPPER}} .wnb-feature-title' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <div class="wnb-features">
            <?php if (!empty($s['heading'])) : ?>
                <div class="wnb-features-header">
                    <h2 class="wnb-features-heading"><?php echo esc_html($s['heading']); ?></h2>
                    <?php if (!empty($s['subheading'])) : ?>
                        <p class="wnb-features-subheading"><?php echo esc_html($s['subheading']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="wnb-features-list">
                <?php foreach ($s['features'] as $item) : ?>
                    <div class="wnb-feature-card">
                        <?php if (!empty($item['icon']['value'])) : ?>
                            <div class="wnb-feature-icon"><?php Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></div>
                        <?php endif; ?>
                        <h3 class="wnb-feature-title"><?php echo esc_html($item['title']); ?></h3>
                        <p class="wnb-feature-desc"><?php echo esc_html($item['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
