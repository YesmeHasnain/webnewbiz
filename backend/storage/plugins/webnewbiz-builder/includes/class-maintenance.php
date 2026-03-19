<?php
/**
 * WebNewBiz Maintenance Mode
 *
 * Displays a beautiful, self-contained maintenance page to visitors
 * while allowing logged-in administrators to browse the site normally.
 * Responds with HTTP 503 (Service Unavailable) + Retry-After header.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Maintenance {

    private static ?self $instance = null;

    /** Default maintenance settings */
    private array $defaults = [
        'enabled'    => false,
        'message'    => "We're currently updating our website. We'll be back shortly with something amazing!",
        'back_date'  => '',         // ISO 8601 datetime, e.g. 2026-03-15T18:00:00
        'bg_color'   => '#0f172a',  // Slate-900
        'allow_admins' => true,
        'custom_css' => '',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Front-end intercept — very high priority so it fires first
        add_action('template_redirect', [$this, 'render_maintenance_page'], 1);

        // Let REST API / AJAX still work for admin
        add_filter('rest_authentication_errors', [$this, 'rest_block'], 99);

        // Admin bar notice so admins know maintenance is on
        add_action('admin_bar_menu', [$this, 'admin_bar_notice'], 999);

        // AJAX handlers
        add_action('wp_ajax_wnb_toggle_maintenance', [$this, 'ajax_toggle_maintenance']);
        add_action('wp_ajax_wnb_save_maintenance_settings', [$this, 'ajax_save_maintenance_settings']);
    }

    // ================================================================
    //  State helpers
    // ================================================================

    public function is_enabled(): bool {
        return (bool) get_option('wnb_maintenance_enabled', false);
    }

    public function enable(): void {
        update_option('wnb_maintenance_enabled', true);
        if (class_exists('WebNewBiz_Security')) {
            WebNewBiz_Security::instance()->log_activity('maintenance_enabled', 'Maintenance mode turned ON');
        }
    }

    public function disable(): void {
        update_option('wnb_maintenance_enabled', false);
        if (class_exists('WebNewBiz_Security')) {
            WebNewBiz_Security::instance()->log_activity('maintenance_disabled', 'Maintenance mode turned OFF');
        }
    }

    /**
     * Should the maintenance page be shown to the current visitor?
     */
    public function should_show(): bool {
        if (!$this->is_enabled()) {
            return false;
        }

        $settings = $this->get_settings();

        // Allow logged-in admins to bypass
        if (!empty($settings['allow_admins']) && is_user_logged_in() && current_user_can('manage_options')) {
            return false;
        }

        // Never block wp-login.php, wp-admin AJAX, cron, or REST auth endpoints
        if (
            (defined('DOING_AJAX') && DOING_AJAX) ||
            (defined('DOING_CRON') && DOING_CRON) ||
            (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], 'wp-login.php'))
        ) {
            return false;
        }

        return true;
    }

    // ================================================================
    //  Settings
    // ================================================================

    public function get_settings(): array {
        $saved = get_option('wnb_maintenance_settings', []);
        if (!is_array($saved)) {
            $saved = [];
        }
        $settings            = array_merge($this->defaults, $saved);
        $settings['enabled'] = $this->is_enabled();
        return $settings;
    }

    public function save_settings(array $data): void {
        $clean = [];

        if (isset($data['message'])) {
            $clean['message'] = wp_kses_post($data['message']);
        }
        if (isset($data['back_date'])) {
            $clean['back_date'] = sanitize_text_field($data['back_date']);
        }
        if (isset($data['bg_color'])) {
            $clean['bg_color'] = sanitize_hex_color($data['bg_color']) ?: $this->defaults['bg_color'];
        }
        if (isset($data['allow_admins'])) {
            $clean['allow_admins'] = (bool) $data['allow_admins'];
        }
        if (isset($data['custom_css'])) {
            $clean['custom_css'] = wp_strip_all_tags($data['custom_css']);
        }

        $existing = get_option('wnb_maintenance_settings', []);
        if (!is_array($existing)) {
            $existing = [];
        }

        update_option('wnb_maintenance_settings', array_merge($existing, $clean));
    }

    // ================================================================
    //  Render maintenance page
    // ================================================================

    /**
     * Hook into template_redirect; output the maintenance HTML and exit.
     */
    public function render_maintenance_page(): void {
        if (!$this->should_show()) {
            return;
        }

        $settings = $this->get_settings();

        // 503 Service Unavailable + Retry-After (1 hour or until back_date)
        $retry = 3600;
        if (!empty($settings['back_date'])) {
            $back_ts = strtotime($settings['back_date']);
            if ($back_ts && $back_ts > time()) {
                $retry = $back_ts - time();
            }
        }

        status_header(503);
        header('Retry-After: ' . $retry);
        header('Content-Type: text/html; charset=utf-8');

        echo $this->get_maintenance_html($settings);
        exit;
    }

    /**
     * Block REST API for non-admins when maintenance is on.
     */
    public function rest_block($errors) {
        if ($this->is_enabled() && !current_user_can('manage_options')) {
            return new \WP_Error(
                'maintenance_mode',
                'Site is under maintenance. Please try again later.',
                ['status' => 503]
            );
        }
        return $errors;
    }

    /**
     * Show a notice in the admin bar when maintenance mode is active.
     */
    public function admin_bar_notice(\WP_Admin_Bar $bar): void {
        if (!$this->is_enabled() || !current_user_can('manage_options')) {
            return;
        }
        $bar->add_node([
            'id'    => 'wnb-maintenance-notice',
            'title' => '<span style="color:#fbbf24;font-weight:600;">&#9888; Maintenance Mode ON</span>',
            'href'  => admin_url('admin.php?page=webnewbiz-settings'),
            'meta'  => ['class' => 'wnb-maintenance-bar'],
        ]);
    }

    // ================================================================
    //  Maintenance HTML (self-contained)
    // ================================================================

    /**
     * Build the full HTML for the maintenance page.
     * No external dependencies — all CSS/JS is inline.
     */
    public function get_maintenance_html(array $settings = []): string {
        if (empty($settings)) {
            $settings = $this->get_settings();
        }

        $site_name   = esc_html(get_bloginfo('name'));
        $admin_email = esc_html(get_option('admin_email'));
        $message     = wp_kses_post($settings['message']);
        $bg_color    = esc_attr($settings['bg_color'] ?: '#0f172a');
        $custom_css  = $settings['custom_css'] ?? '';

        // Calculate lighter accent from bg_color for glow effects
        $back_date_js = '';
        if (!empty($settings['back_date'])) {
            $back_date_js = esc_attr($settings['back_date']);
        }

        $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>{$site_name} — Maintenance</title>
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background: linear-gradient(135deg, {$bg_color} 0%, #1e293b 50%, {$bg_color} 100%);
        color: #e2e8f0;
        line-height: 1.6;
        overflow: hidden;
    }

    /* Animated background orbs */
    .bg-orb {
        position: fixed;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.15;
        animation: float 20s ease-in-out infinite;
        pointer-events: none;
    }
    .bg-orb-1 { width: 400px; height: 400px; background: #6366f1; top: -100px; left: -100px; }
    .bg-orb-2 { width: 300px; height: 300px; background: #8b5cf6; bottom: -80px; right: -80px; animation-delay: -7s; }
    .bg-orb-3 { width: 200px; height: 200px; background: #3b82f6; top: 50%; left: 60%; animation-delay: -14s; }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25%      { transform: translate(30px, -30px) scale(1.05); }
        50%      { transform: translate(-20px, 20px) scale(0.95); }
        75%      { transform: translate(15px, 10px) scale(1.02); }
    }

    .container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 540px;
        margin: 24px;
        text-align: center;
    }

    .card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(148, 163, 184, 0.1);
        border-radius: 24px;
        padding: 48px 40px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    }

    /* Animated gear spinner */
    .spinner-wrap {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        margin-bottom: 28px;
    }
    .gear {
        width: 56px;
        height: 56px;
        animation: spin 4s linear infinite;
    }
    .gear path { fill: #6366f1; }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    h1 {
        font-size: 22px;
        font-weight: 700;
        color: #f8fafc;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }
    .site-name {
        font-size: 13px;
        color: #6366f1;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }
    .message {
        font-size: 15px;
        color: #94a3b8;
        margin-bottom: 32px;
        line-height: 1.7;
    }

    /* Countdown */
    .countdown-wrap {
        display: none;
        margin-bottom: 32px;
    }
    .countdown-wrap.active { display: block; }
    .countdown-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
    }
    .countdown {
        display: flex;
        justify-content: center;
        gap: 12px;
    }
    .cd-unit {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 12px;
        padding: 12px 16px;
        min-width: 64px;
    }
    .cd-val {
        font-size: 28px;
        font-weight: 700;
        color: #f8fafc;
        display: block;
        font-variant-numeric: tabular-nums;
    }
    .cd-lbl {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Progress bar */
    .progress-bar {
        width: 100%;
        height: 4px;
        background: rgba(99, 102, 241, 0.15);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .progress-bar-fill {
        height: 100%;
        width: 30%;
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
        border-radius: 2px;
        animation: progress 2s ease-in-out infinite;
    }
    @keyframes progress {
        0%   { width: 10%; margin-left: 0; }
        50%  { width: 40%; margin-left: 30%; }
        100% { width: 10%; margin-left: 90%; }
    }

    .contact {
        font-size: 13px;
        color: #475569;
    }
    .contact a {
        color: #6366f1;
        text-decoration: none;
    }
    .contact a:hover { text-decoration: underline; }

    .footer {
        margin-top: 24px;
        font-size: 12px;
        color: #334155;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .card { padding: 36px 24px; border-radius: 18px; }
        h1 { font-size: 19px; }
        .cd-unit { min-width: 52px; padding: 10px 8px; }
        .cd-val { font-size: 22px; }
    }

    {$custom_css}
</style>
</head>
<body>

<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>

<div class="container">
    <div class="card">
        <!-- Animated gear icon -->
        <div class="spinner-wrap">
            <svg class="gear" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97s-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65A.488.488 0 0 0 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1s.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.58 1.69-.98l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66z"/>
            </svg>
        </div>

        <div class="site-name">{$site_name}</div>
        <h1>We'll Be Back Soon</h1>

        <div class="progress-bar"><div class="progress-bar-fill"></div></div>

        <p class="message">{$message}</p>

        <div class="countdown-wrap" id="countdown-wrap">
            <div class="countdown-label">Estimated return</div>
            <div class="countdown" id="countdown">
                <div class="cd-unit"><span class="cd-val" id="cd-days">00</span><span class="cd-lbl">Days</span></div>
                <div class="cd-unit"><span class="cd-val" id="cd-hours">00</span><span class="cd-lbl">Hours</span></div>
                <div class="cd-unit"><span class="cd-val" id="cd-mins">00</span><span class="cd-lbl">Min</span></div>
                <div class="cd-unit"><span class="cd-val" id="cd-secs">00</span><span class="cd-lbl">Sec</span></div>
            </div>
        </div>

        <p class="contact">Questions? Email us at <a href="mailto:{$admin_email}">{$admin_email}</a></p>
    </div>

    <div class="footer">Powered by WebNewBiz</div>
</div>

<script>
(function() {
    var backDate = '{$back_date_js}';
    if (!backDate) return;

    var target = new Date(backDate).getTime();
    if (isNaN(target)) return;

    var wrap = document.getElementById('countdown-wrap');
    wrap.classList.add('active');

    function pad(n) { return n < 10 ? '0' + n : n; }

    function tick() {
        var now  = Date.now();
        var diff = Math.max(0, Math.floor((target - now) / 1000));

        var d = Math.floor(diff / 86400);
        var h = Math.floor((diff % 86400) / 3600);
        var m = Math.floor((diff % 3600) / 60);
        var s = diff % 60;

        document.getElementById('cd-days').textContent = pad(d);
        document.getElementById('cd-hours').textContent = pad(h);
        document.getElementById('cd-mins').textContent  = pad(m);
        document.getElementById('cd-secs').textContent  = pad(s);

        if (diff <= 0) {
            clearInterval(timer);
            location.reload();
        }
    }

    tick();
    var timer = setInterval(tick, 1000);
})();
</script>

</body>
</html>
HTML;

        return $html;
    }

    // ================================================================
    //  AJAX Handlers
    // ================================================================

    /**
     * Toggle maintenance mode on/off.
     * POST: nonce, enabled (0|1)
     */
    public function ajax_toggle_maintenance(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $enabled = !empty($_POST['enabled']);

        if ($enabled) {
            $this->enable();
        } else {
            $this->disable();
        }

        wp_send_json_success([
            'enabled'  => $this->is_enabled(),
            'settings' => $this->get_settings(),
        ]);
    }

    /**
     * Save all maintenance settings.
     * POST: nonce, message, back_date, bg_color, allow_admins, custom_css
     */
    public function ajax_save_maintenance_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $data = [
            'message'      => $_POST['message'] ?? '',
            'back_date'    => $_POST['back_date'] ?? '',
            'bg_color'     => $_POST['bg_color'] ?? '',
            'allow_admins' => $_POST['allow_admins'] ?? true,
            'custom_css'   => $_POST['custom_css'] ?? '',
        ];

        $this->save_settings($data);

        // Also toggle enabled state if provided
        if (isset($_POST['enabled'])) {
            if (!empty($_POST['enabled'])) {
                $this->enable();
            } else {
                $this->disable();
            }
        }

        wp_send_json_success([
            'settings' => $this->get_settings(),
            'message'  => 'Maintenance settings saved.',
        ]);
    }
}
