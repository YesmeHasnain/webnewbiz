<?php
/**
 * Creates a WordPress template at C:\xampp\htdocs\wp-template
 * with all plugins pre-installed and a clean SQL dump.
 * Run once: php create_template.php
 */

$htdocs = 'C:/xampp/htdocs';
$templateDir = "{$htdocs}/wp-template";
$baseWp = "{$htdocs}/wordpress";
$templateDb = 'wp_template';
$templateUrl = 'http://localhost/wp-template';
$mysqlBin = 'C:/xampp/mysql/bin';

// Source site for premium plugins only (can't auto-download from wp.org)
$pluginSources = [
    'elementor-pro' => "{$htdocs}/kababjees-qmbg/wp-content/plugins/elementor-pro",
];

// Free plugins to auto-download from WordPress.org (always gets latest)
$wpOrgPlugins = ['elementor', 'header-footer-elementor', 'woocommerce'];

// WebNewBiz Builder plugin (our custom plugin — from storage)
$backendDir = __DIR__;  // backend/ directory
$webnewbizPluginSource = "{$backendDir}/storage/plugins/webnewbiz-builder";

echo "=== Creating WordPress Template ===\n\n";

// Step 1: Copy base WordPress
echo "[1/6] Copying WordPress files...\n";
if (is_dir($templateDir)) {
    exec("rmdir /S /Q \"" . str_replace('/', '\\', $templateDir) . "\" 2>&1");
    sleep(1);
}
$src = str_replace('/', '\\', $baseWp);
$dst = str_replace('/', '\\', $templateDir);
exec("xcopy \"{$src}\" \"{$dst}\" /E /I /Q /Y 2>&1", $out, $code);
echo "  xcopy exit: {$code}\n";

// Step 2: Download latest free plugins from WordPress.org + copy premium
echo "[2/7] Installing plugins...\n";

// Auto-download latest versions from WordPress.org
foreach ($wpOrgPlugins as $pluginSlug) {
    $dest = "{$templateDir}/wp-content/plugins/{$pluginSlug}";
    if (is_dir($dest)) {
        echo "  {$pluginSlug}: removing old version...\n";
        exec("rmdir /S /Q \"" . str_replace('/', '\\', $dest) . "\" 2>&1");
    }
    echo "  {$pluginSlug}: downloading latest from WordPress.org...\n";
    $apiUrl = "https://api.wordpress.org/plugins/info/1.0/{$pluginSlug}.json";
    $pluginInfo = @json_decode(@file_get_contents($apiUrl), true);
    $downloadUrl = $pluginInfo['download_link'] ?? "https://downloads.wordpress.org/plugin/{$pluginSlug}.latest-stable.zip";
    $version = $pluginInfo['version'] ?? 'latest';
    $zipPath = "{$templateDir}/wp-content/plugins/{$pluginSlug}.zip";
    $zipData = @file_get_contents($downloadUrl);
    if ($zipData && strlen($zipData) > 1000) {
        file_put_contents($zipPath, $zipData);
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $zip->extractTo("{$templateDir}/wp-content/plugins/");
            $zip->close();
            echo "  {$pluginSlug}: v{$version} installed\n";
        } else {
            echo "  {$pluginSlug}: ZIP extraction FAILED\n";
        }
        @unlink($zipPath);
    } else {
        echo "  {$pluginSlug}: DOWNLOAD FAILED — falling back to kababjees-qmbg\n";
        $fallback = "{$htdocs}/kababjees-qmbg/wp-content/plugins/{$pluginSlug}";
        if (is_dir($fallback)) {
            $s = str_replace('/', '\\', $fallback);
            $d = str_replace('/', '\\', $dest);
            exec("xcopy \"{$s}\" \"{$d}\" /E /I /Q /Y 2>&1");
            echo "  {$pluginSlug}: copied from fallback\n";
        }
    }
}

// Copy premium plugins from source site
foreach ($pluginSources as $slug => $source) {
    $dest = "{$templateDir}/wp-content/plugins/{$slug}";
    if (is_dir($dest)) {
        echo "  {$slug}: already exists\n";
        continue;
    }
    if (!is_dir($source)) {
        echo "  {$slug}: SOURCE NOT FOUND at {$source}\n";
        continue;
    }
    $s = str_replace('/', '\\', $source);
    $d = str_replace('/', '\\', $dest);
    exec("xcopy \"{$s}\" \"{$d}\" /E /I /Q /Y 2>&1", $out2, $code2);
    echo "  {$slug}: copied (exit: {$code2})\n";
}

