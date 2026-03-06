<?php

namespace App\Services;

use App\Models\Website;
use App\Services\Layouts\AbstractLayout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Orchestrates building master WordPress sites using the premium Layout system.
 * Each layout is a complete design system with CSS, JS, header, footer, and 5 pages.
 */
class MasterThemeBuilder
{
    private array $layouts;

    public function __construct(
        private WordPressService $wpService,
    ) {
        $this->layouts = config('layouts') ?? [];
    }

    /**
     * Get all layout slugs.
     */
    public function allSlugs(): array
    {
        return array_keys($this->layouts);
    }

    /**
     * Get layout config by slug.
     */
    public function getConfig(string $slug): ?array
    {
        return $this->layouts[$slug] ?? null;
    }

    /**
     * Get all layouts with metadata.
     */
    public function allLayouts(): array
    {
        $result = [];
        foreach ($this->layouts as $slug => $cfg) {
            $layout = AbstractLayout::resolve($slug);
            if ($layout) {
                $result[$slug] = [
                    'slug' => $slug,
                    'name' => $layout->name(),
                    'description' => $layout->description(),
                    'style' => $cfg['style'] ?? 'light',
                    'primary' => $cfg['primary'] ?? '#333',
                    'accent' => $cfg['accent'] ?? '#666',
                    'preview_bg' => $cfg['preview_bg'] ?? '#FFF',
                    'best_for' => $layout->bestFor(),
                    'is_dark' => $layout->isDark(),
                ];
            }
        }
        return $result;
    }

