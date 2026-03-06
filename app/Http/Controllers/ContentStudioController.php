<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\AIContentService;
use App\Services\WordPressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContentStudioController extends Controller
{
    public function __construct(
        private AIContentService $aiService,
        private WordPressService $wpService,
    ) {}

    /**
     * Regenerate a specific section's content using AI.
     */
    public function regenerateSection(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'page_id' => 'required|integer',
            'section_index' => 'required|integer|min:0',
            'instructions' => 'nullable|string|max:500',
        ]);

        $dbName = $website->wp_db_name;
        $pageId = $request->input('page_id');
        $sectionIndex = $request->input('section_index');
        $instructions = $request->input('instructions', 'Regenerate this section with better, more professional content');

        $pdo = $this->wpService->getPdo($dbName);
        $elementorData = $this->getElementorData($pdo, $pageId);
        if (!$elementorData || !isset($elementorData[$sectionIndex])) {
            return response()->json(['success' => false, 'error' => 'Section not found'], 404);
        }

        $currentSection = $elementorData[$sectionIndex];
        $currentText = $this->extractTextFromSection($currentSection);

        $prompt = "You are rewriting content for a website section. Business type: {$website->ai_business_type}.
Current section content: {$currentText}
Instructions: {$instructions}

Return ONLY a JSON object with these fields (include all that apply):
{\"title\": \"New heading\", \"subtitle\": \"New subtext\", \"description\": \"New paragraph\", \"items\": [{\"title\": \"...\", \"description\": \"...\"}]}";

        $result = $this->aiService->quickComplete($prompt);
        $newContent = json_decode($result, true);
        if (!$newContent) {
            return response()->json(['success' => false, 'error' => 'AI generation failed'], 500);
        }

        // Apply new content to the section
        $elementorData[$sectionIndex] = $this->applySectionContent($currentSection, $newContent);
        $this->saveElementorData($pdo, $pageId, $elementorData);

        return response()->json([
            'success' => true,
            'message' => 'Section content regenerated',
            'new_content' => $newContent,
        ]);
    }

    /**
     * Change the tone of content across a page or section.
     */
    public function changeTone(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'page_id' => 'required|integer',
            'tone' => 'required|string|in:professional,casual,friendly,formal,luxury,playful,urgent,technical',
            'section_index' => 'nullable|integer|min:0',
        ]);

        $dbName = $website->wp_db_name;
        $pageId = $request->input('page_id');
        $tone = $request->input('tone');
        $sectionIndex = $request->input('section_index');

        $pdo = $this->wpService->getPdo($dbName);
        $elementorData = $this->getElementorData($pdo, $pageId);
        if (!$elementorData) {
            return response()->json(['success' => false, 'error' => 'Page not found'], 404);
        }

        $sections = $sectionIndex !== null ? [$sectionIndex => $elementorData[$sectionIndex]] : $elementorData;
        $allText = [];
        foreach ($sections as $idx => $section) {
            $allText[$idx] = $this->extractTextFromSection($section);
        }

        $prompt = "Rewrite the following website content in a {$tone} tone. Business: {$website->ai_business_type}.
Keep the same structure/meaning but change the voice and style.

Content sections:\n" . json_encode($allText, JSON_PRETTY_PRINT) . "

Return a JSON object where keys are section indices and values have {\"title\": \"...\", \"subtitle\": \"...\", \"description\": \"...\"}. Return ONLY JSON.";

        $result = $this->aiService->quickComplete($prompt);
        $rewritten = json_decode($result, true);
        if (!$rewritten) {
            return response()->json(['success' => false, 'error' => 'Tone change failed'], 500);
        }

        foreach ($rewritten as $idx => $content) {
            if (isset($elementorData[$idx])) {
                $elementorData[$idx] = $this->applySectionContent($elementorData[$idx], $content);
            }
        }
        $this->saveElementorData($pdo, $pageId, $elementorData);

        return response()->json([
            'success' => true,
            'message' => "Content tone changed to {$tone}",
            'sections_updated' => count($rewritten),
        ]);
    }

    /**
     * Translate page content to another language.
     */
    public function translateContent(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'page_id' => 'required|integer',
            'language' => 'required|string|max:50',
        ]);

        $dbName = $website->wp_db_name;
        $pageId = $request->input('page_id');
        $language = $request->input('language');

        $pdo = $this->wpService->getPdo($dbName);
        $elementorData = $this->getElementorData($pdo, $pageId);
        if (!$elementorData) {
            return response()->json(['success' => false, 'error' => 'Page not found'], 404);
        }

        $allText = $this->extractAllText($elementorData);

        $prompt = "Translate the following website content to {$language}. Keep all formatting intact.
