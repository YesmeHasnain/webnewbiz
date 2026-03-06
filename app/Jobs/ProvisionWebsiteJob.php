<?php

namespace App\Jobs;

use App\Models\Website;
use App\Models\Domain;
use App\Services\WordPressService;
use App\Services\AIContentService;
use App\Services\IdeogramService;
use App\Services\UnsplashService;
use App\Services\ThemeMatcherService;
use App\Services\ContentSwapperService;
use App\Services\Layouts\AbstractLayout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\WebsiteBuildFailedNotification;
use App\Notifications\WebsiteReadyNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProvisionWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $backoff = 30;
    public int $timeout = 600;

    public function __construct(
        public Website $website,
        public array $params,
    ) {}

    public function handle(
        WordPressService $wordpressService,
        AIContentService $aiService,
        IdeogramService $ideogramService,
        UnsplashService $unsplashService,
    ): void {
        $this->handleLayoutProvisioning($wordpressService, $aiService, $ideogramService, $unsplashService);
    }

    /**
     * Layout-based provisioning: create WP site + inject Elementor pages from Layout classes.
     */
    private function handleLayoutProvisioning(
        WordPressService $wordpressService,
        AIContentService $aiService,
        IdeogramService $ideogramService,
        UnsplashService $unsplashService,
    ): void {
        $htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');

        try {
            // Step 0: Resolve layout
            $themeSlug = $this->params['theme'] ?? $this->website->ai_theme ?? 'auto';
            if ($themeSlug === 'auto' || !AbstractLayout::resolve($themeSlug)) {
                $matcher = new ThemeMatcherService();
                $themeSlug = $matcher->match(
                    $this->params['business_type'] ?? 'general',
                    $this->params['prompt'] ?? ''
                );
            }
            Log::info("Layout selected: '{$themeSlug}' for website {$this->website->id}");
            $this->website->update(['ai_theme' => $themeSlug, 'ai_style' => $themeSlug]);

            // Step 1: Generate AI content
            $aiContent = null;
            if ($this->params['prompt'] ?? $this->params['business_type'] ?? false) {
                Log::info("Generating AI content for website {$this->website->id}");
                $pagesStructure = $this->params['pages_structure'] ?? null;
                $aiResult = $aiService->generateWebsiteContent(
                    $this->params['business_type'] ?? 'general',
                    $this->params['name'],
                    $themeSlug,
                    $pagesStructure,
                    $this->params['prompt'] ?? null
                );
                if ($aiResult['success']) {
                    $aiContent = $aiResult['data'];
                } else {
                    Log::warning("AI content generation failed: " . ($aiResult['message'] ?? 'Unknown'));
                }
            }

            // Step 2: Create WordPress site locally
            Log::info("Creating WordPress site for website {$this->website->id}");
            $wpResult = $wordpressService->createSite($this->website);
            if (!$wpResult['success']) {
                throw new \RuntimeException("WordPress install failed: " . ($wpResult['message'] ?? 'Unknown error'));
            }
            Log::info("WordPress installed for website {$this->website->id}");

            $this->website->refresh();

            $sitePath = $htdocsPath . '/' . $this->website->subdomain;
            $yearMonth = date('Y/m');
            $uploadsDir = $sitePath . '/wp-content/uploads/' . $yearMonth;
            if (!File::isDirectory($uploadsDir)) {
                File::makeDirectory($uploadsDir, 0755, true);
            }
            $siteUrl = $this->website->url;
            $uploadsUrl = $siteUrl . '/wp-content/uploads/' . $yearMonth;

            // Step 3-5: Generate all images IN PARALLEL (logo + favicon + website images)
            $logoUrl = null;
            $imageUrls = [];

            Log::info("Starting parallel image generation for website {$this->website->id}");
            $parallelStart = microtime(true);

            // First, try stock images (Unsplash API → Pexels API → Curated Unsplash by business type)
            try {
                $stockImages = $unsplashService->getWebsiteImages(
                    $this->params['name'],
                    $this->params['business_type'] ?? 'general',
                    $uploadsDir,
                    $this->params['prompt'] ?? null
                );
                foreach ($stockImages as $key => $localPath) {
                    if ($localPath && File::exists($localPath)) {
                        $imageUrls[$key] = $uploadsUrl . '/' . basename($localPath);
                    }
                }
                Log::info("Stock images: got " . count($imageUrls) . " images (business-type matched)");
            } catch (\Exception $e) {
                Log::warning("Stock images failed: {$e->getMessage()}");
            }

            // Build batch of ALL Ideogram requests to run in parallel
            $batchRequests = [];
            $businessName = $this->params['name'];
            $businessType = $this->params['business_type'] ?? 'general';
            $style = $this->params['style'] ?? 'modern';
            $styleHint = match ($style) {
                'modern' => 'clean, modern, minimalist design',
                'classic' => 'warm, classic, timeless aesthetic',
                'minimal' => 'ultra minimal, white space, subtle',
                'bold' => 'bold colors, high contrast, energetic',
                'elegant' => 'luxurious, refined, sophisticated',
                'gradient', 'horizon' => 'gradient futuristic, travel vibrant colors',
                'dark', 'ember' => 'dark moody, nightlife warm tones',
                'industrial', 'slate' => 'industrial brutalist, raw textured',
                'beauty', 'bloom' => 'soft, feminine, rounded wellness aesthetic',
                'fintech', 'vertex' => 'sharp, modern fintech with dark accents',
                'gallery', 'canvas' => 'minimal editorial, gallery-style clean layout',
                'clinical', 'pulse' => 'clean clinical, medical blue tones',
                'trades', 'forge' => 'bold industrial, heavy construction feel',
                'sleek', 'nova' => 'sleek modern, e-commerce product focused',
                'coaching', 'aura' => 'warm sophisticated, personal brand coaching',
                'academic', 'summit' => 'structured academic, clean educational',
                'neon' => 'neon glow, electric futuristic, dark with bright accents',
                'royal', 'heritage', 'crest' => 'regal heritage, luxury gold and deep tones',
                'adventure', 'outdoor', 'drift' => 'rugged adventure, earthy outdoor tones',
                'chic', 'velvet', 'fashion2' => 'chic fashion, deep plum boutique elegance',
                'launch', 'spark', 'startup2' => 'energetic startup, bright innovative tones',
                'eco', 'green', 'grove' => 'natural eco-friendly, organic earthy greens',
                'urban', 'metro', 'city' => 'urban city, concrete and bold metropolitan',
                'tropical', 'resort', 'coral' => 'tropical resort, warm turquoise sunset',
                'gaming', 'pixel' => 'digital gaming, neon on dark cyber aesthetic',
                'global', 'international', 'mosaic' => 'global diverse, warm earth multicultural',
                'playful', 'kids', 'prism' => 'bright playful, colorful fun kids aesthetic',
                'lumen', 'photo' => 'dramatic studio photography, high contrast light focused',
                'petal', 'floral' => 'soft floral pastels, garden nature gentle aesthetic',
                'nexus', 'ai', 'data', 'cyber' => 'cyber AI dark mode, data analytics futuristic',
                'haven', 'property', 'realty' => 'warm elegant property, real estate sophisticated',
                'rhythm', 'concert', 'festival' => 'dynamic music events, bold entertainment vibes',
                'sprout', 'sustainable' => 'organic farm, eco green sustainable natural',
                'pinnacle', 'gold', 'highend' => 'luxury premium gold accents, high-end sophisticated',
                default => 'professional, clean design',
            };
            $initial = strtoupper(substr(trim($businessName), 0, 1));

            // Always generate logo + favicon
            $batchRequests[] = [
                'key' => 'logo',
                'prompt' => "Vector-style flat design logo for '{$businessName}', a {$businessType} business. " . match ($style) { 'modern' => 'modern, minimalist', 'classic' => 'classic, timeless', 'minimal' => 'ultra minimalist, simple', 'bold', 'trades', 'forge', 'industrial', 'slate' => 'bold, striking', 'elegant', 'beauty', 'bloom', 'coaching', 'aura' => 'elegant, luxury', 'dark', 'ember', 'neon' => 'dark, moody', 'gradient', 'horizon' => 'vibrant, gradient-inspired', 'fintech', 'vertex' => 'sharp, fintech', 'gallery', 'canvas' => 'minimal, editorial', 'clinical', 'pulse' => 'clean, medical', 'sleek', 'nova' => 'sleek, modern', 'academic', 'summit' => 'structured, academic', 'tech' => 'futuristic, tech', 'royal', 'heritage', 'crest' => 'regal, heritage', 'adventure', 'outdoor', 'drift' => 'rugged, adventure', 'chic', 'velvet' => 'chic, fashion', 'launch', 'spark' => 'energetic, startup', 'eco', 'green', 'grove' => 'natural, eco', 'urban', 'metro' => 'urban, metropolitan', 'tropical', 'coral' => 'tropical, resort', 'gaming', 'pixel' => 'digital, gaming', 'global', 'mosaic' => 'global, diverse', 'playful', 'prism' => 'playful, colorful', 'lumen', 'photo' => 'dramatic, studio', 'petal', 'floral' => 'soft, floral', 'nexus', 'ai', 'cyber' => 'cyber, futuristic', 'haven', 'property' => 'elegant, property', 'rhythm', 'concert' => 'dynamic, music', 'sprout', 'sustainable' => 'natural, eco', 'pinnacle', 'gold' => 'premium, luxury', default => 'professional, clean' } . " style. Solid clean white background, readable text '{$businessName}', 2-3 colors maximum, no borders, no mockups, no 3D effects, no gradients, no shadows. Simple scalable design suitable for website header.",
                'aspect' => 'ASPECT_3_1',
                'style' => 'DESIGN',
            ];
            $batchRequests[] = [
                'key' => 'favicon',
                'prompt' => "Letter '{$initial}' in bold clean design, solid colored background, works perfectly at 32x32 pixels. Flat design, single letter favicon icon, no borders, no 3D, no gradients. Simple recognizable lettermark for a {$businessType} business.",
                'aspect' => 'ASPECT_1_1',
                'style' => 'DESIGN',
            ];

            // Add website images only if Unsplash didn't get enough
            $imageSlots = [
                'hero' => ['prompt' => "Editorial photography for a {$businessType} business called '{$businessName}'. Shallow depth of field, cinematic lighting, {$styleHint}. No text, no watermarks, no overlays. High-end professional photo.", 'aspect' => 'ASPECT_16_9'],
                'about' => ['prompt' => "Professional image representing a {$businessType} business called '{$businessName}'. Showing the essence of the business, team or workspace. {$styleHint}. Photorealistic, no text.", 'aspect' => 'ASPECT_1_1'],
                'services' => ['prompt' => "Professional image showcasing services of a {$businessType} business. {$styleHint}. Clean, professional, no text or watermarks.", 'aspect' => 'ASPECT_16_10'],
                'gallery1' => ['prompt' => "Modern workspace or project showcase for a {$businessType} business. Professional environment, {$styleHint}. No text, clean photo.", 'aspect' => 'ASPECT_16_9'],
                'gallery2' => ['prompt' => "Success and results imagery for a {$businessType} business. Achievement, growth concept. {$styleHint}. Photorealistic, no text.", 'aspect' => 'ASPECT_16_9'],
                'gallery3' => ['prompt' => "Interior or ambiance of a {$businessType} business. Inviting atmosphere, warm lighting, {$styleHint}. Photorealistic, no text.", 'aspect' => 'ASPECT_16_9'],
                'gallery4' => ['prompt' => "Happy customers or clients at a {$businessType} business. Genuine interaction, {$styleHint}. Photorealistic, no text.", 'aspect' => 'ASPECT_16_9'],
                'gallery5' => ['prompt' => "Products or offerings of a {$businessType} business displayed beautifully. {$styleHint}. Clean professional photo, no text.", 'aspect' => 'ASPECT_16_9'],
                'gallery6' => ['prompt' => "Quality craftsmanship or expertise of a {$businessType} business. Detail shot, {$styleHint}. Photorealistic, no text.", 'aspect' => 'ASPECT_16_9'],
                'team' => ['prompt' => "Professional diverse team working together in a modern office. Business professionals collaborating. Clean, bright, no text.", 'aspect' => 'ASPECT_16_10'],
            ];
            foreach ($imageSlots as $imgKey => $config) {
                if (!isset($imageUrls[$imgKey])) {
                    $batchRequests[] = [
                        'key' => $imgKey,
                        'prompt' => $config['prompt'],
                        'aspect' => $config['aspect'],
                        'style' => 'REALISTIC',
                    ];
                }
            }

            // Fire all Ideogram requests in parallel
            try {
                $batchResults = $ideogramService->generateBatch($batchRequests);

                // Process logo
                if (isset($batchResults['logo']) && $batchResults['logo']['success'] && ($batchResults['logo']['url'] ?? null)) {
                    $logoLocalPath = $ideogramService->downloadTo($batchResults['logo']['url'], $uploadsDir, 'logo.png');
                    if ($logoLocalPath) {
                        $logoUrl = $uploadsUrl . '/logo.png';
                        $wordpressService->setLogo($this->website, $logoUrl, $yearMonth . '/logo.png');
                        Log::info("Logo set for website {$this->website->id}");
                    }
                }

                // Process favicon
                if (isset($batchResults['favicon']) && $batchResults['favicon']['success'] && ($batchResults['favicon']['url'] ?? null)) {
                    $faviconLocalPath = $ideogramService->downloadTo($batchResults['favicon']['url'], $uploadsDir, 'favicon.png');
                    if ($faviconLocalPath) {
                        $faviconUrl = $uploadsUrl . '/favicon.png';
                        $wordpressService->setFavicon($this->website, $faviconUrl, $yearMonth . '/favicon.png');
                        Log::info("Favicon set for website {$this->website->id}");
                    }
                }

                // Process website images
                foreach (['hero', 'about', 'services', 'gallery1', 'gallery2', 'gallery3', 'gallery4', 'gallery5', 'gallery6', 'team'] as $imgKey) {
                    if (isset($imageUrls[$imgKey])) continue; // Already from Unsplash
                    if (isset($batchResults[$imgKey]) && $batchResults[$imgKey]['success'] && ($batchResults[$imgKey]['url'] ?? null)) {
                        $filename = "{$imgKey}-ai.png";
                        $localPath = $ideogramService->downloadTo($batchResults[$imgKey]['url'], $uploadsDir, $filename);
                        if ($localPath) {
                            $imageUrls[$imgKey] = $uploadsUrl . '/' . $filename;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Batch image generation failed: {$e->getMessage()}");
            }

            // Fallback: generate SVG logo/favicon if Ideogram didn't produce them
            if (!$logoUrl) {
                $primaryColor = $aiContent['colors']['primary'] ?? '#2563eb';
                $logoPath = $unsplashService->generateFallbackLogo($businessName, $primaryColor, $uploadsDir);
                if ($logoPath) {
                    $logoUrl = $uploadsUrl . '/logo.svg';
                    $wordpressService->setLogo($this->website, $logoUrl, $yearMonth . '/logo.svg');
                    Log::info("Fallback SVG logo set for website {$this->website->id}");
                }

                $faviconPath = $unsplashService->generateFallbackFavicon($businessName, $primaryColor, $uploadsDir);
                if ($faviconPath) {
                    $faviconUrl = $uploadsUrl . '/favicon.svg';
                    $wordpressService->setFavicon($this->website, $faviconUrl, $yearMonth . '/favicon.svg');
                    Log::info("Fallback SVG favicon set for website {$this->website->id}");
                }
            }

            $parallelEnd = microtime(true);
            Log::info("All images done in " . round($parallelEnd - $parallelStart, 1) . "s — " . count($imageUrls) . " images, logo=" . ($logoUrl ? 'yes' : 'no'));

            // Register all images as WordPress media attachments (so Elementor can resolve them by ID)
            $imageAttachmentIds = [];
            $dbName = $this->website->wp_db_name;
            if ($dbName) {
                foreach ($imageUrls as $key => $url) {
                    // Extract relative file path from URL (e.g. "2026/02/hero.jpg")
                    $relPath = null;
                    if (preg_match('#/wp-content/uploads/(.+)$#', $url, $m)) {
                        $relPath = $m[1];
                    }
                    $attachId = $wordpressService->createAttachment($dbName, $url, $key . '-image', $relPath ?? $key . '.jpg');
                    if ($attachId) {
                        $imageAttachmentIds[$key] = $attachId;
                        Log::info("Registered WP attachment for '{$key}': ID={$attachId}");
                    }
                }
            }

            // Merge images into AI content
            if ($aiContent) {
                $aiContent['images'] = $imageUrls;
                $aiContent['image_ids'] = $imageAttachmentIds;
                $aiContent['logo'] = $logoUrl;
                $this->website->update(['ai_generated_content' => $aiContent]);
            }

            // Step 6: Inject AI content + images into WordPress pages
            if ($aiContent) {
                $this->injectContent($wordpressService, $this->website, $aiContent, $imageUrls, $imageAttachmentIds);
                Log::info("Content injected for website {$this->website->id}");
            } else {
                Log::info("AI content unavailable — injecting Example template for website {$this->website->id}");
                $this->injectExampleContent($wordpressService);
            }

            // Step 6b: WooCommerce setup for e-commerce sites
            $bizType = strtolower($this->params['business_type'] ?? '');
            $promptText = strtolower($this->params['prompt'] ?? '');
            $isEcommerce = str_starts_with($bizType, 'ecommerce')
                || preg_match('/\b(online store|e-?commerce|sell products?|shopping|product catalog|shop online|web store|retail store|buy online|online shop)\b/i', $promptText);
            if ($isEcommerce) {
                try {
                    Log::info("Setting up WooCommerce for website {$this->website->id}");
                    $wordpressService->setupWooCommerce($this->website, $aiContent ?? []);

                    // Generate sample products via AI
                    $products = $aiService->generateProductContent(
                        $this->params['business_type'] ?? 'general',
                        $this->params['name'],
                        6
                    );

                    $dbName = $this->website->wp_db_name;
                    foreach ($products as $product) {
                        $productImageUrl = '';
                        // Try to generate product image
                        try {
                            $imgResult = $ideogramService->generateImage(
                                ($product['image_prompt'] ?? "Product photo of {$product['name']}") . '. Clean white background, product photography, no text.',
                                'ASPECT_1_1',
                                'REALISTIC'
                            );
                            if ($imgResult['success'] && ($imgResult['url'] ?? null)) {
                                $imgFilename = 'product-' . \Illuminate\Support\Str::slug($product['name']) . '.png';
                                $imgLocal = $ideogramService->downloadTo($imgResult['url'], $uploadsDir, $imgFilename);
                                if ($imgLocal) {
                                    $productImageUrl = $uploadsUrl . '/' . $imgFilename;
                                }
                            }
                        } catch (\Exception $e) {
                            Log::warning("Product image generation failed: {$e->getMessage()}");
                        }

                        // Fallback: Unsplash stock photo for product image
                        if (!$productImageUrl) {
                            try {
                                $searchQuery = ($product['name'] ?? 'product') . ' product';
                                $stockUrl = $unsplashService->searchUnsplash($searchQuery, 'squarish');
                                if (!$stockUrl) {
                                    $stockUrl = $unsplashService->searchPexels($searchQuery, 'square');
                                }
                                if ($stockUrl) {
                                    $imgFilename = 'product-' . \Illuminate\Support\Str::slug($product['name'] ?? 'item') . '.jpg';
                                    $localPath = $unsplashService->downloadImage($stockUrl, $uploadsDir, $imgFilename);
                                    if ($localPath) {
                                        $productImageUrl = $uploadsUrl . '/' . $imgFilename;
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::warning("Product stock image fallback failed: {$e->getMessage()}");
                            }
                        }

                        $wordpressService->createSampleProduct(
                            $dbName,
                            $product['name'] ?? 'Sample Product',
                            $product['description'] ?? '',
                            (float) ($product['price'] ?? 29.99),
                            $productImageUrl,
                            $product['category'] ?? ''
                        );
                    }

                    // Add "Shop" link to existing nav menu (HFE header)
                    $shopPages = $wordpressService->getPages($dbName);
                    $siteUrl = $this->website->url ?: 'http://localhost/' . $this->website->subdomain;
                    foreach ($shopPages as $page) {
                        if (($page['post_title'] ?? '') === 'Shop') {
                            $wordpressService->addNavMenuItem($dbName, $siteUrl, (int) $page['ID'], 'Shop', 'shop');
                            break;
                        }
                    }

                    // Activate WooCommerce plugin in database
                    $pdo = new \PDO("mysql:host=" . config('database.connections.mysql.host', '127.0.0.1') . ";dbname={$dbName}", config('database.connections.mysql.username', 'root'), config('database.connections.mysql.password', ''));
                    $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name = 'active_plugins'");
                    $plugins = @unserialize($stmt->fetchColumn()) ?: [];
                    if (!in_array('woocommerce/woocommerce.php', $plugins)) {
                        $plugins[] = 'woocommerce/woocommerce.php';
                        $pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote(serialize($plugins)) . " WHERE option_name = 'active_plugins'");
                    }

                    Log::info("WooCommerce setup complete for website {$this->website->id}");
                } catch (\Exception $e) {
                    Log::warning("WooCommerce setup failed: {$e->getMessage()}");
                }
            }

            // Step 7: Create domain record (local mode)
            $localMode = config('webnewbiz.local_mode', true);
            if (!$localMode) {
                $this->setupDnsAndSsl();
            } else {
                Domain::create([
                    'website_id' => $this->website->id,
                    'domain' => 'localhost/' . $this->website->subdomain,
                    'type' => 'subdomain',
                    'is_primary' => true,
                    'dns_status' => 'active',
                ]);
            }

            // Step 8: Warm up the site to trigger Elementor CSS generation
            try {
                $siteUrl = $this->website->url ?: 'http://localhost/' . $this->website->subdomain;
                Log::info("Warming up site to generate Elementor CSS: {$siteUrl}");
                $response = \Illuminate\Support\Facades\Http::timeout(30)->get($siteUrl);
                Log::info("Warmup response: HTTP " . $response->status());
            } catch (\Exception $e) {
                Log::warning("Site warmup failed (non-critical): {$e->getMessage()}");
            }

            // Mark as active
            $this->website->update(['status' => 'active']);
            Log::info("Website {$this->website->id} provisioned successfully: {$this->website->url}");

            // Notify user
            $this->website->user->notify(new WebsiteReadyNotification($this->website));

        } catch (\Exception $e) {
            Log::error("Website provisioning failed for {$this->website->id}: {$e->getMessage()}");
            $this->website->update(['status' => 'error']);
        }
    }

    private function setupDnsAndSsl(): void
    {
        $dnsService = app(\App\Services\DnsService::class);
        $sslService = app(\App\Services\SslService::class);

        $domain = $this->website->subdomain . config('webnewbiz.subdomain_suffix', '.webnewbiz.com');
        $server = $this->website->server;
        $dnsResult = ['success' => false, 'data' => []];

        if ($server) {
            $dnsResult = $dnsService->createRecord('A', $this->website->subdomain, $server->ip_address);
        }

        $domainRecord = Domain::create([
            'website_id' => $this->website->id,
            'domain' => $domain,
            'type' => 'subdomain',
            'is_primary' => true,
            'cloudflare_record_id' => $dnsResult['data']['id'] ?? null,
            'dns_status' => ($dnsResult['success'] ?? false) ? 'active' : 'pending',
        ]);

        if ($dnsResult['success'] ?? false) {
            $sslService->issueCertificate($domainRecord);
        }
    }

    private function injectContent(WordPressService $wordpressService, Website $website, array $content, array $images = [], array $imageIds = []): void
    {
        try {
            if (isset($content['site_title'])) {
                $wordpressService->updateOption($website, 'blogname', $content['site_title']);
            }
            if (isset($content['tagline'])) {
                $wordpressService->updateOption($website, 'blogdescription', $content['tagline']);
            }

            // Resolve layout
            $layoutSlug = $website->ai_theme ?? 'azure';
            $layout = AbstractLayout::resolve($layoutSlug);
            if (!$layout) {
                Log::warning("Layout '{$layoutSlug}' not found, falling back to azure");
                $layout = AbstractLayout::resolve('azure');
            }

            $pages = $content['pages'] ?? [];
            $homePostId = null;
            $createdPages = [];
            $dbName = $website->wp_db_name;

            // Merge attachment IDs into images array
            $imagesWithIds = $images;
            foreach ($imageIds as $key => $attachId) {
                $imagesWithIds[$key . '_id'] = $attachId;
            }

            foreach ($pages as $slug => $pageData) {
                $title = $pageData['title'] ?? ucfirst($slug);

                // Map common page slugs to layout page types
                $pageType = match ($slug) {
                    'home' => 'home',
                    'about', 'about-us' => 'about',
                    'services', 'features', 'programs' => 'services',
                    'portfolio', 'gallery', 'work', 'projects' => 'portfolio',
                    'contact', 'contact-us' => 'contact',
                    default => 'about', // fallback to about layout for custom pages
                };

                // Build Elementor data using the Layout class
                $elementorData = $layout->buildPage($pageType, $content, $imagesWithIds);

                Log::info("Built Elementor data for page '{$slug}' using layout '{$layoutSlug}': " . strlen(json_encode($elementorData)) . " bytes");

                // Build fallback HTML content
                $htmlContent = $this->buildPageHtml($slug, $pageData, $images, $content);

                // CREATE new page with HFE template
                $pageTemplate = 'elementor_header_footer';
                $result = $wordpressService->createElementorPage($website, $title, $htmlContent, $elementorData, $pageTemplate);
                Log::info("Page '{$slug}' creation result: " . json_encode($result));

                if (($result['success'] ?? false) && isset($result['post_id'])) {
                    $createdPages[] = [
                        'post_id' => $result['post_id'],
                        'title' => $title,
                        'slug' => $slug,
                    ];

                    if ($slug === 'home') {
                        $homePostId = $result['post_id'];
                    }
                }
            }

            // Set homepage as static front page
            if ($homePostId) {
                $wordpressService->updateOption($website, 'show_on_front', 'page');
                $wordpressService->updateOption($website, 'page_on_front', (string) $homePostId);
            }

            // Create navigation menu
            if (!empty($createdPages) && $dbName) {
                $siteUrl = $website->url ?: 'http://localhost/' . $website->subdomain;
                $wordpressService->createNavigationMenu($dbName, $siteUrl, $createdPages, $layoutSlug);
                Log::info("Navigation menu created for website {$website->id}");
            }

            // Setup HFE header/footer templates using Layout
            $this->setupHfeHeaderFooter($wordpressService, $website, $content, $imagesWithIds);

            // Setup theme colors/typography
            if ($dbName) {
                $dbHost = config('database.connections.mysql.host', '127.0.0.1');
                $dbUser = config('database.connections.mysql.username', 'root');
                $dbPass = config('database.connections.mysql.password', '');
                $pdo = new \PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $colors = $content['colors'] ?? $layout->colors();
                $wordpressService->setupTheme($pdo, $website->name, $colors, $layoutSlug, $layoutSlug);

                // Store chatbot config
                $wordpressService->setOption($dbName, 'webnewbiz_site_id', (string) $website->id);
                $wordpressService->setOption($dbName, 'webnewbiz_api_url', config('app.url', 'http://127.0.0.1:8000'));
                $wordpressService->setOption($dbName, 'webnewbiz_colors', json_encode($colors));
            }
        } catch (\Exception $e) {
            Log::error("Content injection failed: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
                'website_id' => $website->id,
            ]);
        }
    }

    /**
     * Fallback: generate proper template-based pages when AI content is unavailable.
     * Uses hardcoded fallback content + Elementor Free template system (NOT 10Web Pro dumps).
     */
    private function injectExampleContent(WordPressService $wordpressService): void
    {
        try {
            $website = $this->website;
            $dbName = $website->wp_db_name;
            if (!$dbName) {
                Log::error("injectExampleContent: No database name for website {$website->id}");
                return;
            }

            $businessName = $this->params['name'] ?? $this->website->name ?? 'My Website';
            $businessType = $this->params['business_type'] ?? 'business';

            // Build fallback content that mimics AI-generated structure
            $pagesStructure = $this->params['pages_structure'] ?? null;
            $fallbackContent = $this->buildFallbackContent($businessName, $businessType, $pagesStructure);

            // Use layout colors if available
            $layoutSlug = $website->ai_theme ?? 'azure';
            $layout = AbstractLayout::resolve($layoutSlug);
            if ($layout) {
                $colors = $layout->colors();
                $fallbackContent['colors'] = [
                    'primary' => $colors['primary'] ?? '#2563eb',
                    'secondary' => $colors['secondary'] ?? '#1e40af',
                    'accent' => $colors['accent'] ?? '#60a5fa',
                ];
            }

            Log::info("Using template-based fallback content for website {$website->id} (AI unavailable)");

            // Re-use the normal injectContent pipeline with fallback content
            $this->injectContent($wordpressService, $website, $fallbackContent, [], []);

        } catch (\Exception $e) {
            Log::error("Fallback content injection failed: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
                'website_id' => $this->website->id,
            ]);
        }
    }

    /**
     * Build hardcoded fallback content matching the AI content structure.
     * Used when AI content generation fails — produces a professional-looking site.
     * When $pagesStructure is provided, generates content for user's custom pages/sections.
     */
    private function buildFallbackContent(string $businessName, string $businessType, ?array $pagesStructure = null): array
    {
        $typeLabel = ucfirst(str_replace(['-', '_'], ' ', $businessType));

        $base = [
            'site_title' => $businessName,
            'tagline' => "Professional {$typeLabel} Services You Can Trust",
            'colors' => ['primary' => '#2563eb', 'secondary' => '#1e40af', 'accent' => '#60a5fa'],
            'fonts' => ['heading' => 'Poppins', 'body' => 'Inter'],
        ];

        // If no pages_structure from the user, use the hardcoded defaults
        if (empty($pagesStructure)) {
            $base['pages'] = $this->buildDefaultFallbackPages($businessName, $businessType, $typeLabel);
            return $base;
        }

        // Build pages from user's pages_structure
        $pages = [];
        foreach ($pagesStructure as $pageDef) {
            $title = $pageDef['title'] ?? 'Page';
            $slug = $pageDef['slug'] ?? strtolower(str_replace(' ', '-', $title));
            $userSections = $pageDef['sections'] ?? [];

            $pageData = ['title' => $title];

            if ($slug === 'home') {
                $pageData['hero_title'] = "Elevating Your {$typeLabel} Experience";
                $pageData['hero_subtitle'] = "At {$businessName}, we deliver exceptional results with a commitment to quality and innovation.";
                $pageData['hero_cta'] = 'Get Started Today';
            }

            // Build sections array from user's section types
            $sections = [];
            foreach ($userSections as $sec) {
                $sType = $sec['type'] ?? 'content';
                $sLabel = $sec['label'] ?? ucfirst($sType);

                // Skip hero for home (handled by buildPremiumHero), keep for child pages
                if ($sType === 'hero' && $slug === 'home') continue;

                $sections[] = $this->buildFallbackSection($sType, $sLabel, $businessName, $typeLabel, $title);
            }

            $pageData['sections'] = $sections;

            // Add contact info for contact pages
            if ($slug === 'contact') {
                $pageData['address'] = '123 Business Avenue, Suite 100';
                $pageData['phone'] = '(555) 123-4567';
                $pageData['email'] = 'info@' . strtolower(str_replace(' ', '', $businessName)) . '.com';
                $pageData['subtitle'] = 'Get in touch with our team — we would love to hear from you.';
            }

            // Add about content for about pages
            if ($slug === 'about') {
                $pageData['content'] = "{$businessName} was founded with a clear mission: to provide exceptional {$typeLabel} services that make a real difference.";
                $pageData['mission'] = "To deliver innovative {$typeLabel} solutions that empower businesses and exceed expectations.";
                $pageData['vision'] = 'To be the most trusted partner for businesses seeking excellence and growth.';
            }

            $pages[$slug] = $pageData;
        }

        $base['pages'] = $pages;
        return $base;
    }

    /**
     * Generate fallback content for a single section type.
     */
    private function buildFallbackSection(string $type, string $label, string $businessName, string $typeLabel, string $pageTitle): array
    {
        return match ($type) {
            'hero' => [
                'type' => 'hero',
                'title' => $pageTitle,
                'subtitle' => "Discover what {$businessName} has to offer.",
            ],
            'features' => [
                'type' => 'features',
                'title' => $label ?: "Why Choose {$businessName}",
                'items' => [
                    ['title' => 'Expert Team', 'description' => 'Our seasoned professionals bring years of industry experience to every project.', 'icon' => 'users'],
                    ['title' => 'Quality First', 'description' => 'We maintain the highest standards of quality in every aspect of our work.', 'icon' => 'star'],
                    ['title' => 'Fast Delivery', 'description' => 'Efficient processes and dedicated workflows ensure timely delivery.', 'icon' => 'clock'],
                    ['title' => 'Custom Solutions', 'description' => 'Tailored approaches designed specifically for your unique needs.', 'icon' => 'cog'],
                    ['title' => '24/7 Support', 'description' => 'Round-the-clock assistance whenever you need help.', 'icon' => 'headset'],
                    ['title' => 'Best Value', 'description' => 'Competitive pricing without compromising on quality.', 'icon' => 'tag'],
                ],
            ],
            'testimonials' => [
                'type' => 'testimonials',
                'title' => $label ?: 'What Our Clients Say',
                'items' => [
                    ['name' => 'Sarah Mitchell', 'role' => 'CEO, TechFlow Inc', 'content' => "Working with {$businessName} transformed our operations. Exceeded all expectations."],
                    ['name' => 'James Rodriguez', 'role' => 'Director, Peak Solutions', 'content' => 'Outstanding results on time and within budget. Expertise is unmatched.'],
                    ['name' => 'Emily Chen', 'role' => 'Founder, BrightPath', 'content' => 'Every interaction was professional and productive. Highly recommended.'],
                    ['name' => 'Michael Brooks', 'role' => 'VP Operations, Atlas Group', 'content' => 'Exceptional service from start to finish. Solutions that make a real impact.'],
                ],
            ],
            'about_preview' => [
                'type' => 'about_preview',
                'title' => $label ?: "About {$businessName}",
                'content' => "Founded with a passion for excellence, {$businessName} has been serving clients with dedication and expertise. We believe in building lasting relationships through exceptional service.",
            ],
            'stats' => [
                'type' => 'stats',
                'title' => $label ?: 'Our Impact in Numbers',
                'items' => [
                    ['title' => '500+', 'description' => 'Projects Completed'],
                    ['title' => '150+', 'description' => 'Happy Clients'],
                    ['title' => '10+', 'description' => 'Years Experience'],
                    ['title' => '99%', 'description' => 'Client Satisfaction'],
                ],
            ],
            'process' => [
                'type' => 'process',
                'title' => $label ?: 'How It Works',
                'items' => [
                    ['title' => 'Consultation', 'description' => 'We understand your specific needs and challenges.'],
                    ['title' => 'Strategy', 'description' => 'We develop a customized plan tailored to your objectives.'],
                    ['title' => 'Execution', 'description' => 'We implement with precision, keeping you informed.'],
                    ['title' => 'Results', 'description' => 'We deliver measurable results and optimize for success.'],
                ],
            ],
            'faq' => [
                'type' => 'faq',
                'title' => $label ?: 'Frequently Asked Questions',
                'items' => [
                    ['title' => "What services does {$businessName} offer?", 'description' => "We offer a comprehensive range of {$typeLabel} services tailored to meet your specific needs."],
                    ['title' => 'How do I get started?', 'description' => 'Simply contact us through our form or give us a call. We\'ll schedule a free consultation.'],
                    ['title' => 'What are your business hours?', 'description' => 'We\'re available Monday through Friday, 9 AM to 6 PM. Emergency support is available 24/7.'],
                    ['title' => 'Do you offer free consultations?', 'description' => 'Yes! We offer a free initial consultation to discuss your needs and how we can help.'],
                ],
            ],
            'team' => [
                'type' => 'team',
                'title' => $label ?: 'Meet Our Team',
                'items' => [
                    ['name' => 'Alex Johnson', 'role' => 'Founder & CEO', 'description' => 'Leading the vision with over 15 years of industry experience.'],
                    ['name' => 'Maria Garcia', 'role' => 'Creative Director', 'description' => 'Bringing creative excellence to every project we deliver.'],
                    ['name' => 'David Kim', 'role' => 'Head of Operations', 'description' => 'Ensuring seamless execution and client satisfaction.'],
                ],
            ],
            'pricing' => [
                'type' => 'pricing',
                'title' => $label ?: 'Our Pricing',
                'items' => [
                    ['title' => 'Basic', 'price' => '$29/mo', 'description' => 'Perfect for getting started', 'features' => ['Core features', 'Email support', '1 user']],
                    ['title' => 'Professional', 'price' => '$79/mo', 'description' => 'Best for growing businesses', 'features' => ['All Basic features', 'Priority support', '5 users', 'Analytics']],
                    ['title' => 'Enterprise', 'price' => '$199/mo', 'description' => 'For large organizations', 'features' => ['All Pro features', '24/7 support', 'Unlimited users', 'Custom integrations']],
                ],
            ],
            'gallery' => [
                'type' => 'gallery',
                'title' => $label ?: 'Our Gallery',
                'items' => [],
            ],
            'contact_form' => [
                'type' => 'contact_form',
                'title' => $label ?: 'Get In Touch',
                'subtitle' => "We'd love to hear from you. Send us a message and we'll respond as soon as possible.",
            ],
            'cta' => [
                'type' => 'cta',
                'title' => $label ?: 'Ready to Get Started?',
                'subtitle' => "Let's discuss how {$businessName} can help you achieve your goals.",
                'button_text' => 'Contact Us Today',
            ],
            default => [
                'type' => 'content',
                'title' => $label ?: $pageTitle,
                'content' => "Welcome to the {$pageTitle} page of {$businessName}. We are dedicated to providing the best {$typeLabel} services for our clients.",
            ],
        };
    }

    /**
     * Build the default hardcoded fallback pages (used when no pages_structure provided).
     */
    private function buildDefaultFallbackPages(string $businessName, string $businessType, string $typeLabel): array
    {
        return [
            'home' => [
                'title' => 'Home',
                'hero_title' => "Elevating Your {$typeLabel} Experience",
                'hero_subtitle' => "At {$businessName}, we deliver exceptional results with a commitment to quality and innovation. Discover what sets us apart.",
                'hero_cta' => 'Get Started Today',
                'sections' => [
                    [
                        'type' => 'features',
                        'title' => "Why Choose {$businessName}",
                        'items' => [
                            ['title' => 'Expert Team', 'description' => 'Our seasoned professionals bring years of industry experience to every project we undertake.', 'icon' => 'users'],
                            ['title' => 'Quality First', 'description' => 'We maintain the highest standards of quality in every aspect of our work.', 'icon' => 'star'],
                            ['title' => 'Fast Delivery', 'description' => 'Efficient processes and dedicated workflows ensure timely delivery of all projects.', 'icon' => 'clock'],
                            ['title' => 'Custom Solutions', 'description' => 'Tailored approaches designed specifically for your unique needs and goals.', 'icon' => 'cog'],
                            ['title' => '24/7 Support', 'description' => 'Round-the-clock assistance whenever you need help or have questions.', 'icon' => 'headset'],
                            ['title' => 'Best Value', 'description' => 'Competitive pricing without compromising on the quality of our deliverables.', 'icon' => 'tag'],
                        ],
                    ],
                    [
                        'type' => 'stats',
                        'title' => 'Our Impact in Numbers',
                        'items' => [
                            ['title' => '500+', 'description' => 'Projects Completed'],
                            ['title' => '150+', 'description' => 'Happy Clients'],
                            ['title' => '10+', 'description' => 'Years Experience'],
                            ['title' => '99%', 'description' => 'Client Satisfaction'],
                        ],
                    ],
                    [
                        'type' => 'about_preview',
                        'title' => "About {$businessName}",
                        'content' => "Founded with a passion for excellence, {$businessName} has been serving clients with dedication and expertise. We believe in building lasting relationships through exceptional service and innovative solutions that drive real results.",
                    ],
                    [
                        'type' => 'process',
                        'title' => 'How It Works',
                        'items' => [
                            ['title' => 'Consultation', 'description' => 'We start by understanding your specific needs, goals, and challenges through a detailed consultation.'],
                            ['title' => 'Strategy', 'description' => 'Our team develops a customized strategy and plan of action tailored to your objectives.'],
                            ['title' => 'Execution', 'description' => 'We implement the strategy with precision, keeping you informed at every step of the process.'],
                            ['title' => 'Results', 'description' => 'We deliver measurable results and continue to optimize for long-term success.'],
                        ],
                    ],
                    [
                        'type' => 'testimonials',
                        'title' => 'What Our Clients Say',
                        'items' => [
                            ['name' => 'Sarah Mitchell', 'role' => 'CEO, TechFlow Inc', 'content' => "Working with {$businessName} transformed our operations. Their attention to detail and commitment to excellence exceeded all our expectations."],
                            ['name' => 'James Rodriguez', 'role' => 'Director, Peak Solutions', 'content' => 'The team delivered outstanding results on time and within budget. Their expertise and professionalism are unmatched in the industry.'],
                            ['name' => 'Emily Chen', 'role' => 'Founder, BrightPath', 'content' => 'From the initial consultation to final delivery, every interaction was professional and productive. Highly recommended for any business.'],
                            ['name' => 'Michael Brooks', 'role' => 'VP Operations, Atlas Group', 'content' => 'Exceptional service from start to finish. They truly understand what businesses need and deliver solutions that make a real impact.'],
                        ],
                    ],
                    [
                        'type' => 'cta',
                        'title' => 'Ready to Get Started?',
                        'subtitle' => "Let's discuss how {$businessName} can help you achieve your goals.",
                        'button_text' => 'Contact Us Today',
                    ],
                ],
            ],
            'about' => [
                'title' => 'About Us',
                'content' => "{$businessName} was founded with a clear mission: to provide exceptional {$typeLabel} services that make a real difference. With over a decade of experience, our team of dedicated professionals has helped hundreds of clients achieve their goals.\n\nWe pride ourselves on our client-first approach, ensuring every project receives the attention and expertise it deserves. Our commitment to innovation and quality has made us a trusted name in the industry.",
                'mission' => "To deliver innovative {$typeLabel} solutions that empower businesses and exceed expectations.",
                'vision' => 'To be the most trusted partner for businesses seeking excellence and growth.',
            ],
            'services' => [
                'title' => 'Our Services',
                'intro' => "We offer a comprehensive range of {$typeLabel} services designed to meet the diverse needs of our clients.",
                'items' => [
                    ['title' => 'Consulting', 'description' => "Expert {$typeLabel} consulting to help you navigate challenges and seize opportunities for growth.", 'price' => 'Custom'],
                    ['title' => 'Strategy Development', 'description' => 'Comprehensive strategic planning aligned with your business objectives and market conditions.', 'price' => 'Custom'],
                    ['title' => 'Implementation', 'description' => 'End-to-end implementation services with meticulous attention to detail and quality assurance.', 'price' => 'Custom'],
                    ['title' => 'Training & Support', 'description' => 'Comprehensive training programs and ongoing support to ensure your team is fully equipped.', 'price' => 'Custom'],
                    ['title' => 'Analytics & Reporting', 'description' => 'Data-driven insights and detailed reporting to measure performance and guide decisions.', 'price' => 'Custom'],
                    ['title' => 'Optimization', 'description' => 'Continuous improvement and optimization services to maximize your return on investment.', 'price' => 'Custom'],
                ],
            ],
            'contact' => [
                'title' => 'Contact Us',
                'subtitle' => 'Get in touch with our team — we would love to hear from you.',
                'address' => '123 Business Avenue, Suite 100',
                'phone' => '(555) 123-4567',
                'email' => 'info@' . strtolower(str_replace(' ', '', $businessName)) . '.com',
            ],
        ];
    }

    /**
     * Install HFE plugin and create Elementor-based header/footer templates.
     * Non-fatal: if this fails, site still works with basic theme header/footer.
     */
    private function setupHfeHeaderFooter(WordPressService $wordpressService, Website $website, array $content, array $images): void
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) return;

        try {
            $htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
            $sitePath = $htdocsPath . '/' . $website->subdomain;
            $siteUrl = $website->url ?: 'http://localhost/' . $website->subdomain;

            // 1. Install & activate HFE plugin
            $wordpressService->installHfePlugin($sitePath, $dbName);

            // 2. Resolve the Layout class
            $layoutSlug = $website->ai_theme ?? 'azure';
            $layout = AbstractLayout::resolve($layoutSlug);
            if (!$layout) {
                $layout = AbstractLayout::resolve('azure');
            }

            // 3. Build page list for header/footer nav
            $pageNames = [];
            foreach (($content['pages'] ?? []) as $slug => $pageData) {
                $pageNames[$slug] = $pageData['title'] ?? ucfirst($slug);
            }

            // 4. Build header + footer Elementor data from Layout
            $siteName = $content['site_title'] ?? $website->name ?? 'My Website';
            $headerData = $layout->buildHeader($siteName, $pageNames);
            $contactPage = $content['pages']['contact'] ?? [];
            $contact = [
                'tagline' => $content['tagline'] ?? '',
                'phone' => $contactPage['phone'] ?? $content['phone'] ?? '',
                'email' => $contactPage['email'] ?? $content['email'] ?? '',
                'address' => $contactPage['address'] ?? $content['address'] ?? '',
            ];
            $footerData = $layout->buildFooter($siteName, $pageNames, $contact);

            // 5. Create HFE templates in database
            $wordpressService->createHfeTemplate($dbName, 'type_header', 'Site Header', $headerData, $siteUrl);
            $wordpressService->createHfeTemplate($dbName, 'type_footer', 'Site Footer', $footerData, $siteUrl);

            // 6. Set all pages to use HFE template
            $wordpressService->setAllPagesTemplate($dbName, 'elementor_header_footer');

            Log::info("HFE header/footer setup complete for website {$website->id} using layout '{$layoutSlug}'");

        } catch (\Exception $e) {
            Log::warning("HFE setup failed (non-critical): {$e->getMessage()}");
        }
    }

    /**
     * Helper to insert postmeta directly via PDO (used by injectExampleContent).
     */
    private function setPostMetaDirect(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    private function buildPageHtml(string $slug, array $pageData, array $images = [], array $siteContent = []): string
    {
        $colors = $siteContent['colors'] ?? ['primary' => '#2563eb', 'secondary' => '#1e40af', 'accent' => '#60a5fa'];
        $primary = $colors['primary'] ?? '#2563eb';
        $secondary = $colors['secondary'] ?? '#1e40af';

        $html = '';

        // HOME PAGE — full featured layout
        if ($slug === 'home') {
            // Hero Section
            $heroImg = $pageData['hero_image'] ?? $images['hero'] ?? '';
            $heroTitle = e($pageData['hero_title'] ?? '');
            $heroSubtitle = e($pageData['hero_subtitle'] ?? '');
            $heroCta = e($pageData['hero_cta'] ?? 'Get Started');

            $html .= "<div style=\"position:relative;min-height:500px;display:flex;align-items:center;justify-content:center;text-align:center;color:#fff;overflow:hidden;\">";
            if ($heroImg) {
                $html .= "<img src=\"{$heroImg}\" alt=\"\" style=\"position:absolute;inset:0;width:100%;height:100%;object-fit:cover;\" />";
            }
            $html .= "<div style=\"position:absolute;inset:0;background:linear-gradient(135deg,{$primary}cc,{$secondary}cc);\"></div>";
            $html .= "<div style=\"position:relative;z-index:2;padding:40px 20px;max-width:800px;\">";
            $html .= "<h1 style=\"font-size:3em;font-weight:800;margin-bottom:16px;text-shadow:0 2px 10px rgba(0,0,0,0.3);\">{$heroTitle}</h1>";
            $html .= "<p style=\"font-size:1.3em;margin-bottom:24px;opacity:0.95;\">{$heroSubtitle}</p>";
            $html .= "<a href=\"#contact\" style=\"display:inline-block;padding:14px 36px;background:#fff;color:{$primary};font-weight:700;border-radius:8px;text-decoration:none;font-size:1.1em;\">{$heroCta}</a>";
            $html .= "</div></div>";

            // Sections
            if (isset($pageData['sections'])) {
                foreach ($pageData['sections'] as $section) {
                    $sectionType = $section['type'] ?? 'content';
                    $sectionTitle = e($section['title'] ?? '');

                    if ($sectionType === 'features') {
                        $html .= "<div style=\"padding:60px 20px;max-width:1100px;margin:0 auto;text-align:center;\">";
                        $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                        $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:30px;\">";
                        foreach ($section['items'] ?? [] as $item) {
                            $html .= "<div style=\"padding:30px;border-radius:12px;background:#f9fafb;border:1px solid #e5e7eb;\">";
                            if (isset($item['icon'])) {
                                $html .= "<div style=\"font-size:2em;margin-bottom:12px;\">" . e($item['icon']) . "</div>";
                            }
                            $html .= "<h3 style=\"font-size:1.2em;font-weight:600;margin-bottom:8px;color:#1a1a1a;\">" . e($item['title'] ?? '') . "</h3>";
                            $html .= "<p style=\"color:#6b7280;line-height:1.6;\">" . e($item['description'] ?? '') . "</p>";
                            $html .= "</div>";
                        }
                        $html .= "</div></div>";

                    } elseif ($sectionType === 'testimonials') {
                        $html .= "<div style=\"padding:60px 20px;background:#f9fafb;\">";
                        $html .= "<div style=\"max-width:1100px;margin:0 auto;text-align:center;\">";
                        $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                        $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;\">";
                        foreach ($section['items'] ?? [] as $item) {
                            $html .= "<div style=\"padding:24px;border-radius:12px;background:#fff;border:1px solid #e5e7eb;text-align:left;\">";
                            $html .= "<p style=\"color:#4b5563;line-height:1.7;margin-bottom:16px;font-style:italic;\">\"" . e($item['content'] ?? '') . "\"</p>";
                            $html .= "<div><strong style=\"color:#1a1a1a;\">" . e($item['name'] ?? '') . "</strong>";
                            if (isset($item['role'])) {
                                $html .= "<br><span style=\"color:#9ca3af;font-size:0.9em;\">" . e($item['role']) . "</span>";
                            }
                            $html .= "</div></div>";
                        }
                        $html .= "</div></div></div>";

                    } elseif ($sectionType === 'about_preview') {
                        $aboutImg = $images['about'] ?? '';
                        $html .= "<div style=\"padding:60px 20px;\">";
                        $html .= "<div style=\"max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:center;\">";
                        if ($aboutImg) {
                            $html .= "<img src=\"{$aboutImg}\" alt=\"\" style=\"width:100%;border-radius:12px;object-fit:cover;max-height:400px;\" />";
                        }
                        $html .= "<div>";
                        $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:16px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                        $html .= "<p style=\"color:#4b5563;line-height:1.8;\">" . e($section['content'] ?? '') . "</p>";
                        $html .= "</div></div></div>";

                    } elseif ($sectionType === 'cta') {
                        $html .= "<div style=\"padding:60px 20px;background:linear-gradient(135deg,{$primary},{$secondary});text-align:center;color:#fff;\">";
                        $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:12px;\">{$sectionTitle}</h2>";
                        if (isset($section['subtitle'])) {
                            $html .= "<p style=\"font-size:1.1em;margin-bottom:24px;opacity:0.9;\">" . e($section['subtitle']) . "</p>";
                        }
                        if (isset($section['button_text'])) {
                            $html .= "<a href=\"#contact\" style=\"display:inline-block;padding:14px 36px;background:#fff;color:{$primary};font-weight:700;border-radius:8px;text-decoration:none;\">" . e($section['button_text']) . "</a>";
                        }
                        $html .= "</div>";
                    }
                }
            }
            return $html;
        }

        // ABOUT PAGE
        if ($slug === 'about') {
            $aboutImg = $pageData['featured_image'] ?? $images['about'] ?? '';
            $html .= "<div style=\"padding:60px 20px;max-width:900px;margin:0 auto;\">";
            $html .= "<h1 style=\"font-size:2.5em;font-weight:800;margin-bottom:24px;color:#1a1a1a;\">" . e($pageData['title'] ?? 'About Us') . "</h1>";
            if ($aboutImg) {
                $html .= "<img src=\"{$aboutImg}\" alt=\"\" style=\"width:100%;border-radius:12px;object-fit:cover;max-height:400px;margin-bottom:32px;\" />";
            }
            $html .= "<div style=\"color:#4b5563;line-height:1.8;font-size:1.1em;\">" . nl2br(e($pageData['content'] ?? '')) . "</div>";
            if (isset($pageData['mission'])) {
                $html .= "<div style=\"margin-top:40px;padding:30px;background:#f9fafb;border-radius:12px;border-left:4px solid {$primary};\">";
                $html .= "<h2 style=\"font-size:1.5em;font-weight:700;color:#1a1a1a;margin-bottom:8px;\">Our Mission</h2>";
                $html .= "<p style=\"color:#4b5563;line-height:1.7;\">" . e($pageData['mission']) . "</p>";
                $html .= "</div>";
            }
            if (isset($pageData['vision'])) {
                $html .= "<div style=\"margin-top:20px;padding:30px;background:#f9fafb;border-radius:12px;border-left:4px solid {$primary};\">";
                $html .= "<h2 style=\"font-size:1.5em;font-weight:700;color:#1a1a1a;margin-bottom:8px;\">Our Vision</h2>";
                $html .= "<p style=\"color:#4b5563;line-height:1.7;\">" . e($pageData['vision']) . "</p>";
                $html .= "</div>";
            }
            $html .= "</div>";
            return $html;
        }

        // SERVICES PAGE
        if ($slug === 'services') {
            $servicesImg = $pageData['featured_image'] ?? $images['services'] ?? '';
            $html .= "<div style=\"padding:60px 20px;max-width:1100px;margin:0 auto;\">";
            $html .= "<div style=\"text-align:center;margin-bottom:40px;\">";
            $html .= "<h1 style=\"font-size:2.5em;font-weight:800;color:#1a1a1a;\">" . e($pageData['title'] ?? 'Our Services') . "</h1>";
            if (isset($pageData['intro'])) {
                $html .= "<p style=\"color:#6b7280;font-size:1.1em;max-width:600px;margin:12px auto 0;\">" . e($pageData['intro']) . "</p>";
            }
            $html .= "</div>";
            if ($servicesImg) {
                $html .= "<img src=\"{$servicesImg}\" alt=\"\" style=\"width:100%;border-radius:12px;object-fit:cover;max-height:300px;margin-bottom:40px;\" />";
            }
            $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;\">";
            foreach ($pageData['items'] ?? [] as $item) {
                $html .= "<div style=\"padding:30px;border-radius:12px;border:1px solid #e5e7eb;background:#fff;\">";
                $html .= "<h3 style=\"font-size:1.3em;font-weight:600;margin-bottom:10px;color:#1a1a1a;\">" . e($item['title'] ?? '') . "</h3>";
                $html .= "<p style=\"color:#6b7280;line-height:1.6;margin-bottom:12px;\">" . e($item['description'] ?? '') . "</p>";
                if (isset($item['price']) && $item['price']) {
                    $html .= "<span style=\"display:inline-block;padding:6px 16px;background:{$primary}15;color:{$primary};border-radius:6px;font-weight:600;\">" . e($item['price']) . "</span>";
                }
                $html .= "</div>";
            }
            $html .= "</div></div>";
            return $html;
        }

        // CONTACT PAGE
        if ($slug === 'contact') {
            $html .= "<div style=\"padding:60px 20px;max-width:900px;margin:0 auto;\">";
            $html .= "<div style=\"text-align:center;margin-bottom:40px;\">";
            $html .= "<h1 style=\"font-size:2.5em;font-weight:800;color:#1a1a1a;\">" . e($pageData['title'] ?? 'Contact Us') . "</h1>";
            if (isset($pageData['subtitle'])) {
                $html .= "<p style=\"color:#6b7280;font-size:1.1em;\">" . e($pageData['subtitle']) . "</p>";
            }
            $html .= "</div>";
            $html .= "<div style=\"display:grid;grid-template-columns:1fr 1fr;gap:40px;\" id=\"contact\">";
            // Contact info
            $html .= "<div style=\"padding:30px;background:#f9fafb;border-radius:12px;\">";
            $html .= "<h2 style=\"font-size:1.5em;font-weight:600;margin-bottom:20px;color:#1a1a1a;\">Get in Touch</h2>";
            if (isset($pageData['address'])) {
                $html .= "<div style=\"margin-bottom:16px;\"><strong style=\"color:#1a1a1a;\">Address</strong><br><span style=\"color:#6b7280;\">" . e($pageData['address']) . "</span></div>";
            }
            if (isset($pageData['phone'])) {
                $html .= "<div style=\"margin-bottom:16px;\"><strong style=\"color:#1a1a1a;\">Phone</strong><br><span style=\"color:#6b7280;\">" . e($pageData['phone']) . "</span></div>";
            }
            if (isset($pageData['email'])) {
                $html .= "<div style=\"margin-bottom:16px;\"><strong style=\"color:#1a1a1a;\">Email</strong><br><span style=\"color:#6b7280;\">" . e($pageData['email']) . "</span></div>";
            }
            $html .= "</div>";
            // Contact form placeholder
            $html .= "<div style=\"padding:30px;background:#fff;border-radius:12px;border:1px solid #e5e7eb;\">";
            $html .= "<h2 style=\"font-size:1.5em;font-weight:600;margin-bottom:20px;color:#1a1a1a;\">Send a Message</h2>";
            $html .= "<p style=\"color:#6b7280;\">Contact form coming soon. Please reach out via the details provided.</p>";
            $html .= "</div>";
            $html .= "</div></div>";
            return $html;
        }

        // GENERIC PAGE fallback (menu, shop, portfolio, pricing, faq, gallery, team, etc.)
        $title = e($pageData['title'] ?? ucfirst($slug));

        // Hero header
        if (isset($pageData['hero_title'])) {
            $html .= "<div style=\"padding:60px 20px;text-align:center;background:linear-gradient(135deg,{$primary},{$secondary});color:#fff;\">";
            $html .= "<h1 style=\"font-size:2.5em;font-weight:800;\">" . e($pageData['hero_title']) . "</h1>";
            if (isset($pageData['hero_subtitle'])) {
                $html .= "<p style=\"font-size:1.2em;opacity:0.9;margin-top:12px;\">" . e($pageData['hero_subtitle']) . "</p>";
            }
            $html .= "</div>";
        } else {
            $html .= "<div style=\"padding:60px 20px;text-align:center;background:linear-gradient(135deg,{$primary},{$secondary});color:#fff;\">";
            $html .= "<h1 style=\"font-size:2.5em;font-weight:800;\">{$title}</h1>";
            $html .= "</div>";
        }

        // Main content
        if (isset($pageData['content'])) {
            $html .= "<div style=\"padding:40px 20px;max-width:800px;margin:0 auto;color:#4b5563;line-height:1.8;\">" . nl2br(e($pageData['content'])) . "</div>";
        }

        // Intro
        if (isset($pageData['intro'])) {
            $html .= "<div style=\"padding:20px 20px;max-width:800px;margin:0 auto;text-align:center;color:#6b7280;font-size:1.1em;\">" . e($pageData['intro']) . "</div>";
        }

        // Items grid (services, features, menu items, products, team members, etc.)
        if (isset($pageData['items']) && is_array($pageData['items'])) {
            $html .= "<div style=\"padding:40px 20px;max-width:1100px;margin:0 auto;\">";
            $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;\">";
            foreach ($pageData['items'] as $item) {
                $html .= "<div style=\"padding:24px;border-radius:12px;border:1px solid #e5e7eb;background:#fff;\">";
                $html .= "<h3 style=\"font-size:1.2em;font-weight:600;margin-bottom:8px;color:#1a1a1a;\">" . e($item['title'] ?? $item['name'] ?? $item['question'] ?? '') . "</h3>";
                $html .= "<p style=\"color:#6b7280;line-height:1.6;\">" . e($item['description'] ?? $item['content'] ?? $item['answer'] ?? $item['role'] ?? '') . "</p>";
                if (isset($item['price']) && $item['price']) {
                    $html .= "<p style=\"margin-top:8px;font-weight:600;color:{$primary};\">" . e($item['price']) . "</p>";
                }
                $html .= "</div>";
            }
            $html .= "</div></div>";
        }

        // Sections
        if (isset($pageData['sections']) && is_array($pageData['sections'])) {
            foreach ($pageData['sections'] as $section) {
                $sectionType = $section['type'] ?? 'content';
                $sectionTitle = e($section['title'] ?? '');

                if ($sectionType === 'features' || $sectionType === 'pricing' || $sectionType === 'team') {
                    $html .= "<div style=\"padding:60px 20px;max-width:1100px;margin:0 auto;text-align:center;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;\">";
                    foreach ($section['items'] ?? [] as $item) {
                        $html .= "<div style=\"padding:24px;border-radius:12px;border:1px solid #e5e7eb;background:#fff;\">";
                        $html .= "<h3 style=\"font-size:1.2em;font-weight:600;margin-bottom:8px;color:#1a1a1a;\">" . e($item['title'] ?? $item['name'] ?? '') . "</h3>";
                        $html .= "<p style=\"color:#6b7280;line-height:1.6;\">" . e($item['description'] ?? $item['content'] ?? $item['role'] ?? '') . "</p>";
                        if (isset($item['price'])) $html .= "<p style=\"margin-top:8px;font-weight:600;color:{$primary};\">" . e($item['price']) . "</p>";
                        $html .= "</div>";
                    }
                    $html .= "</div></div>";
                } elseif ($sectionType === 'testimonials') {
                    $html .= "<div style=\"padding:60px 20px;background:#f9fafb;\"><div style=\"max-width:1100px;margin:0 auto;text-align:center;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;\">";
                    foreach ($section['items'] ?? [] as $item) {
                        $html .= "<div style=\"padding:24px;border-radius:12px;background:#fff;border:1px solid #e5e7eb;text-align:left;\">";
                        $html .= "<p style=\"color:#4b5563;line-height:1.7;margin-bottom:12px;font-style:italic;\">\"" . e($item['content'] ?? '') . "\"</p>";
                        $html .= "<strong>" . e($item['name'] ?? '') . "</strong>";
                        if (isset($item['role'])) $html .= "<br><span style=\"color:#9ca3af;font-size:0.9em;\">" . e($item['role']) . "</span>";
                        $html .= "</div>";
                    }
                    $html .= "</div></div></div>";
                } elseif ($sectionType === 'faq') {
                    $html .= "<div style=\"padding:60px 20px;max-width:800px;margin:0 auto;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;text-align:center;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    foreach ($section['items'] ?? [] as $item) {
                        $html .= "<div style=\"padding:20px;border-bottom:1px solid #e5e7eb;\">";
                        $html .= "<h3 style=\"font-weight:600;color:#1a1a1a;margin-bottom:8px;\">" . e($item['question'] ?? $item['title'] ?? '') . "</h3>";
                        $html .= "<p style=\"color:#6b7280;line-height:1.6;\">" . e($item['answer'] ?? $item['description'] ?? $item['content'] ?? '') . "</p>";
                        $html .= "</div>";
                    }
                    $html .= "</div>";
                } elseif ($sectionType === 'cta') {
                    $html .= "<div style=\"padding:60px 20px;background:linear-gradient(135deg,{$primary},{$secondary});text-align:center;color:#fff;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:12px;\">{$sectionTitle}</h2>";
                    if (isset($section['subtitle'])) $html .= "<p style=\"font-size:1.1em;margin-bottom:24px;opacity:0.9;\">" . e($section['subtitle']) . "</p>";
                    if (isset($section['button_text'])) $html .= "<a href=\"#contact\" style=\"display:inline-block;padding:14px 36px;background:#fff;color:{$primary};font-weight:700;border-radius:8px;text-decoration:none;\">" . e($section['button_text']) . "</a>";
                    $html .= "</div>";
                } elseif ($sectionType === 'process') {
                    $html .= "<div style=\"padding:60px 20px;background:#f9fafb;\"><div style=\"max-width:1100px;margin:0 auto;text-align:center;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:30px;\">";
                    foreach ($section['items'] ?? [] as $i => $item) {
                        $num = $i + 1;
                        $html .= "<div style=\"text-align:center;\">";
                        $html .= "<div style=\"display:inline-flex;align-items:center;justify-content:center;width:50px;height:50px;border-radius:50%;background:{$primary};color:#fff;font-weight:700;font-size:1.3em;margin-bottom:16px;\">{$num}</div>";
                        $html .= "<h3 style=\"font-size:1.2em;font-weight:600;margin-bottom:8px;color:#1a1a1a;\">" . e($item['title'] ?? '') . "</h3>";
                        $html .= "<p style=\"color:#6b7280;line-height:1.6;\">" . e($item['description'] ?? '') . "</p>";
                        $html .= "</div>";
                    }
                    $html .= "</div></div></div>";
                } elseif ($sectionType === 'about_preview' || $sectionType === 'content') {
                    $html .= "<div style=\"padding:60px 20px;max-width:900px;margin:0 auto;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:16px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<p style=\"color:#4b5563;line-height:1.8;\">" . e($section['content'] ?? '') . "</p>";
                    $html .= "</div>";
                } elseif ($sectionType === 'stats') {
                    $html .= "<div style=\"padding:60px 20px;background:#f9fafb;\"><div style=\"max-width:1100px;margin:0 auto;text-align:center;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<div style=\"display:flex;justify-content:center;gap:40px;flex-wrap:wrap;\">";
                    foreach ($section['items'] ?? [] as $item) {
                        $html .= "<div style=\"text-align:center;\">";
                        $html .= "<div style=\"font-size:2.5em;font-weight:800;color:{$primary};\">" . e($item['title'] ?? $item['number'] ?? '') . "</div>";
                        $html .= "<div style=\"color:#6b7280;font-size:0.9em;\">" . e($item['description'] ?? $item['label'] ?? '') . "</div>";
                        $html .= "</div>";
                    }
                    $html .= "</div></div></div>";
                } elseif ($sectionType === 'gallery') {
                    $html .= "<div style=\"padding:60px 20px;max-width:1100px;margin:0 auto;\">";
                    if ($sectionTitle) $html .= "<h2 style=\"font-size:2em;font-weight:700;margin-bottom:40px;text-align:center;color:#1a1a1a;\">{$sectionTitle}</h2>";
                    $html .= "<div style=\"display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;\">";
                    foreach ($section['items'] ?? [] as $item) {
                        $html .= "<div style=\"padding:16px;border-radius:12px;background:#f9fafb;border:1px solid #e5e7eb;\">";
                        $html .= "<h3 style=\"font-weight:600;margin-bottom:4px;\">" . e($item['title'] ?? '') . "</h3>";
                        $html .= "<p style=\"color:#6b7280;font-size:0.9em;\">" . e($item['description'] ?? '') . "</p>";
                        $html .= "</div>";
                    }
                    $html .= "</div></div>";
                }
            }
        }

        return $html ?: '<p></p>';
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProvisionWebsiteJob permanently failed for website {$this->website->id}: {$exception->getMessage()}");
        $this->website->update(['status' => 'error']);
        $this->website->user->notify(new WebsiteBuildFailedNotification($this->website));
    }
}
