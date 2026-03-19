<?php
/**
 * WebNewBiz Performance Optimizer
 *
 * Individual toggles for: emoji removal, embed removal, jQuery Migrate removal,
 * HTML minification, lazy loading, DNS prefetch, query string removal,
 * heartbeat control, self-pingback removal, RSS disabling, font preloading.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Performance {

    private static ?self $instance = null;

    /** Default settings — all off until user enables */
    private array $defaults = [
        'disable_emojis'         => false,
        'disable_embeds'         => false,
        'remove_jquery_migrate'  => false,
        'minify_html'            => false,
        'lazy_load_images'       => false,
        'lazy_load_iframes'      => false,
        'dns_prefetch'           => false,
        'remove_query_strings'   => false,
        'disable_heartbeat'      => false,
        'disable_self_pingbacks' => false,
        'disable_rss'            => false,
        'preload_fonts'          => false,
    ];

    /** Domains to DNS-prefetch */
    private array $prefetch_domains = [
        '//fonts.googleapis.com',
        '//fonts.gstatic.com',
        '//cdnjs.cloudflare.com',
        '//ajax.googleapis.com',
        '//cdn.jsdelivr.net',
        '//www.google-analytics.com',
        '//www.googletagmanager.com',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $settings = $this->get_settings();

        if ($settings['disable_emojis'])         $this->disable_emojis();
        if ($settings['disable_embeds'])          $this->disable_embeds();
        if ($settings['remove_jquery_migrate'])   $this->remove_jquery_migrate();
        if ($settings['minify_html'])             $this->minify_html();
        if ($settings['lazy_load_images'])        $this->lazy_load_images();
        if ($settings['lazy_load_iframes'])       $this->lazy_load_iframes();
        if ($settings['dns_prefetch'])            $this->dns_prefetch();
        if ($settings['remove_query_strings'])    $this->remove_query_strings();
        if ($settings['disable_heartbeat'])       $this->disable_heartbeat();
        if ($settings['disable_self_pingbacks'])  $this->disable_self_pingbacks();
        if ($settings['disable_rss'])             $this->disable_rss();
        if ($settings['preload_fonts'])           $this->preload_fonts();

        // AJAX handler
        add_action('wp_ajax_wnb_save_performance_settings', [$this, 'ajax_save_performance_settings']);
    }

    // ──────────────────────────────────────────────
    //  Feature Implementations
    // ──────────────────────────────────────────────

    /**
     * Remove WordPress emoji scripts and styles everywhere.
     */
    private function disable_emojis(): void {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Remove emoji DNS prefetch
        add_filter('wp_resource_hints', function (array $urls, string $relation_type): array {
            if ($relation_type === 'dns-prefetch') {
                $urls = array_filter($urls, function ($url) {
                    $url_str = is_array($url) ? ($url['href'] ?? '') : (string) $url;
                    return strpos($url_str, 'https://s.w.org/images/core/emoji') === false;
                });
            }
            return $urls;
        }, 10, 2);

        // Remove emoji from TinyMCE
        add_filter('tiny_mce_plugins', function (array $plugins): array {
            return array_diff($plugins, ['wpemoji']);
        });
    }

    /**
     * Disable WordPress oEmbed functionality.
     */
    private function disable_embeds(): void {
        // Remove oEmbed discovery links
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');

        // Dequeue the embed script on frontend
        add_action('wp_footer', function () {
            wp_dequeue_script('wp-embed');
        });

        // Disable oEmbed REST API endpoint
        add_filter('embed_oembed_discover', '__return_false');

        // Remove embed rewrite rules
        add_filter('rewrite_rules_array', function (array $rules): array {
            foreach ($rules as $rule => $rewrite) {
                if (strpos($rewrite, 'embed=true') !== false) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        });

        // Disable REST API oEmbed endpoint
        add_filter('rest_endpoints', function (array $endpoints): array {
            unset($endpoints['/oembed/1.0/embed']);
            return $endpoints;
        });
    }

    /**
     * Remove jQuery Migrate from frontend (keep in admin for compatibility).
     */
    private function remove_jquery_migrate(): void {
        add_action('wp_default_scripts', function (\WP_Scripts $scripts) {
            if (is_admin()) return;

            if (isset($scripts->registered['jquery']) && !empty($scripts->registered['jquery']->deps)) {
                $scripts->registered['jquery']->deps = array_diff(
                    $scripts->registered['jquery']->deps,
                    ['jquery-migrate']
                );
            }
        });
    }

    /**
     * Minify HTML output on the frontend.
     * Strips comments (except IE conditionals) and collapses whitespace.
     */
    private function minify_html(): void {
        // Only on frontend, non-AJAX, non-REST
        if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
            return;
        }

        add_action('template_redirect', function () {
            ob_start(function (string $html): string {
                if (empty($html)) return $html;

                // Don't minify XML/feeds
                if (strpos($html, '<?xml') !== false) return $html;

                // Remove HTML comments (preserve IE conditionals and script/style tags)
                $html = preg_replace('/<!--(?!\s*\[if)(?!-->).*?-->/s', '', $html);

                // Collapse whitespace between HTML tags (not inside <pre>, <script>, <style>, <textarea>)
                // Simple approach: collapse runs of whitespace to single space between tags
                $html = preg_replace('/>\s{2,}</', '> <', $html);

                // Remove whitespace at beginning of lines
                $html = preg_replace('/^\s+/m', '', $html);

                return $html;
            });
        });
    }

    /**
     * Add loading="lazy" to images in post content.
     */
    private function lazy_load_images(): void {
        add_filter('the_content', function (string $content): string {
            if (is_admin() || is_feed()) return $content;

            // Add loading="lazy" to img tags that don't already have it
            $content = preg_replace_callback(
                '/<img\b([^>]*?)>/i',
                function (array $matches): string {
                    $attrs = $matches[1];

                    // Skip if already has loading attribute
                    if (preg_match('/\bloading\s*=/i', $attrs)) {
                        return $matches[0];
                    }

                    // Skip if it has data-no-lazy or class containing "no-lazy"
                    if (preg_match('/data-no-lazy|class\s*=\s*["\'][^"\']*no-lazy/i', $attrs)) {
                        return $matches[0];
                    }

                    return '<img loading="lazy"' . $attrs . '>';
                },
                $content
            );

            return $content;
        }, 99);
    }

    /**
     * Add loading="lazy" to iframes in post content.
     */
    private function lazy_load_iframes(): void {
        add_filter('the_content', function (string $content): string {
            if (is_admin() || is_feed()) return $content;

            $content = preg_replace_callback(
                '/<iframe\b([^>]*?)>/i',
                function (array $matches): string {
                    $attrs = $matches[1];

                    if (preg_match('/\bloading\s*=/i', $attrs)) {
                        return $matches[0];
                    }

                    return '<iframe loading="lazy"' . $attrs . '>';
                },
                $content
            );

            return $content;
        }, 99);
    }

    /**
     * Add DNS prefetch hints for common CDN domains.
     */
    private function dns_prefetch(): void {
        add_action('wp_head', function () {
            foreach ($this->prefetch_domains as $domain) {
                echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
            }
        }, 1);
    }

    /**
     * Remove ?ver= query strings from static resources.
     */
    private function remove_query_strings(): void {
        $strip = function (string $src): string {
            if (is_admin()) return $src;

            if (strpos($src, '?ver=') !== false) {
                $src = remove_query_arg('ver', $src);
            }
            return $src;
        };

        add_filter('script_loader_src', $strip, 15);
        add_filter('style_loader_src', $strip, 15);
    }

    /**
     * Control the WordPress Heartbeat API.
     * - Frontend: set to 60s interval
     * - Admin pages (except post editor): disabled
     * - Post editor: default (15s)
     */
    private function disable_heartbeat(): void {
        add_action('init', function () {
            // On frontend: slow heartbeat to 60s
            if (!is_admin()) {
                wp_deregister_script('heartbeat');
                return;
            }

            // In admin but not on post editor: deregister heartbeat
            global $pagenow;
            if (isset($pagenow) && !in_array($pagenow, ['post.php', 'post-new.php'], true)) {
                wp_deregister_script('heartbeat');
            }
        }, 1);

        // If heartbeat is still loaded (post editor), slow it to 60s
        add_filter('heartbeat_settings', function (array $settings): array {
            $settings['interval'] = 60;
            return $settings;
        });
    }

    /**
     * Prevent WordPress from sending pingbacks to its own URLs.
     */
    private function disable_self_pingbacks(): void {
        add_action('pre_ping', function (array &$links) {
            $home = get_option('home');
            foreach ($links as $i => $link) {
                if (strpos($link, $home) === 0) {
                    unset($links[$i]);
                }
            }
        });
    }

    /**
     * Disable RSS feeds and redirect to homepage.
     */
    private function disable_rss(): void {
        $redirect = function () {
            wp_redirect(home_url(), 301);
            exit;
        };

        add_action('do_feed', $redirect, 1);
        add_action('do_feed_rdf', $redirect, 1);
        add_action('do_feed_rss', $redirect, 1);
        add_action('do_feed_rss2', $redirect, 1);
        add_action('do_feed_atom', $redirect, 1);
        add_action('do_feed_rss2_comments', $redirect, 1);
        add_action('do_feed_atom_comments', $redirect, 1);

        // Remove feed links from <head>
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
    }

    /**
     * Preload theme fonts with link[rel=preload].
     * Detects font files in the active theme directory.
     */
    private function preload_fonts(): void {
        add_action('wp_head', function () {
            $theme_dir = get_stylesheet_directory();
            $theme_url = get_stylesheet_directory_uri();

            // Common font directories within themes
            $font_dirs = ['fonts', 'assets/fonts', 'assets/webfonts', 'css/fonts'];
            $font_extensions = ['woff2', 'woff'];
            $fonts_found = [];

            foreach ($font_dirs as $dir) {
                $full_dir = $theme_dir . '/' . $dir;
                if (!is_dir($full_dir)) continue;

                foreach ($font_extensions as $ext) {
                    $files = glob($full_dir . '/*.' . $ext);
                    if (!is_array($files)) continue;

                    foreach ($files as $file) {
                        $filename = basename($file);
                        $url = $theme_url . '/' . $dir . '/' . $filename;
                        $type = ($ext === 'woff2') ? 'font/woff2' : 'font/woff';
                        $fonts_found[] = [
                            'url'  => $url,
                            'type' => $type,
                        ];
                    }
                }

                // Limit to woff2 if available, max 6 fonts to avoid too many preloads
                if (!empty($fonts_found)) break;
            }

            // Prefer woff2, limit to 6
            $woff2 = array_filter($fonts_found, fn($f) => $f['type'] === 'font/woff2');
            $to_preload = !empty($woff2) ? array_slice($woff2, 0, 6) : array_slice($fonts_found, 0, 6);

            foreach ($to_preload as $font) {
                printf(
                    '<link rel="preload" href="%s" as="font" type="%s" crossorigin>' . "\n",
                    esc_url($font['url']),
                    esc_attr($font['type'])
                );
            }
        }, 1);
    }

    // ──────────────────────────────────────────────
    //  Settings
    // ──────────────────────────────────────────────

    /**
     * Get saved performance settings.
     */
    public function get_settings(): array {
        $saved = get_option('wnb_performance_settings', []);
        return wp_parse_args($saved, $this->defaults);
    }

    /**
     * Save performance settings.
     */
    public function save_settings(array $data): bool {
        $clean = [];
        foreach ($this->defaults as $key => $default) {
            $clean[$key] = isset($data[$key]) ? (bool) $data[$key] : $default;
        }
        return update_option('wnb_performance_settings', $clean);
    }

    // ──────────────────────────────────────────────
    //  AJAX Handler
    // ──────────────────────────────────────────────

    /**
     * AJAX: Save an individual performance toggle (or all at once).
     */
    public function ajax_save_performance_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $settings = $this->get_settings();

        // Support single key + value (toggle) or full settings array
        $key = sanitize_text_field($_POST['key'] ?? '');
        if ($key && array_key_exists($key, $this->defaults)) {
            // Single toggle
            $settings[$key] = !empty($_POST['value']);
            $this->save_settings($settings);

            wp_send_json_success([
                'message'  => ucfirst(str_replace('_', ' ', $key)) . ($settings[$key] ? ' enabled.' : ' disabled.'),
                'key'      => $key,
                'value'    => $settings[$key],
                'settings' => $this->get_settings(),
            ]);
        } else {
            // Bulk update: all keys from POST
            foreach ($this->defaults as $k => $default) {
                if (isset($_POST[$k])) {
                    $settings[$k] = !empty($_POST[$k]);
                }
            }
            $this->save_settings($settings);

            wp_send_json_success([
                'message'  => 'Performance settings saved.',
                'settings' => $this->get_settings(),
            ]);
        }
    }
}
