<?php
/**
 * Rebuild Elementor page data for webnews site using fixed Azure layout.
 * Run: cd backend && php rebuild-webnews.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Services\Layouts\LayoutAzure;

$dbName = 'wp_webnews';
$siteUrl = 'http://localhost/webnews';

echo "=== Rebuilding webnews Elementor pages ===\n\n";

// Connect to DB
$pdo = new PDO("mysql:host=127.0.0.1;dbname={$dbName}", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get current site name
$siteName = $pdo->query("SELECT option_value FROM wp_options WHERE option_name='blogname'")->fetchColumn() ?: 'WebNews';
echo "Site: {$siteName}\n";

// Build layout
$layout = new LayoutAzure();

// Default content for rebuild
$content = [
    'site_name' => $siteName,
    'hero_title' => 'Build Something Amazing Today',
    'hero_subtitle' => 'We deliver exceptional digital solutions that help businesses grow faster and smarter with modern technology.',
    'hero_cta' => 'Get Started Free',
    'hero_eyebrow' => "Welcome to {$siteName}",
    'about_title' => 'Who We Are',
    'about_text' => 'We are a team of passionate innovators building the future of digital business solutions.',
    'about_text2' => 'With cutting-edge technology and a user-first approach, we help companies transform their ideas into reality.',
    'services_title' => 'Everything You Need',
    'services_subtitle' => 'Powerful features designed to help your business thrive.',
    'benefits_title' => 'Built for Growth',
    'testimonials_title' => 'Loved by Teams',
    'cta_title' => 'Start Building Today',
    'cta_text' => 'Join thousands of businesses already growing with our platform.',
];

$images = [
    'hero' => '',
    'about' => '',
    'gallery1' => '',
    'gallery2' => '',
    'services' => '',
];

// Page types and their DB post titles
$pageMap = [
    'Home' => 'home',
    'About Us' => 'about',
    'Our Services' => 'services',
    'Contact Us' => 'contact',
];

foreach ($pageMap as $postTitle => $pageType) {
    echo "\nBuilding: {$postTitle} ({$pageType})...\n";

    // Get post ID
    $stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_title = ? AND post_status = 'publish' AND post_type = 'page' LIMIT 1");
    $stmt->execute([$postTitle]);
    $postId = $stmt->fetchColumn();

    if (!$postId) {
        echo "  SKIP: page not found\n";
        continue;
    }

    // Build page elements
    $elements = $layout->buildPage($pageType, $content, $images);

    // Encode to JSON
    $json = json_encode($elements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo "  JSON size: " . strlen($json) . " bytes\n";

    // Update _elementor_data
    $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'");
    $stmt->execute([$json, $postId]);
    echo "  Updated post #{$postId}\n";

    // Clear element cache
    $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_element_cache'")->execute([$postId]);
}

// Rebuild header/footer too
echo "\nBuilding Header...\n";
$headerElements = $layout->buildHeader($siteName, ['home' => 'Home', 'about' => 'About', 'services' => 'Services', 'contact' => 'Contact']);
$headerJson = json_encode($headerElements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_title = 'Site Header' AND post_type = 'elementor-hf' LIMIT 1");
$stmt->execute();
$headerId = $stmt->fetchColumn();
if ($headerId) {
    $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$headerJson, $headerId]);
    $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_element_cache'")->execute([$headerId]);
    echo "  Updated header #{$headerId}\n";
}

echo "\nBuilding Footer...\n";
$footerElements = $layout->buildFooter($siteName, ['home' => 'Home', 'about' => 'About', 'services' => 'Services', 'contact' => 'Contact'], ['email' => 'hello@webnews.com']);
$footerJson = json_encode($footerElements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_title = 'Site Footer' AND post_type = 'elementor-hf' LIMIT 1");
$stmt->execute();
$footerId = $stmt->fetchColumn();
if ($footerId) {
    $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$footerJson, $footerId]);
    $pdo->prepare("DELETE FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_element_cache'")->execute([$footerId]);
    echo "  Updated footer #{$footerId}\n";
}

// Clear Elementor CSS cache
$sitePath = "C:/xampp/htdocs/webnews";
$cssDir = "{$sitePath}/wp-content/uploads/elementor/css";
if (is_dir($cssDir)) {
    array_map('unlink', glob("{$cssDir}/*.css"));
    echo "\nCleared Elementor CSS cache\n";
}

// Clear global CSS meta
$pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%css%' OR option_name LIKE '%_elementor_global%'");

echo "\n=== Done! Visit: {$siteUrl} ===\n";
echo "Note: First load may be slow as Elementor regenerates CSS.\n";
