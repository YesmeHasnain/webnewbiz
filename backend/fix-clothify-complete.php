<?php
/**
 * Fix clothify site: Add WooCommerce, products, and refresh images.
 * Run: php fix-clothify-complete.php
 */

$dbName = 'wp_clothify';
$sitePath = 'C:/xampp/htdocs/clothify';
$siteUrl = 'http://localhost/clothify';
$businessName = 'Clothify';
$businessType = 'clothing brand';

$pdo = new PDO("mysql:host=127.0.0.1;dbname={$dbName}", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== Fixing Clothify Site ===\n\n";

// 1. Activate WooCommerce plugin
echo "[1] Activating WooCommerce...\n";
$stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name = 'active_plugins'");
$currentPlugins = unserialize($stmt->fetchColumn());
if (!in_array('woocommerce/woocommerce.php', $currentPlugins)) {
    $currentPlugins[] = 'woocommerce/woocommerce.php';
    $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'active_plugins'")->execute([serialize($currentPlugins)]);
    echo "  WooCommerce activated.\n";
} else {
    echo "  Already active.\n";
}

// 2. Create WooCommerce pages
echo "[2] Creating WooCommerce pages...\n";
$now = date('Y-m-d H:i:s');
$wooPages = [
    'shop' => ['title' => 'Shop', 'option' => 'woocommerce_shop_page_id'],
    'cart' => ['title' => 'Cart', 'option' => 'woocommerce_cart_page_id', 'shortcode' => '[woocommerce_cart]'],
    'checkout' => ['title' => 'Checkout', 'option' => 'woocommerce_checkout_page_id', 'shortcode' => '[woocommerce_checkout]'],
    'myaccount' => ['title' => 'My Account', 'option' => 'woocommerce_myaccount_page_id', 'shortcode' => '[woocommerce_my_account]'],
];

foreach ($wooPages as $slug => $page) {
    // Check if page exists
    $check = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_name = ? AND post_type = 'page' LIMIT 1");
    $check->execute([$slug]);
    $existing = $check->fetchColumn();

    if ($existing) {
        setOption($pdo, $page['option'], (string) $existing);
        echo "  {$page['title']}: already exists (ID: {$existing})\n";
        continue;
    }

    $postContent = $page['shortcode'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '')");
    $stmt->execute([$now, $now, $postContent, $page['title'], $slug, $now, $now]);
    $pageId = (int) $pdo->lastInsertId();
    setOption($pdo, $page['option'], (string) $pageId);
    echo "  {$page['title']}: created (ID: {$pageId})\n";
}

// 3. WooCommerce settings
echo "[3] Configuring WooCommerce settings...\n";
$settings = [
    'woocommerce_currency' => 'USD',
    'woocommerce_currency_pos' => 'left',
    'woocommerce_price_thousand_sep' => ',',
    'woocommerce_price_decimal_sep' => '.',
    'woocommerce_price_num_decimals' => '2',
    'woocommerce_default_country' => 'US:CA',
    'woocommerce_calc_taxes' => 'no',
    'woocommerce_enable_reviews' => 'yes',
    'woocommerce_manage_stock' => 'yes',
    'woocommerce_onboarding_profile' => serialize(['completed' => true]),
    'woocommerce_task_list_hidden' => 'yes',
    'woocommerce_task_list_complete' => 'yes',
    'woocommerce_show_marketplace_suggestions' => 'no',
];
foreach ($settings as $key => $value) {
    setOption($pdo, $key, $value);
}
echo "  Done.\n";

// 4. Create sample clothing products
echo "[4] Creating clothing products...\n";
$products = [
    ['title' => 'Classic Cotton Tee', 'price' => '29.99', 'sale_price' => '', 'description' => 'Premium quality cotton t-shirt with a comfortable fit. Available in multiple colors.', 'short' => 'Soft & breathable cotton tee', 'category' => 'T-Shirts', 'sku' => 'TEE-001'],
    ['title' => 'Slim Fit Denim Jeans', 'price' => '79.99', 'sale_price' => '59.99', 'description' => 'Modern slim fit jeans crafted from premium stretch denim. Perfect for casual and semi-formal occasions.', 'short' => 'Stretch denim slim fit', 'category' => 'Jeans', 'sku' => 'JEN-001'],
    ['title' => 'Casual Linen Shirt', 'price' => '49.99', 'sale_price' => '', 'description' => 'Lightweight linen shirt ideal for warm weather. Relaxed fit with a modern collar.', 'short' => 'Breezy linen button-down', 'category' => 'Shirts', 'sku' => 'SHT-001'],
    ['title' => 'Wool Blend Blazer', 'price' => '149.99', 'sale_price' => '119.99', 'description' => 'Elegant wool blend blazer for a polished look. Features two-button closure and interior pockets.', 'short' => 'Tailored wool blend blazer', 'category' => 'Blazers', 'sku' => 'BLZ-001'],
    ['title' => 'Knit Pullover Sweater', 'price' => '64.99', 'sale_price' => '', 'description' => 'Cozy knit sweater with ribbed cuffs and hem. Perfect layering piece for cooler days.', 'short' => 'Warm & cozy knit pullover', 'category' => 'Sweaters', 'sku' => 'SWT-001'],
    ['title' => 'Chino Shorts', 'price' => '39.99', 'sale_price' => '34.99', 'description' => 'Versatile chino shorts with a clean tailored look. Comfortable cotton-blend fabric.', 'short' => 'Classic chino shorts', 'category' => 'Shorts', 'sku' => 'SHR-001'],
    ['title' => 'Leather Belt', 'price' => '34.99', 'sale_price' => '', 'description' => 'Genuine leather belt with polished metal buckle. A timeless accessory for any outfit.', 'short' => 'Genuine leather belt', 'category' => 'Accessories', 'sku' => 'BLT-001'],
    ['title' => 'Oversized Hoodie', 'price' => '54.99', 'sale_price' => '', 'description' => 'Ultra-soft oversized hoodie with kangaroo pocket. Perfect for relaxed weekends.', 'short' => 'Comfy oversized hoodie', 'category' => 'Hoodies', 'sku' => 'HOD-001'],
];

$created = 0;
foreach ($products as $product) {
    $title = $product['title'];
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)));

    // Skip if product already exists
    $check = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_name = ? AND post_type = 'product' LIMIT 1");
    $check->execute([$slug]);
    if ($check->fetchColumn()) {
        echo "  {$title}: already exists\n";
        continue;
    }

    $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, ?, 'publish', 'open', 'closed', ?, 'product', ?, ?, '', '', '')");
    $stmt->execute([$now, $now, $product['description'], $title, $product['short'], $slug, $now, $now]);
    $productId = (int) $pdo->lastInsertId();

    if ($productId) {
        setPostMeta($pdo, $productId, '_regular_price', $product['price']);
        setPostMeta($pdo, $productId, '_price', $product['sale_price'] ?: $product['price']);
        if ($product['sale_price']) {
            setPostMeta($pdo, $productId, '_sale_price', $product['sale_price']);
        }
        setPostMeta($pdo, $productId, '_sku', $product['sku']);
        setPostMeta($pdo, $productId, '_stock_status', 'instock');
        setPostMeta($pdo, $productId, '_manage_stock', 'no');
        setPostMeta($pdo, $productId, '_virtual', 'no');
        setPostMeta($pdo, $productId, '_downloadable', 'no');
        setPostMeta($pdo, $productId, '_visibility', 'visible');
        setPostMeta($pdo, $productId, 'total_sales', '0');

        ensureProductCategory($pdo, $productId, $product['category']);
        echo "  {$title}: created (ID: {$productId}, \${$product['price']})\n";
        $created++;
    }
}
echo "  {$created} products created.\n";