    /**
     * Check if a master site already exists.
     */
    public function masterExists(string $slug): bool
    {
        $dbName = 'wp_master_' . Str::slug($slug, '_');
        try {
            $pdo = new \PDO(
                "mysql:host=" . config('database.connections.mysql.host', '127.0.0.1'),
                config('database.connections.mysql.username', 'root'),
                config('database.connections.mysql.password', ''),
            );
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($dbName));
            return (bool) $stmt->fetchColumn();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Build a single master site from a layout.
     */
    public function build(string $slug): array
    {
        $cfg = $this->getConfig($slug);
        if (!$cfg) {
            throw new \InvalidArgumentException("Unknown layout slug: {$slug}");
        }

        $layout = AbstractLayout::resolve($slug);
        if (!$layout) {
            throw new \InvalidArgumentException("Cannot resolve layout class for: {$slug}");
        }

        $subdomain = "master-{$slug}";
        $dbName = 'wp_master_' . Str::slug($slug, '_');
        $siteUrl = "http://localhost/{$subdomain}";
        $sitePath = config('webnewbiz.htdocs_path', 'C:/xampp/htdocs') . "/{$subdomain}";

        Log::info("Building master layout: {$slug} -> {$siteUrl}");

        // Step 1: Create a temporary Website model for WordPressService
        $website = new Website();
        $website->subdomain = $subdomain;
        $website->name = $layout->name() . ' Theme';
        $website->ai_style = $cfg['style'] ?? 'light';
        $website->ai_theme = 'flavor-starter';
        $website->ai_business_type = implode(', ', $layout->bestFor());
        $website->wp_db_name = $dbName;
        $website->wp_admin_password = Str::random(16);
        $website->wp_admin_email = 'admin@webnewbiz.com';

        // Step 2: Create WordPress site (DB, files, config, tables)
        $result = $this->wpService->createSite($website);

        $pdo = $this->wpService->getPdo($dbName);
        $now = date('Y-m-d H:i:s');

        // Default content for master preview
        $siteName = $layout->name() . ' Demo';
        $defaultContent = $this->buildDefaultContent($siteName, $layout);
        $defaultImages = $this->buildDefaultImages();

        // Step 3: Build pages
        $pages = ['home', 'about', 'services', 'portfolio', 'contact'];
        $pageIds = [];
        $pageLabels = [];

        foreach ($pages as $pageSlug) {
            $pageTitle = ucfirst($pageSlug);
            if ($pageSlug === 'portfolio') $pageTitle = 'Portfolio';

            $elementorData = $layout->buildPage($pageSlug, $defaultContent, $defaultImages);
            $json = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $stmt = $pdo->prepare("INSERT INTO wp_posts
                (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
                 post_status, comment_status, ping_status, post_name, post_type,
                 post_modified, post_modified_gmt, guid, to_ping, pinged, post_content_filtered)
                VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, ?, '', '', '')");
            $guid = $siteUrl . '/?p=0';
            $stmt->execute([$now, $now, $pageTitle, $pageSlug, $now, $now, $guid]);
            $postId = (int) $pdo->lastInsertId();

            $pdo->exec("UPDATE wp_posts SET guid = " . $pdo->quote("{$siteUrl}/?p={$postId}") . " WHERE ID = {$postId}");

            $this->setMeta($pdo, $postId, '_elementor_data', $json);
            $this->setMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
            $this->setMeta($pdo, $postId, '_elementor_version', '3.35.6');
            $this->setMeta($pdo, $postId, '_elementor_template_type', 'wp-post');
            $this->setMeta($pdo, $postId, '_wp_page_template', 'elementor_header_footer');

            $pageIds[$pageSlug] = $postId;
            $pageLabels[$pageSlug] = $pageTitle;

            Log::info("Created page: {$pageTitle} (ID: {$postId})");
        }

        // Step 4: Build HFE header
        $headerData = $layout->buildHeader($siteName, $pageLabels);
        $this->wpService->createHfeTemplate($dbName, 'type_header', 'Master Header', $headerData, $siteUrl);

        // Step 5: Build HFE footer
        $footerData = $layout->buildFooter($siteName, $pageLabels, [
            'email' => 'hello@example.com',
            'phone' => '(555) 123-4567',
            'address' => '123 Business Street, City, State',
            'tagline' => 'Premium Solutions',
            'footer_text' => 'Delivering excellence with every project.',
        ]);
        $this->wpService->createHfeTemplate($dbName, 'type_footer', 'Master Footer', $footerData, $siteUrl);

        // Step 6: Create navigation menu
        $pageList = [];
        foreach ($pageIds as $s => $id) {
            $pageList[] = ['title' => ucfirst($s), 'id' => $id, 'slug' => $s];
        }
        $this->createNavMenu($pdo, $siteUrl, $pageList);

        // Step 7: Set homepage, title, tagline, permalinks
        $homePageId = $pageIds['home'] ?? reset($pageIds);
        $this->setOption($pdo, 'show_on_front', 'page');
        $this->setOption($pdo, 'page_on_front', (string) $homePageId);
        $this->setOption($pdo, 'blogname', $siteName);
        $this->setOption($pdo, 'blogdescription', $layout->description());
        $this->setOption($pdo, 'permalink_structure', '/%postname%/');

        // Step 8: Clear Elementor CSS cache
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key='_elementor_css'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '_transient_elementor%'");

        // Step 9: Update Elementor kit with global colors/typography
        $this->updateElementorKit($pdo, $layout);

        Log::info("Master layout built successfully: {$slug}");

        return [
            'slug' => $slug,
            'url' => $siteUrl,
            'db' => $dbName,
            'pages' => count($pageIds),
            'layout' => $layout->name(),
        ];
    }

    /**
     * Destroy a master site (drop DB and remove files).
     */
    public function destroy(string $slug): void
    {
        $dbName = 'wp_master_' . Str::slug($slug, '_');
        $subdomain = "master-{$slug}";
        $sitePath = config('webnewbiz.htdocs_path', 'C:/xampp/htdocs') . "/{$subdomain}";

        try {
            $pdo = new \PDO(
                "mysql:host=" . config('database.connections.mysql.host', '127.0.0.1'),
                config('database.connections.mysql.username', 'root'),
                config('database.connections.mysql.password', ''),
            );
            $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
            Log::info("Dropped database: {$dbName}");
        } catch (\Exception $e) {
            Log::warning("Failed to drop DB {$dbName}: " . $e->getMessage());
        }

        if (is_dir($sitePath)) {
            if (PHP_OS_FAMILY === 'Windows') {
                exec("rmdir /s /q " . escapeshellarg($sitePath));
            } else {
                exec("rm -rf " . escapeshellarg($sitePath));
            }
            Log::info("Removed site files: {$sitePath}");
        }
    }

    // ─── Content/Image Defaults ─────────────────────────────

    private function buildDefaultContent(string $siteName, AbstractLayout $layout): array
    {
        return [
            'site_name' => $siteName,
            'hero_title' => 'Premium Quality. Delivered.',
            'hero_subtitle' => 'We deliver exceptional results with cutting-edge solutions tailored to your unique needs and goals.',
            'hero_cta' => 'Get Started',
            'hero_cta_url' => '/contact/',
            'hero_ghost_cta' => 'Learn More',
            'hero_eyebrow' => 'Welcome to ' . $siteName,
            'about_title' => 'Who We Are.',
            'about_eyebrow' => 'About Us',
            'about_text' => 'We are a team of dedicated professionals committed to delivering excellence in everything we do.',
            'about_text2' => 'With years of experience and a passion for innovation, we help businesses achieve their goals and exceed expectations.',
            'services_title' => 'What We Offer.',
            'services_eyebrow' => 'Our Services',
            'services_subtitle' => 'Comprehensive solutions tailored to your needs.',
            'services' => [
                ['icon' => '⚡', 'title' => 'Strategy & Planning', 'desc' => 'We develop comprehensive strategies tailored to your unique business goals and market position.'],
                ['icon' => '🎯', 'title' => 'Design & Development', 'desc' => 'Creating beautiful, functional solutions that engage your audience and drive measurable results.'],
                ['icon' => '📈', 'title' => 'Growth & Marketing', 'desc' => 'Data-driven marketing campaigns that increase visibility, engagement, and revenue consistently.'],
            ],
            'benefits' => [
                ['icon' => '🏆', 'title' => 'Award Winning', 'desc' => 'Recognized for excellence in our industry.'],
                ['icon' => '⚡', 'title' => 'Fast Delivery', 'desc' => 'Quick turnaround without compromising quality.'],
                ['icon' => '🤝', 'title' => 'Dedicated Support', 'desc' => '24/7 customer support for your satisfaction.'],
                ['icon' => '💡', 'title' => 'Innovation First', 'desc' => 'Cutting-edge solutions and best practices.'],
            ],
            'testimonials' => [
                ['quote' => 'Working with this team transformed our business completely. The results exceeded all our expectations.', 'name' => 'Sarah Mitchell', 'role' => 'CEO, Tech Corp', 'initials' => 'SM'],
                ['quote' => 'Exceptional quality and attention to detail. They delivered exactly what we needed.', 'name' => 'James Kim', 'role' => 'Marketing Director', 'initials' => 'JK'],
                ['quote' => 'The best investment we have made. Their expertise is unmatched in the industry.', 'name' => 'Lisa Rodriguez', 'role' => 'Business Owner', 'initials' => 'LR'],
            ],
            'stats' => [
                ['number' => '500', 'suffix' => '+', 'label' => 'Projects Completed'],
                ['number' => '98', 'suffix' => '%', 'label' => 'Client Satisfaction'],
                ['number' => '15', 'suffix' => '+', 'label' => 'Years Experience'],
            ],
            'ticker_items' => [$siteName, 'Premium Quality', 'Expert Team', 'Proven Results', 'Get Started'],
            'credentials' => [
                'Award-winning team with proven track record',
                'Industry-leading expertise and innovation',
                'Trusted by hundreds of businesses worldwide',
            ],
            'pillars' => [
                ['icon' => '🎯', 'title' => 'Expert Guidance', 'desc' => 'Personalized strategies from industry veterans.'],
                ['icon' => '📱', 'title' => 'Modern Solutions', 'desc' => 'Cutting-edge technology tailored to your needs.'],
                ['icon' => '🏆', 'title' => 'Proven Results', 'desc' => 'Track record of delivering measurable outcomes.'],
            ],
            'cta_title' => 'Let\'s Build Together.',
            'cta_eyebrow' => 'Ready to Start?',
            'cta_text' => 'Get in touch today and discover how we can help your business grow.',
            'cta_button' => 'Get Started',
            'cta_ghost' => 'Contact Us',
            'contact_title' => 'Get In Touch.',
            'contact_subtitle' => 'We would love to hear from you.',
            'email' => 'hello@example.com',
            'phone' => '(555) 123-4567',
            'address' => '123 Business Street, City, State 12345',
        ];
    }

    private function buildDefaultImages(): array
    {
        // Placeholder images for master previews
        $base = 'https://images.unsplash.com/';
        return [
            'hero' => $base . 'photo-1497366216548-37526070297c?w=1920&h=1080&fit=crop',
            'about' => $base . 'photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
            'services' => $base . 'photo-1460925895917-afdab827c52f?w=800&h=600&fit=crop',
            'gallery1' => $base . 'photo-1504384308090-c894fdcc538d?w=800&h=600&fit=crop',
            'gallery2' => $base . 'photo-1553877522-43269d4ea984?w=800&h=600&fit=crop',
            'team' => $base . 'photo-1560250097-0b93528c311a?w=800&h=600&fit=crop',
        ];
    }

    // ─── Private Helpers ─────────────────────────────────────

    private function setMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    private function setOption(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT option_id FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);
        if ($stmt->fetchColumn()) {
            $stmt = $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?");
            $stmt->execute([$value, $key]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
            $stmt->execute([$key, $value]);
        }
    }

    private function createNavMenu(\PDO $pdo, string $siteUrl, array $pages): void
    {
        $pdo->exec("INSERT INTO wp_terms (name, slug) VALUES ('Primary Menu', 'primary-menu')");
        $termId = (int) $pdo->lastInsertId();
        $pdo->exec("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES ({$termId}, 'nav_menu', '', 0, " . count($pages) . ")");

        $order = 0;
        $now = date('Y-m-d H:i:s');
        foreach ($pages as $page) {
            $order++;
            $stmt = $pdo->prepare("INSERT INTO wp_posts
                (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
                 post_status, comment_status, ping_status, post_name, post_type,
                 post_modified, post_modified_gmt, guid, menu_order, to_ping, pinged, post_content_filtered)
                VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'nav_menu_item', ?, ?, ?, ?, '', '', '')");
            $itemSlug = 'nav-' . $page['slug'];
            $guid = $siteUrl . '/?p=0';
            $stmt->execute([$now, $now, $page['title'], $itemSlug, $now, $now, $guid, $order]);
            $itemId = (int) $pdo->lastInsertId();

            $this->setMeta($pdo, $itemId, '_menu_item_type', 'post_type');
            $this->setMeta($pdo, $itemId, '_menu_item_menu_item_parent', '0');
            $this->setMeta($pdo, $itemId, '_menu_item_object_id', (string) $page['id']);
            $this->setMeta($pdo, $itemId, '_menu_item_object', 'page');
            $this->setMeta($pdo, $itemId, '_menu_item_target', '');
            $this->setMeta($pdo, $itemId, '_menu_item_classes', serialize(['']));
            $this->setMeta($pdo, $itemId, '_menu_item_xfn', '');
            $this->setMeta($pdo, $itemId, '_menu_item_url', '');

            $ttId = $pdo->query("SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = {$termId}")->fetchColumn();
            $pdo->exec("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES ({$itemId}, {$ttId}, {$order})");
        }

        $locations = serialize(['primary' => $termId, 'menu-1' => $termId]);
        $this->setOption($pdo, 'nav_menu_locations', $locations);
        $this->setOption($pdo, 'theme_mods_flavor-starter', serialize(['nav_menu_locations' => ['primary' => $termId]]));
    }

    private function updateElementorKit(\PDO $pdo, AbstractLayout $layout): void
    {
        $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name='elementor_active_kit'");
        $kitId = (int) $stmt->fetchColumn();

        if (!$kitId) {
            return;
        }

        $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_page_settings'");
        $stmt->execute([$kitId]);
        $raw = $stmt->fetchColumn();
        $kitSettings = $raw ? (json_decode($raw, true) ?: []) : [];

        $colors = $layout->colors();
        $fonts = $layout->fonts();

        $kitSettings['system_colors'] = [
            ['_id' => 'primary', 'title' => 'Primary', 'color' => $colors['primary']],
            ['_id' => 'secondary', 'title' => 'Secondary', 'color' => $colors['secondary'] ?? $colors['primary']],
            ['_id' => 'text', 'title' => 'Text', 'color' => $colors['text']],
            ['_id' => 'accent', 'title' => 'Accent', 'color' => $colors['accent'] ?? $colors['primary']],
        ];

        $kitSettings['system_typography'] = [
            ['_id' => 'primary', 'title' => 'Primary', 'typography_font_family' => $fonts['heading'], 'typography_font_weight' => '700'],
            ['_id' => 'secondary', 'title' => 'Secondary', 'typography_font_family' => $fonts['body'], 'typography_font_weight' => '400'],
            ['_id' => 'text', 'title' => 'Text', 'typography_font_family' => $fonts['body'], 'typography_font_weight' => '400'],
            ['_id' => 'accent', 'title' => 'Accent', 'typography_font_family' => $fonts['heading'], 'typography_font_weight' => '600'],
        ];

        $json = json_encode($kitSettings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_page_settings'");
        $stmt->execute([$json, $kitId]);
    }
}
