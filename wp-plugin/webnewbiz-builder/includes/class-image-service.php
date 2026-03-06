<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Image_Service {

    /**
     * Curated Unsplash photo IDs by business category.
     * Direct CDN URLs — no API key needed.
     */
    private static array $curated_photos = [
        'clothing' => [
            'hero'     => ['1441986300917-64674bd600d8', '1558618666-fcd25c85f7e7', '1567401893414-76b7b1e5a7a5', '1490481651871-ab68de25d43d', '1523381210434-271e8be1f52b'],
            'about'    => ['1558171813-4c088753af8f', '1556742049-0cfed4f6a45d', '1556905055-8f358a7a47b2', '1441984904996-e0b6ba687e04', '1469334031218-e382a71b716b'],
            'services' => ['1551232864-3f0890e580d9', '1445205170230-053b83016050', '1560243563-062bfc001d68', '1556742111-a301076d9d18', '1485230895905-ec40ba36b9bc'],
        ],
        'restaurant' => [
            'hero'     => ['1517248135467-4c7edcad34c4', '1552566626-52f8b828add9', '1414235077428-338989a2e8c0', '1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5'],
            'about'    => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1600565193348-f74bd3c7ccdf', '1466978913421-dad2ebd01d17', '1551218808-94e220e084d2'],
            'services' => ['1504674900247-0877df9cc836', '1476224203421-9ac39bcb3327', '1565299624946-b28f40a0ae38', '1540189549336-e6e99c3679fe', '1555939594-58d7cb561ad1'],
        ],
        'technology' => [
            'hero'     => ['1518770660439-4636190af475', '1451187580459-43490279c0fa', '1526374965328-7f61d4dc18c5', '1550751827-4bd374c3f58b', '1504384764586-bb4cdc1812f0'],
            'about'    => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1600880292203-757bb62b4baf', '1519389950473-47ba0277781c', '1556761175-4b46a572b786'],
            'services' => ['1460925895917-afdab827c52f', '1504639725590-34d0984388bd', '1555066931-4365d14bab8c', '1498050108023-c5249f4df085', '1517694712202-14dd9538aa97'],
        ],
        'health' => [
            'hero'     => ['1571019613454-1cb2f99b2d8b', '1576091160399-112ba8d25d1d', '1505751172876-fa1923c5c528', '1544367567-0f2fcb009e0b', '1540206395-68808572332f'],
            'about'    => ['1579684385127-1ef15d508118', '1576091160550-2173dba999ef', '1534438327276-14e5300c3a48', '1579684385127-1ef15d508118', '1576091160550-2173dba999ef'],
            'services' => ['1535914254981-b5012eebbd15', '1538108149393-fbbd81895907', '1530497610245-94d3c16cda28', '1559757175-5700dde675bc', '1538108149393-fbbd81895907'],
        ],
        'realestate' => [
            'hero'     => ['1560518883-ce09059eeffa', '1564013799919-ab600027ffc6', '1512917774080-9991f1c4c750', '1560448204-e02f11c3d0e2', '1600596542815-ffad4c1539a9'],
            'about'    => ['1600585154340-be6161a56a0c', '1600607687939-ce8a6c25118c', '1582407947304-fd86f028f716', '1560448075-bb8b16e93e5e', '1560185127-6ed189bf02f4'],
            'services' => ['1558036117-15d82a90b9b1', '1600585154526-990dced4db0d', '1600566753086-00f18fb6b3ea', '1560184897-ae75f418493e', '1560448076-36efba0de3f0'],
        ],
        'education' => [
            'hero'     => ['1523050854058-8df90110c9f1', '1427504494785-3a9ca7044f45', '1503676260728-1c00da094a0b', '1524178232363-1fb2b075b655', '1509062522246-3755977927d7'],
            'about'    => ['1522202176988-66273c2fd55f', '1529390079861-591de354faf5', '1571260899304-425eee4c7efc', '1517486808906-6ca8b3f04846', '1524178232363-1fb2b075b655'],
            'services' => ['1488190211105-8b0e65b80b4e', '1513258496099-48168024aec0', '1497633762265-9d179a990aa6', '1501504905252-473c47e087f8', '1456513080510-7bf3a84b82f8'],
        ],
        'beauty' => [
            'hero'     => ['1560066984-138dadb4c035', '1522335789203-aabd1fc54bc9', '1487412912498-0447578fcca8', '1596462502278-27bfdc403348', '1570172619644-dfd03ed5d881'],
            'about'    => ['1519699047748-de8e457a634e', '1516975080664-ed2fc6a32937', '1570172619644-dfd03ed5d881', '1596462502278-27bfdc403348', '1560066984-138dadb4c035'],
            'services' => ['1522335789203-aabd1fc54bc9', '1487412912498-0447578fcca8', '1516975080664-ed2fc6a32937', '1596462502278-27bfdc403348', '1560066984-138dadb4c035'],
        ],
        'fitness' => [
            'hero'     => ['1534438327276-14e5300c3a48', '1517836357463-d25dfeac3438', '1534438327276-14e5300c3a48', '1544367567-0f2fcb009e0b', '1540206395-68808572332f'],
            'about'    => ['1571388208497-71bedc66e932', '1518611012118-696072aa579a', '1574680178050-55c6a6a96e0a', '1534438327276-14e5300c3a48', '1534438327276-14e5300c3a48'],
            'services' => ['1517836357463-d25dfeac3438', '1574680178050-55c6a6a96e0a', '1571388208497-71bedc66e932', '1518611012118-696072aa579a', '1534438327276-14e5300c3a48'],
        ],
        'food' => [
            'hero'     => ['1504674900247-0877df9cc836', '1476224203421-9ac39bcb3327', '1565299624946-b28f40a0ae38', '1555939594-58d7cb561ad1', '1540189549336-e6e99c3679fe'],
            'about'    => ['1556910103-1c02745aae4d', '1581349485608-9469926a8e5e', '1600565193348-f74bd3c7ccdf', '1466978913421-dad2ebd01d17', '1551218808-94e220e084d2'],
            'services' => ['1414235077428-338989a2e8c0', '1537047902294-62a40c20a6ae', '1555396273-367ea4eb4db5', '1517248135467-4c7edcad34c4', '1552566626-52f8b828add9'],
        ],
        'consulting' => [
            'hero'     => ['1454165804606-c3d57bc86b40', '1497366216548-37526070297c', '1552664730-d307ca884978', '1556761175-4b46a572b786', '1542744173-8e7e53415bb0'],
            'about'    => ['1522071820081-009f0129c71c', '1553877522-43269d4ea984', '1600880292203-757bb62b4baf', '1519389950473-47ba0277781c', '1573497019940-1c28c88b4f3e'],
            'services' => ['1460925895917-afdab827c52f', '1531973576160-7125b8386882', '1521737604893-d14cc237f11d', '1542744173-8e7e53415bb0', '1553877522-43269d4ea984'],
        ],
        'default' => [
            'hero'     => ['1497366216548-37526070297c', '1454165804606-c3d57bc86b40', '1486406146926-c627a92ad1ab', '1557804506-669a67965ba0', '1556761175-4b46a572b786'],
            'about'    => ['1522071820081-009f0129c71c', '1521737604893-d14cc237f11d', '1600880292203-757bb62b4baf', '1553877522-43269d4ea984', '1573497019940-1c28c88b4f3e'],
            'services' => ['1460925895917-afdab827c52f', '1531973576160-7125b8386882', '1504639725590-34d0984388bd', '1555066931-4365d14bab8c', '1498050108023-c5249f4df085'],
        ],
    ];

    /**
     * Get image URLs for a business type (hero, about, services).
     */
    public function get_images(string $business_type): array {
        $category = $this->match_category($business_type);
        $images = [];

        foreach (['hero', 'about', 'services'] as $key) {
            $photos = self::$curated_photos[$category][$key] ?? self::$curated_photos['default'][$key];
            $photo_id = $photos[array_rand($photos)];
            $images[$key] = "https://images.unsplash.com/photo-{$photo_id}?w=1200&h=800&fit=crop&auto=format&q=80";
        }

        return $images;
    }

    /**
     * Download an image and create a WordPress attachment.
     * Returns the attachment ID.
     */
    public function download_and_attach(string $url, string $title = ''): int {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Try up to 3 times with increasing timeout
        $temp_file = null;
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $timeout = 30 * $attempt; // 30s, 60s, 90s
            $temp_file = download_url($url, $timeout);
            if (!is_wp_error($temp_file)) {
                break;
            }
            $temp_file = null;
        }

        if (!$temp_file) {
            return 0;
        }

        $file_array = [
            'name'     => sanitize_file_name(($title ?: 'stock-image') . '.jpg'),
            'tmp_name' => $temp_file,
        ];

        $upload = wp_handle_sideload($file_array, ['test_form' => false]);
        if (!empty($upload['error'])) {
            @unlink($temp_file);
            return 0;
        }

        $attachment = [
            'post_title'     => $title ?: 'Stock Image',
            'post_mime_type' => $upload['type'],
            'post_status'    => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $upload['file']);
        if (is_wp_error($attach_id) || !$attach_id) {
            return 0;
        }

        $metadata = wp_generate_attachment_metadata($attach_id, $upload['file']);
        wp_update_attachment_metadata($attach_id, $metadata);

        return $attach_id;
    }

    /**
     * Match a business type string to a curated photo category.
     */
    private function match_category(string $business_type): string {
        $type = strtolower($business_type);

        $mapping = [
            'beauty'     => ['beauty', 'salon', 'spa', 'cosmetic', 'skincare', 'hair', 'makeup', 'nail', 'barber'],
            'clothing'   => ['clothing', 'fashion', 'apparel', 'boutique', 'garment', 'textile', 'wear', 'dress', 'shirt'],
            'restaurant' => ['restaurant', 'cafe', 'bistro', 'diner', 'eatery', 'grill', 'bakery', 'pizza', 'sushi'],
            'food'       => ['food', 'catering', 'chef', 'cook', 'meal', 'delivery', 'grocery', 'organic'],
            'fitness'    => ['fitness', 'gym', 'workout', 'yoga', 'sport', 'athletic', 'crossfit', 'personal train'],
            'health'     => ['health', 'medical', 'clinic', 'hospital', 'dental', 'pharmacy', 'wellness', 'doctor', 'therapy'],
            'realestate' => ['real estate', 'property', 'realty', 'housing', 'apartment', 'construction', 'architect'],
            'education'  => ['education', 'school', 'university', 'academy', 'training', 'course', 'tutor', 'learning'],
            'technology' => ['tech', 'software', 'saas', 'digital', 'startup', 'cloud', 'cyber', 'programming', 'developer', 'web design', 'ecommerce'],
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
}
