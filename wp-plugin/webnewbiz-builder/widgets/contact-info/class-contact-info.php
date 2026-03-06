<?php
namespace WebnewBiz\Builder\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Contact_Info extends Base_Widget {

    public function get_name(): string { return 'wnb-contact-info'; }
    public function get_title(): string { return __('Contact Info', 'webnewbiz-builder'); }
    public function get_icon(): string { return 'eicon-mail'; }
    public function get_style_depends(): array { return ['wnb-contact-info']; }

    protected function register_controls(): void {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'webnewbiz-builder'),
        ]);

        $this->add_control('title', [
            'label'   => __('Title', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Get In Touch', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('subtitle', [
            'label'   => __('Subtitle', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __("We'd love to hear from you. Reach out to us using any of the methods below.", 'webnewbiz-builder'),
        ]);

        $this->add_control('address', [
            'label'   => __('Address', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('123 Business Street, City, Country', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('phone', [
            'label'   => __('Phone', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => '+1 (555) 123-4567',
        ]);

        $this->add_control('email', [
            'label'   => __('Email', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => 'hello@business.com',
        ]);

        $this->add_control('hours', [
            'label'   => __('Business Hours', 'webnewbiz-builder'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Mon - Fri: 9am - 5pm', 'webnewbiz-builder'),
            'label_block' => true,
        ]);

        $this->add_control('map_embed', [
            'label'       => __('Google Maps Embed URL', 'webnewbiz-builder'),
            'type'        => Controls_Manager::URL,
            'description' => __('Paste Google Maps embed URL here.', 'webnewbiz-builder'),
            'default'     => ['url' => ''],
        ]);

        $this->add_control('layout', [
            'label'   => __('Layout', 'webnewbiz-builder'),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'stacked'  => __('Stacked', 'webnewbiz-builder'),
                'side'     => __('Side by Side', 'webnewbiz-builder'),
            ],
            'default' => 'stacked',
        ]);

        $this->end_controls_section();

        // ─── Style ───
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'webnewbiz-builder'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typo',
            'selector' => '{{WRAPPER}} .wnb-contact-title',
        ]);

        $this->add_control('icon_color', [
            'label'     => __('Icon Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => ['{{WRAPPER}} .wnb-contact-icon' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('text_color', [
            'label'     => __('Text Color', 'webnewbiz-builder'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#374151',
            'selectors' => ['{{WRAPPER}} .wnb-contact-text' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void {
        $s = $this->get_settings_for_display();
        $is_side = $s['layout'] === 'side';
        ?>
        <div class="wnb-contact <?php echo $is_side ? 'wnb-contact--side' : ''; ?>">
            <div class="wnb-contact-info-col">
                <?php if (!empty($s['title'])) : ?>
                    <h2 class="wnb-contact-title"><?php echo esc_html($s['title']); ?></h2>
                <?php endif; ?>
                <?php if (!empty($s['subtitle'])) : ?>
                    <p class="wnb-contact-subtitle"><?php echo esc_html($s['subtitle']); ?></p>
                <?php endif; ?>

                <div class="wnb-contact-items">
                    <?php if (!empty($s['address'])) : ?>
                        <div class="wnb-contact-item">
                            <span class="wnb-contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <span class="wnb-contact-text"><?php echo esc_html($s['address']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($s['phone'])) : ?>
                        <div class="wnb-contact-item">
                            <span class="wnb-contact-icon"><i class="fas fa-phone"></i></span>
                            <a class="wnb-contact-text" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $s['phone'])); ?>">
                                <?php echo esc_html($s['phone']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($s['email'])) : ?>
                        <div class="wnb-contact-item">
                            <span class="wnb-contact-icon"><i class="fas fa-envelope"></i></span>
                            <a class="wnb-contact-text" href="mailto:<?php echo esc_attr($s['email']); ?>">
                                <?php echo esc_html($s['email']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($s['hours'])) : ?>
                        <div class="wnb-contact-item">
                            <span class="wnb-contact-icon"><i class="fas fa-clock"></i></span>
                            <span class="wnb-contact-text"><?php echo esc_html($s['hours']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($s['map_embed']['url'])) : ?>
                <div class="wnb-contact-map">
                    <iframe src="<?php echo esc_url($s['map_embed']['url']); ?>" width="100%" height="350" style="border:0; border-radius: 12px;" allowfullscreen loading="lazy"></iframe>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
