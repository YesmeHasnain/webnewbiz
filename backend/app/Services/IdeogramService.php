<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdeogramService
{
    private ?string $apiKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.ideogram.api_key');
        $this->apiUrl = config('services.ideogram.api_url', 'https://api.ideogram.ai');
    }

    /**
     * Generate an image using Ideogram API.
     *
     * @param string $prompt The image description
     * @param string $aspectRatio e.g. ASPECT_10_16, ASPECT_16_10, ASPECT_1_1
     * @param string $style e.g. REALISTIC, DESIGN, AUTO
     * @return array{success: bool, url?: string, path?: string, message?: string}
     */
    public function generateImage(string $prompt, string $aspectRatio = 'ASPECT_16_10', string $style = 'REALISTIC'): array
    {
        if (!$this->apiKey) {
            Log::warning('Ideogram API not configured');
            return ['success' => false, 'message' => 'Ideogram API not configured'];
        }

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'Api-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->apiUrl}/generate", [
                    'image_request' => [
                        'prompt' => $prompt,
                        'aspect_ratio' => $aspectRatio,
                        'model' => 'V_2',
                        'magic_prompt_option' => 'AUTO',
                        'style_type' => $style,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $imageUrl = $data['data'][0]['url'] ?? null;

                if (!$imageUrl) {
                    return ['success' => false, 'message' => 'No image URL in response'];
                }

                // Download and save locally
                $savedPath = $this->downloadAndSave($imageUrl);

                return [
                    'success' => true,
                    'url' => $imageUrl,
                    'path' => $savedPath,
                ];
            }

            Log::error('Ideogram API request failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'Ideogram API request failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("Ideogram API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate multiple images for a website based on business info.
     *
     * @return array<string, array{success: bool, url?: string, path?: string}>
     */
    public function generateWebsiteImages(string $businessName, string $businessType, string $description, string $style = 'modern'): array
    {
        $results = [];

        $styleHint = match ($style) {
            'modern' => 'clean, modern, minimalist design',
            'classic' => 'warm, classic, timeless aesthetic',
            'minimal' => 'ultra minimal, white space, subtle',
            'bold' => 'bold colors, high contrast, energetic',
            'elegant' => 'luxurious, refined, sophisticated',
            default => 'professional, clean design',
        };

        // Hero image — editorial photography style
        $results['hero'] = $this->generateImage(
            "Editorial photography for a {$businessType} business called '{$businessName}'. Shallow depth of field, cinematic lighting, {$styleHint}. No text, no watermarks, no overlays. High-end professional photo.",
            'ASPECT_16_9',
            'REALISTIC'
        );

        // About image
        $results['about'] = $this->generateImage(
            "Professional image representing a {$businessType} business called '{$businessName}'. Showing the essence of the business, team or workspace. {$styleHint}. Photorealistic, no text.",
            'ASPECT_1_1',
            'REALISTIC'
        );

        // Services/features image
        $results['services'] = $this->generateImage(
            "Professional image showcasing services of a {$businessType} business. {$description}. {$styleHint}. Clean, professional, no text or watermarks.",
            'ASPECT_16_10',
            'REALISTIC'
        );

        return $results;
    }

    /**
     * Generate a logo for a business using Ideogram.
     */
    public function generateLogo(string $businessName, string $businessType, string $style = 'modern'): array
    {
        $styleHint = match ($style) {
            'modern' => 'modern, minimalist',
            'classic' => 'classic, timeless',
            'minimal' => 'ultra minimalist, simple',
            'bold' => 'bold, striking',
            'elegant' => 'elegant, luxury',
            default => 'professional, clean',
        };

        return $this->generateImage(
            "Vector-style flat design logo for '{$businessName}', a {$businessType} business. {$styleHint} style. Solid clean white background, readable text '{$businessName}', 2-3 colors maximum, no borders, no mockups, no 3D effects, no gradients, no shadows. Simple scalable design suitable for website header.",
            'ASPECT_3_1',
            'DESIGN'
        );
    }

    /**
     * Generate a favicon for a business using Ideogram.
     */
    public function generateFavicon(string $businessName, string $businessType, string $style = 'modern'): array
    {
        $initial = strtoupper(substr(trim($businessName), 0, 1));
        return $this->generateImage(
            "Letter '{$initial}' in bold clean design, solid colored background, works perfectly at 32x32 pixels. Flat design, single letter favicon icon, no borders, no 3D, no gradients. Simple recognizable lettermark for a {$businessType} business.",
            'ASPECT_1_1',
            'DESIGN'
        );
    }

    /**
     * Generate multiple images in parallel using Http::pool().
     *
     * @param array $requests Each item: ['key' => string, 'prompt' => string, 'aspect' => string, 'style' => string]
     * @return array<string, array{success: bool, url?: string, path?: string}>
     */
    public function generateBatch(array $requests): array
    {
        if (!$this->apiKey || empty($requests)) {
            return [];
        }

        $results = [];
        $keys = [];

        try {
            $responses = Http::pool(function ($pool) use ($requests, &$keys) {
                foreach ($requests as $i => $req) {
                    $keys[$i] = $req['key'];
                    $pool->as($req['key'])
                        ->timeout(120)
                        ->withHeaders([
                            'Api-Key' => $this->apiKey,
                            'Content-Type' => 'application/json',
                        ])
                        ->post("{$this->apiUrl}/generate", [
                            'image_request' => [
                                'prompt' => $req['prompt'],
                                'aspect_ratio' => $req['aspect'] ?? 'ASPECT_16_10',
                                'model' => 'V_2',
                                'magic_prompt_option' => 'AUTO',
                                'style_type' => $req['style'] ?? 'REALISTIC',
                            ],
                        ]);
                }
            });

            foreach ($responses as $key => $response) {
                if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                    $data = $response->json();
                    $imageUrl = $data['data'][0]['url'] ?? null;
                    if ($imageUrl) {
                        $savedPath = $this->downloadAndSave($imageUrl);
                        $results[$key] = ['success' => true, 'url' => $imageUrl, 'path' => $savedPath];
                        continue;
                    }
                }
                Log::warning("Ideogram batch: '{$key}' failed");
                $results[$key] = ['success' => false, 'message' => 'Request failed'];
            }
        } catch (\Exception $e) {
            Log::error("Ideogram batch error: {$e->getMessage()}");
        }

        return $results;
    }

    /**
     * Download an image from URL and save to a specific directory.
     */
    public function downloadTo(string $url, string $destDir, string $filename): ?string
    {
        try {
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            $contents = Http::timeout(30)->get($url)->body();
            if (empty($contents)) return null;

            $destPath = rtrim($destDir, '/') . '/' . $filename;
            File::put($destPath, $contents);

            return $destPath;
        } catch (\Exception $e) {
            Log::warning("Failed to download image to {$destDir}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Download an image from URL and save to public storage.
     */
    private function downloadAndSave(string $url): ?string
    {
        try {
            $imageContents = Http::timeout(30)->get($url)->body();
            $filename = 'ai-images/' . Str::random(20) . '.png';

            Storage::disk('public')->put($filename, $imageContents);

            return '/storage/' . $filename;
        } catch (\Exception $e) {
            Log::warning("Failed to download AI image: {$e->getMessage()}");
            return null;
        }
    }
}
