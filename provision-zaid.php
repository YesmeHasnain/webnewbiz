<?php
/**
 * Provision WordPress site "zaid" at localhost/zaid
 * with pixel-perfect Elementor page from convert-elementor.html
 */

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'wp_zaid';
$sitePath = 'C:\\xampp\\htdocs\\zaid';
$siteUrl = 'http://localhost/zaid';
$wpUser = 'admin';
$wpPass = 'admin123';
$wpEmail = 'admin@zaid.local';
$siteTitle = 'Coop B-Ball Training';
$basePath = 'C:\\xampp\\htdocs\\wordpress';
$pluginPath = __DIR__ . '\\prebuild\\plugins';
$htmlFile = __DIR__ . '\\convert-elementor.html';

echo "=== Provisioning WordPress site: zaid ===\n\n";

// ── Step 1: Create Database ──
echo "[1/8] Creating database...\n";
try {
    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
    $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "  ✓ Database '$dbName' created\n";
} catch (PDOException $e) {
    die("  ✗ DB Error: " . $e->getMessage() . "\n");
}

// ── Step 2: Copy WordPress files ──
echo "[2/8] Copying WordPress files...\n";
if (is_dir($sitePath)) {
    exec("rmdir /s /q \"$sitePath\" 2>NUL");
    sleep(1);
}
$cmd = "robocopy \"$basePath\" \"$sitePath\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
exec($cmd, $out, $code);
if ($code > 7) {
    die("  ✗ Robocopy failed with code $code\n");
}
echo "  ✓ WordPress copied to $sitePath\n";

// Copy Elementor Pro plugin
echo "[3/8] Copying plugins...\n";
$plugins = ['elementor-pro', 'header-footer-elementor'];
foreach ($plugins as $plug) {
    $src = "$pluginPath\\$plug";
    $dst = "$sitePath\\wp-content\\plugins\\$plug";
    if (is_dir($src)) {
        exec("robocopy \"$src\" \"$dst\" /E /NFL /NDL /NJH /NJS /NC /NS /NP", $o, $c);
        echo "  ✓ Copied $plug\n";
    } else {
        echo "  - $plug not found, skipping\n";
    }
}

