<?php
$pdo = new PDO('mysql:host=localhost;dbname=wp_cozy_italian_restaurant_grap', 'root', '');
$stmt = $pdo->query("SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key='_elementor_data' AND post_id IN (10,11,12,13,14)");

while ($row = $stmt->fetch()) {
    $data = json_decode($row['meta_value'], true);
    if (!$data) continue;
    echo "\n=== Page ID: {$row['post_id']} ===\n";
    findWidgets($data);
}

function findWidgets($elements, $depth = 0) {
    foreach ($elements as $el) {
        $wt = $el['widgetType'] ?? '';
        $elType = $el['elType'] ?? '';
        $s = $el['settings'] ?? [];

        // Check column/section background images
        if (in_array($elType, ['section', 'column', 'container'])) {
            $bgImg = $s['background_image'] ?? null;
            $bgSize = $s['background_size'] ?? 'N/A';
            $bgPos = $s['background_position'] ?? 'N/A';
            if ($bgImg && !empty($bgImg['url'])) {
                echo "  BG IMAGE ({$elType}) | size: {$bgSize} | pos: {$bgPos} | id: " . ($bgImg['id'] ?? '?') . " | url: " . substr($bgImg['url'], 0, 80) . "\n";
            }
        }

        if ($wt === 'image') {
            $img = $s['image'] ?? [];
            $size = $s['image_size'] ?? 'N/A';
            $custom = $s['image_custom_dimension'] ?? [];
            $width = $s['width'] ?? null;
            $widthPx = $s['width_px'] ?? null;
            $imgW = $custom['width'] ?? 'auto';
            $imgH = $custom['height'] ?? 'auto';
            echo "  IMAGE widget | image_size: {$size} | custom_dim: {$imgW}x{$imgH}";
            if ($width) echo " | width: " . json_encode($width);
            if ($widthPx) echo " | width_px: " . json_encode($widthPx);
            echo " | id: " . ($img['id'] ?? '?') . " | url: " . substr($img['url'] ?? '?', 0, 80) . "\n";
        }

        if ($wt === 'image-carousel') {
            $slides = $s['carousel'] ?? [];
            $size = $s['image_size'] ?? 'N/A';
            $thumbSize = $s['thumbnail_size'] ?? 'N/A';
            echo "  IMAGE-CAROUSEL | slides: " . count($slides) . " | image_size: {$size} | thumbnail_size: {$thumbSize}\n";
            foreach (array_slice($slides, 0, 2) as $i => $slide) {
                $img = $slide['image'] ?? [];
                echo "    slide[$i] | id: " . ($img['id'] ?? '?') . " | url: " . substr($img['url'] ?? '?', 0, 80) . "\n";
            }
        }

        if ($wt === 'html') {
            $html = $s['html'] ?? '';
            // Check for img tags with sizes
            if (preg_match_all('/<img[^>]*>/i', $html, $matches)) {
                foreach (array_slice($matches[0], 0, 2) as $m) {
                    $short = substr($m, 0, 120);
                    echo "  HTML img tag: {$short}...\n";
                }
                if (count($matches[0]) > 2) echo "  ... and " . (count($matches[0]) - 2) . " more img tags\n";
            }
        }

        if (!empty($el['elements'])) {
            findWidgets($el['elements'], $depth + 1);
        }
    }
}
