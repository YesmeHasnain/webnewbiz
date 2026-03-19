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
            'hero' => ['1441986300917-64674bd600d8', '1489987707025-afc232f7ea0f', '1558618666-fcd25c85f7e7', '1490481651871-ab68de25d43d', '1523381210434-271e8be1f52b'],
            'about' => ['1558171813-4c088753af8f', '1556742049-0cfed4f6a45d', '1483985988355-763728e1935b', '1441984904996-e0b6ba687e04', '1469334031218-e382a71b716b'],
            'services' => ['1551232864-3f0890e580d9', '1445205170230-053b83016050', '1560243563-062bfc001d68', '1567401893414-76b7b1e5a7a5', '1485230895905-ec40ba36b9bc'],
            'gallery1' => ['1445205170230-053b83016050', '1489987707025-afc232f7ea0f', '1441984904996-e0b6ba687e04'],
            'gallery2' => ['1560243563-062bfc001d68', '1523381210434-271e8be1f52b', '1483985988355-763728e1935b'],
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
        // Portrait photos (shared across all categories) — professional headshots
        '_portraits' => [
            'portrait1' => ['1560250097-0b93528c311a', '1507003211169-0a1dd7228f2d', '1472099645785-5658abf4ff4e'],
            'portrait2' => ['1573496359142-b8d87734a5a2', '1580489944761-15a19d654956', '1508214751196-bcfd4ca60f91'],
            'portrait3' => ['1500648767791-00dcc994a43e', '1519085360753-af0119f7cbe7', '1506794778202-cad84cf45f1d'],
            'portrait4' => ['1438761681033-6461ffad8d80', '1544005313-94ddf0286df2', '1487412720507-e7ab37603c6f'],
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
    /** Extra context (business name + prompt) to help match category */
    private ?string $contextHint = null;

    private function getCuratedUrl(string $businessType, string $imageKey, string $orientation = 'landscape'): string
    {
        $category = $this->matchCategory($businessType);

        // Portrait keys always use the shared _portraits pool
        if (str_starts_with($imageKey, 'portrait')) {
            $photos = self::$curatedPhotos['_portraits'][$imageKey] ?? self::$curatedPhotos['_portraits']['portrait1'];
            $available = array_diff($photos, $this->usedPhotoIds);
            if (empty($available)) $available = $photos;
            $photoId = $available[array_rand($available)];
            $this->usedPhotoIds[] = $photoId;
            return "https://images.unsplash.com/photo-{$photoId}?w=800&h=1200&fit=crop&auto=format&q=80";
        }

        // Fallback mapping: new keys → existing pool keys
        $fallbacks = [
            'hero2'    => 'hero',
            'hero3'    => 'hero',
            'about2'   => 'about',
            'feature1' => 'services',
            'feature2' => 'services',
            'feature3' => 'services',
            'gallery3' => 'about',
            'gallery4' => 'services',
            'gallery5' => 'hero',
            'gallery6' => 'about',
        ];
        $lookupKey = $imageKey;
        if (!isset(self::$curatedPhotos[$category][$imageKey]) && isset($fallbacks[$imageKey])) {
            $lookupKey = $fallbacks[$imageKey];
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
        $type = strtolower($businessType . ' ' . ($this->contextHint ?? ''));

        // Order matters: more specific categories first to avoid false matches
        $mapping = [
            'beauty' => ['beauty', 'salon', 'spa', 'cosmetic', 'skincare', 'hair', 'makeup', 'nail', 'barber', 'facial', 'massage', 'wellness', 'petal'],
            'clothing' => ['clothing', 'fashion', 'apparel', 'boutique', 'garment', 'textile', 'wear', 'dress', 'shirt', 'clothify', 'cloth'],
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
        // Set context hint so matchCategory can use name + prompt for better matching
        $this->contextHint = $businessName . ' ' . ($prompt ?? '');
        Log::info("Downloading stock images — Unsplash: " . ($this->unsplashKey ? 'yes' : 'no') . ", Pexels: " . ($this->pexelsKey ? 'yes' : 'no') . ", Curated fallback: yes, category: " . $this->matchCategory($businessType));

        // Determine the visual category for search queries
        $category = $this->matchCategory($businessType);
        $searchTerm = $category !== 'default' ? $category : $businessType;

        // Category-specific search queries for much better image relevance
        $categoryQueries = [
            'beauty' => [
                'hero' => 'luxury spa treatment room candles', 'hero2' => 'beauty salon interior modern', 'hero3' => 'facial treatment spa wellness',
                'about' => 'spa therapist giving massage', 'about2' => 'beauty salon team professionals',
                'services' => 'skincare products luxury display', 'gallery1' => 'woman relaxing spa pool',
                'gallery2' => 'beauty treatment facial closeup', 'gallery3' => 'spa interior zen peaceful',
                'gallery4' => 'manicure pedicure salon', 'gallery5' => 'aromatherapy essential oils candles',
                'gallery6' => 'hair styling salon modern', 'team' => 'spa wellness team portrait',
                'feature1' => 'massage therapy relaxation', 'feature2' => 'facial skincare treatment', 'feature3' => 'nail art manicure',
                'product1' => 'skincare product serum bottle', 'product2' => 'facial cream moisturizer jar', 'product3' => 'essential oils aromatherapy',
                'product4' => 'hair care shampoo products', 'product5' => 'makeup cosmetics lipstick', 'product6' => 'body lotion spa product',
                'product7' => 'nail polish colors beauty', 'product8' => 'beauty gift set box',
            ],
            'restaurant' => [
                'hero' => 'elegant restaurant interior dining', 'hero2' => 'gourmet food plating beautiful', 'hero3' => 'restaurant kitchen chef cooking',
                'about' => 'chef preparing gourmet dish', 'about2' => 'restaurant team kitchen staff',
                'services' => 'gourmet dishes food plating', 'gallery1' => 'restaurant dining ambiance evening',
                'gallery2' => 'delicious food presentation plate', 'gallery3' => 'restaurant bar cocktails drinks',
                'gallery4' => 'outdoor dining restaurant patio', 'gallery5' => 'fresh ingredients cooking kitchen',
                'gallery6' => 'dessert pastry bakery sweet', 'team' => 'restaurant chef team kitchen',
                'feature1' => 'appetizer food dish elegant', 'feature2' => 'main course steak dinner', 'feature3' => 'wine selection sommelier',
                'product1' => 'gourmet burger restaurant food', 'product2' => 'pasta Italian dish plate', 'product3' => 'grilled steak dinner plate',
                'product4' => 'fresh sushi platter Japanese', 'product5' => 'pizza margherita fresh baked', 'product6' => 'chocolate dessert cake sweet',
                'product7' => 'craft cocktail bar drink', 'product8' => 'fresh salad bowl healthy',
            ],
            'food' => [
                'hero' => 'food catering event beautiful', 'hero2' => 'fresh organic ingredients colorful', 'hero3' => 'chef cooking professional kitchen',
                'about' => 'food preparation kitchen professional', 'about2' => 'catering team event setup',
                'services' => 'catering buffet food spread', 'gallery1' => 'food delivery packaging modern',
                'gallery2' => 'fresh vegetables organic market', 'gallery3' => 'bakery bread artisan fresh',
                'gallery4' => 'meal prep healthy food', 'gallery5' => 'food truck street food',
                'gallery6' => 'spices herbs cooking ingredients', 'team' => 'kitchen team cooking together',
                'feature1' => 'food plating gourmet dish', 'feature2' => 'baking pastry dessert', 'feature3' => 'fresh salad healthy meal',
                'product1' => 'meal prep container healthy', 'product2' => 'artisan bread loaf bakery', 'product3' => 'organic juice bottle fresh',
                'product4' => 'cupcake dessert decorated', 'product5' => 'gourmet sauce jar product', 'product6' => 'cheese board charcuterie',
                'product7' => 'fresh fruit basket delivery', 'product8' => 'coffee beans bag premium',
            ],
            'fitness' => [
                'hero' => 'modern gym interior equipment', 'hero2' => 'athlete training workout intense', 'hero3' => 'group fitness class energy',
                'about' => 'personal trainer coaching client', 'about2' => 'gym team trainers fitness',
                'services' => 'crossfit training workout box', 'gallery1' => 'weight training barbell squat',
                'gallery2' => 'yoga class meditation stretching', 'gallery3' => 'running treadmill cardio gym',
                'gallery4' => 'boxing training fitness ring', 'gallery5' => 'kettlebell workout functional',
                'gallery6' => 'gym equipment dumbbells rack', 'team' => 'fitness trainers team portrait',
                'feature1' => 'strength training deadlift', 'feature2' => 'group spinning cycle class', 'feature3' => 'stretching flexibility yoga',
                'product1' => 'protein powder supplement container', 'product2' => 'yoga mat exercise equipment', 'product3' => 'resistance bands workout',
                'product4' => 'dumbbell weights gym equipment', 'product5' => 'water bottle sports fitness', 'product6' => 'gym bag sports training',
                'product7' => 'running shoes sneakers sport', 'product8' => 'fitness tracker watch wearable',
            ],
            'realestate' => [
                'hero' => 'luxury modern house exterior', 'hero2' => 'beautiful home interior living room', 'hero3' => 'residential neighborhood aerial view',
                'about' => 'real estate agent showing home', 'about2' => 'construction workers building site',
                'services' => 'modern kitchen renovation design', 'gallery1' => 'luxury bathroom renovation modern',
                'gallery2' => 'backyard garden landscape pool', 'gallery3' => 'commercial building architecture modern',
                'gallery4' => 'home renovation before after', 'gallery5' => 'apartment building exterior modern',
                'gallery6' => 'construction heavy machinery site', 'team' => 'construction team workers hardhat',
                'feature1' => 'interior design living room', 'feature2' => 'blueprint architecture planning', 'feature3' => 'home inspection quality check',
                'product1' => 'luxury house exterior beautiful', 'product2' => 'modern apartment building city', 'product3' => 'commercial office space interior',
                'product4' => 'villa pool tropical luxury', 'product5' => 'cozy cottage countryside home', 'product6' => 'penthouse city skyline view',
                'product7' => 'townhouse row suburban street', 'product8' => 'warehouse industrial converted loft',
            ],
            'health' => [
                'hero' => 'modern medical clinic interior', 'hero2' => 'doctor patient consultation office', 'hero3' => 'dental clinic modern equipment',
                'about' => 'medical team doctors hospital', 'about2' => 'doctor examining patient care',
                'services' => 'medical equipment stethoscope clean', 'gallery1' => 'dental chair modern clinic',
                'gallery2' => 'pharmacy medicine health care', 'gallery3' => 'hospital corridor modern clean',
                'gallery4' => 'physical therapy rehabilitation', 'gallery5' => 'laboratory medical research test',
                'gallery6' => 'wellness health checkup clinic', 'team' => 'medical team doctors nurses',
                'feature1' => 'eye exam optometry clinic', 'feature2' => 'pediatric care children doctor', 'feature3' => 'medical consultation diagnosis',
                'product1' => 'vitamins supplements bottle health', 'product2' => 'first aid kit medical supplies', 'product3' => 'blood pressure monitor device',
                'product4' => 'thermometer digital medical', 'product5' => 'herbal tea wellness drink', 'product6' => 'essential oils health natural',
                'product7' => 'face mask medical protection', 'product8' => 'health book wellness guide',
            ],
            'technology' => [
                'hero' => 'modern tech office workspace', 'hero2' => 'software developer coding screen', 'hero3' => 'data center server room',
                'about' => 'tech team collaboration meeting', 'about2' => 'startup office modern design',
                'services' => 'laptop code programming development', 'gallery1' => 'mobile app interface design',
                'gallery2' => 'cloud computing technology abstract', 'gallery3' => 'cybersecurity digital protection',
                'gallery4' => 'AI artificial intelligence robot', 'gallery5' => 'circuit board electronics tech',
                'gallery6' => 'smart devices IoT technology', 'team' => 'tech startup team diverse',
                'feature1' => 'web design interface mockup', 'feature2' => 'data analytics dashboard', 'feature3' => 'video conference remote work',
                'product1' => 'laptop computer workspace desk', 'product2' => 'smartphone mobile device modern', 'product3' => 'wireless headphones earbuds tech',
                'product4' => 'smart watch wearable device', 'product5' => 'keyboard mouse desk setup', 'product6' => 'monitor display screen computer',
                'product7' => 'USB drive storage device', 'product8' => 'tablet digital stylus pen',
            ],
            'clothing' => [
                'product1' => 'cotton t-shirt fashion flatlay', 'product2' => 'denim jeans fashion display', 'product3' => 'casual linen shirt menswear',
                'product4' => 'wool blazer jacket formal', 'product5' => 'knit sweater pullover cozy', 'product6' => 'chino shorts summer fashion',
                'product7' => 'leather belt accessories fashion', 'product8' => 'oversized hoodie streetwear',
            ],
            'consulting' => [
                'product1' => 'business strategy meeting whiteboard', 'product2' => 'financial planning documents charts', 'product3' => 'online course laptop learning',
                'product4' => 'coaching session mentoring', 'product5' => 'business book leadership guide', 'product6' => 'workshop seminar conference room',
                'product7' => 'webinar presentation online screen', 'product8' => 'productivity planner notebook pen',
            ],
            'education' => [
                'product1' => 'textbook educational resource', 'product2' => 'online course platform laptop', 'product3' => 'stationery school supplies set',
                'product4' => 'tablet digital learning child', 'product5' => 'art supplies creative materials', 'product6' => 'science kit educational toys',
                'product7' => 'language learning flashcards', 'product8' => 'backpack school bag student',
            ],
        ];

        $queries = $categoryQueries[$category] ?? [];
        $searches = [
            'hero' => [$queries['hero'] ?? "{$searchTerm} professional", 'landscape'],
            'hero2' => [$queries['hero2'] ?? "{$searchTerm} service", 'landscape'],
            'hero3' => [$queries['hero3'] ?? "{$searchTerm} business", 'landscape'],
            'about' => [$queries['about'] ?? "{$searchTerm} team people", 'landscape'],
            'about2' => [$queries['about2'] ?? "{$searchTerm} workspace", 'landscape'],
            'services' => [$queries['services'] ?? "{$searchTerm} products", 'landscape'],
            'gallery1' => [$queries['gallery1'] ?? "{$searchTerm} showcase", 'landscape'],
            'gallery2' => [$queries['gallery2'] ?? "{$searchTerm} lifestyle", 'landscape'],
            'gallery3' => [$queries['gallery3'] ?? "{$searchTerm} interior", 'landscape'],
            'gallery4' => [$queries['gallery4'] ?? "{$searchTerm} project", 'landscape'],
            'gallery5' => [$queries['gallery5'] ?? "{$searchTerm} quality", 'landscape'],
            'gallery6' => [$queries['gallery6'] ?? "{$searchTerm} equipment", 'landscape'],
            'team' => [$queries['team'] ?? "professional team business people", 'landscape'],
            'portrait1' => ["professional headshot business person", 'portrait'],
            'portrait2' => ["professional woman portrait office", 'portrait'],
            'portrait3' => ["professional man portrait suit", 'portrait'],
            'portrait4' => ["business person headshot smiling", 'portrait'],
            'feature1' => [$queries['feature1'] ?? "{$searchTerm} detail", 'landscape'],
            'feature2' => [$queries['feature2'] ?? "{$searchTerm} work", 'landscape'],
            'feature3' => [$queries['feature3'] ?? "{$searchTerm} expert", 'landscape'],
            // Product images for WooCommerce
            'product1' => [$queries['product1'] ?? "{$searchTerm} product closeup", 'squarish'],
            'product2' => [$queries['product2'] ?? "{$searchTerm} item display", 'squarish'],
            'product3' => [$queries['product3'] ?? "{$searchTerm} product showcase", 'squarish'],
            'product4' => [$queries['product4'] ?? "{$searchTerm} product detail", 'squarish'],
            'product5' => [$queries['product5'] ?? "{$searchTerm} item professional", 'squarish'],
            'product6' => [$queries['product6'] ?? "{$searchTerm} product studio", 'squarish'],
            'product7' => [$queries['product7'] ?? "{$searchTerm} service professional", 'squarish'],
            'product8' => [$queries['product8'] ?? "{$searchTerm} work result", 'squarish'],
        ];

        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        // Collect all download URLs first (fast — no network for curated)
        $downloadUrls = [];
        foreach ($searches as $key => [$query, $orient]) {
            if ($hasApiKey) {
                $url = $this->searchPhoto($query, $orient);
                if ($url) {
                    $downloadUrls[$key] = $url;
                    continue;
                }
            }
            $downloadUrls[$key] = $this->getCuratedUrl($businessType, $key, $orient);
        }

        // Download ALL images in parallel using Http::pool
        $responses = Http::pool(function ($pool) use ($downloadUrls) {
            foreach ($downloadUrls as $key => $url) {
                $pool->as($key)->timeout(30)->withOptions(['allow_redirects' => true])->get($url);
            }
        });

        // Save downloaded images to disk
        foreach ($responses as $key => $response) {
            try {
                if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                    $body = $response->body();
                    if (!empty($body) && strlen($body) > 1000) {
                        $destPath = rtrim($destDir, '/') . "/{$key}.jpg";
                        File::put($destPath, $body);
                        $results[$key] = $destPath;
                        Log::info("Downloaded image: {$key}.jpg (" . round(strlen($body) / 1024) . "KB)");
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Failed to save image '{$key}': {$e->getMessage()}");
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
