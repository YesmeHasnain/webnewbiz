<?php

namespace App\Services\AiCopilot;

use App\Models\Website;
use App\Services\WpBridgeService;
use Illuminate\Support\Facades\Log;

class ContextBuilder
{
    private WpBridgeService $bridge;

    public function __construct(WpBridgeService $bridge)
    {
        $this->bridge = $bridge;
    }

    /**
     * Build rich context for the AI copilot system prompt.
     */
    public function build(Website $website, ?int $pageId = null): array
    {
        $context = [
            'site' => $this->getSiteContext($website),
            'pages' => $this->getPagesContext($website),
            'current_page' => null,
        ];

        if ($pageId) {
            $context['current_page'] = $this->getPageEditables($website, $pageId);
        }

        return $context;
    }

    /**
     * Build the system prompt string from context.
     */
    public function buildSystemPrompt(Website $website, array $context): string
    {
        $siteName = $website->name;
        $siteUrl = $website->url;
        $businessType = $website->business_type ?? 'business';
        $layout = $website->layout_slug ?? 'unknown';

        $siteInfo = $context['site'] ?? [];
        $pages = $context['pages'] ?? [];
        $currentPage = $context['current_page'] ?? null;

        $pageList = '';
        if (!empty($pages)) {
            foreach ($pages as $p) {
                $pageList .= "  - [{$p['id']}] {$p['title']} ({$p['status']}) — {$p['url']}\n";
            }
        }

        $currentPageSection = '';
        if ($currentPage) {
            $currentPageSection = "\nCURRENT PAGE (user is viewing this page):\n";
            $currentPageSection .= "- Page ID: {$currentPage['page_id']}\n";
            $currentPageSection .= "- Title: {$currentPage['title']}\n";
            $currentPageSection .= "- Editable elements: {$currentPage['total']}\n";

            if (!empty($currentPage['editables'])) {
                $currentPageSection .= "- Elements:\n";
                foreach (array_slice($currentPage['editables'], 0, 50) as $el) {
                    $type = $el['widget_type'];
                    $id = $el['id'];
                    $fields = $el['fields'] ?? [];
                    $preview = '';

                    if (isset($fields['title'])) {
                        $preview = mb_substr(strip_tags($fields['title']), 0, 60);
                    } elseif (isset($fields['editor'])) {
                        $preview = mb_substr(strip_tags($fields['editor']), 0, 60);
                    } elseif (isset($fields['text'])) {
                        $preview = mb_substr(strip_tags($fields['text']), 0, 60);
                    }

                    $currentPageSection .= "  [{$id}] {$type}";
                    if ($preview) $currentPageSection .= ": \"{$preview}\"";
                    $currentPageSection .= "\n";
                }
            }
        }

        $wooSection = '';
        if (!empty($siteInfo['woocommerce_active'])) {
            $wooSection = "\nWOOCOMMERCE: Active — can manage products, orders, categories";
        }

        $themeInfo = $siteInfo['active_theme'] ?? 'unknown';
        $pluginCount = $siteInfo['active_plugins'] ?? 0;
        $totalPages = $siteInfo['total_pages'] ?? 0;

        return <<<PROMPT
You are an expert AI Co-Pilot for the WordPress website "{$siteName}".
You can READ and WRITE to this website. You have tools to modify content, design, SEO, products, and more.

WEBSITE INFO:
- Name: {$siteName}
- Business Type: {$businessType}
- URL: {$siteUrl}
- Layout: {$layout}
- Theme: {$themeInfo}
- Active Plugins: {$pluginCount}
- Total Pages: {$totalPages}
{$wooSection}

PAGES:
{$pageList}
{$currentPageSection}

RULES:
1. When the user asks to change something, USE YOUR TOOLS to make the change. Do NOT just describe what to do.
2. Always explain what you changed AFTER making the change.
3. For text edits, use edit_element_text. For style changes, use edit_element_style.
4. For new sections/pages, use the appropriate creation tools.
5. When editing Elementor elements, use the element ID from the CURRENT PAGE context above.
6. If you need to see a page's content before editing, use get_page_editables first.
7. Be concise. State what you did, not how the tool works.
8. Respond in the same language the user writes in (English, Urdu, Roman Urdu, etc).
9. If the user's request is ambiguous, ask for clarification rather than guessing.
10. For global changes (brand color, font everywhere), use the global color/font tools.
PROMPT;
    }

    private function getSiteContext(Website $website): array
    {
        try {
            $overview = $this->bridge->getOverview($website);
            return $overview['data'] ?? [];
        } catch (\Exception $e) {
            Log::warning("Copilot: failed to get site context for {$website->slug}: {$e->getMessage()}");
            return [];
        }
    }

    private function getPagesContext(Website $website): array
    {
        try {
            $result = $this->bridge->listPages($website);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            Log::warning("Copilot: failed to get pages for {$website->slug}: {$e->getMessage()}");
            return [];
        }
    }

    private function getPageEditables(Website $website, int $pageId): ?array
    {
        try {
            $result = $this->bridge->getElementorEditables($website, $pageId);
            return $result['data'] ?? null;
        } catch (\Exception $e) {
            Log::warning("Copilot: failed to get editables for page {$pageId}: {$e->getMessage()}");
            return null;
        }
    }
}
