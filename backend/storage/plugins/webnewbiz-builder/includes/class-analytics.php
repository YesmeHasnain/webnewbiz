<?php
/**
 * WebNewBiz Basic Analytics
 *
 * Privacy-respecting visitor tracking with custom table storage.
 * Tracks page views, unique visitors, referrers, device types,
 * and provides time-series data for charting.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Analytics {

    private static ?self $instance = null;

    /** Custom table name (without prefix) */
    private const TABLE_NAME = 'wnb_analytics';

    /** Days to keep analytics data before cleanup */
    private const RETENTION_DAYS = 90;

    /** Known bot user-agent fragments */
    private const BOT_PATTERNS = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
        'yandexbot', 'sogou', 'exabot', 'facebot', 'facebookexternalhit',
        'ia_archiver', 'mj12bot', 'semrushbot', 'ahrefsbot', 'dotbot',
        'rogerbot', 'screaming frog', 'sitebulb', 'blexbot', 'petalbot',
        'applebot', 'twitterbot', 'linkedinbot', 'pinterest', 'uptimerobot',
        'gptbot', 'claudebot', 'bytespider', 'ccbot', 'crawl', 'spider',
        'bot/', 'bot;', 'headlesschrome', 'phantomjs', 'wget', 'curl',
        'python-requests', 'go-http-client', 'java/', 'libwww', 'httpie',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Track visits on the frontend
        add_action('wp', [$this, 'track_visit']);

        // Cleanup cron
        add_action('wnb_analytics_cleanup', [$this, 'cleanup_old_data']);
        if (!wp_next_scheduled('wnb_analytics_cleanup')) {
            wp_schedule_event(time(), 'weekly', 'wnb_analytics_cleanup');
        }

        // AJAX handlers
        add_action('wp_ajax_wnb_get_analytics', [$this, 'ajax_get_analytics']);
        add_action('wp_ajax_wnb_get_popular_pages', [$this, 'ajax_get_popular_pages']);
    }

    // ──────────────────────────────────────────────
    //  Table Creation
    // ──────────────────────────────────────────────

    /**
     * Create the analytics table. Call from plugin activation hook.
     */
    public static function create_table(): void {
        global $wpdb;

        $table_name      = $wpdb->prefix . self::TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            page_url VARCHAR(500) NOT NULL DEFAULT '',
            referrer VARCHAR(500) NOT NULL DEFAULT '',
            user_agent VARCHAR(500) NOT NULL DEFAULT '',
            ip_hash VARCHAR(64) NOT NULL DEFAULT '',
            device_type VARCHAR(10) NOT NULL DEFAULT 'desktop',
            visitor_id VARCHAR(64) NOT NULL DEFAULT '',
            created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id),
            KEY idx_created_at (created_at),
            KEY idx_page_url (page_url(191)),
            KEY idx_ip_hash (ip_hash),
            KEY idx_device_type (device_type),
            KEY idx_visitor_id (visitor_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // Store the DB version for future migrations
        update_option('wnb_analytics_db_version', '1.0.0');
    }

    /**
     * Get the full table name with prefix.
     */
    private function table(): string {
        global $wpdb;
        return $wpdb->prefix . self::TABLE_NAME;
    }

    // ──────────────────────────────────────────────
    //  Visit Tracking
    // ──────────────────────────────────────────────

    /**
     * Track a page visit. Hooked to 'wp' action.
     * Skips: admin pages, bots, logged-in administrators, AJAX/cron requests.
     */
    public function track_visit(): void {
        // Only track frontend page loads
        if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        // Don't track admin users
        if (is_user_logged_in() && current_user_can('manage_options')) {
            return;
        }

        // Don't track preview pages
        if (is_preview()) {
            return;
        }

        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Don't track bots
        if ($this->is_bot($user_agent)) {
            return;
        }

        global $wpdb;

        // Page URL — path only for privacy and storage efficiency
        $page_url = wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $page_url = substr($page_url, 0, 500);

        // Referrer — store domain only for privacy
        $referrer = '';
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $ref_parsed = wp_parse_url($_SERVER['HTTP_REFERER']);
            $ref_host   = $ref_parsed['host'] ?? '';
            $site_host  = wp_parse_url(home_url(), PHP_URL_HOST);

            // Only store external referrers
            if ($ref_host && $ref_host !== $site_host) {
                $referrer = substr($ref_host, 0, 500);
            }
        }

        // IP hash for unique visitor approximation (privacy-safe)
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ip_hash = hash('sha256', $ip . wp_salt('auth'));

        // Device type
        $device_type = $this->detect_device($user_agent);

        // Visitor cookie for session/unique tracking
        $visitor_id = '';
        if (isset($_COOKIE['wnb_visitor'])) {
            $visitor_id = sanitize_text_field($_COOKIE['wnb_visitor']);
        } else {
            $visitor_id = wp_generate_uuid4();
            // Set cookie for 30 days — use setcookie since headers haven't been sent in 'wp' hook
            // We check headers_sent() to be safe
            if (!headers_sent()) {
                setcookie('wnb_visitor', $visitor_id, [
                    'expires'  => time() + (30 * DAY_IN_SECONDS),
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => is_ssl(),
                    'httponly'  => true,
                    'samesite' => 'Lax',
                ]);
            }
        }

        $wpdb->insert(
            $this->table(),
            [
                'page_url'    => $page_url,
                'referrer'    => $referrer,
                'user_agent'  => substr($user_agent, 0, 500),
                'ip_hash'     => $ip_hash,
                'device_type' => $device_type,
                'visitor_id'  => $visitor_id,
                'created_at'  => current_time('mysql', true), // GMT
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );
    }

    // ──────────────────────────────────────────────
    //  Data Retrieval
    // ──────────────────────────────────────────────

    /**
     * Get total page views for a period.
     *
     * @param string $period today|7days|30days|all
     */
    public function get_views(string $period = '7days'): int {
        global $wpdb;

        $where = $this->period_where($period);
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->table()} WHERE 1=1 {$where}");
    }

    /**
     * Get unique visitor count for a period (by ip_hash).
     */
    public function get_unique_visitors(string $period = '7days'): int {
        global $wpdb;

        $where = $this->period_where($period);
        return (int) $wpdb->get_var("SELECT COUNT(DISTINCT ip_hash) FROM {$this->table()} WHERE 1=1 {$where}");
    }

    /**
     * Get most-visited pages for a period.
     *
     * @return array [{url, title, views, unique_views}]
     */
    public function get_popular_pages(string $period = '7days', int $limit = 10): array {
        global $wpdb;

        $where = $this->period_where($period);
        $limit = max(1, min(100, $limit));

        $rows = $wpdb->get_results(
            "SELECT page_url,
                    COUNT(*) as views,
                    COUNT(DISTINCT ip_hash) as unique_views
             FROM {$this->table()}
             WHERE 1=1 {$where}
             GROUP BY page_url
             ORDER BY views DESC
             LIMIT {$limit}"
        );

        $results = [];
        foreach ($rows as $row) {
            // Try to resolve page URL to a WordPress title
            $title = $this->url_to_title($row->page_url);

            $results[] = [
                'url'          => $row->page_url,
                'title'        => $title,
                'views'        => (int) $row->views,
                'unique_views' => (int) $row->unique_views,
            ];
        }

        return $results;
    }

    /**
     * Get top referrers for a period.
     *
     * @return array [{source, visits}]
     */
    public function get_referrers(string $period = '7days', int $limit = 10): array {
        global $wpdb;

        $where = $this->period_where($period);
        $limit = max(1, min(100, $limit));

        $rows = $wpdb->get_results(
            "SELECT referrer as source,
                    COUNT(*) as visits
             FROM {$this->table()}
             WHERE referrer != '' {$where}
             GROUP BY referrer
             ORDER BY visits DESC
             LIMIT {$limit}"
        );

        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                'source' => $row->source,
                'visits' => (int) $row->visits,
            ];
        }

        return $results;
    }

    /**
     * Get device type breakdown for a period.
     *
     * @return array {desktop: N, mobile: N, tablet: N}
     */
    public function get_device_stats(string $period = '7days'): array {
        global $wpdb;

        $where = $this->period_where($period);

        $rows = $wpdb->get_results(
            "SELECT device_type, COUNT(*) as cnt
             FROM {$this->table()}
             WHERE 1=1 {$where}
             GROUP BY device_type"
        );

        $stats = ['desktop' => 0, 'mobile' => 0, 'tablet' => 0];
        foreach ($rows as $row) {
            if (isset($stats[$row->device_type])) {
                $stats[$row->device_type] = (int) $row->cnt;
            }
        }

        return $stats;
    }

    /**
     * Get views grouped by date for charting.
     *
     * @return array [{date: 'YYYY-MM-DD', views: N}]
     */
    public function get_views_over_time(string $period = '7days'): array {
        global $wpdb;

        $where = $this->period_where($period);

        $rows = $wpdb->get_results(
            "SELECT DATE(created_at) as date, COUNT(*) as views
             FROM {$this->table()}
             WHERE 1=1 {$where}
             GROUP BY DATE(created_at)
             ORDER BY date ASC"
        );

        // Fill in gaps for the period so the chart has a continuous line
        $days_map = [];
        foreach ($rows as $row) {
            $days_map[$row->date] = (int) $row->views;
        }

        $num_days = $this->period_to_days($period);
        $results  = [];

        if ($num_days > 0) {
            for ($i = $num_days - 1; $i >= 0; $i--) {
                $date = gmdate('Y-m-d', strtotime("-{$i} days"));
                $results[] = [
                    'date'  => $date,
                    'views' => $days_map[$date] ?? 0,
                ];
            }
        } else {
            // "all" period — just return what we have
            foreach ($rows as $row) {
                $results[] = [
                    'date'  => $row->date,
                    'views' => (int) $row->views,
                ];
            }
        }

        return $results;
    }

    /**
     * Get real-time visitor count (views in last N minutes).
     */
    public function get_real_time(int $minutes = 5): int {
        global $wpdb;

        $minutes = max(1, min(60, $minutes));
        $since   = gmdate('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table()} WHERE created_at >= %s",
                $since
            )
        );
    }

    // ──────────────────────────────────────────────
    //  Cleanup
    // ──────────────────────────────────────────────

    /**
     * Delete records older than the retention period. Hooked to weekly cron.
     */
    public function cleanup_old_data(): int {
        global $wpdb;

        $cutoff = gmdate('Y-m-d H:i:s', strtotime('-' . self::RETENTION_DAYS . ' days'));

        $deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$this->table()} WHERE created_at < %s",
                $cutoff
            )
        );

        return (int) $deleted;
    }

    // ──────────────────────────────────────────────
    //  Bot & Device Detection
    // ──────────────────────────────────────────────

    /**
     * Check if a user agent belongs to a known bot.
     */
    public function is_bot(string $user_agent): bool {
        if (empty($user_agent)) return true;

        $ua_lower = strtolower($user_agent);

        foreach (self::BOT_PATTERNS as $pattern) {
            if (strpos($ua_lower, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect device type from user agent.
     *
     * @return string 'mobile'|'tablet'|'desktop'
     */
    public function detect_device(string $user_agent): string {
        if (empty($user_agent)) return 'desktop';

        $ua = strtolower($user_agent);

        // Tablets (check first — many tablets have "mobile" in UA too)
        $tablet_patterns = [
            'ipad', 'tablet', 'kindle', 'silk', 'playbook',
            'nexus 7', 'nexus 9', 'nexus 10', 'sm-t', 'gt-p',
            'lenovo tab', 'mediapad', 'surface',
        ];
        foreach ($tablet_patterns as $pattern) {
            if (strpos($ua, $pattern) !== false) {
                return 'tablet';
            }
        }

        // Android tablets without "mobile"
        if (strpos($ua, 'android') !== false && strpos($ua, 'mobile') === false) {
            return 'tablet';
        }

        // Mobile phones
        $mobile_patterns = [
            'iphone', 'ipod', 'android', 'mobile', 'phone',
            'blackberry', 'opera mini', 'opera mobi', 'iemobile',
            'windows phone', 'symbian', 'nokia', 'samsung', 'webos',
            'palm', 'bolt', 'fennec', 'minimo', 'maemo', 'blazer',
        ];
        foreach ($mobile_patterns as $pattern) {
            if (strpos($ua, $pattern) !== false) {
                return 'mobile';
            }
        }

        return 'desktop';
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Return analytics dashboard data.
     */
    public function ajax_get_analytics(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $period = sanitize_text_field($_POST['period'] ?? '7days');
        if (!in_array($period, ['today', '7days', '30days', 'all'], true)) {
            $period = '7days';
        }

        wp_send_json_success([
            'period'          => $period,
            'views'           => $this->get_views($period),
            'unique_visitors' => $this->get_unique_visitors($period),
            'popular_pages'   => $this->get_popular_pages($period, 10),
            'referrers'       => $this->get_referrers($period, 10),
            'device_stats'    => $this->get_device_stats($period),
            'views_over_time' => $this->get_views_over_time($period),
            'real_time'       => $this->get_real_time(5),
        ]);
    }

    /**
     * AJAX: Return popular pages list.
     */
    public function ajax_get_popular_pages(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $period = sanitize_text_field($_POST['period'] ?? '7days');
        $limit  = (int) ($_POST['limit'] ?? 20);

        if (!in_array($period, ['today', '7days', '30days', 'all'], true)) {
            $period = '7days';
        }

        wp_send_json_success([
            'period' => $period,
            'pages'  => $this->get_popular_pages($period, $limit),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Private Helpers
    // ──────────────────────────────────────────────

    /**
     * Build a SQL WHERE clause fragment for the given period.
     * All dates are in GMT (created_at is stored as GMT).
     */
    private function period_where(string $period): string {
        switch ($period) {
            case 'today':
                $since = gmdate('Y-m-d 00:00:00');
                return " AND created_at >= '{$since}'";

            case '7days':
                $since = gmdate('Y-m-d H:i:s', strtotime('-7 days'));
                return " AND created_at >= '{$since}'";

            case '30days':
                $since = gmdate('Y-m-d H:i:s', strtotime('-30 days'));
                return " AND created_at >= '{$since}'";

            case 'all':
            default:
                return '';
        }
    }

    /**
     * Convert period string to number of days (for chart gap-filling).
     */
    private function period_to_days(string $period): int {
        return match ($period) {
            'today'  => 1,
            '7days'  => 7,
            '30days' => 30,
            default  => 0, // "all" — no gap filling
        };
    }

    /**
     * Try to resolve a URL path to a WordPress post/page title.
     */
    private function url_to_title(string $path): string {
        if ($path === '/') {
            return get_bloginfo('name') . ' (Home)';
        }

        // Try url_to_postid
        $post_id = url_to_postid(home_url($path));
        if ($post_id) {
            return get_the_title($post_id) ?: $path;
        }

        // Return a cleaned-up version of the path
        $clean = trim($path, '/');
        $clean = str_replace(['-', '_', '/'], [' ', ' ', ' > '], $clean);
        return ucwords($clean);
    }

    /**
     * Deactivation: clear the cron event.
     */
    public static function deactivate(): void {
        $timestamp = wp_next_scheduled('wnb_analytics_cleanup');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wnb_analytics_cleanup');
        }
    }
}
