<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentService
{
    private ?string $apiKey;
    private string $model;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->apiUrl = config('services.gemini.api_url');
    }

    private function generateContent(string $prompt): array
    {
        if (!$this->apiKey) {
            Log::warning('Gemini API not configured');
            return ['success' => false, 'message' => 'Gemini API not configured'];
        }

        try {
            $response = Http::timeout(120)->post(
                "{$this->apiUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topP' => 0.9,
                        'maxOutputTokens' => 8192,
                    ],
                ]
            );

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text', '');
                return ['success' => true, 'data' => $text];
            }

            return ['success' => false, 'message' => 'Gemini API request failed: ' . $response->body()];
        } catch (\Exception $e) {
            Log::error("Gemini API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function generateWebsiteContent(string $businessType, string $businessName, string $style = 'modern'): array
    {
        $prompt = "Generate complete website content for a {$businessType} business named '{$businessName}' with a {$style} style.

Return a JSON object with this exact structure:
{
    \"site_title\": \"Business Name\",
    \"tagline\": \"A catchy tagline\",
    \"pages\": {
        \"home\": {
            \"hero_title\": \"...\",
            \"hero_subtitle\": \"...\",
            \"hero_cta\": \"...\",
            \"sections\": [
                {\"type\": \"features\", \"title\": \"...\", \"items\": [{\"title\": \"...\", \"description\": \"...\", \"icon\": \"...\"}]},
                {\"type\": \"about_preview\", \"title\": \"...\", \"content\": \"...\"},
                {\"type\": \"testimonials\", \"title\": \"...\", \"items\": [{\"name\": \"...\", \"role\": \"...\", \"content\": \"...\"}]},
                {\"type\": \"cta\", \"title\": \"...\", \"subtitle\": \"...\", \"button_text\": \"...\"}
            ]
        },
        \"about\": {
            \"title\": \"About Us\",
            \"content\": \"2-3 paragraphs about the business...\",
            \"mission\": \"...\",
            \"vision\": \"...\"
        },
        \"services\": {
            \"title\": \"Our Services\",
            \"intro\": \"...\",
            \"items\": [{\"title\": \"...\", \"description\": \"...\", \"price\": \"...\"}]
        },
        \"contact\": {
            \"title\": \"Contact Us\",
            \"subtitle\": \"...\",
            \"address\": \"...\",
            \"phone\": \"...\",
            \"email\": \"...\"
        }
    },
    \"colors\": {\"primary\": \"#hex\", \"secondary\": \"#hex\", \"accent\": \"#hex\"},
    \"fonts\": {\"heading\": \"...\", \"body\": \"...\"}
}

Return ONLY valid JSON, no markdown code blocks or extra text.";

        $result = $this->generateContent($prompt);
        if (!$result['success']) return $result;

        try {
            $content = $result['data'];
            // Strip markdown code block if present
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $parsed = json_decode($content, true);

            if (!$parsed) {
                return ['success' => false, 'message' => 'Failed to parse AI response as JSON'];
            }

            return ['success' => true, 'data' => $parsed];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to parse AI content: ' . $e->getMessage()];
        }
    }

    public function generateSeoContent(string $businessType, string $businessName): array
    {
        $prompt = "Generate SEO metadata for a {$businessType} business named '{$businessName}'.
Return JSON: {\"meta_title\": \"...\", \"meta_description\": \"...\", \"keywords\": [\"...\"], \"og_title\": \"...\", \"og_description\": \"...\"}
Return ONLY valid JSON.";

        $result = $this->generateContent($prompt);
        if (!$result['success']) return $result;

        try {
            $content = preg_replace('/^```json\s*/', '', $result['data']);
            $content = preg_replace('/\s*```$/', '', $content);
            $parsed = json_decode($content, true);
            return $parsed ? ['success' => true, 'data' => $parsed] : ['success' => false, 'message' => 'Failed to parse SEO content'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
