<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Domain;
use App\Models\Server;
use App\Models\Website;
use Illuminate\Support\Facades\Log;
use App\Services\Layouts\AbstractLayout;
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
                    $params['style'] ?? 'modern',
                    null,
                    $params['prompt'] ?? null
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
            $images = $content['images'] ?? [];
            $homePostId = null;
            $style = $website->ai_style ?? 'modern';
            $businessType = $website->ai_business_type ?? 'general';

            // Create pages with Elementor data
            $pages = $content['pages'] ?? [];
            foreach ($pages as $slug => $pageData) {
                $title = $pageData['title'] ?? ucfirst($slug);
                $elementorData = $this->buildElementorData($pageData, $colors, $images, $style, $businessType, $slug, $content);
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

    public function generateElementorId(): string
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, 7);
    }

    public function buildElementorData(
        array $pageData,
        array $colors,
        array $images = [],
        string $style = 'modern',
        string $businessType = 'general',
        string $slug = 'home',
        array $fullContent = [],
    ): array {
        // Use Layout system
        $layout = AbstractLayout::resolve($style) ?? AbstractLayout::resolve('azure');
        return $layout->buildPage($slug, $fullContent, $images);
    }

    /**
     * Legacy builder — kept for backward compatibility.
     * @deprecated Use template-based buildElementorData() instead.
     */
    public function buildElementorDataLegacy(array $pageData, array $colors, array $images = []): array
    {
        $sections = [];
        $primary = $colors['primary'] ?? '#4A90D9';
        $secondary = $colors['secondary'] ?? '#2C3E50';
        $accent = $colors['accent'] ?? '#E74C3C';

        // Hero section — full-width with background image + gradient overlay
        if (isset($pageData['hero_title'])) {
            $heroElements = [];

            // Spacer before title
            $heroElements[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 40]]);

            $heroElements[] = $this->makeWidget('heading', [
                'title' => $pageData['hero_title'],
                'header_size' => 'h1',
                'align' => 'center',
                'title_color' => '#FFFFFF',
                'typography_typography' => 'custom',
                'typography_font_size' => ['unit' => 'px', 'size' => 52],
                'typography_font_weight' => '800',
                'typography_line_height' => ['unit' => 'em', 'size' => 1.2],
            ]);

            if (isset($pageData['hero_subtitle'])) {
                $heroElements[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 16]]);
                $heroElements[] = $this->makeWidget('text-editor', [
                    'editor' => '<p style="text-align:center;color:rgba(255,255,255,0.9);font-size:20px;line-height:1.6;max-width:600px;margin:0 auto;">' . e($pageData['hero_subtitle']) . '</p>',
                ]);
            }

            if (isset($pageData['hero_cta'])) {
                $heroElements[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 24]]);
                $heroElements[] = $this->makeWidget('button', [
                    'text' => $pageData['hero_cta'],
                    'align' => 'center',
                    'button_type' => 'default',
                    'background_color' => $accent,
                    'button_text_color' => '#FFFFFF',
                    'border_radius' => $this->makeRadius(8),
                    'typography_typography' => 'custom',
                    'typography_font_size' => ['unit' => 'px', 'size' => 16],
                    'typography_font_weight' => '700',
                    'button_padding' => ['unit' => 'px', 'top' => '16', 'right' => '40', 'bottom' => '16', 'left' => '40'],
                    'button_box_shadow_box_shadow' => ['horizontal' => 0, 'vertical' => 4, 'blur' => 15, 'spread' => 0, 'color' => 'rgba(0,0,0,0.2)'],
                ]);
            }

            $heroElements[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 40]]);

            $heroSettings = [
                'layout' => 'full_width',
                'content_width' => ['unit' => 'px', 'size' => 1140],
                'min_height' => ['unit' => 'px', 'size' => 600],
                'flex_align_items' => 'center',
                'flex_justify_content' => 'center',
                'padding' => $this->makePadding(120, 20),
            ];

            // Use background image if available
            $heroImage = $images['hero'] ?? $pageData['hero_image'] ?? null;
            if ($heroImage) {
                $heroSettings['background_background'] = 'classic';
                $heroSettings['background_image'] = ['url' => $heroImage];
                $heroSettings['background_position'] = 'center center';
                $heroSettings['background_size'] = 'cover';
                $heroSettings['background_overlay_background'] = 'gradient';
                $heroSettings['background_overlay_color'] = $primary;
                $heroSettings['background_overlay_color_b'] = $secondary;
                $heroSettings['background_overlay_gradient_angle'] = ['unit' => 'deg', 'size' => 135];
                $heroSettings['background_overlay_opacity'] = ['unit' => 'px', 'size' => 0.75];
            } else {
                $heroSettings['background_background'] = 'gradient';
                $heroSettings['background_color'] = $primary;
                $heroSettings['background_color_b'] = $secondary;
                $heroSettings['background_gradient_angle'] = ['unit' => 'deg', 'size' => 135];
            }

            $sections[] = $this->makeSection($heroSettings, [
                $this->makeColumn(100, $heroElements),
            ]);
        }

        // Content section (for about/services pages)
        if (isset($pageData['content'])) {
            $contentWidgets = [];
            if (isset($pageData['mission'])) {
                $contentWidgets[] = $this->makeHeading('Our Mission', 'h2', 'left', $secondary);
                $contentWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="font-size:16px;line-height:1.8;color:#4b5563;">' . e($pageData['mission']) . '</p>']);
                $contentWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 24]]);
            }
            if (isset($pageData['vision'])) {
                $contentWidgets[] = $this->makeHeading('Our Vision', 'h2', 'left', $secondary);
                $contentWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="font-size:16px;line-height:1.8;color:#4b5563;">' . e($pageData['vision']) . '</p>']);
                $contentWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 24]]);
            }
            $contentWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="font-size:16px;line-height:1.8;color:#4b5563;">' . e($pageData['content']) . '</p>']);

            $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 20)], [
                $this->makeColumn(100, $contentWidgets),
            ]);
        }

        // Intro section
        if (isset($pageData['intro'])) {
            $sections[] = $this->makeSection(['padding' => $this->makePadding(60, 20)], [
                $this->makeColumn(100, [
                    $this->makeWidget('text-editor', ['editor' => '<p style="text-align:center;font-size:18px;line-height:1.7;color:#6b7280;max-width:700px;margin:0 auto;">' . e($pageData['intro']) . '</p>']),
                ]),
            ]);
        }

        // Top-level items (services page)
        if (isset($pageData['items']) && !isset($pageData['sections'])) {
            $sections[] = $this->buildFeatureCardsSection($pageData['items'], $primary, $secondary);
        }

        // Dynamic sections
        if (isset($pageData['sections'])) {
            foreach ($pageData['sections'] as $section) {
                $type = $section['type'] ?? 'generic';
                $sectionTitle = $section['title'] ?? '';

                switch ($type) {
                    case 'features':
                        if ($sectionTitle) {
                            $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 10)], [
                                $this->makeColumn(100, [$this->makeSectionHeading($sectionTitle, $secondary)]),
                            ]);
                        }
                        if (isset($section['items'])) {
                            $sections[] = $this->buildFeatureCardsSection($section['items'], $primary, $secondary);
                        }
                        break;

                    case 'testimonials':
                        $sections[] = $this->buildTestimonialsSection($section, $secondary, $images);
                        break;

                    case 'about_preview':
                        $sections[] = $this->buildAboutPreviewSection($section, $secondary, $images);
                        break;

                    case 'cta':
                        $sections[] = $this->buildCtaSection($section, $primary, $secondary, $accent, $images);
                        break;

                    case 'pricing':
                    case 'team':
                        if ($sectionTitle) {
                            $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 10)], [
                                $this->makeColumn(100, [$this->makeSectionHeading($sectionTitle, $secondary)]),
                            ]);
                        }
                        if (isset($section['items'])) {
                            $sections[] = $this->buildFeatureCardsSection($section['items'], $primary, $secondary);
                        }
                        break;

                    case 'faq':
                        $faqWidgets = [];
                        if ($sectionTitle) {
                            $faqWidgets[] = $this->makeSectionHeading($sectionTitle, $secondary);
                            $faqWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 20]]);
                        }
                        foreach ($section['items'] ?? [] as $item) {
                            $question = $item['question'] ?? $item['title'] ?? '';
                            $answer = $item['answer'] ?? $item['description'] ?? $item['content'] ?? '';
                            $faqWidgets[] = $this->makeWidget('toggle', [
                                'tabs' => [['_id' => $this->generateElementorId(), 'tab_title' => $question, 'tab_content' => '<p>' . e($answer) . '</p>']],
                            ]);
                        }
                        $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 20)], [
                            $this->makeColumn(100, $faqWidgets),
                        ]);
                        break;

                    case 'stats':
                        $statsColumns = [];
                        foreach ($section['items'] ?? [] as $item) {
                            $number = $item['title'] ?? $item['number'] ?? '0';
                            $label = $item['description'] ?? $item['label'] ?? '';
                            $statsColumns[] = $this->makeColumn(25, [
                                $this->makeWidget('counter', [
                                    'ending_number' => preg_replace('/[^0-9]/', '', $number) ?: '100',
                                    'title' => $label,
                                    'number_color' => $primary,
                                    'typography_typography' => 'custom',
                                    'typography_font_size' => ['unit' => 'px', 'size' => 48],
                                    'typography_font_weight' => '800',
                                ]),
                            ]);
                        }
                        if (!empty($statsColumns)) {
                            $settings = [
                                'background_background' => 'classic',
                                'background_color' => '#F8F9FA',
                                'padding' => $this->makePadding(80, 20),
                            ];
                            if ($sectionTitle) {
                                // Add title as its own section first
                                $sections[] = $this->makeSection(['background_background' => 'classic', 'background_color' => '#F8F9FA', 'padding' => $this->makePadding(80, 10)], [
                                    $this->makeColumn(100, [$this->makeSectionHeading($sectionTitle, $secondary)]),
                                ]);
                            }
                            $sections[] = $this->makeSection($settings, $statsColumns);
                        }
                        break;

                    case 'gallery':
                        $galleryWidgets = [];
                        if ($sectionTitle) {
                            $galleryWidgets[] = $this->makeSectionHeading($sectionTitle, $secondary);
                            $galleryWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 20]]);
                        }
                        // Gallery images from Unsplash
                        $galleryImages = [];
                        for ($i = 1; $i <= 3; $i++) {
                            if (isset($images["gallery_{$i}"])) {
                                $galleryImages[] = ['url' => $images["gallery_{$i}"]];
                            }
                        }
                        if (!empty($galleryImages)) {
                            $galleryWidgets[] = $this->makeWidget('image-gallery', [
                                'gallery' => $galleryImages,
                                'gallery_columns' => '3',
                                'gallery_link' => 'none',
                            ]);
                        } elseif (isset($section['items'])) {
                            // Fallback to items as cards
                            $sections[] = $this->buildFeatureCardsSection($section['items'], $primary, $secondary);
                            break;
                        }
                        $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 20)], [
                            $this->makeColumn(100, $galleryWidgets),
                        ]);
                        break;

                    case 'contact_form':
                        $contactWidgets = [];
                        if ($sectionTitle) {
                            $contactWidgets[] = $this->makeSectionHeading($sectionTitle, $secondary);
                            $contactWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 16]]);
                        }
                        if (isset($section['subtitle'])) {
                            $contactWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="text-align:center;color:#6b7280;font-size:16px;">' . e($section['subtitle']) . '</p>']);
                        }
                        $contactHtml = '';
                        if (isset($section['address'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Address:</strong> ' . e($section['address']) . '</p>';
                        if (isset($section['phone'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Phone:</strong> ' . e($section['phone']) . '</p>';
                        if (isset($section['email'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Email:</strong> ' . e($section['email']) . '</p>';
                        if ($contactHtml) {
                            $contactWidgets[] = $this->makeWidget('text-editor', ['editor' => '<div style="font-size:16px;line-height:1.7;color:#4b5563;">' . $contactHtml . '</div>']);
                        }
                        $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 20)], [
                            $this->makeColumn(100, $contactWidgets),
                        ]);
                        break;

                    case 'content':
                        $cWidgets = [];
                        if ($sectionTitle) {
                            $cWidgets[] = $this->makeHeading($sectionTitle, 'h2', 'left', $secondary);
                            $cWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 12]]);
                        }
                        if (isset($section['content'])) {
                            $cWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="font-size:16px;line-height:1.8;color:#4b5563;">' . e($section['content']) . '</p>']);
                        }
                        $sections[] = $this->makeSection(['padding' => $this->makePadding(40, 20)], [
                            $this->makeColumn(100, $cWidgets),
                        ]);
                        break;

                    default:
                        $genericWidgets = [];
                        if ($sectionTitle) {
                            $genericWidgets[] = $this->makeSectionHeading($sectionTitle, $secondary);
                        }
                        if (isset($section['items'])) {
                            foreach ($section['items'] as $item) {
                                $genericWidgets[] = $this->makeWidget('text-editor', [
                                    'editor' => '<h3 style="font-weight:600;margin-bottom:8px;">' . e($item['title'] ?? $item['name'] ?? '') . '</h3><p style="color:#6b7280;line-height:1.7;">' . e($item['description'] ?? $item['content'] ?? '') . '</p>',
                                ]);
                            }
                        }
                        if (isset($section['content'])) {
                            $genericWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="color:#4b5563;line-height:1.7;">' . e($section['content']) . '</p>']);
                        }
                        $sections[] = $this->makeSection(['padding' => $this->makePadding(60, 20)], [
                            $this->makeColumn(100, $genericWidgets),
                        ]);
                        break;
                }
            }
        }

        // Top-level contact fields
        if (isset($pageData['address']) || isset($pageData['phone']) || isset($pageData['email'])) {
            $contactHtml = '';
            if (isset($pageData['address'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Address:</strong> ' . e($pageData['address']) . '</p>';
            if (isset($pageData['phone'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Phone:</strong> ' . e($pageData['phone']) . '</p>';
            if (isset($pageData['email'])) $contactHtml .= '<p style="margin-bottom:12px;"><strong>Email:</strong> ' . e($pageData['email']) . '</p>';

            $contactWidgets = [];
            if (isset($pageData['subtitle'])) {
                $contactWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="text-align:center;font-size:16px;color:#6b7280;">' . e($pageData['subtitle']) . '</p>']);
            }
            $contactWidgets[] = $this->makeWidget('text-editor', ['editor' => '<div style="font-size:16px;line-height:1.7;">' . $contactHtml . '</div>']);
            $sections[] = $this->makeSection(['padding' => $this->makePadding(80, 20)], [
                $this->makeColumn(100, $contactWidgets),
            ]);
        }

        return $sections;
    }

    // ── Helper: make a widget element ──
    private function makeWidget(string $type, array $settings): array
    {
        return ['id' => $this->generateElementorId(), 'elType' => 'widget', 'widgetType' => $type, 'settings' => $settings];
    }

    // ── Helper: make a section element ──
    private function makeSection(array $settings, array $columns): array
    {
        return ['id' => $this->generateElementorId(), 'elType' => 'section', 'settings' => $settings, 'elements' => $columns];
    }

    // ── Helper: make a column element ──
    private function makeColumn(int $size, array $widgets): array
    {
        return ['id' => $this->generateElementorId(), 'elType' => 'column', 'settings' => ['_column_size' => $size], 'elements' => $widgets];
    }

    // ── Helper: consistent padding ──
    private function makePadding(int $topBottom, int $leftRight = 0): array
    {
        return ['unit' => 'px', 'top' => (string) $topBottom, 'right' => (string) $leftRight, 'bottom' => (string) $topBottom, 'left' => (string) $leftRight];
    }

    // ── Helper: border radius ──
    private function makeRadius(int $px): array
    {
        return ['unit' => 'px', 'top' => (string) $px, 'right' => (string) $px, 'bottom' => (string) $px, 'left' => (string) $px];
    }

    // ── Helper: styled heading widget ──
    private function makeHeading(string $title, string $tag = 'h2', string $align = 'center', string $color = '#1a1a1a'): array
    {
        return $this->makeWidget('heading', [
            'title' => $title,
            'header_size' => $tag,
            'align' => $align,
            'title_color' => $color,
            'typography_typography' => 'custom',
            'typography_font_size' => ['unit' => 'px', 'size' => $tag === 'h1' ? 52 : ($tag === 'h2' ? 36 : 24)],
            'typography_font_weight' => $tag === 'h1' ? '800' : '700',
            'typography_line_height' => ['unit' => 'em', 'size' => 1.2],
        ]);
    }

    // ── Helper: section heading with subtitle line ──
    private function makeSectionHeading(string $title, string $color): array
    {
        return $this->makeHeading($title, 'h2', 'center', $color);
    }

    // ── About preview: two-column (image + text) ──
    private function buildAboutPreviewSection(array $section, string $secondary, array $images = []): array
    {
        $sectionTitle = $section['title'] ?? '';
        $aboutImage = $images['about'] ?? null;

        if ($aboutImage) {
            // Two-column layout: image left, text right
            $leftCol = $this->makeColumn(50, [
                $this->makeWidget('image', [
                    'image' => ['url' => $aboutImage],
                    'image_size' => 'full',
                    'border_radius' => $this->makeRadius(12),
                ]),
            ]);

            $rightWidgets = [];
            if ($sectionTitle) {
                $rightWidgets[] = $this->makeHeading($sectionTitle, 'h2', 'left', $secondary);
                $rightWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 12]]);
            }
            if (isset($section['content'])) {
                $rightWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="font-size:16px;line-height:1.8;color:#4b5563;">' . e($section['content']) . '</p>']);
            }
            $rightCol = $this->makeColumn(50, $rightWidgets);

            return $this->makeSection([
                'background_background' => 'classic',
                'background_color' => '#F8F9FA',
                'padding' => $this->makePadding(80, 20),
            ], [$leftCol, $rightCol]);
        }

        // No image — single column
        $widgets = [];
        if ($sectionTitle) {
            $widgets[] = $this->makeSectionHeading($sectionTitle, $secondary);
            $widgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 16]]);
        }
        if (isset($section['content'])) {
            $widgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="text-align:center;font-size:16px;line-height:1.8;color:#4b5563;max-width:700px;margin:0 auto;">' . e($section['content']) . '</p>']);
        }
        return $this->makeSection([
            'background_background' => 'classic',
            'background_color' => '#F8F9FA',
            'padding' => $this->makePadding(80, 20),
        ], [$this->makeColumn(100, $widgets)]);
    }

    // ── Feature cards with background, border, shadow ──
    private function buildFeatureCardsSection(array $items, string $primaryColor, string $secondaryColor): array
    {
        $columnSize = count($items) > 0 ? (int) floor(100 / min(count($items), 3)) : 100;
        $allSections = [];

        foreach (array_chunk($items, 3) as $chunk) {
            $rowColumns = [];
            foreach ($chunk as $item) {
                $rowColumns[] = $this->makeColumn($columnSize, [
                    $this->makeWidget('icon-box', [
                        'title_text' => $item['title'] ?? $item['name'] ?? '',
                        'description_text' => ($item['description'] ?? $item['content'] ?? '') . (isset($item['price']) ? "\n\nPrice: " . $item['price'] : ''),
                        'icon' => ['value' => 'fas fa-' . ($item['icon'] ?? 'star'), 'library' => 'fa-solid'],
                        'primary_color' => $primaryColor,
                        'title_typography_typography' => 'custom',
                        'title_typography_font_size' => ['unit' => 'px', 'size' => 18],
                        'title_typography_font_weight' => '600',
                        'description_typography_typography' => 'custom',
                        'description_typography_font_size' => ['unit' => 'px', 'size' => 14],
                        'icon_space' => ['unit' => 'px', 'size' => 16],
                    ]),
                ]);
            }
            $allSections[] = $this->makeSection([
                'padding' => $this->makePadding(20, 15),
                'gap' => 'extended',
            ], $rowColumns);
        }

        return count($allSections) === 1 ? $allSections[0] : $this->makeSection(
            ['padding' => $this->makePadding(20, 15)],
            $allSections[0]['elements'] ?? []
        );
    }

    // ── CTA section with background image + overlay ──
    private function buildCtaSection(array $section, string $primary, string $secondary, string $accent, array $images = []): array
    {
        $sectionTitle = $section['title'] ?? '';
        $ctaWidgets = [];
        if ($sectionTitle) {
            $ctaWidgets[] = $this->makeHeading($sectionTitle, 'h2', 'center', '#FFFFFF');
            $ctaWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 12]]);
        }
        if (isset($section['subtitle'])) {
            $ctaWidgets[] = $this->makeWidget('text-editor', ['editor' => '<p style="text-align:center;color:rgba(255,255,255,0.9);font-size:18px;max-width:600px;margin:0 auto;">' . e($section['subtitle']) . '</p>']);
            $ctaWidgets[] = $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 20]]);
        }
        if (isset($section['button_text'])) {
            $ctaWidgets[] = $this->makeWidget('button', [
                'text' => $section['button_text'],
                'align' => 'center',
                'background_color' => '#FFFFFF',
                'button_text_color' => $primary,
                'border_radius' => $this->makeRadius(8),
                'typography_typography' => 'custom',
                'typography_font_weight' => '700',
                'button_padding' => ['unit' => 'px', 'top' => '14', 'right' => '36', 'bottom' => '14', 'left' => '36'],
            ]);
        }

        $settings = ['padding' => $this->makePadding(80, 20)];
        $ctaBg = $images['cta_bg'] ?? null;
        if ($ctaBg) {
            $settings['background_background'] = 'classic';
            $settings['background_image'] = ['url' => $ctaBg];
            $settings['background_position'] = 'center center';
            $settings['background_size'] = 'cover';
            $settings['background_overlay_background'] = 'gradient';
            $settings['background_overlay_color'] = $primary;
            $settings['background_overlay_color_b'] = $secondary;
            $settings['background_overlay_opacity'] = ['unit' => 'px', 'size' => 0.85];
        } else {
            $settings['background_background'] = 'gradient';
            $settings['background_color'] = $primary;
            $settings['background_color_b'] = $secondary;
            $settings['background_gradient_angle'] = ['unit' => 'deg', 'size' => 135];
        }

        return $this->makeSection($settings, [$this->makeColumn(100, $ctaWidgets)]);
    }

    // ── Testimonials with card-style per column ──
    private function buildTestimonialsSection(array $section, string $secondary, array $images = []): array
    {
        $sectionTitle = $section['title'] ?? '';
        $items = $section['items'] ?? [];
        $columnSize = count($items) > 0 ? (int) floor(100 / min(count($items), 3)) : 100;
        $columns = [];

        foreach (array_slice($items, 0, 3) as $item) {
            $columns[] = $this->makeColumn($columnSize, [
                $this->makeWidget('testimonial', [
                    'testimonial_content' => $item['content'] ?? '',
                    'testimonial_name' => $item['name'] ?? '',
                    'testimonial_job' => $item['role'] ?? '',
                    'content_typography_typography' => 'custom',
                    'content_typography_font_size' => ['unit' => 'px', 'size' => 15],
                    'content_typography_font_style' => 'italic',
                    'name_typography_typography' => 'custom',
                    'name_typography_font_weight' => '700',
                ]),
            ]);
        }

        $settings = [
            'background_background' => 'classic',
            'background_color' => '#F8F9FA',
            'padding' => $this->makePadding(80, 20),
        ];

        // Build with title
        if ($sectionTitle) {
            $titleSection = $this->makeSection(['background_background' => 'classic', 'background_color' => '#F8F9FA', 'padding' => $this->makePadding(80, 10)], [
                $this->makeColumn(100, [$this->makeSectionHeading($sectionTitle, $secondary)]),
            ]);
            // We can't merge sections easily, so return just the columns section
            // The caller should handle title separately — but for simplicity, let's add title as first column widget
        }

        // Prepend title inside by wrapping
        if ($sectionTitle) {
            array_unshift($columns, $this->makeColumn(100, [
                $this->makeSectionHeading($sectionTitle, $secondary),
                $this->makeWidget('spacer', ['space' => ['unit' => 'px', 'size' => 24]]),
            ]));
        }

        return $this->makeSection($settings, $columns);
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
        // Delete WordPress site (DB + files)
        try {
            $this->wordpressService->deleteSite($website);
        } catch (\Exception $e) {
            \Log::warning("WP site delete failed for {$website->subdomain}: {$e->getMessage()}");
        }

        // Delete DNS records (don't let DNS failure block deletion)
        try {
            foreach ($website->domains as $domain) {
                if ($domain->cloudflare_record_id) {
                    $this->dnsService->deleteRecord($domain->cloudflare_record_id);
                }
            }
        } catch (\Exception $e) {
            \Log::warning("DNS delete failed for {$website->subdomain}: {$e->getMessage()}");
        }

        ActivityLog::log('website.deleted', "Website deleted: {$website->name}", $website);
        $website->delete();

        return ['success' => true, 'message' => 'Website deleted'];
    }
}
