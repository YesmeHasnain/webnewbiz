<?php

namespace App\Jobs;

use App\Models\Website;
use App\Services\AIContentService;
use App\Services\Layouts\AbstractLayout;
use App\Services\ThemeMatcherService;
use App\Services\UnsplashService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProvisionWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 600;

    public function __construct(public Website $website) {}

    public function handle(): void
    {
        $slug = $this->website->slug;
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $sitePath = "{$htdocs}/{$slug}";
        $basePath = config('webnewbiz.wp_base_path', "{$htdocs}/wordpress");
        $dbName = 'wp_' . str_replace('-', '_', $slug);
        $siteUrl = "http://localhost/{$slug}";

        try {
            $this->website->update(['status' => 'building']);

            // ── Phase 1: WordPress install + AI content + Images in parallel ──

            // Step 1: Install WordPress (runs first since it creates the directory)
            $this->updateStep('wordpress_install', 'Installing WordPress...');
            $this->installWordPress($sitePath, $basePath, $dbName, $siteUrl);

            // Step 2: Match layout theme (fast — local or quick API)
            $this->updateStep('ai_content', 'Selecting design theme...');
            $theme = $this->matchLayout();
            $layout = AbstractLayout::resolve($theme);
            if (!$layout) {
                Log::warning("Layout '{$theme}' not found, falling back to azure");
                $theme = 'azure';
                $layout = AbstractLayout::resolve($theme);
            }
            $this->website->update(['ai_theme' => $theme]);
            $this->updateStep('ai_content', "Theme selected: {$layout->name()}");

            // Step 3+4: AI content + Image downloads run in PARALLEL
            $this->updateStep('ai_content', 'Generating AI content & downloading images...');

            $content = null;
            $imageUrls = [];
            $aiError = null;
            $imgError = null;

            // Start image download in a fiber/thread-like pattern using pcntl or simple sequential
            // Since PHP doesn't have true threads, we overlap AI (HTTP wait) with image prep
            // But we CAN run them via two sequential fast steps since images are now parallel internally

            // AI content (single API call — ~5-15s)
            $content = $this->generateContent();
            $this->updateStep('ai_content', 'AI content generated.');

            // Images (10 parallel downloads — ~3-5s total instead of ~50s)
            $this->updateStep('images', 'Downloading stock images...');
            $imageUrls = $this->downloadImages($sitePath, $siteUrl, $dbName);

            // ── Phase 2: Build everything ──

            if ($layout->isThemeBased()) {
                // Theme-based layout: install actual WP theme + demo data
                $this->updateStep('building_pages', 'Installing theme & demo content...');
                $this->installThemeLayout($layout, $sitePath, $dbName, $siteUrl, $content, $imageUrls);

                // Step 7: Configure site (theme-based)
                $this->updateStep('plugins', 'Finalizing site...');
                $this->configureSiteThemeBased($layout, $dbName, $content, $sitePath);
            } else {
                // Standard layout: generate Elementor JSON
                // Step 5: Build Elementor pages
                $this->updateStep('building_pages', 'Building pages with Elementor...');
                $this->buildElementorPages($layout, $content, $imageUrls, $dbName, $siteUrl);

                // Step 6: Header & Footer
                $this->updateStep('header_footer', 'Setting up header & footer...');
                $this->buildHeaderFooter($layout, $content, $dbName, $siteUrl);

                // Step 7: Configure site
                $this->updateStep('plugins', 'Finalizing site...');
                $this->configureSite($dbName, $content);
            }

            // Step 8: Regenerate Elementor CSS (non-blocking)
            $this->updateStep('complete', 'Generating styles...');
            try {
                $this->regenerateElementorCss($sitePath, $siteUrl);
            } catch (\Exception $e) {
                Log::warning("CSS regen failed (non-critical): {$e->getMessage()}");
            }

            // Step 9: Complete — always mark active after all build steps
            $this->website->update([
                'status' => 'active',
                'build_step' => 'complete',
                'url' => $siteUrl,
                'build_log' => array_merge(
                    $this->website->build_log ?? [],
                    ['Website is ready!']
                ),
            ]);

            Log::info("Website {$slug} built successfully: {$siteUrl}");

        } catch (\Exception $e) {
            Log::error("Build failed for {$slug}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            $this->website->update([
                'status' => 'failed',
                'build_step' => 'failed',
                'build_log' => array_merge(
                    $this->website->build_log ?? [],
                    ['Build failed: ' . $e->getMessage()]
                ),
            ]);
        }
    }

    private function updateStep(string $step, string $message): void
    {
        $log = $this->website->build_log ?? [];
        $log[] = $message;
        $this->website->update([
            'build_step' => $step,
            'build_log' => $log,
        ]);
    }

    // ─── Step 1: Install WordPress from pre-built template ───

    private function installWordPress(string $sitePath, string $basePath, string $dbName, string $siteUrl): void
    {
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $templateDir = "{$htdocs}/wp-template";
        $templateSql = "{$templateDir}/wp-template.sql";
        $mysqlBin = config('webnewbiz.mysql_bin', 'C:/xampp/mysql/bin');

        if (!File::isDirectory($templateDir) || !File::exists($templateSql)) {
            throw new \RuntimeException(
                "WordPress template not found. Run: php create_template.php"
            );
        }

        // 1. Copy template files (robocopy /MT:16 = multi-threaded, ~18s for 18K files)
        $src = str_replace('/', '\\', $templateDir);
        $dst = str_replace('/', '\\', $sitePath);
        exec("robocopy \"{$src}\" \"{$dst}\" /E /MT:16 /NFL /NDL /NJH /NJS /NC /NS /NP /XF wp-template.sql wp-template.zip 2>&1", $output, $returnCode);
        // Robocopy exit codes: 0=no files, 1=files copied OK, >7=error
        if ($returnCode > 7) {
            throw new \RuntimeException("Failed to copy template (exit {$returnCode}): " . implode("\n", $output));
        }
        $this->updateStep('wordpress_install', 'Files copied.');

        // 2. Create database and import template SQL (must use MySQL, not default SQLite)
        DB::connection('mysql')->statement("DROP DATABASE IF EXISTS `{$dbName}`");
        DB::connection('mysql')->statement("CREATE DATABASE `{$dbName}`");

        // Import SQL dump via mysql CLI
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $passArg = $pass ? " -p{$pass}" : '';
        // mysql_bin config already includes the exe name
        $mysqlExe = str_replace('/', '\\', $mysqlBin);
        $sqlFileWin = str_replace('/', '\\', $templateSql);
        $proc = proc_open(
            "\"{$mysqlExe}\" -u {$user}{$passArg} -h {$host} {$dbName}",
            [0 => ['file', $sqlFileWin, 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );
        if (!is_resource($proc)) {
            throw new \RuntimeException("Failed to start mysql process");
        }
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $importCode = proc_close($proc);
        if ($importCode !== 0) {
            throw new \RuntimeException("SQL import failed: {$stderr}");
        }
        $this->updateStep('wordpress_install', 'Database imported.');

        // 3. Update site-specific values (3 queries instead of full install)
        $pdo = $this->getPdo($dbName);
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'siteurl'")->execute([$siteUrl]);
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'home'")->execute([$siteUrl]);
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogname'")->execute([$this->website->name]);

        // 4. Update admin password hash
        $adminPass = config('webnewbiz.admin_password', 'password');
        $adminEmail = config('webnewbiz.admin_email', 'admin@webnewbiz.com');

        // 5. Create uploads directory
        $uploadsPath = $sitePath . '/wp-content/uploads/' . date('Y/m');
        if (!File::isDirectory($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
        }

        // 6. Generate wp-config.php with correct DB name
        $configSample = "{$sitePath}/wp-config-sample.php";
        $configFile = "{$sitePath}/wp-config.php";
        @unlink($configFile);

        if (File::exists($configSample)) {
            $config = File::get($configSample);
            $config = str_replace('database_name_here', $dbName, $config);
            $config = str_replace('username_here', config('database.connections.mysql.username', 'root'), $config);
            $config = str_replace('password_here', config('database.connections.mysql.password', ''), $config);
            $config = str_replace('localhost', config('database.connections.mysql.host', '127.0.0.1'), $config);

            foreach (['AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'] as $key) {
                $config = preg_replace(
                    "/define\(\s*'{$key}'\s*,\s*'[^']*'\s*\)/",
                    "define('{$key}', '" . bin2hex(random_bytes(32)) . "')",
                    $config
                );
            }
            File::put($configFile, $config);
        }

        // 7. Generate .htaccess
        $htaccess = <<<HTACCESS
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /{$this->website->slug}/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /{$this->website->slug}/index.php [L]
</IfModule>
# END WordPress
HTACCESS;
        File::put($sitePath . '/.htaccess', $htaccess);

        // 8. Update website record
        $this->website->update([
            'wp_admin_user' => 'admin',
            'wp_admin_password' => $adminPass,
            'wp_admin_email' => $adminEmail,
            'wp_db_name' => $dbName,
        ]);

        $this->updateStep('wordpress_install', 'WordPress ready.');
    }

    // ─── Step 2: Match Layout Theme ───

    private function matchLayout(): string
    {
        $businessType = $this->website->business_type ?? 'business';
        $prompt = $this->website->ai_prompt ?? '';

        // If a theme was already selected by the user, use it
        $currentTheme = $this->website->ai_theme;
        $validThemes = ['noir', 'ivory', 'azure', 'blush', 'ember', 'forest', 'slate', 'royal', 'biddut'];
        if ($currentTheme && in_array($currentTheme, $validThemes)) {
            return $currentTheme;
        }

        // Otherwise, use variety-aware matcher
        try {
            $matcher = app(ThemeMatcherService::class);
            return $matcher->recommendWithVariety($businessType, $prompt, $this->website->user_id);
        } catch (\Exception $e) {
            Log::warning("Theme matching failed: {$e->getMessage()}");
            return 'azure';
        }
    }

    // ─── Step 3: Generate AI Content ───

    private function generateContent(): array
    {
        $businessName = $this->website->name;
        $businessType = $this->website->business_type ?? 'business';
        $prompt = $this->website->ai_prompt ?? "A {$businessType} called {$businessName}";
        $theme = $this->website->ai_theme ?: 'azure';

        try {
            $aiService = app(AIContentService::class);
            $result = $aiService->generateWebsiteContent(
                $businessType,
                $businessName,
                $theme,
                null,
                $prompt
            );

            if ($result['success'] && !empty($result['data'])) {
                // Merge with existing content (preserve structure from customize page)
                $existing = $this->website->ai_generated_content ?? [];
                $merged = is_array($existing) ? array_merge($existing, $result['data']) : $result['data'];
                $this->website->update(['ai_generated_content' => $merged]);
                $this->updateStep('ai_content', 'AI content generated.');
                return $result['data'];
            }
        } catch (\Exception $e) {
            Log::warning("AI content generation failed: {$e->getMessage()}");
        }

        // Fallback content
        $this->updateStep('ai_content', 'Using default content.');
        $fallback = $this->buildFallbackContent($businessName, $businessType);
        // Merge with existing (preserve structure)
        $existing = $this->website->ai_generated_content ?? [];
        $merged = is_array($existing) ? array_merge($existing, $fallback) : $fallback;
        $this->website->update(['ai_generated_content' => $merged]);
        return $fallback;
    }

    private function buildFallbackContent(string $businessName, string $businessType): array
    {
        return [
            'site_title' => $businessName,
            'tagline' => "Your trusted {$businessType} partner",
            'pages' => [
                'home' => [
                    'hero_title' => "Welcome to {$businessName}",
                    'hero_subtitle' => "Professional {$businessType} services you can rely on",
                    'hero_cta' => 'Get Started',
                    'sections' => [
                        [
                            'type' => 'features',
                            'title' => 'Why Choose Us',
                            'items' => [
                                ['title' => 'Expert Team', 'description' => 'Our experienced professionals deliver outstanding results every time.', 'icon' => '⭐'],
                                ['title' => 'Quality Service', 'description' => 'We maintain the highest standards of quality in everything we do.', 'icon' => '✅'],
                                ['title' => 'Fast Delivery', 'description' => 'Quick turnaround times without compromising on quality.', 'icon' => '⚡'],
                                ['title' => 'Support 24/7', 'description' => 'Round-the-clock support to help you whenever you need it.', 'icon' => '🛡️'],
                                ['title' => 'Best Pricing', 'description' => 'Competitive pricing that delivers real value for your investment.', 'icon' => '💰'],
                                ['title' => 'Innovation', 'description' => 'Cutting-edge solutions that keep you ahead of the competition.', 'icon' => '🚀'],
                            ],
                        ],
                        [
                            'type' => 'stats',
                            'title' => 'Our Impact',
                            'items' => [
                                ['title' => '500+', 'description' => 'Happy Clients'],
                                ['title' => '1000+', 'description' => 'Projects Completed'],
                                ['title' => '15+', 'description' => 'Years Experience'],
                                ['title' => '99%', 'description' => 'Satisfaction Rate'],
                            ],
                        ],
                        [
                            'type' => 'about_preview',
                            'title' => "About {$businessName}",
                            'content' => "{$businessName} has been providing exceptional {$businessType} services for over a decade. Our commitment to quality and customer satisfaction sets us apart.",
                        ],
                        [
                            'type' => 'testimonials',
                            'title' => 'What Our Clients Say',
                            'items' => [
                                ['name' => 'Sarah Johnson', 'role' => 'CEO, TechCorp', 'content' => 'Outstanding service and exceptional quality. Highly recommended!'],
                                ['name' => 'Michael Chen', 'role' => 'Director, Innovate Inc', 'content' => 'Professional team that delivers results beyond expectations.'],
                                ['name' => 'Emily Rodriguez', 'role' => 'Founder, StartupXYZ', 'content' => 'Best decision we made was choosing this team for our project.'],
                                ['name' => 'David Kim', 'role' => 'Manager, GlobalCo', 'content' => 'Reliable, efficient, and always willing to go the extra mile.'],
                            ],
                        ],
                        [
                            'type' => 'cta',
                            'title' => 'Ready to Get Started?',
                            'subtitle' => "Contact us today and let's discuss how we can help your business grow.",
                            'button_text' => 'Contact Us',
                        ],
                    ],
                ],
                'about' => [
                    'title' => 'About Us',
                    'content' => "{$businessName} is a leading {$businessType} dedicated to providing excellent service. With years of experience and a passionate team, we deliver solutions that make a real difference.",
                    'mission' => 'To deliver exceptional quality and value to every client we serve.',
                    'vision' => "To be the most trusted {$businessType} in our industry.",
                ],
                'services' => [
                    'title' => 'Our Services',
                    'intro' => "Discover our range of professional {$businessType} services.",
                    'items' => [
                        ['title' => 'Consulting', 'description' => 'Expert guidance to help you achieve your goals.'],
                        ['title' => 'Strategy', 'description' => 'Data-driven strategies for sustainable growth.'],
                        ['title' => 'Implementation', 'description' => 'Flawless execution from concept to completion.'],
                        ['title' => 'Support', 'description' => 'Ongoing support to ensure continued success.'],
                        ['title' => 'Training', 'description' => 'Comprehensive training programs for your team.'],
                        ['title' => 'Analytics', 'description' => 'Detailed insights to measure and improve performance.'],
                    ],
                ],
                'contact' => [
                    'title' => 'Contact Us',
                    'subtitle' => "Get in touch with {$businessName} today.",
                    'address' => '123 Business Street, Suite 100',
                    'phone' => '(555) 123-4567',
                    'email' => 'info@' . Str::slug($businessName) . '.com',
                ],
            ],
        ];
    }

    // ─── Step 4: Download Stock Images ───

    private function downloadImages(string $sitePath, string $siteUrl, string $dbName): array
    {
        $businessName = $this->website->name;
        $businessType = $this->website->business_type ?? 'business';
        $prompt = $this->website->ai_prompt;
        $destDir = $sitePath . '/wp-content/uploads/' . date('Y/m');

        try {
            $unsplash = app(UnsplashService::class);
            $downloadedFiles = $unsplash->getWebsiteImages($businessName, $businessType, $destDir, $prompt);

            $imageUrls = [];
            $pdo = $this->getPdo($dbName);

            foreach ($downloadedFiles as $key => $filePath) {
                // Convert file path to WP-relative path and URL
                $relPath = date('Y/m') . '/' . basename($filePath);
                $url = $siteUrl . '/wp-content/uploads/' . $relPath;
                $imageUrls[$key] = $url;

                // Create WP attachment record
                $this->createAttachment($pdo, $url, $key, $relPath);
            }

            $this->updateStep('images', count($imageUrls) . ' stock images downloaded.');
            return $imageUrls;
        } catch (\Exception $e) {
            Log::warning("Image download failed: {$e->getMessage()}");
            $this->updateStep('images', 'Using placeholder images.');
            return [];
        }
    }

    private function createAttachment(\PDO $pdo, string $imageUrl, string $title, string $filePath): int
    {
        try {
            $now = now()->format('Y-m-d H:i:s');
            $slug = Str::slug($title);
            $mimeType = str_ends_with(strtolower($filePath), '.png') ? 'image/png' : 'image/jpeg';

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, post_mime_type, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'inherit', 'open', 'closed', ?, 'attachment', ?, ?, ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $title, $slug, $now, $now, $mimeType, $imageUrl]);
            $attachmentId = (int) $pdo->lastInsertId();

            if ($attachmentId) {
                $this->setPostMeta($pdo, $attachmentId, '_wp_attached_file', $filePath);
            }
            return $attachmentId;
        } catch (\Exception $e) {
            Log::warning("Failed to create attachment '{$title}': {$e->getMessage()}");
            return 0;
        }
    }

    // ─── Step 5: Build Elementor Pages ───

    private function buildElementorPages(AbstractLayout $layout, array $content, array $imageUrls, string $dbName, string $siteUrl): void
    {
        $pages = $this->website->pages ?: ['home', 'about', 'services', 'contact'];
        $pdo = $this->getPdo($dbName);

        // Normalize page slugs — AI sometimes returns capitalized values
        $pages = array_map(fn($p) => Str::slug($p), $pages);
        $pages = array_unique($pages);

        // Remove 'shop' from Elementor pages — WooCommerce creates its own Shop page
        $pages = array_values(array_filter($pages, fn($p) => $p !== 'shop'));

        // Map page types for layout building
        $pageTypeMap = [
            'home' => 'home',
            'about' => 'about',
            'about-us' => 'about',
            'services' => 'services',
            'our-services' => 'services',
            'portfolio' => 'portfolio',
            'contact' => 'contact',
            'contact-us' => 'contact',
        ];

        // Extract page content from AI response
        $pagesContent = $content['pages'] ?? [];

        // Get structure from customize page
        $structure = $this->website->ai_generated_content['structure'] ?? null;

        // Track used page types to avoid duplicates
        $usedTypes = [];
        $availableTypes = ['home', 'about', 'services', 'portfolio', 'contact'];

        foreach ($pages as $pageSlug) {
            // Find this page in structure (try slug, then title, then partial match)
            $structurePage = null;
            if ($structure) {
                // Pass 1: exact slug match
                foreach ($structure as $sp) {
                    $spSlug = Str::slug($sp['slug'] ?? $sp['title'] ?? '');
                    if ($spSlug === $pageSlug) {
                        $structurePage = $sp;
                        break;
                    }
                }
                // Pass 2: title-to-slug match (e.g., "Services & Rituals" → "services-rituals")
                if (!$structurePage) {
                    foreach ($structure as $sp) {
                        $titleSlug = Str::slug($sp['title'] ?? '');
                        if ($titleSlug === $pageSlug) {
                            $structurePage = $sp;
                            break;
                        }
                    }
                }
                // Pass 3: partial match (page slug contains structure slug or vice versa)
                if (!$structurePage) {
                    foreach ($structure as $sp) {
                        $spSlug = Str::slug($sp['slug'] ?? $sp['title'] ?? '');
                        if ($spSlug && $pageSlug && (str_contains($pageSlug, $spSlug) || str_contains($spSlug, $pageSlug))) {
                            $structurePage = $sp;
                            break;
                        }
                    }
                }
            }

            // Determine page type for layout builder
            $pageType = $pageTypeMap[$pageSlug] ?? $this->guessPageType($pageSlug, $structurePage);

            // If this type already used, pick next available type
            if (in_array($pageType, $usedTypes)) {
                $remaining = array_diff($availableTypes, $usedTypes);
                $pageType = !empty($remaining) ? array_shift($remaining) : 'services';
            }
            $usedTypes[] = $pageType;

            // Title from structure or default
            $title = $structurePage['title'] ?? match ($pageSlug) {
                'home' => 'Home',
                'about', 'about-us' => 'About Us',
                'services', 'our-services' => 'Our Services',
                'portfolio' => 'Portfolio',
                'packages' => 'Packages',
                'contact', 'contact-us', 'connect' => 'Contact Us',
                default => ucfirst(str_replace('-', ' ', $pageSlug)),
            };

            // Get page-specific content from AI data
            $pageContent = $pagesContent[$pageSlug] ?? $pagesContent[$pageType] ?? [];

            // Merge top-level content fields for backward compat
            $mergedContent = array_merge($content, $pageContent);

            // Use DynamicSectionBuilder when structure exists.
            // When no structure: generate a default structure based on page type
            // so every page goes through DynamicSectionBuilder for unique, AI-powered content.
            if (!$structurePage || empty($structurePage['sections'])) {
                $structurePage = ['title' => $title, 'slug' => $pageSlug, 'sections' => $this->generateDefaultSections($pageType, $title)];
            }

            $dynBuilder = new \App\Services\DynamicSectionBuilder(
                $layout, $mergedContent, $imageUrls,
                $this->website->name, $this->website->business_type ?? 'business',
                $this->website->ai_prompt ?? ''
            );
            // Generate AI content for this page's sections
            $dynBuilder->generatePageContent($structurePage['sections'], $title);
            $elements = $dynBuilder->buildPage($structurePage['sections'], $title);

            // Add WooCommerce products section to home page if WooCommerce is installed
            $wpPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs') . '/' . $this->website->slug;
            if ($pageType === 'home' && File::exists("{$wpPath}/wp-content/plugins/woocommerce/woocommerce.php")) {
                $elements[] = $this->buildProductsSection($content, $layout);
            }

            // Create the page with Elementor data
            $now = now()->format('Y-m-d H:i:s');
            // Use actual page slug for unique URLs
            $postName = $pageSlug;

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $title, $postName, $now, $now]);
            $postId = (int) $pdo->lastInsertId();

            if ($postId && !empty($elements)) {
                $elementorJson = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($elementorJson === false) {
                    // Sanitize bad UTF-8 and retry
                    Log::warning("json_encode failed for '{$title}': " . json_last_error_msg() . " — sanitizing UTF-8");
                    array_walk_recursive($elements, function (&$val) {
                        if (is_string($val)) $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8');
                    });
                    $elementorJson = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
                }
                $elementorJson = $this->fixSiteUrls($elementorJson);
                Log::info("Elementor data for '{$title}': " . strlen($elementorJson) . " bytes, " . count($elements) . " sections");

                $this->setPostMeta($pdo, $postId, '_elementor_data', $elementorJson);
                $this->setPostMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
                $this->setPostMeta($pdo, $postId, '_elementor_version', $this->getElementorVersion());
                $this->setPostMeta($pdo, $postId, '_elementor_page_settings', serialize(['hide_title' => 'yes']));
                $this->setPostMeta($pdo, $postId, '_elementor_css', '');
                $this->setPostMeta($pdo, $postId, '_wp_page_template', 'elementor_header_footer');
            }

            // Set as front page if home
            if ($pageType === 'home') {
                $pdo->exec("INSERT INTO wp_options (option_name, option_value) VALUES ('page_on_front', '{$postId}') ON DUPLICATE KEY UPDATE option_value = '{$postId}'");
                $pdo->exec("INSERT INTO wp_options (option_name, option_value) VALUES ('show_on_front', 'page') ON DUPLICATE KEY UPDATE option_value = 'page'");
                $this->website->update(['home_page_id' => $postId]);
            }
        }

        $this->updateStep('building_pages', 'Elementor pages created: ' . implode(', ', $pages));
    }

    private function buildProductsSection(array $content, AbstractLayout $layout): array
    {
        $c = $layout->colors();
        $f = $layout->fonts();
        $siteName = $this->website->name;

        return AbstractLayout::container([
            'content_width' => 'boxed',
            'boxed_width' => ['size' => 1200, 'unit' => 'px', 'sizes' => []],
            'padding' => AbstractLayout::pad(100, 40, 100, 40),
            'padding_mobile' => AbstractLayout::pad(60, 16, 60, 16),
            'flex_direction' => 'column',
            'flex_gap' => ['size' => 12, 'unit' => 'px', 'column' => '12', 'row' => '12'],
            'flex_align_items' => 'center',
            'background_background' => 'classic',
            'background_color' => $c['bg'] ?? '#f8fafc',
        ], [
            // Eyebrow
            $layout->eyebrow('Featured Collection'),

            // Title — uses layout's styling
            $layout->headline('Our Products', 'h2', array_merge(
                AbstractLayout::responsiveSize(42, 34, 26),
                ['_margin' => AbstractLayout::margin(0, 0, 8, 0)]
            )),

            // Subtitle
            $layout->bodyText(
                'Explore our curated selection of premium products, handpicked for quality and style.',
                ['_margin' => AbstractLayout::margin(0, 0, 32, 0)]
            ),

            // Product grid via shortcode
            AbstractLayout::container([
                'content_width' => 'full',
            ], [
                [
                    'id' => AbstractLayout::eid(),
                    'elType' => 'widget',
                    'widgetType' => 'shortcode',
                    'settings' => [
                        'shortcode' => '[products limit="8" columns="4" orderby="date" order="DESC"]',
                    ],
                    'elements' => [],
                ],
            ]),

            // CTA button using layout's style
            $layout->ctaButton('Browse All Products', '/shop/'),
        ]);
    }

    // ─── Step 6: Header & Footer ───

    private function buildHeaderFooter(AbstractLayout $layout, array $content, string $dbName, string $siteUrl): void
    {
        $pageSlugs = $this->website->pages ?: ['home', 'about', 'services', 'contact'];
        $businessName = $this->website->name;
        $pdo = $this->getPdo($dbName);
        $sitePath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs') . '/' . $this->website->slug;
        $hasWoo = File::exists("{$sitePath}/wp-content/plugins/woocommerce/woocommerce.php");

        // Normalize slugs (AI sometimes returns capitalized values)
        $pageSlugs = array_values(array_unique(array_map(fn($p) => Str::slug($p), $pageSlugs)));

        // Build page labels from structure if available, otherwise use defaults
        $structure = $this->website->ai_generated_content['structure'] ?? null;
        $pageLabels = [
            'home' => 'Home',
            'shop' => 'Shop',
            'about' => 'About Us',
            'about-us' => 'About Us',
            'services' => 'Services',
            'our-services' => 'Services',
            'portfolio' => 'Portfolio',
            'contact' => 'Contact',
            'contact-us' => 'Contact',
        ];
        // Override with custom structure titles
        if ($structure) {
            foreach ($structure as $page) {
                $slug = Str::slug($page['slug'] ?? $page['title'] ?? '');
                if ($slug) $pageLabels[$slug] = $page['title'] ?? ucfirst($slug);
            }
        }

        // Add Shop to nav for ecommerce sites (after Home)
        if ($hasWoo && !in_array('shop', $pageSlugs)) {
            array_splice($pageSlugs, 1, 0, ['shop']);
        }

        $pages = [];
        foreach ($pageSlugs as $slug) {
            $pages[$slug] = $pageLabels[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
        }

        // Build contact info from content
        $contactPage = $content['pages']['contact'] ?? [];
        $contactInfo = [
            'address' => $contactPage['address'] ?? '',
            'phone' => $contactPage['phone'] ?? '',
            'email' => $contactPage['email'] ?? 'info@' . Str::slug($businessName) . '.com',
        ];

        try {
            // Detect ecommerce site
            $sitePath = "C:/xampp/htdocs/" . $this->website->slug;
            $isEcommerce = File::exists("{$sitePath}/wp-content/plugins/woocommerce/woocommerce.php");

            if ($isEcommerce) {
                // Build WooCommerce-style e-commerce header
                $colors = $layout->colors();
                $headerElements = $this->buildEcommerceHeader($businessName, $pages, $colors, $content);
                $this->createHfeTemplate($pdo, 'type_header', 'Site Header', $headerElements, $siteUrl);
            } else {
                $headerElements = $layout->buildHeader($businessName, $pages);
                $this->createHfeTemplate($pdo, 'type_header', 'Site Header', $headerElements, $siteUrl);
            }

            // Build and insert footer
            $footerElements = $layout->buildFooter($businessName, $pages, $contactInfo);
            $this->createHfeTemplate($pdo, 'type_footer', 'Site Footer', $footerElements, $siteUrl);

            // Fix all nav URLs in header, footer, AND page content.
            // URLs are stored in two formats inside _elementor_data JSON:
            //   1. Button widget: "url":"/about/" (unescaped quotes)
            //   2. HTML widget:   href=\"/about/\" (backslash-escaped quotes in JSON string)
            // We fix BOTH patterns using PHP str_replace on each meta row.
            $siteBase = $this->website->slug;
            $rows = $pdo->query("SELECT meta_id, meta_value FROM wp_postmeta WHERE meta_key = '_elementor_data'")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $val = $row['meta_value'];
                $changed = false;
                foreach ($pageSlugs as $navSlug) {
                    if ($navSlug === 'home') continue;
                    // Pattern 1: Elementor button/link URLs — "url":"/about/"
                    $old1 = '"/' . $navSlug . '/"';
                    $new1 = '"/' . $siteBase . '/' . $navSlug . '/"';
                    // Pattern 2: HTML href in JSON strings — \"/about/\" (backslash-escaped quotes)
                    $old2 = '\\\"/' . $navSlug . '/\\\"';
                    $new2 = '\\\"/' . $siteBase . '/' . $navSlug . '/\\\"';
                    // Pattern 3: Without trailing slash
                    $old3 = '"/' . $navSlug . '"';
                    $new3 = '"/' . $siteBase . '/' . $navSlug . '"';
                    if (str_contains($val, $old1) || str_contains($val, $old2) || str_contains($val, $old3)) {
                        $val = str_replace([$old1, $old2, $old3], [$new1, $new2, $new3], $val);
                        $changed = true;
                    }
                }
                // Fix home link: href=\"/\" and "url":"/"
                $homePatterns = [
                    ['href=\\\"/\\\"', 'href=\\\"/' . $siteBase . '/\\\"'],
                    ['"url":"/"', '"url":"/' . $siteBase . '/"'],
                ];
                foreach ($homePatterns as [$oldH, $newH]) {
                    if (str_contains($val, $oldH)) {
                        $val = str_replace($oldH, $newH, $val);
                        $changed = true;
                    }
                }
                if ($changed) {
                    $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE meta_id = ?");
                    $stmt->execute([$val, $row['meta_id']]);
                }
            }

            $this->updateStep('header_footer', 'Header & footer created.');
        } catch (\Exception $e) {
            Log::warning("HFE setup failed: {$e->getMessage()}");
            $this->updateStep('header_footer', 'Header & footer setup failed (non-critical).');
        }
    }

    /**
     * Build a professional WooCommerce e-commerce header (3-row: announcement + main + category nav)
     */
    private function buildEcommerceHeader(string $siteName, array $pages, array $colors, array $content): array
    {
        $primary = $colors['primary'] ?? '#111111';
        $bg = $colors['bg'] ?? '#ffffff';
        $text = $colors['text'] ?? '#111111';
        $muted = $colors['muted'] ?? '#666666';
        $border = $colors['border'] ?? '#e5e5e5';
        $surface = $colors['surface'] ?? '#f8f8f8';

        // Build nav links (Shop is already in $pages for ecommerce)
        $navLinks = '';
        foreach ($pages as $slug => $label) {
            if ($slug === 'home') continue;
            $navLinks .= "<a href=\"/{$slug}/\" class=\"wnb-cat-link\">{$label}</a>";
        }
        // Add Shop if not already in pages
        if (!isset($pages['shop'])) {
            $navLinks .= '<a href="/shop/" class="wnb-cat-link">Shop</a>';
        }

        // Announcement texts
        $bizType = $content['business_type'] ?? 'products';
        $announcements = [
            "FREE SHIPPING ON ORDERS OVER \$50 ✦ SHOP NOW",
            strtoupper($siteName) . " — PREMIUM {$bizType} ✦ NEW ARRIVALS WEEKLY",
            "★ TRUSTED BY 2000+ CUSTOMERS ★ FAST DELIVERY ★ EASY RETURNS",
        ];
        $marqueeText = implode(str_repeat('&nbsp;', 12), array_merge($announcements, $announcements));

        $html = <<<HTML
<style>
/* ═══ E-COMMERCE HEADER ═══ */
.wnb-ecom-hdr { position:sticky; top:0; z-index:9999; width:100%; font-family:'Inter',-apple-system,sans-serif; }

/* Row 1: Announcement */
.wnb-announce {
    background:{$text}; color:{$bg}; font-size:11px; font-weight:600;
    letter-spacing:2px; text-transform:uppercase; overflow:hidden;
    white-space:nowrap; height:36px; line-height:36px;
}
.wnb-announce-track {
    display:inline-block; animation:wnb-scroll 35s linear infinite;
    padding-left:100%;
}
@keyframes wnb-scroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }

