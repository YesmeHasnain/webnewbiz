<?php

namespace App\Services\AiCopilot;

/**
 * Generates Elementor section JSON templates for common section types.
 */
class SectionTemplates
{
    /**
     * Generate a section template.
     */
    public static function generate(string $type, array $content = []): array
    {
        return match ($type) {
            'hero'         => self::hero($content),
            'features'     => self::features($content),
            'testimonials' => self::testimonials($content),
            'cta'          => self::cta($content),
            'contact'      => self::contact($content),
            'pricing'      => self::pricing($content),
            'team'         => self::team($content),
            'faq'          => self::faq($content),
            'gallery'      => self::gallery($content),
            default        => self::custom($content),
        };
    }

    private static function eid(): string
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 7);
    }

    private static function hero(array $c): array
    {
        $title = $c['title'] ?? 'Welcome to Our Website';
        $subtitle = $c['subtitle'] ?? 'We help businesses grow with innovative solutions.';
        $buttonText = $c['button_text'] ?? 'Get Started';
        $buttonUrl = $c['button_url'] ?? '#contact';

        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'full',
                'min_height' => ['size' => 500, 'unit' => 'px'],
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'padding' => ['top' => '80', 'right' => '40', 'bottom' => '80', 'left' => '40', 'unit' => 'px'],
                'background_color' => $c['bg_color'] ?? '#1a1a2e',
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => $title,
                        'align' => 'center',
                        'title_color' => '#FFFFFF',
                        'typography_typography' => 'custom',
                        'typography_font_size' => ['size' => 48, 'unit' => 'px'],
                        'typography_font_weight' => '700',
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => [
                        'editor' => "<p style=\"text-align:center;color:#cccccc;font-size:18px;\">{$subtitle}</p>",
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => $buttonText,
                        'link' => ['url' => $buttonUrl],
                        'align' => 'center',
                        'button_text_color' => '#FFFFFF',
                        'background_color' => $c['button_color'] ?? '#e94560',
                        'border_radius' => ['size' => 8, 'unit' => 'px'],
                    ],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function features(array $c): array
    {
        $title = $c['title'] ?? 'Our Features';
        $items = $c['items'] ?? [
            ['title' => 'Fast Performance', 'description' => 'Lightning fast loading speeds.', 'icon' => 'fas fa-bolt'],
            ['title' => 'Secure', 'description' => 'Enterprise-grade security.', 'icon' => 'fas fa-shield-alt'],
            ['title' => '24/7 Support', 'description' => 'Always here to help.', 'icon' => 'fas fa-headset'],
        ];

        $columns = [];
        foreach ($items as $item) {
            $columns[] = [
                'id' => self::eid(),
                'elType' => 'container',
                'settings' => [
                    'flex_direction' => 'column',
                    'flex_align_items' => 'center',
                    'padding' => ['top' => '30', 'right' => '20', 'bottom' => '30', 'left' => '20', 'unit' => 'px'],
                    'width' => ['size' => 33, 'unit' => '%'],
                    'width_mobile' => ['size' => 100, 'unit' => '%'],
                ],
                'elements' => [
                    [
                        'id' => self::eid(),
                        'elType' => 'widget',
                        'widgetType' => 'icon-box',
                        'settings' => [
                            'title_text' => $item['title'],
                            'description_text' => $item['description'],
                            'selected_icon' => ['value' => $item['icon'] ?? 'fas fa-star', 'library' => 'fa-solid'],
                            'position' => 'top',
                            'title_text_color' => '#333333',
                        ],
                        'elements' => [],
                    ],
                ],
            ];
        }

        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => $title,
                        'align' => 'center',
                        'typography_typography' => 'custom',
                        'typography_font_size' => ['size' => 36, 'unit' => 'px'],
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'container',
                    'settings' => [
                        'flex_direction' => 'row',
                        'flex_wrap' => 'wrap',
                        'flex_gap' => ['size' => 20, 'unit' => 'px'],
                        'flex_direction_mobile' => 'column',
                    ],
                    'elements' => $columns,
                ],
            ],
        ];
    }

    private static function testimonials(array $c): array
    {
        $title = $c['title'] ?? 'What Our Clients Say';
        $items = $c['items'] ?? [
            ['name' => 'Sarah Johnson', 'role' => 'CEO, TechCo', 'text' => 'Amazing service and results!'],
            ['name' => 'Mike Chen', 'role' => 'Marketing Director', 'text' => 'Transformed our online presence.'],
        ];

        $testimonialWidgets = [];
        foreach ($items as $item) {
            $testimonialWidgets[] = [
                'id' => self::eid(),
                'elType' => 'container',
                'settings' => [
                    'width' => ['size' => 50, 'unit' => '%'],
                    'width_mobile' => ['size' => 100, 'unit' => '%'],
                    'padding' => ['top' => '30', 'right' => '30', 'bottom' => '30', 'left' => '30', 'unit' => 'px'],
                    'background_color' => '#f8f9fa',
                    'border_radius' => ['size' => 12, 'unit' => 'px'],
                ],
                'elements' => [
                    [
                        'id' => self::eid(),
                        'elType' => 'widget',
                        'widgetType' => 'testimonial',
                        'settings' => [
                            'testimonial_content' => $item['text'],
                            'testimonial_name' => $item['name'],
                            'testimonial_job' => $item['role'],
                        ],
                        'elements' => [],
                    ],
                ],
            ];
        }

        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
                'background_color' => '#ffffff',
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $title, 'align' => 'center', 'typography_font_size' => ['size' => 36, 'unit' => 'px']],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'container',
                    'settings' => [
                        'flex_direction' => 'row',
                        'flex_wrap' => 'wrap',
                        'flex_gap' => ['size' => 20, 'unit' => 'px'],
                        'flex_direction_mobile' => 'column',
                    ],
                    'elements' => $testimonialWidgets,
                ],
            ],
        ];
    }

    private static function cta(array $c): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_justify_content' => 'center',
                'flex_align_items' => 'center',
                'min_height' => ['size' => 300, 'unit' => 'px'],
                'padding' => ['top' => '60', 'right' => '40', 'bottom' => '60', 'left' => '40', 'unit' => 'px'],
                'background_color' => $c['bg_color'] ?? '#2563eb',
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => $c['title'] ?? 'Ready to Get Started?',
                        'align' => 'center',
                        'title_color' => '#FFFFFF',
                        'typography_font_size' => ['size' => 36, 'unit' => 'px'],
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => [
                        'editor' => '<p style="text-align:center;color:#e0e0e0;">' . ($c['subtitle'] ?? 'Contact us today and let us help you achieve your goals.') . '</p>',
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => $c['button_text'] ?? 'Contact Us',
                        'link' => ['url' => $c['button_url'] ?? '#contact'],
                        'align' => 'center',
                        'button_text_color' => $c['bg_color'] ?? '#2563eb',
                        'background_color' => '#FFFFFF',
                        'border_radius' => ['size' => 8, 'unit' => 'px'],
                    ],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function contact(array $c): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => [
                        'title' => $c['title'] ?? 'Contact Us',
                        'align' => 'center',
                        'typography_font_size' => ['size' => 36, 'unit' => 'px'],
                    ],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => [
                        'editor' => '<p style="text-align:center;">' . ($c['description'] ?? 'Get in touch with us. We\'d love to hear from you.') . '</p>',
                    ],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function pricing(array $c): array
    {
        $title = $c['title'] ?? 'Our Plans';
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $title, 'align' => 'center', 'typography_font_size' => ['size' => 36, 'unit' => 'px']],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function team(array $c): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $c['title'] ?? 'Our Team', 'align' => 'center', 'typography_font_size' => ['size' => 36, 'unit' => 'px']],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function faq(array $c): array
    {
        $items = $c['items'] ?? [
            ['question' => 'What services do you offer?', 'answer' => 'We offer a wide range of professional services.'],
            ['question' => 'How can I get started?', 'answer' => 'Contact us through our form or call us directly.'],
        ];

        $tabs = [];
        foreach ($items as $item) {
            $tabs[] = [
                'tab_title' => $item['question'],
                'tab_content' => $item['answer'],
            ];
        }

        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $c['title'] ?? 'FAQ', 'align' => 'center', 'typography_font_size' => ['size' => 36, 'unit' => 'px']],
                    'elements' => [],
                ],
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'accordion',
                    'settings' => ['tabs' => $tabs],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function gallery(array $c): array
    {
        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => $c['title'] ?? 'Gallery', 'align' => 'center', 'typography_font_size' => ['size' => 36, 'unit' => 'px']],
                    'elements' => [],
                ],
            ],
        ];
    }

    private static function custom(array $c): array
    {
        $elements = [];

        if (!empty($c['heading'])) {
            $elements[] = [
                'id' => self::eid(),
                'elType' => 'widget',
                'widgetType' => 'heading',
                'settings' => ['title' => $c['heading'], 'align' => 'center'],
                'elements' => [],
            ];
        }

        if (!empty($c['text'])) {
            $elements[] = [
                'id' => self::eid(),
                'elType' => 'widget',
                'widgetType' => 'text-editor',
                'settings' => ['editor' => $c['text']],
                'elements' => [],
            ];
        }

        return [
            'id' => self::eid(),
            'elType' => 'container',
            'settings' => [
                'content_width' => 'boxed',
                'flex_direction' => 'column',
                'padding' => ['top' => '40', 'right' => '20', 'bottom' => '40', 'left' => '20', 'unit' => 'px'],
            ],
            'elements' => $elements ?: [
                [
                    'id' => self::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => '<p>New section content goes here.</p>'],
                    'elements' => [],
                ],
            ],
        ];
    }
}
