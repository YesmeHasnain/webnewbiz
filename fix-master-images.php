<?php
/**
 * Generate placeholder images for master sites using binary data.
 * No GD library needed.
 */

// Minimal valid JPEG (1x1 pixel, dark blue) - we'll use this as base
$jpegHeader = "\xFF\xD8\xFF\xE0\x00\x10\x4A\x46\x49\x46\x00\x01\x01\x01\x00\x48\x00\x48\x00\x00";
$jpegData = "\xFF\xDB\x00\x43\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\x09\x09\x08\x0A\x0C\x14\x0D\x0C\x0B\x0B\x0C\x19\x12\x13\x0F\x14\x1D\x1A\x1F\x1E\x1D\x1A\x1C\x1C\x20\x24\x2E\x27\x20\x22\x2C\x23\x1C\x1C\x28\x37\x29\x2C\x30\x31\x34\x34\x34\x1F\x27\x39\x3D\x38\x32\x3C\x2E\x33\x34\x32";
$jpegFooter = "\xFF\xC0\x00\x0B\x08\x00\x01\x00\x01\x01\x01\x11\x00\xFF\xC4\x00\x1F\x00\x00\x01\x05\x01\x01\x01\x01\x01\x01\x00\x00\x00\x00\x00\x00\x00\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\xFF\xC4\x00\xB5\x10\x00\x02\x01\x03\x03\x02\x04\x03\x05\x05\x04\x04\x00\x00\x01\x7D\x01\x02\x03\x00\x04\x11\x05\x12\x21\x31\x41\x06\x13\x51\x61\x07\x22\x71\x14\x32\x81\x91\xA1\x08\x23\x42\xB1\xC1\x15\x52\xD1\xF0\x24\x33\x62\x72\x82\x09\x0A\x16\x17\x18\x19\x1A\x25\x26\x27\x28\x29\x2A\x34\x35\x36\x37\x38\x39\x3A\x43\x44\x45\x46\x47\x48\x49\x4A\x53\x54\x55\x56\x57\x58\x59\x5A\x63\x64\x65\x66\x67\x68\x69\x6A\x73\x74\x75\x76\x77\x78\x79\x7A\x83\x84\x85\x86\x87\x88\x89\x8A\x92\x93\x94\x95\x96\x97\x98\x99\x9A\xA2\xA3\xA4\xA5\xA6\xA7\xA8\xA9\xAA\xB2\xB3\xB4\xB5\xB6\xB7\xB8\xB9\xBA\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xD2\xD3\xD4\xD5\xD6\xD7\xD8\xD9\xDA\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xF1\xF2\xF3\xF4\xF5\xF6\xF7\xF8\xF9\xFA\xFF\xDA\x00\x08\x01\x01\x00\x00\x3F\x00\x7B\x94\xB2\x51\x00\x00\x00\xFF\xD9";
$minJpeg = $jpegHeader . $jpegData . $jpegFooter;

// Minimal 1x1 PNG (transparent)
$minPng = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==");

// SVG placeholder
$svgPlaceholder = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="60"><rect width="200" height="60" fill="#2563eb" rx="8"/><text x="100" y="35" text-anchor="middle" fill="white" font-family="Arial" font-size="16">Logo</text></svg>';

$masters = [
    'wp_master_geoport' => 'C:/xampp/htdocs/master-geoport',
    'wp_master_barab' => 'C:/xampp/htdocs/master-barab',
    'wp_master_transland' => 'C:/xampp/htdocs/master-transland',
];

foreach ($masters as $db => $sitePath) {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname={$db}", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT DISTINCT SUBSTRING_INDEX(guid, 'uploads/', -1) as path FROM wp_posts WHERE post_type='attachment' AND guid LIKE '%uploads%'");

    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $filePath = $sitePath . '/wp-content/uploads/' . $row['path'];
        $dir = dirname($filePath);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        if (!file_exists($filePath)) {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($ext === 'jpg' || $ext === 'jpeg') {
                file_put_contents($filePath, $minJpeg);
                $count++;
            } elseif ($ext === 'png') {
                file_put_contents($filePath, $minPng);
                $count++;
            } elseif ($ext === 'svg') {
                file_put_contents($filePath, $svgPlaceholder);
                $count++;
            }
        }
    }

    echo "{$db}: created {$count} placeholder images\n";
}

echo "Done!\n";