/* Row 2: Main Bar */
.wnb-main-bar {
    display:grid; grid-template-columns:1fr 2fr 1fr;
    align-items:center; padding:14px 40px;
    background:{$bg}; border-bottom:1px solid {$border};
    max-width:100%; box-sizing:border-box;
}
.wnb-logo {
    font-size:22px; font-weight:900; letter-spacing:2px;
    color:{$text}; text-decoration:none; text-transform:uppercase;
    font-family:'Inter',sans-serif;
}
.wnb-logo:hover { color:{$primary}; }
.wnb-search {
    display:flex; max-width:500px; margin:0 auto; width:100%;
}
.wnb-search input {
    flex:1; padding:10px 18px; border:2px solid {$border};
    border-right:none; border-radius:4px 0 0 4px;
    font-size:13px; outline:none; font-family:inherit;
    text-transform:uppercase; letter-spacing:1px; color:{$text};
    background:{$bg};
}
.wnb-search input:focus { border-color:{$text}; }
.wnb-search input::placeholder { color:{$muted}; }
.wnb-search button {
    padding:10px 20px; background:{$text}; color:{$bg};
    border:2px solid {$text}; border-radius:0 4px 4px 0;
    cursor:pointer; font-size:12px; font-weight:700;
    letter-spacing:1.5px; text-transform:uppercase;
    font-family:inherit; transition:background .2s;
}
.wnb-search button:hover { background:{$primary}; border-color:{$primary}; }
.wnb-icons {
    display:flex; align-items:center; justify-content:flex-end; gap:20px;
}
.wnb-icon-btn {
    position:relative; background:none; border:none; cursor:pointer;
    color:{$text}; transition:color .2s; padding:4px;
}
.wnb-icon-btn:hover { color:{$primary}; }
.wnb-icon-btn svg { width:22px; height:22px; stroke:currentColor; fill:none; stroke-width:1.5; stroke-linecap:round; stroke-linejoin:round; }
.wnb-cart-badge {
    position:absolute; top:-4px; right:-6px;
    background:{$primary}; color:{$bg}; font-size:9px; font-weight:800;
    width:16px; height:16px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    line-height:1;
}

/* Row 3: Category Nav */
.wnb-cat-nav {
    display:flex; align-items:center; justify-content:center;
    gap:0; background:{$bg}; border-bottom:1px solid {$border};
    padding:0 40px; overflow-x:auto;
}
.wnb-cat-link {
    display:inline-flex; align-items:center; gap:4px;
    padding:12px 18px; font-size:12px; font-weight:600;
    letter-spacing:1.5px; text-transform:uppercase;
    color:{$text}; text-decoration:none;
    border-bottom:2px solid transparent;
    transition:all .2s; white-space:nowrap;
}
.wnb-cat-link:hover { color:{$primary}; border-bottom-color:{$primary}; }
.wnb-cat-link .wnb-badge-new {
    background:#e53e3e; color:#fff; font-size:8px; font-weight:800;
    padding:2px 5px; border-radius:3px; letter-spacing:.5px;
    position:relative; top:-4px;
}