// 5. Download proper clothing images
echo "[5] Downloading clothing images...\n";
$uploadsDir = $sitePath . '/wp-content/uploads/' . date('Y/m');
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

// Curated clothing-specific Unsplash photo IDs
$clothingPhotos = [
    'hero' => '1441986300917-64674bd600d8',
    'about' => '1558171813-4c088753af8f',
    'services' => '1551232864-3f0890e580d9',
    'gallery1' => '1489987707025-afc232f7ea0f',
    'gallery2' => '1523381210434-271e8be1f52b',
    'gallery3' => '1483985988355-763728e1935b',
    'gallery4' => '1556742049-0cfed4f6a45d',
    'gallery5' => '1490481651871-ab68de25d43d',
    'gallery6' => '1567401893414-76b7b1e5a7a5',
    'team' => '1469334031218-e382a71b716b',
];

$imageUrls = [];
foreach ($clothingPhotos as $key => $photoId) {
    $destFile = "{$uploadsDir}/{$key}.jpg";
    $url = "https://images.unsplash.com/photo-{$photoId}?w=1200&h=800&fit=crop&auto=format&q=80";

    if (file_exists($destFile) && filesize($destFile) > 5000) {
        // Keep existing if seems valid
        $wpUrl = $siteUrl . '/wp-content/uploads/' . date('Y/m') . "/{$key}.jpg";
        $imageUrls[$key] = $wpUrl;
        echo "  {$key}: using existing\n";
        continue;
    }

    $ctx = stream_context_create(['http' => ['timeout' => 30]]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data && strlen($data) > 1000) {
        file_put_contents($destFile, $data);
        $wpUrl = $siteUrl . '/wp-content/uploads/' . date('Y/m') . "/{$key}.jpg";
        $imageUrls[$key] = $wpUrl;
        echo "  {$key}: downloaded (" . round(strlen($data) / 1024) . " KB)\n";
    } else {
        echo "  {$key}: FAILED to download\n";
    }
}