Return a JSON object mapping original text to translated text.

Text to translate:\n" . json_encode($allText, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "

Return ONLY a JSON object: {\"original text\": \"translated text\", ...}";

        $result = $this->aiService->quickComplete($prompt);
        $translations = json_decode($result, true);
        if (!$translations) {
            return response()->json(['success' => false, 'error' => 'Translation failed'], 500);
        }

        // Apply translations to Elementor data
        $json = json_encode($elementorData, JSON_UNESCAPED_UNICODE);
        foreach ($translations as $original => $translated) {
            $json = str_replace(
                json_encode($original, JSON_UNESCAPED_UNICODE),
                json_encode($translated, JSON_UNESCAPED_UNICODE),
                $json
            );
        }
        $elementorData = json_decode($json, true);
        $this->saveElementorData($pdo, $pageId, $elementorData);

        // Also translate page title
        $stmt = $pdo->prepare("SELECT post_title FROM wp_posts WHERE ID=?");
        $stmt->execute([$pageId]);
        $title = $stmt->fetchColumn();
        if ($title && isset($translations[$title])) {
            $stmt = $pdo->prepare("UPDATE wp_posts SET post_title=? WHERE ID=?");
            $stmt->execute([$translations[$title], $pageId]);
        }

        return response()->json([
            'success' => true,
            'message' => "Content translated to {$language}",
            'translations_count' => count($translations),
        ]);
    }

    /**
     * Generate A/B content variants for a section.
     */
    public function generateVariant(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'page_id' => 'required|integer',
            'section_index' => 'required|integer|min:0',
            'variant_count' => 'nullable|integer|min:1|max:3',
        ]);

        $pageId = $request->input('page_id');
        $sectionIndex = $request->input('section_index');
        $variantCount = $request->input('variant_count', 2);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $elementorData = $this->getElementorData($pdo, $pageId);
        if (!$elementorData || !isset($elementorData[$sectionIndex])) {
            return response()->json(['success' => false, 'error' => 'Section not found'], 404);
        }

        $currentText = $this->extractTextFromSection($elementorData[$sectionIndex]);

        $prompt = "Generate {$variantCount} alternative versions of this website section content.
Business: {$website->ai_business_type}
Current content: {$currentText}

Return a JSON array of {$variantCount} objects, each with: {\"title\": \"...\", \"subtitle\": \"...\", \"description\": \"...\"}
Make each variant distinctly different in approach (e.g., benefit-focused, story-driven, data-driven).
Return ONLY JSON array.";

        $result = $this->aiService->quickComplete($prompt);
        $variants = json_decode($result, true);
        if (!$variants || !is_array($variants)) {
            return response()->json(['success' => false, 'error' => 'Variant generation failed'], 500);
        }

        return response()->json([
            'success' => true,
            'current' => $currentText,
            'variants' => $variants,
        ]);
    }

    /**
     * Expand short content into longer, richer content.
     */
    public function expandContent(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'page_id' => 'required|integer',
            'section_index' => 'required|integer|min:0',
        ]);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $elementorData = $this->getElementorData($pdo, $request->page_id);
        if (!$elementorData || !isset($elementorData[$request->section_index])) {
            return response()->json(['success' => false, 'error' => 'Section not found'], 404);
        }

        $currentText = $this->extractTextFromSection($elementorData[$request->section_index]);

        $prompt = "Expand the following short website content into a richer, more detailed version. Add specific details, benefits, and compelling copy.
Business: {$website->ai_business_type}
Current: {$currentText}

