<?php

namespace App\Console\Commands;

use App\Services\WordPressService;
use App\Services\WxrImporterService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetupEnvatoMasterCommand extends Command
{
    protected $signature = 'setup:envato-master
        {theme? : Theme slug (barab, geoport, transland, etc.)}
        {--all : Setup all configured master sites}
        {--fresh : Drop existing database and re-create from scratch}
        {--list : Show all configured themes and their setup status}';

    protected $description = 'Setup Envato master WordPress sites for clone-based provisioning';

    private string $htdocsPath;
    private string $baseWpPath;
    private string $dbHost;
    private string $dbUser;
    private string $dbPass;
    private string $mysqlBin;

    public function handle(): int
    {
        $this->htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $this->baseWpPath = config('webnewbiz.wp_base_path', 'C:/xampp/htdocs/wordpress');
        $this->dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $this->dbUser = config('database.connections.mysql.username', 'root');
        $this->dbPass = config('database.connections.mysql.password', '');
        $this->mysqlBin = config('webnewbiz.mysql_bin', 'C:/xampp/mysql/bin/mysql.exe');

        $themes = config('webnewbiz.envato_masters');

        if ($this->option('list')) {
            return $this->listThemes($themes);
        }

        if ($this->option('all')) {
            $slugs = array_keys($themes);
        } elseif ($this->argument('theme')) {
            $slug = $this->argument('theme');
            if (!isset($themes[$slug])) {
                $this->error("Unknown theme: {$slug}. Available: " . implode(', ', array_keys($themes)));
                return 1;
            }
            $slugs = [$slug];
        } else {
            $this->error('Specify a theme name or use --all');
            return 1;
        }

        foreach ($slugs as $slug) {
            $label = $themes[$slug]['label'] ?? $slug;
            $bestFor = $themes[$slug]['best_for'] ?? '';
            $this->info("========================================");
            $this->info("Setting up master site: {$slug} ({$label})");
            if ($bestFor) {
                $this->info("  Best for: {$bestFor}");
            }
            $this->info("========================================");

            try {
                $this->setupMaster($slug, $themes[$slug]);
                $this->info("Master site {$slug} setup complete!");
                $this->newLine();
            } catch (\Exception $e) {
                $this->error("Failed to setup {$slug}: {$e->getMessage()}");
                Log::error("Envato master setup failed for {$slug}: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
            }
        }

        $this->info('Done!');
        return 0;
    }

    private function listThemes(array $themes): int
    {
        $this->info('Configured Envato Master Themes:');
        $this->newLine();

        $rows = [];
        foreach ($themes as $slug => $config) {
            $masterExists = File::isDirectory($config['path']);
            $themeSourceExists = File::isDirectory(base_path($config['theme_source']));
            $demoXmlExists = file_exists(base_path($config['demo_xml']));

            $status = match (true) {
                $masterExists => '<fg=green>READY</>',
                $themeSourceExists && $demoXmlExists => '<fg=yellow>NOT SET UP</> (source ready)',
                $themeSourceExists => '<fg=yellow>NOT SET UP</> (missing demo XML)',
                default => '<fg=red>NOT AVAILABLE</> (missing theme source)',
            };

            $rows[] = [
                $slug,
                $config['label'] ?? '-',
                $config['best_for'] ?? '-',
                $status,
                $config['path'],
            ];
        }

        $this->table(
            ['Slug', 'Label', 'Best For', 'Status', 'Path'],
            $rows
        );

        $readyCount = collect($themes)->filter(fn($c) => File::isDirectory($c['path']))->count();
        $this->newLine();
        $this->info("{$readyCount}/" . count($themes) . " master sites are ready.");

        return 0;
    }

    private function setupMaster(string $slug, array $config): void
    {
        $sitePath = $config['path'];
        $dbName = $config['db'];
        $siteUrl = 'http://localhost/master-' . $slug;

        // Step 1: Create database
        $this->info("  [1/13] Creating database {$dbName}...");
        $this->createDatabase($dbName);

        // Step 2: Copy WordPress core files
        $this->info("  [2/13] Copying WordPress files to {$sitePath}...");
        $this->copyWordPress($sitePath);

        // Step 3: Copy Envato theme
        $this->info("  [3/13] Copying {$slug} theme...");
        $themeSource = base_path($config['theme_source']);
        $themeDest = $sitePath . '/wp-content/themes/' . $config['theme_slug'];
        if (!File::isDirectory($themeSource)) {
            throw new \RuntimeException("Theme source not found: {$themeSource}");
        }
        $this->robocopy($themeSource, $themeDest);

        // Step 4: Copy companion plugin
        $this->info("  [4/13] Copying companion plugin...");
        $plugin = $config['companion_plugin'];
        $pluginDest = $sitePath . '/wp-content/plugins/' . $plugin['slug'];
        if (isset($plugin['source_dir'])) {
            $this->robocopy(base_path($plugin['source_dir']), $pluginDest);
        } elseif (isset($plugin['source'])) {
            $zipPath = base_path($plugin['source']);
            if (!file_exists($zipPath)) {
                throw new \RuntimeException("Plugin zip not found: {$zipPath}");
            }
            $this->extractZip($zipPath, $sitePath . '/wp-content/plugins/');
        }

        // Step 5: Copy shared plugins
        $this->info("  [5/13] Copying shared plugins...");
        $sharedPlugins = [
            'elementor-pro', 'header-footer-elementor', 'woocommerce',
            'redux-framework', 'contact-form-7', 'cmb2',
        ];
        foreach ($sharedPlugins as $pluginSlug) {
            $source = base_path('prebuild/plugins/' . $pluginSlug);
            if (File::isDirectory($source)) {
                $dest = $sitePath . '/wp-content/plugins/' . $pluginSlug;
                $this->robocopy($source, $dest);
            }
        }

        // Step 5b: Copy mu-plugins
        $this->info("  [6/13] Copying mu-plugins...");
        $muSource = base_path('prebuild/mu-plugins');
        $muDest = $sitePath . '/wp-content/mu-plugins';
        if (File::isDirectory($muSource)) {
            if (!File::isDirectory($muDest)) {
                File::makeDirectory($muDest, 0755, true);
            }
            foreach (File::files($muSource) as $file) {
                $destFile = $muDest . '/' . $file->getFilename();
                if (!File::exists($destFile)) {
                    File::copy($file->getPathname(), $destFile);
                }
            }
        }

        // Step 6: Generate wp-config.php
        $this->info("  [7/13] Generating wp-config.php...");
        $this->generateWpConfig($sitePath, $dbName, $siteUrl);

        // Step 7: Install WordPress via SQL
        $this->info("  [8/13] Installing WordPress tables...");
        $this->installWordPress($dbName, $siteUrl, $config['theme_slug']);

        // Step 8: Import demo content XML
        $this->info("  [9/13] Importing demo content XML...");
        $xmlPath = base_path($config['demo_xml']);
        if (!file_exists($xmlPath)) {
            $this->warn("  Demo XML not found: {$xmlPath}");
        } else {
            $importer = new WxrImporterService();
            $importer->setDemoDomain($config['demo_domain']);
            $stats = $importer->import($dbName, $xmlPath, $siteUrl);
            $this->line("  Imported: {$stats['posts']} posts, {$stats['attachments']} attachments, {$stats['terms']} terms");
        }

        // Step 9: Import theme options
        $this->info("  [10/13] Importing theme options...");
        $optionsPath = base_path($config['demo_options']);
        $importer = new WxrImporterService();
        if ($config['options_format'] === 'redux_json') {
            $importer->importReduxOptions($dbName, $optionsPath);
        } else {
            $importer->importCodestarOptions($dbName, $optionsPath);
        }

        // Step 10: Activate theme + plugins
        $this->info("  [11/13] Activating theme and plugins...");
        $this->activateThemeAndPlugins($dbName, $sitePath, $config);

        // Step 11: Generate .htaccess
        $this->info("  [12/13] Generating .htaccess...");
        $this->generateHtaccess($sitePath, 'master-' . $slug);

        // Step 12: Create uploads directory
        $uploadsPath = $sitePath . '/wp-content/uploads/' . date('Y/m');
        if (!File::isDirectory($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
        }

        // Step 13: Set permalinks and basic options
        $this->info("  [13/13] Setting WP options...");
        $pdo = $this->getPdo($dbName);
        $this->setOption($pdo, 'permalink_structure', '/%postname%/');
        $this->setOption($pdo, 'default_comment_status', 'closed');
        $this->setOption($pdo, 'default_ping_status', 'closed');
        $this->setOption($pdo, 'blogname', ucfirst($slug) . ' Master');

        $this->info("  URL: {$siteUrl}");
        $this->info("  Admin: {$siteUrl}/wp-admin (admin / admin123)");
    }

    private function createDatabase(string $dbName): void
    {
        $fresh = $this->option('fresh');
        $pdo = new \PDO("mysql:host={$this->dbHost}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if ($fresh) {
            $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
        }

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    private function copyWordPress(string $destPath): void
    {
        if ($this->option('fresh') && File::isDirectory($destPath)) {
            // Remove existing to do fresh copy
            File::deleteDirectory($destPath);
        }

        if (File::isDirectory($destPath)) {
            return;
        }

        $this->robocopy($this->baseWpPath, $destPath);
    }

    private function robocopy(string $src, string $dest): void
    {
        if (File::isDirectory($dest) && !$this->option('fresh')) {
            return;
        }

        $srcWin = str_replace('/', '\\', $src);
        $destWin = str_replace('/', '\\', $dest);
        $cmd = "robocopy \"{$srcWin}\" \"{$destWin}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
        exec($cmd, $output, $exitCode);

        if ($exitCode > 7) {
            throw new \RuntimeException("robocopy failed (exit {$exitCode}): {$srcWin} → {$destWin}");
        }
    }

    private function extractZip(string $zipPath, string $destDir): void
    {
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException("Failed to open zip: {$zipPath}");
        }

        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $zip->extractTo($destDir);
        $zip->close();
    }

    private function generateWpConfig(string $sitePath, string $dbName, string $siteUrl): void
    {
        $salts = $this->generateSalts();

        $config = <<<PHP
<?php
define('DB_NAME', '{$dbName}');
define('DB_USER', '{$this->dbUser}');
define('DB_PASSWORD', '{$this->dbPass}');
define('DB_HOST', '{$this->dbHost}');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

{$salts}

\$table_prefix = 'wp_';

define('WP_DEBUG', false);
define('WP_SITEURL', '{$siteUrl}');
define('WP_HOME', '{$siteUrl}');
define('FS_METHOD', 'direct');
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
PHP;

        File::put($sitePath . '/wp-config.php', $config);
    }

    private function generateSalts(): string
    {
        $keys = ['AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
                 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT'];
        $lines = [];
        foreach ($keys as $key) {
            $salt = Str::random(64);
            $lines[] = "define('{$key}', '{$salt}');";
        }
        return implode("\n", $lines);
    }

    private function generateHtaccess(string $sitePath, string $subdomain): void
    {
        $htaccess = <<<HTACCESS
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /{$subdomain}/
RewriteRule ^index\\.php\$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /{$subdomain}/index.php [L]
</IfModule>
# END WordPress
HTACCESS;

        File::put($sitePath . '/.htaccess', $htaccess);
    }

    private function installWordPress(string $dbName, string $siteUrl, string $themeSlug): void
    {
        $pdo = $this->getPdo($dbName);
        $charset = 'utf8mb4';
        $collate = 'utf8mb4_unicode_ci';

        // Disable strict mode for WordPress-compatible table creation
        $pdo->exec("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

        // Check if tables already exist
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        if (in_array('wp_options', $tables) && !$this->option('fresh')) {
            $this->line("  Tables already exist, skipping table creation");
            return;
        }

        // Create all WordPress tables
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_options` (
                option_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                option_name VARCHAR(191) NOT NULL DEFAULT '',
                option_value LONGTEXT NOT NULL,
                autoload VARCHAR(20) NOT NULL DEFAULT 'yes',
                PRIMARY KEY (option_id),
                UNIQUE KEY option_name (option_name),
                KEY autoload (autoload)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_users` (
                ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_login VARCHAR(60) NOT NULL DEFAULT '',
                user_pass VARCHAR(255) NOT NULL DEFAULT '',
                user_nicename VARCHAR(50) NOT NULL DEFAULT '',
                user_email VARCHAR(100) NOT NULL DEFAULT '',
                user_url VARCHAR(100) NOT NULL DEFAULT '',
                user_registered DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                user_activation_key VARCHAR(255) NOT NULL DEFAULT '',
                user_status INT NOT NULL DEFAULT 0,
                display_name VARCHAR(250) NOT NULL DEFAULT '',
                PRIMARY KEY (ID),
                KEY user_login_key (user_login),
                KEY user_nicename (user_nicename),
                KEY user_email (user_email)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_usermeta` (
                umeta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                meta_key VARCHAR(255) DEFAULT NULL,
                meta_value LONGTEXT,
                PRIMARY KEY (umeta_id),
                KEY user_id (user_id),
                KEY meta_key (meta_key(191))
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_posts` (
                ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                post_author BIGINT UNSIGNED NOT NULL DEFAULT 0,
                post_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                post_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                post_content LONGTEXT NOT NULL,
                post_title TEXT NOT NULL,
                post_excerpt TEXT NOT NULL,
                post_status VARCHAR(20) NOT NULL DEFAULT 'publish',
                comment_status VARCHAR(20) NOT NULL DEFAULT 'open',
                ping_status VARCHAR(20) NOT NULL DEFAULT 'open',
                post_password VARCHAR(255) NOT NULL DEFAULT '',
                post_name VARCHAR(200) NOT NULL DEFAULT '',
                to_ping TEXT NOT NULL,
                pinged TEXT NOT NULL,
                post_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                post_modified_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                post_content_filtered LONGTEXT NOT NULL,
                post_parent BIGINT UNSIGNED NOT NULL DEFAULT 0,
                guid VARCHAR(255) NOT NULL DEFAULT '',
                menu_order INT NOT NULL DEFAULT 0,
                post_type VARCHAR(20) NOT NULL DEFAULT 'post',
                post_mime_type VARCHAR(100) NOT NULL DEFAULT '',
                comment_count BIGINT NOT NULL DEFAULT 0,
                PRIMARY KEY (ID),
                KEY post_name (post_name(191)),
                KEY type_status_date (post_type, post_status, post_date, ID),
                KEY post_parent (post_parent),
                KEY post_author (post_author)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_postmeta` (
                meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                post_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                meta_key VARCHAR(255) DEFAULT NULL,
                meta_value LONGTEXT,
                PRIMARY KEY (meta_id),
                KEY post_id (post_id),
                KEY meta_key (meta_key(191))
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_terms` (
                term_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(200) NOT NULL DEFAULT '',
                slug VARCHAR(200) NOT NULL DEFAULT '',
                term_group BIGINT NOT NULL DEFAULT 0,
                PRIMARY KEY (term_id),
                KEY slug (slug(191)),
                KEY name (name(191))
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_term_taxonomy` (
                term_taxonomy_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                term_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                taxonomy VARCHAR(32) NOT NULL DEFAULT '',
                description LONGTEXT NOT NULL,
                parent BIGINT UNSIGNED NOT NULL DEFAULT 0,
                count BIGINT NOT NULL DEFAULT 0,
                PRIMARY KEY (term_taxonomy_id),
                UNIQUE KEY term_id_taxonomy (term_id, taxonomy),
                KEY taxonomy (taxonomy)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_term_relationships` (
                object_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                term_taxonomy_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                term_order INT NOT NULL DEFAULT 0,
                PRIMARY KEY (object_id, term_taxonomy_id),
                KEY term_taxonomy_id (term_taxonomy_id)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_comments` (
                comment_ID BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                comment_post_ID BIGINT UNSIGNED NOT NULL DEFAULT 0,
                comment_author TINYTEXT NOT NULL,
                comment_author_email VARCHAR(100) NOT NULL DEFAULT '',
                comment_author_url VARCHAR(200) NOT NULL DEFAULT '',
                comment_author_IP VARCHAR(100) NOT NULL DEFAULT '',
                comment_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                comment_date_gmt DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                comment_content TEXT NOT NULL,
                comment_karma INT NOT NULL DEFAULT 0,
                comment_approved VARCHAR(20) NOT NULL DEFAULT '1',
                comment_agent VARCHAR(255) NOT NULL DEFAULT '',
                comment_type VARCHAR(20) NOT NULL DEFAULT 'comment',
                comment_parent BIGINT UNSIGNED NOT NULL DEFAULT 0,
                user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                PRIMARY KEY (comment_ID),
                KEY comment_post_ID (comment_post_ID),
                KEY comment_approved_date_gmt (comment_approved, comment_date_gmt),
                KEY comment_date_gmt (comment_date_gmt),
                KEY comment_parent (comment_parent),
                KEY comment_author_email (comment_author_email(10))
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_commentmeta` (
                meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                comment_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
                meta_key VARCHAR(255) DEFAULT NULL,
                meta_value LONGTEXT,
                PRIMARY KEY (meta_id),
                KEY comment_id (comment_id),
                KEY meta_key (meta_key(191))
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `wp_links` (
                link_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                link_url VARCHAR(255) NOT NULL DEFAULT '',
                link_name VARCHAR(255) NOT NULL DEFAULT '',
                link_image VARCHAR(255) NOT NULL DEFAULT '',
                link_target VARCHAR(25) NOT NULL DEFAULT '',
                link_description VARCHAR(255) NOT NULL DEFAULT '',
                link_visible VARCHAR(20) NOT NULL DEFAULT 'Y',
                link_owner BIGINT UNSIGNED NOT NULL DEFAULT 1,
                link_rating INT NOT NULL DEFAULT 0,
                link_updated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                link_rel VARCHAR(255) NOT NULL DEFAULT '',
                link_notes MEDIUMTEXT NOT NULL,
                link_rss VARCHAR(255) NOT NULL DEFAULT '',
                PRIMARY KEY (link_id),
                KEY link_visible (link_visible)
            ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}
        ");

        // Insert default options
        $now = date('Y-m-d H:i:s');
        $wpPass = password_hash('admin123', PASSWORD_BCRYPT);

        // Admin user
        $stmt = $pdo->prepare("INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, user_registered, display_name) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $wpPass, 'admin', 'admin@webnewbiz.com', $now, 'Admin']);

        $userId = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, 'wp_capabilities', ?)")->execute([$userId, serialize(['administrator' => true])]);
        $pdo->prepare("INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, 'wp_user_level', '10')")->execute([$userId]);

        // Default category
        $pdo->exec("INSERT INTO wp_terms (name, slug, term_group) VALUES ('Uncategorized', 'uncategorized', 0)");
        $catTermId = $pdo->lastInsertId();
        $pdo->exec("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES ({$catTermId}, 'category', '', 0, 0)");

        // Essential options
        $essentialOptions = [
            'siteurl' => $siteUrl,
            'home' => $siteUrl,
            'blogname' => ucfirst($themeSlug) . ' Master',
            'blogdescription' => 'Envato Master Site',
            'admin_email' => 'admin@webnewbiz.com',
            'users_can_register' => '0',
            'default_role' => 'subscriber',
            'db_version' => '57155',
            'permalink_structure' => '/%postname%/',
            'show_on_front' => 'page',
            'template' => $themeSlug,
            'stylesheet' => $themeSlug,
            'current_theme' => ucfirst($themeSlug),
            'active_plugins' => serialize([]),
            'widget_block' => serialize([]),
            'sidebars_widgets' => serialize(['wp_inactive_widgets' => []]),
            'default_comment_status' => 'closed',
            'default_ping_status' => 'closed',
            'rewrite_rules' => serialize([
                'index.php' => 'index.php',
                '(.+?)(/[0-9]+)?/?$' => 'index.php?pagename=$matches[1]&page=$matches[2]',
            ]),
            'initial_db_version' => '57155',
            'wp_user_roles' => serialize($this->getDefaultRoles()),
            'fresh_site' => '0',
            'WPLANG' => '',
            'cron' => serialize([]),
        ];

        foreach ($essentialOptions as $key => $value) {
            $this->setOption($pdo, $key, $value);
        }
    }

    private function activateThemeAndPlugins(string $dbName, string $sitePath, array $config): void
    {
        $pdo = $this->getPdo($dbName);

        // Set active theme
        $this->setOption($pdo, 'template', $config['theme_slug']);
        $this->setOption($pdo, 'stylesheet', $config['theme_slug']);

        // Build plugin list from what actually exists on disk
        $plugins = [];
        $pluginsDir = $sitePath . '/wp-content/plugins';

        // Known main files for common plugins (fallback if auto-detect fails)
        $knownFiles = [
            'elementor' => 'elementor/elementor.php',
            'elementor-pro' => 'elementor-pro/elementor-pro.php',
            'contact-form-7' => 'contact-form-7/wp-contact-form-7.php',
            'woocommerce' => 'woocommerce/woocommerce.php',
            'redux-framework' => 'redux-framework/redux-framework.php',
            'cmb2' => 'cmb2/init.php',
            'header-footer-elementor' => 'header-footer-elementor/header-footer-elementor.php',
        ];

        // Add all shared plugins to required list
        $allRequired = array_merge($config['required_plugins'], [
            'redux-framework', 'contact-form-7', 'cmb2',
        ]);
        $allRequired = array_unique($allRequired);

        foreach ($allRequired as $pluginSlug) {
            // Try auto-detection first (works for any theme's companion plugin)
            $detected = $this->detectPluginMainFile($pluginsDir, $pluginSlug);
            if ($detected) {
                $plugins[] = $detected;
                continue;
            }

            // Fallback to known file map
            if (isset($knownFiles[$pluginSlug])) {
                $mainFile = $knownFiles[$pluginSlug];
                if (file_exists($pluginsDir . '/' . $mainFile)) {
                    $plugins[] = $mainFile;
                } else {
                    $this->warn("  Plugin '{$pluginSlug}' not found on disk");
                }
            }
        }

        // Always add elementor-pro and HFE if they exist
        foreach (['elementor-pro/elementor-pro.php', 'header-footer-elementor/header-footer-elementor.php'] as $extra) {
            if (file_exists($pluginsDir . '/' . $extra) && !in_array($extra, $plugins)) {
                $plugins[] = $extra;
            }
        }

        // Always add elementor if it exists
        $elementorMain = 'elementor/elementor.php';
        if (file_exists($pluginsDir . '/' . $elementorMain) && !in_array($elementorMain, $plugins)) {
            array_unshift($plugins, $elementorMain);
        }

        $this->setOption($pdo, 'active_plugins', serialize($plugins));
        $this->line("  Activated " . count($plugins) . " plugins: " . implode(', ', array_map(fn($p) => explode('/', $p)[0], $plugins)));
    }

    private function detectPluginMainFile(string $pluginsDir, string $slug): ?string
    {
        $pluginDir = $pluginsDir . '/' . $slug;
        if (!is_dir($pluginDir)) {
            return null;
        }

        // Look for PHP files with "Plugin Name:" header
        foreach (glob($pluginDir . '/*.php') as $file) {
            $content = file_get_contents($file, false, null, 0, 4096);
            if (stripos($content, 'Plugin Name:') !== false) {
                return $slug . '/' . basename($file);
            }
        }

        return null;
    }

    private function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    private function setOption(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT option_id FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);

        if ($stmt->fetchColumn()) {
            $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?")->execute([$value, $key]);
        } else {
            $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')")->execute([$key, $value]);
        }
    }

    private function getDefaultRoles(): array
    {
        return [
            'administrator' => [
                'name' => 'Administrator',
                'capabilities' => [
                    'switch_themes' => true, 'edit_themes' => true, 'activate_plugins' => true,
                    'edit_plugins' => true, 'edit_users' => true, 'edit_files' => true,
                    'manage_options' => true, 'moderate_comments' => true, 'manage_categories' => true,
                    'manage_links' => true, 'upload_files' => true, 'import' => true,
                    'unfiltered_html' => true, 'edit_posts' => true, 'edit_others_posts' => true,
                    'edit_published_posts' => true, 'publish_posts' => true, 'edit_pages' => true,
                    'read' => true, 'level_10' => true, 'level_9' => true, 'level_8' => true,
                    'level_7' => true, 'level_6' => true, 'level_5' => true, 'level_4' => true,
                    'level_3' => true, 'level_2' => true, 'level_1' => true, 'level_0' => true,
                    'edit_others_pages' => true, 'edit_published_pages' => true,
                    'publish_pages' => true, 'delete_pages' => true, 'delete_others_pages' => true,
                    'delete_published_pages' => true, 'delete_posts' => true,
                    'delete_others_posts' => true, 'delete_published_posts' => true,
                    'delete_private_posts' => true, 'edit_private_posts' => true,
                    'read_private_posts' => true, 'delete_private_pages' => true,
                    'edit_private_pages' => true, 'read_private_pages' => true,
                    'delete_users' => true, 'create_users' => true, 'unfiltered_upload' => true,
                    'edit_dashboard' => true, 'update_plugins' => true, 'delete_plugins' => true,
                    'install_plugins' => true, 'update_themes' => true, 'install_themes' => true,
                    'update_core' => true, 'list_users' => true, 'remove_users' => true,
                    'promote_users' => true, 'edit_theme_options' => true, 'delete_themes' => true,
                    'export' => true,
                ],
            ],
            'editor' => [
                'name' => 'Editor',
                'capabilities' => [
                    'moderate_comments' => true, 'manage_categories' => true, 'manage_links' => true,
                    'upload_files' => true, 'unfiltered_html' => true, 'edit_posts' => true,
                    'edit_others_posts' => true, 'edit_published_posts' => true,
                    'publish_posts' => true, 'edit_pages' => true, 'read' => true,
                    'level_7' => true, 'level_6' => true, 'level_5' => true, 'level_4' => true,
                    'level_3' => true, 'level_2' => true, 'level_1' => true, 'level_0' => true,
                    'edit_others_pages' => true, 'edit_published_pages' => true,
                    'publish_pages' => true, 'delete_pages' => true,
                    'delete_others_pages' => true, 'delete_published_pages' => true,
                    'delete_posts' => true, 'delete_others_posts' => true,
                    'delete_published_posts' => true, 'delete_private_posts' => true,
                    'edit_private_posts' => true, 'read_private_posts' => true,
                    'delete_private_pages' => true, 'edit_private_pages' => true,
                    'read_private_pages' => true,
                ],
            ],
            'subscriber' => [
                'name' => 'Subscriber',
                'capabilities' => ['read' => true, 'level_0' => true],
            ],
        ];
    }
}