// Copy WebNewBiz Builder plugin
$wnbDest = "{$templateDir}/wp-content/plugins/webnewbiz-builder";
if (is_dir($webnewbizPluginSource)) {
    $s = str_replace('/', '\\', $webnewbizPluginSource);
    $d = str_replace('/', '\\', $wnbDest);
    exec("xcopy \"{$s}\" \"{$d}\" /E /I /Q /Y 2>&1", $out3, $code3);
    echo "  webnewbiz-builder: copied (exit: {$code3})\n";
} else {
    echo "  webnewbiz-builder: SOURCE NOT FOUND at {$webnewbizPluginSource}\n";
}

// Clean themes - keep only hello-elementor (classic theme needed for Elementor + HFE)
echo "[3/7] Cleaning themes...\n";
$themesDir = "{$templateDir}/wp-content/themes";
// Download Hello Elementor if not present
$helloDir = "{$themesDir}/hello-elementor";
if (!is_dir($helloDir)) {
    echo "  Downloading Hello Elementor theme...\n";
    $zipUrl = 'https://downloads.wordpress.org/theme/hello-elementor.latest-stable.zip';
    $zipPath = "{$themesDir}/hello-elementor.zip";
    file_put_contents($zipPath, file_get_contents($zipUrl));
    $zip = new ZipArchive;
    if ($zip->open($zipPath) === true) {
        $zip->extractTo($themesDir);
        $zip->close();
    }
    @unlink($zipPath);
}
if (is_dir($themesDir)) {
    foreach (scandir($themesDir) as $d) {
        if ($d === '.' || $d === '..' || $d === 'hello-elementor') continue;
        $path = str_replace('/', '\\', "{$themesDir}/{$d}");
        exec("rmdir /S /Q \"{$path}\" 2>&1");
    }
}

// Create uploads directory
@mkdir("{$templateDir}/wp-content/uploads", 0755, true);

// Create wp-config.php
echo "[4/7] Creating wp-config.php...\n";
$configSample = "{$templateDir}/wp-config-sample.php";
if (file_exists($configSample)) {
    $config = file_get_contents($configSample);
    $config = str_replace('database_name_here', $templateDb, $config);
    $config = str_replace('username_here', 'root', $config);
    $config = str_replace('password_here', '', $config);
    $config = str_replace("'localhost'", "'127.0.0.1'", $config);

    // Set unique salts
    foreach (['AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'] as $key) {
        $config = preg_replace(
            "/define\(\s*'{$key}'\s*,\s*'[^']*'\s*\)/",
            "define('{$key}', '" . bin2hex(random_bytes(32)) . "')",
            $config
        );
    }
    file_put_contents("{$templateDir}/wp-config.php", $config);
}

// Step 5: Create database and install WordPress
echo "[5/7] Installing WordPress...\n";
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$pdo->exec("DROP DATABASE IF EXISTS `{$templateDb}`");
$pdo->exec("CREATE DATABASE `{$templateDb}`");

// Run WP install via HTTP
$installUrl = "{$templateUrl}/wp-admin/install.php?step=2";
$postData = http_build_query([
    'weblog_title' => 'Template Site',
    'user_name' => 'admin',
    'admin_password' => 'password',
    'admin_password2' => 'password',
    'pw_weak' => '1',
    'admin_email' => 'admin@webnewbiz.com',
    'blog_public' => '0',
    'language' => '',
]);

$ctx = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $postData,
        'timeout' => 120,
    ],
]);
$result = @file_get_contents($installUrl, false, $ctx);
echo "  Install response: " . (strlen($result ?? '') > 0 ? "OK (" . strlen($result) . " bytes)" : "FAILED") . "\n";

// Configure the template database
$pdo = new PDO("mysql:host=127.0.0.1;dbname={$templateDb}", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Activate plugins
$plugins = [];
$pluginChecks = [
    'elementor/elementor.php',
    'elementor-pro/elementor-pro.php',
    'header-footer-elementor/header-footer-elementor.php',
    'woocommerce/woocommerce.php',
    'webnewbiz-builder/webnewbiz-builder.php',
];
foreach ($pluginChecks as $plugin) {
    if (file_exists("{$templateDir}/wp-content/plugins/{$plugin}")) {
        $plugins[] = $plugin;
        echo "  Plugin found: {$plugin}\n";
    }
}
$pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote(serialize($plugins)) . " WHERE option_name = 'active_plugins'");

