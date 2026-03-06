<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Settings {

    private string $page_slug = 'webnewbiz-builder';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_menu(): void {
        add_menu_page(
            __('WebnewBiz Builder', 'webnewbiz-builder'),
            __('WebnewBiz', 'webnewbiz-builder'),
            'manage_options',
            $this->page_slug,
            [$this, 'render_page'],
            'dashicons-layout',
            58
        );
    }

    public function register_settings(): void {
        // AI settings
        register_setting('wnb_settings', 'wnb_claude_api_key', ['sanitize_callback' => 'sanitize_text_field']);
        register_setting('wnb_settings', 'wnb_claude_model', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'claude-sonnet-4-20250514']);
        register_setting('wnb_settings', 'wnb_gemini_api_key', ['sanitize_callback' => 'sanitize_text_field']);

        // Style settings
        register_setting('wnb_settings', 'wnb_default_style', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'agency']);
        register_setting('wnb_settings', 'wnb_primary_color', ['sanitize_callback' => 'sanitize_hex_color', 'default' => '#2563eb']);
        register_setting('wnb_settings', 'wnb_secondary_color', ['sanitize_callback' => 'sanitize_hex_color', 'default' => '#1e40af']);
        register_setting('wnb_settings', 'wnb_accent_color', ['sanitize_callback' => 'sanitize_hex_color', 'default' => '#60a5fa']);

        // Section: AI Config
        add_settings_section('wnb_section_ai', __('AI Configuration', 'webnewbiz-builder'), function () {
            echo '<p>' . __('Configure AI API keys for content generation.', 'webnewbiz-builder') . '</p>';
        }, $this->page_slug);

        add_settings_field('wnb_claude_api_key', __('Claude API Key', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_claude_api_key', '');
            echo '<input type="password" name="wnb_claude_api_key" value="' . esc_attr($val) . '" class="regular-text" autocomplete="off">';
        }, $this->page_slug, 'wnb_section_ai');

        add_settings_field('wnb_claude_model', __('Claude Model', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_claude_model', 'claude-sonnet-4-20250514');
            $models = [
                'claude-sonnet-4-20250514' => 'Claude Sonnet 4 (Recommended)',
                'claude-opus-4-20250514' => 'Claude Opus 4',
                'claude-haiku-4-5-20251001' => 'Claude Haiku 4.5 (Fast)',
            ];
            echo '<select name="wnb_claude_model">';
            foreach ($models as $id => $label) {
                echo '<option value="' . esc_attr($id) . '"' . selected($val, $id, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }, $this->page_slug, 'wnb_section_ai');

        add_settings_field('wnb_gemini_api_key', __('Gemini API Key (Fallback)', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_gemini_api_key', '');
            echo '<input type="password" name="wnb_gemini_api_key" value="' . esc_attr($val) . '" class="regular-text" autocomplete="off">';
        }, $this->page_slug, 'wnb_section_ai');

        // Section: Default Styles
        add_settings_section('wnb_section_style', __('Default Styles', 'webnewbiz-builder'), function () {
            echo '<p>' . __('Set default template style and brand colors.', 'webnewbiz-builder') . '</p>';
        }, $this->page_slug);

        add_settings_field('wnb_default_style', __('Template Style', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_default_style', 'agency');
            $styles = ['agency' => 'Agency (Modern)', 'corporate' => 'Corporate (Classic)', 'flavor' => 'Flavor (Warm)', 'prestige' => 'Prestige (Luxury)', 'starter' => 'Starter (Bold)', 'vivid' => 'Vivid (Creative)', 'zenith' => 'Zenith (Tech)'];
            echo '<select name="wnb_default_style">';
            foreach ($styles as $id => $label) {
                echo '<option value="' . esc_attr($id) . '"' . selected($val, $id, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }, $this->page_slug, 'wnb_section_style');

        add_settings_field('wnb_primary_color', __('Primary Color', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_primary_color', '#2563eb');
            echo '<input type="color" name="wnb_primary_color" value="' . esc_attr($val) . '"> <code>' . esc_html($val) . '</code>';
        }, $this->page_slug, 'wnb_section_style');

        add_settings_field('wnb_secondary_color', __('Secondary Color', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_secondary_color', '#1e40af');
            echo '<input type="color" name="wnb_secondary_color" value="' . esc_attr($val) . '"> <code>' . esc_html($val) . '</code>';
        }, $this->page_slug, 'wnb_section_style');

        add_settings_field('wnb_accent_color', __('Accent Color', 'webnewbiz-builder'), function () {
            $val = get_option('wnb_accent_color', '#60a5fa');
            echo '<input type="color" name="wnb_accent_color" value="' . esc_attr($val) . '"> <code>' . esc_html($val) . '</code>';
        }, $this->page_slug, 'wnb_section_style');
    }

    public function render_page(): void {
        ?>
        <div class="wrap">
            <h1><span class="dashicons dashicons-layout" style="font-size: 28px; margin-right: 8px;"></span><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p style="font-size: 14px; color: #666;"><?php _e('Version', 'webnewbiz-builder'); ?> <?php echo WNB_VERSION; ?></p>
            <form method="post" action="options.php">
                <?php
                settings_fields('wnb_settings');
                do_settings_sections($this->page_slug);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
