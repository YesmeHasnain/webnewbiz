<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AIContentService;
use App\Services\Layouts\AbstractLayout;
use App\Services\ThemeMatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BuilderController extends Controller
{
    /**
     * Original analyze endpoint (kept for backward compat with wizard).
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10',
        ]);

        $prompt = $request->prompt;

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        $layouts = config('layouts');
        $layoutSummary = collect($layouts)->map(fn($l, $k) => "$k: {$l['name']} - {$l['style']} style (best for: " . implode(', ', $l['best_for'] ?? $l['keywords'] ?? []) . ")")->implode("\n");

        $systemPrompt = "You are a website planning AI. Analyze the user's business description and return a JSON object with:
- business_name: extracted or suggested business name (string)
- business_type: category like 'restaurant', 'dental clinic', 'tech startup', etc (string)
- features: array of 3-5 key features/services to highlight
- pages: array of pages to create (from: home, about, services, portfolio, contact)
- recommended_layout: one of these layout slugs that BEST matches the business type: noir, ivory, azure, blush, ember, forest, slate, royal, biddut
- reasoning: brief explanation of why this layout fits

Available layouts (PICK THE ONE MOST RELEVANT to the business — do NOT default to azure):
$layoutSummary

IMPORTANT: Match the layout to the business industry. For example:
- Restaurant/food → ember, Fitness/sports → noir, Medical/legal → ivory
- Beauty/wedding → blush, Real estate/construction → forest, Photography/portfolio → slate
- Hotel/luxury → royal, SaaS/tech → azure, Electrical/contractors → biddut
Only use azure if the business is truly a tech/SaaS/digital agency.

Return ONLY valid JSON, no markdown.";

        // Keyword-based layout matcher for fallback/validation
        $matcher = app(ThemeMatcherService::class);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $prompt]]],
                ],
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            $text = $response->json('candidates.0.content.parts.0.text', '{}');
            $analysis = json_decode($text, true) ?: [];

            $analysis['business_name'] = $analysis['business_name'] ?? 'My Business';
            $analysis['business_type'] = $analysis['business_type'] ?? 'business';
            $analysis['features'] = $analysis['features'] ?? ['Professional Services', 'Quality Work', 'Customer Support'];
            $analysis['pages'] = $analysis['pages'] ?? ['home', 'about', 'services', 'contact'];
            $analysis['reasoning'] = $analysis['reasoning'] ?? 'A modern, versatile layout suitable for most businesses.';

            // Validate recommended_layout — use keyword matching if AI gave invalid or no layout
            $validLayouts = array_keys(config('layouts', []));
            if (empty($analysis['recommended_layout']) || !in_array($analysis['recommended_layout'], $validLayouts)) {
                $analysis['recommended_layout'] = $matcher->keywordFallback(
                    $analysis['business_type'],
                    $prompt
                );
            }

            return response()->json($analysis);
        } catch (\Exception $e) {
            Log::warning("Gemini analyze failed: {$e->getMessage()}");

            // Use keyword matching to recommend the best layout
            $matchedLayout = $matcher->keywordFallback('business', $prompt);

            return response()->json([
                'business_name' => 'My Business',
                'business_type' => 'business',
                'features' => ['Professional Services', 'Quality Work', 'Customer Support'],
                'pages' => ['home', 'about', 'services', 'contact'],
                'recommended_layout' => $matchedLayout,
                'reasoning' => 'Layout matched based on your business description.',
            ]);
        }
    }

    /**
     * Analyze prompt + return questions for the modal questionnaire.
     * Questions can be type "text" (for missing info) or "yesno" (for features).
     */
    public function analyzeWithQuestions(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10',
        ]);

        $prompt = $request->prompt;

        try {
            $aiService = app(AIContentService::class);
            $result = $aiService->generateContent(
                "Analyze this business description and generate questions to better understand what the client wants for their website.

Business description: \"{$prompt}\"

Return a JSON object with:
{
    \"business_name\": \"extracted business name or empty string if not found\",
    \"business_type\": \"category like restaurant, dental clinic, tech startup, etc or empty string if unclear\",
    \"questions\": [
        {\"id\": \"q_name\", \"type\": \"text\", \"question\": \"What is your business name?\", \"context\": \"This will be your website title\", \"placeholder\": \"e.g. Sunrise Dental Clinic\"},
        {\"id\": \"q1\", \"type\": \"yesno\", \"question\": \"Do you want to sell products online?\", \"context\": \"We'll add an online store\"}
    ],
    \"suggested_style\": \"noir\"
}

CRITICAL RULES:
1. If the description does NOT contain a clear business/brand name, you MUST add a text question with id \"q_name\" asking for the business name. This should be the FIRST question.
2. If the description does NOT clearly state what products/services the business offers, you MUST add a text question with id \"q_service\" asking what they sell or offer. This should be right after the name question.
3. After text questions, add 3-5 Yes/No questions (type: \"yesno\") about website features.
4. For text questions: include a \"placeholder\" field with an example answer.
5. For yesno questions: no placeholder needed.
6. Yes/No questions should cover: e-commerce, booking/appointments, photo gallery, design preference, customer reviews.
7. Don't ask about things already obvious from the description.
8. Each question max 80 characters.
9. suggested_style must be one of: noir, ivory, azure, blush, ember, forest, slate, royal

Return ONLY valid JSON.",
                "You are a website planning assistant. You analyze what information is missing from the user's description and ask smart questions to fill the gaps."
            );

            if ($result['success']) {
                $parsed = $this->extractJson($result['data']);
                if ($parsed && isset($parsed['questions'])) {
                    $parsed['business_name'] = $parsed['business_name'] ?? '';
                    $parsed['business_type'] = $parsed['business_type'] ?? '';
                    $parsed['suggested_style'] = $parsed['suggested_style'] ?? 'azure';

                    // Ensure type field exists on all questions
                    foreach ($parsed['questions'] as &$q) {
                        $q['type'] = $q['type'] ?? 'yesno';
                    }

                    return response()->json($parsed);
                }
            }
        } catch (\Exception $e) {
            Log::warning("AI question generation failed: {$e->getMessage()}");
        }

        // Fallback: return default questions with text inputs for missing info
        return response()->json($this->fallbackAnalysis($prompt));
    }

    /**
     * Summarize answers and generate build plan.
     */
    public function summarize(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10',
            'business_name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'answers' => 'required|array',
        ]);

        $prompt = $request->prompt;
        $businessName = $request->business_name ?? '';
        $businessType = $request->business_type ?? '';
        $answers = $request->answers;

        // Override business name/type from text answers if provided
        if (!empty($answers['q_name']) && is_string($answers['q_name'])) {
            $businessName = $answers['q_name'];
        }

        // Build enriched description from answers
        $enrichedPrompt = $prompt;

        if (!empty($answers['q_service']) && is_string($answers['q_service'])) {
            $enrichedPrompt .= ' They offer: ' . $answers['q_service'];
        }
        $features = [];
        $pages = ['home', 'about', 'services', 'contact'];

        foreach ($answers as $questionId => $answer) {
            if ($answer === true || $answer === 'yes') {
                // Map common question patterns to features/pages
                $questionText = strtolower($questionId);
                if (str_contains($questionText, 'sell') || str_contains($questionText, 'ecommerce') || str_contains($questionText, 'store') || str_contains($questionText, 'product')) {
                    $features[] = 'Online Store';
                    $enrichedPrompt .= ' The business wants to sell products online.';
                }
                if (str_contains($questionText, 'booking') || str_contains($questionText, 'appointment')) {
                    $features[] = 'Booking System';
                    $enrichedPrompt .= ' The business needs appointment booking.';
                }
                if (str_contains($questionText, 'gallery') || str_contains($questionText, 'photo')) {
                    $features[] = 'Photo Gallery';
                    if (!in_array('portfolio', $pages)) $pages[] = 'portfolio';
                }
                if (str_contains($questionText, 'review') || str_contains($questionText, 'testimonial')) {
                    $features[] = 'Customer Reviews';
                }
                if (str_contains($questionText, 'blog')) {
                    $features[] = 'Blog';
                }
            }
        }

        // Match layout theme
        try {
            $matcher = app(ThemeMatcherService::class);
            $theme = $matcher->match($businessType, $enrichedPrompt);
        } catch (\Exception $e) {
            $theme = 'azure';
        }

        // Generate summary via AI
        $summary = '';
        try {
            $aiService = app(AIContentService::class);
            $answersText = collect($answers)->map(fn($v, $k) => "$k: " . (is_string($v) ? $v : ($v ? 'Yes' : 'No')))->implode(', ');

            $result = $aiService->generateContent(
                "Based on this business description and user preferences, write a brief 2-3 sentence summary of what their website will include.

Business: {$businessName} ({$businessType})
Description: {$prompt}
User preferences: {$answersText}
Selected features: " . implode(', ', $features) . "

Return ONLY the summary text, no JSON, no formatting. Keep it under 100 words.",
                "You are a friendly website builder assistant."
            );

            if ($result['success']) {
                $summary = trim($result['data']);
                // Clean any markdown/quotes
                $summary = trim($summary, '"\'');
            }
        } catch (\Exception $e) {
            Log::warning("Summary generation failed: {$e->getMessage()}");
        }

        if (!$summary) {
            $summary = "We'll build a professional {$businessType} website for {$businessName} with a beautiful design, "
                . (count($features) > 0 ? implode(', ', array_slice($features, 0, 3)) . ', and more.' : 'all the essential features you need.');
        }

        return response()->json([
            'business_name' => $businessName,
            'business_type' => $businessType,
            'summary' => $summary,
            'features' => $features ?: ['Professional Design', 'Mobile Responsive', 'Contact Form', 'SEO Optimized'],
            'pages' => $pages,
            'theme' => $theme,
        ]);
    }

    /**
     * Enhance a rough/short prompt into a detailed business description using AI.
     */
    public function enhancePrompt(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:3|max:1000',
        ]);

        $prompt = $request->prompt;

        try {
            $aiService = app(AIContentService::class);
            $result = $aiService->generateContent(
                "The user typed a rough/short business description for an AI website builder. Enhance it into a detailed, well-written description (2-4 sentences, max 300 characters) that would help an AI build the perfect website.

User's input: \"{$prompt}\"

Rules:
1. Keep the same meaning and intent — don't invent facts
2. Make it specific and descriptive
3. Include industry, location (if mentioned), key services/products
4. Write in third person (\"A modern...\", \"An established...\")
5. Keep it natural, not robotic
6. Return ONLY the enhanced text, nothing else — no quotes, no JSON, no explanation",
                "You are a copywriting assistant that enhances rough business descriptions into clear, detailed prompts."
            );

            if ($result['success']) {
                $enhanced = trim($result['data'], " \t\n\r\0\x0B\"'");
                return response()->json([
                    'success' => true,
                    'enhanced' => $enhanced,
                ]);
            }
        } catch (\Exception $e) {
            Log::warning("Prompt enhancement failed: {$e->getMessage()}");
        }

        return response()->json([
            'success' => false,
            'enhanced' => $prompt,
        ]);
    }

    public function layouts()
    {
        $layouts = config('layouts');

        $result = [];
        foreach ($layouts as $slug => $config) {
            $result[] = [
                'slug' => $slug,
                'name' => $config['name'] ?? ucfirst($slug),
                'description' => $config['description'] ?? '',
                'style' => $config['style'] ?? 'modern',
                'primary_color' => $config['primary_color'] ?? '#2563EB',
                'keywords' => $config['keywords'] ?? [],
                'fonts' => $config['fonts'] ?? [],
                'best_for' => $config['best_for'] ?? [],
            ];
        }

        return response()->json($result);
    }

    private function fallbackAnalysis(string $prompt): array
    {
        $questions = [];

        // Check if business name is in the prompt (very basic heuristic)
        $hasName = preg_match('/\b(called|named|name is|for my|my company|my business|my shop|my store|my clinic|my restaurant)\b/i', $prompt);
        if (!$hasName) {
            $questions[] = ['id' => 'q_name', 'type' => 'text', 'question' => 'What is your business name?', 'context' => 'This will be your website title', 'placeholder' => 'e.g. Sunrise Dental Clinic'];
        }

        // Check if services/products are described
        $hasService = preg_match('/\b(sell|offer|provide|speciali|service|product|make|deliver|teach|consult)\b/i', $prompt);
        if (!$hasService) {
            $questions[] = ['id' => 'q_service', 'type' => 'text', 'question' => 'What products or services do you offer?', 'context' => "We'll highlight these on your website", 'placeholder' => 'e.g. Teeth whitening, dental implants, cosmetic dentistry'];
        }

        // Standard yes/no feature questions
        $questions = array_merge($questions, [
            ['id' => 'q_ecommerce', 'type' => 'yesno', 'question' => 'Do you want to sell products online?', 'context' => "We'll add an online store to your website"],
            ['id' => 'q_booking', 'type' => 'yesno', 'question' => 'Do you need appointment booking?', 'context' => "We'll add a booking system"],
            ['id' => 'q_gallery', 'type' => 'yesno', 'question' => 'Do you want a photo gallery?', 'context' => "We'll add a portfolio gallery"],
            ['id' => 'q_dark', 'type' => 'yesno', 'question' => 'Do you prefer a dark design style?', 'context' => 'Affects the overall look and feel'],
            ['id' => 'q_reviews', 'type' => 'yesno', 'question' => 'Do you want customer reviews on your site?', 'context' => "We'll add a testimonials section"],
        ]);

        return [
            'business_name' => '',
            'business_type' => '',
            'questions' => $questions,
            'suggested_style' => 'azure',
        ];
    }

    private function extractJson(string $raw): ?array
    {
        $content = trim($raw);
        $content = preg_replace('/^```(?:json|JSON)?\s*\n?/m', '', $content);
        $content = preg_replace('/\n?```\s*$/m', '', $content);
        $content = trim($content);

        $parsed = json_decode($content, true);
        if (is_array($parsed) && !empty($parsed)) return $parsed;

        $firstBrace = strpos($content, '{');
        $lastBrace = strrpos($content, '}');
        if ($firstBrace !== false && $lastBrace !== false && $lastBrace > $firstBrace) {
            $jsonStr = substr($content, $firstBrace, $lastBrace - $firstBrace + 1);
            $parsed = json_decode($jsonStr, true);
            if (is_array($parsed) && !empty($parsed)) return $parsed;
        }

        return null;
    }
}
