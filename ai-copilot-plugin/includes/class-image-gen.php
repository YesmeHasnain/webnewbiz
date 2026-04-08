<?php
/**
 * AI Image Service - DALL-E generation + stock photo search
 * Priority: DALL-E (if key) → Pexels → Pixabay → Lorem Picsum
 */
class AICopilot_ImageGen
{
    /**
     * Generate or search for images by description
     */
    public static function search(string $query, int $count = 4): array
    {
        $results = [];

        // Try DALL-E first (AI generated, best for custom requests)
        $openaiKey = get_option('aicopilot_openai_key', '');
        if ($openaiKey) {
            $results = self::generateDalle($query, min($count, 4), $openaiKey);
            if (!empty($results)) return $results;
        }

        // Try Pexels (best stock quality, free)
        // Default key as fallback so plugin works out of the box for all clients
        $defaultPexelsKey = 'V6JqBFXsnXfiGuYRqMwJ8ue2bHfQ78mIB9IXZh8TYkrP3aZO8OEW9SM7';
        $pexelsKey = get_option('aicopilot_pexels_key', '') ?: $defaultPexelsKey;
        if ($pexelsKey) {
            $results = self::searchPexels($query, $count, $pexelsKey);
        }

        // Fallback: Pixabay (free, no key needed for basic)
        if (empty($results)) {
            $pixabayKey = get_option('aicopilot_pixabay_key', '');
            if ($pixabayKey) {
                $results = self::searchPixabay($query, $count, $pixabayKey);
            }
        }

        // Final fallback: Lorem Picsum (no API key needed)
        if (empty($results)) {
            $results = self::getPlaceholders($query, $count);
        }

        return $results;
    }

    /**
     * Generate images using OpenAI DALL-E API
     */
    private static function generateDalle(string $prompt, int $count, string $apiKey): array
    {
        $response = wp_remote_post('https://api.openai.com/v1/images/generations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => min($count, 1), // DALL-E 3 only supports n=1
                'size' => '1792x1024',
                'quality' => 'standard',
            ]),
            'timeout' => 60,
        ]);

        if (is_wp_error($response)) return [];

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['data'])) return [];

        $results = [];
        foreach ($body['data'] as $img) {
            $results[] = [
                'url' => $img['url'],
                'thumb' => $img['url'],
                'alt' => $prompt,
                'credit' => 'AI Generated (DALL-E)',
                'source' => 'dalle',
            ];
        }

        // If DALL-E 3 only gave 1, generate more with different style hints
        if (count($results) < $count && count($results) > 0) {
            $styles = ['photorealistic', 'minimalist', 'artistic illustration'];
            foreach ($styles as $style) {
                if (count($results) >= $count) break;
                $styledResp = wp_remote_post('https://api.openai.com/v1/images/generations', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'body' => wp_json_encode([
                        'model' => 'dall-e-3',
                        'prompt' => $prompt . ', ' . $style . ' style',
                        'n' => 1,
                        'size' => '1792x1024',
                        'quality' => 'standard',
                    ]),
                    'timeout' => 60,
                ]);
                if (!is_wp_error($styledResp)) {
                    $styledBody = json_decode(wp_remote_retrieve_body($styledResp), true);
                    if (!empty($styledBody['data'][0]['url'])) {
                        $results[] = [
                            'url' => $styledBody['data'][0]['url'],
                            'thumb' => $styledBody['data'][0]['url'],
                            'alt' => $prompt . ' (' . $style . ')',
                            'credit' => 'AI Generated (DALL-E - ' . $style . ')',
                            'source' => 'dalle',
                        ];
                    }
                }
            }
        }

        return $results;
    }

    private static function searchPexels(string $query, int $count, string $apiKey): array
    {
        $url = 'https://api.pexels.com/v1/search?' . http_build_query([
            'query' => $query,
            'per_page' => $count,
            'orientation' => 'landscape',
        ]);

        $response = wp_remote_get($url, [
            'headers' => ['Authorization' => $apiKey],
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) return [];

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['photos'])) return [];

        $results = [];
        foreach ($body['photos'] as $photo) {
            $results[] = [
                'url' => $photo['src']['large'] ?? $photo['src']['original'],
                'thumb' => $photo['src']['medium'] ?? $photo['src']['small'],
                'alt' => $photo['alt'] ?? $query,
                'credit' => 'Photo by ' . ($photo['photographer'] ?? 'Pexels'),
                'source' => 'pexels',
            ];
        }
        return $results;
    }

    private static function searchPixabay(string $query, int $count, string $apiKey): array
    {
        $url = 'https://pixabay.com/api/?' . http_build_query([
            'key' => $apiKey,
            'q' => $query,
            'per_page' => $count,
            'image_type' => 'photo',
            'orientation' => 'horizontal',
        ]);

        $response = wp_remote_get($url, ['timeout' => 10]);
        if (is_wp_error($response)) return [];

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['hits'])) return [];

        $results = [];
        foreach ($body['hits'] as $hit) {
            $results[] = [
                'url' => $hit['largeImageURL'] ?? $hit['webformatURL'],
                'thumb' => $hit['previewURL'] ?? $hit['webformatURL'],
                'alt' => $query,
                'credit' => 'Photo from Pixabay',
                'source' => 'pixabay',
            ];
        }
        return $results;
    }

    /**
     * Lorem Picsum fallback - random high-quality images (no API key needed)
     */
    private static function getPlaceholders(string $query, int $count): array
    {
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $seed = md5($query . $i);
            $results[] = [
                'url' => "https://picsum.photos/seed/{$seed}/1200/800",
                'thumb' => "https://picsum.photos/seed/{$seed}/400/300",
                'alt' => $query,
                'credit' => 'Lorem Picsum',
                'source' => 'picsum',
            ];
        }
        return $results;
    }

    /**
     * Download image from URL and add to WordPress media library
     */
    public static function downloadToMedia(string $imageUrl, string $alt = ''): ?array
    {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $tmp = download_url($imageUrl);
        if (is_wp_error($tmp)) return null;

        $filename = basename(parse_url($imageUrl, PHP_URL_PATH)) ?: 'ai-image.jpg';
        // Ensure valid extension
        if (!preg_match('/\.(jpe?g|png|gif|webp)$/i', $filename)) {
            $filename .= '.jpg';
        }

        $file = ['name' => $filename, 'tmp_name' => $tmp];
        $attachmentId = media_handle_sideload($file, 0);

        if (is_wp_error($attachmentId)) {
            @unlink($tmp);
            return null;
        }

        if ($alt) {
            update_post_meta($attachmentId, '_wp_attachment_image_alt', $alt);
        }

        return [
            'id' => $attachmentId,
            'url' => wp_get_attachment_url($attachmentId),
        ];
    }
}