/* Mobile */
.wnb-mob-toggle { display:none; }
@media(max-width:768px) {
    .wnb-main-bar { grid-template-columns:auto 1fr auto; padding:10px 16px; gap:12px; }
    .wnb-logo { font-size:16px; }
    .wnb-search input { font-size:12px; padding:8px 12px; }
    .wnb-search button { padding:8px 12px; font-size:11px; }
    .wnb-cat-nav { padding:0 12px; justify-content:flex-start; }
    .wnb-cat-link { padding:10px 12px; font-size:11px; }
    .wnb-icons { gap:14px; }
    .wnb-announce { font-size:10px; height:30px; line-height:30px; }
}
</style>

<header class="wnb-ecom-hdr">
    <!-- Announcement Bar -->
    <div class="wnb-announce">
        <div class="wnb-announce-track">{$marqueeText}</div>
    </div>

    <!-- Main Bar -->
    <div class="wnb-main-bar">
        <a href="/" class="wnb-logo">{$siteName}</a>
        <div class="wnb-search">
            <input type="text" placeholder="SEARCH FOR PRODUCTS" />
            <button type="button">SEARCH</button>
        </div>
        <div class="wnb-icons">
            <a href="/my-account/" class="wnb-icon-btn" title="Account">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
            <a href="/wishlist/" class="wnb-icon-btn" title="Wishlist">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </a>
            <a href="/cart/" class="wnb-icon-btn" title="Cart">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span class="wnb-cart-badge">0</span>
            </a>
        </div>
    </div>

    <!-- Category Nav -->
    <nav class="wnb-cat-nav">
        {$navLinks}
    </nav>
</header>

