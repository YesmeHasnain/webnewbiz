<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Content_Generator {

    private AI_Service $ai;

    public function __construct(AI_Service $ai) {
        $this->ai = $ai;
    }

    /**
     * Generate full website content via AI.
     * Ported from Laravel AIContentService::generateWebsiteContent().
     */
    public function generate_website_content(string $business_type, string $business_name, string $style = 'agency', string $user_prompt = ''): array {

        $style_color_guide = match ($style) {
            'starter', 'vivid'    => 'Use bold, high-contrast colors. Think vibrant primary colors with strong accent. Make it eye-catching.',
            'prestige'            => 'Use luxurious, premium colors. Think black, gold, deep navy, or champagne. Make it feel high-end.',
            'corporate'           => 'Use clean, professional colors. Think blues, grays, and whites. Keep it sleek.',
            'flavor'              => 'Use warm, inviting colors. Think terracotta, amber, sage green, or warm brown. Make it feel cozy and welcoming.',
            'zenith'              => 'Use modern tech colors. Think electric blue, neon green, dark backgrounds with bright accents. Make it futuristic.',
            'agency'              => 'Use vibrant, playful colors. Think bright purples, pinks, teals, or coral. Make it artistic and fun.',
            default               => 'Choose colors that best match the business type and style.',
        };

        $user_context = '';
        if (trim($user_prompt)) {
            $user_context = "\n\nUSER'S DESCRIPTION OF THEIR WEBSITE:\n\"{$user_prompt}\"\n\nThis is the most important input. Use this description to guide ALL content generation — the tone, terminology, specific services/products mentioned, target audience, and unique selling points should all reflect what the user described. Do NOT generate generic placeholder content. Generate content that feels like it was written specifically for this business based on their description.";
        }

        $system_prompt = "You are an expert website content writer and designer. You generate unique, creative, and highly specific website content. Every website you create must feel completely different from the last one.

CRITICAL RULES FOR DIVERSITY:
- NEVER use generic phrases like \"Welcome to [business name]\" or \"Your trusted partner\" as hero titles
- Create unique, compelling hero titles that speak directly to the target audience
- Each business should have a distinct voice and personality in the content
- Colors MUST be different for every website — {$style_color_guide}
- Testimonials should have diverse, realistic names and specific feedback
- Service/feature descriptions should be detailed and specific to this exact business, not generic
- The tagline must be memorable and unique to this business

You must return ONLY valid JSON. No markdown code blocks, no explanation, no extra text.";

        $prompt = "Generate complete website content for a {$business_type} business named '{$business_name}' with a {$style} style.{$user_context}

Return a JSON object with this structure:
{
    \"site_title\": \"Business Name\",
    \"tagline\": \"A catchy tagline\",
    \"pages\": {
        \"home\": {
            \"hero_title\": \"...\",
            \"hero_subtitle\": \"...\",
            \"hero_cta\": \"...\",
            \"sections\": [
                {\"type\": \"features\", \"title\": \"...\", \"items\": [{\"title\": \"...\", \"description\": \"...\", \"icon\": \"...\"}, ...6 items]},
                {\"type\": \"stats\", \"title\": \"...\", \"items\": [{\"title\": \"500+\", \"description\": \"Projects Completed\"}, ...4 items]},
                {\"type\": \"about_preview\", \"title\": \"...\", \"content\": \"...\"},
                {\"type\": \"process\", \"title\": \"How It Works\", \"items\": [{\"title\": \"Step 1 Title\", \"description\": \"...\"}, ...4 items]},
                {\"type\": \"testimonials\", \"title\": \"...\", \"items\": [{\"name\": \"...\", \"role\": \"...\", \"content\": \"...\"}, ...4 items]},
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
            \"items\": [{\"title\": \"...\", \"description\": \"...\", \"price\": \"...\"}, ...6 items]
        },
        \"contact\": {
            \"title\": \"Contact Us\",
            \"subtitle\": \"...\",
            \"address\": \"...\",
            \"phone\": \"...\",
            \"email\": \"...\"
        },
        \"pricing\": {
            \"title\": \"Pricing\",
            \"intro\": \"Short intro about pricing...\",
            \"items\": [{\"title\": \"Plan Name\", \"price\": \"$29/mo\", \"description\": \"Plan description...\", \"features\": [\"Feature 1\", \"Feature 2\", \"Feature 3\", \"Feature 4\"], \"cta\": \"Get Started\"}, ...3 items]
        }
    },
    \"colors\": {\"primary\": \"#hex\", \"secondary\": \"#hex\", \"accent\": \"#hex\"},
    \"fonts\": {\"heading\": \"...\", \"body\": \"...\"}
}

Important rules:
- The home page MUST have hero_title, hero_subtitle, hero_cta at the top level
- The home page MUST have at least 6 sections (features, stats, about_preview, process, testimonials, cta)
- Each section in \"sections\" array must have a \"type\" field matching the section types
- Features sections must have exactly 6 items with title, description, and icon (emoji)
- Stats sections must have exactly 4 items with \"title\" (a number like \"500+\") and \"description\"
- Process sections must have exactly 4 items representing sequential steps
- Testimonials must have exactly 4 items with name, role, and detailed content
- Services page items must have at least 6 items with detailed descriptions
- Pricing page must have exactly 3 items (plans) with title, price, description, features array (4-6 features), and cta button text
- The middle pricing plan should be the recommended/popular one
- All text content should be professional, specific, and compelling — not generic
- Return ONLY valid JSON, no markdown code blocks or extra text";

        $result = $this->ai->generate($prompt, $system_prompt, 8192);
        if (!$result['success']) {
            return $result;
        }

        // Parse JSON response
        $content = $result['data'];
        $content = preg_replace('/^```(?:json)?\s*/s', '', $content);
        $content = preg_replace('/\s*```\s*$/s', '', $content);

        $parsed = json_decode($content, true);
        if (!$parsed || !isset($parsed['pages'])) {
            return ['success' => false, 'message' => 'Failed to parse AI response as valid JSON. Raw: ' . substr($content, 0, 200)];
        }

        return ['success' => true, 'data' => $parsed];
    }
}
