<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WordPressService
{
    private const FALLBACK_THEME_SLUG = 'twentytwentyfive';

    /**
     * Style → WordPress theme slug mapping.
     * Each style selected in the builder maps to a specific flavor-* theme.
     */
    private const STYLE_THEME_MAP = [
        'modern'     => 'flavor-starter',
        'tech'       => 'flavor-developer',
        'elegant'    => 'flavor-elegance',
        'warm'       => 'flavor-freshly',
        'bold'       => 'flavor-blaze',
        'classic'    => 'flavor-oceanic',
        'creative'   => 'flavor-rosewood',
        'luxury'     => 'flavor-sandstone',
        'minimal'    => 'flavor-minimal',
        'neon'       => 'flavor-neon',
        // 10 new flavor themes
        'aurora'     => 'flavor-aurora',
        'harvest'    => 'flavor-harvest',
        'glacier'    => 'flavor-glacier',
        'timber'     => 'flavor-timber',
        'circuit'    => 'flavor-circuit',
        // New template styles (UI keywords)
        'gradient'   => 'flavor-rosewood',
        'dark'       => 'flavor-neon',
        'industrial' => 'flavor-blaze',
        'beauty'     => 'flavor-elegance',
        'fintech'    => 'flavor-developer',
        'gallery'    => 'flavor-minimal',
        'clinical'   => 'flavor-starter',
        'trades'     => 'flavor-blaze',
        'sleek'      => 'flavor-starter',
        'coaching'   => 'flavor-elegance',
        'academic'   => 'flavor-oceanic',
        // Template name aliases
        'bloom'      => 'flavor-elegance',
        'vertex'     => 'flavor-developer',
        'canvas'     => 'flavor-minimal',
        'pulse'      => 'flavor-starter',
        'forge'      => 'flavor-blaze',
        'nova'       => 'flavor-starter',
        'aura'       => 'flavor-elegance',
        'summit'     => 'flavor-summit',
        'ember'      => 'flavor-ember',
        'horizon'    => 'flavor-rosewood',
        'slate'      => 'flavor-slate',
        // Batch 3: 10 new templates
        'royal'      => 'flavor-sandstone',
        'heritage'   => 'flavor-sandstone',
        'crest'      => 'flavor-sandstone',
        'adventure'  => 'flavor-freshly',
        'outdoor'    => 'flavor-freshly',
        'drift'      => 'flavor-freshly',
        'chic'       => 'flavor-elegance',
        'velvet'     => 'flavor-velvet',
        'launch'     => 'flavor-developer',
        'spark'      => 'flavor-developer',
        'eco'        => 'flavor-freshly',
        'green'      => 'flavor-freshly',
        'grove'      => 'flavor-freshly',
        'urban'      => 'flavor-blaze',
        'metro'      => 'flavor-blaze',
        'tropical'   => 'flavor-rosewood',
        'coral'      => 'flavor-rosewood',
        'gaming'     => 'flavor-neon',
        'pixel'      => 'flavor-neon',
        'global'     => 'flavor-oceanic',
        'mosaic'     => 'flavor-mosaic',
        'playful'    => 'flavor-rosewood',
        'prism'      => 'flavor-rosewood',
        // Batch 4: 7 more templates
        'lumen'      => 'flavor-minimal',
        'petal'      => 'flavor-elegance',
        'nexus'      => 'flavor-developer',
        'haven'      => 'flavor-sandstone',
        'rhythm'     => 'flavor-neon',
        'sprout'     => 'flavor-freshly',
        'pinnacle'   => 'flavor-sandstone',
        // Batch 5: 30 new flavor themes
        'cobalt'     => 'flavor-cobalt',
        'sage'       => 'flavor-sage',
        'copper'     => 'flavor-copper',
        'pearl'      => 'flavor-pearl',
        'maple'      => 'flavor-maple',
        'frost'      => 'flavor-frost',
        'amber'      => 'flavor-amber',
        'jade'       => 'flavor-jade',
        'breeze'     => 'flavor-breeze',
        'olive'      => 'flavor-olive',
        'ruby'       => 'flavor-ruby',
        'zinc'       => 'flavor-zinc',
        'cypress'    => 'flavor-cypress',
        'ivory'      => 'flavor-ivory',
        'solar'      => 'flavor-solar',
        'carbon'     => 'flavor-carbon',
        'onyx'       => 'flavor-onyx',
        'dusk'       => 'flavor-dusk',
        'basalt'     => 'flavor-basalt',
        'steel'      => 'flavor-steel',
        'quartz'     => 'flavor-quartz',
        'obsidian'   => 'flavor-obsidian',
        'nebula'     => 'flavor-nebula',
        'mauve'      => 'flavor-mauve',
        'luxe'       => 'flavor-luxe',
        'plum'       => 'flavor-plum',
        'indigo'     => 'flavor-indigo',
        'tundra'     => 'flavor-tundra',
        'bamboo'     => 'flavor-bamboo',
        'garnet'     => 'flavor-garnet',
    ];

    public static function getThemeSlug(string $style): string
    {
        return self::STYLE_THEME_MAP[$style] ?? self::FALLBACK_THEME_SLUG;
    }

    public static function getThemeModsKey(string $themeSlug): string
    {
        return 'theme_mods_' . $themeSlug;
    }

    private static function themeModsKey(string $themeSlug): string
    {
        return 'theme_mods_' . $themeSlug;
    }

    private string $htdocsPath;
    private string $baseWpPath;
    private string $dbHost;
    private string $dbUser;
    private string $dbPass;

    public function __construct()
    {
        $this->htdocsPath = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
        $this->baseWpPath = config('webnewbiz.wp_base_path', 'C:/xampp/htdocs/wordpress');
        $this->dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $this->dbUser = config('database.connections.mysql.username', 'root');
        $this->dbPass = config('database.connections.mysql.password', '');
    }

    /**
     * Create a new WordPress site locally — fully via filesystem + SQL.
     */
    public function createSite(Website $website): array
    {
        try {
            $subdomain = $website->subdomain;
            $sitePath = $this->htdocsPath . '/' . $subdomain;
            $dbName = 'wp_' . Str::slug($subdomain, '_');
            $wpUser = 'admin';
            $wpPass = Str::random(16);
            $wpEmail = $website->wp_admin_email ?: ($website->user->email ?? 'admin@webnewbiz.com');
            $siteUrl = 'http://localhost/' . $subdomain;
            $rawTheme = ($website->ai_theme && $website->ai_theme !== 'auto') ? $website->ai_theme : self::getThemeSlug($website->ai_style ?? 'modern');
            // Layout slugs (noir, azure, etc.) are not WP themes — use a basic WP theme
            $layoutSlugs = ['noir', 'ivory', 'azure', 'blush', 'ember', 'forest', 'slate', 'royal'];
            $themeSlug = in_array($rawTheme, $layoutSlugs) ? 'twentytwentyfive' : $rawTheme;

            // Step 1: Create MySQL database
            $this->createDatabase($dbName);
            Log::info("Created database: {$dbName}");

            // Step 2: Copy WordPress files
            $this->copyWordPress($sitePath);
            Log::info("Copied WordPress to: {$sitePath}");

            // Step 2b: Copy selected flavor theme into the site
            $this->copyThemeToSite($sitePath, $themeSlug);
            Log::info("Copied theme {$themeSlug} to: {$sitePath}");

            // Step 2c: Copy HFE plugin into the site
            $this->copyPluginToSite($sitePath, 'header-footer-elementor');

            // Step 2d: Copy Elementor Pro plugin into the site
            $this->copyPluginToSite($sitePath, 'elementor-pro');

            // Step 2e: Copy WooCommerce plugin (available for all sites, activated for e-commerce)
            $this->copyPluginToSite($sitePath, 'woocommerce');

            // Step 2f: Copy mu-plugins (auto-loaded: custom CSS, Elementor fixes, Font Awesome)
            $this->copyMuPlugins($sitePath);

            // Step 3: Generate wp-config.php
            $this->generateWpConfig($sitePath, $dbName, $siteUrl);
            Log::info("Generated wp-config.php for: {$subdomain}");

            // Step 4: Try dump import, fallback to scratch
            $dumpFile = base_path("Example/backups/{$themeSlug}/dump.sql");
            if (File::exists($dumpFile)) {
                $this->importDump($dbName, $themeSlug);
                $this->updateSiteAfterImport($dbName, $siteUrl, $website->name, $wpPass, $wpEmail, $themeSlug);
                Log::info("WordPress installed via SQL dump for: {$subdomain} (theme: {$themeSlug})");
            } else {
                // Fallback: original scratch method
                $this->installWordPressViaSql($dbName, $website->name, $wpUser, $wpPass, $wpEmail, $siteUrl, $themeSlug);
                Log::info("WordPress installed via SQL (scratch) for: {$subdomain}");

                // Detect e-commerce: form sends 'ecommerce-restaurant', 'ecommerce-fashion', etc.
                $bizType = strtolower($website->ai_business_type ?? '');
                $enableWoo = str_starts_with($bizType, 'ecommerce') || in_array($bizType, ['e-commerce', 'retail']);
                $this->activatePluginsViaDb($dbName, $sitePath, $enableWoo, $themeSlug);
                Log::info("Plugins activated for: {$subdomain} (theme: {$themeSlug})");
            }

            // Step 5: Set permalink structure & other options
            $this->setOption($dbName, 'permalink_structure', '/%postname%/');
            $this->setOption($dbName, 'default_comment_status', 'closed');
            $this->setOption($dbName, 'default_ping_status', 'closed');

            // Step 5a: Generate proper .htaccess for pretty permalinks
            $this->generateHtaccess($sitePath, $subdomain);

            // Step 5b: Setup theme
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->setupTheme($pdo, $website->name, [], $website->ai_style ?? 'modern', $themeSlug);

            // Step 7: Create uploads directory
            $uploadsPath = $sitePath . '/wp-content/uploads/' . date('Y/m');
            if (!File::isDirectory($uploadsPath)) {
                File::makeDirectory($uploadsPath, 0755, true);
            }

            // Step 8: Create auto-login script
            $autoLoginToken = Str::random(32);
            $this->createAutoLoginScript($sitePath, $autoLoginToken);
            $this->updateAutoLoginPassword($sitePath, $autoLoginToken, $wpPass);

            // Update website record
            $website->update([
                'wp_admin_user' => $wpUser,
                'wp_admin_password' => encrypt($wpPass),
                'wp_admin_email' => $wpEmail,
                'wp_db_name' => $dbName,
                'wp_db_user' => $this->dbUser,
                'wp_db_password' => encrypt($this->dbPass ?: 'none'),
                'url' => $siteUrl,
                'wp_auto_login_token' => encrypt($autoLoginToken),
                'ai_theme' => $themeSlug,
            ]);

            $website->server?->increment('current_websites');

            return [
                'success' => true,
                'data' => [
                    'domain' => $siteUrl,
                    'wp_admin_user' => $wpUser,
                    'wp_admin_password' => $wpPass,
                    'wp_url' => $siteUrl . '/wp-admin',
                    'site_path' => $sitePath,
                ],
            ];
        } catch (\Exception $e) {
            Log::error("WordPress site creation failed: {$e->getMessage()}");
            $website->update(['status' => 'error']);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Clone an Envato master site for a new user website.
     * Copies files, clones database, updates URLs.
     */
    public function cloneMasterSite(string $themeSlug, Website $website): array
    {
        $master = config("webnewbiz.envato_masters.{$themeSlug}");
        if (!$master) {
            throw new \RuntimeException("Unknown Envato master theme: {$themeSlug}");
        }

        $masterPath = $master['path'];
        $masterDb = $master['db'];
        $subdomain = $website->subdomain;
        $clonePath = $this->htdocsPath . '/' . $subdomain;
        $cloneDb = 'wp_' . Str::slug($subdomain, '_');
        $cloneUrl = 'http://localhost/' . $subdomain;
        $masterUrl = 'http://localhost/master-' . $themeSlug;
        $wpUser = 'admin';
        $wpPass = Str::random(16);
        $wpEmail = $website->wp_admin_email ?: ($website->user->email ?? 'admin@webnewbiz.com');

        if (!File::isDirectory($masterPath)) {
            throw new \RuntimeException("Master site not found at: {$masterPath}. Run: php artisan setup:envato-master {$themeSlug}");
        }

        // Step 1: Create clone database
        $this->createDatabase($cloneDb);
        Log::info("Clone: Created database {$cloneDb}");

        // Step 2: Copy master files to clone
        $srcWin = str_replace('/', '\\', $masterPath);
        $destWin = str_replace('/', '\\', $clonePath);
        $cmd = "robocopy \"{$srcWin}\" \"{$destWin}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
        exec($cmd, $output, $exitCode);
        if ($exitCode > 7) {
            throw new \RuntimeException("Failed to copy master site files. Exit code: {$exitCode}");
        }
        Log::info("Clone: Copied files to {$clonePath}");

        // Step 3: Generate new wp-config.php for clone
        $this->generateWpConfig($clonePath, $cloneDb, $cloneUrl);
        Log::info("Clone: Generated wp-config.php");

        // Step 4: Clone database via mysqldump
        $mysqlBin = dirname(config('webnewbiz.mysql_bin', 'C:/xampp/mysql/bin/mysql.exe'));
        $mysqldump = $mysqlBin . '/mysqldump.exe';
        $mysql = $mysqlBin . '/mysql.exe';
        $passArg = $this->dbPass ? "-p{$this->dbPass}" : '';

        $dumpCmd = "\"{$mysqldump}\" -u {$this->dbUser} {$passArg} -h {$this->dbHost} {$masterDb} | \"{$mysql}\" -u {$this->dbUser} {$passArg} -h {$this->dbHost} {$cloneDb}";
        exec($dumpCmd, $dumpOutput, $dumpExit);

        if ($dumpExit !== 0) {
            // Fallback: copy tables via PHP
            Log::warning("Clone: mysqldump failed (exit {$dumpExit}), trying PHP copy");
            $this->cloneDatabaseViaPHP($masterDb, $cloneDb);
        }
        Log::info("Clone: Database cloned from {$masterDb} to {$cloneDb}");

        // Step 5: Replace URLs in clone database
        $this->searchReplaceInDb($cloneDb, $masterUrl, $cloneUrl);
        Log::info("Clone: URL replacement complete ({$masterUrl} → {$cloneUrl})");

        // Step 5b: Also replace any remaining original demo domain URLs
        $demoDomain = $master['demo_domain'] ?? '';
        if ($demoDomain) {
            // Replace full demo domain (e.g. https://pluginspoint.com/geoport → http://localhost/subdomain)
            $this->searchReplaceInDb($cloneDb, $demoDomain, $cloneUrl);
            // Also try http variant
            $httpDemoDomain = str_replace('https://', 'http://', $demoDomain);
            if ($httpDemoDomain !== $demoDomain) {
                $this->searchReplaceInDb($cloneDb, $httpDemoDomain, $cloneUrl);
            }
            Log::info("Clone: Demo domain replacement complete ({$demoDomain} → {$cloneUrl})");
        }

        // Step 5c: Clear Elementor CSS cache so it regenerates with correct URLs
        $elementorCssDir = $clonePath . '/wp-content/uploads/elementor/css';
        if (File::isDirectory($elementorCssDir)) {
            File::cleanDirectory($elementorCssDir);
        }
        $clonePdo = new \PDO("mysql:host={$this->dbHost};dbname={$cloneDb}", $this->dbUser, $this->dbPass);
        $clonePdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $clonePdo->exec("DELETE FROM wp_postmeta WHERE meta_key = '_elementor_css'");
        $clonePdo->exec("DELETE FROM wp_options WHERE option_name LIKE '%elementor%cache%' OR option_name LIKE '_transient_%elementor%'");

        // Step 6: Update admin credentials
        $pdo = $clonePdo;

        $hashedPass = password_hash($wpPass, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE wp_users SET user_pass = ?, user_email = ? WHERE user_login = 'admin' LIMIT 1")
            ->execute([$hashedPass, $wpEmail]);

        // Step 7: Generate .htaccess for clone
        $this->generateHtaccess($clonePath, $subdomain);

        // Step 8: Create auto-login script
        $autoLoginToken = Str::random(32);
        $this->createAutoLoginScript($clonePath, $autoLoginToken);
        $this->updateAutoLoginPassword($clonePath, $autoLoginToken, $wpPass);

        // Step 9: Create uploads directory
        $uploadsPath = $clonePath . '/wp-content/uploads/' . date('Y/m');
        if (!File::isDirectory($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
        }

        // Step 10: Update website record
        $website->update([
            'wp_admin_user' => $wpUser,
            'wp_admin_password' => encrypt($wpPass),
            'wp_admin_email' => $wpEmail,
            'wp_db_name' => $cloneDb,
            'wp_db_user' => $this->dbUser,
            'wp_db_password' => encrypt($this->dbPass ?: 'none'),
            'url' => $cloneUrl,
            'wp_auto_login_token' => encrypt($autoLoginToken),
            'ai_theme' => $themeSlug,
        ]);

        $website->server?->increment('current_websites');

        return [
            'success' => true,
            'data' => [
                'domain' => $cloneUrl,
                'wp_admin_user' => $wpUser,
                'wp_admin_password' => $wpPass,
                'wp_url' => $cloneUrl . '/wp-admin',
                'site_path' => $clonePath,
                'db_name' => $cloneDb,
            ],
        ];
    }

    /**
     * Search and replace a URL in all relevant WP tables.
     * Handles serialized data carefully.
     */
    private function searchReplaceInDb(string $dbName, string $search, string $replace): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Simple string replacements for non-serialized columns
        $simpleReplacements = [
            "UPDATE wp_options SET option_value = REPLACE(option_value, ?, ?) WHERE option_name IN ('siteurl', 'home')",
            "UPDATE wp_posts SET guid = REPLACE(guid, ?, ?)",
            "UPDATE wp_posts SET post_content = REPLACE(post_content, ?, ?)",
        ];

        foreach ($simpleReplacements as $sql) {
            $pdo->prepare($sql)->execute([$search, $replace]);
        }

        // For wp_postmeta and wp_options with potentially serialized data,
        // we need to handle serialized strings carefully
        $this->searchReplaceSerializedMeta($pdo, 'wp_postmeta', 'meta_value', 'meta_id', $search, $replace);
        $this->searchReplaceSerializedOptions($pdo, $search, $replace);
    }

    /**
     * Replace URLs in wp_postmeta, handling serialized data.
     */
    private function searchReplaceSerializedMeta(\PDO $pdo, string $table, string $column, string $idColumn, string $search, string $replace): void
    {
        // First do simple (non-serialized) replacement
        $pdo->prepare("UPDATE {$table} SET {$column} = REPLACE({$column}, ?, ?) WHERE {$column} NOT LIKE 'a:%' AND {$column} NOT LIKE 's:%' AND {$column} LIKE ?")->execute([$search, $replace, '%' . $search . '%']);

        // For serialized data, fetch, unserialize, replace, re-serialize
        $stmt = $pdo->prepare("SELECT {$idColumn}, {$column} FROM {$table} WHERE {$column} LIKE ? AND ({$column} LIKE 'a:%' OR {$column} LIKE 's:%')");
        $stmt->execute(['%' . $search . '%']);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $value = $row[$column];
            $newValue = $this->replaceInSerialized($value, $search, $replace);

            if ($newValue !== $value) {
                $pdo->prepare("UPDATE {$table} SET {$column} = ? WHERE {$idColumn} = ?")
                    ->execute([$newValue, $row[$idColumn]]);
            }
        }
    }

    /**
     * Replace URLs in wp_options, handling serialized data.
     */
    private function searchReplaceSerializedOptions(\PDO $pdo, string $search, string $replace): void
    {
        $stmt = $pdo->prepare("SELECT option_id, option_value FROM wp_options WHERE option_value LIKE ? AND option_name NOT IN ('siteurl', 'home')");
        $stmt->execute(['%' . $search . '%']);

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $value = $row['option_value'];
            $newValue = $this->replaceInSerialized($value, $search, $replace);

            if ($newValue !== $value) {
                $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_id = ?")
                    ->execute([$newValue, $row['option_id']]);
            }
        }
    }

    /**
     * Replace a string within potentially serialized data.
     * Handles PHP serialized format by adjusting string length counts.
     */
    private function replaceInSerialized(string $data, string $search, string $replace): string
    {
        // Try to unserialize
        $unserialized = @unserialize($data);
        if ($unserialized !== false || $data === 'b:0;') {
            // It's serialized — recursively replace in the data structure
            $replaced = $this->recursiveReplace($unserialized, $search, $replace);
            return serialize($replaced);
        }

        // Not serialized — simple string replace
        return str_replace($search, $replace, $data);
    }

    /**
     * Recursively replace strings in arrays/objects.
     */
    private function recursiveReplace($data, string $search, string $replace)
    {
        if (is_string($data)) {
            return str_replace($search, $replace, $data);
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->recursiveReplace($value, $search, $replace);
            }
            return $data;
        }
        if (is_object($data)) {
            foreach (get_object_vars($data) as $prop => $value) {
                $data->$prop = $this->recursiveReplace($value, $search, $replace);
            }
            return $data;
        }
        return $data;
    }

    /**
     * Clone a database entirely via PHP when mysqldump is unavailable.
     */
    private function cloneDatabaseViaPHP(string $sourceDb, string $destDb): void
    {
        $sourcePdo = new \PDO("mysql:host={$this->dbHost};dbname={$sourceDb}", $this->dbUser, $this->dbPass);
        $sourcePdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $destPdo = new \PDO("mysql:host={$this->dbHost};dbname={$destDb}", $this->dbUser, $this->dbPass);
        $destPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Get all tables
        $tables = $sourcePdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // Create table structure
            $createStmt = $sourcePdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createStmt['Create Table'];

            $destPdo->exec("DROP TABLE IF EXISTS `{$table}`");
            $destPdo->exec($createSql);

            // Copy data
            $rows = $sourcePdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($rows)) continue;

            $columns = array_keys($rows[0]);
            $placeholders = implode(',', array_fill(0, count($columns), '?'));
            $columnList = implode(',', array_map(fn($c) => "`{$c}`", $columns));

            $insertStmt = $destPdo->prepare("INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})");

            foreach ($rows as $row) {
                $insertStmt->execute(array_values($row));
            }
        }
    }

    private function createDatabase(string $dbName): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    private function copyWordPress(string $destPath): void
    {
        if (File::isDirectory($destPath)) {
            return;
        }

        $src = str_replace('/', '\\', $this->baseWpPath);
        $dest = str_replace('/', '\\', $destPath);
        $cmd = "robocopy \"{$src}\" \"{$dest}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
        exec($cmd, $output, $exitCode);

        if ($exitCode > 7) {
            throw new \RuntimeException("Failed to copy WordPress files. Exit code: {$exitCode}");
        }

        // Clean Mac resource fork files — use PowerShell for precise matching
        $cleanCmd = "powershell -Command \"Get-ChildItem -Path '{$dest}' -Recurse -Force -Filter '._*' | Where-Object { \$_.Name -match '^\\._.+' } | Remove-Item -Force -ErrorAction SilentlyContinue; Get-ChildItem -Path '{$dest}' -Recurse -Force -Filter '.DS_Store' | Remove-Item -Force -ErrorAction SilentlyContinue\"";
        exec($cleanCmd);
    }

    /**
     * Copy the selected flavor theme from prebuild/ into the WordPress site's themes directory.
     */
    private function copyThemeToSite(string $sitePath, string $themeSlug): void
    {
        $source = base_path('prebuild/' . $themeSlug);
        $dest = $sitePath . '/wp-content/themes/' . $themeSlug;

        if (!File::isDirectory($source)) {
            Log::warning("Theme source not found: {$source}, falling back to " . self::FALLBACK_THEME_SLUG);
            $source = base_path('prebuild/' . self::FALLBACK_THEME_SLUG);
            if (!File::isDirectory($source)) {
                Log::error("Fallback theme not found either: {$source}");
                return;
            }
        }

        if (File::isDirectory($dest)) {
            return; // Already copied
        }

        $src = str_replace('/', '\\', $source);
        $dst = str_replace('/', '\\', $dest);
        $cmd = "robocopy \"{$src}\" \"{$dst}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
        exec($cmd, $output, $exitCode);

        if ($exitCode > 7) {
            Log::error("Failed to copy theme {$themeSlug}. robocopy exit code: {$exitCode}");
        }
    }

    /**
     * Copy a bundled plugin from prebuild/plugins/ into a WP site's plugins directory.
     */
    private function copyPluginToSite(string $sitePath, string $pluginSlug): void
    {
        $source = base_path('prebuild/plugins/' . $pluginSlug);
        $dest = $sitePath . '/wp-content/plugins/' . $pluginSlug;

        if (!File::isDirectory($source)) {
            Log::warning("Plugin source not found: {$source}");
            return;
        }

        if (File::isDirectory($dest)) {
            return; // Already copied
        }

        $src = str_replace('/', '\\', $source);
        $dst = str_replace('/', '\\', $dest);
        $cmd = "robocopy \"{$src}\" \"{$dst}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
        exec($cmd, $output, $exitCode);

        if ($exitCode > 7) {
            Log::error("Failed to copy plugin {$pluginSlug}. robocopy exit code: {$exitCode}");
        } else {
            Log::info("Copied plugin {$pluginSlug} to: {$dest}");
        }
    }

    /**
     * Copy mu-plugins (must-use) from prebuild/mu-plugins/ into a WP site.
     * Mu-plugins auto-load without activation — handles custom CSS, Elementor fixes, etc.
     */
    private function copyMuPlugins(string $sitePath): void
    {
        $source = base_path('prebuild/mu-plugins');
        $dest = $sitePath . '/wp-content/mu-plugins';

        if (!File::isDirectory($source)) {
            Log::warning("Mu-plugins source not found: {$source}");
            return;
        }

        if (!File::isDirectory($dest)) {
            File::makeDirectory($dest, 0755, true);
        }

        foreach (File::files($source) as $file) {
            $destFile = $dest . '/' . $file->getFilename();
            if (!File::exists($destFile)) {
                File::copy($file->getPathname(), $destFile);
            }
        }

        Log::info("Copied mu-plugins to: {$dest}");
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

    /**
     * Install WordPress entirely via SQL — no HTTP calls.
     * Replicates what wp-admin/install.php does: creates all tables + admin user.
     */
    private function installWordPressViaSql(string $dbName, string $siteTitle, string $user, string $pass, string $email, string $siteUrl, string $themeSlug = self::FALLBACK_THEME_SLUG): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $charset = 'utf8mb4';
        $collate = 'utf8mb4_unicode_ci';

        // Check if tables already exist
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        if (in_array('wp_options', $tables)) {
            Log::info("WordPress tables already exist for {$dbName}, skipping table creation");
            // Still ensure admin user exists
            $this->ensureAdminUser($pdo, $user, $pass, $email);
            return;
        }

        // Create all WordPress core tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_options` (
            `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `option_name` varchar(191) NOT NULL DEFAULT '',
            `option_value` longtext NOT NULL,
            `autoload` varchar(20) NOT NULL DEFAULT 'yes',
            PRIMARY KEY (`option_id`),
            UNIQUE KEY `option_name` (`option_name`),
            KEY `autoload` (`autoload`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_users` (
            `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_login` varchar(60) NOT NULL DEFAULT '',
            `user_pass` varchar(255) NOT NULL DEFAULT '',
            `user_nicename` varchar(50) NOT NULL DEFAULT '',
            `user_email` varchar(100) NOT NULL DEFAULT '',
            `user_url` varchar(100) NOT NULL DEFAULT '',
            `user_registered` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `user_activation_key` varchar(255) NOT NULL DEFAULT '',
            `user_status` int(11) NOT NULL DEFAULT 0,
            `display_name` varchar(250) NOT NULL DEFAULT '',
            PRIMARY KEY (`ID`),
            KEY `user_login_key` (`user_login`),
            KEY `user_nicename` (`user_nicename`),
            KEY `user_email` (`user_email`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_usermeta` (
            `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`umeta_id`),
            KEY `user_id` (`user_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_posts` (
            `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `post_author` bigint(20) unsigned NOT NULL DEFAULT 0,
            `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `post_date_gmt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `post_content` longtext NOT NULL,
            `post_title` text NOT NULL,
            `post_excerpt` text NOT NULL,
            `post_status` varchar(20) NOT NULL DEFAULT 'publish',
            `comment_status` varchar(20) NOT NULL DEFAULT 'open',
            `ping_status` varchar(20) NOT NULL DEFAULT 'open',
            `post_password` varchar(255) NOT NULL DEFAULT '',
            `post_name` varchar(200) NOT NULL DEFAULT '',
            `to_ping` text NOT NULL,
            `pinged` text NOT NULL,
            `post_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `post_modified_gmt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `post_content_filtered` longtext NOT NULL,
            `post_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
            `guid` varchar(255) NOT NULL DEFAULT '',
            `menu_order` int(11) NOT NULL DEFAULT 0,
            `post_type` varchar(20) NOT NULL DEFAULT 'post',
            `post_mime_type` varchar(100) NOT NULL DEFAULT '',
            `comment_count` bigint(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (`ID`),
            KEY `post_name` (`post_name`(191)),
            KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
            KEY `post_parent` (`post_parent`),
            KEY `post_author` (`post_author`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_postmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `post_id` (`post_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_comments` (
            `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT 0,
            `comment_author` tinytext NOT NULL,
            `comment_author_email` varchar(100) NOT NULL DEFAULT '',
            `comment_author_url` varchar(200) NOT NULL DEFAULT '',
            `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
            `comment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `comment_date_gmt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `comment_content` text NOT NULL,
            `comment_karma` int(11) NOT NULL DEFAULT 0,
            `comment_approved` varchar(20) NOT NULL DEFAULT '1',
            `comment_agent` varchar(255) NOT NULL DEFAULT '',
            `comment_type` varchar(20) NOT NULL DEFAULT 'comment',
            `comment_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
            `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY (`comment_ID`),
            KEY `comment_post_ID` (`comment_post_ID`),
            KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
            KEY `comment_date_gmt` (`comment_date_gmt`),
            KEY `comment_parent` (`comment_parent`),
            KEY `comment_author_email` (`comment_author_email`(10))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_commentmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `comment_id` (`comment_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_terms` (
            `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(200) NOT NULL DEFAULT '',
            `slug` varchar(200) NOT NULL DEFAULT '',
            `term_group` bigint(10) NOT NULL DEFAULT 0,
            PRIMARY KEY (`term_id`),
            KEY `slug` (`slug`(191)),
            KEY `name` (`name`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_term_taxonomy` (
            `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `taxonomy` varchar(32) NOT NULL DEFAULT '',
            `description` longtext NOT NULL,
            `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
            `count` bigint(20) NOT NULL DEFAULT 0,
            PRIMARY KEY (`term_taxonomy_id`),
            UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
            KEY `taxonomy` (`taxonomy`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_term_relationships` (
            `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `term_order` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`object_id`,`term_taxonomy_id`),
            KEY `term_taxonomy_id` (`term_taxonomy_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_termmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `term_id` (`term_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `wp_links` (
            `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `link_url` varchar(255) NOT NULL DEFAULT '',
            `link_name` varchar(255) NOT NULL DEFAULT '',
            `link_image` varchar(255) NOT NULL DEFAULT '',
            `link_target` varchar(25) NOT NULL DEFAULT '',
            `link_description` varchar(255) NOT NULL DEFAULT '',
            `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
            `link_owner` bigint(20) unsigned NOT NULL DEFAULT 1,
            `link_rating` int(11) NOT NULL DEFAULT 0,
            `link_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `link_rel` varchar(255) NOT NULL DEFAULT '',
            `link_notes` mediumtext NOT NULL,
            `link_rss` varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY (`link_id`),
            KEY `link_visible` (`link_visible`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        // Insert default options
        $now = date('Y-m-d H:i:s');
        $defaultOptions = [
            ['siteurl', $siteUrl],
            ['home', $siteUrl],
            ['blogname', $siteTitle],
            ['blogdescription', 'Just another WordPress site'],
            ['users_can_register', '0'],
            ['admin_email', $email],
            ['start_of_week', '1'],
            ['use_balanceTags', '0'],
            ['use_smilies', '1'],
            ['require_name_email', '1'],
            ['comments_notify', '1'],
            ['posts_per_rss', '10'],
            ['rss_use_excerpt', '0'],
            ['mailserver_url', 'mail.example.com'],
            ['mailserver_login', 'login@example.com'],
            ['mailserver_pass', 'password'],
            ['mailserver_port', '110'],
            ['default_category', '1'],
            ['default_comment_status', 'closed'],
            ['default_link_category', '2'],
            ['default_ping_status', 'closed'],
            ['default_pingback_flag', '0'],
            ['posts_per_page', '10'],
            ['date_format', 'F j, Y'],
            ['time_format', 'g:i a'],
            ['links_updated_date_format', 'F j, Y g:i a'],
            ['comment_moderation', '0'],
            ['moderation_notify', '1'],
            ['permalink_structure', '/%postname%/'],
            ['rewrite_rules', ''],
            ['hack_file', '0'],
            ['blog_charset', 'UTF-8'],
            ['moderation_keys', ''],
            ['active_plugins', serialize([])],
            ['category_base', ''],
            ['ping_sites', 'http://rpc.pingomatic.com/'],
            ['comment_max_links', '2'],
            ['gmt_offset', '0'],
            ['default_email_category', '1'],
            ['recently_edited', ''],
            ['template', $themeSlug],
            ['stylesheet', $themeSlug],
            ['comment_registration', '0'],
            ['html_type', 'text/html'],
            ['use_trackback', '0'],
            ['default_role', 'subscriber'],
            ['db_version', '60717'],
            ['uploads_use_yearmonth_folders', '1'],
            ['upload_path', ''],
            ['blog_public', '0'],
            ['default_link_category', '2'],
            ['show_on_front', 'posts'],
            ['tag_base', ''],
            ['show_avatars', '1'],
            ['avatar_rating', 'G'],
            ['upload_url_path', ''],
            ['thumbnail_size_w', '150'],
            ['thumbnail_size_h', '150'],
            ['thumbnail_crop', '1'],
            ['medium_size_w', '300'],
            ['medium_size_h', '300'],
            ['avatar_default', 'mystery'],
            ['large_size_w', '1024'],
            ['large_size_h', '1024'],
            ['image_default_link_type', 'none'],
            ['image_default_size', ''],
            ['image_default_align', ''],
            ['sidebars_widgets', serialize(['wp_inactive_widgets' => []])],
            ['cron', serialize(['version' => 2, time() + 3600 => ['wp_version_check' => ['schedule' => 'twicedaily', 'args' => []], 'wp_update_plugins' => ['schedule' => 'twicedaily', 'args' => []], 'wp_update_themes' => ['schedule' => 'twicedaily', 'args' => []]]])],
            ['widget_categories', serialize([])],
            ['widget_text', serialize([])],
            ['widget_rss', serialize([])],
            ['uninstall_plugins', serialize([])],
            ['timezone_string', ''],
            ['page_for_posts', '0'],
            ['page_on_front', '0'],
            ['default_post_format', '0'],
            ['link_manager_enabled', '0'],
            ['finished_splitting_shared_terms', '1'],
            ['site_icon', '0'],
            ['medium_large_size_w', '768'],
            ['medium_large_size_h', '0'],
            ['wp_page_for_privacy_policy', '0'],
            ['show_comments_cookies_opt_in', '1'],
            ['admin_email_lifespan', strval(time() + 15552000)],
            ['initial_db_version', '60717'],
            ['wp_user_roles', serialize($this->getDefaultRoles())],
            ['fresh_site', '0'],
            ['auto_update_core_dev', 'enabled'],
            ['auto_update_core_minor', 'enabled'],
            ['auto_update_core_major', 'unset'],
        ];

        $stmt = $pdo->prepare("INSERT IGNORE INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
        foreach ($defaultOptions as [$name, $value]) {
            $stmt->execute([$name, $value]);
        }

        // Create default category
        $pdo->exec("INSERT IGNORE INTO wp_terms (term_id, name, slug, term_group) VALUES (1, 'Uncategorized', 'uncategorized', 0)");
        $pdo->exec("INSERT IGNORE INTO wp_term_taxonomy (term_taxonomy_id, term_id, taxonomy, description, parent, count) VALUES (1, 1, 'category', '', 0, 0)");

        // Create admin user
        $this->ensureAdminUser($pdo, $user, $pass, $email);

        Log::info("WordPress installed via direct SQL for database: {$dbName}");
    }

    private function ensureAdminUser(\PDO $pdo, string $user, string $pass, string $email): void
    {
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT ID FROM wp_users WHERE user_login = ?");
        $stmt->execute([$user]);
        $userId = $stmt->fetchColumn();

        // WordPress password hash (portable phpass)
        $hashedPass = $this->wpHashPassword($pass);
        $now = date('Y-m-d H:i:s');

        if (!$userId) {
            $stmt = $pdo->prepare("INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name) VALUES (?, ?, ?, ?, '', ?, '', 0, ?)");
            $stmt->execute([$user, $hashedPass, $user, $email, $now, $user]);
            $userId = (int) $pdo->lastInsertId();
        } else {
            // Update password for existing user
            $stmt = $pdo->prepare("UPDATE wp_users SET user_pass = ?, user_email = ? WHERE ID = ?");
            $stmt->execute([$hashedPass, $email, $userId]);
        }

        // Set user meta for admin
        $capabilities = serialize(['administrator' => true]);
        $this->setUserMeta($pdo, $userId, 'wp_capabilities', $capabilities);
        $this->setUserMeta($pdo, $userId, 'wp_user_level', '10');
        $this->setUserMeta($pdo, $userId, 'nickname', $user);
        $this->setUserMeta($pdo, $userId, 'first_name', '');
        $this->setUserMeta($pdo, $userId, 'last_name', '');
        $this->setUserMeta($pdo, $userId, 'description', '');
        $this->setUserMeta($pdo, $userId, 'rich_editing', 'true');
        $this->setUserMeta($pdo, $userId, 'syntax_highlighting', 'true');
        $this->setUserMeta($pdo, $userId, 'show_admin_bar_front', 'true');
        $this->setUserMeta($pdo, $userId, 'locale', '');
    }

    /**
     * WordPress-compatible password hashing (bcrypt — supported since WP 6.x).
     */
    private function wpHashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function setUserMeta(\PDO $pdo, int $userId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("DELETE FROM wp_usermeta WHERE user_id = ? AND meta_key = ?");
        $stmt->execute([$userId, $key]);

        $stmt = $pdo->prepare("INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $key, $value]);
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
                    'export' => true,
                ],
            ],
            'editor' => [
                'name' => 'Editor',
                'capabilities' => [
                    'moderate_comments' => true, 'manage_categories' => true, 'manage_links' => true,
                    'upload_files' => true, 'unfiltered_html' => true, 'edit_posts' => true,
                    'edit_others_posts' => true, 'edit_published_posts' => true, 'publish_posts' => true,
                    'edit_pages' => true, 'read' => true, 'edit_others_pages' => true,
                    'edit_published_pages' => true, 'publish_pages' => true, 'delete_pages' => true,
                    'delete_others_pages' => true, 'delete_published_pages' => true, 'delete_posts' => true,
                    'delete_others_posts' => true, 'delete_published_posts' => true, 'delete_private_posts' => true,
                    'edit_private_posts' => true, 'read_private_posts' => true, 'delete_private_pages' => true,
                    'edit_private_pages' => true, 'read_private_pages' => true,
                ],
            ],
            'subscriber' => [
                'name' => 'Subscriber',
                'capabilities' => ['read' => true],
            ],
        ];
    }

    private function activatePluginsViaDb(string $dbName, string $sitePath, bool $enableWooCommerce = false, string $themeSlug = self::FALLBACK_THEME_SLUG): void
    {
        $plugins = [];

        // Only activate plugins that actually exist in the copied files
        $pluginChecks = [
            'elementor/elementor.php',
            'elementor-pro/elementor-pro.php',
            'header-footer-elementor/header-footer-elementor.php',
            'tenweb-builder/tenweb-builder.php',
        ];

        if ($enableWooCommerce) {
            $pluginChecks[] = 'woocommerce/woocommerce.php';
        }

        foreach ($pluginChecks as $plugin) {
            if (File::exists($sitePath . '/wp-content/plugins/' . $plugin)) {
                $plugins[] = $plugin;
            }
        }

        $serialized = serialize($plugins);

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote($serialized) . " WHERE option_name = 'active_plugins'");

        // Set theme to selected theme (Elementor-compatible)
        $pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote($themeSlug) . " WHERE option_name = 'template'");
        $pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote($themeSlug) . " WHERE option_name = 'stylesheet'");

        // Create Elementor default kit (required for Elementor editor to work)
        if (in_array('elementor/elementor.php', $plugins)) {
            $this->createElementorKit($pdo, $dbName);
        }
    }

    /**
     * Create the Elementor default kit post and options.
     * Without this, Elementor shows "Your site doesn't have a default kit" error.
     */
    private function createElementorKit(\PDO $pdo, string $dbName): void
    {
        // Check if kit already exists
        $stmt = $pdo->prepare("SELECT ID FROM wp_posts WHERE post_type = 'elementor_library' AND post_status = 'publish' AND post_name = 'default-kit' LIMIT 1");
        $stmt->execute();
        $existingKit = $stmt->fetchColumn();

        if ($existingKit) {
            // Kit exists, just make sure the option points to it
            $this->setOptionDirect($pdo, 'elementor_active_kit', (string) $existingKit);
            return;
        }

        $now = date('Y-m-d H:i:s');

        // Create the kit post
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', 'Default Kit', '', 'publish', 'closed', 'closed', 'default-kit', 'elementor_library', ?, ?, '', '', '')");
        $stmt->execute([$now, $now, $now, $now]);
        $kitId = (int) $pdo->lastInsertId();

        if ($kitId) {
            // Set kit meta
            $this->setPostMeta($pdo, $kitId, '_elementor_edit_mode', 'builder');
            $this->setPostMeta($pdo, $kitId, '_elementor_template_type', 'kit');
            $this->setPostMeta($pdo, $kitId, '_elementor_version', '3.35.6');
            $this->setPostMeta($pdo, $kitId, '_elementor_data', '[]');
            $this->setPostMeta($pdo, $kitId, '_wp_page_template', 'default');

            // Tell Elementor which post is the active kit
            $this->setOptionDirect($pdo, 'elementor_active_kit', (string) $kitId);

            // Set Elementor defaults
            $this->setOptionDirect($pdo, 'elementor_disable_color_schemes', 'yes');
            $this->setOptionDirect($pdo, 'elementor_disable_typography_schemes', 'yes');
            $this->setOptionDirect($pdo, 'elementor_cpt_support', serialize(['post', 'page']));
            $this->setOptionDirect($pdo, 'elementor_experiment-e_font_icon_svg', 'active');
            // Enable Elementor container (flexbox) layout — required for modern 10Web-style templates
            $this->setOptionDirect($pdo, 'elementor_experiment-container', 'active');
            $this->setOptionDirect($pdo, 'elementor_experiment-e_nested_atomic_repeaters', 'active');

            Log::info("Elementor default kit created with ID: {$kitId} for database: {$dbName}");
        }
    }

    public function setOption(string $dbName, string $key, string $value): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);

        if ($stmt->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?");
            $stmt->execute([$value, $key]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
            $stmt->execute([$key, $value]);
        }
    }

    /**
     * Copy an image file into the WordPress uploads directory and return the URL.
     */
    public function copyImageToUploads(Website $website, string $sourcePath, ?string $filename = null): ?string
    {
        $sitePath = $this->htdocsPath . '/' . $website->subdomain;
        $yearMonth = date('Y/m');
        $uploadsDir = $sitePath . '/wp-content/uploads/' . $yearMonth;

        if (!File::isDirectory($uploadsDir)) {
            File::makeDirectory($uploadsDir, 0755, true);
        }

        $filename = $filename ?: basename($sourcePath);
        $destPath = $uploadsDir . '/' . $filename;

        if (File::exists($sourcePath)) {
            File::copy($sourcePath, $destPath);
        } elseif (str_starts_with($sourcePath, '/storage/')) {
            // It's a Laravel storage path
            $realPath = storage_path('app/public/' . ltrim(str_replace('/storage/', '', $sourcePath), '/'));
            if (File::exists($realPath)) {
                File::copy($realPath, $destPath);
            } else {
                return null;
            }
        } else {
            return null;
        }

        $siteUrl = $website->url ?: 'http://localhost/' . $website->subdomain;
        return $siteUrl . '/wp-content/uploads/' . $yearMonth . '/' . $filename;
    }

    /**
     * Set the site logo in WordPress customizer settings.
     */
    public function setLogo(Website $website, string $imageUrl, ?string $localPath = null): void
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) return;

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $now = now()->format('Y-m-d H:i:s');
        $themeModsKey = self::themeModsKey(($website->ai_theme && $website->ai_theme !== 'auto') ? $website->ai_theme : self::getThemeSlug($website->ai_style ?? 'modern'));

        // Create attachment post for the logo
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, post_mime_type, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', 'site-logo', '', 'inherit', 'open', 'closed', 'site-logo', 'attachment', ?, ?, 'image/png', ?, '', '', '')");
        $stmt->execute([$now, $now, $now, $now, $imageUrl]);
        $attachmentId = (int) $pdo->lastInsertId();

        if ($attachmentId) {
            // Set as site logo via theme mod
            $themeMods = $this->getOption($pdo, $themeModsKey);
            $mods = $themeMods ? @unserialize($themeMods) : [];
            if (!is_array($mods)) $mods = [];
            $mods['custom_logo'] = $attachmentId;
            $this->setOptionDirect($pdo, $themeModsKey, serialize($mods));

            // Set file path meta
            if ($localPath) {
                $this->setPostMeta($pdo, $attachmentId, '_wp_attached_file', $localPath);
            }
        }
    }

    /**
     * Set the site favicon/icon in WordPress.
     */
    public function setFavicon(Website $website, string $imageUrl, ?string $localPath = null): void
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) return;

        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $now = now()->format('Y-m-d H:i:s');

        // Create attachment post for the favicon
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, post_mime_type, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', 'site-icon', '', 'inherit', 'open', 'closed', 'site-icon', 'attachment', ?, ?, 'image/png', ?, '', '', '')");
        $stmt->execute([$now, $now, $now, $now, $imageUrl]);
        $attachmentId = (int) $pdo->lastInsertId();

        if ($attachmentId) {
            $this->setOptionDirect($pdo, 'site_icon', (string) $attachmentId);
            if ($localPath) {
                $this->setPostMeta($pdo, $attachmentId, '_wp_attached_file', $localPath);
            }
        }
    }

    /**
     * Create an auto-login PHP script that uses wp-signon() directly.
     * This bypasses the test cookie check and logs in immediately.
     */
    private function createAutoLoginScript(string $sitePath, string $token): void
    {
        $script = <<<'PHPTPL'
<?php
// Auto-login script for Webnewbiz platform — uses wp-signon() directly
$token = isset($_GET['token']) ? $_GET['token'] : '';
$expected = '%TOKEN%';
$password = '%PASSWORD%';

if (empty($token) || !hash_equals($expected, $token)) {
    http_response_code(403);
    die('Invalid token');
}

// Load WordPress
define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-load.php';

// Log in via wp_signon
$creds = array(
    'user_login'    => 'admin',
    'user_password' => $password,
    'remember'      => true,
);
$user = wp_signon($creds, false);

if (is_wp_error($user)) {
    wp_die('Auto-login failed: ' . $user->get_error_message());
}

// Set current user and auth cookies
wp_set_current_user($user->ID);
wp_set_auth_cookie($user->ID, true);

// Redirect to wp-admin
wp_safe_redirect(admin_url());
exit;
PHPTPL;

        $script = str_replace('%TOKEN%', $token, $script);
        File::put($sitePath . '/wp-auto-login.php', $script);
    }

    /**
     * Update the auto-login script with the actual password.
     */
    public function updateAutoLoginPassword(string $sitePath, string $token, string $password): void
    {
        $scriptPath = $sitePath . '/wp-auto-login.php';
        if (File::exists($scriptPath)) {
            $content = File::get($scriptPath);
            $content = str_replace('%PASSWORD%', addslashes($password), $content);
            File::put($scriptPath, $content);
        }
    }

    private function getOption(\PDO $pdo, string $key): ?string
    {
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : null;
    }

    private function setOptionDirect(\PDO $pdo, string $key, string $value): void
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);

        if ($stmt->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = ?");
            $stmt->execute([$value, $key]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
            $stmt->execute([$key, $value]);
        }
    }

    /**
     * Create a WordPress navigation menu with links to the given pages.
     */
    public function createNavigationMenu(string $dbName, string $siteUrl, array $pages, string $themeSlug = self::FALLBACK_THEME_SLUG): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // 1. Create the menu term
        $menuName = 'Main Menu';
        $menuSlug = 'main-menu';

        $stmt = $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)");
        $stmt->execute([$menuName, $menuSlug]);
        $termId = (int) $pdo->lastInsertId();

        // 2. Create term_taxonomy entry for nav_menu
        $stmt = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'nav_menu', '', 0, ?)");
        $stmt->execute([$termId, count($pages)]);
        $termTaxonomyId = (int) $pdo->lastInsertId();

        // 3. Create nav_menu_item posts for each page
        $menuOrder = 0;
        $now = now()->format('Y-m-d H:i:s');

        foreach ($pages as $page) {
            $menuOrder++;
            $postId = $page['post_id'] ?? 0;
            $slug = $page['slug'] ?? Str::slug($page['title'] ?? 'page');

            // Use short, clean nav labels instead of verbose AI-generated page titles
            $title = $page['nav_label'] ?? $this->shortNavLabel($slug);

            // Create nav_menu_item post
            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, menu_order, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'nav_menu_item', ?, ?, ?, ?, '', '', '')");
            $menuItemSlug = 'nav-menu-item-' . $menuOrder;
            $guid = $siteUrl . '/?p=' . ($postId ?: $menuOrder);
            $stmt->execute([$now, $now, $title, $menuItemSlug, $now, $now, $menuOrder, $guid]);
            $menuItemId = (int) $pdo->lastInsertId();

            if ($menuItemId) {
                // Set postmeta for this menu item
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_type', 'post_type');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_menu_item_parent', '0');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_object_id', (string) $postId);
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_object', 'page');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_target', '');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_classes', serialize(['']));
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_xfn', '');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_url', '');

                // Link menu item to menu via term_relationships
                $stmt = $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)");
                $stmt->execute([$menuItemId, $termTaxonomyId]);
            }
        }

        // 4. Assign menu to theme location
        $themeModsKey = self::themeModsKey($themeSlug);
        $themeMods = $this->getOption($pdo, $themeModsKey);
        $mods = $themeMods ? @unserialize($themeMods) : [];
        if (!is_array($mods)) $mods = [];
        $mods['nav_menu_locations'] = [
            'menu-1' => $termId,
            'primary' => $termId,
            'header_menu' => $termId,
            'footer_menu' => $termId,
        ];
        $this->setOptionDirect($pdo, $themeModsKey, serialize($mods));
    }

    /**
     * Convert a page slug to a clean, short navigation menu label.
     */
    private function shortNavLabel(string $slug): string
    {
        $map = [
            'home' => 'Home',
            'about' => 'About',
            'about-us' => 'About',
            'services' => 'Services',
            'contact' => 'Contact',
            'contact-us' => 'Contact',
            'portfolio' => 'Portfolio',
            'gallery' => 'Gallery',
            'pricing' => 'Pricing',
            'testimonials' => 'Testimonials',
            'team' => 'Team',
            'faq' => 'FAQ',
            'blog' => 'Blog',
            'shop' => 'Shop',
            'products' => 'Products',
        ];

        return $map[$slug] ?? ucwords(str_replace(['-', '_'], ' ', $slug));
    }

    /**
     * Add a single page as a nav menu item to an existing menu.
     */
    public function addNavMenuItem(string $dbName, string $siteUrl, int $pageId, string $title, string $slug): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Find existing menu term_taxonomy_id
        $stmt = $pdo->query("SELECT tt.term_taxonomy_id, tt.term_id FROM wp_term_taxonomy tt JOIN wp_terms t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'nav_menu' LIMIT 1");
        $menu = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$menu) return;

        $termTaxonomyId = (int) $menu['term_taxonomy_id'];

        // Get next menu_order
        $stmt = $pdo->prepare("SELECT MAX(p.menu_order) FROM wp_posts p JOIN wp_term_relationships tr ON p.ID = tr.object_id WHERE tr.term_taxonomy_id = ? AND p.post_type = 'nav_menu_item'");
        $stmt->execute([$termTaxonomyId]);
        $maxOrder = (int) $stmt->fetchColumn();
        $menuOrder = $maxOrder + 1;

        $now = now()->format('Y-m-d H:i:s');
        $navLabel = $this->shortNavLabel($slug);
        $guid = $siteUrl . '/?p=' . $pageId;

        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, menu_order, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'nav_menu_item', ?, ?, ?, ?, '', '', '')");
        $menuItemSlug = 'nav-menu-item-' . $menuOrder;
        $stmt->execute([$now, $now, $navLabel, $menuItemSlug, $now, $now, $menuOrder, $guid]);
        $menuItemId = (int) $pdo->lastInsertId();

        if ($menuItemId) {
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_type', 'post_type');
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_menu_item_parent', '0');
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_object_id', (string) $pageId);
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_object', 'page');
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_target', '');
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_classes', serialize(['']));
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_xfn', '');
            $this->setPostMeta($pdo, $menuItemId, '_menu_item_url', '');

            // Link to menu
            $stmt = $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)");
            $stmt->execute([$menuItemId, $termTaxonomyId]);

            // Update menu count
            $pdo->exec("UPDATE wp_term_taxonomy SET count = count + 1 WHERE term_taxonomy_id = {$termTaxonomyId}");
        }
    }

    /**
     * Configure the Builder Theme — logo, nav menu, colors, basic customizer settings.
     * The tenweb-website-builder-theme is an Elementor-first theme with minimal header/footer.
     */
    public function setupTheme(\PDO $pdo, string $siteName, array $colors = [], string $style = 'modern', string $themeSlug = self::FALLBACK_THEME_SLUG): void
    {
        $primary = $colors['primary'] ?? '#2563eb';
        $secondary = $colors['secondary'] ?? '#1e40af';
        $accent = $colors['accent'] ?? '#60a5fa';
        $themeModsKey = self::themeModsKey($themeSlug);

        $themeMods = $this->getOption($pdo, $themeModsKey);
        $mods = $themeMods ? @unserialize($themeMods) : [];
        if (!is_array($mods)) $mods = [];

        // Custom CSS post id — we inject via option instead
        $mods['custom_css_post_id'] = -1;

        $this->setOptionDirect($pdo, $themeModsKey, serialize($mods));

        // Inject dynamic CSS that uses the site's brand colors
        $customCss = "
/* === Brand Colors === */
.site-title a { color: {$secondary} !important; }
.site-title a:hover { color: {$primary} !important; }
.main-navigation .current-menu-item > a { color: {$primary}; }
.site-footer { background: {$secondary}; }
.footer-heading { color: #fff; }
a { color: {$primary}; }
a:hover { color: {$secondary}; }

/* === Base Layout === */
.elementor-page .site-content { margin: 0; padding: 0; }
body:not(.elementor-page) .site-content { max-width: 100%; width: 100%; padding: 40px 24px; }

/* === Global Typography Enhancement === */
.elementor-widget-heading .elementor-heading-title {
    letter-spacing: -0.02em;
}

/* === Hero Section === */
.wnb-hero-section {
    overflow: hidden;
    position: relative;
}

/* === Image Widgets === */
.elementor-widget-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 12px;
}

/* === About Image === */
.wnb-about-img img {
    max-height: 480px;
    width: 100%;
    object-fit: cover;
    aspect-ratio: 3/2;
}
.wnb-hover-zoom {
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 10px 40px -5px rgba(0,0,0,0.12);
    transition: box-shadow 0.4s ease;
}
.wnb-hover-zoom img {
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.wnb-hover-zoom:hover {
    box-shadow: 0 20px 50px -5px rgba(0,0,0,0.2);
}
.wnb-hover-zoom:hover img {
    transform: scale(1.06);
}

/* === Gallery Carousel === */
.wnb-gallery-carousel .swiper-slide {
    padding: 0 8px;
    box-sizing: border-box;
}
.elementor-widget-image-carousel .swiper-slide img {
    width: 100%;
    height: 360px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
}
.elementor-widget-image-carousel .swiper-slide img:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 16px 40px rgba(0,0,0,0.15);
}
.elementor-widget-image-carousel .elementor-swiper-button {
    background: rgba(255,255,255,0.95) !important;
    border-radius: 50% !important;
    width: 48px !important;
    height: 48px !important;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.elementor-widget-image-carousel .elementor-swiper-button:hover {
    background: #fff !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.18);
    transform: scale(1.05);
}
.elementor-widget-image-carousel .elementor-swiper-button i {
    color: {$secondary} !important;
    font-size: 16px !important;
}
.elementor-widget-image-carousel .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    opacity: 0.4;
    transition: all 0.3s ease;
}
.elementor-widget-image-carousel .swiper-pagination-bullet-active {
    background: {$primary} !important;
    opacity: 1;
    transform: scale(1.2);
}

/* === Card Hover Effects === */
.elementor-widget-text-editor > .elementor-widget-container > div[style*='box-shadow'] {
    transition: transform 0.3s ease, box-shadow 0.3s ease !important;
}

/* === Button Polish === */
.elementor-widget-button .elementor-button {
    letter-spacing: 0.3px;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* === Section Spacing === */
.elementor-element > .elementor-container {
    max-width: 1200px;
}

/* === Responsive === */
@media (max-width: 1024px) {
    .elementor-widget-image-carousel .swiper-slide img {
        height: 280px;
    }
}
@media (max-width: 767px) {
    .elementor-widget-image-carousel .swiper-slide img {
        height: 220px;
        border-radius: 10px;
    }
    .wnb-about-img img {
        max-height: 300px;
        aspect-ratio: 4/3;
    }
    .wnb-gallery-carousel .swiper-slide {
        padding: 0 4px;
    }
    .elementor-widget-image-carousel .elementor-swiper-button {
        width: 36px !important;
        height: 36px !important;
    }
}

/* === Archetype: Glassmorphism (Dark themes) === */
.wnb-glass {
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    transition: all 0.4s ease;
}
.wnb-glass:hover {
    border-color: {$primary} !important;
    box-shadow: 0 0 20px rgba(0,0,0,0.3), 0 0 40px {$primary}22 !important;
}
.wnb-glass-card {
    transition: all 0.4s ease;
}
.wnb-glass-card:hover {
    border-color: {$primary}66 !important;
    transform: translateY(-4px);
}

/* === Archetype: Gradient Button (Dark themes) === */
.wnb-gradient-btn .elementor-button,
a.wnb-gradient-btn {
    background: linear-gradient(135deg, {$primary}, {$secondary}) !important;
    border: none !important;
    transition: all 0.3s ease;
}
.wnb-gradient-btn .elementor-button:hover,
a.wnb-gradient-btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px {$primary}44;
}

/* === Archetype: Elegant Card (Elegant themes) === */
.wnb-elegant-card {
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.wnb-elegant-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 60px -8px rgba(0,0,0,0.1) !important;
}

/* === Archetype: Gradient Text === */
.wnb-gradient-text {
    background: linear-gradient(135deg, {$primary}, {$secondary});
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* === Shape Divider Transitions === */
.elementor-shape-bottom svg,
.elementor-shape-top svg {
    transition: fill 0.3s ease;
}
";
        $this->setOptionDirect($pdo, 'webnewbiz_custom_css', $customCss);
    }

    public function deleteSite(Website $website): array
    {
        try {
            $sitePath = $this->htdocsPath . '/' . $website->subdomain;
            $dbName = $website->wp_db_name;

            if ($dbName) {
                $pdo = new \PDO("mysql:host={$this->dbHost}", $this->dbUser, $this->dbPass);
                $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
            }

            if (File::isDirectory($sitePath)) {
                File::deleteDirectory($sitePath);
            }

            $website->server?->decrement('current_websites');
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error("WordPress delete failed: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createPage(Website $website, string $title, string $content): array
    {
        return $this->createPageInDb($website->wp_db_name, $title, $content);
    }

    public function createElementorPage(Website $website, string $title, string $htmlContent, array $elementorData, string $pageTemplate = 'default'): array
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) {
            return ['success' => false, 'message' => 'No database for this website'];
        }

        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $slug = Str::slug($title);
            $now = now()->format('Y-m-d H:i:s');

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $htmlContent, $title, $slug, $now, $now]);
            $postId = (int) $pdo->lastInsertId();

            if (!$postId) {
                return ['success' => false, 'message' => 'Failed to create page'];
            }

            if (!empty($elementorData)) {
                $elementorJson = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                Log::info("Elementor data for '{$title}': " . strlen($elementorJson) . " bytes, " . count($elementorData) . " sections");
                $this->setPostMeta($pdo, $postId, '_elementor_data', $elementorJson);
                $this->setPostMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
                $this->setPostMeta($pdo, $postId, '_elementor_version', '3.35.6');
                $this->setPostMeta($pdo, $postId, '_elementor_page_settings', serialize([
                    'hide_title' => 'yes',
                ]));
                $this->setPostMeta($pdo, $postId, '_elementor_css', '');
            }

            // Page template: 'default' shows theme header/footer
            $this->setPostMeta($pdo, $postId, '_wp_page_template', $pageTemplate);

            return ['success' => true, 'post_id' => $postId];
        } catch (\Exception $e) {
            Log::error("Elementor page creation failed: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateOption(Website $website, string $key, string $value): array
    {
        try {
            $dbName = $website->wp_db_name;
            if (!$dbName) return ['success' => false, 'message' => 'No database'];
            $this->setOption($dbName, $key, $value);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updatePostMeta(Website $website, int $postId, string $key, string $value): array
    {
        try {
            $dbName = $website->wp_db_name;
            if (!$dbName) return ['success' => false, 'message' => 'No database'];
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $this->setPostMeta($pdo, $postId, $key, $value);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get a PDO connection for a WordPress database.
     */
    public function getPdo(string $dbName): \PDO
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * Update a WordPress option directly via PDO.
     */
    public function updateOptionDirect(\PDO $pdo, string $key, string $value): void
    {
        $this->setOptionDirect($pdo, $key, $value);
    }

    /**
     * Update/insert post meta directly via PDO.
     */
    public function updatePostMetaDirect(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $this->setPostMeta($pdo, $postId, $key, $value);
    }

    /**
     * Create a WordPress attachment post so images appear in the media library.
     */
    public function createAttachment(string $dbName, string $imageUrl, string $title, string $filePath): ?int
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $now = now()->format('Y-m-d H:i:s');
            $slug = Str::slug($title);
            $mimeType = str_ends_with(strtolower($filePath), '.png') ? 'image/png' : 'image/jpeg';

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, post_mime_type, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'inherit', 'open', 'closed', ?, 'attachment', ?, ?, ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $title, $slug, $now, $now, $mimeType, $imageUrl]);
            $attachmentId = (int) $pdo->lastInsertId();

            if ($attachmentId) {
                $this->setPostMeta($pdo, $attachmentId, '_wp_attached_file', $filePath);
            }

            return $attachmentId ?: null;
        } catch (\Exception $e) {
            Log::warning("Failed to create attachment: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Get all published pages from a WordPress database.
     */
    public function getPages(string $dbName): array
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $stmt = $pdo->query("SELECT ID, post_title, post_name, post_status FROM wp_posts WHERE post_type='page' AND post_status='publish' ORDER BY menu_order, ID");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Log::warning("Failed to get pages: {$e->getMessage()}");
            return [];
        }
    }

    /**
     * Read Elementor data from a page.
     */
    public function getPageElementorData(string $dbName, int $postId): ?array
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $stmt = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
            $stmt->execute([$postId]);
            $raw = $stmt->fetchColumn();
            if (!$raw) return null;
            $decoded = json_decode(stripslashes($raw), true);
            return is_array($decoded) ? $decoded : null;
        } catch (\Exception $e) {
            Log::warning("Failed to get Elementor data: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Update Elementor data for a page.
     */
    public function updatePageElementorData(string $dbName, int $postId, array $data): bool
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $stmt = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_elementor_data'");
            $stmt->execute([$json, $postId]);
            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            Log::warning("Failed to update Elementor data: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Get site info (name, description, theme mods).
     */
    public function getSiteInfo(string $dbName, ?string $themeSlug = null): array
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Read active theme from DB if not provided
            if (!$themeSlug) {
                $themeSlug = $this->getOption($pdo, 'stylesheet') ?: self::FALLBACK_THEME_SLUG;
            }

            $themeModsKey = self::themeModsKey($themeSlug);
            return [
                'blogname' => $this->getOption($pdo, 'blogname') ?? '',
                'blogdescription' => $this->getOption($pdo, 'blogdescription') ?? '',
                'active_theme' => $themeSlug,
                'theme_mods' => @unserialize($this->getOption($pdo, $themeModsKey) ?? '') ?: [],
            ];
        } catch (\Exception $e) {
            return ['blogname' => '', 'blogdescription' => '', 'active_theme' => '', 'theme_mods' => []];
        }
    }

    /**
     * Set up WooCommerce pages and options for an e-commerce site.
     */
    public function setupWooCommerce(Website $website, array $aiContent = []): void
    {
        $dbName = $website->wp_db_name;
        if (!$dbName) {
            throw new \RuntimeException('No database name for website');
        }

        // Create WooCommerce pages
        $wooPages = [
            'Shop'       => '[woocommerce_products]',
            'Cart'       => '[woocommerce_cart]',
            'Checkout'   => '[woocommerce_checkout]',
            'My Account' => '[woocommerce_my_account]',
        ];

        $pageIds = [];
        foreach ($wooPages as $title => $shortcode) {
            $result = $this->createPageInDb($dbName, $title, $shortcode);
            if ($result['success'] && isset($result['post_id'])) {
                $pageIds[$title] = $result['post_id'];
            }
        }

        // Set WooCommerce options
        $this->setOption($dbName, 'woocommerce_currency', 'USD');

        if (isset($pageIds['Shop'])) {
            $this->setOption($dbName, 'woocommerce_shop_page_id', (string) $pageIds['Shop']);
        }
        if (isset($pageIds['Cart'])) {
            $this->setOption($dbName, 'woocommerce_cart_page_id', (string) $pageIds['Cart']);
        }
        if (isset($pageIds['Checkout'])) {
            $this->setOption($dbName, 'woocommerce_checkout_page_id', (string) $pageIds['Checkout']);
        }
        if (isset($pageIds['My Account'])) {
            $this->setOption($dbName, 'woocommerce_myaccount_page_id', (string) $pageIds['My Account']);
        }

        // Skip WooCommerce setup wizard and onboarding
        $this->setOption($dbName, 'woocommerce_onboarding_profile', serialize(['completed' => true]));
        $this->setOption($dbName, 'woocommerce_task_list_hidden', 'yes');
        $this->setOption($dbName, 'woocommerce_task_list_complete', 'yes');
        $this->setOption($dbName, 'woocommerce_default_country', 'US:CA');
        $this->setOption($dbName, 'woocommerce_calc_taxes', 'no');
        $this->setOption($dbName, 'woocommerce_enable_reviews', 'yes');
        $this->setOption($dbName, 'woocommerce_coming_soon', 'no');
        $this->setOption($dbName, 'woocommerce_onboarding_opt_in', 'no');
        $this->setOption($dbName, 'woocommerce_store_address', '123 Main Street');
        $this->setOption($dbName, 'woocommerce_store_city', 'Los Angeles');
        $this->setOption($dbName, 'woocommerce_store_postcode', '90001');

        Log::info("WooCommerce pages and options configured for database: {$dbName}");
    }

    /**
     * Create a sample WooCommerce product directly in the database.
     */
    public function createSampleProduct(string $dbName, string $title, string $description, float $price, string $imageUrl = '', string $category = ''): int
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $now = now()->format('Y-m-d H:i:s');
        $slug = Str::slug($title);

        // Insert the product post
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, '', 'publish', 'closed', 'closed', ?, 'product', ?, ?, '', '', '')");
        $stmt->execute([$now, $now, $description, $title, $slug, $now, $now]);
        $productId = (int) $pdo->lastInsertId();

        if (!$productId) {
            return 0;
        }

        // Set WooCommerce product meta
        $this->setPostMeta($pdo, $productId, '_price', (string) $price);
        $this->setPostMeta($pdo, $productId, '_regular_price', (string) $price);
        $this->setPostMeta($pdo, $productId, '_stock_status', 'instock');
        $this->setPostMeta($pdo, $productId, '_visibility', 'visible');
        $this->setPostMeta($pdo, $productId, '_manage_stock', 'no');
        $this->setPostMeta($pdo, $productId, '_product_version', '10.3.3');

        // Assign product type taxonomy (simple)
        $this->assignProductType($pdo, $productId, 'simple');

        // Assign product category if provided
        if ($category) {
            $catTermId = $this->ensureProductCategory($pdo, $category);
            if ($catTermId) {
                $this->assignTermToPost($pdo, $productId, $catTermId, 'product_cat');
            }
        }

        // Assign product visibility (catalog + search)
        $this->ensureProductVisibility($pdo, $productId);

        // If image URL provided, create an attachment and set as thumbnail
        if ($imageUrl) {
            $mimeType = str_ends_with(strtolower($imageUrl), '.png') ? 'image/png' : 'image/jpeg';
            $imgSlug = 'product-' . $slug;

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, post_mime_type, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'inherit', 'open', 'closed', ?, 'attachment', ?, ?, ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $title, $imgSlug, $now, $now, $mimeType, $imageUrl]);
            $attachmentId = (int) $pdo->lastInsertId();

            if ($attachmentId) {
                $this->setPostMeta($pdo, $productId, '_thumbnail_id', (string) $attachmentId);
                // Extract relative file path from URL for _wp_attached_file
                if (preg_match('#/wp-content/uploads/(.+)$#', $imageUrl, $matches)) {
                    $this->setPostMeta($pdo, $attachmentId, '_wp_attached_file', $matches[1]);
                }
            }
        }

        return $productId;
    }

    /**
     * Ensure a product category exists and return its term_taxonomy_id.
     */
    private function ensureProductCategory(\PDO $pdo, string $categoryName): int
    {
        $slug = Str::slug($categoryName);

        // Check if category already exists
        $stmt = $pdo->prepare("SELECT t.term_id, tt.term_taxonomy_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_cat' AND t.slug = ?");
        $stmt->execute([$slug]);
        $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($existing) {
            return (int) $existing['term_taxonomy_id'];
        }

        // Create the term
        $stmt = $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)");
        $stmt->execute([$categoryName, $slug]);
        $termId = (int) $pdo->lastInsertId();

        // Create the term_taxonomy entry
        $stmt = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'product_cat', '', 0, 0)");
        $stmt->execute([$termId]);
        $termTaxonomyId = (int) $pdo->lastInsertId();

        return $termTaxonomyId;
    }

    /**
     * Assign a term to a post via wp_term_relationships.
     */
    private function assignTermToPost(\PDO $pdo, int $postId, int $termTaxonomyId, string $taxonomy): void
    {
        // Check if already assigned
        $stmt = $pdo->prepare("SELECT object_id FROM wp_term_relationships WHERE object_id = ? AND term_taxonomy_id = ?");
        $stmt->execute([$postId, $termTaxonomyId]);
        if ($stmt->fetch()) {
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)");
        $stmt->execute([$postId, $termTaxonomyId]);

        // Update count
        $pdo->exec("UPDATE wp_term_taxonomy SET count = count + 1 WHERE term_taxonomy_id = {$termTaxonomyId}");
    }

    /**
     * Assign product type taxonomy (simple, variable, etc.)
     */
    private function assignProductType(\PDO $pdo, int $productId, string $type = 'simple'): void
    {
        $slug = $type;
        // Ensure the product_type term exists
        $stmt = $pdo->prepare("SELECT t.term_id, tt.term_taxonomy_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'product_type' AND t.slug = ?");
        $stmt->execute([$slug]);
        $existing = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$existing) {
            $stmt = $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)");
            $stmt->execute([$type, $slug]);
            $termId = (int) $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'product_type', '', 0, 0)");
            $stmt->execute([$termId]);
            $ttId = (int) $pdo->lastInsertId();
        } else {
            $ttId = (int) $existing['term_taxonomy_id'];
        }

        $this->assignTermToPost($pdo, $productId, $ttId, 'product_type');
    }

    /**
     * Set product visibility to catalog and search.
     * WooCommerce treats products as visible by default — just set the _visibility meta.
     */
    private function ensureProductVisibility(\PDO $pdo, int $productId): void
    {
        $this->setPostMeta($pdo, $productId, '_visibility', 'visible');
        $this->setPostMeta($pdo, $productId, '_catalog_visibility', 'visible');
    }

    public function installPlugin(Website $website, string $pluginSlug): array { return ['success' => true]; }
    public function deactivatePlugin(Website $website, string $pluginSlug): array { return ['success' => true]; }
    public function deletePlugin(Website $website, string $pluginSlug): array { return ['success' => true]; }
    public function installTheme(Website $website, string $themeSlug): array { return ['success' => true]; }
    public function activateTheme(Website $website, string $themeSlug): array { return ['success' => true]; }
    public function listPlugins(Website $website): array { return ['success' => true, 'data' => []]; }
    public function listThemes(Website $website): array { return ['success' => true, 'data' => []]; }

    private function createPageInDb(string $dbName, string $title, string $content): array
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $slug = Str::slug($title);
            $now = now()->format('Y-m-d H:i:s');

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, ?, ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '')");
            $stmt->execute([$now, $now, $content, $title, $slug, $now, $now]);

            return ['success' => true, 'post_id' => (int) $pdo->lastInsertId()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function setPostMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    /**
     * Import a pre-built SQL dump into a database.
     */
    public function importDump(string $dbName, string $themeSlug): void
    {
        $dumpFile = base_path("Example/backups/{$themeSlug}/dump.sql");
        if (!File::exists($dumpFile)) {
            throw new \RuntimeException("Dump file not found: {$dumpFile}");
        }

        // Try mysql CLI first (fastest)
        $passArg = $this->dbPass ? "-p{$this->dbPass}" : '';
        $dumpFilePath = str_replace('\\', '/', $dumpFile);
        $cmd = "mysql -h {$this->dbHost} -u {$this->dbUser} {$passArg} {$dbName} < \"{$dumpFilePath}\" 2>&1";
        exec($cmd, $output, $exitCode);

        if ($exitCode === 0) {
            Log::info("Imported SQL dump via mysql CLI for {$dbName} (theme: {$themeSlug})");
            return;
        }

        // Fallback: PHP-based import
        Log::info("mysql CLI failed (exit {$exitCode}), using PHP-based import for {$dbName}");
        $this->importDumpViaPdo($dbName, $dumpFile);
    }

    private function importDumpViaPdo(string $dbName, string $dumpFile): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $sql = File::get($dumpFile);

        // Split on statement boundaries (semicolons at end of line)
        // This handles multi-line INSERT statements with semicolons in data
        $statements = [];
        $current = '';
        foreach (explode("\n", $sql) as $line) {
            // Skip comments and empty lines
            if (str_starts_with(trim($line), '--') || trim($line) === '') {
                continue;
            }
            $current .= $line . "\n";
            if (str_ends_with(rtrim($line), ';')) {
                $statements[] = trim($current);
                $current = '';
            }
        }

        foreach ($statements as $statement) {
            if (empty(trim($statement))) continue;
            try {
                $pdo->exec($statement);
            } catch (\PDOException $e) {
                // Skip non-critical errors (e.g., DROP TABLE IF EXISTS when table doesn't exist)
                Log::warning("SQL import statement warning: {$e->getMessage()}");
            }
        }

        Log::info("Imported SQL dump via PDO for {$dbName}");
    }

    /**
     * Update site URL, credentials, and title after importing a dump.
     */
    public function updateSiteAfterImport(string $dbName, string $siteUrl, string $siteTitle, string $adminPass, string $adminEmail, string $themeSlug): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Update site URL (replace __SITE_URL__ placeholder)
        $stmt = $pdo->prepare("UPDATE wp_options SET option_value = REPLACE(option_value, '__SITE_URL__', ?) WHERE option_name IN ('siteurl', 'home')");
        $stmt->execute([$siteUrl]);

        // Update site title (replace __SITE_TITLE__ placeholder)
        $stmt = $pdo->prepare("UPDATE wp_options SET option_value = ? WHERE option_name = 'blogname'");
        $stmt->execute([$siteTitle]);

        // Update admin user password + email
        $hashedPass = $this->wpHashPassword($adminPass);
        $stmt = $pdo->prepare("UPDATE wp_users SET user_pass = ?, user_email = ? WHERE user_login = 'admin'");
        $stmt->execute([$hashedPass, $adminEmail]);

        // Replace __SITE_URL__ in all post guids
        $pdo->exec("UPDATE wp_posts SET guid = REPLACE(guid, '__SITE_URL__', " . $pdo->quote($siteUrl) . ")");

        // Replace __SITE_URL__ in postmeta (Elementor data references)
        $pdo->exec("UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, '__SITE_URL__', " . $pdo->quote($siteUrl) . ") WHERE meta_key = '_elementor_data'");

        // Replace __SITE_URL__ in serialized nav menu item URLs (if any)
        $pdo->exec("UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, '__SITE_URL__', " . $pdo->quote($siteUrl) . ") WHERE meta_key = '_menu_item_url'");

        Log::info("Updated site after dump import: {$dbName} → {$siteUrl}");
    }

    /**
     * Get all published pages keyed by slug: ['home' => 5, 'about' => 6, ...]
     */
    public function getPagesBySlug(string $dbName): array
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $stmt = $pdo->query("SELECT ID, post_name FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'");
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = [];
            foreach ($rows as $row) {
                $map[$row['post_name']] = (int) $row['ID'];
            }
            return $map;
        } catch (\Exception $e) {
            Log::warning("Failed to get pages by slug: {$e->getMessage()}");
            return [];
        }
    }

    /**
     * Update post content (HTML) for an existing page.
     */
    public function updatePostContent(string $dbName, int $postId, string $content): void
    {
        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
            $now = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("UPDATE wp_posts SET post_content = ?, post_modified = ?, post_modified_gmt = ? WHERE ID = ?");
            $stmt->execute([$content, $now, $now, $postId]);
        } catch (\Exception $e) {
            Log::warning("Failed to update post content for post {$postId}: {$e->getMessage()}");
        }
    }

    // ═══════════════════════════════════════════════════════
    //  HFE (Header Footer Elementor) PLUGIN INTEGRATION
    // ═══════════════════════════════════════════════════════

    /**
     * Install and activate the Header Footer Elementor plugin.
     * Copies plugin files from prebuild/plugins/ and adds to active_plugins.
     */
    public function installHfePlugin(string $sitePath, string $dbName): void
    {
        $source = base_path('prebuild/plugins/header-footer-elementor');
        $dest = $sitePath . '/wp-content/plugins/header-footer-elementor';

        if (!File::isDirectory($source)) {
            Log::warning("HFE plugin source not found: {$source}");
            return;
        }

        // Copy plugin files if not already present
        if (!File::isDirectory($dest)) {
            $src = str_replace('/', '\\', $source);
            $dst = str_replace('/', '\\', $dest);
            $cmd = "robocopy \"{$src}\" \"{$dst}\" /E /NFL /NDL /NJH /NJS /NC /NS /NP";
            exec($cmd, $output, $exitCode);
            if ($exitCode > 7) {
                Log::error("Failed to copy HFE plugin. robocopy exit code: {$exitCode}");
                return;
            }
        }

        // Add to active_plugins in wp_options
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT option_value FROM wp_options WHERE option_name = 'active_plugins'");
        $plugins = @unserialize($stmt->fetchColumn()) ?: [];

        $hfeSlug = 'header-footer-elementor/header-footer-elementor.php';
        if (!in_array($hfeSlug, $plugins)) {
            $plugins[] = $hfeSlug;
            $pdo->exec("UPDATE wp_options SET option_value = " . $pdo->quote(serialize($plugins)) . " WHERE option_name = 'active_plugins'");
        }

        // Register the elementor-hf CPT support so Elementor recognizes it
        $cptSupport = $this->getOptionDirect($pdo, 'elementor_cpt_support');
        $cpts = $cptSupport ? @unserialize($cptSupport) : ['post', 'page'];
        if (!in_array('elementor-hf', $cpts)) {
            $cpts[] = 'elementor-hf';
            $this->setOptionDirect($pdo, 'elementor_cpt_support', serialize($cpts));
        }

        Log::info("HFE plugin installed and activated for: {$sitePath}");
    }

    /**
     * Create an HFE header or footer template as an elementor-hf post.
     *
     * @param string $type 'type_header' or 'type_footer'
     * @return int The post ID of the created template
     */
    public function createHfeTemplate(string $dbName, string $type, string $title, array $elementorData, string $siteUrl): int
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $now = date('Y-m-d H:i:s');
        $slug = Str::slug($title);

        // Create the elementor-hf post
        $stmt = $pdo->prepare("INSERT INTO wp_posts
            (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt,
             post_status, comment_status, ping_status, post_name, post_type,
             post_modified, post_modified_gmt, guid, to_ping, pinged, post_content_filtered)
            VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'elementor-hf', ?, ?, ?, '', '', '')");
        $guid = rtrim($siteUrl, '/') . '/?p=0';
        $stmt->execute([$now, $now, $title, $slug, $now, $now, $guid]);
        $postId = (int) $pdo->lastInsertId();

        if (!$postId) {
            throw new \RuntimeException("Failed to create HFE template post: {$title}");
        }

        // Update guid with actual post ID
        $pdo->exec("UPDATE wp_posts SET guid = " . $pdo->quote(rtrim($siteUrl, '/') . "/?p={$postId}") . " WHERE ID = {$postId}");

        // Set Elementor data
        $json = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->setPostMeta($pdo, $postId, '_elementor_data', $json);
        $this->setPostMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
        $this->setPostMeta($pdo, $postId, '_elementor_version', '3.35.6');
        $this->setPostMeta($pdo, $postId, '_elementor_template_type', 'wp-post');
        $this->setPostMeta($pdo, $postId, '_elementor_page_settings', serialize([]));
        $this->setPostMeta($pdo, $postId, '_elementor_css', '');

        // HFE-specific meta
        $this->setPostMeta($pdo, $postId, 'ehf_template_type', $type);

        // Target: entire website
        $target = serialize([
            ['rule' => ['basic-global'], 'specific' => []],
        ]);
        $this->setPostMeta($pdo, $postId, 'ehf_target_include_locations', $target);
        $this->setPostMeta($pdo, $postId, 'ehf_target_exclude_locations', serialize([]));
        $this->setPostMeta($pdo, $postId, 'ehf_target_user_roles', serialize([]));

        Log::info("Created HFE {$type} template (post_id={$postId}): {$title}");
        return $postId;
    }

    /**
     * Get the first nav menu term_id from the database.
     */
    public function getMenuTermId(string $dbName): int
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $stmt = $pdo->query("SELECT t.term_id FROM wp_terms t
            JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'nav_menu'
            ORDER BY t.term_id ASC LIMIT 1");
        return (int) ($stmt->fetchColumn() ?: 0);
    }

    /**
     * Set _wp_page_template for all published pages.
     */
    public function setAllPagesTemplate(string $dbName, string $template): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT ID FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'");
        $pageIds = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($pageIds as $pageId) {
            // Check if meta exists
            $check = $pdo->prepare("SELECT meta_id FROM wp_postmeta WHERE post_id = ? AND meta_key = '_wp_page_template'");
            $check->execute([$pageId]);
            if ($check->fetchColumn()) {
                $update = $pdo->prepare("UPDATE wp_postmeta SET meta_value = ? WHERE post_id = ? AND meta_key = '_wp_page_template'");
                $update->execute([$template, $pageId]);
            } else {
                $this->setPostMeta($pdo, (int) $pageId, '_wp_page_template', $template);
            }
        }

        Log::info("Set page template '{$template}' for " . count($pageIds) . " pages in {$dbName}");
    }

    /**
     * Get an option value directly via PDO.
     */
    private function getOptionDirect(\PDO $pdo, string $key): ?string
    {
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : null;
    }
}
