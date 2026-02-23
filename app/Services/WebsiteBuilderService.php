<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Domain;
use App\Models\Server;
use App\Models\Website;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebsiteBuilderService
{
    public function __construct(
        private WordPressService $wordpressService,
        private AIContentService $aiService,
        private DnsService $dnsService,
        private SslService $sslService,
        private ScreenshotService $screenshotService,
    ) {}

    public function buildWebsite(array $params): array
    {
        $user = auth()->user();

        try {
            // Step 1: Create website record
            $subdomain = $params['subdomain'] ?? Str::slug($params['name']) . '-' . Str::random(4);
            $website = Website::create([
                'user_id' => $user->id,
                'name' => $params['name'],
                'subdomain' => strtolower($subdomain),
                'status' => 'provisioning',
                'ai_prompt' => $params['prompt'] ?? null,
                'ai_business_type' => $params['business_type'] ?? null,
                'ai_style' => $params['style'] ?? 'modern',
                'wp_admin_email' => $params['email'] ?? $user->email,
            ]);

            ActivityLog::log('website.creating', "Building website: {$website->name}", $website);

            // Step 2: Select server with capacity
            $server = Server::active()->withCapacity()->orderBy('current_websites')->first();
            if (!$server) {
                $website->update(['status' => 'error']);
                return ['success' => false, 'message' => 'No servers available. Please try again later.'];
            }
            $website->update(['server_id' => $server->id]);

            // Step 3: Generate AI content if prompt provided
            $aiContent = null;
            if ($params['prompt'] ?? $params['business_type'] ?? false) {
                $aiResult = $this->aiService->generateWebsiteContent(
                    $params['business_type'] ?? 'general',
                    $params['name'],
                    $params['style'] ?? 'modern'
                );
                if ($aiResult['success']) {
                    $aiContent = $aiResult['data'];
                    $website->update(['ai_generated_content' => $aiContent]);
                }
            }

            // Step 4: Create WordPress site
            $wpResult = $this->wordpressService->createSite($website);
            if (!$wpResult['success']) {
                $website->update(['status' => 'error']);
                return ['success' => false, 'message' => 'WordPress installation failed: ' . ($wpResult['message'] ?? 'Unknown error')];
            }

            // Step 5: Inject AI content into WordPress
            if ($aiContent) {
                $this->injectContent($website, $aiContent);
            }

            // Step 6: Setup DNS
            $domain = $website->subdomain . config('webnewbiz.subdomain_suffix');
            $dnsResult = $this->dnsService->createRecord('A', $website->subdomain, $server->ip_address);

            $domainRecord = Domain::create([
                'website_id' => $website->id,
                'domain' => $domain,
                'type' => 'subdomain',
                'is_primary' => true,
                'cloudflare_record_id' => $dnsResult['data']['id'] ?? null,
                'dns_status' => $dnsResult['success'] ? 'active' : 'pending',
            ]);

            // Step 7: SSL Certificate
            if ($dnsResult['success']) {
                $this->sslService->issueCertificate($domainRecord);
            }

            // Step 8: Take screenshot
            $this->screenshotService->capture($website);

            $website->update(['status' => 'active']);
            ActivityLog::log('website.created', "Website built successfully: {$website->name}", $website);

            return [
                'success' => true,
                'data' => [
                    'website' => $website->fresh()->load(['server', 'domains', 'plugins', 'themes']),
                    'wp_credentials' => $wpResult['data'] ?? null,
                ],
            ];
        } catch (\Exception $e) {
            Log::error("Website build failed: {$e->getMessage()}");
            if (isset($website)) {
                $website->update(['status' => 'error']);
            }
            return ['success' => false, 'message' => 'Build failed: ' . $e->getMessage()];
        }
    }

    private function injectContent(Website $website, array $content): void
    {
        try {
            // Update site title and tagline
            if (isset($content['site_title'])) {
                $this->wordpressService->updateOption($website, 'blogname', $content['site_title']);
            }
            if (isset($content['tagline'])) {
                $this->wordpressService->updateOption($website, 'blogdescription', $content['tagline']);
            }

            $colors = $content['colors'] ?? ['primary' => '#4A90D9', 'secondary' => '#2C3E50', 'accent' => '#E74C3C'];
            $homePostId = null;

            // Create pages with Elementor data
            $pages = $content['pages'] ?? [];
            foreach ($pages as $slug => $pageData) {
                $title = $pageData['title'] ?? ucfirst($slug);
                $elementorData = $this->buildElementorData($pageData, $colors);
                $htmlFallback = $this->buildFallbackHtml($pageData);

                $result = $this->wordpressService->createElementorPage($website, $title, $htmlFallback, $elementorData);

                if ($slug === 'home' && ($result['success'] ?? false) && isset($result['post_id'])) {
                    $homePostId = $result['post_id'];
                }
            }

            // Set homepage as static front page
            if ($homePostId) {
                $this->wordpressService->updateOption($website, 'show_on_front', 'page');
                $this->wordpressService->updateOption($website, 'page_on_front', (string) $homePostId);
            }
        } catch (\Exception $e) {
            Log::warning("Content injection partially failed: {$e->getMessage()}");
        }
    }

    private function generateElementorId(): string
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 7);
    }

    private function buildElementorData(array $pageData, array $colors): array
    {
        $sections = [];
        $primary = $colors['primary'] ?? '#4A90D9';
        $secondary = $colors['secondary'] ?? '#2C3E50';
        $accent = $colors['accent'] ?? '#E74C3C';

        // Hero section
        if (isset($pageData['hero_title'])) {
            $heroElements = [];

            $heroElements[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'widget',
                'widgetType' => 'heading',
                'settings' => [
                    'title' => $pageData['hero_title'],
                    'header_size' => 'h1',
                    'align' => 'center',
                    'title_color' => '#FFFFFF',
                ],
            ];

            if (isset($pageData['hero_subtitle'])) {
                $heroElements[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => [
                        'editor' => '<p style="text-align:center;color:#FFFFFF;font-size:18px;">' . e($pageData['hero_subtitle']) . '</p>',
                    ],
                ];
            }

            if (isset($pageData['hero_cta'])) {
                $heroElements[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'button',
                    'settings' => [
                        'text' => $pageData['hero_cta'],
                        'align' => 'center',
                        'button_type' => 'default',
                        'background_color' => $accent,
                        'button_text_color' => '#FFFFFF',
                        'border_radius' => ['unit' => 'px', 'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4'],
                    ],
                ];
            }

            $sections[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'section',
                'settings' => [
                    'background_background' => 'classic',
                    'background_color' => $primary,
                    'padding' => ['unit' => 'px', 'top' => '80', 'right' => '0', 'bottom' => '80', 'left' => '0'],
                ],
                'elements' => [
                    [
                        'id' => $this->generateElementorId(),
                        'elType' => 'column',
                        'settings' => ['_column_size' => 100],
                        'elements' => $heroElements,
                    ],
                ],
            ];
        }

        // Content section (for about/services pages with direct content)
        if (isset($pageData['content'])) {
            $contentWidgets = [];

            if (isset($pageData['mission'])) {
                $contentWidgets[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'Our Mission', 'header_size' => 'h2', 'align' => 'left'],
                ];
                $contentWidgets[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => '<p>' . e($pageData['mission']) . '</p>'],
                ];
            }

            if (isset($pageData['vision'])) {
                $contentWidgets[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'heading',
                    'settings' => ['title' => 'Our Vision', 'header_size' => 'h2', 'align' => 'left'],
                ];
                $contentWidgets[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => '<p>' . e($pageData['vision']) . '</p>'],
                ];
            }

            $contentWidgets[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'widget',
                'widgetType' => 'text-editor',
                'settings' => ['editor' => '<p>' . e($pageData['content']) . '</p>'],
            ];

            $sections[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'section',
                'settings' => [
                    'padding' => ['unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '60', 'left' => '0'],
                ],
                'elements' => [
                    [
                        'id' => $this->generateElementorId(),
                        'elType' => 'column',
                        'settings' => ['_column_size' => 100],
                        'elements' => $contentWidgets,
                    ],
                ],
            ];
        }

        // Intro section (for services page)
        if (isset($pageData['intro'])) {
            $sections[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'section',
                'settings' => [
                    'padding' => ['unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '20', 'left' => '0'],
                ],
                'elements' => [
                    [
                        'id' => $this->generateElementorId(),
                        'elType' => 'column',
                        'settings' => ['_column_size' => 100],
                        'elements' => [
                            [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => ['editor' => '<p style="text-align:center;font-size:18px;">' . e($pageData['intro']) . '</p>'],
                            ],
                        ],
                    ],
                ],
            ];
        }

        // Service/feature items (top-level items array, e.g. services page)
        if (isset($pageData['items']) && !isset($pageData['sections'])) {
            $sections[] = $this->buildItemsSection($pageData['items'], $primary, 'icon-box');
        }

        // Dynamic sections (features, testimonials, about_preview, cta)
        if (isset($pageData['sections'])) {
            foreach ($pageData['sections'] as $section) {
                $type = $section['type'] ?? 'generic';

                // Section title
                $sectionTitle = $section['title'] ?? '';

                switch ($type) {
                    case 'features':
                        $sectionElements = [];
                        if ($sectionTitle) {
                            $sectionElements[] = $this->buildTitleSection($sectionTitle, $secondary);
                        }
                        if (isset($section['items'])) {
                            $sectionElements[] = $this->buildItemsSection($section['items'], $primary, 'icon-box');
                        }
                        $sections = array_merge($sections, $sectionElements);
                        break;

                    case 'testimonials':
                        if ($sectionTitle) {
                            $sections[] = $this->buildTitleSection($sectionTitle, $secondary);
                        }
                        if (isset($section['items'])) {
                            $sections[] = $this->buildTestimonialsSection($section['items']);
                        }
                        break;

                    case 'about_preview':
                        $aboutWidgets = [];
                        if ($sectionTitle) {
                            $aboutWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => ['title' => $sectionTitle, 'header_size' => 'h2', 'align' => 'center', 'title_color' => $secondary],
                            ];
                        }
                        if (isset($section['content'])) {
                            $aboutWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => ['editor' => '<p style="text-align:center;font-size:16px;">' . e($section['content']) . '</p>'],
                            ];
                        }
                        $sections[] = [
                            'id' => $this->generateElementorId(),
                            'elType' => 'section',
                            'settings' => [
                                'background_background' => 'classic',
                                'background_color' => '#F8F9FA',
                                'padding' => ['unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '60', 'left' => '0'],
                            ],
                            'elements' => [
                                [
                                    'id' => $this->generateElementorId(),
                                    'elType' => 'column',
                                    'settings' => ['_column_size' => 100],
                                    'elements' => $aboutWidgets,
                                ],
                            ],
                        ];
                        break;

                    case 'cta':
                        $ctaWidgets = [];
                        if ($sectionTitle) {
                            $ctaWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => ['title' => $sectionTitle, 'header_size' => 'h2', 'align' => 'center', 'title_color' => '#FFFFFF'],
                            ];
                        }
                        if (isset($section['subtitle'])) {
                            $ctaWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => ['editor' => '<p style="text-align:center;color:#FFFFFF;">' . e($section['subtitle']) . '</p>'],
                            ];
                        }
                        if (isset($section['button_text'])) {
                            $ctaWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'button',
                                'settings' => [
                                    'text' => $section['button_text'],
                                    'align' => 'center',
                                    'background_color' => $accent,
                                    'button_text_color' => '#FFFFFF',
                                ],
                            ];
                        }
                        $sections[] = [
                            'id' => $this->generateElementorId(),
                            'elType' => 'section',
                            'settings' => [
                                'background_background' => 'classic',
                                'background_color' => $secondary,
                                'padding' => ['unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '60', 'left' => '0'],
                            ],
                            'elements' => [
                                [
                                    'id' => $this->generateElementorId(),
                                    'elType' => 'column',
                                    'settings' => ['_column_size' => 100],
                                    'elements' => $ctaWidgets,
                                ],
                            ],
                        ];
                        break;

                    default:
                        // Generic section with title and items
                        $genericWidgets = [];
                        if ($sectionTitle) {
                            $genericWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'heading',
                                'settings' => ['title' => $sectionTitle, 'header_size' => 'h2', 'align' => 'center'],
                            ];
                        }
                        if (isset($section['items'])) {
                            foreach ($section['items'] as $item) {
                                $genericWidgets[] = [
                                    'id' => $this->generateElementorId(),
                                    'elType' => 'widget',
                                    'widgetType' => 'text-editor',
                                    'settings' => [
                                        'editor' => '<h3>' . e($item['title'] ?? $item['name'] ?? '') . '</h3><p>' . e($item['description'] ?? $item['content'] ?? '') . '</p>',
                                    ],
                                ];
                            }
                        }
                        if (isset($section['content'])) {
                            $genericWidgets[] = [
                                'id' => $this->generateElementorId(),
                                'elType' => 'widget',
                                'widgetType' => 'text-editor',
                                'settings' => ['editor' => '<p>' . e($section['content']) . '</p>'],
                            ];
                        }
                        $sections[] = [
                            'id' => $this->generateElementorId(),
                            'elType' => 'section',
                            'settings' => [
                                'padding' => ['unit' => 'px', 'top' => '40', 'right' => '0', 'bottom' => '40', 'left' => '0'],
                            ],
                            'elements' => [
                                [
                                    'id' => $this->generateElementorId(),
                                    'elType' => 'column',
                                    'settings' => ['_column_size' => 100],
                                    'elements' => $genericWidgets,
                                ],
                            ],
                        ];
                        break;
                }
            }
        }

        // Contact section fields (address, phone, email)
        if (isset($pageData['address']) || isset($pageData['phone']) || isset($pageData['email'])) {
            $contactHtml = '';
            if (isset($pageData['address'])) $contactHtml .= '<p><strong>Address:</strong> ' . e($pageData['address']) . '</p>';
            if (isset($pageData['phone'])) $contactHtml .= '<p><strong>Phone:</strong> ' . e($pageData['phone']) . '</p>';
            if (isset($pageData['email'])) $contactHtml .= '<p><strong>Email:</strong> ' . e($pageData['email']) . '</p>';

            $contactWidgets = [];
            if (isset($pageData['subtitle'])) {
                $contactWidgets[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'widget',
                    'widgetType' => 'text-editor',
                    'settings' => ['editor' => '<p style="text-align:center;font-size:16px;">' . e($pageData['subtitle']) . '</p>'],
                ];
            }
            $contactWidgets[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'widget',
                'widgetType' => 'text-editor',
                'settings' => ['editor' => $contactHtml],
            ];

            $sections[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'section',
                'settings' => [
                    'padding' => ['unit' => 'px', 'top' => '60', 'right' => '0', 'bottom' => '60', 'left' => '0'],
                ],
                'elements' => [
                    [
                        'id' => $this->generateElementorId(),
                        'elType' => 'column',
                        'settings' => ['_column_size' => 100],
                        'elements' => $contactWidgets,
                    ],
                ],
            ];
        }

        return $sections;
    }

    private function buildTitleSection(string $title, string $color): array
    {
        return [
            'id' => $this->generateElementorId(),
            'elType' => 'section',
            'settings' => [
                'padding' => ['unit' => 'px', 'top' => '40', 'right' => '0', 'bottom' => '10', 'left' => '0'],
            ],
            'elements' => [
                [
                    'id' => $this->generateElementorId(),
                    'elType' => 'column',
                    'settings' => ['_column_size' => 100],
                    'elements' => [
                        [
                            'id' => $this->generateElementorId(),
                            'elType' => 'widget',
                            'widgetType' => 'heading',
                            'settings' => ['title' => $title, 'header_size' => 'h2', 'align' => 'center', 'title_color' => $color],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function buildItemsSection(array $items, string $primaryColor, string $widgetType = 'icon-box'): array
    {
        $columns = [];
        $columnSize = count($items) > 0 ? (int) floor(100 / min(count($items), 3)) : 100;

        foreach (array_chunk($items, 3) as $chunk) {
            $rowColumns = [];
            foreach ($chunk as $item) {
                $widgetSettings = [];
                if ($widgetType === 'icon-box') {
                    $widgetSettings = [
                        'title_text' => $item['title'] ?? $item['name'] ?? '',
                        'description_text' => $item['description'] ?? $item['content'] ?? '',
                        'icon' => ['value' => 'fas fa-' . ($item['icon'] ?? 'star'), 'library' => 'fa-solid'],
                        'primary_color' => $primaryColor,
                    ];
                    if (isset($item['price'])) {
                        $widgetSettings['description_text'] .= "\n\nPrice: " . $item['price'];
                    }
                }

                $rowColumns[] = [
                    'id' => $this->generateElementorId(),
                    'elType' => 'column',
                    'settings' => ['_column_size' => $columnSize],
                    'elements' => [
                        [
                            'id' => $this->generateElementorId(),
                            'elType' => 'widget',
                            'widgetType' => $widgetType,
                            'settings' => $widgetSettings,
                        ],
                    ],
                ];
            }

            $columns[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'section',
                'settings' => [
                    'padding' => ['unit' => 'px', 'top' => '20', 'right' => '0', 'bottom' => '40', 'left' => '0'],
                ],
                'elements' => $rowColumns,
            ];
        }

        // If there's only one row section, return it directly
        return count($columns) === 1 ? $columns[0] : [
            'id' => $this->generateElementorId(),
            'elType' => 'section',
            'settings' => [
                'padding' => ['unit' => 'px', 'top' => '20', 'right' => '0', 'bottom' => '40', 'left' => '0'],
            ],
            'elements' => $columns[0]['elements'] ?? [],
        ];
    }

    private function buildTestimonialsSection(array $items): array
    {
        $columns = [];
        $columnSize = count($items) > 0 ? (int) floor(100 / min(count($items), 3)) : 100;

        foreach (array_slice($items, 0, 3) as $item) {
            $columns[] = [
                'id' => $this->generateElementorId(),
                'elType' => 'column',
                'settings' => ['_column_size' => $columnSize],
                'elements' => [
                    [
                        'id' => $this->generateElementorId(),
                        'elType' => 'widget',
                        'widgetType' => 'testimonial',
                        'settings' => [
                            'testimonial_content' => $item['content'] ?? '',
                            'testimonial_name' => $item['name'] ?? '',
                            'testimonial_job' => $item['role'] ?? '',
                        ],
                    ],
                ],
            ];
        }

        return [
            'id' => $this->generateElementorId(),
            'elType' => 'section',
            'settings' => [
                'background_background' => 'classic',
                'background_color' => '#F8F9FA',
                'padding' => ['unit' => 'px', 'top' => '40', 'right' => '0', 'bottom' => '40', 'left' => '0'],
            ],
            'elements' => $columns,
        ];
    }

    private function buildFallbackHtml(array $pageData): string
    {
        $html = '';
        if (isset($pageData['hero_title'])) {
            $html .= '<h1>' . e($pageData['hero_title']) . '</h1>';
            if (isset($pageData['hero_subtitle'])) $html .= '<p>' . e($pageData['hero_subtitle']) . '</p>';
        }
        if (isset($pageData['content'])) {
            $html .= '<p>' . e($pageData['content']) . '</p>';
        }
        if (isset($pageData['sections'])) {
            foreach ($pageData['sections'] as $section) {
                $html .= '<h2>' . e($section['title'] ?? '') . '</h2>';
                foreach ($section['items'] ?? [] as $item) {
                    $html .= '<h3>' . e($item['title'] ?? $item['name'] ?? '') . '</h3>';
                    $html .= '<p>' . e($item['description'] ?? $item['content'] ?? '') . '</p>';
                }
            }
        }
        return $html;
    }

    public function suspendWebsite(Website $website, string $reason = ''): array
    {
        $website->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => $reason,
        ]);

        ActivityLog::log('website.suspended', "Website suspended: {$website->name}", $website);
        return ['success' => true, 'message' => 'Website suspended'];
    }

    public function unsuspendWebsite(Website $website): array
    {
        $website->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        ActivityLog::log('website.unsuspended', "Website reactivated: {$website->name}", $website);
        return ['success' => true, 'message' => 'Website reactivated'];
    }

    public function deleteWebsite(Website $website): array
    {
        // Delete WordPress site
        $this->wordpressService->deleteSite($website);

        // Delete DNS records
        foreach ($website->domains as $domain) {
            if ($domain->cloudflare_record_id) {
                $this->dnsService->deleteRecord($domain->cloudflare_record_id);
            }
        }

        ActivityLog::log('website.deleted', "Website deleted: {$website->name}", $website);
        $website->delete();

        return ['success' => true, 'message' => 'Website deleted'];
    }
}
