<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Website_Generator {

    private Content_Generator $content;
    private Image_Service $images;
    private Template_Library $templates;

    public function __construct(Content_Generator $content, Image_Service $images) {
        $this->content   = $content;
        $this->images    = $images;
        $this->templates = new Template_Library();

        add_action('admin_menu', [$this, 'add_submenu']);
        add_action('wp_ajax_wnb_generate_website', [$this, 'ajax_generate']);
    }

    public function add_submenu(): void {
        add_submenu_page(
            'webnewbiz-builder',
            __('Generate Website', 'webnewbiz-builder'),
            __('Generate Website', 'webnewbiz-builder'),
            'manage_options',
            'wnb-generate',
            [$this, 'render_page']
        );
    }

    public function render_page(): void {
        $default_theme     = get_option('wnb_default_style', 'wnb-starter');
        $has_api_key       = get_option('wnb_claude_api_key') || get_option('wnb_gemini_api_key');
        $all_themes        = Theme_Manager::get_all_themes();
        ?>
        <div class="wrap wnb-generator-wrap">
            <h1><span class="dashicons dashicons-welcome-add-page" style="font-size:28px;margin-right:8px;"></span><?php _e('Generate Website', 'webnewbiz-builder'); ?></h1>

            <?php if (!$has_api_key): ?>
                <div class="notice notice-error"><p>
                    <strong><?php _e('No AI API key configured.', 'webnewbiz-builder'); ?></strong>
                    <?php printf(__('Please add a Claude or Gemini API key in <a href="%s">WebnewBiz Settings</a>.', 'webnewbiz-builder'), admin_url('admin.php?page=webnewbiz-builder')); ?>
                </p></div>
            <?php endif; ?>

            <div class="wnb-generator-form-card">
                <form id="wnb-generator-form">
                    <?php wp_nonce_field('wnb_generate_website', 'wnb_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="wnb_business_name"><?php _e('Business Name', 'webnewbiz-builder'); ?> <span class="required">*</span></label></th>
                            <td><input type="text" id="wnb_business_name" name="business_name" class="regular-text" required placeholder="<?php esc_attr_e('e.g. Pixel Perfect Studios', 'webnewbiz-builder'); ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="wnb_business_type"><?php _e('Business Type', 'webnewbiz-builder'); ?></label></th>
                            <td>
                                <select id="wnb_business_type" name="business_type">
                                    <option value="consulting">Consulting</option>
                                    <option value="technology" selected>Technology</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="health">Health & Medical</option>
                                    <option value="education">Education</option>
                                    <option value="beauty">Beauty & Salon</option>
                                    <option value="fitness">Fitness & Gym</option>
                                    <option value="clothing">Clothing & Fashion</option>
                                    <option value="realestate">Real Estate</option>
                                    <option value="food">Food & Catering</option>
                                    <option value="ecommerce">E-Commerce</option>
                                    <option value="agency">Agency</option>
                                    <option value="other">Other</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="wnb_description"><?php _e('Description', 'webnewbiz-builder'); ?></label></th>
                            <td><textarea id="wnb_description" name="description" rows="4" class="large-text" placeholder="<?php esc_attr_e('Describe your business, target audience, services, and what makes you unique...', 'webnewbiz-builder'); ?>"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="wnb_theme"><?php _e('Theme', 'webnewbiz-builder'); ?></label></th>
                            <td>
                                <select id="wnb_theme" name="theme">
                                    <?php foreach ($all_themes as $slug => $theme): ?>
                                        <option value="<?php echo esc_attr($slug); ?>"<?php selected($default_theme, $slug); ?>>
                                            <?php echo esc_html($theme['name']); ?> &mdash; <?php echo esc_html($theme['description']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php _e('The theme will be installed and activated on your site. Colors and fonts are set by the theme.', 'webnewbiz-builder'); ?></p>
                            </td>
                        </tr>
                    </table>

                    <div class="wnb-generator-actions">
                        <button type="submit" class="button button-primary button-hero" <?php echo $has_api_key ? '' : 'disabled'; ?>>
                            <span class="dashicons dashicons-admin-site-alt3" style="margin-top:4px;"></span>
                            <?php _e('Generate Website', 'webnewbiz-builder'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Progress -->
            <div id="wnb-progress" style="display:none;">
                <div class="wnb-progress-card">
                    <h2 id="wnb-progress-title"><?php _e('Generating your website...', 'webnewbiz-builder'); ?></h2>
                    <div class="wnb-progress-bar-wrap">
                        <div class="wnb-progress-bar" id="wnb-progress-bar" style="width:0%"></div>
                    </div>
                    <p id="wnb-progress-step" class="description"><?php _e('Initializing...', 'webnewbiz-builder'); ?></p>
                    <div class="wnb-step-indicators">
                        <span class="wnb-step" data-step="1"><?php _e('Theme', 'webnewbiz-builder'); ?></span>
                        <span class="wnb-step" data-step="2"><?php _e('Content', 'webnewbiz-builder'); ?></span>
                        <span class="wnb-step" data-step="3"><?php _e('Images', 'webnewbiz-builder'); ?></span>
                        <span class="wnb-step" data-step="4"><?php _e('Pages', 'webnewbiz-builder'); ?></span>
                        <span class="wnb-step" data-step="5"><?php _e('Menu', 'webnewbiz-builder'); ?></span>
                        <span class="wnb-step" data-step="6"><?php _e('Done', 'webnewbiz-builder'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Result -->
            <div id="wnb-result" style="display:none;">
                <div class="wnb-result-card">
                    <h2><span class="dashicons dashicons-yes-alt" style="color:#46b450;"></span> <?php _e('Website Generated Successfully!', 'webnewbiz-builder'); ?></h2>
                    <div id="wnb-result-links"></div>
                    <p style="margin-top:20px;">
                        <a href="#" id="wnb-generate-another" class="button"><?php _e('Generate Another', 'webnewbiz-builder'); ?></a>
                    </p>
                </div>
            </div>

            <!-- Error -->
            <div id="wnb-error" style="display:none;">
                <div class="notice notice-error">
                    <p id="wnb-error-message"></p>
                    <p><button type="button" class="button" id="wnb-retry"><?php _e('Try Again', 'webnewbiz-builder'); ?></button></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler: full website generation pipeline.
     */
    public function ajax_generate(): void {
        check_ajax_referer('wnb_generate_website', 'wnb_nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $business_name = sanitize_text_field($_POST['business_name'] ?? '');
        $business_type = sanitize_text_field($_POST['business_type'] ?? 'technology');
        $description   = sanitize_textarea_field($_POST['description'] ?? '');
        $theme_slug    = sanitize_text_field($_POST['theme'] ?? 'wnb-starter');

        if (empty($business_name)) {
            wp_send_json_error(['message' => 'Business name is required.']);
        }

        // Resolve theme → template style and colors
        $theme_config = Theme_Manager::get_theme($theme_slug);
        if (!$theme_config) {
            $theme_slug   = 'wnb-starter';
            $theme_config = Theme_Manager::get_theme($theme_slug);
        }
        $style = Theme_Manager::get_template_style($theme_slug);

        // Increase execution time for AI + image downloads + theme install
        @set_time_limit(300);

        // Step 0: Install and activate the selected theme
        Theme_Manager::setup_theme($theme_slug);

        // Assign existing menus to the new theme's locations (theme_mods are per-theme)
        $existing_menu = wp_get_nav_menu_object('Main Menu');
        if ($existing_menu) {
            $locations = get_theme_mod('nav_menu_locations', []);
            $locations['header_menu'] = $existing_menu->term_id;
            $locations['primary']     = $existing_menu->term_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        // Step 1: AI content generation
        $content_result = $this->content->generate_website_content($business_type, $business_name, $style, $description);
        if (!$content_result['success']) {
            wp_send_json_error(['message' => 'AI content generation failed: ' . ($content_result['message'] ?? 'Unknown error'), 'step' => 1]);
        }
        $ai_content = $content_result['data'];

        // Step 2: Download images and create WP attachments
        // Try up to 2 different photo sets if first fails
        $image_ids = [];
        for ($img_attempt = 0; $img_attempt < 2; $img_attempt++) {
            $image_urls = $this->images->get_images($business_type);
            foreach ($image_urls as $key => $url) {
                if (isset($image_ids[$key])) continue;
                $attach_id = $this->images->download_and_attach($url, $business_name . ' ' . ucfirst($key));
                if ($attach_id) {
                    $image_ids[$key] = $attach_id;
                }
            }
            // If all 3 images downloaded, stop retrying
            if (count($image_ids) >= 3) break;
        }

        // Build template data maps — use theme colors
        $theme_colors = Theme_Manager::get_colors($theme_slug);
        $colors = [
            'primary'   => $theme_colors['primary'] ?? ($ai_content['colors']['primary'] ?? '#2563eb'),
            'secondary' => $theme_colors['secondary'] ?? ($ai_content['colors']['secondary'] ?? '#1e40af'),
            'accent'    => $theme_colors['accent'] ?? ($ai_content['colors']['accent'] ?? '#60a5fa'),
        ];

        $image_data = [];
        foreach ($image_ids as $key => $id) {
            $image_data[$key . '_image'] = wp_get_attachment_url($id);
            $image_data[$key . '_image_id'] = $id;
        }

        // Step 3: Create pages with Elementor data
        $pages_content = $ai_content['pages'] ?? [];
        $created_pages = [];

        // Page definitions: slug => [template_page, title_key]
        $page_defs = [
            'home'     => ['home', 'home'],
            'about'    => ['about', 'about'],
            'services' => ['services', 'services'],
            'pricing'  => ['pricing', 'pricing'],
            'contact'  => ['contact', 'contact'],
        ];

        foreach ($page_defs as $slug => $def) {
            [$template_page, $content_key] = $def;
            $page_content = $pages_content[$content_key] ?? [];

            // Map AI content to template placeholders
            $data = $this->map_content_to_placeholders($slug, $page_content, $ai_content, $business_name);

            // Build Elementor data via template library
            $elementor_data = $this->templates->build($style, $template_page, $data, $colors, $image_data);

            if (!$elementor_data) {
                continue;
            }

            $title = $page_content['title'] ?? ($slug === 'home' ? ($ai_content['site_title'] ?? $business_name) : ucfirst($slug));
            $page_id = $this->create_elementor_page($title, $slug, $elementor_data);

            if ($page_id) {
                $created_pages[$slug] = $page_id;
            }
        }

        if (empty($created_pages)) {
            wp_send_json_error(['message' => 'Failed to create any pages. Check that templates exist for style: ' . $style, 'step' => 3]);
        }

        // Step 4: Set homepage and create nav menu
        if (isset($created_pages['home'])) {
            $this->set_homepage($created_pages['home']);
        }
        $this->create_nav_menu($created_pages);

        // Update site title and tagline
        if (!empty($ai_content['site_title'])) {
            update_option('blogname', $ai_content['site_title']);
        }
        if (!empty($ai_content['tagline'])) {
            update_option('blogdescription', $ai_content['tagline']);
        }

        // Step 5: Regenerate Elementor CSS
        $this->regenerate_css(array_values($created_pages));

        // Build result links
        $links = [];
        foreach ($created_pages as $slug => $page_id) {
            $links[] = [
                'title'    => get_the_title($page_id),
                'url'      => get_permalink($page_id),
                'edit_url' => admin_url("post.php?post={$page_id}&action=elementor"),
                'slug'     => $slug,
            ];
        }

        wp_send_json_success([
            'message' => 'Website generated successfully!',
            'pages'   => $links,
            'home'    => isset($created_pages['home']) ? get_permalink($created_pages['home']) : '',
        ]);
    }

    /**
     * Map AI-generated content to template placeholder keys.
     */
    private function map_content_to_placeholders(string $slug, array $page_content, array $ai_content, string $business_name): array {
        $data = [
            'site_title' => $ai_content['site_title'] ?? $business_name,
        ];

        switch ($slug) {
            case 'home':
                $data['hero_title']       = $page_content['hero_title'] ?? $business_name;
                $data['hero_subtitle']    = $page_content['hero_subtitle'] ?? '';
                $data['hero_description'] = $page_content['hero_subtitle'] ?? '';
                $data['hero_cta']         = $page_content['hero_cta'] ?? 'Get Started';

                // CTA section
                $sections = $page_content['sections'] ?? [];
                foreach ($sections as $section) {
                    if (($section['type'] ?? '') === 'cta') {
                        $data['cta_title']    = $section['title'] ?? '';
                        $data['cta_subtitle'] = $section['subtitle'] ?? '';
                        $data['cta_button']   = $section['button_text'] ?? 'Get Started';
                        break;
                    }
                }
                if (!isset($data['cta_title'])) {
                    $data['cta_title']    = 'Ready to Get Started?';
                    $data['cta_subtitle'] = 'Contact us today';
                    $data['cta_button']   = 'Contact Us';
                }
                break;

            case 'about':
                $data['hero_title']    = $page_content['title'] ?? 'About Us';
                $data['hero_subtitle'] = $page_content['mission'] ?? '';
                $data['content']       = $page_content['content'] ?? '';
                $data['mission']       = $page_content['mission'] ?? '';
                $data['cta_title']     = 'Work With Us';
                $data['cta_subtitle']  = $page_content['vision'] ?? 'Let\'s build something great together.';
                $data['cta_button']    = 'Get in Touch';
                break;

            case 'services':
                $data['hero_title']    = $page_content['title'] ?? 'Our Services';
                $data['hero_subtitle'] = $page_content['intro'] ?? '';
                $data['intro']         = $page_content['intro'] ?? '';
                $data['cta_title']     = 'Need a Custom Solution?';
                $data['cta_subtitle']  = 'Let\'s discuss how we can help.';
                $data['cta_button']    = 'Contact Us';
                break;

            case 'pricing':
                $data['hero_title']    = $page_content['title'] ?? 'Pricing';
                $data['hero_subtitle'] = $page_content['intro'] ?? '';
                $data['intro']         = $page_content['intro'] ?? '';
                $data['cta_title']     = 'Ready to Get Started?';
                $data['cta_subtitle']  = 'Choose the plan that works best for you.';
                $data['cta_button']    = 'Contact Us';

                // Build pricing placeholder from items
                $pricing_items = $page_content['items'] ?? [];
                $pricing_html = '';
                foreach ($pricing_items as $i => $plan) {
                    $features_html = '';
                    foreach (($plan['features'] ?? []) as $feat) {
                        $features_html .= $feat . ', ';
                    }
                    $pricing_html .= ($plan['title'] ?? 'Plan ' . ($i + 1)) . ': ' . ($plan['price'] ?? '') . ' - ' . ($plan['description'] ?? '') . '. ';
                }
                $data['pricing_placeholder'] = $pricing_html ?: 'Contact us for pricing details.';
                break;

            case 'contact':
                $data['hero_title']    = $page_content['title'] ?? 'Contact Us';
                $data['hero_subtitle'] = $page_content['subtitle'] ?? '';
                $data['address']       = $page_content['address'] ?? '';
                $data['phone']         = $page_content['phone'] ?? '';
                $data['email']         = $page_content['email'] ?? '';
                break;
        }

        return $data;
    }

    /**
     * Create a WordPress page with Elementor data.
     */
    private function create_elementor_page(string $title, string $slug, array $elementor_data): int {
        // Delete existing page with same slug if present
        $existing = get_page_by_path($slug);
        if ($existing) {
            wp_delete_post($existing->ID, true);
        }

        $page_id = wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_content' => '',
        ]);

        if (is_wp_error($page_id) || !$page_id) {
            return 0;
        }

        // Set Elementor metadata
        update_post_meta($page_id, '_elementor_data', wp_json_encode($elementor_data));
        update_post_meta($page_id, '_elementor_edit_mode', 'builder');
        update_post_meta($page_id, '_elementor_template_type', 'wp-page');
        update_post_meta($page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.25.0');
        update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');

        return $page_id;
    }

    /**
     * Set a page as the static front page.
     */
    private function set_homepage(int $page_id): void {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
    }

    /**
     * Create a navigation menu from the generated pages.
     */
    private function create_nav_menu(array $pages): void {
        $menu_name = 'Main Menu';

        // Delete existing menu with same name
        $existing = wp_get_nav_menu_object($menu_name);
        if ($existing) {
            wp_delete_nav_menu($existing->term_id);
        }

        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) {
            return;
        }

        $order = 1;
        $labels = [
            'home'     => 'Home',
            'about'    => 'About',
            'services' => 'Services',
            'pricing'  => 'Pricing',
            'contact'  => 'Contact',
        ];

        foreach ($labels as $slug => $label) {
            if (!isset($pages[$slug])) continue;

            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'     => $label,
                'menu-item-object'    => 'page',
                'menu-item-object-id' => $pages[$slug],
                'menu-item-type'      => 'post_type',
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $order++,
            ]);
        }

        // Assign to all known menu locations so it works with any theme
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['header_menu'] = $menu_id;  // wnb themes
        $locations['primary']     = $menu_id;  // fallback
        $locations['main']        = $menu_id;  // fallback
        set_theme_mod('nav_menu_locations', $locations);
    }

    /**
     * Regenerate Elementor CSS for created pages.
     */
    private function regenerate_css(array $page_ids): void {
        if (!class_exists('\Elementor\Core\Files\CSS\Post')) {
            return;
        }

        foreach ($page_ids as $page_id) {
            $css_file = \Elementor\Core\Files\CSS\Post::create($page_id);
            $css_file->update();
        }

        if (class_exists('\Elementor\Core\Files\CSS\Global_CSS')) {
            $global_css = \Elementor\Core\Files\CSS\Global_CSS::create('global.css');
            $global_css->update();
        }
    }
}
