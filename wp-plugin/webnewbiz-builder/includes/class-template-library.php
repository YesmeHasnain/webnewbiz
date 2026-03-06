<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class Template_Library {

    private string $templates_dir;

    public function __construct() {
        $this->templates_dir = WNB_DIR . 'templates/elementor/';
    }

    /**
     * Get available template styles.
     */
    public function get_styles(): array {
        $styles = [];
        if (!is_dir($this->templates_dir)) return $styles;
        foreach (scandir($this->templates_dir) as $dir) {
            if ($dir === '.' || $dir === '..' || !is_dir($this->templates_dir . $dir)) continue;
            $styles[] = $dir;
        }
        return $styles;
    }

    /**
     * Get available page types for a style.
     */
    public function get_pages(string $style): array {
        $dir = $this->templates_dir . $style . '/';
        $pages = [];
        if (!is_dir($dir)) return $pages;
        foreach (glob($dir . '*.json') as $file) {
            $pages[] = pathinfo($file, PATHINFO_FILENAME);
        }
        return $pages;
    }

    /**
     * Load template JSON data.
     */
    public function load_template(string $style, string $page): ?array {
        $path = $this->templates_dir . "{$style}/{$page}.json";
        if (!file_exists($path)) {
            // Fallback to generic page template
            $path = $this->templates_dir . "{$style}/page.json";
        }
        if (!file_exists($path)) {
            return null;
        }
        $json = file_get_contents($path);
        return json_decode($json, true);
    }

    /**
     * Hydrate template JSON by replacing {{placeholder}} tokens.
     * Ported from AbstractTemplate::hydrateJson().
     */
    public function hydrate(string $json, array $data, array $colors = [], array $images = []): string {
        return preg_replace_callback('/\{\{(\w+)\}\}/', function ($matches) use ($data, $colors, $images) {
            $key = $matches[1];
            if (isset($data[$key]) && is_string($data[$key])) {
                return $this->json_escape($data[$key]);
            }
            if (isset($colors[$key])) {
                return $this->json_escape($colors[$key]);
            }
            if (isset($images[$key])) {
                $val = $images[$key];
                return is_int($val) ? (string) $val : $this->json_escape((string) $val);
            }
            return '';
        }, $json);
    }

    /**
     * Regenerate Elementor element IDs (7-char hex).
     * Ported from AbstractTemplate::regenerateIds().
     */
    public function regenerate_ids(string $json): string {
        return preg_replace_callback('/"id"\s*:\s*"[a-z0-9]{7,8}"/', function () {
            return '"id":"' . substr(md5(uniqid(mt_rand(), true)), 0, 7) . '"';
        }, $json);
    }

    /**
     * Full pipeline: load → hydrate → regenerate IDs → decode.
     */
    public function build(string $style, string $page, array $data = [], array $colors = [], array $images = []): ?array {
        $template = $this->load_template($style, $page);
        if (!$template) return null;

        $json = json_encode($template, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $json = $this->hydrate($json, $data, $colors, $images);
        $json = $this->regenerate_ids($json);

        return json_decode($json, true);
    }

    /**
     * Escape a value for safe insertion into JSON string context.
     */
    private function json_escape(string $value): string {
        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE);
        return trim($encoded, '"');
    }
}