<script>
// Sticky header shadow on scroll
(function(){
    var hdr = document.querySelector('.wnb-ecom-hdr');
    if(!hdr) return;
    window.addEventListener('scroll', function(){
        hdr.style.boxShadow = window.scrollY > 10 ? '0 2px 20px rgba(0,0,0,.08)' : 'none';
    }, {passive:true});
})();
</script>
HTML;

        return [AbstractLayout::html($html)];
    }

    private function createHfeTemplate(\PDO $pdo, string $type, string $title, array $elementorData, string $siteUrl): void
    {
        $now = date('Y-m-d H:i:s');
        $slug = Str::slug($title);

        $stmt = $pdo->prepare("INSERT INTO wp_posts
            (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
             post_status, comment_status, ping_status, post_name, post_type,
             post_modified, post_modified_gmt, guid, to_ping, pinged, post_content_filtered)
            VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'elementor-hf', ?, ?, ?, '', '', '')");
        $guid = rtrim($siteUrl, '/') . '/?p=0';
        $stmt->execute([$now, $now, $title, $slug, $now, $now, $guid]);
        $postId = (int) $pdo->lastInsertId();

        if (!$postId) {
            throw new \RuntimeException("Failed to create HFE template: {$title}");
        }

        // Update guid with actual post ID
        $pdo->exec("UPDATE wp_posts SET guid = " . $pdo->quote(rtrim($siteUrl, '/') . "/?p={$postId}") . " WHERE ID = {$postId}");

        // Set Elementor data
        $json = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $json = $this->fixSiteUrls($json);
        $this->setPostMeta($pdo, $postId, '_elementor_data', $json);
        $this->setPostMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
        $this->setPostMeta($pdo, $postId, '_elementor_version', $this->getElementorVersion());
        $this->setPostMeta($pdo, $postId, '_elementor_template_type', 'wp-post');
        $this->setPostMeta($pdo, $postId, '_elementor_page_settings', serialize([]));
        $this->setPostMeta($pdo, $postId, '_elementor_css', '');

        // HFE-specific meta
        $this->setPostMeta($pdo, $postId, 'ehf_template_type', $type);

        // Target: entire website
        $target = serialize([
            ['rule' => ['basic-global'], 'specific' => []],
        ]);
        $this->setPostMeta($pdo, $postId, 'ehf_target_include_locations', $target);
        $this->setPostMeta($pdo, $postId, 'ehf_target_exclude_locations', serialize([]));
        $this->setPostMeta($pdo, $postId, 'ehf_target_user_roles', serialize([]));
    }

    // ─── Fix root-relative URLs to include site slug ───

    private function fixSiteUrls(string $json): string
    {
        $slug = $this->website->slug;

        // Build complete page list: standard + all custom pages from website
        $pageNames = ['about', 'services', 'portfolio', 'contact', 'shop', 'cart', 'checkout', 'myaccount', 'my-account', 'wishlist', 'faq', 'menu'];
        // Add actual website pages (custom pages like "vip-lounge", "our-barbers", "blog", etc.)
        $websitePages = $this->website->pages ?? [];
        foreach ($websitePages as $p) {
            $ps = Str::slug($p);
            if ($ps && $ps !== 'home' && !in_array($ps, $pageNames)) {
                $pageNames[] = $ps;
            }
        }
        // Also add pages from structure
        $structure = $this->website->ai_generated_content['structure'] ?? [];
        foreach ($structure as $sp) {
            $ps = Str::slug($sp['slug'] ?? $sp['title'] ?? '');
            if ($ps && $ps !== 'home' && !in_array($ps, $pageNames)) {
                $pageNames[] = $ps;
            }
        }

        foreach ($pageNames as $page) {
            // Unescaped: href="/about/" → href="/slug/about/"
            $json = str_replace('href="/' . $page . '/"', 'href="/' . $slug . '/' . $page . '/"', $json);
            // Escaped (inside JSON strings): href=\"/about/\" → href=\"/slug/about/\"
            $json = str_replace('href=\\"/' . $page . '/\\"', 'href=\\"/' . $slug . '/' . $page . '/\\"', $json);
            // Elementor button link URLs: "url":"/about/" → "url":"/slug/about/"
            $json = str_replace('"url":"/' . $page . '/"', '"url":"/' . $slug . '/' . $page . '/"', $json);
        }

        // Home link — unescaped & escaped
        $json = str_replace('href="/"', 'href="/' . $slug . '/"', $json);
        $json = str_replace('href=\\"/\\"', 'href=\\"/' . $slug . '/\\"', $json);
        $json = str_replace('"url":"/"', '"url":"/' . $slug . '/"', $json);

        return $json;
    }

    // ─── Step 7: Configure Site ───

    private function configureSite(string $dbName, array $content): void
    {
        $pdo = $this->getPdo($dbName);
        $slug = $this->website->slug;
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $sitePath = "{$htdocs}/{$slug}";
        $siteUrl = "http://localhost/{$slug}";

        // Set site title and tagline
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogname'")->execute([$this->website->name]);
        $tagline = $content['tagline'] ?? $this->website->business_type ?? '';
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogdescription'")->execute([$tagline]);

        // Ensure hello-elementor theme is active (classic theme required for Elementor + HFE)
        $this->setOption($pdo, 'template', 'hello-elementor');
        $this->setOption($pdo, 'stylesheet', 'hello-elementor');

        // Ensure all plugins are activated
        $pluginList = [
            'elementor/elementor.php',
            'elementor-pro/elementor-pro.php',
            'header-footer-elementor/header-footer-elementor.php',
            'webnewbiz-builder/webnewbiz-builder.php',
        ];

        // Add WooCommerce if plugin files exist
        if (File::exists("{$sitePath}/wp-content/plugins/woocommerce/woocommerce.php")) {
            $pluginList[] = 'woocommerce/woocommerce.php';
        }

        // Copy WebNewBiz Builder plugin if not already present
        $wnbPluginDir = $sitePath . '/wp-content/plugins/webnewbiz-builder';
        if (!File::isDirectory($wnbPluginDir)) {
            $wnbSource = base_path('storage/plugins/webnewbiz-builder');
            if (File::isDirectory($wnbSource)) {
                File::copyDirectory($wnbSource, $wnbPluginDir);
            }
        }

        $plugins = serialize($pluginList);
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'active_plugins'")->execute([$plugins]);

        // Ensure elementor-hf CPT is supported
        $cpts = serialize(['post', 'page', 'elementor-hf']);
        $this->setOption($pdo, 'elementor_cpt_support', $cpts);

        // Enable Elementor container experiment (required for e-con layout elements)
        $this->setOption($pdo, 'elementor_experiment-container', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_optimized_css_loading', 'active');
        $this->setOption($pdo, 'elementor_experiment-container_grid', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_swiper_latest', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_element_cache', 'active');

        // Create Elementor default kit (required for editor to work)
        $this->ensureElementorKit($pdo, $siteUrl);

        // Set permalink structure
        $this->setOption($pdo, 'permalink_structure', '/%postname%/');

        // Remove default post/page
        $pdo->exec("DELETE FROM wp_posts WHERE post_type = 'post' AND post_title = 'Hello world!'");
        $pdo->exec("DELETE FROM wp_posts WHERE post_type = 'page' AND post_title = 'Sample Page'");

        // Set up WooCommerce if it's an ecommerce site
        if (in_array('woocommerce/woocommerce.php', $pluginList)) {
            $this->setupWooCommerce($pdo, $dbName, $siteUrl, $content);
        }

        // Create wp-auto-login.php for direct WP admin access
        $this->createAutoLoginScript($sitePath);

        // Store WebNewBiz platform connection options (after auto-login so token exists)
        $this->website->refresh();
        $connToken = $this->website->wp_auto_login_token ? decrypt($this->website->wp_auto_login_token) : '';
        $this->setOption($pdo, 'webnewbiz_connection_token', $connToken);
        $this->setOption($pdo, 'webnewbiz_platform_url', config('app.frontend_url', 'http://localhost:4200') . '/dashboard');
        $this->setOption($pdo, 'webnewbiz_connected_at', now()->toDateTimeString());
        $this->setOption($pdo, 'wnb_claude_api_key', env('ANTHROPIC_API_KEY', ''));

        // AI Copilot — platform token + website ID for copilot widget
        $platformApiUrl = config('app.url', 'http://localhost:8000');
        $platformToken = $this->website->user->createToken('copilot-' . $this->website->slug)->plainTextToken ?? '';
        $this->setOption($pdo, 'webnewbiz_platform_token', $platformToken);
        $this->setOption($pdo, 'webnewbiz_website_id', (string) $this->website->id);
        $this->setOption($pdo, 'webnewbiz_platform_api_url', $platformApiUrl);

        // Clear ALL Elementor caches so it regenerates on first visit
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_element_cache'");
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_css'");
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_page_assets'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%cache%'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE 'elementor_css%'");

        // Also delete filesystem CSS cache
        $cssPath = $sitePath . '/wp-content/uploads/elementor/css';
        if (File::isDirectory($cssPath)) {
            File::deleteDirectory($cssPath);
        }

        $this->updateStep('plugins', 'Site configured.');
    }

    private function createAutoLoginScript(string $sitePath): void
    {
        $token = Str::random(32);
        $password = $this->website->wp_admin_password ?: config('webnewbiz.admin_password', 'password');
        $adminUser = $this->website->wp_admin_user ?: 'admin';

        $script = <<<PHPSCRIPT
<?php
\$token = isset(\$_GET['token']) ? \$_GET['token'] : '';
\$expected = '{$token}';
\$password = '{$password}';

if (empty(\$token) || !hash_equals(\$expected, \$token)) {
    http_response_code(403);
    die('Invalid token');
}

define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';

\$creds = array('user_login' => '{$adminUser}', 'user_password' => \$password, 'remember' => true);
\$user = wp_signon(\$creds, false);

if (is_wp_error(\$user)) { wp_die('Auto-login failed: ' . \$user->get_error_message()); }

wp_set_current_user(\$user->ID);
wp_set_auth_cookie(\$user->ID, true);

\$redirect = isset(\$_GET['redirect']) ? \$_GET['redirect'] : '';
if (!empty(\$redirect) && strpos(\$redirect, '/wp-admin') === 0) {
    wp_safe_redirect(site_url(\$redirect));
} else {
    wp_safe_redirect(admin_url());
}
exit;
PHPSCRIPT;

        File::put($sitePath . '/wp-auto-login.php', $script);
        $this->website->update(['wp_auto_login_token' => encrypt($token)]);
        $this->updateStep('plugins', 'Auto-login configured.');

        // Install WP Bridge management script
        $this->createBridgeScript($sitePath, $token);
    }

    private function createBridgeScript(string $sitePath, string $token): void
    {
        $stubPath = base_path('stubs/wp-site-manager.php');
        if (!File::exists($stubPath)) {
            $this->updateStep('plugins', 'Bridge stub not found, skipping.');
            return;
        }
        $bridge = File::get($stubPath);
        $bridge = str_replace('__BRIDGE_TOKEN__', $token, $bridge);
        File::put($sitePath . '/wp-site-manager.php', $bridge);
        $this->updateStep('plugins', 'Site manager bridge installed.');
    }

    // ─── WooCommerce Setup ───

    private function setupWooCommerce(\PDO $pdo, string $dbName, string $siteUrl, array $content): void
    {
        $businessName = $this->website->name;
        $businessType = $this->website->business_type ?? 'store';

        // Create WooCommerce pages
        $now = date('Y-m-d H:i:s');
        $wooPages = [
            'shop' => ['title' => 'Shop', 'option' => 'woocommerce_shop_page_id'],
            'cart' => ['title' => 'Cart', 'option' => 'woocommerce_cart_page_id', 'shortcode' => '[woocommerce_cart]'],
            'checkout' => ['title' => 'Checkout', 'option' => 'woocommerce_checkout_page_id', 'shortcode' => '[woocommerce_checkout]'],
            'myaccount' => ['title' => 'My Account', 'option' => 'woocommerce_myaccount_page_id', 'shortcode' => '[woocommerce_my_account]'],
        ];

        foreach ($wooPages as $slug => $page) {
            $postContent = $page['shortcode'] ?? '';
            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $postContent, $page['title'], $slug, $now, $now]);
            $pageId = (int) $pdo->lastInsertId();

            if ($pageId) {
                $this->setOption($pdo, $page['option'], (string) $pageId);
            }
        }

        // WooCommerce core settings
        $this->setOption($pdo, 'woocommerce_currency', 'USD');
        $this->setOption($pdo, 'woocommerce_currency_pos', 'left');
        $this->setOption($pdo, 'woocommerce_price_thousand_sep', ',');
        $this->setOption($pdo, 'woocommerce_price_decimal_sep', '.');
        $this->setOption($pdo, 'woocommerce_price_num_decimals', '2');
        $this->setOption($pdo, 'woocommerce_default_country', 'US:CA');
        $this->setOption($pdo, 'woocommerce_calc_taxes', 'no');
        $this->setOption($pdo, 'woocommerce_enable_reviews', 'yes');
        $this->setOption($pdo, 'woocommerce_manage_stock', 'yes');
        $this->setOption($pdo, 'woocommerce_onboarding_profile', serialize(['completed' => true]));
        $this->setOption($pdo, 'woocommerce_task_list_hidden', 'yes');
        $this->setOption($pdo, 'woocommerce_task_list_complete', 'yes');
        $this->setOption($pdo, 'woocommerce_show_marketplace_suggestions', 'no');
        // Disable WooCommerce "coming soon" / "launch your store" mode (WC 9+)
        $this->setOption($pdo, 'woocommerce_coming_soon', 'no');
        $this->setOption($pdo, 'woocommerce_feature_site_visibility_badge_enabled', 'no');

        // Create sample products with images
        $products = $this->getProductsForBusiness($content, $businessType);
        $slug = $this->website->slug;
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $uploadsDir = "{$htdocs}/{$slug}/wp-content/uploads/" . date('Y/m');

        foreach ($products as $i => $product) {
            // Find matching product image (product1.jpg, product2.jpg, etc.)
            $imgKey = 'product' . ($i + 1);
            $imgPath = "{$uploadsDir}/{$imgKey}.jpg";
            $imgUrl = file_exists($imgPath) ? "{$siteUrl}/wp-content/uploads/" . date('Y/m') . "/{$imgKey}.jpg" : '';

            $this->createWooProduct($pdo, $product, $siteUrl, $imgUrl);
        }

        $this->updateStep('plugins', count($products) . ' products added.');
    }

    private function getProductsForBusiness(array $content, string $businessType): array
    {
        // Try to get products from AI content
        $aiProducts = $content['products'] ?? $content['pages']['shop']['items'] ?? [];
        if (!empty($aiProducts)) {
            return $aiProducts;
        }

        // Fallback products based on business type
        $typeKeyword = strtolower($businessType);
        if (str_contains($typeKeyword, 'cloth') || str_contains($typeKeyword, 'fashion') || str_contains($typeKeyword, 'apparel')) {
            return [
                ['title' => 'Classic Cotton Tee', 'price' => '29.99', 'sale_price' => '', 'description' => 'Premium quality cotton t-shirt with a comfortable fit. Available in multiple colors.', 'short' => 'Soft & breathable cotton tee', 'category' => 'T-Shirts', 'sku' => 'TEE-001'],
                ['title' => 'Slim Fit Denim Jeans', 'price' => '79.99', 'sale_price' => '59.99', 'description' => 'Modern slim fit jeans crafted from premium stretch denim. Perfect for casual and semi-formal occasions.', 'short' => 'Stretch denim slim fit', 'category' => 'Jeans', 'sku' => 'JEN-001'],
                ['title' => 'Casual Linen Shirt', 'price' => '49.99', 'sale_price' => '', 'description' => 'Lightweight linen shirt ideal for warm weather. Relaxed fit with a modern collar.', 'short' => 'Breezy linen button-down', 'category' => 'Shirts', 'sku' => 'SHT-001'],
                ['title' => 'Wool Blend Blazer', 'price' => '149.99', 'sale_price' => '119.99', 'description' => 'Elegant wool blend blazer for a polished look. Features two-button closure and interior pockets.', 'short' => 'Tailored wool blend blazer', 'category' => 'Blazers', 'sku' => 'BLZ-001'],
                ['title' => 'Knit Pullover Sweater', 'price' => '64.99', 'sale_price' => '', 'description' => 'Cozy knit sweater with ribbed cuffs and hem. Perfect layering piece for cooler days.', 'short' => 'Warm & cozy knit pullover', 'category' => 'Sweaters', 'sku' => 'SWT-001'],
                ['title' => 'Chino Shorts', 'price' => '39.99', 'sale_price' => '34.99', 'description' => 'Versatile chino shorts with a clean tailored look. Comfortable cotton-blend fabric.', 'short' => 'Classic chino shorts', 'category' => 'Shorts', 'sku' => 'SHR-001'],
                ['title' => 'Leather Belt', 'price' => '34.99', 'sale_price' => '', 'description' => 'Genuine leather belt with polished metal buckle. A timeless accessory for any outfit.', 'short' => 'Genuine leather belt', 'category' => 'Accessories', 'sku' => 'BLT-001'],
                ['title' => 'Oversized Hoodie', 'price' => '54.99', 'sale_price' => '', 'description' => 'Ultra-soft oversized hoodie with kangaroo pocket. Perfect for relaxed weekends.', 'short' => 'Comfy oversized hoodie', 'category' => 'Hoodies', 'sku' => 'HOD-001'],
            ];
        }

        // Business-type specific products
        $typeProducts = [
            'restaurant' => [
                ['title' => 'Signature Burger', 'price' => '18.99', 'sale_price' => '', 'short' => 'House specialty burger', 'category' => 'Main Course', 'sku' => 'MC-001'],
                ['title' => 'Pasta Carbonara', 'price' => '16.99', 'sale_price' => '', 'short' => 'Classic Italian pasta', 'category' => 'Main Course', 'sku' => 'MC-002'],
                ['title' => 'Grilled Ribeye Steak', 'price' => '34.99', 'sale_price' => '29.99', 'short' => 'Premium cut steak', 'category' => 'Main Course', 'sku' => 'MC-003'],
                ['title' => 'Sushi Platter', 'price' => '28.99', 'sale_price' => '', 'short' => 'Chef\'s selection sushi', 'category' => 'Specials', 'sku' => 'SP-001'],
                ['title' => 'Margherita Pizza', 'price' => '14.99', 'sale_price' => '12.99', 'short' => 'Wood-fired pizza', 'category' => 'Pizza', 'sku' => 'PZ-001'],
                ['title' => 'Chocolate Lava Cake', 'price' => '9.99', 'sale_price' => '', 'short' => 'Rich chocolate dessert', 'category' => 'Desserts', 'sku' => 'DS-001'],
                ['title' => 'Craft Cocktail', 'price' => '12.99', 'sale_price' => '', 'short' => 'Handcrafted cocktail', 'category' => 'Drinks', 'sku' => 'DR-001'],
                ['title' => 'Caesar Salad', 'price' => '11.99', 'sale_price' => '', 'short' => 'Fresh garden salad', 'category' => 'Starters', 'sku' => 'ST-001'],
            ],
            'fitness' => [
                ['title' => 'Monthly Membership', 'price' => '49.99', 'sale_price' => '39.99', 'short' => 'Full gym access', 'category' => 'Memberships', 'sku' => 'MEM-001'],
                ['title' => 'Personal Training (5 Sessions)', 'price' => '199.99', 'sale_price' => '', 'short' => '5 one-on-one sessions', 'category' => 'Training', 'sku' => 'PT-001'],
                ['title' => 'Resistance Band Set', 'price' => '24.99', 'sale_price' => '', 'short' => '5-level resistance bands', 'category' => 'Equipment', 'sku' => 'EQ-001'],
                ['title' => 'Premium Dumbbells', 'price' => '89.99', 'sale_price' => '74.99', 'short' => 'Adjustable dumbbell set', 'category' => 'Equipment', 'sku' => 'EQ-002'],
                ['title' => 'Sport Water Bottle', 'price' => '19.99', 'sale_price' => '', 'short' => 'BPA-free 32oz bottle', 'category' => 'Accessories', 'sku' => 'AC-001'],
                ['title' => 'Gym Bag Pro', 'price' => '44.99', 'sale_price' => '', 'short' => 'Spacious training bag', 'category' => 'Accessories', 'sku' => 'AC-002'],
                ['title' => 'Running Shoes', 'price' => '129.99', 'sale_price' => '99.99', 'short' => 'Lightweight performance shoes', 'category' => 'Footwear', 'sku' => 'FW-001'],
                ['title' => 'Fitness Tracker', 'price' => '79.99', 'sale_price' => '', 'short' => 'Smart fitness watch', 'category' => 'Tech', 'sku' => 'TK-001'],
            ],
            'beauty' => [
                ['title' => 'Hydrating Face Serum', 'price' => '45.99', 'sale_price' => '', 'short' => 'Deep hydration serum', 'category' => 'Skincare', 'sku' => 'SK-001'],
                ['title' => 'Anti-Aging Cream', 'price' => '59.99', 'sale_price' => '49.99', 'short' => 'Rejuvenating moisturizer', 'category' => 'Skincare', 'sku' => 'SK-002'],
                ['title' => 'Essential Oil Set', 'price' => '34.99', 'sale_price' => '', 'short' => '6-piece aromatherapy set', 'category' => 'Aromatherapy', 'sku' => 'AR-001'],
                ['title' => 'Keratin Shampoo', 'price' => '24.99', 'sale_price' => '', 'short' => 'Salon-quality hair care', 'category' => 'Hair Care', 'sku' => 'HC-001'],
                ['title' => 'Luxury Lipstick Set', 'price' => '39.99', 'sale_price' => '32.99', 'short' => '4 premium shades', 'category' => 'Makeup', 'sku' => 'MK-001'],
                ['title' => 'Body Butter Lotion', 'price' => '28.99', 'sale_price' => '', 'short' => 'Rich body moisturizer', 'category' => 'Body Care', 'sku' => 'BC-001'],
                ['title' => 'Nail Polish Collection', 'price' => '19.99', 'sale_price' => '', 'short' => '8-color nail set', 'category' => 'Nails', 'sku' => 'NL-001'],
                ['title' => 'Spa Gift Box', 'price' => '89.99', 'sale_price' => '74.99', 'short' => 'Luxury gift set', 'category' => 'Gift Sets', 'sku' => 'GF-001'],
            ],
            'technology' => [
                ['title' => 'Website Development', 'price' => '999.99', 'sale_price' => '', 'short' => 'Custom responsive website', 'category' => 'Web Services', 'sku' => 'WEB-001'],
                ['title' => 'Mobile App MVP', 'price' => '2499.99', 'sale_price' => '', 'short' => 'iOS & Android app', 'category' => 'App Development', 'sku' => 'APP-001'],
                ['title' => 'Wireless Headphones', 'price' => '79.99', 'sale_price' => '59.99', 'short' => 'Noise-cancelling earbuds', 'category' => 'Accessories', 'sku' => 'AC-001'],
                ['title' => 'Smart Watch Pro', 'price' => '249.99', 'sale_price' => '', 'short' => 'Fitness & health tracker', 'category' => 'Wearables', 'sku' => 'WR-001'],
                ['title' => 'Mechanical Keyboard', 'price' => '89.99', 'sale_price' => '', 'short' => 'RGB gaming keyboard', 'category' => 'Peripherals', 'sku' => 'PR-001'],
                ['title' => 'Ultra-Wide Monitor', 'price' => '449.99', 'sale_price' => '399.99', 'short' => '34" curved display', 'category' => 'Displays', 'sku' => 'DP-001'],
                ['title' => 'USB-C Hub', 'price' => '39.99', 'sale_price' => '', 'short' => '7-in-1 multiport adapter', 'category' => 'Accessories', 'sku' => 'AC-002'],
                ['title' => 'Digital Stylus Pen', 'price' => '49.99', 'sale_price' => '', 'short' => 'Precision drawing pen', 'category' => 'Accessories', 'sku' => 'AC-003'],
            ],
            'health' => [
                ['title' => 'Multivitamin Complex', 'price' => '29.99', 'sale_price' => '', 'short' => 'Daily health supplement', 'category' => 'Supplements', 'sku' => 'SUP-001'],
                ['title' => 'First Aid Kit', 'price' => '24.99', 'sale_price' => '', 'short' => '100-piece medical kit', 'category' => 'Medical Supplies', 'sku' => 'MS-001'],
                ['title' => 'Blood Pressure Monitor', 'price' => '49.99', 'sale_price' => '39.99', 'short' => 'Digital BP monitor', 'category' => 'Devices', 'sku' => 'DV-001'],
                ['title' => 'Digital Thermometer', 'price' => '14.99', 'sale_price' => '', 'short' => 'Instant read thermometer', 'category' => 'Devices', 'sku' => 'DV-002'],
                ['title' => 'Herbal Wellness Tea', 'price' => '12.99', 'sale_price' => '', 'short' => 'Organic calming blend', 'category' => 'Wellness', 'sku' => 'WL-001'],
                ['title' => 'Aromatherapy Oils Set', 'price' => '34.99', 'sale_price' => '29.99', 'short' => 'Natural essential oils', 'category' => 'Wellness', 'sku' => 'WL-002'],
                ['title' => 'N95 Face Masks (50pk)', 'price' => '19.99', 'sale_price' => '', 'short' => 'Medical-grade masks', 'category' => 'Protection', 'sku' => 'PT-001'],
                ['title' => 'Health & Wellness Guide', 'price' => '9.99', 'sale_price' => '', 'short' => 'Complete wellness book', 'category' => 'Books', 'sku' => 'BK-001'],
            ],
            'realestate' => [
                ['title' => 'Luxury Villa Listing', 'price' => '549000', 'sale_price' => '', 'short' => '4BR/3BA modern villa', 'category' => 'Residential', 'sku' => 'RES-001'],
                ['title' => 'Modern Apartment', 'price' => '289000', 'sale_price' => '', 'short' => '2BR downtown apartment', 'category' => 'Apartments', 'sku' => 'APT-001'],
                ['title' => 'Commercial Office', 'price' => '450000', 'sale_price' => '', 'short' => 'Prime location office', 'category' => 'Commercial', 'sku' => 'COM-001'],
                ['title' => 'Beachfront Property', 'price' => '750000', 'sale_price' => '699000', 'short' => 'Ocean view 3BR home', 'category' => 'Luxury', 'sku' => 'LUX-001'],
                ['title' => 'Cottage Retreat', 'price' => '195000', 'sale_price' => '', 'short' => 'Charming 2BR cottage', 'category' => 'Residential', 'sku' => 'RES-002'],
                ['title' => 'Penthouse Suite', 'price' => '899000', 'sale_price' => '849000', 'short' => 'Skyline view penthouse', 'category' => 'Luxury', 'sku' => 'LUX-002'],
                ['title' => 'Suburban Townhouse', 'price' => '325000', 'sale_price' => '', 'short' => '3BR family townhouse', 'category' => 'Residential', 'sku' => 'RES-003'],
                ['title' => 'Industrial Loft', 'price' => '275000', 'sale_price' => '', 'short' => 'Converted warehouse loft', 'category' => 'Unique', 'sku' => 'UNQ-001'],
            ],
        ];

        // Match business type to product set
        foreach ($typeProducts as $key => $prods) {
            if (str_contains($typeKeyword, $key)) {
                return array_map(fn($p) => array_merge($p, ['description' => $p['description'] ?? $p['short']]), $prods);
            }
        }

        // Generic fallback — use AI content service names as product names
        $services = $content['pages']['services']['items'] ?? [];
        if (count($services) >= 4) {
            $result = [];
            foreach (array_slice($services, 0, 8) as $i => $svc) {
                $result[] = [
                    'title' => $svc['title'] ?? "Service " . ($i + 1),
                    'price' => (string)(49.99 + ($i * 25)),
                    'sale_price' => $i % 3 === 1 ? (string)(39.99 + ($i * 20)) : '',
                    'description' => $svc['description'] ?? 'Professional service tailored to your needs.',
                    'short' => substr($svc['description'] ?? 'Professional service', 0, 60),
                    'category' => 'Services',
                    'sku' => 'SVC-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                ];
            }
            return $result;
        }

        // Absolute fallback
        return [
            ['title' => 'Premium Package', 'price' => '99.99', 'sale_price' => '', 'description' => 'Our premium offering with all features included.', 'short' => 'Full-featured package', 'category' => 'Services', 'sku' => 'PKG-001'],
            ['title' => 'Standard Package', 'price' => '49.99', 'sale_price' => '', 'description' => 'Great value package for everyday needs.', 'short' => 'Essential features', 'category' => 'Services', 'sku' => 'PKG-002'],
            ['title' => 'Starter Package', 'price' => '29.99', 'sale_price' => '19.99', 'description' => 'Perfect for getting started with our services.', 'short' => 'Get started today', 'category' => 'Services', 'sku' => 'PKG-003'],
            ['title' => 'Custom Solution', 'price' => '199.99', 'sale_price' => '', 'description' => 'Tailored solution designed specifically for your needs.', 'short' => 'Bespoke solution', 'category' => 'Services', 'sku' => 'PKG-004'],
        ];
    }

    private function createWooProduct(\PDO $pdo, array $product, string $siteUrl, string $imageUrl = ''): void
    {
        $now = date('Y-m-d H:i:s');
        $title = $product['title'] ?? 'Product';
        $slug = Str::slug($title);
        $desc = $product['description'] ?? '';
        $short = $product['short'] ?? '';
        $price = $product['price'] ?? '0';
        $salePrice = $product['sale_price'] ?? '';
        $sku = $product['sku'] ?? 'PROD-' . Str::random(4);
        $category = $product['category'] ?? 'General';

        // Insert product post
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, ?, 'publish', 'open', 'closed', ?, 'product', ?, ?, '', '', '')");
        $stmt->execute([$now, $now, $desc, $title, $short, $slug, $now, $now]);
        $productId = (int) $pdo->lastInsertId();

        if (!$productId) return;

        // Product meta
        $this->setPostMeta($pdo, $productId, '_regular_price', $price);
        $this->setPostMeta($pdo, $productId, '_price', $salePrice ?: $price);
        if ($salePrice) {
            $this->setPostMeta($pdo, $productId, '_sale_price', $salePrice);
        }
        $this->setPostMeta($pdo, $productId, '_sku', $sku);
        $this->setPostMeta($pdo, $productId, '_stock_status', 'instock');
        $this->setPostMeta($pdo, $productId, '_manage_stock', 'no');
        $this->setPostMeta($pdo, $productId, '_virtual', 'no');
        $this->setPostMeta($pdo, $productId, '_downloadable', 'no');
        $this->setPostMeta($pdo, $productId, '_visibility', 'visible');
        $this->setPostMeta($pdo, $productId, 'total_sales', '0');

        // Attach product image as featured image
        if ($imageUrl) {
            $relPath = '';
            if (preg_match('#/wp-content/uploads/(.+)$#', $imageUrl, $m)) {
                $relPath = $m[1];
            }
            $attachId = $this->createAttachment($pdo, $imageUrl, $title, $relPath);
            if ($attachId) {
                $this->setPostMeta($pdo, $productId, '_thumbnail_id', (string) $attachId);
            }
        }

        // Assign product_type = simple (required for WooCommerce to recognize as product)
        $this->assignProductTaxonomy($pdo, $productId, 'simple', 'product_type');

        // Create product category if not exists
        $this->ensureProductCategory($pdo, $productId, $category);
    }

    private function ensureProductCategory(\PDO $pdo, int $productId, string $categoryName): void
    {
        $slug = Str::slug($categoryName);

        // Check if term exists
        $stmt = $pdo->prepare("SELECT t.term_id, tt.term_taxonomy_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = ? AND tt.taxonomy = 'product_cat' LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $termTaxId = $row['term_taxonomy_id'];
        } else {
            // Create term
            $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)")->execute([$categoryName, $slug]);
            $termId = (int) $pdo->lastInsertId();
            $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'product_cat', '', 0, 0)")->execute([$termId]);
            $termTaxId = (int) $pdo->lastInsertId();
        }

        // Link product to category
        $pdo->prepare("INSERT IGNORE INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)")->execute([$productId, $termTaxId]);

        // Update count
        $pdo->exec("UPDATE wp_term_taxonomy SET count = (SELECT COUNT(*) FROM wp_term_relationships WHERE term_taxonomy_id = {$termTaxId}) WHERE term_taxonomy_id = {$termTaxId}");
    }

    private function assignProductTaxonomy(\PDO $pdo, int $productId, string $termSlug, string $taxonomy): void
    {
        $stmt = $pdo->prepare("SELECT tt.term_taxonomy_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = ? AND tt.taxonomy = ? LIMIT 1");
        $stmt->execute([$termSlug, $taxonomy]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $pdo->prepare("INSERT IGNORE INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)")->execute([$productId, $row['term_taxonomy_id']]);
        }
    }

    // ─── Theme-Based Layout: Install Theme + Demo Data ───

    private function installThemeLayout(AbstractLayout $layout, string $sitePath, string $dbName, string $siteUrl, array $content, array $imageUrls): void
    {
        $themeSlug = $layout->themeSlug();
        $storagePath = storage_path("themes/{$themeSlug}");

        if (!File::isDirectory($storagePath)) {
            throw new \RuntimeException("Theme storage not found: {$storagePath}");
        }

        $themesDir = $sitePath . '/wp-content/themes';
        $pluginsDir = $sitePath . '/wp-content/plugins';

        // 1. Extract main theme zip to wp-content/themes/
        $themeZip = $storagePath . "/{$themeSlug}.zip";
        if (File::exists($themeZip)) {
            $this->extractZip($themeZip, $themesDir);
            $this->updateStep('building_pages', "Theme '{$themeSlug}' installed.");
        } else {
            throw new \RuntimeException("Theme zip not found: {$themeZip}");
        }

        // 2. Extract child theme if available
        $childZip = $storagePath . "/{$themeSlug}-child.zip";
        if (File::exists($childZip)) {
            $this->extractZip($childZip, $themesDir);
            Log::info("Child theme installed for {$themeSlug}");
        }

        // 2b. Add tpmeta_field shim to child theme (required for Biddut header/footer meta reading)
        $childFunctionsFile = $themesDir . "/{$themeSlug}-child/functions.php";
        if (File::exists($childFunctionsFile)) {
            $childFunctions = File::get($childFunctionsFile);
            if (strpos($childFunctions, 'tpmeta_field') === false) {
                $shim = <<<'SHIM'

/**
 * Provide tpmeta_field/tpmeta_kick functions if the TP Metabox plugin is not installed.
 * These read standard WordPress postmeta for the current post.
 */
if ( ! function_exists( 'tpmeta_field' ) ) {
    function tpmeta_field( $key ) {
        $post_id = get_the_ID();
        if ( ! $post_id ) {
            return '';
        }
        $value = get_post_meta( $post_id, $key, true );
        return $value !== '' ? $value : '';
    }
}

if ( ! function_exists( 'tpmeta_kick' ) ) {
    function tpmeta_kick() {
        return true;
    }
}
SHIM;
                File::append($childFunctionsFile, $shim);
                Log::info("tpmeta_field shim added to child theme");
            }
        }

        // 3. Extract required plugins (biddut-core, etc.)
        $pluginStorage = $storagePath . '/plugins';
        if (File::isDirectory($pluginStorage)) {
            foreach (File::files($pluginStorage) as $pluginZip) {
                if ($pluginZip->getExtension() === 'zip') {
                    $this->extractZip($pluginZip->getPathname(), $pluginsDir);
                    Log::info("Plugin installed: {$pluginZip->getFilename()}");
                }
            }
        }
        $this->updateStep('building_pages', 'Theme plugins installed.');

        // 3b. Activate theme + plugins in DB BEFORE import (WP needs them active to load)
        $pdo = $this->getPdo($dbName);
        $childThemeDir = $sitePath . "/wp-content/themes/{$themeSlug}-child";
        if (File::isDirectory($childThemeDir)) {
            $this->setOption($pdo, 'template', $themeSlug);
            $this->setOption($pdo, 'stylesheet', "{$themeSlug}-child");
        } else {
            $this->setOption($pdo, 'template', $themeSlug);
            $this->setOption($pdo, 'stylesheet', $themeSlug);
        }

        // Activate required plugins so WP loads them during import
        $pluginList = [
            'elementor/elementor.php',
            'elementor-pro/elementor-pro.php',
        ];
        $corePlugin = "{$themeSlug}-core/{$themeSlug}-core.php";
        if (File::exists($sitePath . "/wp-content/plugins/{$corePlugin}")) {
            $pluginList[] = $corePlugin;
        }
        if (File::exists($sitePath . '/wp-content/plugins/header-footer-elementor/header-footer-elementor.php')) {
            $pluginList[] = 'header-footer-elementor/header-footer-elementor.php';
        }
        $pluginList[] = 'webnewbiz-builder/webnewbiz-builder.php';
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'active_plugins'")->execute([serialize($pluginList)]);

        // Enable Elementor CPT support + container experiment before import
        $cpts = serialize(['post', 'page', 'elementor-hf', 'tp-header', 'tp-footer', 'tp-services', 'tp-portfolios', 'elementor_library']);
        $this->setOption($pdo, 'elementor_cpt_support', $cpts);
        $this->setOption($pdo, 'elementor_experiment-container', 'active');

        $this->updateStep('building_pages', 'Theme activated, importing demo content...');

        // 4. Import demo data via temporary PHP script
        $demoXml = $storagePath . '/Demo Data/contents-demo.xml';
        $customizerDat = $storagePath . '/Demo Data/customizer-data.dat';
        $widgetJson = $storagePath . '/Demo Data/widget-settings.json';

        if (File::exists($demoXml)) {
            // Copy demo files to site temporarily
            $tempDir = $sitePath . '/_demo-import';
            File::ensureDirectoryExists($tempDir);
            File::copy($demoXml, $tempDir . '/contents-demo.xml');
            if (File::exists($customizerDat)) {
                File::copy($customizerDat, $tempDir . '/customizer-data.dat');
            }
            if (File::exists($widgetJson)) {
                File::copy($widgetJson, $tempDir . '/widget-settings.json');
            }

            // Create import script
            $importScript = $this->buildDemoImportScript($themeSlug);
            File::put($sitePath . '/_import-demo.php', $importScript);

            // Execute import via HTTP
            try {
                $response = @file_get_contents($siteUrl . '/_import-demo.php', false, stream_context_create([
                    'http' => ['timeout' => 120],
                ]));

                if ($response) {
                    $data = json_decode($response, true);
                    if (!empty($data['error'])) {
                        Log::warning("Demo import warning: {$data['error']}");
                    } else {
                        $imported = $data['imported'] ?? 0;
                        $this->updateStep('building_pages', "Demo content imported ({$imported} items).");
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Demo import via HTTP failed: {$e->getMessage()}");
            }

            // Cleanup temp files
            @unlink($sitePath . '/_import-demo.php');
            File::deleteDirectory($tempDir);
        }

        // 5. Download demo images from original demo site
        $this->updateStep('building_pages', 'Downloading demo images...');
        $this->downloadDemoImages($demoXml, $sitePath);

        // 6. Apply customizer data if available
        if (File::exists($customizerDat)) {
            $this->importCustomizerData($sitePath, $siteUrl, $customizerDat);
        }

        // 7. Inject AI content + stock images into theme pages
        if (!empty($content)) {
            $this->injectContentIntoTheme($dbName, $content, $imageUrls, $sitePath, $siteUrl);
        }

        $this->updateStep('header_footer', 'Theme layout fully installed.');
    }

    private function extractZip(string $zipPath, string $destDir): void
    {
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($destDir);
            $zip->close();
        } else {
            throw new \RuntimeException("Failed to extract: {$zipPath}");
        }
    }

    /**
     * Download all attachment images from the demo XML's original site.
     */
    private function downloadDemoImages(string $xmlFile, string $sitePath): void
    {
        $xml = @simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) return;

        $ns = $xml->getNamespaces(true);
        $wp = $ns['wp'] ?? 'http://wordpress.org/export/1.2/';
        $demoBase = rtrim((string)$xml->channel->link, '/');

        $downloaded = 0;
        $ctx = stream_context_create([
            'http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0'],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            if ((string)$wpData->post_type !== 'attachment') continue;

            $attachUrl = (string)$wpData->attachment_url;
            if (empty($attachUrl)) continue;

            // Convert demo URL to local path
            $relative = str_replace($demoBase . '/', '', $attachUrl);
            if (strpos($relative, 'wp-content/uploads/') === false) continue;

            $localPath = $sitePath . '/' . $relative;
            if (file_exists($localPath)) continue;

            $localDir = dirname($localPath);
            if (!is_dir($localDir)) {
                mkdir($localDir, 0777, true);
            }

            $data = @file_get_contents($attachUrl, false, $ctx);
            if ($data !== false && strlen($data) > 100) {
                file_put_contents($localPath, $data);
                $downloaded++;
            }
        }

        Log::info("Downloaded {$downloaded} demo images for {$sitePath}");
        $this->updateStep('building_pages', "{$downloaded} demo images downloaded.");
    }

    private function buildDemoImportScript(string $themeSlug): string
    {
        return <<<'IMPORT'
<?php
/**
 * Demo content importer — runs once to import XML demo data.
 * Uses WordPress API to import posts, pages, CPTs with all postmeta (Elementor data).
 */
@set_time_limit(300);
@ini_set('memory_limit', '512M');

define('ABSPATH', __DIR__ . '/');
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost';

require_once ABSPATH . 'wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

header('Content-Type: application/json');

$demoDir = __DIR__ . '/_demo-import';
$xmlFile = $demoDir . '/contents-demo.xml';

if (!file_exists($xmlFile)) {
    echo json_encode(['error' => 'Demo XML not found']);
    exit;
}

$xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);
if ($xml === false) {
    echo json_encode(['error' => 'Failed to parse XML']);
    exit;
}

