<?php
/**
 * WebNewBiz Security Shield
 *
 * Hardens WordPress with toggleable security features:
 * XML-RPC blocking, login limiting, security headers, version hiding,
 * user enumeration prevention, activity logging, and more.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Security {

    private static ?self $instance = null;

    /** Default settings for every toggle */
    private array $defaults = [
        'disable_xmlrpc'           => false,
        'disable_file_editor'      => false,
        'hide_wp_version'          => false,
        'add_security_headers'     => false,
        'limit_login_attempts'     => false,
        'disable_user_enumeration' => false,
        'disable_php_in_uploads'   => false,
        'force_ssl_admin'          => false,
    ];

    /** Points each feature contributes to the security score */
    private array $score_weights = [
        'disable_xmlrpc'           => 10,
        'disable_file_editor'      => 10,
        'hide_wp_version'          => 10,
        'add_security_headers'     => 15,
        'limit_login_attempts'     => 20,
        'disable_user_enumeration' => 10,
        'disable_php_in_uploads'   => 15,
        'force_ssl_admin'          => 10,
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $settings = $this->get_settings();

        // ── Activate features based on saved settings ──────────────
        if (!empty($settings['disable_xmlrpc'])) {
            $this->disable_xmlrpc();
        }
        if (!empty($settings['disable_file_editor'])) {
            $this->disable_file_editor();
        }
        if (!empty($settings['hide_wp_version'])) {
            $this->hide_wp_version();
        }
        if (!empty($settings['add_security_headers'])) {
            $this->add_security_headers();
        }
        if (!empty($settings['limit_login_attempts'])) {
            $this->limit_login_attempts();
        }
        if (!empty($settings['disable_user_enumeration'])) {
            $this->disable_user_enumeration();
        }
        if (!empty($settings['disable_php_in_uploads'])) {
            $this->disable_php_in_uploads();
        }
        if (!empty($settings['force_ssl_admin'])) {
            $this->force_ssl_admin();
        }

        // ── Activity-logging hooks (always active) ─────────────────
        add_action('wp_login', [$this, 'on_login_success'], 10, 2);
        add_action('wp_login_failed', [$this, 'on_login_failed']);
        add_action('activated_plugin', [$this, 'on_plugin_activated'], 10, 2);
        add_action('deactivated_plugin', [$this, 'on_plugin_deactivated'], 10, 2);
        add_action('switch_theme', [$this, 'on_theme_switch'], 10, 3);

        // ── AJAX handlers ──────────────────────────────────────────
        add_action('wp_ajax_wnb_save_security_settings', [$this, 'ajax_save_security_settings']);
        add_action('wp_ajax_wnb_get_security_score', [$this, 'ajax_get_security_score']);
        add_action('wp_ajax_wnb_get_activity_log', [$this, 'ajax_get_activity_log']);
    }

    // ================================================================
    //  Settings helpers
    // ================================================================

    /**
     * Merge saved settings with defaults.
     */
    public function get_settings(): array {
        $saved = get_option('wnb_security_settings', []);
        if (!is_array($saved)) {
            $saved = [];
        }
        return array_merge($this->defaults, $saved);
    }

    /**
     * Persist a single toggle (or the whole array).
     */
    public function save_settings(array $data): void {
        $current = $this->get_settings();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->defaults)) {
                $current[$key] = (bool) $value;
            }
        }
        update_option('wnb_security_settings', $current);
    }

    // ================================================================
    //  Feature: Disable XML-RPC
    // ================================================================

    public function disable_xmlrpc(): void {
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('wp_headers', function (array $headers): array {
            unset($headers['X-Pingback']);
            return $headers;
        });
        // Write .htaccess rule to block xmlrpc.php at the server level
        $this->write_xmlrpc_htaccess();
    }

    /**
     * Add a deny rule for xmlrpc.php inside the site root .htaccess.
     */
    private function write_xmlrpc_htaccess(): void {
        $htaccess = ABSPATH . '.htaccess';
        if (!file_exists($htaccess)) {
            return;
        }
        $contents = file_get_contents($htaccess);
        if ($contents === false) {
            return;
        }
        $marker = '# BEGIN WebNewBiz XML-RPC Block';
        if (str_contains($contents, $marker)) {
            return; // already present
        }
        $rule = "\n" . $marker . "\n"
            . "<Files xmlrpc.php>\n"
            . "  <IfModule mod_authz_core.c>\n"
            . "    Require all denied\n"
            . "  </IfModule>\n"
            . "  <IfModule !mod_authz_core.c>\n"
            . "    Order Allow,Deny\n"
            . "    Deny from all\n"
            . "  </IfModule>\n"
            . "</Files>\n"
            . "# END WebNewBiz XML-RPC Block\n";
        file_put_contents($htaccess, $contents . $rule);
    }

    // ================================================================
    //  Feature: Disable Theme/Plugin File Editor
    // ================================================================

    public function disable_file_editor(): void {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    // ================================================================
    //  Feature: Hide WordPress Version
    // ================================================================

    public function hide_wp_version(): void {
        remove_action('wp_head', 'wp_generator');
        add_filter('the_generator', '__return_empty_string');
        // Also strip version from enqueued styles/scripts
        add_filter('style_loader_src', [$this, 'strip_version_qs'], 9999);
        add_filter('script_loader_src', [$this, 'strip_version_qs'], 9999);
    }

    /**
     * Remove ?ver= query string from asset URLs.
     */
    public function strip_version_qs(string $src): string {
        if (str_contains($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    // ================================================================
    //  Feature: Security Headers
    // ================================================================

    public function add_security_headers(): void {
        add_filter('wp_headers', function (array $headers): array {
            $headers['X-Content-Type-Options']  = 'nosniff';
            $headers['X-Frame-Options']         = 'SAMEORIGIN';
            $headers['X-XSS-Protection']        = '1; mode=block';
            $headers['Referrer-Policy']          = 'strict-origin-when-cross-origin';
            $headers['Permissions-Policy']       = 'camera=(), microphone=(), geolocation=()';
            return $headers;
        });

        // Also send headers on admin pages via send_headers
        add_action('send_headers', function (): void {
            if (headers_sent()) {
                return;
            }
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        });
    }

    // ================================================================
    //  Feature: Limit Login Attempts
    // ================================================================

    public function limit_login_attempts(): void {
        add_action('wp_login_failed', [$this, 'track_failed_login']);
        add_filter('authenticate', [$this, 'check_lockout'], 30, 1);
    }

    /**
     * Record a failed login attempt for the client IP.
     */
    public function track_failed_login(string $username): void {
        $ip  = $this->get_client_ip();
        $key = 'wnb_login_fails_' . md5($ip);

        $attempts = (int) get_transient($key);
        $attempts++;
        set_transient($key, $attempts, 15 * MINUTE_IN_SECONDS);

        if ($attempts >= 5) {
            // Store in persistent lockout list
            $lockouts = get_option('wnb_lockouts', []);
            if (!is_array($lockouts)) {
                $lockouts = [];
            }
            $lockouts[$ip] = time() + (15 * 60);
            update_option('wnb_lockouts', $lockouts);

            $this->log_activity('ip_locked', sprintf(
                'IP %s locked out after %d failed login attempts (user: %s)',
                $ip,
                $attempts,
                sanitize_text_field($username)
            ));
        }
    }

    /**
     * Prevent authentication if the IP is currently locked out.
     *
     * @param \WP_User|\WP_Error|null $user
     * @return \WP_User|\WP_Error|null
     */
    public function check_lockout($user) {
        $ip       = $this->get_client_ip();
        $lockouts = get_option('wnb_lockouts', []);

        if (!is_array($lockouts)) {
            return $user;
        }

        if (isset($lockouts[$ip]) && $lockouts[$ip] > time()) {
            $remaining = (int) ceil(($lockouts[$ip] - time()) / 60);
            return new \WP_Error(
                'wnb_locked_out',
                sprintf(
                    '<strong>Security alert:</strong> Too many failed login attempts. Please try again in %d minute(s).',
                    $remaining
                )
            );
        }

        // Expired lockout — clean up
        if (isset($lockouts[$ip]) && $lockouts[$ip] <= time()) {
            unset($lockouts[$ip]);
            update_option('wnb_lockouts', $lockouts);
            delete_transient('wnb_login_fails_' . md5($ip));
        }

        return $user;
    }

    /**
     * Return the number of IPs that are currently locked out.
     */
    public function get_blocked_count(): int {
        $lockouts = get_option('wnb_lockouts', []);
        if (!is_array($lockouts)) {
            return 0;
        }
        $now   = time();
        $count = 0;
        foreach ($lockouts as $ip => $expires) {
            if ($expires > $now) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Determine the client's real IP address.
     */
    private function get_client_ip(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // X-Forwarded-For may contain a chain — take the first
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }

    // ================================================================
    //  Feature: Disable User Enumeration
    // ================================================================

    public function disable_user_enumeration(): void {
        // Block ?author=N on the front-end
        add_action('parse_request', function (\WP $wp): void {
            if (!is_admin() && isset($wp->query_vars['author']) && is_numeric($wp->query_vars['author'])) {
                wp_safe_redirect(home_url(), 301);
                exit;
            }
        });

        // Block the redirect_canonical from resolving author IDs
        add_filter('redirect_canonical', function (string $redirect_url, string $requested_url): string {
            if (preg_match('/\?author=\d+/i', $requested_url)) {
                return home_url();
            }
            return $redirect_url;
        }, 10, 2);

        // Block REST API user enumeration for non-logged-in users
        add_filter('rest_endpoints', function (array $endpoints): array {
            if (!is_user_logged_in()) {
                unset($endpoints['/wp/v2/users']);
                unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
            }
            return $endpoints;
        });
    }

    // ================================================================
    //  Feature: Disable PHP Execution in Uploads
    // ================================================================

    public function disable_php_in_uploads(): void {
        $upload_dir = wp_upload_dir();
        $target     = trailingslashit($upload_dir['basedir']) . '.htaccess';

        if (file_exists($target)) {
            $contents = file_get_contents($target);
            if ($contents !== false && str_contains($contents, '# WebNewBiz: Deny PHP')) {
                return; // already present
            }
        }

        $rule = "# WebNewBiz: Deny PHP Execution\n"
            . "<Files \"*.php\">\n"
            . "  <IfModule mod_authz_core.c>\n"
            . "    Require all denied\n"
            . "  </IfModule>\n"
            . "  <IfModule !mod_authz_core.c>\n"
            . "    Order Allow,Deny\n"
            . "    Deny from all\n"
            . "  </IfModule>\n"
            . "</Files>\n"
            . "<Files \"*.phtml\">\n"
            . "  <IfModule mod_authz_core.c>\n"
            . "    Require all denied\n"
            . "  </IfModule>\n"
            . "  <IfModule !mod_authz_core.c>\n"
            . "    Order Allow,Deny\n"
            . "    Deny from all\n"
            . "  </IfModule>\n"
            . "</Files>\n";

        file_put_contents($target, $rule);
    }

    // ================================================================
    //  Feature: Force SSL Admin
    // ================================================================

    public function force_ssl_admin(): void {
        if (is_ssl() && !defined('FORCE_SSL_ADMIN')) {
            define('FORCE_SSL_ADMIN', true);
        }
    }

    // ================================================================
    //  Security Score
    // ================================================================

    /**
     * Calculate a 0–100 security score based on enabled features.
     */
    public function get_security_score(): int {
        $settings   = $this->get_settings();
        $earned     = 0;
        $max_points = array_sum($this->score_weights);

        foreach ($this->score_weights as $feature => $points) {
            if (!empty($settings[$feature])) {
                $earned += $points;
            }
        }

        return ($max_points > 0) ? (int) round(($earned / $max_points) * 100) : 0;
    }

    // ================================================================
    //  Activity Log
    // ================================================================

    private const LOG_MAX_ENTRIES = 200;

    /**
     * Append an entry to the activity log (FIFO, max 200).
     */
    public function log_activity(string $action, string $details = ''): void {
        $log = get_option('wnb_activity_log', []);
        if (!is_array($log)) {
            $log = [];
        }

        array_unshift($log, [
            'action'    => sanitize_text_field($action),
            'details'   => sanitize_text_field($details),
            'ip'        => $this->get_client_ip(),
            'timestamp' => current_time('mysql'),
            'user_id'   => get_current_user_id(),
        ]);

        // Trim to max entries
        if (count($log) > self::LOG_MAX_ENTRIES) {
            $log = array_slice($log, 0, self::LOG_MAX_ENTRIES);
        }

        update_option('wnb_activity_log', $log, false); // autoload = false
    }

    /**
     * Return the last $count entries from the activity log.
     */
    public function get_activity_log(int $count = 50): array {
        $log = get_option('wnb_activity_log', []);
        if (!is_array($log)) {
            return [];
        }
        return array_slice($log, 0, $count);
    }

    // ── Logging hook callbacks ──────────────────────────────────────

    public function on_login_success(string $username, \WP_User $user): void {
        $this->log_activity('login_success', sprintf(
            'User "%s" (ID: %d) logged in successfully',
            $username,
            $user->ID
        ));
    }

    public function on_login_failed(string $username): void {
        $this->log_activity('login_failed', sprintf(
            'Failed login attempt for username "%s"',
            $username
        ));
    }

    public function on_plugin_activated(string $plugin, bool $network_wide): void {
        $this->log_activity('plugin_activated', sprintf(
            'Plugin activated: %s%s',
            $plugin,
            $network_wide ? ' (network-wide)' : ''
        ));
    }

    public function on_plugin_deactivated(string $plugin, bool $network_wide): void {
        $this->log_activity('plugin_deactivated', sprintf(
            'Plugin deactivated: %s%s',
            $plugin,
            $network_wide ? ' (network-wide)' : ''
        ));
    }

    public function on_theme_switch(string $new_name, \WP_Theme $new_theme, \WP_Theme $old_theme): void {
        $this->log_activity('theme_switch', sprintf(
            'Theme changed from "%s" to "%s"',
            $old_theme->get('Name'),
            $new_name
        ));
    }

    // ================================================================
    //  AJAX Handlers
    // ================================================================

    /**
     * Save an individual security toggle.
     *
     * POST: nonce, feature (string), enabled (0|1)
     */
    public function ajax_save_security_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $feature = sanitize_key($_POST['feature'] ?? '');
        $enabled = !empty($_POST['enabled']);

        if (!array_key_exists($feature, $this->defaults)) {
            wp_send_json_error(['message' => 'Unknown security feature: ' . $feature], 400);
        }

        $this->save_settings([$feature => $enabled]);

        $this->log_activity(
            'security_setting_changed',
            sprintf('Feature "%s" %s', $feature, $enabled ? 'enabled' : 'disabled')
        );

        wp_send_json_success([
            'feature'  => $feature,
            'enabled'  => $enabled,
            'score'    => $this->get_security_score(),
            'settings' => $this->get_settings(),
        ]);
    }

    /**
     * Return the current security score.
     */
    public function ajax_get_security_score(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        wp_send_json_success([
            'score'         => $this->get_security_score(),
            'settings'      => $this->get_settings(),
            'blocked_ips'   => $this->get_blocked_count(),
            'weights'       => $this->score_weights,
        ]);
    }

    /**
     * Return recent activity log entries.
     */
    public function ajax_get_activity_log(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $count = absint($_POST['count'] ?? 50);
        $count = min($count, self::LOG_MAX_ENTRIES);

        wp_send_json_success([
            'log'   => $this->get_activity_log($count),
            'total' => count(get_option('wnb_activity_log', [])),
        ]);
    }
}
