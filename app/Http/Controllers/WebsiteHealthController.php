<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\AIContentService;
use App\Services\WebsiteHealthService;
use App\Services\WordPressService;
use Illuminate\Http\Request;

class WebsiteHealthController extends Controller
{
    public function __construct(
        private WebsiteHealthService $healthService,
        private AIContentService $aiService,
        private WordPressService $wpService,
    ) {}

    /**
     * Analyze website and return health report.
     */
    public function analyze(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $result = $this->healthService->analyze($website);

        return response()->json([
            'success' => true,
            'score' => $result['score'],
            'details' => $result['details'],
            'suggestions' => $result['suggestions'],
        ]);
    }

    /**
     * Get cached health data (no re-analysis).
     */
    public function getHealth(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        return response()->json([
            'success' => true,
            'score' => $website->health_score,
            'seo_score' => $website->seo_score,
            'details' => $website->health_details,
            'suggestions' => $website->ai_suggestions,
            'last_analyzed_at' => $website->last_analyzed_at?->toISOString(),
        ]);
    }

    /**
     * Auto-fix common issues found by health analysis.
     */
    public function autoFix(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'fixes' => 'required|array|min:1',
            'fixes.*' => 'string|in:set_permalink,set_homepage,add_meta_descriptions,add_alt_text,set_tagline',
        ]);

        $dbName = $website->wp_db_name;
        $pdo = $this->wpService->getPdo($dbName);
        $fixed = [];

        foreach ($request->input('fixes') as $fix) {
            switch ($fix) {
                case 'set_permalink':
                    $this->wpService->updateOptionDirect($pdo, 'permalink_structure', '/%postname%/');
                    $fixed[] = 'Permalink structure set to /%postname%/';
                    break;

                case 'set_homepage':
                    $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type='page' AND post_status='publish' AND post_name IN ('home','homepage','front-page') LIMIT 1");
                    $homeId = $stmt->fetchColumn();
                    if (!$homeId) {
                        $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, ID LIMIT 1");
                        $homeId = $stmt->fetchColumn();
                    }
                    if ($homeId) {
                        $this->wpService->updateOptionDirect($pdo, 'show_on_front', 'page');
                        $this->wpService->updateOptionDirect($pdo, 'page_on_front', $homeId);
                        $fixed[] = 'Static homepage set';
                    }
                    break;

                case 'add_meta_descriptions':
                    $pages = $pdo->query("SELECT ID, post_title, post_name, post_content FROM wp_posts WHERE post_type='page' AND post_status='publish'")->fetchAll(\PDO::FETCH_ASSOC);
                    $summaries = collect($pages)->map(fn($p) => "{$p['post_name']}: " . mb_substr(strip_tags($p['post_content']), 0, 200))->join("\n");

                    $prompt = "Generate meta descriptions (max 160 chars each) for these pages of a {$website->ai_business_type} website:
{$summaries}
Return JSON: [{\"slug\": \"page-slug\", \"meta_description\": \"...\"}]";

                    $result = $this->aiService->quickComplete($prompt);
                    $metas = json_decode($result, true);
                    if ($metas && is_array($metas)) {
                        foreach ($metas as $meta) {
                            if (empty($meta['slug'])) continue;
                            \App\Models\WebsiteSeoData::updateOrCreate(
                                ['website_id' => $website->id, 'page_slug' => $meta['slug']],
                                ['meta_description' => $meta['meta_description'] ?? '']
                            );
                        }
                        $fixed[] = 'Meta descriptions generated for ' . count($metas) . ' pages';
                    }
                    break;

                case 'add_alt_text':
                    $stmt = $pdo->query("
                        SELECT p.ID, p.post_title FROM wp_posts p
                        LEFT JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='_wp_attachment_image_alt'
                        WHERE p.post_type='attachment' AND p.post_mime_type LIKE 'image%'
                        AND (pm.meta_value IS NULL OR pm.meta_value='')
                        LIMIT 50
                    ");
                    $images = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($images as $img) {
                        $altText = $this->generateAltText($img['post_title'], $website->ai_business_type);
                        $this->wpService->updatePostMetaDirect($pdo, $img['ID'], '_wp_attachment_image_alt', $altText);
                    }
                    $fixed[] = 'Alt text added to ' . count($images) . ' images';
                    break;

                case 'set_tagline':
                    $prompt = "Generate a short, professional tagline (under 60 characters) for a {$website->ai_business_type} business called \"{$website->name}\". Return ONLY the tagline text.";
                    $tagline = trim($this->aiService->quickComplete($prompt), '"\'');
                    if ($tagline) {
                        $this->wpService->updateOptionDirect($pdo, 'blogdescription', $tagline);
                        $fixed[] = "Tagline set: {$tagline}";
                    }
                    break;
            }
        }

        // Re-analyze after fixes
        $result = $this->healthService->analyze($website);

        return response()->json([
            'success' => true,
            'fixed' => $fixed,
            'new_score' => $result['score'],
        ]);
    }

    /**
     * One-click redesign — extract content, apply new style.
     */
    public function redesign(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'style' => 'required|string|in:modern,classic,bold,minimal,luxury,vibrant',
        ]);

        $style = $request->input('style');
        $dbName = $website->wp_db_name;
        $pdo = $this->wpService->getPdo($dbName);

        $styleConfigs = [
            'modern' => ['primary' => '#6366f1', 'secondary' => '#8b5cf6', 'accent' => '#ec4899', 'heading_font' => 'Inter', 'body_font' => 'Inter', 'heading_style' => 'normal'],
            'classic' => ['primary' => '#1e3a5f', 'secondary' => '#2c5282', 'accent' => '#c69c6d', 'heading_font' => 'Playfair Display', 'body_font' => 'Lora', 'heading_style' => 'normal'],
            'bold' => ['primary' => '#000000', 'secondary' => '#333333', 'accent' => '#ff3d00', 'heading_font' => 'Oswald', 'body_font' => 'Roboto', 'heading_style' => 'uppercase'],
            'minimal' => ['primary' => '#111827', 'secondary' => '#374151', 'accent' => '#059669', 'heading_font' => 'Work Sans', 'body_font' => 'Work Sans', 'heading_style' => 'normal'],
            'luxury' => ['primary' => '#1a1a2e', 'secondary' => '#16213e', 'accent' => '#d4af37', 'heading_font' => 'Cormorant Garamond', 'body_font' => 'Jost', 'heading_style' => 'normal'],
            'vibrant' => ['primary' => '#7c3aed', 'secondary' => '#2563eb', 'accent' => '#f59e0b', 'heading_font' => 'Poppins', 'body_font' => 'Poppins', 'heading_style' => 'normal'],
        ];

        $config = $styleConfigs[$style];

        // Update Elementor global colors via kit
        $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name='elementor_active_kit'");
        $kitId = (int) $stmt->fetchColumn();
        if ($kitId) {
            $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_elementor_page_settings'");
            $stmt->execute([$kitId]);
            $kitSettings = json_decode($stmt->fetchColumn() ?: '{}', true);

            $kitSettings['system_colors'] = [
                ['_id' => 'primary', 'title' => 'Primary', 'color' => $config['primary']],
                ['_id' => 'secondary', 'title' => 'Secondary', 'color' => $config['secondary']],
                ['_id' => 'text', 'title' => 'Text', 'color' => '#333333'],
                ['_id' => 'accent', 'title' => 'Accent', 'color' => $config['accent']],
            ];

            $kitSettings['system_typography'] = [
                ['_id' => 'primary', 'title' => 'Primary', 'typography_font_family' => $config['heading_font'], 'typography_font_weight' => '700'],
                ['_id' => 'secondary', 'title' => 'Secondary', 'typography_font_family' => $config['body_font'], 'typography_font_weight' => '400'],
                ['_id' => 'text', 'title' => 'Text', 'typography_font_family' => $config['body_font'], 'typography_font_weight' => '400'],
                ['_id' => 'accent', 'title' => 'Accent', 'typography_font_family' => $config['heading_font'], 'typography_font_weight' => '600'],
            ];

            $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key='_elementor_page_settings'");
            $stmt->execute([json_encode($kitSettings), $kitId]);
        }

        // Apply heading style to all pages
        if ($config['heading_style'] === 'uppercase') {
            $pages = $pdo->query("SELECT ID FROM wp_posts WHERE post_type='page' AND post_status='publish'")->fetchAll(\PDO::FETCH_COLUMN);
            foreach ($pages as $pid) {
                $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_elementor_data'");
                $stmt->execute([$pid]);
                $data = json_decode($stmt->fetchColumn() ?: '[]', true);
                if ($data) {
                    $this->applyHeadingStyle($data, 'uppercase');
                    $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key='_elementor_data'");
                    $stmt->execute([json_encode($data, JSON_UNESCAPED_UNICODE), $pid]);
                }
            }
        }

        // Flush Elementor CSS cache
        $this->wpService->updateOptionDirect($pdo, 'elementor_css_print_method', 'internal');
        $pdo->exec("DELETE FROM wp_postmeta WHERE meta_key='_elementor_css'");
        $pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '_transient_elementor%'");

        return response()->json([
            'success' => true,
            'message' => "Website redesigned with {$style} style",
            'style' => $config,
        ]);
    }

    /**
     * Get AI-powered smart suggestions for the website.
     */
    public function smartSuggestions(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $pages = $pdo->query("SELECT post_title, post_name FROM wp_posts WHERE post_type='page' AND post_status='publish'")->fetchAll(\PDO::FETCH_ASSOC);
        $siteInfo = $this->wpService->getSiteInfo($website->wp_db_name);

        $pageList = collect($pages)->map(fn($p) => $p['post_title'])->join(', ');

        $prompt = "Analyze this website and suggest 5-8 specific improvements. Be concrete and actionable.

Website: {$website->name}
Type: {$website->ai_business_type}
Title: " . ($siteInfo['blogname'] ?? '') . "
Tagline: " . ($siteInfo['blogdescription'] ?? '') . "
Pages: {$pageList}
Health Score: " . ($website->health_score ?? 'not analyzed') . "

Return JSON array: [{\"category\": \"content|design|seo|conversion|trust\", \"title\": \"Short title\", \"description\": \"What to do and why\", \"impact\": \"high|medium|low\", \"effort\": \"easy|moderate|hard\", \"ai_command\": \"A chatbot command the user can copy-paste to implement this\"}]
Return ONLY JSON array.";

        $result = $this->aiService->quickComplete($prompt);
        $suggestions = json_decode($result, true);
        if (!$suggestions || !is_array($suggestions)) {
            return response()->json(['success' => true, 'suggestions' => $website->ai_suggestions ?? []]);
        }

        $website->update(['ai_suggestions' => $suggestions]);

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }

    // ─── Private Helpers ────────────────────────────────────

    private function generateAltText(string $title, string $businessType): string
    {
        $clean = preg_replace('/[-_]+/', ' ', pathinfo($title, PATHINFO_FILENAME));
        return ucfirst(trim("{$businessType} - {$clean}"));
    }

    private function applyHeadingStyle(array &$data, string $style): void
    {
        foreach ($data as &$el) {
            if (isset($el['widgetType']) && $el['widgetType'] === 'heading') {
                $el['settings']['_css_classes'] = ($el['settings']['_css_classes'] ?? '') . ' text-' . $style;
            }
            if (isset($el['elements'])) {
                $this->applyHeadingStyle($el['elements'], $style);
            }
        }
    }
}