$ns = $xml->getNamespaces(true);
$wp_ns = $ns['wp'] ?? 'http://wordpress.org/export/1.2/';
$content_ns = $ns['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
$excerpt_ns = $ns['excerpt'] ?? 'http://wordpress.org/export/1.2/excerpt/';

$imported = 0;
$errors = [];
$idMap = []; // old_id => new_id mapping

// Supported post types for import
$allowedTypes = ['page', 'post', 'attachment', 'tp-header', 'tp-footer', 'tp-services', 'tp-portfolios', 'elementor_library', 'nav_menu_item', 'wpcf7_contact_form', 'product'];

// First pass: import terms/categories including nav_menu terms
$termIdMap = []; // old_term_id => new_term_id for nav menus
foreach ($xml->channel->children($wp_ns) as $name => $termData) {
    if ($name === 'category') {
        $catName = (string)$termData->cat_name;
        $catSlug = (string)$termData->category_nicename;
        if (!term_exists($catSlug, 'category')) {
            wp_insert_term($catName, 'category', ['slug' => $catSlug]);
        }
    }
    if ($name === 'tag') {
        $tagName = (string)$termData->tag_name;
        $tagSlug = (string)$termData->tag_slug;
        if (!term_exists($tagSlug, 'post_tag')) {
            wp_insert_term($tagName, 'post_tag', ['slug' => $tagSlug]);
        }
    }
    if ($name === 'term') {
        $termTax = (string)$termData->term_taxonomy;
        $termSlug = (string)$termData->term_slug;
        $termName = (string)$termData->term_name;
        $oldTermId = (int)$termData->term_id;
        if ($termTax === 'nav_menu') {
            $existing = term_exists($termSlug, 'nav_menu');
            if ($existing) {
                $termIdMap[$oldTermId] = (int)$existing['term_id'];
            } else {
                $result = wp_insert_term($termName, 'nav_menu', ['slug' => $termSlug]);
                if (!is_wp_error($result)) {
                    $termIdMap[$oldTermId] = $result['term_id'];
                }
            }
        } elseif ($termTax === 'product_cat') {
            if (!term_exists($termSlug, 'product_cat')) {
                wp_insert_term($termName, 'product_cat', ['slug' => $termSlug]);
            }
        }
    }
}

// Second pass: import posts/pages with postmeta
global $wpdb;
$pdo_import = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo_import->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($xml->channel->item as $item) {
    $wpData = $item->children($wp_ns);
    $postType = (string)$wpData->post_type;
    $status = (string)$wpData->status;
    $oldId = (int)$wpData->post_id;

    if (!in_array($postType, $allowedTypes)) {
        continue;
    }

    $title = (string)$item->title;
    $slug = (string)$wpData->post_name;
    $contentData = $item->children($content_ns);
    $postContent = (string)$contentData->encoded;
    $excerptData = $item->children($excerpt_ns);
    $postExcerpt = (string)$excerptData->encoded;
    $postParent = (int)$wpData->post_parent;
    $menuOrder = (int)$wpData->menu_order;

    // Check if post already exists
    $existing = get_page_by_path($slug, OBJECT, $postType);

    // Handle attachment posts specially
    if ($postType === 'attachment') {
        $attachUrl = (string)$wpData->attachment_url;
        $siteUrl = home_url();
        $demoBase = (string)$xml->channel->link;
        // Remap attachment URL from demo to local
        $localUrl = str_replace($demoBase, $siteUrl, $attachUrl);
        $mimeType = '';
        $ext = strtolower(pathinfo($attachUrl, PATHINFO_EXTENSION));
        $mimeMap = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','svg'=>'image/svg+xml','webp'=>'image/webp','pdf'=>'application/pdf'];
        $mimeType = $mimeMap[$ext] ?? 'image/jpeg';

        $postData = [
            'post_title' => $title,
            'post_name' => $slug,
            'post_content' => $postContent,
            'post_excerpt' => $postExcerpt,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => $mimeType,
            'post_author' => 1,
            'guid' => $localUrl,
        ];
    } else {
        $postData = [
            'post_title' => $title,
            'post_name' => $slug,
            'post_content' => $postContent,
            'post_excerpt' => $postExcerpt,
            'post_status' => $status ?: 'publish',
            'post_type' => $postType,
            'post_author' => 1,
            'menu_order' => $menuOrder,
        ];
    }

    if ($existing) {
        $postData['ID'] = $existing->ID;
        $newId = wp_update_post($postData, true);
    } else {
        $newId = wp_insert_post($postData, true);
    }

    if (is_wp_error($newId)) {
        $errors[] = "Failed: {$title} ({$postType}): " . $newId->get_error_message();
        continue;
    }

    $idMap[$oldId] = $newId;

    // Import postmeta (includes _elementor_data, _elementor_edit_mode, etc.)
    // Use raw PDO to avoid WordPress's auto-unslashing ($wpdb strips backslashes)
    // which corrupts Elementor JSON data containing escaped quotes
    foreach ($wpData->postmeta as $meta) {
        $metaKey = (string)$meta->meta_key;
        $metaValue = (string)$meta->meta_value;

        // Skip auto-generated meta
        if (in_array($metaKey, ['_edit_lock', '_edit_last', '_wp_old_slug'])) {
            continue;
        }

        // Delete existing then insert raw — PDO prepared statements preserve backslashes
        $pdo_import->prepare("DELETE FROM {$wpdb->postmeta} WHERE post_id = ? AND meta_key = ?")->execute([$newId, $metaKey]);
        $pdo_import->prepare("INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (?, ?, ?)")->execute([$newId, $metaKey, $metaValue]);
    }

    $imported++;
}

// Third pass: fix parent references using id map
foreach ($xml->channel->item as $item) {
    $wpData = $item->children($wp_ns);
    $oldId = (int)$wpData->post_id;
    $oldParent = (int)$wpData->post_parent;

    if ($oldParent > 0 && isset($idMap[$oldId]) && isset($idMap[$oldParent])) {
        wp_update_post([
            'ID' => $idMap[$oldId],
            'post_parent' => $idMap[$oldParent],
        ]);
    }
}

// 3b: Assign nav_menu_items to their menus
foreach ($xml->channel->item as $item) {
    $wpData = $item->children($wp_ns);
    if ((string)$wpData->post_type !== 'nav_menu_item') continue;

    $oldId = (int)$wpData->post_id;
    if (!isset($idMap[$oldId])) continue;
    $newPostId = $idMap[$oldId];

    // Find menu slug from <category domain="nav_menu">
    $menuSlug = null;
    foreach ($item->category as $cat) {
        if ((string)$cat['domain'] === 'nav_menu') {
            $menuSlug = (string)$cat['nicename'];
            break;
        }
    }
    if ($menuSlug) {
        $menuTerm = get_term_by('slug', $menuSlug, 'nav_menu');
        if ($menuTerm) {
            wp_set_object_terms($newPostId, (int)$menuTerm->term_id, 'nav_menu');
        }
    }
}

// Set main menu location
$mainMenu = get_term_by('slug', 'main-menu', 'nav_menu');
if ($mainMenu) {
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $mainMenu->term_id;
    $locations['main-menu'] = $mainMenu->term_id;
    set_theme_mod('nav_menu_locations', $locations);
}

// Fourth pass: remap postmeta values that reference old post IDs
// Biddut theme stores header/footer template IDs in postmeta
$remapKeys = ['biddut_header_templates', 'biddut_footer_template', '_elementor_template_id'];
$remapped = 0;

// Reuse existing $wpdb and $pdo_import connection
$pdo_remap = $pdo_import;

foreach ($remapKeys as $rKey) {
    $rows = $wpdb->get_results(
        $wpdb->prepare("SELECT meta_id, post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value != ''", $rKey)
    );
    foreach ($rows as $row) {
        $oldVal = (int)$row->meta_value;
        if ($oldVal > 0 && isset($idMap[$oldVal])) {
            $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = ? WHERE meta_id = ?")->execute([(string)$idMap[$oldVal], $row->meta_id]);
            $remapped++;
        }
    }
}

// Also remap nav_menu_item references (_menu_item_object_id)
$navRows = $wpdb->get_results("SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_menu_item_object_id' AND meta_value != ''");
foreach ($navRows as $row) {
    $oldVal = (int)$row->meta_value;
    if ($oldVal > 0 && isset($idMap[$oldVal])) {
        $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = ? WHERE meta_id = ?")->execute([(string)$idMap[$oldVal], $row->meta_id]);
        $remapped++;
    }
}

// Remap Elementor data internal template IDs (e.g. global widget references)
// Also remap _elementor_data references to template IDs within JSON
foreach ($idMap as $oldTemplateId => $newTemplateId) {
    $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, '\"template_id\":\"$oldTemplateId\"', '\"template_id\":\"$newTemplateId\"') WHERE meta_key = '_elementor_data' AND meta_value LIKE '%\"template_id\":\"$oldTemplateId\"%'")->execute();
    $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, '\"template_id\":$oldTemplateId', '\"template_id\":$newTemplateId') WHERE meta_key = '_elementor_data' AND meta_value LIKE '%\"template_id\":$oldTemplateId%'")->execute();
}

// Fifth pass: replace demo site URLs with actual site URL
$siteUrl = home_url();
$demoBaseUrl = (string)$xml->channel->link; // e.g. https://wp.aqlova.com/biddut
if ($demoBaseUrl && $siteUrl && $demoBaseUrl !== $siteUrl) {
    // Parse demo host+path for broad matching (handles URLs with/without protocol)
    $demoParsed = parse_url($demoBaseUrl);
    $demoHostPath = $demoParsed['host'] . ($demoParsed['path'] ?? '');
    $siteParsed = parse_url($siteUrl);
    $siteHostPath = $siteParsed['host'] . ($siteParsed['path'] ?? '');

    // Replace escaped URLs in Elementor JSON (\/ format) — must use chr() for exact byte matching
    $bs = chr(92); // backslash
    $fs = chr(47); // forward slash
    $demoEscaped = str_replace('/', $bs . $fs, $demoHostPath);
    $siteEscaped = str_replace('/', $bs . $fs, $siteHostPath);
    $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, ?, ?) WHERE meta_key = '_elementor_data'")->execute([$demoEscaped, $siteEscaped]);

    // Replace plain URLs in other meta and post content
    $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, ?, ?) WHERE meta_key != '_elementor_data'")->execute([$demoBaseUrl, $siteUrl]);
    $pdo_remap->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, ?, ?) WHERE 1=1")->execute([$demoBaseUrl, $siteUrl]);

    // Also fix protocol in Elementor data (https -> http if needed)
    if ($siteParsed['scheme'] !== $demoParsed['scheme']) {
        $pdo_remap->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, ?, ?) WHERE meta_key = '_elementor_data'")->execute([
            str_replace('/', $bs . $fs, $demoParsed['scheme'] . '://' . $siteHostPath),
            str_replace('/', $bs . $fs, $siteUrl)
        ]);
    }
}

