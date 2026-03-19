<?php
/**
 * WebNewBiz White Label
 *
 * Customise the WordPress login page, admin footer, admin bar logo,
 * and add a custom dashboard widget — all controlled from settings.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_WhiteLabel {

    private static ?self $instance = null;

    /** Default white-label settings */
    private array $defaults = [
        'login_logo_url'         => '',
        'login_bg_color'         => '#0f172a',
        'login_bg_image'         => '',
        'login_btn_color'        => '#6366f1',
        'admin_footer_text'      => '',
        'hide_wp_logo'           => false,
        'custom_widget_enabled'  => false,
        'custom_widget_title'    => 'Welcome',
        'custom_widget_content'  => '',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $settings = $this->get_settings();

        // ── Login page customisation ───────────────────────────────
        add_action('login_enqueue_scripts', [$this, 'custom_login_logo']);
        add_action('login_enqueue_scripts', [$this, 'custom_login_page_style']);
        add_filter('login_headerurl', [$this, 'custom_login_url']);
        add_filter('login_headertext', [$this, 'custom_login_title']);

        // ── Admin footer text ──────────────────────────────────────
        if (!empty($settings['admin_footer_text'])) {
            add_filter('admin_footer_text', [$this, 'custom_admin_footer']);
        }

        // ── Hide WP logo from admin bar ────────────────────────────
        if (!empty($settings['hide_wp_logo'])) {
            add_action('admin_head', [$this, 'hide_wp_admin_bar_logo']);
            add_action('wp_head', [$this, 'hide_wp_admin_bar_logo']);
        }

        // ── Custom dashboard widget ────────────────────────────────
        if (!empty($settings['custom_widget_enabled'])) {
            add_action('wp_dashboard_setup', [$this, 'custom_dashboard_widget']);
        }

        // ── AJAX handlers ──────────────────────────────────────────
        add_action('wp_ajax_wnb_save_whitelabel_settings', [$this, 'ajax_save_whitelabel_settings']);
    }

    // ================================================================
    //  Settings
    // ================================================================

    public function get_settings(): array {
        $saved = get_option('wnb_whitelabel_settings', []);
        if (!is_array($saved)) {
            $saved = [];
        }
        return array_merge($this->defaults, $saved);
    }

    public function save_settings(array $data): void {
        $clean = [];

        if (isset($data['login_logo_url'])) {
            $clean['login_logo_url'] = esc_url_raw($data['login_logo_url']);
        }
        if (isset($data['login_bg_color'])) {
            $clean['login_bg_color'] = sanitize_hex_color($data['login_bg_color']) ?: $this->defaults['login_bg_color'];
        }
        if (isset($data['login_bg_image'])) {
            $clean['login_bg_image'] = esc_url_raw($data['login_bg_image']);
        }
        if (isset($data['login_btn_color'])) {
            $clean['login_btn_color'] = sanitize_hex_color($data['login_btn_color']) ?: $this->defaults['login_btn_color'];
        }
        if (isset($data['admin_footer_text'])) {
            $clean['admin_footer_text'] = wp_kses_post($data['admin_footer_text']);
        }
        if (isset($data['hide_wp_logo'])) {
            $clean['hide_wp_logo'] = (bool) $data['hide_wp_logo'];
        }
        if (isset($data['custom_widget_enabled'])) {
            $clean['custom_widget_enabled'] = (bool) $data['custom_widget_enabled'];
        }
        if (isset($data['custom_widget_title'])) {
            $clean['custom_widget_title'] = sanitize_text_field($data['custom_widget_title']);
        }
        if (isset($data['custom_widget_content'])) {
            $clean['custom_widget_content'] = wp_kses_post($data['custom_widget_content']);
        }

        $existing = get_option('wnb_whitelabel_settings', []);
        if (!is_array($existing)) {
            $existing = [];
        }

        update_option('wnb_whitelabel_settings', array_merge($existing, $clean));
    }

    // ================================================================
    //  Login Logo
    // ================================================================

    /**
     * Replace the default WordPress login logo with a custom one.
     * Hooked to login_enqueue_scripts.
     */
    public function custom_login_logo(): void {
        $settings = $this->get_settings();
        $logo_url = $settings['login_logo_url'];

        if (empty($logo_url)) {
            // Use the plugin's logo as default if available
            $plugin_logo = WEBNEWBIZ_PLUGIN_URL . 'assets/images/logo.png';
            $logo_url    = $plugin_logo;
        }

        $logo_url_escaped = esc_url($logo_url);

        ?>
        <style>
            #login h1 a,
            .login h1 a {
                background-image: url('<?php echo $logo_url_escaped; ?>');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                width: 100%;
                max-width: 260px;
                height: 80px;
                margin: 0 auto 24px;
            }
        </style>
        <?php
    }

    // ================================================================
    //  Login URL & Title
    // ================================================================

    /**
     * Change the logo link to point to the site URL instead of wordpress.org.
     */
    public function custom_login_url(): string {
        return esc_url(home_url('/'));
    }

    /**
     * Change the logo title text to the site name.
     */
    public function custom_login_title(): string {
        return esc_html(get_bloginfo('name'));
    }

    // ================================================================
    //  Full Login Page Styling
    // ================================================================

    /**
     * Apply comprehensive login page styling.
     * Hooked to login_enqueue_scripts.
     */
    public function custom_login_page_style(): void {
        $settings  = $this->get_settings();
        $bg_color  = esc_attr($settings['login_bg_color'] ?: '#0f172a');
        $bg_image  = esc_url($settings['login_bg_image']);
        $btn_color = esc_attr($settings['login_btn_color'] ?: '#6366f1');

        // Compute a slightly darker shade for button hover
        $btn_hover = $this->adjust_brightness($btn_color, -20);

        ?>
        <style>
            /* ── Page background ─────────────────────────── */
            body.login {
                background-color: <?php echo $bg_color; ?>;
                <?php if ($bg_image): ?>
                background-image: url('<?php echo $bg_image; ?>');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                <?php else: ?>
                background-image: linear-gradient(135deg, <?php echo $bg_color; ?> 0%, #1e293b 100%);
                <?php endif; ?>
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            /* ── Form card ───────────────────────────────── */
            #loginform,
            #registerform,
            #lostpasswordform {
                background: rgba(255, 255, 255, 0.97);
                border: none;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.25);
                padding: 28px 24px;
            }

            .login #login {
                padding: 0;
                width: 360px;
                max-width: 92vw;
            }

            /* ── Input fields ────────────────────────────── */
            #loginform input[type="text"],
            #loginform input[type="password"],
            #loginform input[type="email"],
            #registerform input[type="text"],
            #registerform input[type="email"],
            #lostpasswordform input[type="text"] {
                border: 2px solid #e2e8f0;
                border-radius: 10px;
                padding: 10px 14px;
                font-size: 14px;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
                background: #f8fafc;
            }
            #loginform input:focus,
            #registerform input:focus,
            #lostpasswordform input:focus {
                border-color: <?php echo $btn_color; ?>;
                box-shadow: 0 0 0 3px <?php echo $btn_color; ?>33;
                outline: none;
                background: #fff;
            }

            /* ── Labels ──────────────────────────────────── */
            #loginform label,
            #registerform label,
            #lostpasswordform label {
                font-size: 13px;
                font-weight: 600;
                color: #334155;
            }

            /* ── Submit button ───────────────────────────── */
            #wp-submit,
            .login .button-primary {
                background: <?php echo $btn_color; ?> !important;
                border: none !important;
                border-radius: 10px !important;
                padding: 10px 28px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                text-shadow: none !important;
                box-shadow: 0 4px 14px <?php echo $btn_color; ?>44 !important;
                transition: all 0.2s ease !important;
                width: 100%;
                height: auto !important;
            }
            #wp-submit:hover,
            .login .button-primary:hover {
                background: <?php echo $btn_hover; ?> !important;
                box-shadow: 0 6px 20px <?php echo $btn_color; ?>66 !important;
                transform: translateY(-1px);
            }
            #wp-submit:active,
            .login .button-primary:active {
                transform: translateY(0);
            }

            /* ── Navigation links ────────────────────────── */
            .login #nav,
            .login #backtoblog {
                text-align: center;
            }
            .login #nav a,
            .login #backtoblog a {
                color: rgba(255,255,255,0.7);
                text-decoration: none;
                font-size: 13px;
                transition: color 0.2s;
            }
            .login #nav a:hover,
            .login #backtoblog a:hover {
                color: #fff;
            }

            /* ── Error/message boxes ─────────────────────── */
            .login .message,
            .login .success,
            .login #login_error {
                border-radius: 10px;
                border-left-width: 4px;
                font-size: 13px;
            }
            .login #login_error {
                border-left-color: #ef4444;
            }

            /* ── Remember me checkbox ────────────────────── */
            .login .forgetmenot label {
                font-size: 12px;
                color: #64748b;
            }

            /* ── Privacy policy link ─────────────────────── */
            .login .privacy-policy-page-link {
                text-align: center;
            }
            .login .privacy-policy-page-link a {
                color: rgba(255,255,255,0.5);
                font-size: 12px;
            }

            /* ── Language switcher (WP 5.9+) ─────────────── */
            .language-switcher {
                text-align: center;
            }
            .language-switcher label {
                color: rgba(255,255,255,0.7) !important;
            }
        </style>
        <?php
    }

    // ================================================================
    //  Admin Footer Text
    // ================================================================

    /**
     * Replace the "Thank you for creating with WordPress" footer text.
     */
    public function custom_admin_footer(): string {
        $settings = $this->get_settings();
        $text     = $settings['admin_footer_text'];

        if (!empty($text)) {
            return wp_kses_post($text);
        }

        $site_name = esc_html(get_bloginfo('name'));
        return "Powered by <strong>WebNewBiz</strong> &mdash; {$site_name}";
    }

    // ================================================================
    //  Hide WP Logo from Admin Bar
    // ================================================================

    /**
     * Inject CSS to hide the WordPress logo in the admin bar.
     */
    public function hide_wp_admin_bar_logo(): void {
        ?>
        <style>
            #wpadminbar #wp-admin-bar-wp-logo { display: none !important; }
        </style>
        <?php
    }

    // ================================================================
    //  Custom Dashboard Widget
    // ================================================================

    /**
     * Register a custom dashboard widget.
     */
    public function custom_dashboard_widget(): void {
        $settings = $this->get_settings();
        $title    = $settings['custom_widget_title'] ?: 'Welcome';

        wp_add_dashboard_widget(
            'wnb_whitelabel_widget',
            esc_html($title),
            [$this, 'render_dashboard_widget']
        );

        // Move to the top of the dashboard
        global $wp_meta_boxes;
        $dashboard = $wp_meta_boxes['dashboard']['normal']['core'] ?? [];
        if (isset($dashboard['wnb_whitelabel_widget'])) {
            $widget = ['wnb_whitelabel_widget' => $dashboard['wnb_whitelabel_widget']];
            unset($dashboard['wnb_whitelabel_widget']);
            $wp_meta_boxes['dashboard']['normal']['core'] = array_merge($widget, $dashboard);
        }
    }

    /**
     * Render the custom dashboard widget content.
     */
    public function render_dashboard_widget(): void {
        $settings = $this->get_settings();
        $content  = $settings['custom_widget_content'];

        if (!empty($content)) {
            echo '<div class="wnb-wl-widget">' . wp_kses_post($content) . '</div>';
        } else {
            $site_name = esc_html(get_bloginfo('name'));
            echo '<div class="wnb-wl-widget">';
            echo '<p>Welcome to <strong>' . $site_name . '</strong>! Use the navigation menu on the left to manage your website content, plugins, and settings.</p>';
            echo '</div>';
        }

        // Minimal styling for the widget
        ?>
        <style>
            .wnb-wl-widget {
                font-size: 13px;
                line-height: 1.7;
                color: #334155;
            }
            .wnb-wl-widget p { margin: 0 0 12px; }
            .wnb-wl-widget p:last-child { margin-bottom: 0; }
            .wnb-wl-widget a { color: #6366f1; }
            .wnb-wl-widget a:hover { color: #4f46e5; }
        </style>
        <?php
    }

    // ================================================================
    //  Helpers
    // ================================================================

    /**
     * Adjust the brightness of a hex colour.
     *
     * @param string $hex   Hex colour (#RRGGBB)
     * @param int    $steps Positive = lighter, negative = darker (-255 to 255)
     * @return string Adjusted hex colour
     */
    private function adjust_brightness(string $hex, int $steps): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) !== 6) {
            return '#' . $hex;
        }

        $r = max(0, min(255, hexdec(substr($hex, 0, 2)) + $steps));
        $g = max(0, min(255, hexdec(substr($hex, 2, 2)) + $steps));
        $b = max(0, min(255, hexdec(substr($hex, 4, 2)) + $steps));

        return '#' . str_pad(dechex((int) $r), 2, '0', STR_PAD_LEFT)
                    . str_pad(dechex((int) $g), 2, '0', STR_PAD_LEFT)
                    . str_pad(dechex((int) $b), 2, '0', STR_PAD_LEFT);
    }

    // ================================================================
    //  AJAX Handlers
    // ================================================================

    /**
     * Save all white-label settings.
     * POST: nonce, + all setting fields
     */
    public function ajax_save_whitelabel_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $data = [];
        $fields = [
            'login_logo_url',
            'login_bg_color',
            'login_bg_image',
            'login_btn_color',
            'admin_footer_text',
            'hide_wp_logo',
            'custom_widget_enabled',
            'custom_widget_title',
            'custom_widget_content',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $data[$field] = $_POST[$field];
            }
        }

        $this->save_settings($data);

        if (class_exists('WebNewBiz_Security')) {
            WebNewBiz_Security::instance()->log_activity('whitelabel_updated', 'White-label settings updated');
        }

        wp_send_json_success([
            'settings' => $this->get_settings(),
            'message'  => 'White-label settings saved.',
        ]);
    }
}
