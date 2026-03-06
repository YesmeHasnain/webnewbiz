<?php
/**
 * Fix CSS issues: switch theme, aggressive mu-plugin, clear caches
 */
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'wp_zaid';
$wpPath = 'C:\\xampp\\htdocs\\zaid';

$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. Switch theme to twentytwentyfour (minimal, no custom fonts/scripts)
$db->exec("UPDATE wp_options SET option_value = 'twentytwentyfour' WHERE option_name = 'template'");
$db->exec("UPDATE wp_options SET option_value = 'twentytwentyfour' WHERE option_name = 'stylesheet'");
echo "OK Switched theme to twentytwentyfour\n";

// 2. Create aggressive mu-plugin
$muDir = $wpPath . '\\wp-content\\mu-plugins';
if (!is_dir($muDir)) mkdir($muDir, 0777, true);

$muCode = <<<'MU'
<?php
/**
 * Clean Canvas: dequeue ALL non-essential styles/scripts on Elementor Canvas pages
 */
add_action('wp_enqueue_scripts', function() {
    if (!is_singular()) return;
    $tpl = get_page_template_slug();
    if ($tpl !== 'elementor_canvas') return;

    // Whitelist: only keep essential Elementor CSS
    $keep = [
        'elementor-frontend',
        'elementor-post-2',  // our page CSS
    ];

    global $wp_styles, $wp_scripts;

    // Dequeue ALL styles except whitelisted
    if ($wp_styles) {
        foreach ($wp_styles->registered as $handle => $style) {
            if (!in_array($handle, $keep)) {
                wp_dequeue_style($handle);
                wp_deregister_style($handle);
            }
        }
    }

    // Dequeue ALL scripts except jQuery (needed for Elementor)
    if ($wp_scripts) {
        $keepScripts = ['jquery', 'jquery-core', 'jquery-migrate'];
        foreach ($wp_scripts->registered as $handle => $script) {
            if (!in_array($handle, $keepScripts) && strpos($handle, 'elementor') === false) {
                wp_dequeue_script($handle);
            }
        }
    }
}, 999);

// Also remove WordPress emoji styles
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
});
MU;

file_put_contents("$muDir/canvas-clean.php", $muCode);
echo "OK Created aggressive mu-plugin\n";

// 3. Disable Elementor's default Google Fonts loading
$kit = $db->query("SELECT option_value FROM wp_options WHERE option_name = 'elementor_active_kit'")->fetchColumn();
if ($kit) {
    // Set kit to not load extra fonts
    $db->exec("DELETE FROM wp_postmeta WHERE post_id = $kit AND meta_key = '_elementor_page_settings'");
    $db->exec("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ($kit, '_elementor_page_settings', '" . json_encode([
        'system_typography' => [],
        'custom_typography' => [],
        'default_generic_fonts' => '',
    ]) . "')");
    echo "OK Cleared Elementor kit font settings\n";
}

// 4. Clear ALL Elementor CSS cache
$cssDir = $wpPath . '\\wp-content\\uploads\\elementor\\css';
if (is_dir($cssDir)) {
    foreach (glob("$cssDir/*.css") as $f) @unlink($f);
}
$db->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_css'");
$db->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%css%cache%'");
echo "OK Cleared all Elementor CSS cache\n";

// 5. Disable Elementor experiments that add overhead
$experiments = [
    'elementor_experiment-e_lazyload' => 'inactive',
    'elementor_experiment-e_font_icon_svg' => 'active',
];
foreach ($experiments as $name => $val) {
    $db->exec("DELETE FROM wp_options WHERE option_name = '$name'");
    $db->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('$name', '$val', 'yes')");
}
echo "OK Updated Elementor experiments\n";

echo "\nDone! Reload http://localhost/zaid\n";
