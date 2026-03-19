<?php

namespace App\Services;

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
     * Keyword-based layout matching.
     */
    public function keywordFallback(string $businessType, string $prompt = '', ?array $layouts = null): string
    {
        $layouts = $layouts ?? config('layouts', []);
        $text = strtolower($businessType . ' ' . $prompt);
        $scores = [];

        foreach ($layouts as $slug => $cfg) {
            $keywords = $cfg['keywords'] ?? [];
            $score = 0;
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $score += strlen($keyword);
                }
            }
            $scores[$slug] = $score;
        }

        arsort($scores);
        $best = array_key_first($scores);

        return ($scores[$best] > 0) ? $best : 'azure'; // azure is the most versatile fallback
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
