<?php
/**
 * WebNewBiz SEO Toolkit
 *
 * Full SEO management: meta tags, Open Graph, Twitter Cards, sitemaps,
 * robots.txt, 301 redirects, JSON-LD schema, and per-post meta boxes.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_SEO {

    private static ?self $instance = null;

    /** Default settings */
    private array $defaults = [
        'organization_name' => '',
        'organization_logo' => '',
        'phone'             => '',
        'address'           => '',
        'schema_enabled'    => true,
        'sitemap_enabled'   => true,
        'og_enabled'        => true,
        'custom_robots'     => '',
    ];

    /** Meta field keys stored in postmeta */
    private const META_KEYS = [
        'wnb_seo_title',
        'wnb_meta_description',
        'wnb_focus_keyword',
        'wnb_og_image',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Frontend meta tags
        add_action('wp_head', [$this, 'add_meta_tags'], 1);

        // JSON-LD schema
        add_action('wp_head', [$this, 'output_schema_markup'], 2);

        // Admin meta box
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_meta'], 10, 2);

        // Sitemap rewrite
        add_action('init', [$this, 'register_sitemap_rewrite']);
        add_action('template_redirect', [$this, 'serve_sitemap']);

        // 301 redirects
        add_action('template_redirect', [$this, 'handle_redirects'], 1);

        // Robots.txt filter
        add_filter('robots_txt', [$this, 'robots_txt'], 10, 2);

        // AJAX handlers
        add_action('wp_ajax_wnb_save_seo_settings', [$this, 'ajax_save_seo_settings']);
        add_action('wp_ajax_wnb_generate_sitemap', [$this, 'ajax_generate_sitemap']);
        add_action('wp_ajax_wnb_save_redirect', [$this, 'ajax_save_redirect']);
        add_action('wp_ajax_wnb_delete_redirect', [$this, 'ajax_delete_redirect']);
        add_action('wp_ajax_wnb_save_robots', [$this, 'ajax_save_robots']);
    }

    // ──────────────────────────────────────────────
    //  Frontend Meta Tags
    // ──────────────────────────────────────────────

    /**
     * Output SEO meta tags in wp_head (singular pages only, not admin).
     */
    public function add_meta_tags(): void {
        if (is_admin() || !is_singular()) {
            return;
        }

        $post_id = get_the_ID();
        if (!$post_id) return;

        $settings = $this->get_settings();

        // Meta description
        $description = $this->get_meta_description($post_id);
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
        }

        // Meta keywords
        $keywords = get_post_meta($post_id, 'wnb_focus_keyword', true);
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '" />' . "\n";
        }

        // Canonical URL
        $canonical = get_permalink($post_id);
        if ($canonical) {
            echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
        }

        // Open Graph tags
        if ($settings['og_enabled']) {
            $og_title = get_post_meta($post_id, 'wnb_seo_title', true) ?: get_the_title($post_id);
            $og_image = get_post_meta($post_id, 'wnb_og_image', true);

            if (!$og_image) {
                $og_image = get_the_post_thumbnail_url($post_id, 'large');
            }

            $og_type = (get_post_type($post_id) === 'post') ? 'article' : 'website';

            echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($description ?: '') . '" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url($canonical) . '" />' . "\n";
            echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";

            if ($og_image) {
                echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
            }

            // Twitter Card tags
            echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr($description ?: '') . '" />' . "\n";

            if ($og_image) {
                echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
            }
        }
    }

    /**
     * Get meta description for a post: custom > excerpt > auto-generated from content.
     */
    private function get_meta_description(int $post_id): string {
        // Custom meta description
        $desc = get_post_meta($post_id, 'wnb_meta_description', true);
        if ($desc) return $desc;

        // Excerpt
        $post = get_post($post_id);
        if (!$post) return '';

        if ($post->post_excerpt) {
            return wp_trim_words(wp_strip_all_tags($post->post_excerpt), 25, '...');
        }

        // Auto-generate from content
        $content = wp_strip_all_tags(strip_shortcodes($post->post_content));
        if ($content) {
            return wp_trim_words($content, 25, '...');
        }

        return '';
    }

    // ──────────────────────────────────────────────
    //  Admin Meta Box
    // ──────────────────────────────────────────────

    /**
     * Register SEO meta box on post/page edit screens.
     */
    public function add_meta_box(): void {
        $post_types = get_post_types(['public' => true], 'names');

        foreach ($post_types as $post_type) {
            add_meta_box(
                'wnb_seo_meta_box',
                'WebNewBiz SEO',
                [$this, 'render_meta_box'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render the SEO meta box HTML with live preview and character counters.
     */
    public function render_meta_box(\WP_Post $post): void {
        wp_nonce_field('wnb_seo_meta_box', 'wnb_seo_nonce');

        $seo_title    = get_post_meta($post->ID, 'wnb_seo_title', true);
        $meta_desc    = get_post_meta($post->ID, 'wnb_meta_description', true);
        $focus_kw     = get_post_meta($post->ID, 'wnb_focus_keyword', true);
        $og_image     = get_post_meta($post->ID, 'wnb_og_image', true);
        $permalink    = get_permalink($post->ID);
        $display_url  = preg_replace('#^https?://#', '', $permalink);
        ?>
        <style>
            .wnb-seo-box { padding: 12px 0; }
            .wnb-seo-field { margin-bottom: 16px; }
            .wnb-seo-field label { display: block; font-weight: 600; margin-bottom: 4px; font-size: 13px; color: #1e293b; }
            .wnb-seo-field input[type="text"],
            .wnb-seo-field textarea { width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
            .wnb-seo-field textarea { resize: vertical; min-height: 60px; }
            .wnb-seo-counter { font-size: 12px; color: #94a3b8; margin-top: 2px; text-align: right; }
            .wnb-seo-counter.wnb-over { color: #ef4444; font-weight: 600; }
            .wnb-seo-preview { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-top: 16px; }
            .wnb-seo-preview-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
            .wnb-seo-preview-title { font-size: 18px; color: #1a0dab; line-height: 1.3; margin-bottom: 2px; cursor: pointer; font-family: arial, sans-serif; }
            .wnb-seo-preview-title:hover { text-decoration: underline; }
            .wnb-seo-preview-url { font-size: 14px; color: #006621; line-height: 1.4; margin-bottom: 2px; font-family: arial, sans-serif; }
            .wnb-seo-preview-desc { font-size: 13px; color: #545454; line-height: 1.5; font-family: arial, sans-serif; }
            .wnb-seo-og-preview { display: flex; align-items: center; gap: 10px; margin-top: 6px; }
            .wnb-seo-og-preview img { width: 60px; height: 60px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0; }
            .wnb-seo-og-btn { display: inline-block; padding: 6px 14px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; cursor: pointer; color: #334155; }
            .wnb-seo-og-btn:hover { background: #e2e8f0; }
        </style>

        <div class="wnb-seo-box">
            <!-- SEO Title -->
            <div class="wnb-seo-field">
                <label for="wnb_seo_title">SEO Title</label>
                <input type="text" id="wnb_seo_title" name="wnb_seo_title"
                       value="<?php echo esc_attr($seo_title); ?>"
                       placeholder="<?php echo esc_attr(get_the_title($post->ID)); ?>"
                       maxlength="70" />
                <div class="wnb-seo-counter" id="wnb-title-counter">
                    <span id="wnb-title-count"><?php echo strlen($seo_title); ?></span> / 60 characters
                </div>
            </div>

            <!-- Meta Description -->
            <div class="wnb-seo-field">
                <label for="wnb_meta_description">Meta Description</label>
                <textarea id="wnb_meta_description" name="wnb_meta_description"
                          placeholder="Enter a meta description for this page..."
                          maxlength="200" rows="3"><?php echo esc_textarea($meta_desc); ?></textarea>
                <div class="wnb-seo-counter" id="wnb-desc-counter">
                    <span id="wnb-desc-count"><?php echo strlen($meta_desc); ?></span> / 160 characters
                </div>
            </div>

            <!-- Focus Keyword -->
            <div class="wnb-seo-field">
                <label for="wnb_focus_keyword">Focus Keyword</label>
                <input type="text" id="wnb_focus_keyword" name="wnb_focus_keyword"
                       value="<?php echo esc_attr($focus_kw); ?>"
                       placeholder="e.g. best web design agency" />
            </div>

            <!-- OG Image -->
            <div class="wnb-seo-field">
                <label>Open Graph Image</label>
                <div class="wnb-seo-og-preview">
                    <?php if ($og_image): ?>
                        <img id="wnb-og-img-preview" src="<?php echo esc_url($og_image); ?>" alt="OG Image" />
                    <?php else: ?>
                        <img id="wnb-og-img-preview" src="" alt="OG Image" style="display:none" />
                    <?php endif; ?>
                    <span class="wnb-seo-og-btn" id="wnb-og-upload-btn">Choose Image</span>
                    <?php if ($og_image): ?>
                        <span class="wnb-seo-og-btn" id="wnb-og-remove-btn" style="color:#ef4444">Remove</span>
                    <?php else: ?>
                        <span class="wnb-seo-og-btn" id="wnb-og-remove-btn" style="color:#ef4444;display:none">Remove</span>
                    <?php endif; ?>
                </div>
                <input type="hidden" id="wnb_og_image" name="wnb_og_image" value="<?php echo esc_attr($og_image); ?>" />
            </div>

            <!-- Google Preview -->
            <div class="wnb-seo-preview">
                <div class="wnb-seo-preview-label">Search Engine Preview</div>
                <div class="wnb-seo-preview-title" id="wnb-preview-title">
                    <?php echo esc_html($seo_title ?: get_the_title($post->ID)); ?>
                </div>
                <div class="wnb-seo-preview-url" id="wnb-preview-url">
                    <?php echo esc_html($display_url); ?>
                </div>
                <div class="wnb-seo-preview-desc" id="wnb-preview-desc">
                    <?php echo esc_html($meta_desc ?: 'Add a meta description to control how this page appears in search results.'); ?>
                </div>
            </div>
        </div>

        <script>
        (function() {
            var titleInput = document.getElementById('wnb_seo_title');
            var descInput  = document.getElementById('wnb_meta_description');
            var titleCount = document.getElementById('wnb-title-count');
            var descCount  = document.getElementById('wnb-desc-count');
            var titleCounter = document.getElementById('wnb-title-counter');
            var descCounter  = document.getElementById('wnb-desc-counter');
            var previewTitle = document.getElementById('wnb-preview-title');
            var previewDesc  = document.getElementById('wnb-preview-desc');
            var postTitle = <?php echo wp_json_encode(get_the_title($post->ID)); ?>;

            function updateTitle() {
                var len = titleInput.value.length;
                titleCount.textContent = len;
                titleCounter.className = 'wnb-seo-counter' + (len > 60 ? ' wnb-over' : '');
                previewTitle.textContent = titleInput.value || postTitle;
            }

            function updateDesc() {
                var len = descInput.value.length;
                descCount.textContent = len;
                descCounter.className = 'wnb-seo-counter' + (len > 160 ? ' wnb-over' : '');
                previewDesc.textContent = descInput.value || 'Add a meta description to control how this page appears in search results.';
            }

            titleInput.addEventListener('input', updateTitle);
            descInput.addEventListener('input', updateDesc);

            // OG Image upload via WordPress Media Library
            var ogUploadBtn = document.getElementById('wnb-og-upload-btn');
            var ogRemoveBtn = document.getElementById('wnb-og-remove-btn');
            var ogInput     = document.getElementById('wnb_og_image');
            var ogPreview   = document.getElementById('wnb-og-img-preview');

            ogUploadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof wp === 'undefined' || typeof wp.media === 'undefined') return;

                var frame = wp.media({
                    title: 'Select OG Image',
                    button: { text: 'Use this image' },
                    multiple: false,
                    library: { type: 'image' }
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    ogInput.value = attachment.url;
                    ogPreview.src = attachment.url;
                    ogPreview.style.display = '';
                    ogRemoveBtn.style.display = '';
                });

                frame.open();
            });

            ogRemoveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                ogInput.value = '';
                ogPreview.style.display = 'none';
                ogRemoveBtn.style.display = 'none';
            });
        })();
        </script>
        <?php
    }

    /**
     * Save meta box fields to postmeta.
     */
    public function save_meta(int $post_id, \WP_Post $post): void {
        // Verify nonce
        if (!isset($_POST['wnb_seo_nonce']) || !wp_verify_nonce($_POST['wnb_seo_nonce'], 'wnb_seo_meta_box')) {
            return;
        }

        // Skip autosaves and revisions
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;

        // Check permissions
        $post_type = get_post_type_object($post->post_type);
        if (!$post_type || !current_user_can($post_type->cap->edit_post, $post_id)) {
            return;
        }

        // Sanitize and save each field
        if (isset($_POST['wnb_seo_title'])) {
            update_post_meta($post_id, 'wnb_seo_title', sanitize_text_field($_POST['wnb_seo_title']));
        }

        if (isset($_POST['wnb_meta_description'])) {
            update_post_meta($post_id, 'wnb_meta_description', sanitize_textarea_field($_POST['wnb_meta_description']));
        }

        if (isset($_POST['wnb_focus_keyword'])) {
            update_post_meta($post_id, 'wnb_focus_keyword', sanitize_text_field($_POST['wnb_focus_keyword']));
        }

        if (isset($_POST['wnb_og_image'])) {
            update_post_meta($post_id, 'wnb_og_image', esc_url_raw($_POST['wnb_og_image']));
        }
    }

    // ──────────────────────────────────────────────
    //  Sitemap
    // ──────────────────────────────────────────────

    /**
     * Register rewrite rule for sitemap.xml.
     */
    public function register_sitemap_rewrite(): void {
        $settings = $this->get_settings();
        if (!$settings['sitemap_enabled']) return;

        add_rewrite_rule('^sitemap\.xml$', 'index.php?wnb_sitemap=1', 'top');
        add_rewrite_rule('^sitemap-(\d+)\.xml$', 'index.php?wnb_sitemap=1&wnb_sitemap_page=$matches[1]', 'top');
        add_filter('query_vars', function (array $vars): array {
            $vars[] = 'wnb_sitemap';
            $vars[] = 'wnb_sitemap_page';
            return $vars;
        });
    }

    /**
     * Serve sitemap XML on template_redirect if the query var is set.
     */
    public function serve_sitemap(): void {
        if (!get_query_var('wnb_sitemap')) return;

        $page = (int) get_query_var('wnb_sitemap_page', 0);
        $xml  = $this->generate_sitemap($page);

        header('Content-Type: application/xml; charset=UTF-8');
        header('X-Robots-Tag: noindex');
        echo $xml;
        exit;
    }

    /**
     * Generate sitemap XML content.
     *
     * @param int $page If 0, generate the sitemap index; otherwise generate that page.
     * @return string XML content.
     */
    public function generate_sitemap(int $page = 0): string {
        $urls_per_page = 1000;

        // Get all public post types
        $post_types = get_post_types(['public' => true], 'names');
        unset($post_types['attachment']);

        // Count total published posts
        global $wpdb;
        $placeholders = implode(',', array_fill(0, count($post_types), '%s'));
        $total = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type IN ($placeholders) AND post_status = 'publish'",
                ...array_values($post_types)
            )
        );

        $total_pages = max(1, (int) ceil($total / $urls_per_page));

        // If multiple pages and page=0, return sitemap index
        if ($total_pages > 1 && $page === 0) {
            return $this->generate_sitemap_index($total_pages);
        }

        // Build single sitemap page
        $actual_page = ($page > 0) ? $page : 1;
        $offset = ($actual_page - 1) * $urls_per_page;

        $posts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_type, post_modified_gmt FROM {$wpdb->posts}
                 WHERE post_type IN ($placeholders) AND post_status = 'publish'
                 ORDER BY post_modified_gmt DESC
                 LIMIT %d OFFSET %d",
                ...array_merge(array_values($post_types), [$urls_per_page, $offset])
            )
        );

        $home_url = trailingslashit(home_url());
        $home_id  = (int) get_option('page_on_front');

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage (only on first page)
        if ($actual_page === 1) {
            $xml .= $this->sitemap_url_entry($home_url, gmdate('Y-m-d'), 'daily', '1.0');
        }

        foreach ($posts as $post) {
            // Skip if this is the homepage (already added)
            if ((int) $post->ID === $home_id && $actual_page === 1) {
                continue;
            }

            $url      = get_permalink($post->ID);
            $lastmod  = ($post->post_modified_gmt && $post->post_modified_gmt !== '0000-00-00 00:00:00')
                ? gmdate('Y-m-d', strtotime($post->post_modified_gmt))
                : gmdate('Y-m-d');
            $priority = ($post->post_type === 'page') ? '0.8' : '0.6';

            $xml .= $this->sitemap_url_entry($url, $lastmod, 'monthly', $priority);
        }

        $xml .= '</urlset>';
        return $xml;
    }

    /**
     * Generate sitemap index when there are many URLs.
     */
    private function generate_sitemap_index(int $total_pages): string {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        for ($i = 1; $i <= $total_pages; $i++) {
            $loc = home_url("/sitemap-{$i}.xml");
            $xml .= "  <sitemap>\n";
            $xml .= "    <loc>" . esc_url($loc) . "</loc>\n";
            $xml .= "    <lastmod>" . gmdate('Y-m-d') . "</lastmod>\n";
            $xml .= "  </sitemap>\n";
        }

        $xml .= '</sitemapindex>';
        return $xml;
    }

    /**
     * Build a single <url> entry.
     */
    private function sitemap_url_entry(string $loc, string $lastmod, string $changefreq, string $priority): string {
        $xml  = "  <url>\n";
        $xml .= "    <loc>" . esc_url($loc) . "</loc>\n";
        $xml .= "    <lastmod>" . esc_html($lastmod) . "</lastmod>\n";
        $xml .= "    <changefreq>" . esc_html($changefreq) . "</changefreq>\n";
        $xml .= "    <priority>" . esc_html($priority) . "</priority>\n";
        $xml .= "  </url>\n";
        return $xml;
    }

    /**
     * Get the sitemap URL.
     */
    public function get_sitemap_url(): string {
        return home_url('/sitemap.xml');
    }

    /**
     * Write sitemap.xml file to disk (for static generation).
     */
    public function write_sitemap_file(): bool {
        $xml  = $this->generate_sitemap(0);
        $path = ABSPATH . 'sitemap.xml';
        $written = @file_put_contents($path, $xml);
        return ($written !== false);
    }

    // ──────────────────────────────────────────────
    //  Robots.txt
    // ──────────────────────────────────────────────

    /**
     * Filter robots.txt output to include custom rules and sitemap URL.
     */
    public function robots_txt(string $output, bool $public): string {
        $settings = $this->get_settings();

        // Append custom rules
        if (!empty($settings['custom_robots'])) {
            $output .= "\n" . $settings['custom_robots'] . "\n";
        }

        // Ensure Sitemap URL is present
        if ($settings['sitemap_enabled']) {
            $sitemap_url = $this->get_sitemap_url();
            if (strpos($output, $sitemap_url) === false) {
                $output .= "\nSitemap: " . $sitemap_url . "\n";
            }
        }

        return $output;
    }

    /**
     * Get the current robots.txt content (WordPress-generated + our additions).
     */
    public function get_robots_content(): string {
        $settings = $this->get_settings();
        return $settings['custom_robots'] ?: '';
    }

    /**
     * Save custom robots.txt content to settings.
     */
    public function save_robots(string $content): bool {
        $settings = $this->get_settings();
        $settings['custom_robots'] = sanitize_textarea_field($content);
        return $this->save_settings($settings);
    }

    // ──────────────────────────────────────────────
    //  301 Redirects
    // ──────────────────────────────────────────────

    /**
     * Get all redirects.
     *
     * @return array [{from, to, hits}]
     */
    public function get_redirects(): array {
        return get_option('wnb_redirects', []);
    }

    /**
     * Add or update a redirect.
     */
    public function add_redirect(string $from, string $to): bool {
        $from = '/' . ltrim(sanitize_text_field($from), '/');
        $to   = sanitize_text_field($to);

        if (empty($from) || $from === '/' || empty($to)) {
            return false;
        }

        $redirects = $this->get_redirects();

        // Check if the source already exists — update it
        $found = false;
        foreach ($redirects as &$redirect) {
            if ($redirect['from'] === $from) {
                $redirect['to'] = $to;
                $found = true;
                break;
            }
        }
        unset($redirect);

        if (!$found) {
            $redirects[] = [
                'from' => $from,
                'to'   => $to,
                'hits'  => 0,
            ];
        }

        return update_option('wnb_redirects', $redirects);
    }

    /**
     * Delete a redirect by source path.
     */
    public function delete_redirect(string $from): bool {
        $from = '/' . ltrim($from, '/');
        $redirects = $this->get_redirects();

        $redirects = array_filter($redirects, function ($r) use ($from) {
            return $r['from'] !== $from;
        });

        return update_option('wnb_redirects', array_values($redirects));
    }

    /**
     * Check current request against redirect list and perform 301 if matched.
     */
    public function handle_redirects(): void {
        if (is_admin()) return;

        $request_path = '/' . ltrim(wp_parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/');
        $redirects    = $this->get_redirects();

        foreach ($redirects as $index => $redirect) {
            if ($redirect['from'] === $request_path) {
                // Increment hit counter
                $redirects[$index]['hits'] = ($redirect['hits'] ?? 0) + 1;
                update_option('wnb_redirects', $redirects);

                // Determine target URL
                $target = $redirect['to'];
                if (strpos($target, 'http') !== 0 && strpos($target, '/') === 0) {
                    $target = home_url($target);
                }

                wp_redirect($target, 301);
                exit;
            }
        }
    }

    // ──────────────────────────────────────────────
    //  JSON-LD Schema
    // ──────────────────────────────────────────────

    /**
     * Output JSON-LD structured data in wp_head.
     */
    public function output_schema_markup(): void {
        if (is_admin()) return;

        $settings = $this->get_settings();
        if (!$settings['schema_enabled']) return;

        $schemas = [];

        // Organization schema
        $org_name = $settings['organization_name'] ?: get_bloginfo('name');
        $org = [
            '@type' => 'Organization',
            '@id'   => home_url('/#organization'),
            'name'  => $org_name,
            'url'   => home_url('/'),
        ];
        if (!empty($settings['organization_logo'])) {
            $org['logo'] = [
                '@type' => 'ImageObject',
                'url'   => $settings['organization_logo'],
            ];
        }
        if (!empty($settings['phone'])) {
            $org['telephone'] = $settings['phone'];
        }
        if (!empty($settings['address'])) {
            $org['address'] = [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $settings['address'],
            ];
        }
        $schemas[] = $org;

        // WebSite schema with SearchAction
        $schemas[] = [
            '@type'          => 'WebSite',
            '@id'            => home_url('/#website'),
            'name'           => get_bloginfo('name'),
            'url'            => home_url('/'),
            'publisher'      => ['@id' => home_url('/#organization')],
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // Page-specific schema
        if (is_singular()) {
            $post_id = get_the_ID();
            $post    = get_post($post_id);

            if ($post) {
                $seo_title = get_post_meta($post_id, 'wnb_seo_title', true) ?: get_the_title($post_id);
                $desc      = $this->get_meta_description($post_id);
                $image     = get_post_meta($post_id, 'wnb_og_image', true) ?: get_the_post_thumbnail_url($post_id, 'large');

                if (get_post_type($post_id) === 'post') {
                    // Article schema for blog posts
                    $article = [
                        '@type'         => 'Article',
                        '@id'           => get_permalink($post_id) . '#article',
                        'headline'      => $seo_title,
                        'url'           => get_permalink($post_id),
                        'datePublished' => get_the_date('c', $post_id),
                        'dateModified'  => get_the_modified_date('c', $post_id),
                        'author'        => [
                            '@type' => 'Person',
                            'name'  => get_the_author_meta('display_name', $post->post_author),
                        ],
                        'publisher'     => ['@id' => home_url('/#organization')],
                        'isPartOf'      => ['@id' => home_url('/#website')],
                    ];
                    if ($desc) {
                        $article['description'] = $desc;
                    }
                    if ($image) {
                        $article['image'] = $image;
                    }
                    $schemas[] = $article;
                } else {
                    // WebPage schema for pages
                    $webpage = [
                        '@type'         => 'WebPage',
                        '@id'           => get_permalink($post_id) . '#webpage',
                        'name'          => $seo_title,
                        'url'           => get_permalink($post_id),
                        'datePublished' => get_the_date('c', $post_id),
                        'dateModified'  => get_the_modified_date('c', $post_id),
                        'isPartOf'      => ['@id' => home_url('/#website')],
                    ];
                    if ($desc) {
                        $webpage['description'] = $desc;
                    }
                    if ($image) {
                        $webpage['primaryImageOfPage'] = $image;
                    }
                    $schemas[] = $webpage;
                }
            }
        }

        // Wrap in @graph
        $json_ld = [
            '@context' => 'https://schema.org',
            '@graph'   => $schemas,
        ];

        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo "\n</script>\n";
    }

    /**
     * Public getter for schema markup (returns the array, not HTML).
     */
    public function get_schema_markup(): array {
        $settings = $this->get_settings();
        $org_name = $settings['organization_name'] ?: get_bloginfo('name');

        return [
            'organization' => $org_name,
            'logo'         => $settings['organization_logo'] ?? '',
            'phone'        => $settings['phone'] ?? '',
            'address'      => $settings['address'] ?? '',
        ];
    }

    // ──────────────────────────────────────────────
    //  Settings
    // ──────────────────────────────────────────────

    /**
     * Get all SEO settings.
     */
    public function get_settings(): array {
        $saved = get_option('wnb_seo_settings', []);
        return wp_parse_args($saved, $this->defaults);
    }

    /**
     * Save SEO settings.
     */
    public function save_settings(array $data): bool {
        $clean = [
            'organization_name' => sanitize_text_field($data['organization_name'] ?? ''),
            'organization_logo' => esc_url_raw($data['organization_logo'] ?? ''),
            'phone'             => sanitize_text_field($data['phone'] ?? ''),
            'address'           => sanitize_text_field($data['address'] ?? ''),
            'schema_enabled'    => !empty($data['schema_enabled']),
            'sitemap_enabled'   => !empty($data['sitemap_enabled']),
            'og_enabled'        => !empty($data['og_enabled']),
            'custom_robots'     => sanitize_textarea_field($data['custom_robots'] ?? ''),
        ];

        $result = update_option('wnb_seo_settings', $clean);

        // Flush rewrite rules if sitemap setting changed
        $old = get_option('wnb_seo_settings', []);
        if (($old['sitemap_enabled'] ?? true) !== $clean['sitemap_enabled']) {
            flush_rewrite_rules();
        }

        return $result;
    }

    /**
     * Count published pages/posts without a meta description set.
     */
    public function get_pages_without_meta(): int {
        global $wpdb;

        $post_types = get_post_types(['public' => true], 'names');
        unset($post_types['attachment']);

        if (empty($post_types)) return 0;

        $placeholders = implode(',', array_fill(0, count($post_types), '%s'));

        $count = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->posts} p
                 WHERE p.post_type IN ($placeholders)
                   AND p.post_status = 'publish'
                   AND p.ID NOT IN (
                       SELECT pm.post_id FROM {$wpdb->postmeta} pm
                       WHERE pm.meta_key = 'wnb_meta_description'
                         AND pm.meta_value != ''
                   )",
                ...array_values($post_types)
            )
        );

        return $count;
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Save SEO settings.
     */
    public function ajax_save_seo_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $data = [
            'organization_name' => $_POST['organization_name'] ?? '',
            'organization_logo' => $_POST['organization_logo'] ?? '',
            'phone'             => $_POST['phone'] ?? '',
            'address'           => $_POST['address'] ?? '',
            'schema_enabled'    => !empty($_POST['schema_enabled']),
            'sitemap_enabled'   => !empty($_POST['sitemap_enabled']),
            'og_enabled'        => !empty($_POST['og_enabled']),
            'custom_robots'     => $_POST['custom_robots'] ?? '',
        ];

        $this->save_settings($data);

        wp_send_json_success([
            'message'  => 'SEO settings saved successfully.',
            'settings' => $this->get_settings(),
        ]);
    }

    /**
     * AJAX: Regenerate sitemap file.
     */
    public function ajax_generate_sitemap(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        // Flush rewrite rules so the rewrite rule is registered
        flush_rewrite_rules();

        // Also write a static file as fallback
        $written = $this->write_sitemap_file();

        wp_send_json_success([
            'message'     => $written
                ? 'Sitemap generated and saved successfully.'
                : 'Sitemap rewrite rules activated. Static file could not be written (check permissions).',
            'sitemap_url' => $this->get_sitemap_url(),
        ]);
    }

    /**
     * AJAX: Add or update a redirect.
     */
    public function ajax_save_redirect(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $from = sanitize_text_field($_POST['from'] ?? '');
        $to   = sanitize_text_field($_POST['to'] ?? '');

        if (empty($from) || empty($to)) {
            wp_send_json_error(['message' => 'Both "from" and "to" fields are required.']);
        }

        $result = $this->add_redirect($from, $to);

        if ($result) {
            wp_send_json_success([
                'message'   => 'Redirect saved successfully.',
                'redirects' => $this->get_redirects(),
            ]);
        } else {
            wp_send_json_error(['message' => 'Failed to save redirect.']);
        }
    }

    /**
     * AJAX: Delete a redirect.
     */
    public function ajax_delete_redirect(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $from = sanitize_text_field($_POST['from'] ?? '');
        if (empty($from)) {
            wp_send_json_error(['message' => 'The "from" field is required.']);
        }

        $this->delete_redirect($from);

        wp_send_json_success([
            'message'   => 'Redirect deleted.',
            'redirects' => $this->get_redirects(),
        ]);
    }

    /**
     * AJAX: Save custom robots.txt content.
     */
    public function ajax_save_robots(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $content = sanitize_textarea_field($_POST['content'] ?? '');
        $this->save_robots($content);

        wp_send_json_success([
            'message' => 'Robots.txt rules saved successfully.',
            'content' => $this->get_robots_content(),
        ]);
    }
}
