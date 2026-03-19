<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\AnthropicService;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandingController extends Controller
{
    public function __construct(
        private WpBridgeService $bridge,
        private AnthropicService $ai,
    ) {}

    /**
     * GET /websites/{websiteId}/branding/logo
     */
    public function getLogo(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->getLogo($website));
    }

    /**
     * POST /websites/{websiteId}/branding/logo
     * Accepts: file upload (logo) or image_url
     */
    public function uploadLogo(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);

        $imageUrl = '';

        if ($request->hasFile('logo')) {
            $imageUrl = $this->storeUploadedLogo($request, $website);
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->input('image_url');
        } else {
            return response()->json(['success' => false, 'error' => 'No logo file or URL provided'], 422);
        }

        return response()->json($this->bridge->uploadLogo($website, $imageUrl));
    }

    /**
     * DELETE /websites/{websiteId}/branding/logo
     */
    public function removeLogo(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->removeLogo($website));
    }

    /**
     * POST /websites/{websiteId}/branding/logo/generate
     * Uses AI to generate an SVG logo based on business info
     */
    public function generateLogo(Request $request, $websiteId)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'style' => 'nullable|string|max:100',
            'colors' => 'nullable|string|max:255',
        ]);

        $website = $request->user()->websites()->findOrFail($websiteId);

        $businessName = $request->input('business_name');
        $style = $request->input('style', 'modern minimal');
        $colors = $request->input('colors', '');
        $businessType = $website->business_type ?? '';

        $prompt = "Generate a professional SVG logo for a business called \"{$businessName}\".
Business type: {$businessType}
Style: {$style}
" . ($colors ? "Color preferences: {$colors}\n" : '') . "

Requirements:
- Output ONLY the SVG code, nothing else. No markdown, no explanation.
- The SVG should be a clean, professional logo
- Use a viewBox of \"0 0 200 60\" for a horizontal logo
- Include the business name as text in a modern font
- Add a simple icon/symbol relevant to the business type on the left
- Use clean geometric shapes
- Make it look professional and modern
- Use 2-3 colors maximum
- The SVG must be self-contained (no external fonts - use system fonts like Arial, Helvetica)
- Keep it simple and recognizable at small sizes";

        try {
            $result = $this->ai->chat(
                [['role' => 'user', 'content' => $prompt]],
                'You are a professional logo designer. You output only valid SVG code. Never include markdown code blocks, explanations, or anything other than the raw SVG markup.',
                2048
            );

            if (!$result['success']) {
                return response()->json(['success' => false, 'error' => 'AI generation failed: ' . ($result['message'] ?? 'Unknown error')], 500);
            }

            $svgContent = trim($result['content'] ?? '');

            // Clean up: extract SVG if wrapped in markdown code blocks
            if (preg_match('/<svg[\s\S]*<\/svg>/i', $svgContent, $matches)) {
                $svgContent = $matches[0];
            }

            if (!str_starts_with($svgContent, '<svg')) {
                return response()->json(['success' => false, 'error' => 'AI did not return valid SVG'], 500);
            }

            // Apply to WordPress
            $applyResult = $this->bridge->applyGeneratedLogo($website, $svgContent);

            return response()->json([
                'success' => true,
                'data' => array_merge($applyResult['data'] ?? [], [
                    'svg_preview' => $svgContent,
                ]),
            ]);
        } catch (\Throwable $e) {
            Log::error("Logo generation failed for site {$website->slug}: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Logo generation failed'], 500);
        }
    }

    private function storeUploadedLogo(Request $request, Website $website): string
    {
        $file = $request->file('logo');
        $filename = 'logo_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $wpUploadsDir = 'C:\\xampp\\htdocs\\' . $website->slug . '\\wp-content\\uploads';
        $subDir = date('Y/m');
        $destDir = $wpUploadsDir . '\\' . $subDir;
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $file->move($destDir, $filename);

        return rtrim($website->url, '/') . '/wp-content/uploads/' . $subDir . '/' . $filename;
    }
}