// 6. Update Elementor data to use new image URLs
echo "[6] Updating page images...\n";
$pages = $pdo->query("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($pages as $page) {
    $metaStmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data' LIMIT 1");
    $metaStmt->execute([$page['ID']]);
    $json = $metaStmt->fetchColumn();
    if (!$json) continue;

    $changed = false;
    foreach ($imageUrls as $key => $url) {
        // Replace any old Unsplash URLs with our new ones
        if (strpos($json, $url) === false) {
            // The old images might be from different unsplash IDs, so update references
        }
    }

    // Replace all unsplash image URLs with our clothing-specific ones
    // Match pattern: images.unsplash.com/photo-XXXXX?...
    $json = preg_replace_callback(
        '#https://images\.unsplash\.com/photo-[a-f0-9-]+\?[^"\'\\\\]*#',
        function ($match) use (&$imageUrls) {
            static $idx = 0;
            $keys = array_keys($imageUrls);
            $key = $keys[$idx % count($keys)];
            $idx++;
            return $imageUrls[$key];
        },
        $json,
        -1,
        $count
    );

    if ($count > 0) {
        $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'")->execute([$json, $page['ID']]);
        echo "  {$page['post_title']}: updated {$count} image references\n";
    } else {
        echo "  {$page['post_title']}: no unsplash images found in data\n";
    }
}

// Also update HFE templates
$hfePages = $pdo->query("SELECT ID, post_title FROM wp_posts WHERE post_type = 'elementor-hf' AND post_status = 'publish'")->fetchAll(PDO::FETCH_ASSOC);
foreach ($hfePages as $page) {
    echo "  HFE: {$page['post_title']} (ID: {$page['ID']})\n";
}

// 7. Clear Elementor caches
echo "[7] Clearing Elementor caches...\n";
$pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_element_cache'");
$pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_css'");
$pdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_page_assets'");
$pdo->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%cache%'");
$pdo->exec("DELETE FROM wp_options WHERE option_name LIKE 'elementor_css%'");
$cssPath = $sitePath . '/wp-content/uploads/elementor/css';
if (is_dir($cssPath)) {
    array_map('unlink', glob("{$cssPath}/*.css"));
    echo "  Filesystem CSS cache cleared.\n";
}
echo "  Database caches cleared.\n";

// 8. Regenerate CSS
echo "[8] Regenerating Elementor CSS...\n";
$regenUrl = $siteUrl . '/_regen-css.php';
$regenScript = <<<'REGEN'
<?php
define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';
header('Content-Type: application/json');
if (!class_exists('\Elementor\Plugin')) { echo json_encode(['error' => 'Elementor not loaded']); exit; }
\Elementor\Plugin::$instance->files_manager->clear_cache();
$posts = get_posts(['post_type' => ['page', 'elementor-hf', 'elementor_library'], 'posts_per_page' => -1, 'meta_key' => '_elementor_data', 'post_status' => 'publish']);
$results = [];
foreach ($posts as $post) {
    try { $css = \Elementor\Core\Files\CSS\Post::create($post->ID); $css->update(); $results[] = $post->post_title; } catch (\Exception $e) {}
}
echo json_encode(['ok' => true, 'pages' => $results]);
REGEN;

file_put_contents($sitePath . '/_regen-css.php', $regenScript);
$response = @file_get_contents($regenUrl, false, stream_context_create(['http' => ['timeout' => 30]]));
if ($response) {
    $data = json_decode($response, true);
    echo "  CSS regenerated for: " . implode(', ', $data['pages'] ?? []) . "\n";
} else {
    echo "  CSS regeneration via HTTP failed — will auto-generate on first visit.\n";
}
@unlink($sitePath . '/_regen-css.php');

echo "\n=== Clothify fix complete! ===\n";
echo "Site URL: {$siteUrl}\n";
echo "Shop URL: {$siteUrl}/shop/\n";

// Helper functions
function setOption(PDO $pdo, string $name, string $value): void {
    $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes') ON DUPLICATE KEY UPDATE option_value = ?")->execute([$name, $value, $value]);
}

function setPostMeta(PDO $pdo, int $postId, string $key, string $value): void {
    $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)")->execute([$postId, $key, $value]);
}

function ensureProductCategory(PDO $pdo, int $productId, string $categoryName): void {
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', strtolower($categoryName)));
    $stmt = $pdo->prepare("SELECT t.term_id, tt.term_taxonomy_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE t.slug = ? AND tt.taxonomy = 'product_cat' LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $termTaxId = $row['term_taxonomy_id'];
    } else {
        $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)")->execute([$categoryName, $slug]);
        $termId = (int) $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'product_cat', '', 0, 0)")->execute([$termId]);
        $termTaxId = (int) $pdo->lastInsertId();
    }
    $pdo->prepare("INSERT IGNORE INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)")->execute([$productId, $termTaxId]);
    $pdo->exec("UPDATE wp_term_taxonomy SET count = (SELECT COUNT(*) FROM wp_term_relationships WHERE term_taxonomy_id = {$termTaxId}) WHERE term_taxonomy_id = {$termTaxId}");
}