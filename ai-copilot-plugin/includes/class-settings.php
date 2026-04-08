<?php
/**
 * Plugin Settings - Claude CLI based (no API key needed)
 */
class AICopilot_Settings
{
    public static function init()
    {
        add_action('admin_menu', [self::class, 'addSettingsPage']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    public static function registerSettings()
    {
        register_setting('aicopilot_settings', 'aicopilot_openai_key');
        register_setting('aicopilot_settings', 'aicopilot_pexels_key');
        register_setting('aicopilot_settings', 'aicopilot_pixabay_key');
    }

    public static function addSettingsPage()
    {
        add_options_page(
            'AI Copilot Settings',
            'AI Copilot',
            'manage_options',
            'ai-copilot-settings',
            [self::class, 'renderSettings']
        );
    }

    public static function isCliAvailable(): bool
    {
        // Check VPS API first
        $response = wp_remote_get('http://72.61.98.106:8090/health', ['timeout' => 5]);
        if (!is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (!empty($body['status']) && $body['status'] === 'ok') return true;
        }
        // Fallback: check local CLI
        return self::findClaude() !== null;
    }

    public static function findClaude(): ?string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $appData = getenv('APPDATA') ?: '';
            $paths = [
                $appData . '\\npm\\claude.cmd',
                $appData . '\\npm\\claude',
            ];
            foreach ($paths as $p) {
                if (file_exists($p)) return $p;
            }
            $result = trim(shell_exec('where claude 2>nul') ?? '');
            if ($result) return explode("\n", $result)[0];
        } else {
            $home = getenv('HOME') ?: '';
            $paths = [$home . '/.local/bin/claude', '/usr/local/bin/claude', '/usr/bin/claude'];
            foreach ($paths as $p) {
                if (file_exists($p)) return $p;
            }
        }
        return null;
    }

    public static function renderSettings()
    {
        $cliPath = self::findClaude();
        $available = $cliPath !== null;
        ?>
        <div class="wrap">
            <h1>AI Copilot Settings</h1>
            <form method="post" action="options.php">
            <?php settings_fields('aicopilot_settings'); ?>
            <table class="form-table">
                <tr>
                    <th>Claude CLI Status</th>
                    <td>
                        <?php if ($available): ?>
                            <span style="color:#16a34a;font-weight:600;">✅ Claude CLI Found</span>
                            <p class="description">Path: <?php echo esc_html($cliPath); ?></p>
                        <?php else: ?>
                            <span style="color:#dc2626;font-weight:600;">❌ Claude CLI Not Found</span>
                            <p class="description">Install Claude Code CLI: <code>npm install -g @anthropic-ai/claude-code</code></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>How It Works</th>
                    <td>
                        <p>This plugin uses <strong>Claude Code CLI</strong> (installed on your server) to power AI features. No API key needed - it uses your Claude CLI subscription.</p>
                        <p><strong>Features:</strong></p>
                        <ul style="list-style:disc;padding-left:20px;">
                            <li>Edit text, styles, images via AI chat</li>
                            <li>Add/remove Elementor sections</li>
                            <li>Create pages, update SEO</li>
                            <li>Change global colors and fonts</li>
                            <li>Upload images from URLs</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th colspan="2"><h2 style="margin:0;">Image Generation API Keys (Optional)</h2></th>
                </tr>
                <tr>
                    <th>OpenAI API Key (DALL-E)</th>
                    <td>
                        <input type="password" name="aicopilot_openai_key" value="<?php echo esc_attr(get_option('aicopilot_openai_key', '')); ?>" class="regular-text" placeholder="sk-...">
                        <p class="description">For AI image generation. Get key from <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a></p>
                    </td>
                </tr>
                <tr>
                    <th>Pexels API Key (Free)</th>
                    <td>
                        <input type="text" name="aicopilot_pexels_key" value="<?php echo esc_attr(get_option('aicopilot_pexels_key', '')); ?>" class="regular-text" placeholder="Free key from pexels.com">
                        <p class="description">Free stock photos. Get key from <a href="https://www.pexels.com/api/" target="_blank">pexels.com/api</a></p>
                    </td>
                </tr>
                <tr>
                    <th>Pixabay API Key (Free)</th>
                    <td>
                        <input type="text" name="aicopilot_pixabay_key" value="<?php echo esc_attr(get_option('aicopilot_pixabay_key', '')); ?>" class="regular-text" placeholder="Free key from pixabay.com">
                        <p class="description">Fallback stock photos. Get key from <a href="https://pixabay.com/api/docs/" target="_blank">pixabay.com/api</a></p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
            </form>
        </div>
        <?php
    }
}
