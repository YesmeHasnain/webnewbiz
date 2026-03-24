<?php

namespace App\Services;

use App\Models\Website;
use App\Services\Layouts\AbstractLayout;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ThemeMatcherService
{
    /**
     * Match a business type/prompt to the best premium layout.
     * Returns layout slug (noir, ivory, azure, etc.)
     */
    public function match(string $businessType, string $prompt = ''): string
    {
        $layouts = config('layouts', []);
        if (empty($layouts)) {
            return 'noir'; // Default fallback
        }

        // Use fast keyword matching (instant, no API call)
        // AI matching removed to speed up build pipeline
        $keywordSlug = $this->keywordFallback($businessType, $prompt, $layouts);
        Log::info("ThemeMatcher: selected '{$keywordSlug}' for '{$businessType}'");
        return $keywordSlug;
    }

    /**
     * AI-powered layout selection.
     */
    private function aiMatch(string $businessType, string $prompt, array $layouts): ?string
    {
        try {
            $layoutList = collect($layouts)->map(fn($cfg, $slug) =>
                "- {$slug}: {$cfg['name']} ({$cfg['style']}) — Best for: " . implode(', ', $cfg['best_for'] ?? [])
            )->join("\n");

            $aiPrompt = "You are a website design matcher. Pick the BEST layout for this business.\n\n"
                . "Available layouts:\n{$layoutList}\n\n"
                . "Business type: {$businessType}\n"
                . ($prompt ? "Description: {$prompt}\n" : '')
                . "\nReply with ONLY the layout slug (e.g. 'noir' or 'ivory'), nothing else.";

            $aiService = app(AIContentService::class);
            $result = $aiService->quickComplete($aiPrompt);
            $slug = trim(strtolower($result));
            $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

            if (isset($layouts[$slug])) {
                return $slug;
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("ThemeMatcher AI failed: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Recommend layout with variety — avoids repeating user's recent layouts.
     * Returns the best layout slug that the user hasn't used recently.
     */
    public function recommendWithVariety(string $businessType, string $prompt, ?int $userId = null): string
    {
        $scores = $this->scoreLayouts($businessType, $prompt);
        $usedLayouts = [];

        // Get layouts the user has already used (most recent first)
        if ($userId) {
            $usedLayouts = Website::where('user_id', $userId)
                ->whereNotNull('ai_theme')
                ->where('ai_theme', '!=', '')
                ->orderByDesc('created_at')
                ->limit(6)
                ->pluck('ai_theme')
                ->toArray();
        }

        // Sort by score descending
        arsort($scores);

        // If we have scores, try to pick a high-scoring layout the user hasn't used
        $bestScore = max($scores);
        if ($bestScore > 0) {
            // Get all layouts within 40% of best score (close contenders)
            $threshold = $bestScore * 0.6;
            $candidates = array_filter($scores, fn($s) => $s >= $threshold);

            // Prefer unused layouts among candidates
            foreach (array_keys($candidates) as $slug) {
                if (!in_array($slug, $usedLayouts)) {
                    return $slug;
                }
            }
            // All candidates used — return the best one anyway
            return array_key_first($candidates);
        }

        // No keyword match — pick from unused layouts, cycling through all
        $allSlugs = array_keys(config('layouts', []));
        $unused = array_diff($allSlugs, $usedLayouts);
        if (!empty($unused)) {
            // Randomize among unused layouts
            $unused = array_values($unused);
            return $unused[array_rand($unused)];
        }

        // User has used ALL layouts — random pick
        return $allSlugs[array_rand($allSlugs)];
    }

    /**
     * Score all layouts against a business type and prompt.
     */
    public function scoreLayouts(string $businessType, string $prompt = ''): array
    {
        $layouts = config('layouts', []);
        $text = strtolower($businessType . ' ' . $prompt);
        $scores = [];

        foreach ($layouts as $slug => $cfg) {
            $keywords = $cfg['keywords'] ?? [];
            $score = 0;
            foreach ($keywords as $keyword) {
                $kw = strtolower($keyword);
                if (preg_match('/\b' . preg_quote($kw, '/') . '\b/', $text)) {
                    $score += strlen($kw) * 3;
                } elseif (str_contains($text, $kw)) {
                    $score += strlen($kw);
                }
            }
            foreach ($cfg['best_for'] ?? [] as $category) {
                if (str_contains($text, strtolower($category))) {
                    $score += 20;
                }
            }
            $scores[$slug] = $score;
        }

        return $scores;
    }

    /**
     * Keyword-based layout matching with word-boundary scoring.
     */
    public function keywordFallback(string $businessType, string $prompt = '', ?array $layouts = null): string
    {
        $scores = $this->scoreLayouts($businessType, $prompt);
        arsort($scores);
        $best = array_key_first($scores);

        if ($scores[$best] > 0) {
            return $best;
        }

        // Smart fallback: rotate through versatile layouts
        $text = strtolower($businessType . ' ' . $prompt);
        $fallbacks = ['azure', 'noir', 'ivory', 'blush', 'forest', 'slate'];
        $hash = crc32($text);
        return $fallbacks[abs($hash) % count($fallbacks)];
    }

    /**
     * Get all layouts for the builder UI.
     */
    public function getLayouts(): array
    {
        $layouts = config('layouts', []);
        $result = [];

        foreach ($layouts as $slug => $cfg) {
            $layout = AbstractLayout::resolve($slug);
            $result[] = [
                'slug' => $slug,
                'name' => $cfg['name'],
                'style' => $cfg['style'],
                'primary' => $cfg['primary'],
                'accent' => $cfg['accent'] ?? $cfg['primary'],
                'preview_bg' => $cfg['preview_bg'] ?? '#FFF',
                'best_for' => $cfg['best_for'] ?? [],
                'description' => $layout ? $layout->description() : '',
                'is_dark' => $layout ? $layout->isDark() : false,
            ];
        }

        return $result;
    }

    /**
     * Check if slug is a premium layout.
     */
    public static function isLayout(string $slug): bool
    {
        return isset(config('layouts', [])[$slug]);
    }

    /**
     * Get label for layout slug.
     */
    public function getLabel(string $slug): string
    {
        $layouts = config('layouts', []);
        return $layouts[$slug]['name'] ?? ucfirst($slug);
    }
}
