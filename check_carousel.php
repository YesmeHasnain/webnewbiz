<?php
$pdo = new PDO('mysql:host=localhost;dbname=wp_cozy_italian_restaurant_grap', 'root', '');
$stmt = $pdo->query("SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key='_elementor_data' AND post_id=10");
$row = $stmt->fetch();
$data = json_decode($row['meta_value'], true);

function findCarousel($elements) {
    foreach ($elements as $el) {
        $wt = $el['widgetType'] ?? '';
        if ($wt === 'image-carousel') {
            echo "=== IMAGE-CAROUSEL WIDGET ===\n";
            echo json_encode($el['settings'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        }
        // Also find image widgets and show their full settings
        if ($wt === 'image') {
            $s = $el['settings'];
            echo "=== IMAGE WIDGET ===\n";
            $img = $s['image'] ?? [];
            $relevant = [
                'image' => $img,
                'image_size' => $s['image_size'] ?? null,
                'image_custom_dimension' => $s['image_custom_dimension'] ?? null,
                'width' => $s['width'] ?? null,
                'height' => $s['height'] ?? null,
                'object-fit' => $s['object-fit'] ?? null,
                '_element_custom_width' => $s['_element_custom_width'] ?? null,
                'img_border_radius' => $s['img_border_radius'] ?? null,
            ];
            echo json_encode($relevant, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";
        }
        if (!empty($el['elements'])) findCarousel($el['elements']);
    }
}
findCarousel($data);
