<?php

namespace App\Console\Commands;

// TemplateRegistry removed — this command is deprecated (old flavor theme system)
use App\Services\WebsiteBuilderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateDumpsCommand extends Command
{
    protected $signature = 'dumps:generate {--theme= : Generate dump for a specific flavor theme only}';
    protected $description = 'Generate pre-built SQL dumps for each flavor theme';

    private const FLAVOR_THEMES = [
        'flavor-starter',
        'flavor-developer',
        'flavor-elegance',
        'flavor-freshly',
        'flavor-blaze',
        'flavor-oceanic',
        'flavor-rosewood',
        'flavor-sandstone',
        'flavor-minimal',
        'flavor-neon',
    ];

    /**
     * Style key used to resolve the correct template class via TemplateRegistry.
     */
    private const THEME_STYLE_MAP = [
        'flavor-starter'   => 'modern',
        'flavor-developer' => 'tech',
        'flavor-elegance'  => 'elegant',
        'flavor-freshly'   => 'warm',
        'flavor-blaze'     => 'bold',
        'flavor-oceanic'   => 'classic',
        'flavor-rosewood'  => 'creative',
        'flavor-sandstone' => 'luxury',
        'flavor-minimal'   => 'minimal',
        'flavor-neon'      => 'neon',
    ];

    private const PAGE_SLUGS = [
        'home', 'about', 'services', 'contact', 'pricing',
        'gallery', 'team', 'testimonials', 'faq', 'blog',
    ];

    private string $dbHost;
    private string $dbUser;
    private string $dbPass;

    public function handle(): int
    {
        $this->dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $this->dbUser = config('database.connections.mysql.username', 'root');
        $this->dbPass = config('database.connections.mysql.password', '');

        $singleTheme = $this->option('theme');
        $themes = $singleTheme ? [$singleTheme] : self::FLAVOR_THEMES;

        // Validate theme names
        foreach ($themes as $theme) {
            if (!in_array($theme, self::FLAVOR_THEMES)) {
                $this->error("Unknown theme: {$theme}. Valid themes: " . implode(', ', self::FLAVOR_THEMES));
                return 1;
            }
        }

        $this->info("Generating SQL dumps for " . count($themes) . " flavor theme(s)...\n");

        $success = 0;
        $failed = 0;

        foreach ($themes as $theme) {
            try {
                $this->generateDump($theme);
                $success++;
            } catch (\Exception $e) {
                $this->error("  FAILED: {$e->getMessage()}");
                Log::error("Dump generation failed for {$theme}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Done! {$success} succeeded, {$failed} failed.");

        return $failed > 0 ? 1 : 0;
    }

    private function generateDump(string $themeSlug): void
    {
        $tempDb = 'wp_dump_temp_' . str_replace('-', '_', $themeSlug);
        $siteUrl = '__SITE_URL__';
        $style = self::THEME_STYLE_MAP[$themeSlug] ?? 'modern';

        $this->info("[{$themeSlug}] Creating temp database: {$tempDb}");

        // 1. Create temp database
        $rootPdo = new \PDO("mysql:host={$this->dbHost}", $this->dbUser, $this->dbPass);
        $rootPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $rootPdo->exec("DROP DATABASE IF EXISTS `{$tempDb}`");
        $rootPdo->exec("CREATE DATABASE `{$tempDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        try {
            $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$tempDb}", $this->dbUser, $this->dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // 2. Create all WordPress tables
            $this->info("  Creating WordPress tables...");
            $this->createWpTables($pdo);

            // 3. Insert default options
            $this->info("  Inserting default options...");
            $this->insertDefaultOptions($pdo, $siteUrl, $themeSlug);

            // 4. Create admin user
            $this->createAdminUser($pdo);

            // 5. Activate plugins
            $this->info("  Activating plugins...");
            $this->activatePlugins($pdo, $themeSlug);

            // 6. Create Elementor kit
            $this->createElementorKit($pdo);

            // 7. Build 10 pages with dummy content via template system
            $this->info("  Building pages via template system...");
            $dummyContent = $this->getDummyContent();
            $colors = $dummyContent['colors'];
            $images = $this->getDummyImages($siteUrl);
            $createdPages = [];

            $builderService = app(WebsiteBuilderService::class);

            foreach (self::PAGE_SLUGS as $slug) {
                $pageData = $dummyContent['pages'][$slug] ?? ['title' => ucfirst($slug)];
                $title = $pageData['title'] ?? ucfirst($slug);

                // Build Elementor data via the existing template system
                $elementorData = $builderService->buildElementorData(
                    $pageData,
                    $colors,
                    $images,
                    $style,
                    'general',
                    $slug,
                    $dummyContent,
                );

                // Create the page in wp_posts
                $now = date('Y-m-d H:i:s');
                $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered, guid) VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'page', ?, ?, '', '', '', ?)");
                $guid = $siteUrl . '/?page_id=' . ($slug === 'home' ? '2' : '');
                $stmt->execute([$now, $now, $title, $slug, $now, $now, $guid]);
                $postId = (int) $pdo->lastInsertId();

                if (!$postId) {
                    $this->warn("  Failed to create page: {$title}");
                    continue;
                }

                // Update guid with actual post ID
                $pdo->prepare("UPDATE wp_posts SET guid = ? WHERE ID = ?")->execute([$siteUrl . '/?page_id=' . $postId, $postId]);

                // Set Elementor postmeta
                $elementorJson = json_encode($elementorData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $this->setPostMeta($pdo, $postId, '_elementor_data', $elementorJson);
                $this->setPostMeta($pdo, $postId, '_elementor_edit_mode', 'builder');
                $this->setPostMeta($pdo, $postId, '_elementor_version', '3.25.0');
                $this->setPostMeta($pdo, $postId, '_elementor_template_type', 'wp-page');
                $this->setPostMeta($pdo, $postId, '_elementor_page_settings', serialize(['hide_title' => 'yes']));
                $this->setPostMeta($pdo, $postId, '_elementor_css', '');
                $this->setPostMeta($pdo, $postId, '_wp_page_template', 'default');

                $createdPages[] = [
                    'post_id' => $postId,
                    'title' => $title,
                    'slug' => $slug,
                ];

                $this->line("    Page '{$slug}' (ID={$postId}): " . count($elementorData) . " sections, " . strlen($elementorJson) . " bytes");
            }

            // 8. Set homepage as static front page
            $homePostId = null;
            foreach ($createdPages as $p) {
                if ($p['slug'] === 'home') {
                    $homePostId = $p['post_id'];
                    break;
                }
            }
            if ($homePostId) {
                $this->setOption($pdo, 'show_on_front', 'page');
                $this->setOption($pdo, 'page_on_front', (string) $homePostId);
            }

            // 9. Create navigation menu
            $this->info("  Creating navigation menu...");
            $this->createNavigationMenu($pdo, $siteUrl, $createdPages, $themeSlug);

            // 10. Setup theme
            $this->setupTheme($pdo, $themeSlug);

            // 11. Export via mysqldump
            $this->info("  Exporting SQL dump...");
            $dumpDir = base_path("Example/backups/{$themeSlug}");
            if (!File::isDirectory($dumpDir)) {
                File::makeDirectory($dumpDir, 0755, true);
            }
            $dumpFile = $dumpDir . '/dump.sql';
            $this->exportDump($tempDb, $dumpFile);

            $fileSize = File::exists($dumpFile) ? round(File::size($dumpFile) / 1024) : 0;
            $this->info("  Dump saved: {$dumpFile} ({$fileSize} KB)");

        } finally {
            // 12. Drop temp database
            $rootPdo->exec("DROP DATABASE IF EXISTS `{$tempDb}`");
            $this->line("  Cleaned up temp database: {$tempDb}");
        }
    }

    private function createWpTables(\PDO $pdo): void
    {
        $charset = 'utf8mb4';
        $collate = 'utf8mb4_unicode_ci';

        $pdo->exec("CREATE TABLE `wp_options` (
            `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `option_name` varchar(191) NOT NULL DEFAULT '',
            `option_value` longtext NOT NULL,
            `autoload` varchar(20) NOT NULL DEFAULT 'yes',
            PRIMARY KEY (`option_id`),
            UNIQUE KEY `option_name` (`option_name`),
            KEY `autoload` (`autoload`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_users` (
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

        $pdo->exec("CREATE TABLE `wp_usermeta` (
            `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`umeta_id`),
            KEY `user_id` (`user_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_posts` (
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

        $pdo->exec("CREATE TABLE `wp_postmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `post_id` (`post_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_comments` (
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

        $pdo->exec("CREATE TABLE `wp_commentmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `comment_id` (`comment_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_terms` (
            `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(200) NOT NULL DEFAULT '',
            `slug` varchar(200) NOT NULL DEFAULT '',
            `term_group` bigint(10) NOT NULL DEFAULT 0,
            PRIMARY KEY (`term_id`),
            KEY `slug` (`slug`(191)),
            KEY `name` (`name`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_term_taxonomy` (
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

        $pdo->exec("CREATE TABLE `wp_term_relationships` (
            `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `term_order` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`object_id`,`term_taxonomy_id`),
            KEY `term_taxonomy_id` (`term_taxonomy_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_termmeta` (
            `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
            `meta_key` varchar(255) DEFAULT NULL,
            `meta_value` longtext,
            PRIMARY KEY (`meta_id`),
            KEY `term_id` (`term_id`),
            KEY `meta_key` (`meta_key`(191))
        ) ENGINE=InnoDB DEFAULT CHARSET={$charset} COLLATE={$collate}");

        $pdo->exec("CREATE TABLE `wp_links` (
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

        // Default category
        $pdo->exec("INSERT INTO wp_terms (term_id, name, slug, term_group) VALUES (1, 'Uncategorized', 'uncategorized', 0)");
        $pdo->exec("INSERT INTO wp_term_taxonomy (term_taxonomy_id, term_id, taxonomy, description, parent, count) VALUES (1, 1, 'category', '', 0, 0)");
    }

    private function insertDefaultOptions(\PDO $pdo, string $siteUrl, string $themeSlug): void
    {
        $defaultOptions = [
            ['siteurl', $siteUrl],
            ['home', $siteUrl],
            ['blogname', '__SITE_TITLE__'],
            ['blogdescription', 'Just another WordPress site'],
            ['users_can_register', '0'],
            ['admin_email', 'admin@webnewbiz.com'],
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
            ['cron', serialize(['version' => 2])],
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

        $stmt = $pdo->prepare("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')");
        foreach ($defaultOptions as [$name, $value]) {
            $stmt->execute([$name, $value]);
        }
    }

    private function createAdminUser(\PDO $pdo): void
    {
        $now = date('Y-m-d H:i:s');
        // Placeholder password — gets replaced on import
        $hashedPass = password_hash('__ADMIN_PASS__', PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO wp_users (user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name) VALUES (?, ?, ?, ?, '', ?, '', 0, ?)");
        $stmt->execute(['admin', $hashedPass, 'admin', 'admin@webnewbiz.com', $now, 'admin']);
        $userId = (int) $pdo->lastInsertId();

        $capabilities = serialize(['administrator' => true]);
        $this->setUserMeta($pdo, $userId, 'wp_capabilities', $capabilities);
        $this->setUserMeta($pdo, $userId, 'wp_user_level', '10');
        $this->setUserMeta($pdo, $userId, 'nickname', 'admin');
        $this->setUserMeta($pdo, $userId, 'first_name', '');
        $this->setUserMeta($pdo, $userId, 'last_name', '');
        $this->setUserMeta($pdo, $userId, 'description', '');
        $this->setUserMeta($pdo, $userId, 'rich_editing', 'true');
        $this->setUserMeta($pdo, $userId, 'syntax_highlighting', 'true');
        $this->setUserMeta($pdo, $userId, 'show_admin_bar_front', 'true');
        $this->setUserMeta($pdo, $userId, 'locale', '');
    }

    private function activatePlugins(\PDO $pdo, string $themeSlug): void
    {
        $plugins = [
            'elementor/elementor.php',
            'tenweb-builder/tenweb-builder.php',
        ];
        $this->setOption($pdo, 'active_plugins', serialize($plugins));
        $this->setOption($pdo, 'template', $themeSlug);
        $this->setOption($pdo, 'stylesheet', $themeSlug);
    }

    private function createElementorKit(\PDO $pdo): void
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', 'Default Kit', '', 'publish', 'closed', 'closed', 'default-kit', 'elementor_library', ?, ?, '', '', '')");
        $stmt->execute([$now, $now, $now, $now]);
        $kitId = (int) $pdo->lastInsertId();

        $this->setPostMeta($pdo, $kitId, '_elementor_edit_mode', 'builder');
        $this->setPostMeta($pdo, $kitId, '_elementor_template_type', 'kit');
        $this->setPostMeta($pdo, $kitId, '_elementor_version', '3.25.0');
        $this->setPostMeta($pdo, $kitId, '_elementor_data', '[]');
        $this->setPostMeta($pdo, $kitId, '_wp_page_template', 'default');

        $this->setOption($pdo, 'elementor_active_kit', (string) $kitId);
        $this->setOption($pdo, 'elementor_disable_color_schemes', 'yes');
        $this->setOption($pdo, 'elementor_disable_typography_schemes', 'yes');
        $this->setOption($pdo, 'elementor_cpt_support', serialize(['post', 'page']));
        $this->setOption($pdo, 'elementor_experiment-e_font_icon_svg', 'active');
    }

    private function createNavigationMenu(\PDO $pdo, string $siteUrl, array $pages, string $themeSlug): void
    {
        $menuName = 'Main Menu';
        $menuSlug = 'main-menu';

        $stmt = $pdo->prepare("INSERT INTO wp_terms (name, slug, term_group) VALUES (?, ?, 0)");
        $stmt->execute([$menuName, $menuSlug]);
        $termId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES (?, 'nav_menu', '', 0, ?)");
        $stmt->execute([$termId, count($pages)]);
        $termTaxonomyId = (int) $pdo->lastInsertId();

        $menuOrder = 0;
        $now = date('Y-m-d H:i:s');

        foreach ($pages as $page) {
            $menuOrder++;
            $postId = $page['post_id'] ?? 0;
            $title = $page['title'] ?? 'Page';

            $stmt = $pdo->prepare("INSERT INTO wp_posts (post_author, post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_name, post_type, post_modified, post_modified_gmt, menu_order, guid, to_ping, pinged, post_content_filtered) VALUES (1, ?, ?, '', ?, '', 'publish', 'closed', 'closed', ?, 'nav_menu_item', ?, ?, ?, ?, '', '', '')");
            $menuItemSlug = 'nav-menu-item-' . $menuOrder;
            $guid = $siteUrl . '/?p=' . $postId;
            $stmt->execute([$now, $now, $title, $menuItemSlug, $now, $now, $menuOrder, $guid]);
            $menuItemId = (int) $pdo->lastInsertId();

            if ($menuItemId) {
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_type', 'post_type');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_menu_item_parent', '0');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_object_id', (string) $postId);
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_object', 'page');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_target', '');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_classes', serialize(['']));
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_xfn', '');
                $this->setPostMeta($pdo, $menuItemId, '_menu_item_url', '');

                $stmt = $pdo->prepare("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order) VALUES (?, ?, 0)");
                $stmt->execute([$menuItemId, $termTaxonomyId]);
            }
        }

        // Assign menu to theme location
        $themeModsKey = 'theme_mods_' . $themeSlug;
        $themeMods = $this->getOption($pdo, $themeModsKey);
        $mods = $themeMods ? @unserialize($themeMods) : [];
        if (!is_array($mods)) $mods = [];
        $mods['nav_menu_locations'] = [
            'menu-1' => $termId,
            'primary' => $termId,
            'header_menu' => $termId,
        ];
        $this->setOption($pdo, $themeModsKey, serialize($mods));
    }

    private function setupTheme(\PDO $pdo, string $themeSlug): void
    {
        $themeModsKey = 'theme_mods_' . $themeSlug;
        $themeMods = $this->getOption($pdo, $themeModsKey);
        $mods = $themeMods ? @unserialize($themeMods) : [];
        if (!is_array($mods)) $mods = [];
        $mods['custom_css_post_id'] = -1;
        $this->setOption($pdo, $themeModsKey, serialize($mods));

        $customCss = "
.site-header { background: #fff; border-bottom: 1px solid #eee; padding: 12px 24px; }
.site-header-container { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
.site-branding .site-title { font-size: 22px; font-weight: 700; }
.site-branding .site-title a { color: #1e40af; text-decoration: none; }
.site-branding .site-description { display: none; }
.site-content { margin: 0; padding: 0; }
.custom-logo { max-height: 50px; width: auto; }
";
        $this->setOption($pdo, 'webnewbiz_custom_css', $customCss);
        $this->setOption($pdo, 'permalink_structure', '/%postname%/');
        $this->setOption($pdo, 'default_comment_status', 'closed');
        $this->setOption($pdo, 'default_ping_status', 'closed');
    }

    private function exportDump(string $dbName, string $dumpFile): void
    {
        $passArg = $this->dbPass ? "-p{$this->dbPass}" : '';
        $cmd = "mysqldump -h {$this->dbHost} -u {$this->dbUser} {$passArg} --no-tablespaces {$dbName} > \"{$dumpFile}\" 2>&1";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            // Fallback: PHP-based export
            $this->info("  mysqldump failed (exit {$exitCode}), using PHP-based export...");
            $this->exportDumpViaPdo($dbName, $dumpFile);
        }
    }

    private function exportDumpViaPdo(string $dbName, string $dumpFile): void
    {
        $pdo = new \PDO("mysql:host={$this->dbHost};dbname={$dbName}", $this->dbUser, $this->dbPass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $sql = "-- WordPress SQL dump generated by Webnewbiz\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: {$dbName}\n\n";
        $sql .= "SET NAMES utf8mb4;\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            // Get CREATE TABLE statement
            $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createStmt['Create Table'] . ";\n\n";

            // Get all rows
            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $columns = array_keys($rows[0]);
                $colList = '`' . implode('`, `', $columns) . '`';

                foreach ($rows as $row) {
                    $values = array_map(function ($val) use ($pdo) {
                        if ($val === null) return 'NULL';
                        return $pdo->quote($val);
                    }, array_values($row));
                    $sql .= "INSERT INTO `{$table}` ({$colList}) VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        File::put($dumpFile, $sql);
    }

    private function getDummyContent(): array
    {
        return [
            'site_title' => '__SITE_TITLE__',
            'tagline' => 'Professional Services',
            'colors' => [
                'primary' => '#2563eb',
                'secondary' => '#1e40af',
                'accent' => '#60a5fa',
            ],
            'pages' => [
                'home' => [
                    'title' => 'Home',
                    'hero_title' => 'Welcome to Our Business',
                    'hero_subtitle' => 'Professional solutions tailored to your needs',
                    'hero_cta' => 'Get Started',
                    'sections' => [
                        [
                            'type' => 'features',
                            'title' => 'Why Choose Us',
                            'items' => [
                                ['title' => 'Expert Team', 'description' => 'Our experienced professionals deliver outstanding results every time.', 'icon' => 'fas fa-users'],
                                ['title' => 'Fast Delivery', 'description' => 'We meet deadlines without compromising on quality.', 'icon' => 'fas fa-bolt'],
                                ['title' => 'Quality First', 'description' => 'Every project meets our rigorous quality standards.', 'icon' => 'fas fa-check-circle'],
                            ],
                        ],
                        [
                            'type' => 'about_preview',
                            'title' => 'About Us',
                            'content' => 'We are a team of dedicated professionals committed to delivering exceptional results. With years of experience in the industry, we bring expertise and innovation to every project.',
                        ],
                        [
                            'type' => 'stats',
                            'title' => 'Our Impact',
                            'items' => [
                                ['number' => '500+', 'label' => 'Happy Clients'],
                                ['number' => '1000+', 'label' => 'Projects Completed'],
                                ['number' => '50+', 'label' => 'Team Members'],
                                ['number' => '10+', 'label' => 'Years Experience'],
                            ],
                        ],
                        [
                            'type' => 'cta',
                            'title' => 'Ready to Get Started?',
                            'content' => 'Contact us today and let us help you achieve your goals.',
                            'button_text' => 'Contact Us',
                        ],
                    ],
                ],
                'about' => [
                    'title' => 'About',
                    'hero_title' => 'About Us',
                    'hero_subtitle' => 'Learn more about our story and mission',
                    'sections' => [
                        [
                            'type' => 'content',
                            'title' => 'Our Story',
                            'content' => 'Founded with a vision to transform the industry, we have grown from a small team to a leading force in our field. Our journey has been defined by innovation, dedication, and a commitment to excellence.',
                        ],
                        [
                            'type' => 'features',
                            'title' => 'Our Values',
                            'items' => [
                                ['title' => 'Integrity', 'description' => 'We conduct business with honesty and transparency.', 'icon' => 'fas fa-handshake'],
                                ['title' => 'Innovation', 'description' => 'We embrace new ideas and technologies.', 'icon' => 'fas fa-lightbulb'],
                                ['title' => 'Excellence', 'description' => 'We strive for the highest standards in everything we do.', 'icon' => 'fas fa-star'],
                            ],
                        ],
                    ],
                ],
                'services' => [
                    'title' => 'Services',
                    'hero_title' => 'Our Services',
                    'hero_subtitle' => 'Comprehensive solutions for your business needs',
                    'sections' => [
                        [
                            'type' => 'features',
                            'title' => 'What We Offer',
                            'items' => [
                                ['title' => 'Consulting', 'description' => 'Strategic guidance to help your business grow and succeed.', 'icon' => 'fas fa-chart-line'],
                                ['title' => 'Development', 'description' => 'Custom solutions built with cutting-edge technology.', 'icon' => 'fas fa-code'],
                                ['title' => 'Design', 'description' => 'Beautiful, user-friendly designs that captivate your audience.', 'icon' => 'fas fa-palette'],
                                ['title' => 'Marketing', 'description' => 'Data-driven strategies to reach your target audience.', 'icon' => 'fas fa-bullhorn'],
                                ['title' => 'Support', 'description' => 'Reliable ongoing support to keep your operations running smoothly.', 'icon' => 'fas fa-headset'],
                                ['title' => 'Analytics', 'description' => 'In-depth insights to inform your business decisions.', 'icon' => 'fas fa-chart-bar'],
                            ],
                        ],
                    ],
                ],
                'contact' => [
                    'title' => 'Contact',
                    'hero_title' => 'Contact Us',
                    'hero_subtitle' => 'Get in touch with our team',
                    'sections' => [
                        [
                            'type' => 'contact_form',
                            'title' => 'Send Us a Message',
                            'email' => 'info@example.com',
                            'phone' => '+1 (555) 123-4567',
                            'address' => '123 Business Street, City, State 12345',
                        ],
                    ],
                ],
                'pricing' => [
                    'title' => 'Pricing',
                    'hero_title' => 'Our Pricing',
                    'hero_subtitle' => 'Simple, transparent pricing for every business',
                    'sections' => [
                        [
                            'type' => 'pricing',
                            'title' => 'Choose Your Plan',
                            'items' => [
                                ['name' => 'Starter', 'price' => '$29/mo', 'description' => 'Perfect for small businesses', 'features' => ['5 Projects', 'Basic Support', 'Email Integration']],
                                ['name' => 'Professional', 'price' => '$79/mo', 'description' => 'For growing teams', 'features' => ['Unlimited Projects', 'Priority Support', 'Advanced Analytics', 'API Access']],
                                ['name' => 'Enterprise', 'price' => '$199/mo', 'description' => 'For large organizations', 'features' => ['Everything in Pro', 'Dedicated Manager', 'Custom Integrations', 'SLA Guarantee']],
                            ],
                        ],
                    ],
                ],
                'gallery' => [
                    'title' => 'Gallery',
                    'hero_title' => 'Our Gallery',
                    'hero_subtitle' => 'Browse our portfolio of work',
                    'sections' => [
                        [
                            'type' => 'gallery',
                            'title' => 'Featured Work',
                            'items' => [],
                        ],
                    ],
                ],
                'team' => [
                    'title' => 'Our Team',
                    'hero_title' => 'Meet Our Team',
                    'hero_subtitle' => 'The people behind our success',
                    'sections' => [
                        [
                            'type' => 'team',
                            'title' => 'Our Experts',
                            'items' => [
                                ['name' => 'John Smith', 'role' => 'CEO & Founder', 'description' => 'Visionary leader with 15+ years of industry experience.'],
                                ['name' => 'Sarah Johnson', 'role' => 'CTO', 'description' => 'Technology expert passionate about innovation.'],
                                ['name' => 'Michael Chen', 'role' => 'Creative Director', 'description' => 'Award-winning designer with a keen eye for detail.'],
                                ['name' => 'Emily Davis', 'role' => 'Marketing Lead', 'description' => 'Strategic marketer driving growth and brand awareness.'],
                            ],
                        ],
                    ],
                ],
                'testimonials' => [
                    'title' => 'Testimonials',
                    'hero_title' => 'What Clients Say',
                    'hero_subtitle' => 'Hear from our satisfied customers',
                    'sections' => [
                        [
                            'type' => 'testimonials',
                            'title' => 'Client Testimonials',
                            'items' => [
                                ['name' => 'David Wilson', 'role' => 'CEO, TechCorp', 'content' => 'Outstanding service and exceptional results. Highly recommended for any business looking to grow.'],
                                ['name' => 'Lisa Anderson', 'role' => 'Marketing Director', 'content' => 'Their expertise and dedication transformed our online presence. A truly remarkable team.'],
                                ['name' => 'Robert Taylor', 'role' => 'Startup Founder', 'content' => 'From concept to execution, they delivered beyond our expectations. A game-changer for our business.'],
                            ],
                        ],
                    ],
                ],
                'faq' => [
                    'title' => 'FAQ',
                    'hero_title' => 'Frequently Asked Questions',
                    'hero_subtitle' => 'Find answers to common questions',
                    'sections' => [
                        [
                            'type' => 'faq',
                            'title' => 'Common Questions',
                            'items' => [
                                ['question' => 'What services do you offer?', 'answer' => 'We offer a comprehensive range of services including consulting, development, design, and marketing solutions tailored to your business needs.'],
                                ['question' => 'How long does a typical project take?', 'answer' => 'Project timelines vary based on scope and complexity. Most projects are completed within 4-8 weeks from kickoff to delivery.'],
                                ['question' => 'Do you offer ongoing support?', 'answer' => 'Yes, we provide ongoing support and maintenance packages to ensure your solutions continue to perform optimally.'],
                                ['question' => 'What is your pricing structure?', 'answer' => 'We offer flexible pricing options including project-based and retainer models. Visit our pricing page for detailed information.'],
                                ['question' => 'How do I get started?', 'answer' => 'Simply reach out through our contact page or give us a call. We will schedule a free consultation to discuss your needs.'],
                            ],
                        ],
                    ],
                ],
                'blog' => [
                    'title' => 'Blog',
                    'hero_title' => 'Our Blog',
                    'hero_subtitle' => 'Insights, news, and industry updates',
                    'sections' => [
                        [
                            'type' => 'content',
                            'title' => 'Latest Posts',
                            'content' => 'Stay tuned for the latest news, tips, and insights from our team of experts.',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getDummyImages(string $siteUrl): array
    {
        // Use placeholder image URLs with __SITE_URL__ so they get replaced on import
        return [
            'hero' => $siteUrl . '/wp-content/uploads/placeholder-hero.jpg',
            'about' => $siteUrl . '/wp-content/uploads/placeholder-about.jpg',
            'services' => $siteUrl . '/wp-content/uploads/placeholder-services.jpg',
        ];
    }

    // ── Helpers ───────────────────────────────────────────────

    private function setPostMeta(\PDO $pdo, int $postId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $key, $value]);
    }

    private function setUserMeta(\PDO $pdo, int $userId, string $key, string $value): void
    {
        $stmt = $pdo->prepare("INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $key, $value]);
    }

    private function setOption(\PDO $pdo, string $key, string $value): void
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

    private function getOption(\PDO $pdo, string $key): ?string
    {
        $stmt = $pdo->prepare("SELECT option_value FROM wp_options WHERE option_name = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : null;
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
}