// ── Step 3: Generate wp-config.php ──
echo "[4/8] Generating wp-config.php...\n";
$salts = '';
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}|;:,.<>?/~`';
$saltKeys = ['AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT'];
foreach ($saltKeys as $k) {
    $s = '';
    for ($i = 0; $i < 64; $i++) $s .= $chars[random_int(0, strlen($chars)-1)];
    $salts .= "define('$k', '$s');\n";
}

$wpConfig = <<<PHP
<?php
define('DB_NAME', '$dbName');
define('DB_USER', '$dbUser');
define('DB_PASSWORD', '$dbPass');
define('DB_HOST', '$dbHost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$salts
\$table_prefix = 'wp_';
define('WP_DEBUG', false);
define('WP_HOME', '$siteUrl');
define('WP_SITEURL', '$siteUrl');
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';
PHP;

file_put_contents("$sitePath/wp-config.php", $wpConfig);
echo "  ✓ wp-config.php generated\n";

// ── Step 4: Install WordPress via SQL ──
echo "[5/8] Installing WordPress...\n";
$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("SET SESSION sql_mode = ''");

// Create core tables
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS wp_options (
  option_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  option_name VARCHAR(191) NOT NULL DEFAULT '',
  option_value LONGTEXT NOT NULL,
  autoload VARCHAR(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (option_id),
  UNIQUE KEY option_name (option_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_users (
  ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_login VARCHAR(60) NOT NULL DEFAULT '',
  user_pass VARCHAR(255) NOT NULL DEFAULT '',
  user_nicename VARCHAR(50) NOT NULL DEFAULT '',
  user_email VARCHAR(100) NOT NULL DEFAULT '',
  user_url VARCHAR(100) NOT NULL DEFAULT '',
  user_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  user_activation_key VARCHAR(255) NOT NULL DEFAULT '',
  user_status INT(11) NOT NULL DEFAULT '0',
  display_name VARCHAR(250) NOT NULL DEFAULT '',
  PRIMARY KEY (ID),
  KEY user_login_key (user_login),
  KEY user_nicename (user_nicename),
  KEY user_email (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_usermeta (
  umeta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  meta_key VARCHAR(255) DEFAULT NULL,
  meta_value LONGTEXT,
  PRIMARY KEY (umeta_id),
  KEY user_id (user_id),
  KEY meta_key (meta_key(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_posts (
  ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  post_author BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  post_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_content LONGTEXT NOT NULL,
  post_title TEXT NOT NULL,
  post_excerpt TEXT NOT NULL,
  post_status VARCHAR(20) NOT NULL DEFAULT 'publish',
  comment_status VARCHAR(20) NOT NULL DEFAULT 'closed',
  ping_status VARCHAR(20) NOT NULL DEFAULT 'closed',
  post_password VARCHAR(255) NOT NULL DEFAULT '',
  post_name VARCHAR(200) NOT NULL DEFAULT '',
  to_ping TEXT NOT NULL,
  pinged TEXT NOT NULL,
  post_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_modified_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_content_filtered LONGTEXT NOT NULL,
  post_parent BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  guid VARCHAR(255) NOT NULL DEFAULT '',
  menu_order INT(11) NOT NULL DEFAULT 0,
  post_type VARCHAR(20) NOT NULL DEFAULT 'post',
  post_mime_type VARCHAR(100) NOT NULL DEFAULT '',
  comment_count BIGINT(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (ID),
  KEY post_name (post_name(191)),
  KEY type_status_date (post_type,post_status,post_date,ID),
  KEY post_parent (post_parent),
  KEY post_author (post_author)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_postmeta (
  meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  post_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  meta_key VARCHAR(255) DEFAULT NULL,
  meta_value LONGTEXT,
  PRIMARY KEY (meta_id),
  KEY post_id (post_id),
  KEY meta_key (meta_key(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_comments (
  comment_ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  comment_post_ID BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  comment_author TINYTEXT NOT NULL,
  comment_author_email VARCHAR(100) NOT NULL DEFAULT '',
  comment_author_url VARCHAR(200) NOT NULL DEFAULT '',
  comment_author_IP VARCHAR(100) NOT NULL DEFAULT '',
  comment_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  comment_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  comment_content TEXT NOT NULL,
  comment_karma INT(11) NOT NULL DEFAULT 0,
  comment_approved VARCHAR(20) NOT NULL DEFAULT '1',
  comment_agent VARCHAR(255) NOT NULL DEFAULT '',
  comment_type VARCHAR(20) NOT NULL DEFAULT 'comment',
  comment_parent BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (comment_ID),
  KEY comment_post_ID (comment_post_ID),
  KEY comment_approved_date_gmt (comment_approved,comment_date_gmt),
  KEY comment_date_gmt (comment_date_gmt),
  KEY comment_parent (comment_parent),
  KEY comment_author_email (comment_author_email(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_commentmeta (
  meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  comment_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  meta_key VARCHAR(255) DEFAULT NULL,
  meta_value LONGTEXT,
  PRIMARY KEY (meta_id),
  KEY comment_id (comment_id),
  KEY meta_key (meta_key(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_terms (
  term_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL DEFAULT '',
  slug VARCHAR(200) NOT NULL DEFAULT '',
  term_group BIGINT(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (term_id),
  KEY slug (slug(191)),
  KEY name (name(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_term_taxonomy (
  term_taxonomy_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  term_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  taxonomy VARCHAR(32) NOT NULL DEFAULT '',
  description LONGTEXT NOT NULL,
  parent BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  count BIGINT(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (term_taxonomy_id),
  UNIQUE KEY term_id_taxonomy (term_id,taxonomy),
  KEY taxonomy (taxonomy)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_term_relationships (
  object_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  term_taxonomy_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  term_order INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (object_id,term_taxonomy_id),
  KEY term_taxonomy_id (term_taxonomy_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_termmeta (
  meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  term_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  meta_key VARCHAR(255) DEFAULT NULL,
  meta_value LONGTEXT,
  PRIMARY KEY (meta_id),
  KEY term_id (term_id),
  KEY meta_key (meta_key(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS wp_links (
  link_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  link_url VARCHAR(255) NOT NULL DEFAULT '',
  link_name VARCHAR(255) NOT NULL DEFAULT '',
  link_image VARCHAR(255) NOT NULL DEFAULT '',
  link_target VARCHAR(25) NOT NULL DEFAULT '',
  link_description VARCHAR(255) NOT NULL DEFAULT '',
  link_visible VARCHAR(20) NOT NULL DEFAULT 'Y',
  link_owner BIGINT(20) UNSIGNED NOT NULL DEFAULT 1,
  link_rating INT(11) NOT NULL DEFAULT 0,
  link_updated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  link_rel VARCHAR(255) NOT NULL DEFAULT '',
  link_notes MEDIUMTEXT NOT NULL,
  link_rss VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (link_id),
  KEY link_visible (link_visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

// Execute table creation
$db->exec($sql);
echo "  ✓ Core tables created\n";

// Insert admin user
$now = date('Y-m-d H:i:s');
$passHash = password_hash($wpPass, PASSWORD_BCRYPT);
$db->exec("INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, user_registered, display_name) VALUES ('$wpUser', '$passHash', '$wpUser', '$wpEmail', '$now', 'Admin')");
$userId = $db->lastInsertId();

// User meta
$capabilities = serialize(['administrator' => true]);
$metas = [
    'wp_capabilities' => $capabilities,
    'wp_user_level' => '10',
    'nickname' => $wpUser,
    'first_name' => '',
    'last_name' => '',
    'description' => '',
    'rich_editing' => 'true',
    'syntax_highlighting' => 'true',
    'show_admin_bar_front' => 'true',
];
$stmtMeta = $db->prepare("INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, ?, ?)");
foreach ($metas as $k => $v) {
    $stmtMeta->execute([$userId, $k, $v]);
}
echo "  ✓ Admin user created (admin / admin123)\n";

// Insert core options
$theme = 'flavor-starter';
$options = [
    'siteurl' => $siteUrl,
    'home' => $siteUrl,
    'blogname' => $siteTitle,
    'blogdescription' => 'Virtual Basketball Training Camps',
    'users_can_register' => '0',
    'admin_email' => $wpEmail,
    'start_of_week' => '1',
    'use_balanceTags' => '0',
    'use_smilies' => '1',
    'require_name_email' => '1',
    'comments_notify' => '1',
    'posts_per_rss' => '10',
    'rss_use_excerpt' => '0',
    'mailserver_url' => 'mail.example.com',
    'mailserver_login' => 'login@example.com',
    'mailserver_pass' => 'password',
    'mailserver_port' => '110',
    'default_category' => '1',
    'default_comment_status' => 'closed',
    'default_ping_status' => 'closed',
    'default_pingback_flag' => '0',
    'posts_per_page' => '10',
    'date_format' => 'F j, Y',
    'time_format' => 'g:i a',
    'links_updated_date_format' => 'F j, Y g:i a',
    'comment_moderation' => '0',
    'moderation_notify' => '1',
    'permalink_structure' => '/%postname%/',
    'rewrite_rules' => '',
    'template' => $theme,
    'stylesheet' => $theme,
    'current_theme' => $theme,
    'comment_registration' => '0',
    'default_role' => 'subscriber',
    'db_version' => '58975',
    'initial_db_version' => '58975',
    'wp_user_roles' => serialize([
        'administrator' => ['name' => 'Administrator', 'capabilities' => [
            'switch_themes' => true, 'edit_themes' => true, 'activate_plugins' => true,
            'edit_plugins' => true, 'edit_users' => true, 'edit_files' => true,
            'manage_options' => true, 'moderate_comments' => true, 'manage_categories' => true,
            'manage_links' => true, 'upload_files' => true, 'import' => true,
            'unfiltered_html' => true, 'edit_posts' => true, 'edit_others_posts' => true,
            'edit_published_posts' => true, 'publish_posts' => true, 'edit_pages' => true,
            'read' => true, 'level_10' => true, 'level_9' => true, 'level_8' => true,
            'level_7' => true, 'level_6' => true, 'level_5' => true, 'level_4' => true,
            'level_3' => true, 'level_2' => true, 'level_1' => true, 'level_0' => true,
            'edit_others_pages' => true, 'edit_published_pages' => true, 'publish_pages' => true,
            'delete_pages' => true, 'delete_others_pages' => true, 'delete_published_pages' => true,
            'delete_posts' => true, 'delete_others_posts' => true, 'delete_published_posts' => true,
            'delete_private_posts' => true, 'edit_private_posts' => true, 'read_private_posts' => true,
            'delete_private_pages' => true, 'edit_private_pages' => true, 'read_private_pages' => true,
            'delete_users' => true, 'create_users' => true, 'unfiltered_upload' => true,
            'edit_dashboard' => true, 'update_plugins' => true, 'delete_plugins' => true,
            'install_plugins' => true, 'update_themes' => true, 'install_themes' => true,
            'update_core' => true, 'list_users' => true, 'remove_users' => true,
            'promote_users' => true, 'edit_theme_options' => true, 'delete_themes' => true,
            'export' => true, 'manage_woocommerce' => true,
        ]],
        'editor' => ['name' => 'Editor', 'capabilities' => ['moderate_comments' => true, 'manage_categories' => true, 'manage_links' => true, 'upload_files' => true, 'unfiltered_html' => true, 'edit_posts' => true, 'edit_others_posts' => true, 'edit_published_posts' => true, 'publish_posts' => true, 'edit_pages' => true, 'read' => true, 'edit_others_pages' => true, 'edit_published_pages' => true, 'publish_pages' => true, 'delete_pages' => true, 'delete_others_pages' => true, 'delete_published_pages' => true, 'delete_posts' => true, 'delete_others_posts' => true, 'delete_published_posts' => true, 'delete_private_posts' => true, 'edit_private_posts' => true, 'read_private_posts' => true, 'delete_private_pages' => true, 'edit_private_pages' => true, 'read_private_pages' => true]],
        'author' => ['name' => 'Author', 'capabilities' => ['upload_files' => true, 'edit_posts' => true, 'edit_published_posts' => true, 'publish_posts' => true, 'read' => true, 'delete_posts' => true, 'delete_published_posts' => true]],
        'contributor' => ['name' => 'Contributor', 'capabilities' => ['edit_posts' => true, 'read' => true, 'delete_posts' => true]],
        'subscriber' => ['name' => 'Subscriber', 'capabilities' => ['read' => true]],
    ]),
    'show_on_front' => 'page',
    'page_on_front' => '0', // will set after creating page
    'page_for_posts' => '0',
    'WPLANG' => '',
    'fresh_site' => '0',
    'widget_block' => serialize([]),
    'sidebars_widgets' => serialize(['wp_inactive_widgets' => []]),
    'cron' => serialize([time() + 60 => ['wp_cron_check' => ['args' => []]]]),
    // Elementor settings
    'elementor_disable_color_schemes' => 'yes',
    'elementor_disable_typography_schemes' => 'yes',
    'elementor_cpt_support' => serialize(['post', 'page', 'elementor-hf']),
    'elementor_experiment-container' => 'active',
    'elementor_experiment-e_swiper_latest' => 'active',
    'elementor_experiment-e_nested_atomic_repeaters' => 'active',
    'elementor_experiment-e_optimized_css_loading' => 'active',
    'elementor_unfiltered_files_upload' => '1',
];

$stmtOpt = $db->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
foreach ($options as $k => $v) {
    $stmtOpt->execute([$k, $v]);
}
echo "  ✓ WordPress options configured\n";

// Activate plugins
$activePlugins = [];
$pluginChecks = [
    'elementor/elementor.php',
    'elementor-pro/elementor-pro.php',
    'header-footer-elementor/header-footer-elementor.php',
];
foreach ($pluginChecks as $p) {
    if (file_exists("$sitePath/wp-content/plugins/$p")) {
        $activePlugins[] = $p;
    }
}
$stmtOpt->execute(['active_plugins', serialize($activePlugins)]);
echo "  ✓ Plugins activated: " . implode(', ', $activePlugins) . "\n";

// Create Elementor default kit
$db->exec("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
VALUES (1, '$now', '$now', '', 'Default Kit', '', 'publish', 'closed', 'closed', '', 'default-kit', '', '', '$now', '$now', '', 0, '$siteUrl/?p=1', 0, 'elementor_library', '', 0)");
$kitId = $db->lastInsertId();

$stmtPM = $db->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
$stmtPM->execute([$kitId, '_elementor_data', '[]']);
$stmtPM->execute([$kitId, '_elementor_edit_mode', 'builder']);
$stmtPM->execute([$kitId, '_elementor_template_type', 'kit']);
$stmtPM->execute([$kitId, '_elementor_version', '3.35.6']);

$db->exec("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('elementor_active_kit', '$kitId', 'yes')");
echo "  ✓ Elementor kit created (ID: $kitId)\n";

// ── Step 5: Create homepage with Elementor Canvas + HTML widget ──
echo "[6/8] Creating homepage with HTML content...\n";

// Read HTML file and extract parts
$rawHtml = file_get_contents($htmlFile);
if (!$rawHtml) {
    die("  ✗ Could not read $htmlFile\n");
}

// Extract Google Fonts link
preg_match('/<link[^>]*fonts\.googleapis\.com[^>]*>/s', $rawHtml, $fontMatch);
$fontLink = $fontMatch[0] ?? '';

// Extract <style> block
preg_match('/<style>(.*?)<\/style>/s', $rawHtml, $styleMatch);
$cssBlock = $styleMatch[1] ?? '';

// Extract body inner content (between <body> and </body>)
preg_match('/<body[^>]*>(.*?)<\/body>/s', $rawHtml, $bodyMatch);
$bodyContent = trim($bodyMatch[1] ?? '');

// Extract <script> block
preg_match('/<script>(.*?)<\/script>/s', $rawHtml, $scriptMatch);
$jsBlock = $scriptMatch[1] ?? '';

// Reset CSS to override WP/Elementor defaults
$resetCss = <<<CSS
/* Reset WP/Elementor interference */
.elementor-widget-html { width: 100% !important; }
.elementor-element { padding: 0 !important; margin: 0 !important; }
.e-con { --padding-top: 0px; --padding-right: 0px; --padding-bottom: 0px; --padding-left: 0px; --margin-top: 0px; --margin-right: 0px; --margin-bottom: 0px; --margin-left: 0px; gap: 0 !important; }
body.elementor-template-canvas { margin: 0; padding: 0; background: #080808; }
CSS;

// Combine: font link + reset + style + body HTML + script
$htmlContent = $fontLink . "\n<style>\n" . $resetCss . "\n" . $cssBlock . "\n</style>\n" . $bodyContent . "\n<script>\n" . $jsBlock . "\n</script>";

// Generate Elementor element IDs (7 hex chars)
function eid() {
    return substr(bin2hex(random_bytes(4)), 0, 7);
}

$sectionId = eid();
$containerId = eid();
$htmlWidgetId = eid();

// The Elementor data structure: a Container with an HTML widget
$elementorData = [
    [
        'id' => $sectionId,
        'elType' => 'container',
        'settings' => [
            'content_width' => 'full-width',
            'padding' => [
                'unit' => 'px',
                'top' => '0',
                'right' => '0',
                'bottom' => '0',
                'left' => '0',
                'isLinked' => false,
            ],
            'margin' => [
                'unit' => 'px',
                'top' => '0',
                'right' => '0',
                'bottom' => '0',
                'left' => '0',
                'isLinked' => false,
            ],
            'gap' => [
                'unit' => 'px',
                'size' => 0,
            ],
        ],
        'elements' => [
            [
                'id' => $htmlWidgetId,
                'elType' => 'widget',
                'widgetType' => 'html',
                'settings' => [
                    'html' => $htmlContent,
                ],
                'elements' => [],
            ],
        ],
    ],
];

$elementorJson = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Insert the homepage
$db->exec("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type, comment_count)
VALUES (1, '$now', '$now', '', 'Home', '', 'publish', 'closed', 'closed', '', 'home', '', '', '$now', '$now', '', 0, '$siteUrl/?page_id=2', 0, 'page', '', 0)");
$homeId = $db->lastInsertId();

// Set Elementor meta
$stmtPM->execute([$homeId, '_elementor_edit_mode', 'builder']);
$stmtPM->execute([$homeId, '_elementor_version', '3.35.6']);
$stmtPM->execute([$homeId, '_wp_page_template', 'elementor_canvas']);
$stmtPM->execute([$homeId, '_elementor_page_settings', serialize(['hide_title' => 'yes'])]);
$stmtPM->execute([$homeId, '_elementor_css', '']);

// Insert Elementor data using prepared statement
$stmtData = $db->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, '_elementor_data', ?)");
$stmtData->execute([$homeId, $elementorJson]);

echo "  ✓ Homepage created (ID: $homeId) with Elementor Canvas template\n";

// ── Step 6: Set homepage as front page ──
echo "[7/8] Setting homepage as front page...\n";
$db->exec("UPDATE wp_options SET option_value = '$homeId' WHERE option_name = 'page_on_front'");
echo "  ✓ Front page set to Homepage\n";

// ── Step 7: Create .htaccess ──
echo "[8/8] Creating .htaccess...\n";
$htaccess = <<<HT
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /zaid/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /zaid/index.php [L]
</IfModule>
# END WordPress
HT;
file_put_contents("$sitePath/.htaccess", $htaccess);
echo "  ✓ .htaccess created\n";

// Create uploads directory
@mkdir("$sitePath/wp-content/uploads/" . date('Y/m'), 0755, true);

echo "\n=== DONE! ===\n";
echo "Site URL:    $siteUrl\n";
echo "Admin URL:   $siteUrl/wp-admin\n";
echo "Username:    $wpUser\n";
echo "Password:    $wpPass\n";
echo "Homepage:    $siteUrl (Elementor Canvas with full HTML)\n";
