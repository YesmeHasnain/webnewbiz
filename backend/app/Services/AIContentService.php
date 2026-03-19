<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIContentService
{
    private ?string $geminiApiKey;
    private string $geminiModel;
    private string $geminiApiUrl;

    private ?string $anthropicApiKey;
    private string $anthropicModel;
    private string $anthropicApiUrl;

    private ?string $glmApiKey;
    private string $glmModel;
    private string $glmApiUrl;

    public function __construct()
    {
        // Gemini config (fallback)
        $this->geminiApiKey = config('services.gemini.api_key');
        $this->geminiModel = config('services.gemini.model', 'gemini-2.0-flash');
        $this->geminiApiUrl = config('services.gemini.api_url');

        // Anthropic/Claude config (primary)
        $this->anthropicApiKey = config('services.anthropic.api_key');
        $this->anthropicModel = config('services.anthropic.model', 'claude-sonnet-4-20250514');
        $this->anthropicApiUrl = config('services.anthropic.api_url', 'https://api.anthropic.com/v1');

        // GLM/Zhipu config (site planner)
        $this->glmApiKey = config('services.glm.api_key');
        $this->glmModel = config('services.glm.model', 'glm-4-flash');
        $this->glmApiUrl = config('services.glm.api_url', 'https://open.bigmodel.cn/api/paas/v4');
    }

    /**
     * Generate content using Claude API (primary) with Gemini fallback.
     */
    public function generateContent(string $prompt, ?string $systemPrompt = null): array
    {
        // Try Claude first
        $claudeResult = $this->generateWithClaude($prompt, $systemPrompt);
        if ($claudeResult['success']) {
            return $claudeResult;
        }

        Log::warning('Claude API failed, falling back to Gemini: ' . ($claudeResult['message'] ?? 'Unknown'));

        // Fallback to Gemini
        return $this->generateWithGemini($prompt);
    }

    /**
     * Generate content using Anthropic Claude API.
     */
    private function generateWithClaude(string $prompt, ?string $systemPrompt = null): array
    {
        if (!$this->anthropicApiKey) {
            Log::warning('Anthropic API not configured');
            return ['success' => false, 'message' => 'Anthropic API not configured'];
        }

        $maxRetries = 3;
        $lastError = '';

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $payload = [
                    'model' => $this->anthropicModel,
                    'max_tokens' => 8192,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ];

                if ($systemPrompt) {
                    $payload['system'] = $systemPrompt;
                }

                $response = Http::timeout(90)
                    ->withHeaders([
                        'x-api-key' => $this->anthropicApiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->anthropicApiUrl}/messages", $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['content'][0]['text'] ?? '';
                    Log::info('Anthropic API content generated successfully');
                    return ['success' => true, 'data' => $text];
                }

                $status = $response->status();
                $lastError = "Anthropic API request failed: {$status}";

                // Retry on 500 (server error), 529 (overloaded), 429 (rate limit)
                if (in_array($status, [500, 529, 429]) && $attempt < $maxRetries) {
                    $delay = $attempt * 2;
                    Log::warning("Anthropic API {$status} on attempt {$attempt}/{$maxRetries}, retrying in {$delay}s");
                    sleep($delay);
                    continue;
                }

                Log::error("Anthropic API failed: {$status} - " . $response->body());
                return ['success' => false, 'message' => $lastError];
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                if ($attempt < $maxRetries) {
                    $delay = $attempt * 2;
                    Log::warning("Anthropic API exception on attempt {$attempt}/{$maxRetries}: {$lastError}, retrying in {$delay}s");
                    sleep($delay);
                    continue;
                }
                Log::error("Anthropic API error after {$maxRetries} attempts: {$lastError}");
                return ['success' => false, 'message' => $lastError];
            }
        }

        return ['success' => false, 'message' => $lastError];
    }

    /**
     * Generate content using Gemini API (fallback).
     */
    private function generateWithGemini(string $prompt): array
    {
        if (!$this->geminiApiKey) {
            Log::warning('Gemini API not configured');
            return ['success' => false, 'message' => 'Gemini API not configured'];
        }

        try {
            $response = Http::timeout(60)->post(
                "{$this->geminiApiUrl}/models/{$this->geminiModel}:generateContent?key={$this->geminiApiKey}",
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
                Log::info('Gemini API content generated successfully (fallback)');
                return ['success' => true, 'data' => $text];
            }

            return ['success' => false, 'message' => 'Gemini API request failed: ' . $response->body()];
        } catch (\Exception $e) {
            Log::error("Gemini API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function generateWebsiteContent(string $businessType, string $businessName, string $style = 'modern', ?array $pagesStructure = null, ?string $userPrompt = null): array
    {
        // Build dynamic page instructions based on user's chosen structure
        $pagesInstruction = '';
        if ($pagesStructure && count($pagesStructure) > 0) {
            $pagesInstruction = "The user has chosen the following pages and sections for their website. Generate content for EXACTLY these pages with these section types:\n\n";
            foreach ($pagesStructure as $page) {
                $title = $page['title'] ?? 'Page';
                $slug = $page['slug'] ?? strtolower(str_replace(' ', '-', $title));
                $sections = $page['sections'] ?? [];
                $sectionList = implode(', ', array_map(fn($s) => ($s['type'] ?? 'content') . ' ("' . ($s['label'] ?? '') . '")', $sections));
                $pagesInstruction .= "- Page \"{$title}\" (slug: {$slug}): sections = [{$sectionList}]\n";
            }
            $pagesInstruction .= "\nFor each page, use the slug as the key in the pages object. For the home page, include hero_title, hero_subtitle, hero_cta at the top level, and additional sections in a \"sections\" array. For other pages, use title, content, and appropriate fields based on section types.\n\nSection type mappings:\n- hero: page header with title\n- features: items array with title, description, icon\n- about_preview: content paragraph\n- testimonials: items array with name, role, content\n- cta: title, subtitle, button_text\n- gallery: items array with title, description\n- pricing: items array with title, description, price, features\n- team: items array with name, role, description\n- faq: items array with question, answer\n- contact_form: subtitle, address, phone, email\n- content: text content paragraph\n- stats: items array with title (number), description\n- process: items array with title, description (sequential steps like 'How It Works')\n";
        }

        $defaultPages = '';
        if (!$pagesInstruction) {
            $defaultPages = '
    "pages": {
        "home": {
            "hero_title": "...",
            "hero_subtitle": "...",
            "hero_cta": "...",
            "sections": [
                {"type": "features", "title": "...", "items": [{"title": "...", "description": "...", "icon": "..."}, ...6 items]},
                {"type": "stats", "title": "...", "items": [{"title": "500+", "description": "Projects Completed"}, ...4 items]},
                {"type": "about_preview", "title": "...", "content": "..."},
                {"type": "process", "title": "How It Works", "items": [{"title": "Step 1 Title", "description": "..."}, ...4 items]},
                {"type": "testimonials", "title": "...", "items": [{"name": "...", "role": "...", "content": "..."}, ...4 items]},
                {"type": "cta", "title": "...", "subtitle": "...", "button_text": "..."}
            ]
        },
        "about": {
            "title": "About Us",
            "content": "2-3 paragraphs about the business...",
            "mission": "...",
            "vision": "..."
        },
        "services": {
            "title": "Our Services",
            "intro": "...",
            "items": [{"title": "...", "description": "...", "price": "..."}, ...6 items]
        },
        "contact": {
            "title": "Contact Us",
            "subtitle": "...",
            "address": "...",
            "phone": "...",
            "email": "..."
        }
    },';
        }

        $structureExample = $defaultPages ? "
Return a JSON object with this structure:
{
    \"site_title\": \"Business Name\",
    \"tagline\": \"A catchy tagline\",{$defaultPages}
    \"colors\": {\"primary\": \"#hex\", \"secondary\": \"#hex\", \"accent\": \"#hex\"},
    \"fonts\": {\"heading\": \"...\", \"body\": \"...\"}
}" : "
Return a JSON object with this structure:
{
    \"site_title\": \"Business Name\",
    \"tagline\": \"A catchy tagline\",
    \"pages\": {
        \"<slug>\": {
            \"title\": \"Page Title\",
            \"hero_title\": \"...\",
            \"hero_subtitle\": \"...\",
            \"hero_cta\": \"...\",
            \"content\": \"...\",
            \"sections\": [
                {\"type\": \"<section_type>\", \"title\": \"Section Title\", \"items\": [{\"title\": \"...\", \"description\": \"...\"}], \"content\": \"...\"}
            ]
        }
    },
    \"colors\": {\"primary\": \"#hex\", \"secondary\": \"#hex\", \"accent\": \"#hex\"},
    \"fonts\": {\"heading\": \"...\", \"body\": \"...\"}
}";

        // Build the user's description context
        $userContext = '';
        if ($userPrompt && trim($userPrompt)) {
            $userContext = "\n\nUSER'S DESCRIPTION OF THEIR WEBSITE:\n\"{$userPrompt}\"\n\nThis is the most important input. Use this description to guide ALL content generation — the tone, terminology, specific services/products mentioned, target audience, and unique selling points should all reflect what the user described. Do NOT generate generic placeholder content. Generate content that feels like it was written specifically for this business based on their description.";
        }

        // Style-specific color guidance
        $styleColorGuide = match ($style) {
            'modern', 'minimal' => 'Use clean, professional colors. Think blues, grays, and whites. Keep it sleek.',
            'bold' => 'Use bold, high-contrast colors. Think vibrant primary colors with strong accent. Make it eye-catching.',
            'classic', 'elegant' => 'Use refined, timeless colors. Think navy, gold, burgundy, or deep green. Keep it sophisticated.',
            'creative' => 'Use vibrant, playful colors. Think bright purples, pinks, teals, or coral. Make it artistic and fun.',
            'luxury' => 'Use luxurious, premium colors. Think black, gold, deep navy, or champagne. Make it feel high-end.',
            'tech', 'neon' => 'Use modern tech colors. Think electric blue, neon green, dark backgrounds with bright accents. Make it futuristic.',
            'warm' => 'Use warm, inviting colors. Think terracotta, amber, sage green, or warm brown. Make it feel cozy and welcoming.',
            'gradient' => 'Use vibrant gradient-friendly color pairs. Think deep indigo to cyan, coral to orange, or teal to emerald. Colors should blend beautifully.',
            'dark' => 'Use dark, moody colors. Think deep charcoal, burgundy, gold accents on dark backgrounds. Make it atmospheric and sophisticated.',
            'industrial' => 'Use raw, industrial colors. Think charcoal, steel gray, safety orange or yellow accents. Make it strong and utilitarian.',
            'beauty' => 'Use soft, feminine colors. Think blush pink, lavender, rose gold, or soft peach. Make it elegant and gentle.',
            'fintech' => 'Use sharp, trustworthy colors. Think deep navy, electric blue, emerald green, or crisp teal. Make it precise and modern.',
            'gallery' => 'Use muted, gallery-inspired colors. Think off-white, warm gray, soft black, with one subtle accent. Let content shine.',
            'clinical' => 'Use clean, medical colors. Think teal, sky blue, fresh green, or soft navy. Make it clean, trustworthy, and calming.',
            'trades' => 'Use heavy, construction colors. Think safety orange, steel blue, dark gray, or high-vis yellow. Make it bold and reliable.',
            'sleek' => 'Use sleek, product-focused colors. Think deep navy, clean white, with a vibrant accent like coral or electric blue. Make it modern and shoppable.',
            'coaching' => 'Use warm, personal brand colors. Think warm gold, sage green, deep plum, or rich teal. Make it inviting and empowering.',
            'academic' => 'Use structured, educational colors. Think deep blue, forest green, warm maroon, or slate. Make it authoritative and trustworthy.',
            'royal', 'heritage', 'crest' => 'Use regal, heritage colors. Think deep burgundy, royal blue, antique gold, or ivory. Make it feel prestigious and timeless.',
            'adventure', 'outdoor', 'drift' => 'Use earthy, adventure colors. Think forest green, burnt sienna, slate blue, or khaki. Make it feel rugged and natural.',
            'chic', 'velvet', 'fashion2' => 'Use sophisticated fashion colors. Think deep plum, champagne, noir, or dusty rose. Make it feel runway-ready and chic.',
            'launch', 'spark', 'startup2' => 'Use energetic startup colors. Think electric indigo, bright cyan, coral, or vivid purple. Make it feel fresh and innovative.',
            'eco', 'green', 'grove' => 'Use natural, eco-friendly colors. Think moss green, earth brown, sky blue, or sage. Make it feel sustainable and organic.',
            'urban', 'metro', 'city' => 'Use urban, metropolitan colors. Think charcoal, concrete gray, taxi yellow, or brick red. Make it feel gritty and cosmopolitan.',
            'tropical', 'resort', 'coral' => 'Use tropical, resort colors. Think turquoise, coral, sandy beige, or sunset orange. Make it feel warm and paradise-like.',
            'gaming', 'pixel' => 'Use digital, gaming colors. Think neon purple, electric green, cyber blue on dark backgrounds. Make it feel high-tech and immersive.',
            'global', 'international', 'mosaic' => 'Use worldly, diverse colors. Think warm earth tones with vibrant accent, terracotta, deep teal, or saffron. Make it feel inclusive and global.',
            'playful', 'kids', 'prism' => 'Use bright, playful colors. Think sunshine yellow, sky blue, grass green, or bubblegum pink. Make it feel fun and energetic.',
            default => 'Choose colors that best match the business type and style.',
        };

        $systemPrompt = "You are an expert website content writer and designer. You generate unique, creative, and highly specific website content. Every website you create must feel completely different from the last one.

CRITICAL RULES FOR DIVERSITY:
- NEVER use generic phrases like \"Welcome to [business name]\" or \"Your trusted partner\" as hero titles
- Create unique, compelling hero titles that speak directly to the target audience
- Each business should have a distinct voice and personality in the content
- Colors MUST be different for every website — {$styleColorGuide}
- Testimonials should have diverse, realistic names and specific feedback
- Service/feature descriptions should be detailed and specific to this exact business, not generic
- The tagline must be memorable and unique to this business

You must return ONLY valid JSON. No markdown code blocks, no explanation, no extra text.";

        $prompt = "Generate complete website content for a {$businessType} business named '{$businessName}' with a {$style} style.{$userContext}

{$pagesInstruction}
{$structureExample}

Important rules:
- Page keys in the \"pages\" object MUST use simple slugs: \"home\", \"about\", \"services\", \"contact\" (NOT creative names like \"our-amazing-solutions\")
- Page \"title\" values should be short and clear: \"About Us\", \"Our Services\", \"Contact Us\" (NOT \"Our Integrated Solutions for Startup Success\")
- The home page MUST have hero_title, hero_subtitle, hero_cta at the top level
- The home page MUST have at least 6 sections (features, stats, about_preview, process, testimonials, cta)
- Each section in \"sections\" array must have a \"type\" field matching the section types
- Features sections must have exactly 6 items with title, description, and icon (emoji)
- Stats sections must have exactly 4 items with \"title\" (a number like \"500+\") and \"description\"
- Process sections must have exactly 4 items representing sequential steps
- Testimonials must have exactly 4 items with name, role, and detailed content
- FAQ sections must have items with \"question\" and \"answer\" fields
- Team/pricing sections must have at least 4 items
- Services page items must have at least 6 items with detailed descriptions
- All text content should be professional, specific, and compelling — not generic
- Return ONLY valid JSON, no markdown code blocks or extra text";

        $result = $this->generateContent($prompt, $systemPrompt);
        if (!$result['success']) return $result;

        try {
            $parsed = $this->extractJson($result['data']);

            if (!$parsed) {
                Log::error('Failed to parse AI response as JSON', [
                    'raw_length' => strlen($result['data']),
                    'raw_preview' => substr($result['data'], 0, 500),
                ]);

                // Retry once with a stricter prompt
                Log::info('Retrying AI content generation with stricter JSON prompt');
                $retryResult = $this->generateContent(
                    $prompt . "\n\nCRITICAL: Your previous response was not valid JSON. Return ONLY a raw JSON object starting with { and ending with }. No markdown, no explanation, no code blocks.",
                    $systemPrompt
                );
                if ($retryResult['success']) {
                    $parsed = $this->extractJson($retryResult['data']);
                }
            }

            if (!$parsed) {
                return ['success' => false, 'message' => 'Failed to parse AI response as JSON after retry'];
            }

            return ['success' => true, 'data' => $parsed];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to parse AI content: ' . $e->getMessage()];
        }
    }

    /**
     * Robustly extract a JSON object from AI response text.
     * Handles markdown fences, surrounding text, trailing commas, etc.
     */
    private function extractJson(string $raw): ?array
    {
        $content = trim($raw);

        // Strip markdown code blocks: ```json ... ``` or ``` ... ```
        $content = preg_replace('/^```(?:json|JSON)?\s*\n?/m', '', $content);
        $content = preg_replace('/\n?```\s*$/m', '', $content);
        $content = trim($content);

        // Attempt 1: Direct parse
        $parsed = json_decode($content, true);
        if (is_array($parsed) && !empty($parsed)) {
            return $parsed;
        }

        // Attempt 2: Extract JSON object between first { and last }
        $firstBrace = strpos($content, '{');
        $lastBrace = strrpos($content, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $jsonStr = substr($content, $firstBrace, $lastBrace - $firstBrace + 1);
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed) && !empty($parsed)) {
                return $parsed;
            }

            // Attempt 3: Fix trailing commas before } or ]
            $cleaned = preg_replace('/,\s*([\]}])/m', '$1', $jsonStr);
            $parsed = json_decode($cleaned, true);
            if (is_array($parsed) && !empty($parsed)) {
                return $parsed;
            }
        }

        // Attempt 4: Remove control characters that break JSON
        $cleaned = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $content);
        $firstBrace = strpos($cleaned, '{');
        $lastBrace = strrpos($cleaned, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $jsonStr = substr($cleaned, $firstBrace, $lastBrace - $firstBrace + 1);
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed) && !empty($parsed)) {
                return $parsed;
            }
        }

        return null;
    }

    /**
     * Lightweight single-prompt AI call — returns raw text (no JSON parsing).
     * Tries Gemini first (cheaper/faster), falls back to Claude.
     */
    public function quickComplete(string $prompt): string
    {
        // Try Gemini first
        $result = $this->generateWithGemini($prompt);
        if ($result['success'] && !empty($result['data'])) {
            return trim($result['data']);
        }

        // Fallback to Claude
        $result = $this->generateWithClaude($prompt);
        if ($result['success'] && !empty($result['data'])) {
            return trim($result['data']);
        }

        Log::warning('quickComplete: all AI providers failed');
        return '';
    }

    public function generateProductContent(string $businessType, string $businessName, int $count = 6): array
    {
        $prompt = "Generate {$count} products for an e-commerce {$businessType} store named '{$businessName}'.
Return JSON array: [{\"name\": \"...\", \"description\": \"2-3 sentences\", \"price\": 29.99, \"category\": \"...\", \"image_prompt\": \"product photo description for AI image generation\"}]
Return ONLY valid JSON array, no markdown.";

        $result = $this->generateContent($prompt);
        if (!$result['success']) return [];

        try {
            $parsed = $this->extractJson($result['data']);
            return is_array($parsed) ? $parsed : [];
        } catch (\Exception $e) {
            return [];
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
            $parsed = $this->extractJson($result['data']);
            return $parsed ? ['success' => true, 'data' => $parsed] : ['success' => false, 'message' => 'Failed to parse SEO content'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate content using GLM (Zhipu AI) API.
     */
    private function generateWithGlm(string $prompt, ?string $systemPrompt = null): array
    {
        if (!$this->glmApiKey) {
            Log::warning('GLM API not configured');
            return ['success' => false, 'message' => 'GLM API not configured'];
        }

        try {
            $messages = [];
            if ($systemPrompt) {
                $messages[] = ['role' => 'system', 'content' => $systemPrompt];
            }
            $messages[] = ['role' => 'user', 'content' => $prompt];

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->glmApiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->glmApiUrl}/chat/completions", [
                    'model' => $this->glmModel,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 2048,
                ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content', '');
                Log::info('GLM API content generated successfully');
                return ['success' => true, 'data' => $text];
            }

            Log::error('GLM API failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'GLM API request failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("GLM API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate a site plan (pages + sections) using AI.
     * Fallback chain: GLM → Claude → Gemini → null
     */
    public function generateSitePlan(string $businessType, string $businessName, string $description): array
    {
        $validSections = ['hero', 'features', 'about_preview', 'testimonials', 'cta', 'gallery', 'pricing', 'team', 'faq', 'contact_form', 'content', 'stats', 'process'];

        $sectionLabels = [
            'hero' => 'Hero Banner', 'features' => 'Features', 'about_preview' => 'About Preview',
            'testimonials' => 'Testimonials', 'cta' => 'Call to Action', 'gallery' => 'Gallery',
            'pricing' => 'Pricing Plans', 'team' => 'Our Team', 'faq' => 'FAQ',
            'contact_form' => 'Contact Form', 'content' => 'Text Content', 'stats' => 'Statistics',
            'process' => 'How It Works',
        ];

        $systemPrompt = 'You are a website structure planner. Return ONLY valid JSON, no markdown or explanation.';

        $prompt = "Plan a website structure for a {$businessType} business named \"{$businessName}\".
Business description: \"{$description}\"

Return a JSON array of pages. Rules:
- 4 to 6 pages total
- First page MUST be Home (slug: \"home\") with 5-7 sections
- Last page MUST be Contact (slug: \"contact\")
- Home page must include: hero, features, and at least 3 more from: about_preview, testimonials, cta, stats, process, gallery
- Other pages should be relevant to this specific business type
- Each page has: title (short, clear like \"About Us\"), slug (lowercase, e.g. \"about\"), sections (array)
- Each section has: type (from allowed list below), label (human-readable name, customized for this business)
- Every non-home page should start with a hero section

Allowed section types: hero, features, about_preview, testimonials, cta, gallery, pricing, team, faq, contact_form, content, stats, process

Example format:
[
  {\"title\": \"Home\", \"slug\": \"home\", \"sections\": [{\"type\": \"hero\", \"label\": \"Hero Banner\"}, {\"type\": \"features\", \"label\": \"Our Services\"}, {\"type\": \"stats\", \"label\": \"Key Numbers\"}, {\"type\": \"testimonials\", \"label\": \"Client Reviews\"}, {\"type\": \"cta\", \"label\": \"Get Started\"}]},
  {\"title\": \"About Us\", \"slug\": \"about\", \"sections\": [{\"type\": \"hero\", \"label\": \"Page Header\"}, {\"type\": \"content\", \"label\": \"Our Story\"}, {\"type\": \"team\", \"label\": \"Meet the Team\"}]},
  {\"title\": \"Contact\", \"slug\": \"contact\", \"sections\": [{\"type\": \"hero\", \"label\": \"Page Header\"}, {\"type\": \"contact_form\", \"label\": \"Get in Touch\"}]}
]

Return ONLY the JSON array.";

        // Fallback chain: GLM → Claude → Gemini
        $result = $this->generateWithGlm($prompt, $systemPrompt);
        if (!$result['success']) {
            Log::warning('GLM site plan failed, trying Claude: ' . ($result['message'] ?? ''));
            $result = $this->generateWithClaude($prompt, $systemPrompt);
        }
        if (!$result['success']) {
            Log::warning('Claude site plan failed, trying Gemini: ' . ($result['message'] ?? ''));
            $result = $this->generateWithGemini($prompt);
        }
        if (!$result['success']) {
            Log::error('All AI providers failed for site plan');
            return ['success' => false, 'message' => 'All AI providers failed'];
        }

        // Parse the JSON array response
        $pages = $this->extractJsonArray($result['data']);
        if (!$pages || !is_array($pages) || count($pages) < 2) {
            Log::error('Failed to parse site plan response', ['raw' => substr($result['data'], 0, 500)]);
            return ['success' => false, 'message' => 'Failed to parse site plan'];
        }

        // Validate and sanitize
        $sanitized = [];
        foreach ($pages as $page) {
            if (!isset($page['title'], $page['slug'], $page['sections']) || !is_array($page['sections'])) {
                continue;
            }

            $title = mb_substr(trim($page['title']), 0, 50);
            $slug = preg_replace('/[^a-z0-9-]/', '', strtolower(trim($page['slug'])));
            if (!$slug) continue;

            $sections = [];
            foreach ($page['sections'] as $section) {
                $type = $section['type'] ?? '';
                if (!in_array($type, $validSections)) continue;
                $label = mb_substr(trim($section['label'] ?? $sectionLabels[$type] ?? $type), 0, 60);
                $sections[] = ['type' => $type, 'label' => $label];
            }

            if (empty($sections)) continue;
            $sanitized[] = ['title' => $title, 'slug' => $slug, 'sections' => $sections];
        }

        if (count($sanitized) < 2) {
            return ['success' => false, 'message' => 'Site plan validation failed'];
        }

        return ['success' => true, 'data' => $sanitized];
    }

    /**
     * Extract a JSON array from AI response text.
     */
    private function extractJsonArray(string $raw): ?array
    {
        $content = trim($raw);

        // Strip markdown code blocks
        $content = preg_replace('/^```(?:json|JSON)?\s*\n?/m', '', $content);
        $content = preg_replace('/\n?```\s*$/m', '', $content);
        $content = trim($content);

        // Attempt 1: Direct parse as array
        $parsed = json_decode($content, true);
        if (is_array($parsed) && isset($parsed[0])) {
            return $parsed;
        }

        // Attempt 2: If AI wrapped in an object like {"pages": [...]}
        if (is_array($parsed) && !isset($parsed[0])) {
            foreach ($parsed as $value) {
                if (is_array($value) && isset($value[0])) {
                    return $value;
                }
            }
        }

        // Attempt 3: Extract between [ and ]
        $firstBracket = strpos($content, '[');
        $lastBracket = strrpos($content, ']');
        if ($firstBracket !== false && $lastBracket !== false && $lastBracket > $firstBracket) {
            $jsonStr = substr($content, $firstBracket, $lastBracket - $firstBracket + 1);
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed) && isset($parsed[0])) {
                return $parsed;
            }

            // Fix trailing commas
            $cleaned = preg_replace('/,\s*([\]}])/m', '$1', $jsonStr);
            $parsed = json_decode($cleaned, true);
            if (is_array($parsed) && isset($parsed[0])) {
                return $parsed;
            }
        }

        // Attempt 4: Extract object that contains an array
        $firstBrace = strpos($content, '{');
        $lastBrace = strrpos($content, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $jsonStr = substr($content, $firstBrace, $lastBrace - $firstBrace + 1);
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed)) {
                foreach ($parsed as $value) {
                    if (is_array($value) && isset($value[0])) {
                        return $value;
                    }
                }
            }
        }

        return null;
    }
}
