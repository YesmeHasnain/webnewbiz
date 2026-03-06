<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class UnsplashService
{
    private string $unsplashApiUrl = 'https://api.unsplash.com';
    private string $pexelsApiUrl = 'https://api.pexels.com/v1';
    private ?string $unsplashKey;
    private ?string $pexelsKey;

    /**
     * Curated high-quality Unsplash photo IDs mapped by business category.
     * Direct Unsplash CDN URLs — no API key needed, hotlinking allowed per Unsplash TOS.
     */
    private static array $curatedPhotos = [
        'clothing' => [
            'hero' => ['1441986300917-64674bd600d8', '1558618666-fcd25c85f7e7', '1567401893414-76b7b1e5a7a5', '1490481651871-ab68de25d43d', '1523381210434-271e8be1f52b'],
            'about' => ['1558171813-4c088753af8f', '1556742049-0cfed4f6a45d', '1556905055-8f358a7a47b2', '1441984904996-e0b6ba687e04', '1469334031218-e382a71b716b'],
            'services' => ['1551232864-3f0890e580d9', '1445205170230-053b83016050', '1560243563-062bfc001d68', '1556742111-a301076d9d18', '1485230895905-ec40ba36b9bc'],
            'gallery1' => ['1445205170230-053b83016050', '1558618666-fcd25c85f7e7', '1441984904996-e0b6ba687e04'],
            'gallery2' => ['1560243563-062bfc001d68', '1523381210434-271e8be1f52b', '1490481651871-ab68de25d43d'],
            'team' => ['1556742049-0cfed4f6a45d', '1558171813-4c088753af8f', '1469334031218-e382a71b716b'],
        ],
        'restaurant' => [
            'hero' => ['1517248135467-4c7edcad34c4', '1552566626-52f8b828add9', '1414235077428-338989a2e8c0', '1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5'],
            'about' => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1600565193348-f74bd3c7ccdf', '1466978913421-dad2ebd01d17', '1551218808-94e220e084d2'],
            'services' => ['1504674900247-0877df9cc836', '1476224203421-9ac39bcb3327', '1565299624946-b28f40a0ae38', '1540189549336-e6e99c3679fe', '1555939594-58d7cb561ad1'],
            'gallery1' => ['1414235077428-338989a2e8c0', '1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5'],
            'gallery2' => ['1476224203421-9ac39bcb3327', '1565299624946-b28f40a0ae38', '1540189549336-e6e99c3679fe'],
            'team' => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1551218808-94e220e084d2'],
        ],
        'technology' => [
            'hero' => ['1518770660439-4636190af475', '1451187580459-43490279c0fa', '1526374965328-7f61d4dc18c5', '1550751827-4bd374c3f58b', '1504384764586-bb4cdc1812f0'],
            'about' => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1600880292203-757bb62b4baf', '1519389950473-47ba0277781c', '1556761175-4b46a572b786'],
            'services' => ['1460925895917-afdab827c52f', '1504639725590-34d0984388bd', '1555066931-4365d14bab8c', '1498050108023-c5249f4df085', '1517694712202-14dd9538aa97'],
            'gallery1' => ['1526374965328-7f61d4dc18c5', '1550751827-4bd374c3f58b', '1504384764586-bb4cdc1812f0'],
            'gallery2' => ['1504639725590-34d0984388bd', '1555066931-4365d14bab8c', '1498050108023-c5249f4df085'],
            'team' => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1519389950473-47ba0277781c'],
        ],
        'health' => [
            'hero' => ['1571019613454-1cb2f99b2d8b', '1576091160399-112ba8d25d1d', '1505751172876-fa1923c5c528', '1544367567-0f2fcb009e0b', '1540206395-68808572332f'],
            'about' => ['1579684385127-1ef15d508118', '1576091160550-2173dba999ef', '1576091160550-2173dba999ef', '1579684385127-1ef15d508118', '1534438327276-14e5300c3a48'],
            'services' => ['1535914254981-b5012eebbd15', '1538108149393-fbbd81895907', '1530497610245-94d3c16cda28', '1559757175-5700dde675bc', '1538108149393-fbbd81895907'],
            'gallery1' => ['1505751172876-fa1923c5c528', '1544367567-0f2fcb009e0b', '1540206395-68808572332f'],
            'gallery2' => ['1535914254981-b5012eebbd15', '1530497610245-94d3c16cda28', '1559757175-5700dde675bc'],
            'team' => ['1579684385127-1ef15d508118', '1576091160550-2173dba999ef', '1534438327276-14e5300c3a48'],
        ],
        'realestate' => [
            'hero' => ['1560518883-ce09059eeffa', '1564013799919-ab600027ffc6', '1512917774080-9991f1c4c750', '1560448204-e02f11c3d0e2', '1600596542815-ffad4c1539a9'],
            'about' => ['1600585154340-be6161a56a0c', '1600607687939-ce8a6c25118c', '1582407947304-fd86f028f716', '1560448075-bb8b16e93e5e', '1560185127-6ed189bf02f4'],
            'services' => ['1558036117-15d82a90b9b1', '1600585154526-990dced4db0d', '1600566753086-00f18fb6b3ea', '1560184897-ae75f418493e', '1560448076-36efba0de3f0'],
            'gallery1' => ['1512917774080-9991f1c4c750', '1560448204-e02f11c3d0e2', '1600596542815-ffad4c1539a9'],
            'gallery2' => ['1600585154526-990dced4db0d', '1600566753086-00f18fb6b3ea', '1560184897-ae75f418493e'],
            'team' => ['1600585154340-be6161a56a0c', '1600607687939-ce8a6c25118c', '1582407947304-fd86f028f716'],
        ],
        'education' => [
            'hero' => ['1523050854058-8df90110c9f1', '1427504494785-3a9ca7044f45', '1503676260728-1c00da094a0b', '1524178232363-1fb2b075b655', '1509062522246-3755977927d7'],
            'about' => ['1522202176988-66273c2fd55f', '1529390079861-591de354faf5', '1571260899304-425eee4c7efc', '1517486808906-6ca8b3f04846', '1524178232363-1fb2b075b655'],
            'services' => ['1488190211105-8b0e65b80b4e', '1513258496099-48168024aec0', '1497633762265-9d179a990aa6', '1501504905252-473c47e087f8', '1456513080510-7bf3a84b82f8'],
            'gallery1' => ['1503676260728-1c00da094a0b', '1524178232363-1fb2b075b655', '1509062522246-3755977927d7'],
            'gallery2' => ['1488190211105-8b0e65b80b4e', '1497633762265-9d179a990aa6', '1501504905252-473c47e087f8'],
            'team' => ['1522202176988-66273c2fd55f', '1529390079861-591de354faf5', '1517486808906-6ca8b3f04846'],
        ],
        'beauty' => [
            'hero' => ['1560066984-138dadb4c035', '1522335789203-aabd1fc54bc9', '1487412912498-0447578fcca8', '1596462502278-27bfdc403348', '1570172619644-dfd03ed5d881'],
            'about' => ['1519699047748-de8e457a634e', '1516975080664-ed2fc6a32937', '1570172619644-dfd03ed5d881', '1596462502278-27bfdc403348', '1560066984-138dadb4c035'],
            'services' => ['1522335789203-aabd1fc54bc9', '1487412912498-0447578fcca8', '1516975080664-ed2fc6a32937', '1596462502278-27bfdc403348', '1560066984-138dadb4c035'],
            'gallery1' => ['1487412912498-0447578fcca8', '1596462502278-27bfdc403348', '1570172619644-dfd03ed5d881'],
            'gallery2' => ['1519699047748-de8e457a634e', '1516975080664-ed2fc6a32937', '1560066984-138dadb4c035'],
            'team' => ['1522335789203-aabd1fc54bc9', '1570172619644-dfd03ed5d881', '1596462502278-27bfdc403348'],
        ],
        'fitness' => [
            'hero' => ['1534438327276-14e5300c3a48', '1517836357463-d25dfeac3438', '1534438327276-14e5300c3a48', '1544367567-0f2fcb009e0b', '1540206395-68808572332f'],
            'about' => ['1571388208497-71bedc66e932', '1518611012118-696072aa579a', '1574680178050-55c6a6a96e0a', '1534438327276-14e5300c3a48', '1534438327276-14e5300c3a48'],
            'services' => ['1517836357463-d25dfeac3438', '1574680178050-55c6a6a96e0a', '1571388208497-71bedc66e932', '1518611012118-696072aa579a', '1534438327276-14e5300c3a48'],
            'gallery1' => ['1544367567-0f2fcb009e0b', '1540206395-68808572332f', '1517836357463-d25dfeac3438'],
            'gallery2' => ['1574680178050-55c6a6a96e0a', '1571388208497-71bedc66e932', '1518611012118-696072aa579a'],
            'team' => ['1571388208497-71bedc66e932', '1518611012118-696072aa579a', '1534438327276-14e5300c3a48'],
        ],
        'food' => [
            'hero' => ['1504674900247-0877df9cc836', '1476224203421-9ac39bcb3327', '1565299624946-b28f40a0ae38', '1555939594-58d7cb561ad1', '1540189549336-e6e99c3679fe'],
            'about' => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1600565193348-f74bd3c7ccdf', '1466978913421-dad2ebd01d17', '1551218808-94e220e084d2'],
            'services' => ['1414235077428-338989a2e8c0', '1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5', '1517248135467-4c7edcad34c4', '1552566626-52f8b828add9'],
            'gallery1' => ['1565299624946-b28f40a0ae38', '1555939594-58d7cb561ad1', '1540189549336-e6e99c3679fe'],
            'gallery2' => ['1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5', '1517248135467-4c7edcad34c4'],
            'team' => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1466978913421-dad2ebd01d17'],
        ],
        'consulting' => [
            'hero' => ['1454165804606-c3d57bc86b40', '1497366216548-37526070297c', '1552664730-d307ca884978', '1556761175-4b46a572b786', '1542744173-8e7e53415bb0'],
            'about' => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1600880292203-757bb62b4baf', '1519389950473-47ba0277781c', '1573497019940-1c28c88b4f3e'],
            'services' => ['1460925895917-afdab827c52f', '1531973576160-7125b8386882', '1521737604893-d14cc237f11d', '1542744173-8e7e53415bb0', '1553877522-43269d4ea984'],
            'gallery1' => ['1552664730-d307ca884978', '1556761175-4b46a572b786', '1542744173-8e7e53415bb0'],
            'gallery2' => ['1531973576160-7125b8386882', '1521737604893-d14cc237f11d', '1573497019940-1c28c88b4f3e'],
            'team' => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1519389950473-47ba0277781c'],
        ],
        'default' => [
            'hero' => ['1497366216548-37526070297c', '1454165804606-c3d57bc86b40', '1486406146926-c627a92ad1ab', '1557804506-669a67965ba0', '1556761175-4b46a572b786'],
            'about' => ['1522071820081-009f0129c71c', '1521737604893-d14cc237f11d', '1600880292203-757bb62b4baf', '1553877522-43269d4ea984', '1573497019940-1c28c88b4f3e'],
            'services' => ['1460925895917-afdab827c52f', '1531973576160-7125b8386882', '1504639725590-34d0984388bd', '1555066931-4365d14bab8c', '1498050108023-c5249f4df085'],
            'gallery1' => ['1486406146926-c627a92ad1ab', '1557804506-669a67965ba0', '1556761175-4b46a572b786'],
            'gallery2' => ['1531973576160-7125b8386882', '1504639725590-34d0984388bd', '1555066931-4365d14bab8c'],
            'team' => ['1522071820081-009f0129c71c', '1521737604893-d14cc237f11d', '1573497019940-1c28c88b4f3e'],
        ],
    ];

    public function __construct()
    {
        $this->unsplashKey = config('services.unsplash.access_key');
        $this->pexelsKey = config('services.pexels.api_key');
    }

    /**
     * Search for a photo and download it to the specified directory.
     * Tries: 1) Unsplash API, 2) Pexels API
     */
    public function searchAndDownload(string $query, string $destDir, ?string $filename = null, string $orientation = 'landscape'): ?string
    {
        $url = $this->searchPhoto($query, $orientation);
        if (!$url) return null;

        return $this->downloadImage($url, $destDir, $filename);
    }

    /**
     * Search for a photo URL using available APIs.
     */
    public function searchPhoto(string $query, string $orientation = 'landscape'): ?string
    {
        // Try Unsplash API first
        if ($this->unsplashKey) {
            $url = $this->searchUnsplash($query, $orientation);
            if ($url) return $url;
        }

        // Try Pexels API
        if ($this->pexelsKey) {
            $url = $this->searchPexels($query, $orientation);
            if ($url) return $url;
        }

        return null;
    }

    public function searchUnsplash(string $query, string $orientation): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => "Client-ID {$this->unsplashKey}"])
                ->get("{$this->unsplashApiUrl}/search/photos", [
                    'query' => $query,
                    'per_page' => 5,
                    'orientation' => $orientation,
                ]);

            if ($response->successful()) {
                $results = $response->json('results', []);
                if (!empty($results)) {
                    $photo = $results[array_rand($results)];
                    $url = $photo['urls']['raw'] ?? $photo['urls']['regular'] ?? null;
                    if (!$url) return null;
                    // Append crop params for consistent 3:2 landscape aspect ratio
                    return $url . '&w=1200&h=800&fit=crop&auto=format&q=80';
                }
            }
        } catch (\Exception $e) {
            Log::warning("Unsplash API error: {$e->getMessage()}");
        }

        return null;
    }

    public function searchPexels(string $query, string $orientation): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['Authorization' => $this->pexelsKey])
                ->get("{$this->pexelsApiUrl}/search", [
                    'query' => $query,
                    'per_page' => 5,
                    'orientation' => $orientation,
                ]);

            if ($response->successful()) {
                $photos = $response->json('photos', []);
                if (!empty($photos)) {
                    $photo = $photos[array_rand($photos)];
                    return $photo['src']['landscape'] ?? $photo['src']['large'] ?? $photo['src']['original'] ?? null;
                }
            }
        } catch (\Exception $e) {
            Log::warning("Pexels API error: {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Get a curated Unsplash photo URL based on business category.
     * No API key needed — uses direct Unsplash CDN URLs.
     */
    /** Track used photo IDs to avoid duplicates within a single website build */
    private array $usedPhotoIds = [];

    private function getCuratedUrl(string $businessType, string $imageKey, string $orientation = 'landscape'): string
    {
        $category = $this->matchCategory($businessType);

        // For gallery3-6, fall back to other section pools for variety
        $galleryFallbacks = [
            'gallery3' => 'about',
            'gallery4' => 'services',
            'gallery5' => 'hero',
            'gallery6' => 'about',
        ];
        $lookupKey = $imageKey;
        if (!isset(self::$curatedPhotos[$category][$imageKey]) && isset($galleryFallbacks[$imageKey])) {
            $lookupKey = $galleryFallbacks[$imageKey];
        }

        $photos = self::$curatedPhotos[$category][$lookupKey] ?? self::$curatedPhotos['default'][$lookupKey] ?? self::$curatedPhotos['default']['hero'];

        // Pick a photo that hasn't been used yet (avoid duplicates across sections)
        $available = array_diff($photos, $this->usedPhotoIds);
        if (empty($available)) {
            $available = $photos; // All used, allow repeats as last resort
        }
        $photoId = $available[array_rand($available)];
        $this->usedPhotoIds[] = $photoId;

        $w = $orientation === 'portrait' ? 800 : 1200;
        $h = $orientation === 'portrait' ? 1200 : 800;

        return "https://images.unsplash.com/photo-{$photoId}?w={$w}&h={$h}&fit=crop&auto=format&q=80";
    }

    /**
     * Match a business type string to a curated category.
     */
    private function matchCategory(string $businessType): string
    {
        $type = strtolower($businessType);

        // Order matters: more specific categories first to avoid false matches
        $mapping = [
            'beauty' => ['beauty', 'salon', 'spa', 'cosmetic', 'skincare', 'hair', 'makeup', 'nail', 'barber'],
            'clothing' => ['clothing', 'fashion', 'apparel', 'boutique', 'garment', 'textile', 'wear', 'dress', 'shirt'],
            'restaurant' => ['restaurant', 'cafe', 'bistro', 'diner', 'eatery', 'grill', 'bakery', 'pizza', 'sushi'],
            'food' => ['food', 'catering', 'chef', 'cook', 'meal', 'delivery', 'grocery', 'organic'],
            'fitness' => ['fitness', 'gym', 'workout', 'yoga', 'sport', 'athletic', 'crossfit', 'personal train'],
            'health' => ['health', 'medical', 'clinic', 'hospital', 'dental', 'pharmacy', 'wellness', 'doctor', 'therapy'],
            'realestate' => ['real estate', 'property', 'realty', 'housing', 'apartment', 'construction', 'architect'],
            'education' => ['education', 'school', 'university', 'academy', 'training', 'course', 'tutor', 'learning'],
            'technology' => ['tech', 'software', 'saas', 'digital', 'startup', 'cloud', 'cyber', 'programming', 'developer'],
            'consulting' => ['consulting', 'agency', 'marketing', 'finance', 'accounting', 'legal', 'law', 'insurance'],
        ];

        foreach ($mapping as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($type, $keyword)) {
                    return $category;
                }
            }
        }

        return 'default';
    }

    /**
     * Download an image from URL and save it to the specified directory.
     */
    public function downloadImage(string $url, string $destDir, ?string $filename = null): ?string
    {
        try {
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            $response = Http::timeout(30)->withOptions(['allow_redirects' => true])->get($url);
            if (!$response->successful()) return null;

            $body = $response->body();
            if (empty($body) || strlen($body) < 1000) return null;

            $filename = $filename ?: ('stock-' . Str::random(12) . '.jpg');
            $destPath = rtrim($destDir, '/') . '/' . $filename;

            File::put($destPath, $body);
            Log::info("Downloaded image: {$filename} (" . round(strlen($body) / 1024) . "KB)");

            return $destPath;
        } catch (\Exception $e) {
            Log::warning("Failed to download image: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Get multiple images for a website based on business type and user prompt.
     */
    public function getWebsiteImages(string $businessName, string $businessType, string $destDir, ?string $prompt = null): array
    {
        $results = [];
        $hasApiKey = $this->unsplashKey || $this->pexelsKey;
        Log::info("Downloading stock images — Unsplash: " . ($this->unsplashKey ? 'yes' : 'no') . ", Pexels: " . ($this->pexelsKey ? 'yes' : 'no') . ", Curated fallback: yes");

        // Build search queries using business type + prompt for relevant results
        $promptContext = $prompt ? " {$prompt}" : '';
        $searches = [
            'hero' => ["{$businessType}{$promptContext}", 'landscape'],
            'about' => ["{$businessType} team office professional", 'landscape'],
            'services' => ["{$businessType} services products work", 'landscape'],
            'gallery1' => ["{$businessType} workspace modern", 'landscape'],
            'gallery2' => ["{$businessType} results success", 'landscape'],
            'gallery3' => ["{$businessType} interior design", 'landscape'],
            'gallery4' => ["{$businessType} customers happy", 'landscape'],
            'gallery5' => ["{$businessType} products showcase", 'landscape'],
            'gallery6' => ["{$businessType} professional quality", 'landscape'],
            'team' => ["professional team business people", 'landscape'],
        ];

        foreach ($searches as $key => [$query, $orient]) {
            // Try API search first (Unsplash -> Pexels)
            if ($hasApiKey) {
                $url = $this->searchPhoto($query, $orient);
                if ($url) {
                    $path = $this->downloadImage($url, $destDir, "{$key}.jpg");
                    if ($path) {
                        $results[$key] = $path;
                        continue;
                    }
                }
            }

            // Fallback: curated Unsplash photos by business type (no API key needed)
            $curatedUrl = $this->getCuratedUrl($businessType, $key, $orient);
            $path = $this->downloadImage($curatedUrl, $destDir, "{$key}.jpg");
            if ($path) {
                $results[$key] = $path;
            }
        }

        Log::info("Got " . count($results) . " stock images for '{$businessName}' (type: {$businessType})");
        return $results;
    }

    /**
     * Generate a text-based SVG logo when Ideogram is unavailable.
     */
    public function generateFallbackLogo(string $businessName, string $primaryColor, string $destDir): ?string
    {
        try {
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            $name = htmlspecialchars($businessName, ENT_XML1);
            $initial = mb_strtoupper(mb_substr(trim($businessName), 0, 1));
            $color = $primaryColor ?: '#2563eb';

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 100" width="400" height="100">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$color};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$color};stop-opacity:0.7" />
    </linearGradient>
  </defs>
  <rect x="10" y="15" width="70" height="70" rx="14" fill="url(#grad)"/>
  <text x="45" y="66" font-family="Arial, Helvetica, sans-serif" font-size="42" font-weight="bold" fill="white" text-anchor="middle">{$initial}</text>
  <text x="100" y="62" font-family="Arial, Helvetica, sans-serif" font-size="30" font-weight="bold" fill="#1a1a1a">{$name}</text>
</svg>
SVG;

            $destPath = rtrim($destDir, '/') . '/logo.svg';
            File::put($destPath, $svg);
            Log::info("Generated fallback SVG logo for '{$businessName}'");

            return $destPath;
        } catch (\Exception $e) {
            Log::warning("Failed to generate fallback logo: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Generate a text-based SVG favicon when Ideogram is unavailable.
     */
    public function generateFallbackFavicon(string $businessName, string $primaryColor, string $destDir): ?string
    {
        try {
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }

            $initial = mb_strtoupper(mb_substr(trim($businessName), 0, 1));
            $color = $primaryColor ?: '#2563eb';

            $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  <rect width="64" height="64" rx="12" fill="{$color}"/>
  <text x="32" y="45" font-family="Arial, Helvetica, sans-serif" font-size="38" font-weight="bold" fill="white" text-anchor="middle">{$initial}</text>
</svg>
SVG;

            $destPath = rtrim($destDir, '/') . '/favicon.svg';
            File::put($destPath, $svg);
            Log::info("Generated fallback SVG favicon for '{$businessName}'");

            return $destPath;
        } catch (\Exception $e) {
            Log::warning("Failed to generate fallback favicon: {$e->getMessage()}");
            return null;
        }
    }
}