Return JSON: {\"title\": \"...\", \"subtitle\": \"...\", \"description\": \"longer detailed paragraph\", \"items\": [{\"title\": \"...\", \"description\": \"...\"}]}
Return ONLY JSON.";

        $result = $this->aiService->quickComplete($prompt);
        $expanded = json_decode($result, true);
        if (!$expanded) {
            return response()->json(['success' => false, 'error' => 'Content expansion failed'], 500);
        }

        $elementorData[$request->section_index] = $this->applySectionContent($elementorData[$request->section_index], $expanded);
        $this->saveElementorData($pdo, $request->page_id, $elementorData);

        return response()->json([
            'success' => true,
            'message' => 'Content expanded successfully',
            'new_content' => $expanded,
        ]);
    }

    /**
     * Generate SEO-optimized content for all pages.
     */
    public function generateSeo(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $pages = $this->getPages($pdo);

        $pagesSummary = collect($pages)->map(fn($p) => "{$p['post_name']}: {$p['post_title']}")->join("\n");

        $prompt = "Generate SEO metadata for a {$website->ai_business_type} website called \"{$website->name}\".

Pages:\n{$pagesSummary}

Return JSON array, one per page: [{\"slug\": \"page-slug\", \"meta_title\": \"60 chars max\", \"meta_description\": \"160 chars max\", \"keywords\": [\"kw1\",\"kw2\"]}]
Return ONLY JSON array.";

        $result = $this->aiService->quickComplete($prompt);
        $seoData = json_decode($result, true);
        if (!$seoData || !is_array($seoData)) {
            return response()->json(['success' => false, 'error' => 'SEO generation failed'], 500);
        }

        $saved = 0;
        foreach ($seoData as $item) {
            if (empty($item['slug'])) continue;
            \App\Models\WebsiteSeoData::updateOrCreate(
                ['website_id' => $website->id, 'page_slug' => $item['slug']],
                [
                    'meta_title' => $item['meta_title'] ?? null,
                    'meta_description' => $item['meta_description'] ?? null,
                    'meta_keywords' => $item['keywords'] ?? null,
                ]
            );
            $saved++;
        }

        // Also inject into WordPress
        foreach ($seoData as $item) {
            if (empty($item['slug'])) continue;
            $stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_name=? AND post_type='page'");
            $stmt->execute([$item['slug']]);
            $pid = $stmt->fetchColumn();
            if ($pid) {
                $this->wpService->updatePostMetaDirect($pdo, $pid, '_yoast_wpseo_title', $item['meta_title'] ?? '');
                $this->wpService->updatePostMetaDirect($pdo, $pid, '_yoast_wpseo_metadesc', $item['meta_description'] ?? '');
            }
        }

        return response()->json([
            'success' => true,
            'message' => "SEO data generated for {$saved} pages",
            'data' => $seoData,
        ]);
    }

    /**
     * Get a page map — all pages with their sections for the visual editor.
     */
    public function getPageMap(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $pages = $this->getPages($pdo);

        $pageMap = [];
        foreach ($pages as $page) {
            $elementorData = $this->getElementorData($pdo, $page['ID']);
            $sections = [];
            if ($elementorData) {
                foreach ($elementorData as $idx => $section) {
                    $text = $this->extractTextFromSection($section);
                    $widgetTypes = [];
                    $this->collectWidgetTypes($section, $widgetTypes);
                    $sections[] = [
                        'index' => $idx,
                        'widgets' => $widgetTypes,
                        'text_preview' => mb_substr($text, 0, 120),
                        'element_count' => $this->countElements($section),
                    ];
                }
            }
            $pageMap[] = [
                'id' => (int) $page['ID'],
                'title' => $page['post_title'],
                'slug' => $page['post_name'],
                'sections' => $sections,
                'section_count' => count($sections),
            ];
        }

        return response()->json(['success' => true, 'pages' => $pageMap]);
    }

    /**
     * Generate social media content from website content.
     */
    public function generateSocialContent(Request $request, Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        $request->validate([
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string|in:facebook,instagram,twitter,linkedin',
            'topic' => 'nullable|string|max:200',
        ]);

        $pdo = $this->wpService->getPdo($website->wp_db_name);
        $siteInfo = $this->wpService->getSiteInfo($website->wp_db_name);
        $pages = $this->getPages($pdo);

        $contentSummary = '';
        foreach (array_slice($pages, 0, 3) as $page) {
            $text = strip_tags($page['post_content'] ?? '');
            $contentSummary .= mb_substr($text, 0, 300) . "\n";
        }

        $platforms = implode(', ', $request->input('platforms'));
        $topic = $request->input('topic', 'general business promotion');

        $prompt = "Generate social media posts for a {$website->ai_business_type} business called \"{$website->name}\".
Website: {$website->url}
Tagline: " . ($siteInfo['blogdescription'] ?? '') . "
Topic: {$topic}
Content summary: {$contentSummary}

Generate posts for: {$platforms}

Return JSON array: [{\"platform\": \"facebook\", \"content\": \"post text\", \"hashtags\": [\"#tag1\"], \"type\": \"promotional|educational|engagement\"}]
For each platform, generate 2-3 posts. Include emojis and hashtags appropriate for each platform.
Return ONLY JSON array.";

        $result = $this->aiService->quickComplete($prompt);
        $posts = json_decode($result, true);
        if (!$posts || !is_array($posts)) {
            return response()->json(['success' => false, 'error' => 'Social content generation failed'], 500);
        }

        return response()->json([
            'success' => true,
            'posts' => $posts,
        ]);
    }

    // ─── Helpers ────────────────────────────────────────────

    private function getElementorData(\PDO $pdo, int $pageId): ?array
    {
        $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id=? AND meta_key='_elementor_data'");
        $stmt->execute([$pageId]);
        $raw = $stmt->fetchColumn();
        return $raw ? json_decode($raw, true) : null;
    }

    private function saveElementorData(\PDO $pdo, int $pageId, array $data): void
    {
        $json = wp_slash_elementor(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value=? WHERE post_id=? AND meta_key='_elementor_data'");
        $stmt->execute([$json, $pageId]);
    }

    private function getPages(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT ID, post_title, post_name, post_content FROM wp_posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, ID");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function extractTextFromSection(array $section): string
    {
        $texts = [];
        $this->walkElements($section, function ($el) use (&$texts) {
            $settings = $el['settings'] ?? [];
            if (!empty($settings['title'])) $texts[] = $settings['title'];
            if (!empty($settings['editor'])) $texts[] = strip_tags($settings['editor']);
            if (!empty($settings['description_text'])) $texts[] = $settings['description_text'];
            if (!empty($settings['testimonial_content'])) $texts[] = $settings['testimonial_content'];
            if (!empty($settings['tab_title'])) $texts[] = $settings['tab_title'];
            if (!empty($settings['tab_content'])) $texts[] = strip_tags($settings['tab_content']);
        });
        return implode(' | ', $texts);
    }

    private function extractAllText(array $data): array
    {
        $texts = [];
        foreach ($data as $section) {
            $this->walkElements($section, function ($el) use (&$texts) {
                $settings = $el['settings'] ?? [];
                foreach (['title', 'editor', 'description_text', 'testimonial_content', 'tab_title', 'tab_content', 'html'] as $key) {
                    if (!empty($settings[$key])) {
                        $clean = strip_tags($settings[$key]);
                        if (strlen($clean) > 3) $texts[] = $clean;
                    }
                }
            });
        }
        return array_unique($texts);
    }

    private function applySectionContent(array $section, array $content): array
    {
        $applied = false;
        $this->walkElementsMut($section, function (&$el) use ($content, &$applied) {
            $type = $el['widgetType'] ?? '';
            if ($type === 'heading' && !empty($content['title']) && !$applied) {
                $el['settings']['title'] = $content['title'];
                $applied = true;
            } elseif ($type === 'text-editor' && !empty($content['description'])) {
                $el['settings']['editor'] = '<p>' . $content['description'] . '</p>';
            } elseif ($type === 'heading' && !empty($content['subtitle'])) {
                $el['settings']['title'] = $content['subtitle'];
            }
        });
        return $section;
    }

    private function walkElements(array $element, callable $callback): void
    {
        $callback($element);
        foreach ($element['elements'] ?? [] as $child) {
            $this->walkElements($child, $callback);
        }
    }

    private function walkElementsMut(array &$element, callable $callback): void
    {
        $callback($element);
        foreach ($element['elements'] ?? [] as &$child) {
            $this->walkElementsMut($child, $callback);
        }
    }

    private function collectWidgetTypes(array $element, array &$types): void
    {
        if (!empty($element['widgetType'])) {
            $types[] = $element['widgetType'];
        }
        foreach ($element['elements'] ?? [] as $child) {
            $this->collectWidgetTypes($child, $types);
        }
    }

    private function countElements(array $element): int
    {
        $count = 1;
        foreach ($element['elements'] ?? [] as $child) {
            $count += $this->countElements($child);
        }
        return $count;
    }
}

/**
 * Helper to properly escape for Elementor meta_value storage.
 */
function wp_slash_elementor(string $json): string
{
    return $json; // PDO prepared statements handle escaping
}
