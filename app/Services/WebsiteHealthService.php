<?php

namespace App\Services;

use App\Models\Website;
use App\Models\WebsiteSeoData;
use Illuminate\Support\Facades\Log;

class WebsiteHealthService
{
    public function __construct(
        private WordPressService $wordpressService,
    ) {}

    /**
     * Analyze a website and return a comprehensive health score.
     * Score: 0-100 across 4 categories: SEO, Content, Performance, Design.
     */
    public function analyze(Website $website): array
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) {
            return ['score' => 0, 'details' => ['error' => 'No database']];
        }

        $seoScore = $this->analyzeSeo($website, $dbName);
        $contentScore = $this->analyzeContent($dbName);
        $performanceScore = $this->analyzePerformance($website, $dbName);
        $designScore = $this->analyzeDesign($dbName);

        $totalScore = (int) round(
            ($seoScore['score'] * 0.30) +
            ($contentScore['score'] * 0.30) +
            ($performanceScore['score'] * 0.20) +
            ($designScore['score'] * 0.20)
        );

        $details = [
            'seo' => $seoScore,
            'content' => $contentScore,
            'performance' => $performanceScore,
            'design' => $designScore,
        ];

        $suggestions = $this->generateSuggestions($details);

        // Save to website
        $website->update([
            'health_score' => $totalScore,
            'seo_score' => $seoScore['score'],
            'health_details' => $details,
            'ai_suggestions' => $suggestions,
            'last_analyzed_at' => now(),
        ]);

        return [
            'score' => $totalScore,
            'details' => $details,
            'suggestions' => $suggestions,
        ];
    }

    private function analyzeSeo(Website $website, string $dbName): array
    {
        $score = 0;
        $issues = [];
        $passes = [];

        $pdo = $this->getPdo($dbName);
        $pages = $this->getPages($pdo);

        // Check 1: Site title exists and is meaningful (10 pts)
        $siteTitle = $this->getOption($pdo, 'blogname');
        if ($siteTitle && strlen($siteTitle) > 3 && $siteTitle !== 'WordPress') {
            $score += 10;
            $passes[] = 'Site title is set';
        } else {
            $issues[] = ['type' => 'warning', 'msg' => 'Set a meaningful site title'];
        }

        // Check 2: Tagline exists (10 pts)
        $tagline = $this->getOption($pdo, 'blogdescription');
        if ($tagline && strlen($tagline) > 5 && $tagline !== 'Just another WordPress site') {
            $score += 10;
            $passes[] = 'Custom tagline set';
        } else {
            $issues[] = ['type' => 'warning', 'msg' => 'Set a custom tagline/description'];
        }

        // Check 3: All pages have titles (15 pts)
        $pagesWithTitles = 0;
        foreach ($pages as $page) {
            if (!empty($page['post_title']) && strlen($page['post_title']) > 2) $pagesWithTitles++;
        }
        if (count($pages) > 0) {
            $ratio = $pagesWithTitles / count($pages);
            $score += (int) round($ratio * 15);
            if ($ratio === 1.0) $passes[] = 'All pages have titles';
            else $issues[] = ['type' => 'info', 'msg' => (count($pages) - $pagesWithTitles) . ' pages missing proper titles'];
        }

        // Check 4: Permalink structure is set (10 pts)
        $permalink = $this->getOption($pdo, 'permalink_structure');
        if ($permalink && $permalink !== '') {
            $score += 10;
            $passes[] = 'Pretty permalinks enabled';
        } else {
            $issues[] = ['type' => 'error', 'msg' => 'Enable pretty permalinks (/%postname%/)'];
        }

        // Check 5: Images have alt text (15 pts)
        $imgAltScore = $this->checkImageAltText($pdo);
        $score += (int) round($imgAltScore * 15);
        if ($imgAltScore > 0.8) $passes[] = 'Most images have alt text';
        else $issues[] = ['type' => 'warning', 'msg' => 'Add alt text to images for better SEO'];

        // Check 6: Meta descriptions exist (via our SEO data) (15 pts)
        $seoDataCount = WebsiteSeoData::where('website_id', $website->id)->whereNotNull('meta_description')->count();
        if ($seoDataCount >= count($pages) && count($pages) > 0) {
            $score += 15;
            $passes[] = 'All pages have meta descriptions';
        } elseif ($seoDataCount > 0) {
            $score += 7;
            $issues[] = ['type' => 'info', 'msg' => "Only {$seoDataCount}/" . count($pages) . " pages have meta descriptions"];
        } else {
            $issues[] = ['type' => 'error', 'msg' => 'No meta descriptions set — ask the AI chatbot to "add SEO to all pages"'];
        }

        // Check 7: Has a homepage set (10 pts)
        $showOnFront = $this->getOption($pdo, 'show_on_front');
        $frontPage = $this->getOption($pdo, 'page_on_front');
        if ($showOnFront === 'page' && $frontPage) {
            $score += 10;
            $passes[] = 'Static homepage set';
        } else {
            $issues[] = ['type' => 'warning', 'msg' => 'Set a static homepage'];
        }

        // Check 8: Has a contact page (15 pts)
        $hasContact = false;
        foreach ($pages as $p) {
            if (str_contains(strtolower($p['post_name']), 'contact')) { $hasContact = true; break; }
        }
        if ($hasContact) { $score += 15; $passes[] = 'Contact page exists'; }
        else $issues[] = ['type' => 'warning', 'msg' => 'Add a contact page'];

        return ['score' => min(100, $score), 'issues' => $issues, 'passes' => $passes];
    }

    private function analyzeContent(string $dbName): array
    {
        $score = 0;
        $issues = [];
        $passes = [];

        $pdo = $this->getPdo($dbName);
        $pages = $this->getPages($pdo);

        // Check 1: Minimum number of pages (20 pts)
        $pageCount = count($pages);
        if ($pageCount >= 5) { $score += 20; $passes[] = "{$pageCount} pages created"; }
        elseif ($pageCount >= 3) { $score += 12; $issues[] = ['type' => 'info', 'msg' => 'Consider adding more pages (at least 5)']; }
        else { $score += 5; $issues[] = ['type' => 'warning', 'msg' => "Only {$pageCount} pages — add more content"]; }

        // Check 2: Content length per page (25 pts)
        $totalWords = 0;
        $shortPages = 0;
        foreach ($pages as $page) {
            $content = strip_tags($page['post_content'] ?? '');
            $words = str_word_count($content);
            $totalWords += $words;
            if ($words < 50) $shortPages++;
        }
        $avgWords = $pageCount > 0 ? $totalWords / $pageCount : 0;
        if ($avgWords >= 200) { $score += 25; $passes[] = "Good content length (avg {$avgWords} words/page)"; }
        elseif ($avgWords >= 100) { $score += 15; $issues[] = ['type' => 'info', 'msg' => 'Content could be longer (aim for 200+ words/page)']; }
        else { $score += 5; $issues[] = ['type' => 'warning', 'msg' => 'Pages have very little text content']; }

        if ($shortPages > 0) {
            $issues[] = ['type' => 'info', 'msg' => "{$shortPages} pages have less than 50 words"];
        }

        // Check 3: Has sections with Elementor data (25 pts)
        $pagesWithElementor = 0;
        $totalSections = 0;
        foreach ($pages as $page) {
            $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_elementor_data'");
            $stmt->execute([$page['ID']]);
            $data = $stmt->fetchColumn();
            if ($data && $data !== '[]') {
                $pagesWithElementor++;
                $decoded = json_decode($data, true);
                if (is_array($decoded)) $totalSections += count($decoded);
            }
        }
        if ($pagesWithElementor > 0) {
            $ratio = $pagesWithElementor / max(1, $pageCount);
            $score += (int) round($ratio * 25);
            $passes[] = "{$pagesWithElementor} pages with Elementor layout ({$totalSections} total sections)";
        } else {
            $issues[] = ['type' => 'error', 'msg' => 'No pages have Elementor layouts'];
        }

        // Check 4: Diverse section types (15 pts)
        $sectionTypes = $this->countSectionTypes($pdo);
        $uniqueTypes = count($sectionTypes);
        if ($uniqueTypes >= 6) { $score += 15; $passes[] = "{$uniqueTypes} different section types used"; }
        elseif ($uniqueTypes >= 3) { $score += 8; $issues[] = ['type' => 'info', 'msg' => 'Add more section variety (testimonials, FAQ, stats, etc.)']; }
        else { $issues[] = ['type' => 'warning', 'msg' => 'Website needs more section variety']; }

        // Check 5: Has CTA sections (15 pts)
        $hasCta = false;
        foreach ($sectionTypes as $type => $count) {
            if (str_contains($type, 'button') || str_contains($type, 'cta')) { $hasCta = true; break; }
        }
        if ($hasCta) { $score += 15; $passes[] = 'Has call-to-action elements'; }
        else { $issues[] = ['type' => 'warning', 'msg' => 'Add call-to-action buttons to improve conversions']; }

        return ['score' => min(100, $score), 'issues' => $issues, 'passes' => $passes];
    }

    private function analyzePerformance(Website $website, string $dbName): array
    {
        $score = 0;
        $issues = [];
        $passes = [];

        $pdo = $this->getPdo($dbName);

        // Check 1: Image count reasonable (20 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type='attachment' AND post_mime_type LIKE 'image%'");
        $imageCount = (int) $stmt->fetchColumn();
        if ($imageCount > 0 && $imageCount <= 50) { $score += 20; $passes[] = "{$imageCount} images (good)"; }
        elseif ($imageCount > 50) { $score += 10; $issues[] = ['type' => 'info', 'msg' => "High image count ({$imageCount}) may slow loading"]; }
        else { $issues[] = ['type' => 'warning', 'msg' => 'No images found — add visuals']; }

        // Check 2: Active plugins count (20 pts)
        $plugins = @unserialize($this->getOption($pdo, 'active_plugins') ?: 'a:0:{}');
        $pluginCount = is_array($plugins) ? count($plugins) : 0;
        if ($pluginCount <= 10) { $score += 20; $passes[] = "{$pluginCount} active plugins (lean)"; }
        elseif ($pluginCount <= 20) { $score += 12; $issues[] = ['type' => 'info', 'msg' => "{$pluginCount} active plugins — consider deactivating unused ones"]; }
        else { $score += 5; $issues[] = ['type' => 'warning', 'msg' => "Too many plugins ({$pluginCount}) — remove unused plugins"]; }

        // Check 3: Has caching mu-plugin (20 pts)
        $htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $muPath = $htdocsPath . '/' . $website->subdomain . '/wp-content/mu-plugins';
        if (is_dir($muPath)) {
            $score += 20;
            $passes[] = 'MU-plugins directory exists';
        } else {
            $issues[] = ['type' => 'info', 'msg' => 'Consider adding performance mu-plugins'];
        }

        // Check 4: Post revisions (cleanup indicator) (20 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type='revision'");
        $revisions = (int) $stmt->fetchColumn();
        if ($revisions < 50) { $score += 20; $passes[] = 'Low revision count'; }
        elseif ($revisions < 200) { $score += 12; $issues[] = ['type' => 'info', 'msg' => "{$revisions} post revisions — consider cleanup"]; }
        else { $issues[] = ['type' => 'warning', 'msg' => "{$revisions} post revisions bloating database"]; }

        // Check 5: Database tables healthy (20 pts)
        $stmt = $pdo->query("SHOW TABLES");
        $tableCount = $stmt->rowCount();
        if ($tableCount > 0) { $score += 20; $passes[] = "{$tableCount} database tables"; }

        return ['score' => min(100, $score), 'issues' => $issues, 'passes' => $passes];
    }

    private function analyzeDesign(string $dbName): array
    {
        $score = 0;
        $issues = [];
        $passes = [];

        $pdo = $this->getPdo($dbName);

        // Check 1: Has a header template (HFE) (25 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts p JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='ehf_template_type' AND pm.meta_value='type_header' WHERE p.post_type='elementor-hf'");
        if ((int) $stmt->fetchColumn() > 0) { $score += 25; $passes[] = 'Custom header template'; }
        else $issues[] = ['type' => 'warning', 'msg' => 'No custom header — site may look incomplete'];

        // Check 2: Has a footer template (25 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts p JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='ehf_template_type' AND pm.meta_value='type_footer' WHERE p.post_type='elementor-hf'");
        if ((int) $stmt->fetchColumn() > 0) { $score += 25; $passes[] = 'Custom footer template'; }
        else $issues[] = ['type' => 'warning', 'msg' => 'No custom footer'];

        // Check 3: Has navigation menu (20 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type='nav_menu_item' AND post_status='publish'");
        $menuItems = (int) $stmt->fetchColumn();
        if ($menuItems >= 3) { $score += 20; $passes[] = "{$menuItems} menu items"; }
        elseif ($menuItems > 0) { $score += 10; $issues[] = ['type' => 'info', 'msg' => 'Menu has few items']; }
        else $issues[] = ['type' => 'error', 'msg' => 'No navigation menu found'];

        // Check 4: Uses images (hero, about, etc.) (15 pts)
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type='attachment' AND post_mime_type LIKE 'image%'");
        $imgs = (int) $stmt->fetchColumn();
        if ($imgs >= 3) { $score += 15; $passes[] = 'Good image usage'; }
        elseif ($imgs > 0) { $score += 8; $issues[] = ['type' => 'info', 'msg' => 'Add more images for visual appeal']; }
        else $issues[] = ['type' => 'warning', 'msg' => 'No images — site looks empty'];

        // Check 5: Has diverse widget types (15 pts)
        $widgetTypes = $this->countWidgetTypes($pdo);
        if (count($widgetTypes) >= 5) { $score += 15; $passes[] = count($widgetTypes) . ' widget types used'; }
        elseif (count($widgetTypes) >= 3) { $score += 8; $issues[] = ['type' => 'info', 'msg' => 'Use more widget types for variety']; }
        else $issues[] = ['type' => 'warning', 'msg' => 'Limited widget variety'];

        return ['score' => min(100, $score), 'issues' => $issues, 'passes' => $passes];
    }

    private function generateSuggestions(array $details): array
    {
        $suggestions = [];

        foreach ($details as $category => $data) {
            foreach ($data['issues'] ?? [] as $issue) {
                $suggestions[] = [
                    'category' => $category,
                    'type' => $issue['type'],
                    'message' => $issue['msg'],
                    'priority' => match ($issue['type']) { 'error' => 'high', 'warning' => 'medium', default => 'low' },
                ];
            }
        }

        // Sort by priority
        usort($suggestions, function ($a, $b) {
            $order = ['high' => 0, 'medium' => 1, 'low' => 2];
            return ($order[$a['priority']] ?? 3) <=> ($order[$b['priority']] ?? 3);
        });

        return $suggestions;
    }

    private function checkImageAltText(\PDO $pdo): float
    {
        $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type='attachment' AND post_mime_type LIKE 'image%'");
        $total = (int) $stmt->fetchColumn();
        if ($total === 0) return 1.0;

        $stmt = $pdo->query("
            SELECT COUNT(DISTINCT p.ID) FROM wp_posts p
            JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='_wp_attachment_image_alt'
            WHERE p.post_type='attachment' AND p.post_mime_type LIKE 'image%'
            AND pm.meta_value IS NOT NULL AND pm.meta_value != ''
        ");
        $withAlt = (int) $stmt->fetchColumn();

        return $withAlt / $total;
    }

    private function countSectionTypes(\PDO $pdo): array
    {
        $types = [];
        $stmt = $pdo->query("SELECT meta_value FROM wp_postmeta WHERE meta_key='_elementor_data' AND meta_value != '[]'");
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data = json_decode($row['meta_value'], true);
            if (!$data) continue;
            $this->countWidgetTypesRecursive($data, $types);
        }
        return $types;
    }

    private function countWidgetTypes(\PDO $pdo): array
    {
        return $this->countSectionTypes($pdo);
    }

    private function countWidgetTypesRecursive(array $data, array &$types): void
    {
        foreach ($data as $el) {
            if (isset($el['widgetType'])) {
                $types[$el['widgetType']] = ($types[$el['widgetType']] ?? 0) + 1;
            }
            if (isset($el['elements'])) {
                $this->countWidgetTypesRecursive($el['elements'], $types);
            }
        }
    }

    private function getPages(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT ID, post_title, post_name, post_content FROM wp_posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, ID");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getOption(\PDO $pdo, string $key): ?string
    {
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name=?");
        $stmt->execute([$key]);
        return $stmt->fetchColumn() ?: null;
    }

    private function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO("mysql:host=" . config('database.connections.mysql.host', '127.0.0.1') . ";dbname={$dbName}", config('database.connections.mysql.username', 'root'), config('database.connections.mysql.password', ''));
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
