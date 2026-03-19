<?php
/**
 * WebNewBiz Admin Pages
 * Renders all admin page UIs with live WordPress data.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Admin {

    private static ?self $instance = null;

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /* ═══════════════════════════════════════════════════
     *  HELPERS
     * ═══════════════════════════════════════════════════ */

    private function page_header(string $title, string $subtitle = '', array $actions = []): void {
        $platform_url = get_option('webnewbiz_platform_url', 'http://localhost:4200/dashboard');
        ?>
        <div class="wnb-header">
            <div class="wnb-header-left">
                <img src="<?php echo esc_url(WEBNEWBIZ_PLUGIN_URL . 'assets/images/logo.png'); ?>" alt="WebNewBiz" class="wnb-logo" />
                <div class="wnb-header-info">
                    <h1><?php echo esc_html($title); ?></h1>
                    <span class="wnb-badge wnb-badge-premium">Premium</span>
                    <span class="wnb-version">v<?php echo WEBNEWBIZ_VERSION; ?></span>
                    <?php if ($subtitle): ?>
                        <p><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wnb-header-right">
                <?php foreach ($actions as $act): ?>
                    <?php echo $act; ?>
                <?php endforeach; ?>
                <a href="<?php echo esc_url($platform_url); ?>" class="wnb-btn wnb-btn-outline" target="_blank">
                    <span class="dashicons dashicons-external" style="margin-top:2px"></span>
                    Platform
                </a>
            </div>
        </div>
        <?php
    }

    private function page_footer(): void {
        echo '<div class="wnb-footer-note">Powered by <strong>WebNewBiz</strong> &mdash; AI Website Builder Platform</div>';
    }

    private function dir_size(string $path): int {
        if (!is_dir($path)) return 0;
        $size = 0;
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $file) {
                if ($file->isFile()) $size += $file->getSize();
            }
        } catch (Exception $e) {}
        return $size;
    }

    private function format_bytes(int $bytes): string {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 1) . ' GB';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)       return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    private function get_db_size(): int {
        global $wpdb;
        $size = 0;
        $tables = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
        if ($tables) {
            foreach ($tables as $t) {
                $size += (int)($t['Data_length'] ?? 0) + (int)($t['Index_length'] ?? 0);
            }
        }
        return $size;
    }

    private function compute_health_score(): int {
        $score = 0;
        $checks = 0;

        // HTTPS
        $checks++;
        if (is_ssl()) $score++;

        // Caching enabled
        $checks++;
        if (get_option('wnb_disable_emojis', '0') === '1' || get_option('wnb_lazy_load_images', '0') === '1') $score++;

        // Security headers
        $checks++;
        if (get_option('wnb_security_headers', '0') === '1') $score++;

        // WP updated
        $checks++;
        $wp_updates = get_core_updates();
        if (empty($wp_updates) || (isset($wp_updates[0]->response) && $wp_updates[0]->response === 'latest')) $score++;

        // XML-RPC disabled
        $checks++;
        if (get_option('wnb_disable_xmlrpc', '0') === '1') $score++;

        // File editor disabled
        $checks++;
        if (get_option('wnb_disable_file_editor', '0') === '1') $score++;

        // WP version hidden
        $checks++;
        if (get_option('wnb_hide_wp_version', '0') === '1') $score++;

        // Login attempts limited
        $checks++;
        if (get_option('wnb_limit_login_attempts', '0') === '1') $score++;

        // PHP in uploads disabled
        $checks++;
        if (get_option('wnb_disable_php_uploads', '0') === '1') $score++;

        // Performance optimizations
        $checks++;
        if (get_option('wnb_remove_query_strings', '0') === '1') $score++;

        return $checks > 0 ? (int) round(($score / $checks) * 100) : 0;
    }

    private function toggle(string $key, string $label, string $description = ''): void {
        $value = get_option($key, '0');
        ?>
        <div class="wnb-setting-row">
            <div class="wnb-setting-info">
                <strong><?php echo esc_html($label); ?></strong>
                <?php if ($description): ?>
                    <span><?php echo esc_html($description); ?></span>
                <?php endif; ?>
            </div>
            <label class="wnb-toggle">
                <input type="checkbox" data-key="<?php echo esc_attr($key); ?>" <?php checked($value, '1'); ?> />
                <span class="wnb-toggle-slider"></span>
            </label>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  1. DASHBOARD
     * ═══════════════════════════════════════════════════ */

    public function render_dashboard(): void {
        $site_url     = get_site_url();
        $site_name    = get_bloginfo('name');
        $wp_version   = get_bloginfo('version');
        $php_version  = phpversion();
        $theme        = wp_get_theme();
        $plugins      = get_option('active_plugins', []);
        $total_pages  = (int)(wp_count_posts('page')->publish ?? 0);
        $total_posts  = (int)(wp_count_posts('post')->publish ?? 0);
        $platform_url = get_option('webnewbiz_platform_url', 'http://localhost:4200/dashboard');
        $connected_at = get_option('webnewbiz_connected_at', '');
        $is_woo       = class_exists('WooCommerce');
        $upload_dir   = wp_upload_dir();
        $upload_size  = $this->dir_size($upload_dir['basedir']);
        $db_size      = $this->get_db_size();
        $health_score = $this->compute_health_score();
        $health_class = $health_score >= 70 ? 'good' : ($health_score >= 40 ? 'fair' : 'poor');
        $circumference = 2 * M_PI * 58;
        $dash_offset  = $circumference - ($circumference * $health_score / 100);
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('WebNewBiz Builder', '', [
                '<a href="' . esc_url($platform_url) . '" class="wnb-btn wnb-btn-primary" target="_blank"><span class="dashicons dashicons-external" style="margin-top:2px"></span> Go to Platform</a>'
            ]); ?>

            <!-- Connection Status -->
            <div class="wnb-card wnb-card-status">
                <div class="wnb-status-indicator wnb-status-connected">
                    <span class="wnb-dot"></span>
                    Connected to WebNewBiz Platform
                </div>
                <?php if ($connected_at): ?>
                    <span class="wnb-meta">Since <?php echo esc_html(date('M j, Y', strtotime($connected_at))); ?></span>
                <?php endif; ?>
            </div>

            <!-- Stats Grid -->
            <div class="wnb-stats-grid" style="grid-template-columns:repeat(6,1fr)">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-wordpress-alt"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo esc_html($wp_version); ?></span>
                        <span class="wnb-stat-label">WordPress</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-purple"><span class="dashicons dashicons-admin-plugins"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo count($plugins); ?></span>
                        <span class="wnb-stat-label">Active Plugins</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-admin-page"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $total_pages; ?></span>
                        <span class="wnb-stat-label">Pages</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-orange"><span class="dashicons dashicons-media-document"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $total_posts; ?></span>
                        <span class="wnb-stat-label">Posts</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-teal"><span class="dashicons dashicons-media-archive"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo esc_html($this->format_bytes($upload_size)); ?></span>
                        <span class="wnb-stat-label">Uploads</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-indigo"><span class="dashicons dashicons-database"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo esc_html($this->format_bytes($db_size)); ?></span>
                        <span class="wnb-stat-label">Database</span>
                    </div>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Site Health Score -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title">
                        <span class="dashicons dashicons-heart"></span> Site Health Score
                    </h3>
                    <div class="wnb-health-score">
                        <div class="wnb-health-circle">
                            <svg viewBox="0 0 140 140">
                                <circle class="wnb-circle-bg" cx="70" cy="70" r="58" />
                                <circle class="wnb-circle-fill <?php echo $health_class; ?>"
                                    cx="70" cy="70" r="58"
                                    stroke-dasharray="<?php echo $circumference; ?>"
                                    stroke-dashoffset="<?php echo $dash_offset; ?>" />
                            </svg>
                            <div class="wnb-health-value">
                                <strong><?php echo $health_score; ?></strong>
                                <span>/ 100</span>
                            </div>
                        </div>
                        <div class="wnb-health-label"><?php
                            echo $health_score >= 80 ? 'Excellent' : ($health_score >= 60 ? 'Good' : ($health_score >= 40 ? 'Needs Improvement' : 'Critical'));
                        ?></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title">
                        <span class="dashicons dashicons-performance"></span> Quick Actions
                    </h3>
                    <div class="wnb-actions-list">
                        <?php
                        $actions = [
                            ['webnewbiz-booster', 'performance', 'Website Booster', 'Optimize speed & performance'],
                            ['webnewbiz-cache', 'archive', 'Cache Manager', 'Purge and manage caches'],
                            ['webnewbiz-security', 'shield', 'Security', 'Harden your site security'],
                            ['webnewbiz-images', 'format-image', 'Image Optimizer', 'Compress & optimize images'],
                            ['webnewbiz-backups', 'cloud-saved', 'Backups', 'Manage your site backups'],
                            ['webnewbiz-seo', 'search', 'SEO Tools', 'Improve search rankings'],
                            ['webnewbiz-ai', 'lightbulb', 'AI Assistant', 'Generate content with AI'],
                            ['webnewbiz-analytics', 'chart-bar', 'Analytics', 'View site traffic'],
                        ];
                        foreach ($actions as $a): ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $a[0])); ?>" class="wnb-action-item">
                                <span class="dashicons dashicons-<?php echo esc_attr($a[1]); ?>"></span>
                                <div>
                                    <strong><?php echo esc_html($a[2]); ?></strong>
                                    <span><?php echo esc_html($a[3]); ?></span>
                                </div>
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Site Information -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title">
                        <span class="dashicons dashicons-admin-site-alt3"></span> Site Information
                    </h3>
                    <table class="wnb-info-table">
                        <tr><td class="wnb-label">Site Name</td><td><?php echo esc_html($site_name); ?></td></tr>
                        <tr><td class="wnb-label">Site URL</td><td><a href="<?php echo esc_url($site_url); ?>" target="_blank"><?php echo esc_html($site_url); ?></a></td></tr>
                        <tr><td class="wnb-label">Active Theme</td><td><?php echo esc_html($theme->get('Name') . ' (' . $theme->get('Version') . ')'); ?></td></tr>
                        <tr><td class="wnb-label">PHP Version</td><td><?php echo esc_html($php_version); ?></td></tr>
                        <tr><td class="wnb-label">Server</td><td><?php echo esc_html($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'); ?></td></tr>
                        <?php if ($is_woo): ?>
                            <tr><td class="wnb-label">WooCommerce</td><td><span class="wnb-badge wnb-badge-green">Active</span></td></tr>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- Recent Activity -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title">
                        <span class="dashicons dashicons-clock"></span> Recent Activity
                    </h3>
                    <div class="wnb-activity-log">
                        <?php
                        $activities = [];

                        // Last cache purge
                        $last_purge = get_option('wnb_last_cache_purge', '');
                        if ($last_purge) $activities[] = ['Cache purged', $last_purge, 'green'];

                        // Last DB cleanup
                        $last_cleanup = get_option('wnb_last_db_cleanup', '');
                        if ($last_cleanup) $activities[] = ['Database cleaned', $last_cleanup, 'green'];

                        // Last backup
                        $backups = get_option('wnb_backups', []);
                        if (!empty($backups)) {
                            $last_bk = end($backups);
                            $activities[] = ['Backup created (' . $last_bk['type'] . ')', $last_bk['date'], 'green'];
                        }

                        // Recent published posts
                        $recent = get_posts(['numberposts' => 3, 'post_status' => 'publish', 'post_type' => ['post', 'page']]);
                        foreach ($recent as $p) {
                            $activities[] = ['Published: ' . $p->post_title, $p->post_date, ''];
                        }

                        // Plugin connection
                        if ($connected_at) $activities[] = ['Plugin connected', $connected_at, 'green'];

                        // Sort by date desc
                        usort($activities, fn($a, $b) => strtotime($b[1]) - strtotime($a[1]));
                        $activities = array_slice($activities, 0, 8);

                        if (empty($activities)): ?>
                            <div class="wnb-empty"><p>No recent activity</p></div>
                        <?php else: ?>
                            <?php foreach ($activities as $act): ?>
                                <div class="wnb-activity-item">
                                    <span class="wnb-activity-dot <?php echo esc_attr($act[2]); ?>"></span>
                                    <span><?php echo esc_html($act[0]); ?></span>
                                    <span class="wnb-activity-time"><?php echo esc_html(human_time_diff(strtotime($act[1])) . ' ago'); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  2. WEBSITE BOOSTER (Performance)
     * ═══════════════════════════════════════════════════ */

    public function render_booster(): void {
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Website Booster', 'Optimize speed and performance with one-click toggles'); ?>

            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-performance"></span> Performance Optimizations</h3>
                <p class="wnb-card-subtitle">Enable optimizations to improve your site loading speed.</p>
                <?php
                $this->toggle('wnb_disable_emojis', 'Disable WordPress Emoji', 'Removes the emoji scripts and styles WordPress loads on every page.');
                $this->toggle('wnb_disable_embeds', 'Disable Embed Scripts', 'Removes the WordPress oEmbed JavaScript. Third-party embeds will show as links.');
                $this->toggle('wnb_remove_jquery_migrate', 'Remove jQuery Migrate', 'Removes the jQuery Migrate script. Only disable if your theme/plugins do not need legacy jQuery.');
                $this->toggle('wnb_minify_html', 'Minify HTML Output', 'Strips whitespace and comments from HTML output to reduce page size.');
                $this->toggle('wnb_lazy_load_images', 'Lazy Load Images', 'Adds native browser lazy loading to all images below the fold.');
                $this->toggle('wnb_lazy_load_iframes', 'Lazy Load Iframes', 'Lazy loads embedded iframes (YouTube, Google Maps, etc.).');
                $this->toggle('wnb_dns_prefetch', 'DNS Prefetch', 'Pre-resolves DNS for external domains used on your site.');
                $this->toggle('wnb_preload_resources', 'Preload Key Resources', 'Adds preload hints for critical fonts and scripts.');
                $this->toggle('wnb_disable_heartbeat', 'Disable WordPress Heartbeat', 'Disables the WordPress Heartbeat API to reduce server load. May affect auto-save and real-time features.');
                $this->toggle('wnb_remove_query_strings', 'Remove Query Strings', 'Strips version query strings from static CSS/JS files for better caching.');
                $this->toggle('wnb_disable_rss', 'Disable RSS Feeds', 'Disables all RSS feed endpoints. Only if you do not need feeds.');
                $this->toggle('wnb_disable_self_pingbacks', 'Disable Self Pingbacks', 'Prevents WordPress from sending pingbacks to your own site.');
                ?>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  3. CACHE MANAGER
     * ═══════════════════════════════════════════════════ */

    public function render_cache(): void {
        $last_purge = get_option('wnb_last_cache_purge', 'Never');
        if ($last_purge !== 'Never') {
            $last_purge = date('M j, Y g:i A', strtotime($last_purge));
        }

        // Estimate cached items
        global $wpdb;
        $transient_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%'");
        $elementor_css_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_elementor_css'");

        $page_cache_dir = WP_CONTENT_DIR . '/cache/webnewbiz/';
        $page_cache_count = 0;
        if (is_dir($page_cache_dir)) {
            $page_cache_count = count(glob($page_cache_dir . '*.html'));
        }
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Cache Manager', 'Manage and purge all website caches'); ?>

            <!-- Purge All -->
            <div class="wnb-card wnb-card-accent">
                <div class="wnb-big-action">
                    <span class="dashicons dashicons-trash"></span>
                    <h2 style="margin:0;font-size:18px;color:var(--wnb-text)">Purge All Caches</h2>
                    <p>Clear every cache layer at once: object cache, Elementor CSS, transients, and page cache.</p>
                    <button class="wnb-btn wnb-btn-primary wnb-btn-lg" data-purge-cache="all">
                        <span class="dashicons dashicons-update" style="margin-top:2px"></span>
                        Purge All Caches
                    </button>
                    <p class="wnb-text-sm wnb-text-muted">Last purged: <span id="wnb-last-purge"><?php echo esc_html($last_purge); ?></span></p>
                </div>
            </div>

            <!-- Stats -->
            <div class="wnb-stats-grid">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-database"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $transient_count; ?></span>
                        <span class="wnb-stat-label">Transients</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-purple"><span class="dashicons dashicons-editor-code"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $elementor_css_count; ?></span>
                        <span class="wnb-stat-label">Elementor CSS</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-media-text"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $page_cache_count; ?></span>
                        <span class="wnb-stat-label">Page Cache Files</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-orange"><span class="dashicons dashicons-clock"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" style="font-size:13px"><?php echo esc_html($last_purge); ?></span>
                        <span class="wnb-stat-label">Last Purge</span>
                    </div>
                </div>
            </div>

            <!-- Individual caches -->
            <div class="wnb-grid-2">
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-database"></span> WordPress Object Cache</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 16px">Flush the WordPress object cache (wp_cache_flush).</p>
                    <button class="wnb-btn wnb-btn-secondary" data-purge-cache="object">Purge Object Cache</button>
                </div>
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-editor-code"></span> Elementor CSS Cache</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 16px">Remove all generated Elementor CSS files and meta.</p>
                    <button class="wnb-btn wnb-btn-secondary" data-purge-cache="elementor">Purge Elementor CSS</button>
                </div>
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-page"></span> Page Cache</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 16px">Clear all cached HTML pages.</p>
                    <button class="wnb-btn wnb-btn-secondary" data-purge-cache="page">Purge Page Cache</button>
                </div>
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-clock"></span> Transients</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 16px">Delete all WordPress transient data (<?php echo $transient_count; ?> items).</p>
                    <button class="wnb-btn wnb-btn-secondary" data-purge-cache="transients">Purge Transients</button>
                </div>
            </div>

            <!-- Auto-purge settings -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-generic"></span> Auto-Purge Settings</h3>
                <?php
                $this->toggle('wnb_auto_purge_post_save', 'Auto-purge on post save', 'Automatically clear relevant caches when a post or page is saved.');
                $this->toggle('wnb_auto_purge_plugin_update', 'Auto-purge on plugin update', 'Automatically clear all caches when a plugin is updated.');
                ?>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  4. IMAGE OPTIMIZER
     * ═══════════════════════════════════════════════════ */

    public function render_images(): void {
        $optimized     = get_option('wnb_optimized_images', []);
        $quality       = (int) get_option('wnb_compression_quality', 82);
        $max_dim       = (int) get_option('wnb_max_image_dimensions', 2048);
        $upload_dir    = wp_upload_dir();
        $base          = $upload_dir['basedir'];

        // Count total images
        $all_images = glob($base . '/**/*.{jpg,jpeg,png}', GLOB_BRACE) ?: [];
        $sub_images = glob($base . '/*/**/*.{jpg,jpeg,png}', GLOB_BRACE) ?: [];
        $all_images = array_unique(array_merge($all_images, $sub_images));
        $total      = count($all_images);
        $opt_count  = count($optimized);
        $remaining  = max(0, $total - $opt_count);

        // Total savings
        $total_saved = 0;
        foreach ($optimized as $o) {
            $total_saved += (int)($o['savings'] ?? 0);
        }
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Image Optimizer', 'Compress and optimize images to improve page speed'); ?>

            <!-- Stats -->
            <div class="wnb-stats-grid">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-format-image"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $total; ?></span>
                        <span class="wnb-stat-label">Total Images</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-yes-alt"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" id="wnb-img-optimized"><?php echo $opt_count; ?></span>
                        <span class="wnb-stat-label">Optimized</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-orange"><span class="dashicons dashicons-warning"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" id="wnb-img-remaining"><?php echo $remaining; ?></span>
                        <span class="wnb-stat-label">Remaining</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-purple"><span class="dashicons dashicons-download"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $this->format_bytes($total_saved); ?></span>
                        <span class="wnb-stat-label">Savings</span>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <?php if ($total > 0): ?>
                <div class="wnb-card">
                    <div class="wnb-progress-label">
                        <span>Optimization Progress</span>
                        <span><?php echo $total > 0 ? round(($opt_count / $total) * 100) : 0; ?>%</span>
                    </div>
                    <div class="wnb-progress wnb-progress-lg">
                        <div class="wnb-progress-bar green" style="width:<?php echo $total > 0 ? round(($opt_count / $total) * 100) : 0; ?>%"></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Optimize Button -->
            <div class="wnb-card wnb-card-accent">
                <div class="wnb-big-action">
                    <span class="dashicons dashicons-format-image"></span>
                    <h2 style="margin:0;font-size:18px;color:var(--wnb-text)">Bulk Optimize</h2>
                    <p>Processes 10 images at a time. Click multiple times for large libraries.</p>
                    <button class="wnb-btn wnb-btn-primary wnb-btn-lg" data-optimize-images <?php echo $remaining === 0 ? 'disabled' : ''; ?>>
                        <span class="dashicons dashicons-image-rotate" style="margin-top:2px"></span>
                        Optimize <?php echo $remaining; ?> Images
                    </button>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Settings -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-generic"></span> Optimization Settings</h3>
                    <div class="wnb-field">
                        <label class="wnb-label">Compression Quality</label>
                        <div class="wnb-range-slider">
                            <span class="wnb-text-sm">60</span>
                            <input type="range" min="60" max="100" value="<?php echo $quality; ?>" data-key="wnb_compression_quality" />
                            <span class="wnb-text-sm">100</span>
                            <span class="wnb-range-value"><?php echo $quality; ?></span>
                        </div>
                        <p class="wnb-field-help">Lower = smaller files, higher = better quality. Recommended: 80-85.</p>
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Max Image Dimensions (px)</label>
                        <div class="wnb-range-slider">
                            <span class="wnb-text-sm">1024</span>
                            <input type="range" min="1024" max="4096" step="256" value="<?php echo $max_dim; ?>" data-key="wnb_max_image_dimensions" />
                            <span class="wnb-text-sm">4096</span>
                            <span class="wnb-range-value"><?php echo $max_dim; ?></span>
                        </div>
                        <p class="wnb-field-help">Images larger than this will be resized down.</p>
                    </div>
                    <?php
                    $this->toggle('wnb_webp_conversion', 'WebP Conversion', 'Also generate a WebP version for each optimized image.');
                    $this->toggle('wnb_strip_exif', 'Strip EXIF Data', 'Remove metadata like camera info and GPS from images.');
                    $this->toggle('wnb_auto_optimize_upload', 'Auto-optimize on Upload', 'Automatically optimize images when they are uploaded.');
                    ?>
                </div>

                <!-- Recent Optimizations -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-images-alt2"></span> Recently Optimized</h3>
                    <?php
                    $recent = array_slice($optimized, -10, 10, true);
                    $recent = array_reverse($recent, true);
                    if (empty($recent)): ?>
                        <div class="wnb-empty"><p>No images optimized yet.</p></div>
                    <?php else: ?>
                        <div style="max-height:350px;overflow-y:auto">
                            <?php foreach ($recent as $path => $info): ?>
                                <div class="wnb-cleanup-row">
                                    <div class="wnb-cleanup-info">
                                        <strong style="font-size:12px"><?php echo esc_html(basename($path)); ?></strong>
                                        <span class="wnb-cleanup-size">
                                            <?php echo $this->format_bytes($info['original_size']); ?> &rarr; <?php echo $this->format_bytes($info['optimized_size']); ?>
                                        </span>
                                    </div>
                                    <span class="wnb-img-savings">
                                        -<?php echo $info['original_size'] > 0 ? round(($info['savings'] / $info['original_size']) * 100) : 0; ?>%
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  5. SECURITY
     * ═══════════════════════════════════════════════════ */

    public function render_security(): void {
        $score = $this->compute_health_score();
        $blocked = (int) get_option('wnb_blocked_login_attempts', 0);
        $circumference = 2 * M_PI * 58;
        $dash_offset   = $circumference - ($circumference * $score / 100);
        $health_class  = $score >= 70 ? 'good' : ($score >= 40 ? 'fair' : 'poor');
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Security', 'Harden and protect your WordPress site'); ?>

            <div class="wnb-grid-2">
                <!-- Security Score -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-shield"></span> Security Score</h3>
                    <div class="wnb-health-score">
                        <div class="wnb-health-circle">
                            <svg viewBox="0 0 140 140">
                                <circle class="wnb-circle-bg" cx="70" cy="70" r="58" />
                                <circle class="wnb-circle-fill <?php echo $health_class; ?>"
                                    cx="70" cy="70" r="58"
                                    stroke-dasharray="<?php echo $circumference; ?>"
                                    stroke-dashoffset="<?php echo $dash_offset; ?>" />
                            </svg>
                            <div class="wnb-health-value">
                                <strong><?php echo $score; ?></strong>
                                <span>/ 100</span>
                            </div>
                        </div>
                        <div class="wnb-health-label"><?php
                            echo $score >= 80 ? 'Well Protected' : ($score >= 60 ? 'Moderate' : 'Needs Attention');
                        ?></div>
                    </div>
                </div>

                <!-- Login Attempts -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-lock"></span> Login Protection</h3>
                    <div style="text-align:center;padding:20px">
                        <div class="wnb-stat-value" style="font-size:36px;color:var(--wnb-danger);margin-bottom:4px"><?php echo $blocked; ?></div>
                        <div class="wnb-stat-label" style="font-size:14px">Blocked Login Attempts</div>
                        <p style="font-size:12px;color:var(--wnb-text-muted);margin-top:12px">Login attempts are limited to 5 per 15 minutes when enabled.</p>
                    </div>
                </div>
            </div>

            <!-- Security Toggles -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-network"></span> Security Hardening</h3>
                <?php
                $this->toggle('wnb_disable_xmlrpc', 'Disable XML-RPC', 'Blocks XML-RPC access. Prevents brute force attacks through xmlrpc.php.');
                $this->toggle('wnb_disable_file_editor', 'Disable File Editor', 'Removes the built-in theme/plugin file editor from WP admin.');
                $this->toggle('wnb_hide_wp_version', 'Hide WordPress Version', 'Removes the WordPress version number from the HTML source.');
                $this->toggle('wnb_security_headers', 'Security Headers', 'Adds X-Frame-Options, X-Content-Type-Options, and X-XSS-Protection headers.');
                $this->toggle('wnb_limit_login_attempts', 'Limit Login Attempts', 'Limits failed login attempts to 5 per 15 minutes per IP address.');
                $this->toggle('wnb_disable_user_enum', 'Disable User Enumeration', 'Prevents attackers from discovering usernames via ?author=N queries.');
                $this->toggle('wnb_block_bad_bots', 'Block Bad Bots', 'Blocks known malicious bot user agents from accessing your site.');
                $this->toggle('wnb_force_ssl_admin', 'Force SSL Admin', 'Forces HTTPS on all admin and login pages.');
                $this->toggle('wnb_disable_php_uploads', 'Disable PHP in Uploads', 'Prevents PHP execution in the wp-content/uploads directory.');
                ?>
            </div>

            <!-- Activity Log -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-list-view"></span> Security Activity Log</h3>
                <?php
                $log = get_option('wnb_security_log', []);
                $log = array_slice($log, 0, 50);
                if (empty($log)): ?>
                    <div class="wnb-empty">
                        <div class="wnb-empty-icon"><span class="dashicons dashicons-shield"></span></div>
                        <p>No security events logged yet.</p>
                        <p class="wnb-text-sm">Events will appear here when login attempts are blocked or file changes detected.</p>
                    </div>
                <?php else: ?>
                    <div class="wnb-activity-log">
                        <?php foreach ($log as $entry): ?>
                            <div class="wnb-activity-item">
                                <span class="wnb-activity-dot <?php echo esc_attr($entry['type'] ?? ''); ?>"></span>
                                <span><?php echo esc_html($entry['message'] ?? ''); ?></span>
                                <span class="wnb-activity-time"><?php echo esc_html(isset($entry['date']) ? human_time_diff(strtotime($entry['date'])) . ' ago' : ''); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  6. SEO TOOLS
     * ═══════════════════════════════════════════════════ */

    public function render_seo(): void {
        $sitemap_url = get_site_url() . '/wp-sitemap.xml';
        $redirects   = get_option('wnb_redirects', []);
        $robots_txt  = get_option('wnb_robots_txt', "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\nSitemap: " . get_site_url() . '/wp-sitemap.xml');
        $schema_name = get_option('wnb_schema_org_name', get_bloginfo('name'));
        $schema_logo = get_option('wnb_schema_org_logo', '');
        $schema_phone = get_option('wnb_schema_org_phone', '');

        // Count pages without meta description
        global $wpdb;
        $total_pages = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page')");
        $pages_with_meta = (int) $wpdb->get_var("SELECT COUNT(DISTINCT post_id) FROM {$wpdb->postmeta} WHERE meta_key='_yoast_wpseo_metadesc' OR meta_key='_aioseo_description'");
        $pages_missing_meta = max(0, $total_pages - $pages_with_meta);
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('SEO Tools', 'Improve your search engine rankings'); ?>

            <!-- Stats -->
            <div class="wnb-stats-grid wnb-stats-grid-3">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-admin-site-alt3"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" style="font-size:14px"><a href="<?php echo esc_url($sitemap_url); ?>" target="_blank" style="color:var(--wnb-primary)">View Sitemap</a></span>
                        <span class="wnb-stat-label">XML Sitemap</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon <?php echo $pages_missing_meta > 0 ? 'wnb-stat-orange' : 'wnb-stat-green'; ?>">
                        <span class="dashicons dashicons-editor-paste-text"></span>
                    </div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $pages_missing_meta; ?></span>
                        <span class="wnb-stat-label">Pages Missing Meta</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-randomize"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo count($redirects); ?></span>
                        <span class="wnb-stat-label">301 Redirects</span>
                    </div>
                </div>
            </div>

            <!-- Sitemap Toggle -->
            <div class="wnb-card" style="max-width:600px">
                <?php $this->toggle('wnb_sitemap_enabled', 'XML Sitemap', 'Enable the built-in XML sitemap for search engines.'); ?>
            </div>

            <!-- Tabs -->
            <div class="wnb-tabs">
                <button class="wnb-tab-btn active" data-tab="seo-redirects">Redirects</button>
                <button class="wnb-tab-btn" data-tab="seo-robots">Robots.txt</button>
                <button class="wnb-tab-btn" data-tab="seo-schema">Schema Markup</button>
            </div>

            <!-- Tab 1: Redirects -->
            <div class="wnb-tab-panel active" id="seo-redirects">
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-randomize"></span> 301 Redirect Manager</h3>
                    <div class="wnb-input-group wnb-mb-md">
                        <input type="text" id="wnb-redirect-from" class="wnb-input" placeholder="From: /old-page" />
                        <span style="padding:8px 4px;color:var(--wnb-text-muted)">&rarr;</span>
                        <input type="url" id="wnb-redirect-to" class="wnb-input" placeholder="To: https://example.com/new-page" />
                        <button class="wnb-btn wnb-btn-primary" data-add-redirect>Add</button>
                    </div>

                    <?php if (!empty($redirects)): ?>
                        <table class="wnb-table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date</th>
                                    <th style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($redirects as $r): ?>
                                    <tr>
                                        <td><code><?php echo esc_html($r['from']); ?></code></td>
                                        <td><a href="<?php echo esc_url($r['to']); ?>" target="_blank"><?php echo esc_html($r['to']); ?></a></td>
                                        <td><?php echo esc_html(date('M j', strtotime($r['date'] ?? ''))); ?></td>
                                        <td>
                                            <button class="wnb-btn wnb-btn-sm" style="color:var(--wnb-danger)" data-delete-redirect="<?php echo esc_attr($r['id']); ?>">
                                                <span class="dashicons dashicons-trash" style="font-size:14px;width:14px;height:14px"></span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="wnb-empty"><p>No redirects configured yet.</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab 2: Robots.txt -->
            <div class="wnb-tab-panel" id="seo-robots" style="display:none">
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-media-code"></span> Robots.txt Editor</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 12px">
                        Control how search engine crawlers access your site. Changes here override the default WordPress robots.txt.
                    </p>
                    <textarea id="wnb-robots-txt" class="wnb-code wnb-code-light" rows="10"><?php echo esc_textarea($robots_txt); ?></textarea>
                    <div class="wnb-mt-md">
                        <button class="wnb-btn wnb-btn-primary" data-save-robots>Save Robots.txt</button>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Schema Markup -->
            <div class="wnb-tab-panel" id="seo-schema" style="display:none">
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-code-standards"></span> Schema Markup (Organization)</h3>
                    <p style="font-size:13px;color:var(--wnb-text-secondary);margin:0 0 16px">
                        Add structured data to help search engines understand your business. This outputs JSON-LD in your site header.
                    </p>
                    <div class="wnb-field">
                        <label class="wnb-label">Organization Name</label>
                        <input type="text" id="wnb-schema-name" class="wnb-input" value="<?php echo esc_attr($schema_name); ?>" />
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Logo URL</label>
                        <input type="url" id="wnb-schema-logo" class="wnb-input" value="<?php echo esc_attr($schema_logo); ?>" placeholder="https://..." />
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Phone Number</label>
                        <input type="text" id="wnb-schema-phone" class="wnb-input" value="<?php echo esc_attr($schema_phone); ?>" placeholder="+1-234-567-8900" />
                    </div>
                    <button class="wnb-btn wnb-btn-primary" data-save-schema>Save Schema</button>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  7. BACKUPS
     * ═══════════════════════════════════════════════════ */

    public function render_backups(): void {
        $backups = get_option('wnb_backups', []);
        $backups = array_reverse($backups); // newest first
        $backup_dir = WP_CONTENT_DIR . '/backups/webnewbiz/';
        $backup_size = is_dir($backup_dir) ? $this->dir_size($backup_dir) : 0;
        $schedule = get_option('wnb_backup_schedule', 'off');
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Backups', 'Create and manage site backups'); ?>

            <!-- Create Backup -->
            <div class="wnb-card wnb-card-accent">
                <div class="wnb-big-action">
                    <span class="dashicons dashicons-cloud-upload"></span>
                    <h2 style="margin:0;font-size:18px;color:var(--wnb-text)">Create Backup</h2>
                    <p>Create a snapshot of your database and/or files that you can restore at any time.</p>
                    <div class="wnb-flex-center wnb-gap-md">
                        <select id="wnb-backup-type" class="wnb-select">
                            <option value="full">Full Site (DB + Files)</option>
                            <option value="database">Database Only</option>
                            <option value="files">Files Only</option>
                        </select>
                        <button class="wnb-btn wnb-btn-primary wnb-btn-lg" data-create-backup>
                            <span class="dashicons dashicons-cloud-upload" style="margin-top:2px"></span>
                            Create Backup Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="wnb-stats-grid wnb-stats-grid-3">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-cloud-saved"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo count($backups); ?></span>
                        <span class="wnb-stat-label">Total Backups</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-purple"><span class="dashicons dashicons-media-archive"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $this->format_bytes($backup_size); ?></span>
                        <span class="wnb-stat-label">Storage Used</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-calendar-alt"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" style="font-size:14px"><?php echo esc_html(ucfirst($schedule)); ?></span>
                        <span class="wnb-stat-label">Auto-backup</span>
                    </div>
                </div>
            </div>

            <!-- Backup List -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-backup"></span> Backup History</h3>
                <?php if (empty($backups)): ?>
                    <div class="wnb-empty">
                        <div class="wnb-empty-icon"><span class="dashicons dashicons-cloud-saved"></span></div>
                        <p>No backups yet. Create your first backup above.</p>
                    </div>
                <?php else: ?>
                    <table class="wnb-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th style="width:200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($backups as $bk): ?>
                                <tr>
                                    <td><?php echo esc_html(date('M j, Y g:i A', strtotime($bk['date'] ?? ''))); ?></td>
                                    <td><span class="wnb-badge wnb-badge-blue"><?php echo esc_html(ucfirst($bk['type'] ?? 'full')); ?></span></td>
                                    <td><?php echo esc_html($this->format_bytes($bk['size'] ?? 0)); ?></td>
                                    <td>
                                        <div class="wnb-table-actions">
                                            <button class="wnb-btn wnb-btn-sm" data-restore-backup="<?php echo esc_attr($bk['id']); ?>">Restore</button>
                                            <button class="wnb-btn wnb-btn-sm" style="color:var(--wnb-danger)" data-delete-backup="<?php echo esc_attr($bk['id']); ?>">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Schedule -->
            <div class="wnb-card" style="max-width:500px">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-calendar-alt"></span> Auto-backup Schedule</h3>
                <?php $this->toggle('wnb_backup_schedule', 'Enable automatic backups', 'When enabled, a full backup is created weekly.'); ?>
                <p class="wnb-text-sm wnb-text-muted wnb-mt-sm">Backup storage: <code><?php echo esc_html($backup_dir); ?></code></p>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  8. DATABASE OPTIMIZER
     * ═══════════════════════════════════════════════════ */

    public function render_database(): void {
        global $wpdb;

        // Get counts
        $revisions    = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='revision'");
        $auto_drafts  = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='auto-draft'");
        $trashed      = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status='trash'");
        $spam         = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved='spam'");
        $trash_cmt    = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved='trash'");
        $transients   = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%'");

        $time = time();
        $expired      = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->options} a
             INNER JOIN {$wpdb->options} b ON b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12))
             WHERE a.option_name LIKE '_transient_%'
             AND a.option_name NOT LIKE '_transient_timeout_%'
             AND b.option_value < {$time}"
        );

        $orphaned_meta = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} pm
             LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE p.ID IS NULL"
        );

        $total_items = $revisions + $auto_drafts + $trashed + $spam + $trash_cmt + $expired + $orphaned_meta;

        // Table sizes
        $tables = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
        $total_db_size = 0;
        foreach ($tables as &$t) {
            $t['total_size'] = (int)($t['Data_length'] ?? 0) + (int)($t['Index_length'] ?? 0);
            $total_db_size += $t['total_size'];
        }
        unset($t);

        $last_cleanup = get_option('wnb_last_db_cleanup', 'Never');
        if ($last_cleanup !== 'Never') $last_cleanup = date('M j, Y g:i A', strtotime($last_cleanup));
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Database Optimizer', 'Clean and optimize your WordPress database'); ?>

            <!-- Stats -->
            <div class="wnb-stats-grid wnb-stats-grid-3">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-red"><span class="dashicons dashicons-trash"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $total_items; ?></span>
                        <span class="wnb-stat-label">Cleanable Items</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-database"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $this->format_bytes($total_db_size); ?></span>
                        <span class="wnb-stat-label">Total DB Size</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-clock"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value" style="font-size:13px"><?php echo esc_html($last_cleanup); ?></span>
                        <span class="wnb-stat-label">Last Cleanup</span>
                    </div>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Cleanup Items -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-editor-removeformatting"></span> Database Cleanup</h3>
                    <?php
                    $items = [
                        ['revisions',         'Post Revisions',       $revisions],
                        ['auto_drafts',       'Auto Drafts',          $auto_drafts],
                        ['trashed_posts',     'Trashed Posts',        $trashed],
                        ['spam_comments',     'Spam Comments',        $spam],
                        ['trashed_comments',  'Trashed Comments',     $trash_cmt],
                        ['expired_transients','Expired Transients',   $expired],
                        ['transients',        'All Transients',       $transients],
                        ['orphaned_meta',     'Orphaned Post Meta',   $orphaned_meta],
                    ];
                    foreach ($items as $item): ?>
                        <div class="wnb-cleanup-row">
                            <div class="wnb-cleanup-info">
                                <strong><?php echo esc_html($item[1]); ?></strong>
                                <span class="wnb-cleanup-count">(<?php echo $item[2]; ?> items)</span>
                            </div>
                            <button class="wnb-btn wnb-btn-sm" data-db-cleanup="<?php echo esc_attr($item[0]); ?>" <?php echo $item[2] === 0 ? 'disabled' : ''; ?>>
                                Clean
                            </button>
                        </div>
                    <?php endforeach; ?>

                    <hr class="wnb-divider" />
                    <div style="text-align:center">
                        <button class="wnb-btn wnb-btn-danger" data-db-cleanup="all" <?php echo $total_items === 0 ? 'disabled' : ''; ?>>
                            <span class="dashicons dashicons-trash" style="margin-top:2px"></span>
                            Clean All (<?php echo $total_items; ?> items)
                        </button>
                    </div>
                </div>

                <!-- Tables List -->
                <div class="wnb-card">
                    <div class="wnb-flex-between wnb-mb-md">
                        <h3 class="wnb-card-title" style="margin:0;padding:0;border:0"><span class="dashicons dashicons-list-view"></span> Database Tables</h3>
                        <button class="wnb-btn wnb-btn-sm" data-db-optimize>Optimize All</button>
                    </div>
                    <div style="max-height:400px;overflow-y:auto">
                        <table class="wnb-table">
                            <thead>
                                <tr>
                                    <th>Table</th>
                                    <th>Rows</th>
                                    <th>Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tables as $t): ?>
                                    <tr>
                                        <td><code style="font-size:11px"><?php echo esc_html($t['Name']); ?></code></td>
                                        <td><?php echo number_format((int)($t['Rows'] ?? 0)); ?></td>
                                        <td><?php echo $this->format_bytes($t['total_size']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Auto-cleanup -->
            <div class="wnb-card" style="max-width:600px">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-calendar-alt"></span> Scheduled Cleanup</h3>
                <?php $this->toggle('wnb_db_auto_cleanup', 'Enable automatic database cleanup', 'Runs weekly cleanup of revisions, drafts, trash, and expired transients.'); ?>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  9. ANALYTICS
     * ═══════════════════════════════════════════════════ */

    public function render_analytics(): void {
        $today_key = 'wnb_analytics_' . current_time('Y-m-d');
        $today     = get_option($today_key, ['views' => 0, 'unique_ips' => [], 'pages' => [], 'referrers' => [], 'devices' => ['desktop' => 0, 'mobile' => 0, 'tablet' => 0]]);

        // Aggregate 7 day and 30 day
        $views_7d  = 0; $uniq_7d = 0;
        $views_30d = 0; $uniq_30d = 0;
        $all_pages = []; $all_refs = [];
        $all_devices = ['desktop' => 0, 'mobile' => 0, 'tablet' => 0];
        $daily_views = [];

        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days", current_time('timestamp')));
            $data = get_option('wnb_analytics_' . $date, null);
            if (!$data) continue;

            $v = (int)($data['views'] ?? 0);
            $u = count($data['unique_ips'] ?? []);

            $views_30d += $v;
            $uniq_30d  += $u;
            if ($i < 7) {
                $views_7d += $v;
                $uniq_7d  += $u;
            }

            $daily_views[$date] = $v;

            foreach (($data['pages'] ?? []) as $pg => $pgd) {
                if (!isset($all_pages[$pg])) $all_pages[$pg] = ['views' => 0, 'unique' => 0];
                $all_pages[$pg]['views']  += (int)($pgd['views'] ?? 0);
                $all_pages[$pg]['unique'] += count($pgd['unique'] ?? []);
            }
            foreach (($data['referrers'] ?? []) as $ref => $cnt) {
                if (!isset($all_refs[$ref])) $all_refs[$ref] = 0;
                $all_refs[$ref] += $cnt;
            }
            foreach (['desktop', 'mobile', 'tablet'] as $dev) {
                $all_devices[$dev] += (int)($data['devices'][$dev] ?? 0);
            }
        }

        arsort($all_pages);
        arsort($all_refs);
        $top_pages = array_slice($all_pages, 0, 10, true);
        $top_refs  = array_slice($all_refs, 0, 10, true);
        $device_total = array_sum($all_devices) ?: 1;

        // Chart data: last 14 days
        $chart_data = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days", current_time('timestamp')));
            $chart_data[] = $daily_views[$d] ?? 0;
        }
        $chart_max = max(1, max($chart_data));
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Analytics', 'Lightweight built-in traffic analytics'); ?>

            <!-- Stats -->
            <div class="wnb-stats-grid">
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-blue"><span class="dashicons dashicons-visibility"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo (int)$today['views']; ?></span>
                        <span class="wnb-stat-label">Views Today</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-green"><span class="dashicons dashicons-chart-line"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $views_7d; ?></span>
                        <span class="wnb-stat-label">Views (7 days)</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-purple"><span class="dashicons dashicons-chart-bar"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $views_30d; ?></span>
                        <span class="wnb-stat-label">Views (30 days)</span>
                    </div>
                </div>
                <div class="wnb-stat-card">
                    <div class="wnb-stat-icon wnb-stat-teal"><span class="dashicons dashicons-groups"></span></div>
                    <div class="wnb-stat-info">
                        <span class="wnb-stat-value"><?php echo $uniq_30d; ?></span>
                        <span class="wnb-stat-label">Unique Visitors (30d)</span>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-chart-area"></span> Views (Last 14 Days)</h3>
                <div class="wnb-chart-bars" style="height:140px;align-items:flex-end;padding:10px 0">
                    <?php foreach ($chart_data as $i => $v): ?>
                        <div class="wnb-chart-bar" style="height:<?php echo max(2, round(($v / $chart_max) * 100)); ?>%" title="<?php echo $v; ?> views"></div>
                    <?php endforeach; ?>
                </div>
                <div class="wnb-flex-between wnb-text-xs wnb-text-muted" style="padding:4px 0">
                    <span><?php echo date('M j', strtotime('-13 days', current_time('timestamp'))); ?></span>
                    <span>Today</span>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Popular Pages -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-page"></span> Popular Pages</h3>
                    <?php if (empty($top_pages)): ?>
                        <div class="wnb-empty"><p>No page views recorded yet.</p></div>
                    <?php else: ?>
                        <table class="wnb-table">
                            <thead><tr><th>Page</th><th>Views</th><th>Unique</th></tr></thead>
                            <tbody>
                                <?php foreach ($top_pages as $pg => $pgd): ?>
                                    <tr>
                                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis"><?php echo esc_html($pg); ?></td>
                                        <td><?php echo $pgd['views']; ?></td>
                                        <td><?php echo $pgd['unique']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Referrers -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-links"></span> Top Referrers</h3>
                    <?php if (empty($top_refs)): ?>
                        <div class="wnb-empty"><p>No referrer data yet.</p></div>
                    <?php else: ?>
                        <table class="wnb-table">
                            <thead><tr><th>Source</th><th>Visits</th></tr></thead>
                            <tbody>
                                <?php foreach ($top_refs as $ref => $cnt): ?>
                                    <tr>
                                        <td><?php echo esc_html($ref); ?></td>
                                        <td><?php echo $cnt; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Device Breakdown -->
            <div class="wnb-card" style="max-width:600px">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-smartphone"></span> Device Breakdown (30 days)</h3>
                <div class="wnb-device-bar">
                    <div class="wnb-device-bar-desktop" style="width:<?php echo round(($all_devices['desktop'] / $device_total) * 100); ?>%"></div>
                    <div class="wnb-device-bar-mobile" style="width:<?php echo round(($all_devices['mobile'] / $device_total) * 100); ?>%"></div>
                    <div class="wnb-device-bar-tablet" style="width:<?php echo round(($all_devices['tablet'] / $device_total) * 100); ?>%"></div>
                </div>
                <div class="wnb-device-legend">
                    <span class="wnb-legend-desktop">Desktop <?php echo round(($all_devices['desktop'] / $device_total) * 100); ?>%</span>
                    <span class="wnb-legend-mobile">Mobile <?php echo round(($all_devices['mobile'] / $device_total) * 100); ?>%</span>
                    <span class="wnb-legend-tablet">Tablet <?php echo round(($all_devices['tablet'] / $device_total) * 100); ?>%</span>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  10. AI ASSISTANT
     * ═══════════════════════════════════════════════════ */

    public function render_ai(): void {
        $has_key = !empty(get_option('wnb_claude_api_key', ''));
        $history = get_option('wnb_ai_history', []);
        $history = array_slice($history, 0, 20);
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('AI Assistant', 'Generate content with Claude AI'); ?>

            <?php if (!$has_key): ?>
                <div class="wnb-card" style="border-left:3px solid var(--wnb-warning);max-width:600px">
                    <div class="wnb-flex-center wnb-gap-sm">
                        <span class="dashicons dashicons-warning" style="color:var(--wnb-warning)"></span>
                        <div>
                            <strong style="color:var(--wnb-text)">API Key Required</strong>
                            <p style="font-size:13px;color:var(--wnb-text-secondary);margin:4px 0 0">Add your Claude API key in <a href="<?php echo admin_url('admin.php?page=webnewbiz-settings'); ?>">Settings</a> to use the AI Assistant.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="wnb-grid-2">
                <!-- Generator -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-lightbulb"></span> Content Generator</h3>

                    <div class="wnb-field">
                        <label class="wnb-label">Prompt</label>
                        <textarea id="wnb-ai-prompt" class="wnb-textarea" rows="4" placeholder="Describe what you want to generate. Be specific for better results..."></textarea>
                    </div>

                    <div class="wnb-grid-3" style="gap:12px;margin-bottom:16px">
                        <div class="wnb-field" style="margin:0">
                            <label class="wnb-label">Content Type</label>
                            <select id="wnb-ai-type" class="wnb-select" style="width:100%">
                                <option value="blog_post">Blog Post</option>
                                <option value="page_content">Page Content</option>
                                <option value="product_description">Product Description</option>
                                <option value="seo_meta">SEO Meta</option>
                                <option value="faq">FAQ</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div class="wnb-field" style="margin:0">
                            <label class="wnb-label">Tone</label>
                            <select id="wnb-ai-tone" class="wnb-select" style="width:100%">
                                <option value="professional">Professional</option>
                                <option value="friendly">Friendly</option>
                                <option value="casual">Casual</option>
                                <option value="formal">Formal</option>
                                <option value="humorous">Humorous</option>
                            </select>
                        </div>
                        <div class="wnb-field" style="margin:0">
                            <label class="wnb-label">Length</label>
                            <select id="wnb-ai-length" class="wnb-select" style="width:100%">
                                <option value="short">Short</option>
                                <option value="medium" selected>Medium</option>
                                <option value="long">Long</option>
                            </select>
                        </div>
                    </div>

                    <div class="wnb-flex-center wnb-gap-sm">
                        <button class="wnb-btn wnb-btn-primary" data-ai-generate <?php echo !$has_key ? 'disabled' : ''; ?>>
                            <span class="dashicons dashicons-admin-customizer" style="margin-top:2px"></span>
                            Generate
                        </button>
                        <button class="wnb-btn wnb-btn-secondary" data-ai-copy>
                            <span class="dashicons dashicons-clipboard" style="margin-top:2px"></span>
                            Copy
                        </button>
                    </div>
                </div>

                <!-- Output -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-text-page"></span> Generated Content</h3>
                    <div id="wnb-ai-output" class="wnb-ai-output"></div>
                </div>
            </div>

            <!-- History -->
            <div class="wnb-card">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-backup"></span> Recent Generations</h3>
                <?php if (empty($history)): ?>
                    <div class="wnb-empty"><p>No content generated yet. Use the form above to get started.</p></div>
                <?php else: ?>
                    <div style="max-height:300px;overflow-y:auto">
                        <?php foreach ($history as $h): ?>
                            <div class="wnb-ai-history-item">
                                <strong><?php echo esc_html(wp_trim_words($h['prompt'], 12)); ?></strong>
                                <span>
                                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $h['content_type']))); ?> &bull;
                                    <?php echo esc_html(ucfirst($h['tone'])); ?> &bull;
                                    <?php echo esc_html(date('M j, g:i A', strtotime($h['date']))); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  11. MAINTENANCE MODE
     * ═══════════════════════════════════════════════════ */

    public function render_maintenance(): void {
        $enabled     = get_option('wnb_maintenance_mode', '0');
        $message     = get_option('wnb_maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');
        $back_date   = get_option('wnb_maintenance_back_date', '');
        $bg_color    = get_option('wnb_maintenance_bg_color', '#1e293b');
        $custom_css  = get_option('wnb_maintenance_custom_css', '');
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Maintenance Mode', 'Show a maintenance page to visitors while you work on your site'); ?>

            <!-- Big Toggle -->
            <div class="wnb-card wnb-card-accent" style="max-width:600px">
                <div class="wnb-flex-between" style="padding:10px 0">
                    <div>
                        <h2 style="font-size:18px;margin:0 0 4px;color:var(--wnb-text)">Maintenance Mode</h2>
                        <p style="font-size:13px;color:var(--wnb-text-muted);margin:0">
                            Status:
                            <span id="wnb-maintenance-status" class="wnb-badge <?php echo $enabled === '1' ? 'wnb-badge-red' : 'wnb-badge-green'; ?>">
                                <?php echo $enabled === '1' ? 'Active' : 'Inactive'; ?>
                            </span>
                        </p>
                    </div>
                    <label class="wnb-toggle" style="transform:scale(1.3)">
                        <input type="checkbox" data-maintenance-toggle <?php checked($enabled, '1'); ?> />
                        <span class="wnb-toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="wnb-grid-2">
                <!-- Maintenance Settings -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-generic"></span> Maintenance Page Settings</h3>
                    <div class="wnb-field">
                        <label class="wnb-label">Custom Message</label>
                        <textarea id="wnb-maint-message" class="wnb-textarea" rows="3"><?php echo esc_textarea($message); ?></textarea>
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Expected Back Date/Time</label>
                        <input type="datetime-local" id="wnb-maint-date" class="wnb-input" value="<?php echo esc_attr($back_date); ?>" />
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Background Color</label>
                        <div class="wnb-color-input">
                            <input type="color" id="wnb-maint-bg" value="<?php echo esc_attr($bg_color); ?>" />
                            <input type="text" class="wnb-input" value="<?php echo esc_attr($bg_color); ?>" style="width:100px" readonly />
                        </div>
                    </div>
                    <?php $this->toggle('wnb_maintenance_allow_admins', 'Allow logged-in admins', 'Administrators can still view the site normally.'); ?>
                    <div class="wnb-field wnb-mt-md">
                        <label class="wnb-label">Custom CSS</label>
                        <textarea id="wnb-maint-css" class="wnb-code" rows="5"><?php echo esc_textarea($custom_css); ?></textarea>
                    </div>
                    <div class="wnb-mt-md">
                        <button class="wnb-btn wnb-btn-primary" data-save-maintenance>Save Maintenance Settings</button>
                    </div>
                </div>

                <!-- Preview -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-visibility"></span> Preview</h3>
                    <div style="background:<?php echo esc_attr($bg_color); ?>;color:#fff;border-radius:var(--wnb-radius-sm);padding:40px 20px;text-align:center;min-height:250px;display:flex;flex-direction:column;align-items:center;justify-content:center">
                        <h2 style="font-size:22px;margin:0 0 12px;color:#fff">Under Maintenance</h2>
                        <p style="opacity:0.85;margin:0;font-size:14px;max-width:350px"><?php echo esc_html($message); ?></p>
                        <?php if ($back_date): ?>
                            <p style="opacity:0.5;margin:16px 0 0;font-size:12px">Expected back: <?php echo esc_html($back_date); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  12. WHITE LABEL
     * ═══════════════════════════════════════════════════ */

    public function render_whitelabel(): void {
        $login_logo     = get_option('wnb_whitelabel_login_logo', '');
        $login_bg       = get_option('wnb_whitelabel_login_bg', '');
        $footer_text    = get_option('wnb_whitelabel_footer_text', '');
        $widget_title   = get_option('wnb_whitelabel_widget_title', '');
        $widget_content = get_option('wnb_whitelabel_widget_content', '');
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('White Label', 'Customize WordPress branding for your clients'); ?>

            <div class="wnb-grid-2">
                <!-- Login Page -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-lock"></span> Login Page Customization</h3>
                    <div class="wnb-field">
                        <label class="wnb-label">Custom Login Logo URL</label>
                        <input type="url" id="wnb-wl-login-logo" class="wnb-input" value="<?php echo esc_attr($login_logo); ?>" placeholder="https://example.com/logo.png" />
                        <p class="wnb-field-help">Replaces the WordPress logo on the login page. Recommended: 320x80px.</p>
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Login Background Image URL</label>
                        <input type="url" id="wnb-wl-login-bg" class="wnb-input" value="<?php echo esc_attr($login_bg); ?>" placeholder="https://example.com/background.jpg" />
                        <p class="wnb-field-help">Custom background image for the login page.</p>
                    </div>
                </div>

                <!-- Admin Customization -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-appearance"></span> Admin Customization</h3>
                    <div class="wnb-field">
                        <label class="wnb-label">Custom Admin Footer Text</label>
                        <input type="text" id="wnb-wl-footer" class="wnb-input" value="<?php echo esc_attr($footer_text); ?>" placeholder="Powered by Your Company" />
                        <p class="wnb-field-help">Replaces the default WordPress footer text in wp-admin.</p>
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Custom Dashboard Widget Title</label>
                        <input type="text" id="wnb-wl-widget-title" class="wnb-input" value="<?php echo esc_attr($widget_title); ?>" placeholder="Your Brand - Dashboard" />
                    </div>
                    <div class="wnb-field">
                        <label class="wnb-label">Custom Dashboard Widget Content</label>
                        <textarea id="wnb-wl-widget-content" class="wnb-textarea" rows="3"><?php echo esc_textarea($widget_content); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Toggles -->
            <div class="wnb-card" style="max-width:600px">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-hidden"></span> Branding Toggles</h3>
                <?php
                $this->toggle('wnb_whitelabel_hide_branding', 'Hide "WebNewBiz" branding', 'Removes WebNewBiz branding from the admin interface.');
                $this->toggle('wnb_whitelabel_remove_wp_logo', 'Remove WordPress logo from admin bar', 'Hides the WordPress icon in the top-left of the admin bar.');
                ?>
            </div>

            <div class="wnb-mt-md" style="max-width:600px">
                <button class="wnb-btn wnb-btn-primary" data-save-whitelabel>
                    <span class="dashicons dashicons-yes" style="margin-top:2px"></span>
                    Save White Label Settings
                </button>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  13. SETTINGS
     * ═══════════════════════════════════════════════════ */

    public function render_settings(): void {
        // Handle form POST (legacy non-AJAX save)
        if (isset($_POST['wnb_save_settings']) && wp_verify_nonce($_POST['_wpnonce'] ?? '', 'wnb_settings')) {
            if (isset($_POST['platform_url'])) update_option('webnewbiz_platform_url', sanitize_url($_POST['platform_url']));
            if (isset($_POST['claude_api_key'])) update_option('wnb_claude_api_key', sanitize_text_field($_POST['claude_api_key']));
            if (isset($_POST['notification_email'])) update_option('wnb_notification_email', sanitize_email($_POST['notification_email']));
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
        }

        $platform_url = get_option('webnewbiz_platform_url', 'http://localhost:4200/dashboard');
        $token        = get_option('webnewbiz_connection_token', '');
        $api_key      = get_option('wnb_claude_api_key', '');
        $notif_email  = get_option('wnb_notification_email', get_option('admin_email'));
        $connected_at = get_option('webnewbiz_connected_at', '');
        ?>
        <div class="wnb-wrap">
            <?php $this->page_header('Settings', 'Configure plugin settings and connections'); ?>

            <div class="wnb-grid-2">
                <!-- Platform Connection -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-links"></span> Platform Connection</h3>
                    <form method="post">
                        <?php wp_nonce_field('wnb_settings'); ?>
                        <div class="wnb-field">
                            <label class="wnb-label" for="platform_url">Platform URL</label>
                            <input type="url" id="platform_url" name="platform_url" class="wnb-input" value="<?php echo esc_attr($platform_url); ?>" />
                            <p class="wnb-field-help">The URL of your WebNewBiz platform dashboard.</p>
                        </div>
                        <div class="wnb-field">
                            <label class="wnb-label">Connection Token</label>
                            <code style="display:block;padding:10px 14px;background:var(--wnb-bg);border-radius:var(--wnb-radius-xs);font-size:12px;color:var(--wnb-text-secondary);word-break:break-all">
                                <?php echo $token ? esc_html(substr($token, 0, 8) . '...' . substr($token, -4)) : 'Not configured'; ?>
                            </code>
                            <p class="wnb-field-help">Managed automatically by the platform.</p>
                        </div>
                        <?php if ($connected_at): ?>
                            <p class="wnb-text-sm wnb-text-muted">Connected since: <?php echo esc_html(date('M j, Y g:i A', strtotime($connected_at))); ?></p>
                        <?php endif; ?>
                        <hr class="wnb-divider" />
                        <button type="submit" name="wnb_save_settings" class="wnb-btn wnb-btn-primary">Save Settings</button>
                    </form>
                </div>

                <!-- AI & Notifications -->
                <div class="wnb-card">
                    <h3 class="wnb-card-title"><span class="dashicons dashicons-admin-generic"></span> API & Notifications</h3>
                    <form method="post">
                        <?php wp_nonce_field('wnb_settings'); ?>
                        <div class="wnb-field">
                            <label class="wnb-label" for="claude_api_key">Claude API Key</label>
                            <input type="password" id="claude_api_key" name="claude_api_key" class="wnb-input" value="<?php echo esc_attr($api_key); ?>" placeholder="sk-ant-..." autocomplete="off" />
                            <p class="wnb-field-help">Required for AI Assistant. Get your key from <a href="https://console.anthropic.com/" target="_blank">Anthropic Console</a>.</p>
                        </div>
                        <?php $this->toggle('wnb_email_notifications', 'Email Notifications', 'Receive email alerts for security events and backup failures.'); ?>
                        <div class="wnb-field wnb-mt-md">
                            <label class="wnb-label" for="notification_email">Notification Email</label>
                            <input type="email" id="notification_email" name="notification_email" class="wnb-input" value="<?php echo esc_attr($notif_email); ?>" />
                        </div>
                        <hr class="wnb-divider" />
                        <button type="submit" name="wnb_save_settings" class="wnb-btn wnb-btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>

            <!-- License -->
            <div class="wnb-card" style="max-width:600px">
                <h3 class="wnb-card-title"><span class="dashicons dashicons-awards"></span> License</h3>
                <div class="wnb-flex-center wnb-gap-md" style="padding:12px 0">
                    <span class="wnb-badge wnb-badge-premium" style="font-size:13px;padding:4px 14px">Premium</span>
                    <div>
                        <strong style="color:var(--wnb-text)">WebNewBiz Builder v<?php echo WEBNEWBIZ_VERSION; ?></strong>
                        <p style="font-size:12px;color:var(--wnb-text-muted);margin:2px 0 0">License managed by the WebNewBiz platform. All premium features are active.</p>
                    </div>
                </div>
            </div>

            <?php $this->page_footer(); ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════
     *  WP DASHBOARD WIDGET
     * ═══════════════════════════════════════════════════ */

    public function render_dashboard_widget(): void {
        $platform_url = get_option('webnewbiz_platform_url', 'http://localhost:4200/dashboard');
        $pages   = (int)(wp_count_posts('page')->publish ?? 0);
        $is_woo  = class_exists('WooCommerce');
        $health  = $this->compute_health_score();
        ?>
        <div class="wnb-widget">
            <div class="wnb-widget-status">
                <span class="wnb-dot"></span>
                <strong>Connected</strong> to WebNewBiz Platform
            </div>
            <div class="wnb-widget-stats">
                <div class="wnb-widget-stat">
                    <span class="wnb-widget-stat-val"><?php echo $pages; ?></span>
                    <span class="wnb-widget-stat-lbl">Pages</span>
                </div>
                <div class="wnb-widget-stat">
                    <span class="wnb-widget-stat-val"><?php echo count(get_option('active_plugins', [])); ?></span>
                    <span class="wnb-widget-stat-lbl">Plugins</span>
                </div>
                <div class="wnb-widget-stat">
                    <span class="wnb-widget-stat-val"><?php echo $health; ?>%</span>
                    <span class="wnb-widget-stat-lbl">Health</span>
                </div>
                <?php if ($is_woo): ?>
                    <div class="wnb-widget-stat">
                        <span class="wnb-widget-stat-val wnb-text-green">Active</span>
                        <span class="wnb-widget-stat-lbl">WooCommerce</span>
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url($platform_url); ?>" class="wnb-btn wnb-btn-sm" target="_blank">
                Open Platform Dashboard &rarr;
            </a>
        </div>
        <?php
    }
}