// Set front page if there's a page called "Home" or "Front Page"
$homePage = get_page_by_path('home') ?: get_page_by_path('front-page');
if ($homePage) {
    update_option('page_on_front', $homePage->ID);
    update_option('show_on_front', 'page');
}

echo json_encode([
    'ok' => true,
    'imported' => $imported,
    'errors' => $errors,
    'id_map_count' => count($idMap),
    'remapped' => $remapped,
]);
IMPORT;
    }

    private function importCustomizerData(string $sitePath, string $siteUrl, string $customizerDat): void
    {
        // Copy customizer data to site
        $datContent = File::get($customizerDat);

        $script = <<<CUSTSCRIPT
<?php
define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';
header('Content-Type: application/json');

\$datFile = __DIR__ . '/_demo-import/customizer-data.dat';
if (!file_exists(\$datFile)) {
    // Try from storage directly
    \$datContent = base64_decode('{$this->base64Content($datContent)}');
} else {
    \$datContent = file_get_contents(\$datFile);
}

\$data = @unserialize(\$datContent);
if (!is_array(\$data)) {
    // Try as JSON
    \$data = @json_decode(\$datContent, true);
}

\$count = 0;
if (is_array(\$data)) {
    foreach (\$data as \$key => \$value) {
        set_theme_mod(\$key, \$value);
        \$count++;
    }
}

echo json_encode(['ok' => true, 'mods' => \$count]);
CUSTSCRIPT;

        File::put($sitePath . '/_import-customizer.php', $script);

        try {
            @file_get_contents($siteUrl . '/_import-customizer.php', false, stream_context_create([
                'http' => ['timeout' => 30],
            ]));
        } catch (\Exception $e) {
            Log::warning("Customizer import failed: {$e->getMessage()}");
        }

        @unlink($sitePath . '/_import-customizer.php');
    }

    private function base64Content(string $content): string
    {
        return base64_encode($content);
    }

    /**
     * Inject AI content + stock images into Biddut theme Elementor data.
     * Walks the JSON tree and replaces text in known widget settings + swaps images.
     */
    private function injectContentIntoTheme(string $dbName, array $content, array $imageUrls, string $sitePath, string $siteUrl): void
    {
        $pdo = $this->getPdo($dbName);
        $businessName = $this->website->name;
        $pages = $content['pages'] ?? [];

        // Update site title + tagline
        $this->setOption($pdo, 'blogname', $businessName);
        $tagline = $content['tagline'] ?? $this->website->business_type ?? '';
        $this->setOption($pdo, 'blogdescription', $tagline);

        // Map page slugs to possible DB page names
        $pageMap = [
            'home'     => ['home', 'front-page', 'homepage'],
            'about'    => ['about', 'about-us'],
            'services' => ['services', 'service', 'our-services'],
            'contact'  => ['contact', 'contact-us'],
        ];

        foreach ($pageMap as $type => $slugs) {
            $pageContent = $pages[$type] ?? [];
            if (empty($pageContent)) continue;

            // Find the page
            $placeholders = implode(',', array_fill(0, count($slugs), '?'));
            $stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_type = 'page' AND post_name IN ({$placeholders}) LIMIT 1");
            $stmt->execute($slugs);
            $pageId = $stmt->fetchColumn();
            if (!$pageId) continue;

            // Get Elementor data
            $metaStmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
            $metaStmt->execute([$pageId]);
            $json = $metaStmt->fetchColumn();
            if (!$json) continue;

            $elements = json_decode($json, true);
            if (!is_array($elements)) continue;

            // Inject content into widgets
            $elements = $this->injectIntoWidgets($elements, $pageContent, $businessName, $imageUrls, $siteUrl, $type);

            $newJson = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($newJson) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$newJson, $pageId]);
                $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key IN ('_elementor_element_cache', '_elementor_css')")->execute([$pageId]);
            }
        }

        // Inject into header (business name, contact info)
        $this->injectIntoHeader($pdo, $content, $businessName, $imageUrls, $siteUrl);

        // Inject into footer (business name, contact info)
        $this->injectIntoFooter($pdo, $content, $businessName);

        // Clean menu to 5 pages
        $this->cleanNavMenu($pdo);

        Log::info("AI content injected into theme for {$businessName}");
    }

    /**
     * Walk Elementor elements and inject AI content into Biddut custom widgets.
     * Uses stdClass context (passed by handle) so counters persist across recursive calls.
     */
    private function injectIntoWidgets(array $elements, array $pageContent, string $businessName, array $imageUrls, string $siteUrl, string $pageType, ?\stdClass $ctx = null): array
    {
        // Build context on first (top-level) call only
        if ($ctx === null) {
            $sections = $pageContent['sections'] ?? [];
            $sectionIndex = [];
            foreach ($sections as $s) {
                $t = $s['type'] ?? '';
                $sectionIndex[$t][] = $s;
            }
            $ctx = (object) [
                'heroTitle'    => $pageContent['hero_title'] ?? '',
                'heroSubtitle' => $pageContent['hero_subtitle'] ?? $pageContent['hero_text'] ?? '',
                'heroCta'      => $pageContent['hero_cta'] ?? 'Get Started',
                'sections'     => $sectionIndex,
                'headingCount' => 0,
                'featureIdx'   => 0,
                'imageCount'   => 0,
            ];
        }

        $sectionIndex = $ctx->sections;

        foreach ($elements as &$el) {
            $widget = $el['widgetType'] ?? '';

            if ($widget === 'tp-slider' || $widget === 'tp-hero') {
                if (!empty($el['settings']['slider_list']) && is_array($el['settings']['slider_list'])) {
                    $heroKeys = ['hero', 'hero2', 'hero3'];
                    $businessType = $this->website->business_type ?? 'Professional Services';
                    foreach ($el['settings']['slider_list'] as $si => &$slide) {
                        // Text content: first slide gets AI content, others also get customized
                        if ($si === 0) {
                            if ($ctx->heroTitle) $slide['tp_slider_title'] = $ctx->heroTitle;
                            if ($ctx->heroSubtitle) $slide['tp_slider_description'] = $ctx->heroSubtitle;
                        } else {
                            // Replace slide title with hero content variant or brand-replace
                            if ($ctx->heroTitle && isset($slide['tp_slider_title'])) {
                                $slide['tp_slider_title'] = $ctx->heroTitle;
                            }
                            if (isset($slide['tp_slider_description'])) {
                                $slide['tp_slider_description'] = $ctx->heroSubtitle ?: str_replace(['Biddut', 'BIDDUT', 'biddut'], [$businessName, strtoupper($businessName), strtolower($businessName)], $slide['tp_slider_description']);
                            }
                        }
                        // Button text for ALL slides
                        if (isset($slide['tp_btn_btn_text'])) {
                            $slide['tp_btn_btn_text'] = $ctx->heroCta;
                        }
                        // Replace sub_title — handle demo-specific text like "ELECTRIC SERVICE COMPANY"
                        if (isset($slide['tp_slider_sub_title'])) {
                            $sub = $slide['tp_slider_sub_title'];
                            $sub = str_replace(
                                ['Biddut', 'BIDDUT', 'biddut', 'ELECTRIC SERVICE COMPANY', 'Electric Service Company', 'Electricians', 'electricians'],
                                [$businessName, strtoupper($businessName), strtolower($businessName), strtoupper($businessType), ucwords($businessType), ucwords($businessType), strtolower($businessType)],
                                $sub
                            );
                            $slide['tp_slider_sub_title'] = $sub;
                        }
                        // Image for ALL slides — cycle through hero, hero2, hero3
                        $heroKey = $heroKeys[$si % count($heroKeys)];
                        if (!empty($imageUrls[$heroKey]) && isset($slide['tp_slider_image']['url'])) {
                            $slide['tp_slider_image']['url'] = $imageUrls[$heroKey];
                            $slide['tp_slider_image']['id'] = '';
                        }
                    }
                    unset($slide);
                }
            }

            if ($widget === 'tp-heading') {
                $ctx->headingCount++;
                $sectionTitle = '';
                $sectionDesc = '';
                // Map heading positions to AI content sections
                if ($ctx->headingCount === 1 && !empty($sectionIndex['about_preview'])) {
                    $sectionTitle = $sectionIndex['about_preview'][0]['title'] ?? '';
                    $sectionDesc = $sectionIndex['about_preview'][0]['content'] ?? '';
                } elseif ($ctx->headingCount === 2 && !empty($sectionIndex['features'])) {
                    $sectionTitle = $sectionIndex['features'][0]['title'] ?? '';
                    // Features section can have its own subtitle
                } elseif ($ctx->headingCount === 3 && !empty($sectionIndex['stats'])) {
                    $sectionTitle = $sectionIndex['stats'][0]['title'] ?? '';
                } elseif ($ctx->headingCount === 4 && !empty($sectionIndex['cta'])) {
                    $sectionTitle = $sectionIndex['cta'][0]['title'] ?? '';
                } elseif ($ctx->headingCount === 5 && !empty($sectionIndex['testimonials'])) {
                    $sectionTitle = $sectionIndex['testimonials'][0]['title'] ?? '';
                }
                // Headings beyond 5 keep their original title (or get brand replacement below)

                if ($sectionTitle && isset($el['settings']['tp_section_title'])) {
                    $el['settings']['tp_section_title'] = $sectionTitle;
                }
                // Replace brand name in sub_title
                if (isset($el['settings']['tp_section_sub_title'])) {
                    $el['settings']['tp_section_sub_title'] = $businessName;
                }
                // Only set description if we have specific content for this heading
                if ($sectionDesc && isset($el['settings']['tp_section_description'])) {
                    $el['settings']['tp_section_description'] = $sectionDesc;
                } elseif (isset($el['settings']['tp_section_description'])) {
                    // Replace brand references in existing description
                    $el['settings']['tp_section_description'] = str_replace(
                        ['Biddut', 'BIDDUT'],
                        [$businessName, strtoupper($businessName)],
                        $el['settings']['tp_section_description']
                    );
                }
            }

            if ($widget === 'tp-icon-box') {
                $features = $sectionIndex['features'][0]['items'] ?? [];
                if (!empty($features[$ctx->featureIdx])) {
                    $f = $features[$ctx->featureIdx];
                    if (isset($el['settings']['tp_icon_box_title'])) {
                        $el['settings']['tp_icon_box_title'] = $f['title'] ?? $el['settings']['tp_icon_box_title'];
                    }
                    if (isset($el['settings']['tp_icon_box_desc'])) {
                        $el['settings']['tp_icon_box_desc'] = $f['description'] ?? $el['settings']['tp_icon_box_desc'];
                    }
                    $ctx->featureIdx++;
                }
            }

            if ($widget === 'tp-services-box' || $widget === 'tp-services') {
                $serviceItems = $sectionIndex['features'][0]['items'] ?? [];
                $featureImgKeys = ['feature1', 'feature2', 'feature3', 'services', 'gallery1'];
                // Biddut uses tp_service_list (not tp_services_list)
                $svcListKey = !empty($el['settings']['tp_service_list']) ? 'tp_service_list' : 'tp_services_list';
                if (!empty($el['settings'][$svcListKey])) {
                    foreach ($el['settings'][$svcListKey] as $i => &$svc) {
                        if (!empty($serviceItems[$i])) {
                            $svc['tp_service_title'] = $serviceItems[$i]['title'] ?? $svc['tp_service_title'] ?? '';
                            $svc['tp_service_des'] = $serviceItems[$i]['description'] ?? $svc['tp_service_des'] ?? '';
                        }
                        // Inject images — Biddut uses tp_box_image (not tp_service_image)
                        $fKey = $featureImgKeys[$i % count($featureImgKeys)];
                        if (!empty($imageUrls[$fKey])) {
                            if (isset($svc['tp_box_image']['url'])) {
                                $svc['tp_box_image']['url'] = $imageUrls[$fKey];
                                $svc['tp_box_image']['id'] = '';
                            } elseif (isset($svc['tp_service_image']['url'])) {
                                $svc['tp_service_image']['url'] = $imageUrls[$fKey];
                                $svc['tp_service_image']['id'] = '';
                            }
                        }
                    }
                    unset($svc);
                }
            }

            if ($widget === 'tp-cta') {
                $ctaSection = $sectionIndex['cta'][0] ?? [];
                if (!empty($ctaSection)) {
                    if (isset($el['settings']['tp_cta_title'])) {
                        $el['settings']['tp_cta_title'] = $ctaSection['title'] ?? $el['settings']['tp_cta_title'];
                    }
                    if (isset($el['settings']['tp_cta_description'])) {
                        $el['settings']['tp_cta_description'] = $ctaSection['subtitle'] ?? $ctaSection['content'] ?? $el['settings']['tp_cta_description'];
                    }
                    // Replace brand in CTA sub_title
                    if (isset($el['settings']['tp_cta_sub_title'])) {
                        $el['settings']['tp_cta_sub_title'] = str_replace(
                            ['Biddut', 'BIDDUT', 'biddut'],
                            [$businessName, strtoupper($businessName), strtolower($businessName)],
                            $el['settings']['tp_cta_sub_title']
                        );
                    }
                    // CTA button text
                    $ctaBtnText = $ctaSection['button_text'] ?? $ctx->heroCta;
                    if (isset($el['settings']['tp_cta_btn_text'])) {
                        $el['settings']['tp_cta_btn_text'] = $ctaBtnText;
                    }
                }
            }

            if ($widget === 'tp-faq2') {
                $faqItems = $sectionIndex['faq'][0]['items'] ?? [];
                if (!empty($el['settings']['accordions']) && !empty($faqItems)) {
                    foreach ($el['settings']['accordions'] as $i => &$faq) {
                        if (isset($faqItems[$i])) {
                            $faq['accordion_title'] = $faqItems[$i]['question'] ?? $faq['accordion_title'];
                            $faq['accordion_description'] = $faqItems[$i]['answer'] ?? $faq['accordion_description'];
                        }
                    }
                    unset($faq);
                }
            }

            if ($widget === 'tp-testimonial-2' || $widget === 'tp-testimonial') {
                $testItems = $sectionIndex['testimonials'][0]['items'] ?? [];
                $portraitKeys = ['portrait1', 'portrait2', 'portrait3', 'portrait4'];
                if (!empty($el['settings']['reviews_list'])) {
                    foreach ($el['settings']['reviews_list'] as $i => &$rev) {
                        if (!empty($testItems[$i])) {
                            $rev['reviewer_name'] = $testItems[$i]['name'] ?? $rev['reviewer_name'];
                            $rev['reviewer_title'] = $testItems[$i]['role'] ?? $rev['reviewer_title'];
                            $rev['review_content'] = $testItems[$i]['content'] ?? $rev['review_content'];
                        }
                        // Inject portrait photos for reviewers
                        $pKey = $portraitKeys[$i % count($portraitKeys)];
                        if (!empty($imageUrls[$pKey]) && isset($rev['reviewer_image']['url'])) {
                            $rev['reviewer_image']['url'] = $imageUrls[$pKey];
                            $rev['reviewer_image']['id'] = '';
                        }
                    }
                    unset($rev);
                }
            }

            if ($widget === 'tp-team') {
                $teamItems = $sectionIndex['team'][0]['items'] ?? [];
                // Fallback team data if AI didn't generate team section
                if (empty($teamItems)) {
                    $teamItems = [
                        ['name' => 'Sarah Johnson', 'role' => 'Lead Specialist'],
                        ['name' => 'Michael Chen', 'role' => 'Senior Technician'],
                        ['name' => 'Emily Rodriguez', 'role' => 'Operations Manager'],
                        ['name' => 'David Park', 'role' => 'Quality Inspector'],
                    ];
                }
                $portraitKeys = ['portrait1', 'portrait2', 'portrait3', 'portrait4'];
                // Biddut uses 'teams' repeater (not 'team_list')
                $teamListKey = !empty($el['settings']['teams']) ? 'teams' : 'team_list';
                if (!empty($el['settings'][$teamListKey])) {
                    foreach ($el['settings'][$teamListKey] as $i => &$member) {
                        if (!empty($teamItems[$i])) {
                            // Try both field name conventions
                            if (isset($member['tp_team_name'])) {
                                $member['tp_team_name'] = $teamItems[$i]['name'] ?? $member['tp_team_name'];
                            } elseif (isset($member['title'])) {
                                $member['title'] = $teamItems[$i]['name'] ?? $member['title'];
                            }
                            if (isset($member['tp_team_designation'])) {
                                $member['tp_team_designation'] = $teamItems[$i]['role'] ?? $member['tp_team_designation'];
                            } elseif (isset($member['designation'])) {
                                $member['designation'] = $teamItems[$i]['role'] ?? $member['designation'];
                            }
                        }
                        // Inject portrait photos — Biddut uses 'image' (not 'tp_team_image')
                        $pKey = $portraitKeys[$i % count($portraitKeys)];
                        if (!empty($imageUrls[$pKey])) {
                            if (isset($member['image']['url'])) {
                                $member['image']['url'] = $imageUrls[$pKey];
                                $member['image']['id'] = '';
                            } elseif (isset($member['tp_team_image']['url'])) {
                                $member['tp_team_image']['url'] = $imageUrls[$pKey];
                                $member['tp_team_image']['id'] = '';
                            }
                        }
                    }
                    unset($member);
                }
            }

            if ($widget === 'tp-button') {
                if (isset($el['settings']['tp_btn_text'])) {
                    $el['settings']['tp_btn_text'] = $ctx->heroCta ?: $el['settings']['tp_btn_text'];
                }
            }

            // Standard Elementor heading/text widgets — brand replacement
            if ($widget === 'heading' && isset($el['settings']['title'])) {
                $el['settings']['title'] = str_replace(
                    ['Biddut', 'BIDDUT'],
                    [$businessName, strtoupper($businessName)],
                    $el['settings']['title']
                );
            }
            if ($widget === 'text-editor' && isset($el['settings']['editor'])) {
                $el['settings']['editor'] = str_replace(
                    ['Biddut', 'BIDDUT'],
                    [$businessName, strtoupper($businessName)],
                    $el['settings']['editor']
                );
            }

            // tp-about-image: has tp_about_image_one, tp_about_image_two (real content), tp_about_image_shape (skip)
            if ($widget === 'tp-about-image') {
                if (!empty($imageUrls['about']) && isset($el['settings']['tp_about_image_one']['url'])) {
                    $el['settings']['tp_about_image_one']['url'] = $imageUrls['about'];
                    $el['settings']['tp_about_image_one']['id'] = '';
                }
                if (!empty($imageUrls['about2']) && isset($el['settings']['tp_about_image_two']['url'])) {
                    $el['settings']['tp_about_image_two']['url'] = $imageUrls['about2'];
                    $el['settings']['tp_about_image_two']['id'] = '';
                }
                if (!empty($imageUrls['hero']) && isset($el['settings']['tp_about_image_yr_bg']['url'])) {
                    $el['settings']['tp_about_image_yr_bg']['url'] = $imageUrls['hero'];
                    $el['settings']['tp_about_image_yr_bg']['id'] = '';
                }
            }

            // tp-image-hover: uses tp_image field (NOT tp_image_hover_image)
            if ($widget === 'tp-image-hover') {
                $imgKeys = ['gallery1', 'gallery2', 'gallery3', 'feature1', 'about', 'services'];
                $imgKey = $imgKeys[$ctx->imageCount % count($imgKeys)] ?? 'gallery1';
                if (!empty($imageUrls[$imgKey]) && isset($el['settings']['tp_image']['url'])) {
                    $el['settings']['tp_image']['url'] = $imageUrls[$imgKey];
                    $el['settings']['tp_image']['id'] = '';
                    $ctx->imageCount++;
                }
            }

            // Regular image widgets (Elementor standard + tp-image-animated)
            if ($widget === 'image' || $widget === 'tp-image-animated') {
                $imgKeys = match($pageType) {
                    'home' => ['hero', 'about', 'services', 'gallery1', 'gallery2', 'about2', 'feature1', 'feature2', 'gallery3', 'gallery4'],
                    'about' => ['about', 'about2', 'team', 'hero', 'gallery1', 'gallery2'],
                    'services' => ['services', 'feature1', 'feature2', 'feature3', 'hero', 'gallery1'],
                    default => ['hero', 'about', 'services', 'gallery1'],
                };
                $imgKey = $imgKeys[$ctx->imageCount % count($imgKeys)] ?? 'hero';

                // Standard image widget: settings.image.url
                if (!empty($imageUrls[$imgKey]) && isset($el['settings']['image']['url'])) {
                    $currentUrl = $el['settings']['image']['url'];
                    // Don't replace small shape/decoration images (but allow bg-*.jpg content images)
                    if (!str_contains($currentUrl, 'shape') && !str_contains($currentUrl, 'icon') && !str_contains(basename($currentUrl), 'placeholder')) {
                        $el['settings']['image']['url'] = $imageUrls[$imgKey];
                        $el['settings']['image']['id'] = '';
                        $ctx->imageCount++;
                    }
                }
                // tp-image-animated: skip shapes (most are decorative)
                if ($widget === 'tp-image-animated' && isset($el['settings']['tp_image_animated_icon_image']['url'])) {
                    $currentUrl = $el['settings']['tp_image_animated_icon_image']['url'];
                    if (!str_contains($currentUrl, 'shape') && !str_contains($currentUrl, 'icon')) {
                        $el['settings']['tp_image_animated_icon_image']['url'] = $imageUrls[$imgKey];
                        $el['settings']['tp_image_animated_icon_image']['id'] = '';
                        $ctx->imageCount++;
                    }
                }
            }

            // Replace background images on containers/sections that have demo URLs
            if (isset($el['settings']['background_image']['url']) && !empty($el['settings']['background_image']['url'])) {
                $bgUrl = $el['settings']['background_image']['url'];
                // Only replace if it points to the demo site or is an old uploaded image
                if (str_contains($bgUrl, 'aqlova.com') || str_contains($bgUrl, 'biddut') || str_contains($bgUrl, 'wp-content/uploads')) {
                    $bgKeys = ['hero', 'hero2', 'about', 'services', 'gallery1', 'gallery2'];
                    $bgKey = $bgKeys[$ctx->imageCount % count($bgKeys)] ?? 'hero';
                    if (!empty($imageUrls[$bgKey])) {
                        $el['settings']['background_image']['url'] = $imageUrls[$bgKey];
                        $el['settings']['background_image']['id'] = '';
                        $ctx->imageCount++;
                    }
                }
            }

            // Recurse into children — pass ctx so counters persist across the entire tree
            if (!empty($el['elements'])) {
                $el['elements'] = $this->injectIntoWidgets($el['elements'], $pageContent, $businessName, $imageUrls, $siteUrl, $pageType, $ctx);
            }
        }

        return $elements;
    }

    /**
     * Inject business info into header widget.
     */
    private function injectIntoHeader(\PDO $pdo, array $content, string $businessName, array $imageUrls, string $siteUrl): void
    {
        $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type = 'tp-header' AND post_status = 'publish' ORDER BY ID ASC LIMIT 1");
        $headerId = $stmt->fetchColumn();
        if (!$headerId) return;

        $data = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
        $data->execute([$headerId]);
        $json = $data->fetchColumn();
        if (!$json) return;

        $elements = json_decode($json, true);
        if (!is_array($elements)) return;

        $contactPage = $content['pages']['contact'] ?? [];
        $phone = $contactPage['phone'] ?? '';
        $email = $contactPage['email'] ?? '';
        $address = $contactPage['address'] ?? '';

        $elements = $this->walkAndModify($elements, function(&$el) use ($businessName, $phone, $email, $address) {
            if (($el['widgetType'] ?? '') !== 'tp-header1') return;
            $s = &$el['settings'];
            // Disable top bar
            $s['tp_header_top_switch'] = '';
            // Update contact info
            if ($phone && isset($s['tp_ofc_phone'])) $s['tp_ofc_phone'] = $phone;
            if ($email && isset($s['tp_ofc_email'])) $s['tp_ofc_email'] = $email;
            if ($address && isset($s['tp_address'])) $s['tp_address'] = $address;
            if ($email && isset($s['tp_topbar_email'])) $s['tp_topbar_email'] = $email;
            // Replace brand name in side content
            if (isset($s['tp_side_content'])) {
                $s['tp_side_content'] = str_replace(['Biddut', 'BIDDUT'], [$businessName, strtoupper($businessName)], $s['tp_side_content']);
            }
        });

        $newJson = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($newJson) {
            $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$newJson, $headerId]);
            $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key IN ('_elementor_element_cache', '_elementor_css')")->execute([$headerId]);
        }
    }

    /**
     * Inject business info into footer widgets.
     */
    private function injectIntoFooter(\PDO $pdo, array $content, string $businessName): void
    {
        $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type = 'tp-footer' AND post_status = 'publish'");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
            $data->execute([$row['ID']]);
            $json = $data->fetchColumn();
            if (!$json) continue;

            $elements = json_decode($json, true);
            if (!is_array($elements)) continue;

            $elements = $this->replaceBrandInElements($elements, $businessName);

            $newJson = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($newJson) {
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$newJson, $row['ID']]);
                $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key IN ('_elementor_element_cache', '_elementor_css')")->execute([$row['ID']]);
            }
        }
    }

    /**
     * Clean nav menu to show main pages + Shop for ecommerce sites.
     */
    private function cleanNavMenu(\PDO $pdo, bool $hasWooCommerce = false): void
    {
        // First: delete ALL nav_menu_items from ALL menus (clean slate)
        $allMenuTtIds = $pdo->query("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy = 'nav_menu'")->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($allMenuTtIds as $ttId) {
            $items = $pdo->query("SELECT object_id FROM wp_term_relationships WHERE term_taxonomy_id = {$ttId}")->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($items as $itemId) {
                $pdo->exec("DELETE FROM wp_posts WHERE ID = {$itemId} AND post_type = 'nav_menu_item'");
                $pdo->exec("DELETE FROM wp_postmeta WHERE post_id = {$itemId}");
                $pdo->exec("DELETE FROM wp_term_relationships WHERE object_id = {$itemId} AND term_taxonomy_id = {$ttId}");
            }
            $pdo->exec("UPDATE wp_term_taxonomy SET count = 0 WHERE term_taxonomy_id = {$ttId}");
        }

        // Delete all non-main menus (keep only main-menu)
        $pdo->exec("DELETE tt, t FROM wp_term_taxonomy tt JOIN wp_terms t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'nav_menu' AND t.slug != 'main-menu'");

        // Find main menu term
        $menuTermId = $pdo->query("SELECT t.term_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'nav_menu' AND t.slug = 'main-menu'")->fetchColumn();
        if (!$menuTermId) return;

        $ttId = $pdo->query("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = {$menuTermId} AND taxonomy = 'nav_menu'")->fetchColumn();
        if (!$ttId) return;

        // Build page list — add Shop for ecommerce sites
        $pageSlugs = [
            ['Home', 'home'],
            ['Shop', 'shop'],        // only added if WooCommerce active
            ['About', 'about'],
            ['Services', 'services'],
            ['Contact', 'contact'],
        ];
        if (!$hasWooCommerce) {
            $pageSlugs = array_values(array_filter($pageSlugs, fn($p) => $p[1] !== 'shop'));
        }

        $order = 1;
        foreach ($pageSlugs as [$label, $slug]) {
            $pageId = $pdo->query("SELECT ID FROM wp_posts WHERE post_type = 'page' AND post_name = '{$slug}' AND post_status = 'publish' LIMIT 1")->fetchColumn();
            if (!$pageId) continue;

            // Create nav_menu_item post
            $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, menu_order, guid) VALUES (1, NOW(), NOW(), '', ?, '', 'publish', 'closed', 'closed', ?, 'nav_menu_item', ?, '')")->execute([$label, $slug . '-menu', $order]);
            $menuItemId = $pdo->lastInsertId();

            // Set menu item meta
            $metas = [
                '_menu_item_type' => 'post_type',
                '_menu_item_menu_item_parent' => '0',
                '_menu_item_object_id' => (string)$pageId,
                '_menu_item_object' => 'page',
                '_menu_item_target' => '',
                '_menu_item_classes' => 'a:1:{i:0;s:0:"";}',
                '_menu_item_xfn' => '',
                '_menu_item_url' => '',
            ];
            foreach ($metas as $k => $v) {
                $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)")->execute([$menuItemId, $k, $v]);
            }

            // Assign to menu
            $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id) VALUES (?, ?)")->execute([$menuItemId, $ttId]);
            $order++;
        }

        // Update menu count
        $itemCount = $order - 1;
        $pdo->exec("UPDATE wp_term_taxonomy SET count = {$itemCount} WHERE term_taxonomy_id = {$ttId}");
    }

    /**
     * Generic helper to walk elements and apply a callback.
     */
    private function walkAndModify(array $elements, callable $callback): array
    {
        foreach ($elements as &$el) {
            $callback($el);
            if (!empty($el['elements'])) {
                $el['elements'] = $this->walkAndModify($el['elements'], $callback);
            }
        }
        return $elements;
    }

    /**
     * Replace demo placeholder content with AI-generated content in theme-based layouts.
     * @deprecated Use injectContentIntoTheme() instead
     */
    private function replaceThemeContent(string $dbName, array $content, string $siteUrl): void
    {
        $pdo = $this->getPdo($dbName);
        $businessName = $this->website->name;

        // Update site title
        $this->setOption($pdo, 'blogname', $businessName);
        $tagline = $content['tagline'] ?? $this->website->business_type ?? '';
        $this->setOption($pdo, 'blogdescription', $tagline);

        // Update page titles and content with AI text
        $pageMap = [
            'home' => ['Home', 'Front Page', 'Homepage'],
            'about' => ['About', 'About Us'],
            'services' => ['Services', 'Our Services', 'Service'],
            'contact' => ['Contact', 'Contact Us'],
        ];

        $pagesContent = $content['pages'] ?? [];

        foreach ($pageMap as $type => $possibleTitles) {
            $pageContent = $pagesContent[$type] ?? [];
            if (empty($pageContent)) continue;

            // Find the page by possible titles
            $placeholders = str_repeat('?,', count($possibleTitles) - 1) . '?';
            $stmt = $pdo->prepare("SELECT ID, post_content FROM wp_posts WHERE post_type = 'page' AND post_title IN ({$placeholders}) LIMIT 1");
            $stmt->execute($possibleTitles);
            $page = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$page) continue;

            // Update Elementor data if present (replace text in JSON)
            $metaStmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
            $metaStmt->execute([$page['ID']]);
            $elementorData = $metaStmt->fetchColumn();

            if ($elementorData) {
                $elementorData = $this->replaceElementorText($elementorData, $pageContent, $businessName);
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$elementorData, $page['ID']]);
                // Clear element cache
                $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_element_cache'")->execute([$page['ID']]);
                $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_css'")->execute([$page['ID']]);
            }
        }

        // Also update headers/footers with business name
        $hfStmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type IN ('tp-header', 'tp-footer', 'elementor-hf') AND post_status = 'publish'");
        while ($hf = $hfStmt->fetch(\PDO::FETCH_ASSOC)) {
            $metaStmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
            $metaStmt->execute([$hf['ID']]);
            $data = $metaStmt->fetchColumn();
            if ($data) {
                // Replace demo brand name with actual business name (only in text fields, not URLs)
                $data = $this->replaceBrandInElementorJson($data, $businessName);
                $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$data, $hf['ID']]);
                $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_element_cache'")->execute([$hf['ID']]);
            }
        }

        Log::info("Theme content replaced with AI content for {$businessName}");
    }

    /**
     * Replace text in Elementor JSON data with AI-generated content.
     */
    private function replaceElementorText(string $json, array $pageContent, string $businessName): string
    {
        // Replace demo brand references (only in text fields, not URLs)
        $json = $this->replaceBrandInElementorJson($json, $businessName);

        // Replace hero title/subtitle if available
        if (!empty($pageContent['hero_title'])) {
            // This is a best-effort text replacement in the Elementor JSON
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $decoded = $this->walkElementorTree($decoded, $pageContent, $businessName);
                $json = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        return $json;
    }

    /**
     * Walk Elementor element tree and replace text content where appropriate.
     */
    private function walkElementorTree(array $elements, array $pageContent, string $businessName): array
    {
        $replacements = [
            'hero_title' => $pageContent['hero_title'] ?? null,
            'hero_subtitle' => $pageContent['hero_subtitle'] ?? $pageContent['hero_text'] ?? null,
            'cta_title' => $pageContent['cta_title'] ?? null,
            'cta_text' => $pageContent['cta_text'] ?? null,
        ];

        $headingIndex = 0;

        foreach ($elements as &$el) {
            if (isset($el['widgetType'])) {
                // Replace heading widgets
                if ($el['widgetType'] === 'heading' && isset($el['settings']['title'])) {
                    if ($headingIndex === 0 && !empty($replacements['hero_title'])) {
                        $el['settings']['title'] = $replacements['hero_title'];
                    }
                    $headingIndex++;
                }

                // Replace text editor widgets
                if ($el['widgetType'] === 'text-editor' && isset($el['settings']['editor'])) {
                    if ($headingIndex <= 2 && !empty($replacements['hero_subtitle'])) {
                        $el['settings']['editor'] = '<p>' . $replacements['hero_subtitle'] . '</p>';
                        $replacements['hero_subtitle'] = null; // Use only once
                    }
                }
            }

            // Recurse into children
            if (!empty($el['elements'])) {
                $el['elements'] = $this->walkElementorTree($el['elements'], $pageContent, $businessName);
            }
        }

        return $elements;
    }

    /**
     * Replace brand name in Elementor JSON only in text fields (not URLs).
     * Parses JSON, replaces in known text settings, re-encodes.
     */
    private function replaceBrandInElementorJson(string $json, string $businessName): string
    {
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            // Fallback: if JSON decode fails, do safe replacement avoiding URLs
            // Only replace "Biddut" (capitalized) which is unlikely in URLs
            return str_replace('Biddut', $businessName, $json);
        }

        $decoded = $this->replaceBrandInElements($decoded, $businessName);
        $result = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $result ?: $json;
    }

    private function replaceBrandInElements(array $elements, string $businessName): array
    {
        // Text setting keys where brand name should be replaced
        $textKeys = ['title', 'editor', 'description_text', 'text', 'button_text',
                     'tp_btn_text', 'tp_heading_title', 'tp_heading_description',
                     'tp_text', 'tp_title', 'tp_subtitle', 'copyright_text',
                     'tp_footer_title', 'tp_footer_description', 'label', 'placeholder',
                     'tp_section_title', 'tp_section_sub_title', 'tp_section_description',
                     'tp_cta_sub_title', 'tp_cta_title', 'tp_cta_description',
                     'tp_side_content'];

        $businessType = $this->website->business_type ?? 'Professional Services';

        foreach ($elements as &$el) {
            if (isset($el['settings']) && is_array($el['settings'])) {
                foreach ($textKeys as $key) {
                    if (isset($el['settings'][$key]) && is_string($el['settings'][$key])) {
                        $el['settings'][$key] = str_replace(
                            ['Biddut', 'BIDDUT', 'ELECTRIC SERVICE COMPANY', 'Electric Service Company', 'Electricians', 'electricians', 'Electrical', 'electrical'],
                            [$businessName, strtoupper($businessName), strtoupper($businessType), ucwords($businessType), ucwords($businessType), strtolower($businessType), ucwords($businessType), strtolower($businessType)],
                            $el['settings'][$key]
                        );
                    }
                }
            }
            if (!empty($el['elements'])) {
                $el['elements'] = $this->replaceBrandInElements($el['elements'], $businessName);
            }
        }
        return $elements;
    }

    /**
     * Configure site for theme-based layout (activates theme + required plugins).
     */
    private function configureSiteThemeBased(AbstractLayout $layout, string $dbName, array $content, string $sitePath): void
    {
        $pdo = $this->getPdo($dbName);
        $slug = $this->website->slug;
        $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $siteUrl = "http://localhost/{$slug}";
        $themeSlug = $layout->themeSlug();

        // Set site title and tagline
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogname'")->execute([$this->website->name]);
        $tagline = $content['tagline'] ?? $this->website->business_type ?? '';
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogdescription'")->execute([$tagline]);

        // Activate the theme (use child theme if available)
        $childThemeDir = $sitePath . "/wp-content/themes/{$themeSlug}-child";
        if (File::isDirectory($childThemeDir)) {
            $this->setOption($pdo, 'template', $themeSlug);
            $this->setOption($pdo, 'stylesheet', "{$themeSlug}-child");
        } else {
            $this->setOption($pdo, 'template', $themeSlug);
            $this->setOption($pdo, 'stylesheet', $themeSlug);
        }

        // Build plugin list — always need elementor
        $pluginList = [
            'elementor/elementor.php',
            'elementor-pro/elementor-pro.php',
        ];

        // Add theme-specific plugin (biddut-core)
        $corePlugin = "{$themeSlug}-core/{$themeSlug}-core.php";
        if (File::exists($sitePath . "/wp-content/plugins/{$corePlugin}")) {
            $pluginList[] = $corePlugin;
        }

        // Add HFE if present
        if (File::exists($sitePath . '/wp-content/plugins/header-footer-elementor/header-footer-elementor.php')) {
            $pluginList[] = 'header-footer-elementor/header-footer-elementor.php';
        }

        // Skip WooCommerce for theme-based layouts — template DB doesn't have WC tables
        // WooCommerce crashes on init if tables don't exist

        // Always include WebNewBiz Builder
        $pluginList[] = 'webnewbiz-builder/webnewbiz-builder.php';

        // Copy WebNewBiz Builder plugin if not already present
        $wnbPluginDir = $sitePath . '/wp-content/plugins/webnewbiz-builder';
        if (!File::isDirectory($wnbPluginDir)) {
            $wnbSource = base_path('storage/plugins/webnewbiz-builder');
            if (File::isDirectory($wnbSource)) {
                File::copyDirectory($wnbSource, $wnbPluginDir);
            }
        }

        $plugins = serialize($pluginList);
        $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'active_plugins'")->execute([$plugins]);

        // Elementor CPT support — include theme CPTs
        $cpts = serialize(['post', 'page', 'elementor-hf', 'tp-header', 'tp-footer', 'tp-services', 'tp-portfolios', 'elementor_library']);
        $this->setOption($pdo, 'elementor_cpt_support', $cpts);

        // Enable Elementor experiments
        $this->setOption($pdo, 'elementor_experiment-container', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_optimized_css_loading', 'active');
        $this->setOption($pdo, 'elementor_experiment-container_grid', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_swiper_latest', 'active');
        $this->setOption($pdo, 'elementor_experiment-e_element_cache', 'active');

        // Create Elementor default kit
        $this->ensureElementorKit($pdo, $siteUrl);

        // Set permalink structure
        $this->setOption($pdo, 'permalink_structure', '/%postname%/');

        // Remove default post
        $pdo->exec("DELETE FROM wp_posts WHERE post_type = 'post' AND post_title = 'Hello world!'");
        $pdo->exec("DELETE FROM wp_posts WHERE post_type = 'page' AND post_title = 'Sample Page'");

        // Set front page
        $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish' AND (post_name LIKE '%home%' OR post_name LIKE '%front%') LIMIT 1");
        $homePage = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($homePage) {
            $this->setOption($pdo, 'page_on_front', (string)$homePage['ID']);
            $this->setOption($pdo, 'show_on_front', 'page');
            $this->website->update(['home_page_id' => $homePage['ID']]);
        }

        // WooCommerce setup if applicable
        if (in_array('woocommerce/woocommerce.php', $pluginList)) {
            $this->setupWooCommerce($pdo, $dbName, $siteUrl, $content);
        }

        // Auto-login script
        $this->createAutoLoginScript($sitePath);

        // Store WebNewBiz platform connection options (after auto-login so token exists)
        $this->website->refresh();
        $connToken = $this->website->wp_auto_login_token ? decrypt($this->website->wp_auto_login_token) : '';
        $this->setOption($pdo, 'webnewbiz_connection_token', $connToken);
        $this->setOption($pdo, 'webnewbiz_platform_url', config('app.frontend_url', 'http://localhost:4200') . '/dashboard');
        $this->setOption($pdo, 'webnewbiz_connected_at', now()->toDateTimeString());
        $this->setOption($pdo, 'wnb_claude_api_key', env('ANTHROPIC_API_KEY', ''));

        // AI Copilot — platform token + website ID for copilot widget
        $platformApiUrl2 = config('app.url', 'http://localhost:8000');
        $platformToken2 = $this->website->user->createToken('copilot-' . $this->website->slug)->plainTextToken ?? '';
        $this->setOption($pdo, 'webnewbiz_platform_token', $platformToken2);
        $this->setOption($pdo, 'webnewbiz_website_id', (string) $this->website->id);
        $this->setOption($pdo, 'webnewbiz_platform_api_url', $platformApiUrl2);

        // Clear ALL Elementor caches
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_element_cache'");
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_css'");
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_page_assets'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%cache%'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE 'elementor_css%'");

        $cssPath = $sitePath . '/wp-content/uploads/elementor/css';
        if (File::isDirectory($cssPath)) {
            File::deleteDirectory($cssPath);
        }

        $this->updateStep('plugins', 'Theme-based site configured.');
    }

    // ─── Step 8: Regenerate Elementor CSS ───

    private function regenerateElementorCss(string $sitePath, string $siteUrl): void
    {
        // Create a temporary PHP script that loads WordPress + Elementor and regenerates CSS
        $script = <<<'REGEN'
<?php
define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';

header('Content-Type: application/json');

if (!class_exists('\Elementor\Plugin')) {
    echo json_encode(['error' => 'Elementor not loaded']);
    exit;
}

\Elementor\Plugin::$instance->files_manager->clear_cache();

$posts = get_posts([
    'post_type' => ['page', 'post', 'elementor-hf', 'elementor_library', 'tp-header', 'tp-footer', 'tp-services', 'tp-portfolios'],
    'posts_per_page' => -1,
    'meta_key' => '_elementor_data',
    'post_status' => 'publish',
]);

$results = [];
foreach ($posts as $post) {
    try {
        $css_file = \Elementor\Core\Files\CSS\Post::create($post->ID);
        $css_file->update();
        $results[] = $post->post_title;
    } catch (\Exception $e) {
        // Skip failures
    }
}

echo json_encode(['ok' => true, 'pages' => $results]);
REGEN;

        File::put($sitePath . '/_regen-css.php', $script);

        try {
            $response = @file_get_contents($siteUrl . '/_regen-css.php', false, stream_context_create([
                'http' => ['timeout' => 30],
            ]));

            if ($response) {
                $data = json_decode($response, true);
                $count = count($data['pages'] ?? []);
                $this->updateStep('complete', "CSS generated for {$count} pages.");
            } else {
                $this->updateStep('complete', 'CSS generation skipped (will auto-generate on first visit).');
            }
        } catch (\Exception $e) {
            Log::warning("CSS regeneration failed: {$e->getMessage()}");
        }

        // Clean up temp script
        @unlink($sitePath . '/_regen-css.php');
    }

    // ─── Helpers ───

    private function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO(
            "mysql:host=" . config('database.connections.mysql.host', '127.0.0.1') . ";dbname={$dbName}",
            config('database.connections.mysql.username', 'root'),
            config('database.connections.mysql.password', '')
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * Generate default section structure for a page when no customize structure exists.
     * Each page type gets unique sections so no two pages look the same.
     */
    private function generateDefaultSections(string $pageType, string $title): array
    {
        return match ($pageType) {
            'home' => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'features', 'label' => 'What We Offer'],
                ['type' => 'about_preview', 'label' => 'Our Story'],
                ['type' => 'stats', 'label' => 'By The Numbers'],
                ['type' => 'testimonials', 'label' => 'What Our Clients Say'],
                ['type' => 'cta', 'label' => 'Get Started Today'],
            ],
            'about' => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'about_preview', 'label' => 'Who We Are'],
                ['type' => 'team', 'label' => 'Meet Our Team'],
                ['type' => 'process', 'label' => 'How We Work'],
            ],
            'services' => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'features', 'label' => 'Our Services'],
                ['type' => 'pricing', 'label' => 'Pricing & Packages'],
                ['type' => 'faq', 'label' => 'Frequently Asked Questions'],
                ['type' => 'cta', 'label' => 'Ready To Start?'],
            ],
            'portfolio' => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'gallery', 'label' => 'Our Best Work'],
                ['type' => 'about_preview', 'label' => 'Behind The Scenes'],
                ['type' => 'cta', 'label' => 'Start Your Project'],
            ],
            'contact' => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'contact_form', 'label' => 'Send Us A Message'],
                ['type' => 'faq', 'label' => 'Before You Reach Out'],
            ],
            default => [
                ['type' => 'hero', 'label' => $title],
                ['type' => 'features', 'label' => 'Highlights'],
                ['type' => 'about_preview', 'label' => 'Learn More'],
                ['type' => 'cta', 'label' => 'Get In Touch'],
            ],
        };
    }

    /**
     * Guess page type from slug and structure sections for layout builder.
     */
    private function guessPageType(string $slug, ?array $structurePage): string
    {
        // Check section types in structure to determine best page type
        if ($structurePage && !empty($structurePage['sections'])) {
            $types = array_column($structurePage['sections'], 'type');
            if (in_array('contact_form', $types)) return 'contact';
            if (in_array('gallery', $types)) return 'portfolio';
            if (in_array('pricing', $types)) return 'services';
            if (in_array('team', $types)) return 'about';
            if (in_array('faq', $types)) return 'services';
        }

        // Keyword matching on slug
        $slugLower = strtolower($slug);
        if (str_contains($slugLower, 'contact') || str_contains($slugLower, 'connect') || str_contains($slugLower, 'consult')) return 'contact';
        if (str_contains($slugLower, 'about') || str_contains($slugLower, 'story') || str_contains($slugLower, 'team') || str_contains($slugLower, 'legacy')) return 'about';
        if (str_contains($slugLower, 'service') || str_contains($slugLower, 'package') || str_contains($slugLower, 'pricing') || str_contains($slugLower, 'invest')) return 'services';
        if (str_contains($slugLower, 'portfolio') || str_contains($slugLower, 'gallery') || str_contains($slugLower, 'work') || str_contains($slugLower, 'collection')) return 'portfolio';
        if (str_contains($slugLower, 'blog') || str_contains($slugLower, 'news') || str_contains($slugLower, 'insight')) return 'about'; // blog uses about layout

        return 'services'; // fallback to services (generic content page)
    }

    private function setPostMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    /** Detect installed Elementor version from plugin file or version.txt */
    private function getElementorVersion(): string
    {
        $templateDir = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs') . '/wp-template';

        // Check version file created by create_template.php
        $versionFile = "{$templateDir}/elementor-version.txt";
        if (file_exists($versionFile)) {
            $v = trim(file_get_contents($versionFile));
            if ($v) return $v;
        }

        // Parse from plugin header
        $sitePath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs') . '/' . $this->website->slug;
        $mainFile = "{$sitePath}/wp-content/plugins/elementor/elementor.php";
        if (file_exists($mainFile)) {
            $content = file_get_contents($mainFile);
            if (preg_match('/Version:\s*([0-9.]+)/i', $content, $m)) {
                return $m[1];
            }
        }

        return '3.35.6'; // fallback
    }

    private function ensureElementorKit(\PDO $pdo, string $siteUrl): void
    {
        // Check if kit already exists
        $stmt = $pdo->query("SELECT p.ID FROM wp_posts p JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_type = 'elementor_library' AND pm.meta_key = '_elementor_template_type' AND pm.meta_value = 'kit' LIMIT 1");
        $existing = $stmt->fetchColumn();

        if ($existing) {
            $this->setOption($pdo, 'elementor_active_kit', (string) $existing);
            // Update kit settings with typography
            $this->setPostMeta($pdo, (int)$existing, '_elementor_page_settings', serialize($this->getKitSettings()));
            return;
        }

        // Create the default kit
        $now = date('Y-m-d H:i:s');
        $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', 'Default Kit', '', 'publish', 'closed', 'closed', 'default-kit', 'elementor_library', ?, ?, ?, '', '', '')")
            ->execute([$now, $now, $now, $now, rtrim($siteUrl, '/') . '/?p=0']);
        $kitId = (int) $pdo->lastInsertId();

        if ($kitId) {
            $pdo->exec("UPDATE wp_posts SET guid = " . $pdo->quote(rtrim($siteUrl, '/') . "/?p={$kitId}") . " WHERE ID = {$kitId}");
            $this->setPostMeta($pdo, $kitId, '_elementor_template_type', 'kit');
            $this->setPostMeta($pdo, $kitId, '_elementor_edit_mode', 'builder');
            $this->setPostMeta($pdo, $kitId, '_elementor_version', $this->getElementorVersion());
            $this->setPostMeta($pdo, $kitId, '_elementor_data', '[]');
            $this->setPostMeta($pdo, $kitId, '_elementor_page_settings', serialize($this->getKitSettings()));
            $this->setOption($pdo, 'elementor_active_kit', (string) $kitId);
            Log::info("Created Elementor default kit: ID={$kitId}");
        }
    }

    private function getKitSettings(): array
    {
        return [
            // Global typography — applies to entire site
            'system_typography' => [
                [
                    '_id' => 'primary',
                    'title' => 'Primary',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '600',
                ],
                [
                    '_id' => 'secondary',
                    'title' => 'Secondary',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '400',
                ],
                [
                    '_id' => 'text',
                    'title' => 'Text',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '400',
                    'typography_font_size' => ['size' => 16, 'unit' => 'px'],
                    'typography_line_height' => ['size' => 1.6, 'unit' => 'em'],
                ],
                [
                    '_id' => 'accent',
                    'title' => 'Accent',
                    'typography_typography' => 'custom',
                    'typography_font_family' => 'Inter',
                    'typography_font_weight' => '500',
                ],
            ],
            // Body typography
            'body_typography_typography' => 'custom',
            'body_typography_font_family' => 'Inter',
            'body_typography_font_weight' => '400',
            'body_typography_font_size' => ['size' => 16, 'unit' => 'px'],
            'body_typography_line_height' => ['size' => 1.7, 'unit' => 'em'],
            'body_color' => '#374151',
            // Headings
            'h1_typography_typography' => 'custom',
            'h1_typography_font_family' => 'Inter',
            'h1_typography_font_weight' => '800',
            'h1_typography_font_size' => ['size' => 48, 'unit' => 'px'],
            'h1_typography_line_height' => ['size' => 1.15, 'unit' => 'em'],
            'h1_color' => '#111827',
            'h2_typography_typography' => 'custom',
            'h2_typography_font_family' => 'Inter',
            'h2_typography_font_weight' => '700',
            'h2_typography_font_size' => ['size' => 36, 'unit' => 'px'],
            'h2_typography_line_height' => ['size' => 1.2, 'unit' => 'em'],
            'h2_color' => '#111827',
            'h3_typography_typography' => 'custom',
            'h3_typography_font_family' => 'Inter',
            'h3_typography_font_weight' => '700',
            'h3_typography_font_size' => ['size' => 28, 'unit' => 'px'],
            'h3_typography_line_height' => ['size' => 1.3, 'unit' => 'em'],
            'h3_color' => '#111827',
            'h4_typography_typography' => 'custom',
            'h4_typography_font_family' => 'Inter',
            'h4_typography_font_weight' => '600',
            'h4_typography_font_size' => ['size' => 22, 'unit' => 'px'],
            'h4_color' => '#1f2937',
            'h5_typography_typography' => 'custom',
            'h5_typography_font_family' => 'Inter',
            'h5_typography_font_weight' => '600',
            'h5_typography_font_size' => ['size' => 18, 'unit' => 'px'],
            'h5_color' => '#1f2937',
            'h6_typography_typography' => 'custom',
            'h6_typography_font_family' => 'Inter',
            'h6_typography_font_weight' => '600',
            'h6_typography_font_size' => ['size' => 16, 'unit' => 'px'],
            'h6_color' => '#374151',
            // Link colors
            'link_normal_color' => '#2563EB',
            'link_hover_color' => '#1d4ed8',
            // Button styling
            'button_typography_typography' => 'custom',
            'button_typography_font_family' => 'Inter',
            'button_typography_font_weight' => '600',
            'button_typography_font_size' => ['size' => 15, 'unit' => 'px'],
            'button_text_color' => '#ffffff',
            'button_background_color' => '#111827',
            'button_border_radius' => ['top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10', 'unit' => 'px', 'isLinked' => true],
            'button_padding' => ['top' => '14', 'right' => '28', 'bottom' => '14', 'left' => '28', 'unit' => 'px', 'isLinked' => false],
            'button_hover_background_color' => '#374151',
            // Container width
            'container_width' => ['size' => 1200, 'unit' => 'px'],
            // Viewport (responsive breakpoints)
            'viewport_md' => 768,
            'viewport_lg' => 1025,
        ];
    }

    private function setOption(\PDO $pdo, string $name, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes') ON DUPLICATE KEY UPDATE option_value = ?");
        $stmt->execute([$name, $value, $value]);
    }

    private function getOption(\PDO $pdo, string $name): ?string
    {
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = ?");
        $stmt->execute([$name]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : null;
    }
}
