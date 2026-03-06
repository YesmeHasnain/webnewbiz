<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH')) exit;

class Testimonials extends Base_Widget {

    public function get_name(): string { return 'wnb-testimonials'; }
    public function get_title(): string { return __('Testimonials', 'webnewbiz-builder'); }
    public function get_icon(): string { return 'eicon-testimonial'; }
    public function get_style_depends(): array { return ['wnb-testimonials']; }

    protected function register_controls(): void {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'webnewbiz-builder'),
        ]);

        $this->add_control('heading', [
            'label'   => __('Section Heading', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('What Our Clients Say', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $repeater = new Repeater();
        $repeater->add_control('content', [
            'label'   => __('Testimonial', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('This service exceeded our expectations. Highly recommended!', 'webnewbiz-builder'),
        ]);
        $repeater->add_control('name', [
            'label'   => __('Name', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('John Doe', 'webnewbiz-builder'),
        ]);
        $repeater->add_control('role', [
            'label'   => __('Role / Company', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('CEO, Company', 'webnewbiz-builder'),
        ]);
        $repeater->add_control('image', [
            'label'   => __('Avatar', 'webnewbiz-builder'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);
        $repeater->add_control('rating', [
            'label'   => __('Rating', 'webnewbiz-builder'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 5,
            'default' => 5,
        ]);

        $this->add_control('testimonials', [
            'label'   => __('Testimonials', 'webnewbiz-builder'),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                ['name' => 'Sarah Johnson', 'role' => 'Marketing Director', 'content' => 'Absolutely fantastic service. They transformed our online presence completely.', 'rating' => 5],
                ['name' => 'Michael Chen', 'role' => 'Startup Founder', 'content' => 'Professional, efficient, and delivered beyond our expectations. Will definitely work with them again.', 'rating' => 5],
                ['name' => 'Emily Rodriguez', 'role' => 'Small Business Owner', 'content' => 'The best investment we made for our business. Our website looks incredible now.', 'rating' => 5],
            ],
            'title_field' => '{{{ name }}}',
        ]);

        $this->add_responsive_control('columns', [
            'label'   => __('Columns', 'webnewbiz-builder'),
            'type'    => Controls_Manager::SELECT,
            'options' => ['1' => '1', '2' => '2', '3' => '3'],
            'default' => '3',
            'selectors' => ['{{WRAPPER}} .wnb-testimonials-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'],
        ]);

        $this->end_controls_section();

        // ─── Style ───
        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Background', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wnb-testimonial-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .wnb-testimonial-card',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_stars', [
            'label' => __('Stars', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('star_color', [
            'label'     => __('Star Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f59e0b',
            'selectors' => ['{{WRAPPER}} .wnb-testimonial-stars' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_quote', [
            'label' => __('Quote Text', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'quote_typography',
            'selector' => '{{WRAPPER}} .wnb-testimonial-quote',
        ]);

        $this->add_control('quote_color', [
            'label'     => __('Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => ['{{WRAPPER}} .wnb-testimonial-quote' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        ?>
        <div class="wnb-testimonials">
            <?php if (!empty($s['heading'])) : ?>
                <h2 class="wnb-testimonials-heading"><?php echo esc_html($s['heading']); ?></h2>
            <?php endif; ?>
            <div class="wnb-testimonials-list">
                <?php foreach ($s['testimonials'] as $item) : ?>
                    <div class="wnb-testimonial-card">
                        <?php if (!empty($item['rating'])) : ?>
                            <div class="wnb-testimonial-stars">
                                <?php for ($i = 0; $i < (int) $item['rating']; $i++) echo '&#9733;'; ?>
                            </div>
                        <?php endif; ?>
                        <p class="wnb-testimonial-quote">&ldquo;<?php echo esc_html($item['content']); ?>&rdquo;</p>
                        <div class="wnb-testimonial-author">
                            <?php if (!empty($item['image']['url'])) : ?>
                                <img class="wnb-testimonial-avatar" src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['name']); ?>">
                            <?php endif; ?>
                            <div>
                                <strong class="wnb-testimonial-name"><?php echo esc_html($item['name']); ?></strong>
                                <?php if (!empty($item['role'])) : ?>
                                    <span class="wnb-testimonial-role"><?php echo esc_html($item['role']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
