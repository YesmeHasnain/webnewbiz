<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdo = new PDO("mysql:host=127.0.0.1;dbname=wp_2sleek", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Check what CSS blocks exist in home page
$row = $pdo->query("SELECT pm.meta_value FROM wp_posts p JOIN wp_postmeta pm ON p.ID = pm.post_id WHERE p.post_name = 'home' AND p.post_type = 'page' AND pm.meta_key = '_elementor_data'")->fetch(PDO::FETCH_ASSOC);
$data = json_decode($row['meta_value'], true);
$html = $data[0]['elements'][0]['settings']['html'];

// Check if footer CSS exists
echo "Has .site-footer CSS: " . (strpos($html, '.site-footer') !== false ? 'YES' : 'NO') . "\n";
echo "Has .footer-grid CSS: " . (strpos($html, '.footer-grid') !== false ? 'YES' : 'NO') . "\n";
echo "Has .footer-main CSS: " . (strpos($html, '.footer-main') !== false ? 'YES' : 'NO') . "\n";
echo "Has <footer HTML: " . (strpos($html, 'class="site-footer"') !== false ? 'YES' : 'NO') . "\n";
echo "Has footer-brand HTML: " . (strpos($html, 'footer-brand') !== false ? 'YES' : 'NO') . "\n";

// Check how many <style> blocks
preg_match_all('/<style>/', $html, $m);
echo "Number of <style> blocks: " . count($m[0]) . "\n";

// Show first 200 chars of footer section
$footerPos = strpos($html, 'site-footer');
if ($footerPos) {
    echo "\nFooter HTML found at position: {$footerPos}\n";
}