// Set theme
$pdo->exec("UPDATE wp_options SET option_value = 'hello-elementor' WHERE option_name = 'template'");
$pdo->exec("UPDATE wp_options SET option_value = 'hello-elementor' WHERE option_name = 'stylesheet'");

// Set permalink structure
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('permalink_structure', '/%postname%/', 'yes') ON DUPLICATE KEY UPDATE option_value = '/%postname%/'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('default_comment_status', 'closed', 'yes') ON DUPLICATE KEY UPDATE option_value = 'closed'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('default_ping_status', 'closed', 'yes') ON DUPLICATE KEY UPDATE option_value = 'closed'");

// Enable Elementor container experiment (required for e-con elements in layouts)
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-container', 'active', 'yes') ON DUPLICATE KEY UPDATE option_value = 'active'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-e_optimized_css_loading', 'active', 'yes') ON DUPLICATE KEY UPDATE option_value = 'active'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-container_grid', 'active', 'yes') ON DUPLICATE KEY UPDATE option_value = 'active'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-e_swiper_latest', 'active', 'yes') ON DUPLICATE KEY UPDATE option_value = 'active'");
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_experiment-e_element_cache', 'active', 'yes') ON DUPLICATE KEY UPDATE option_value = 'active'");

// Register elementor-hf CPT support
$cpts = serialize(['post', 'page', 'elementor-hf']);
$pdo->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_cpt_support', " . $pdo->quote($cpts) . ", 'yes') ON DUPLICATE KEY UPDATE option_value = " . $pdo->quote($cpts));

// Remove default content
$pdo->exec("DELETE FROM wp_posts WHERE post_type = 'post' AND post_title = 'Hello world!'");
$pdo->exec("DELETE FROM wp_posts WHERE post_type = 'page' AND post_title = 'Sample Page'");
$pdo->exec("DELETE FROM wp_posts WHERE post_type = 'page' AND post_title = 'Privacy Policy'");

// Use placeholder values for things we'll replace per-site
// siteurl and home will be replaced per site
// blogname will be replaced per site
$pdo->exec("UPDATE wp_options SET option_value = '__SITE_URL__' WHERE option_name = 'siteurl'");
$pdo->exec("UPDATE wp_options SET option_value = '__SITE_URL__' WHERE option_name = 'home'");
$pdo->exec("UPDATE wp_options SET option_value = '__SITE_NAME__' WHERE option_name = 'blogname'");

echo "  Database configured.\n";

// Step 6: Export SQL dump
// Step 6: Detect installed Elementor version for hardcoded references
echo "[6/7] Detecting plugin versions...\n";
$elementorMainFile = "{$templateDir}/wp-content/plugins/elementor/elementor.php";
$elementorVersion = '3.35.6'; // fallback
if (file_exists($elementorMainFile)) {
    $content = file_get_contents($elementorMainFile);
    if (preg_match("/Version:\s*([0-9.]+)/i", $content, $m)) {
        $elementorVersion = $m[1];
    }
}
echo "  Elementor version: {$elementorVersion}\n";
file_put_contents("{$templateDir}/elementor-version.txt", $elementorVersion);

echo "[7/7] Exporting SQL dump...\n";
$dumpFile = str_replace('/', '\\', "{$templateDir}/wp-template.sql");
$mysqlDump = str_replace('/', '\\', "{$mysqlBin}/mysqldump");
exec("\"{$mysqlDump}\" -u root {$templateDb} > \"{$dumpFile}\" 2>&1", $dumpOut, $dumpCode);
echo "  mysqldump exit: {$dumpCode}\n";

if (file_exists(str_replace('\\', '/', $dumpFile))) {
    $size = round(filesize(str_replace('\\', '/', $dumpFile)) / 1024);
    echo "  Dump size: {$size} KB\n";
}

// Clean up template database (not needed anymore, SQL dump has it)
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$pdo->exec("DROP DATABASE IF EXISTS `{$templateDb}`");

// Delete wp-config.php from template (will be generated per-site)
@unlink("{$templateDir}/wp-config.php");

echo "\n=== Template created at {$templateDir} ===\n";
echo "Plugins: " . implode(', ', array_map(fn($p) => explode('/', $p)[0], $plugins)) . "\n";
echo "SQL dump: {$templateDir}/wp-template.sql\n";
