<?php
/**
 * WebNewBiz Image Optimizer
 *
 * JPEG/PNG compression via GD, optional WebP conversion,
 * auto-resize on upload, bulk optimization, EXIF stripping.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_ImageOptimizer {

    private static ?self $instance = null;

    /** Default settings */
    private array $defaults = [
        'quality'       => 82,
        'webp_enabled'  => false,
        'auto_optimize' => true,
        'max_width'     => 2560,
        'max_height'    => 2560,
        'strip_exif'    => true,
    ];

    /** Supported MIME types */
    private array $supported_types = [
        'image/jpeg' => 'jpeg',
        'image/png'  => 'png',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $settings = $this->get_settings();

        // Auto-optimize on upload
        if ($settings['auto_optimize']) {
            add_filter('wp_generate_attachment_metadata', [$this, 'auto_optimize_on_upload'], 10, 2);
        }

        // AJAX handlers
        add_action('wp_ajax_wnb_optimize_images', [$this, 'ajax_optimize_images']);
        add_action('wp_ajax_wnb_save_image_settings', [$this, 'ajax_save_image_settings']);
        add_action('wp_ajax_wnb_image_stats', [$this, 'ajax_image_stats']);
    }

    // ──────────────────────────────────────────────
    //  Stats
    // ──────────────────────────────────────────────

    /**
     * Get image optimization statistics.
     */
    public function get_stats(): array {
        global $wpdb;

        // Total image attachments
        $total = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts}
             WHERE post_type = 'attachment'
               AND post_mime_type IN ('image/jpeg', 'image/png')"
        );

        // Optimized images (have wnb_optimized = 1)
        $optimized = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
             WHERE p.post_type = 'attachment'
               AND p.post_mime_type IN ('image/jpeg', 'image/png')
               AND pm.meta_key = 'wnb_optimized'
               AND pm.meta_value = '1'"
        );

        // Total bytes saved
        $savings = $wpdb->get_results(
            "SELECT pm.meta_value FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
             WHERE p.post_type = 'attachment'
               AND pm.meta_key = 'wnb_image_data'",
            ARRAY_A
        );

        $total_original = 0;
        $total_new = 0;

        if (is_array($savings)) {
            foreach ($savings as $row) {
                $data = maybe_unserialize($row['meta_value']);
                if (is_array($data)) {
                    $total_original += (int) ($data['original_size'] ?? 0);
                    $total_new += (int) ($data['new_size'] ?? 0);
                }
            }
        }

        $total_saved = max(0, $total_original - $total_new);
        $percent_saved = $total_original > 0
            ? round(($total_saved / $total_original) * 100, 1)
            : 0;

        return [
            'total_images'      => $total,
            'optimized_images'  => $optimized,
            'unoptimized_images'=> max(0, $total - $optimized),
            'total_original_kb' => round($total_original / 1024, 1),
            'total_new_kb'      => round($total_new / 1024, 1),
            'total_saved_kb'    => round($total_saved / 1024, 1),
            'percent_saved'     => $percent_saved,
        ];
    }

    /**
     * Get count of un-optimized image attachments.
     */
    public function get_unoptimized_count(): int {
        global $wpdb;

        return (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts} p
             WHERE p.post_type = 'attachment'
               AND p.post_mime_type IN ('image/jpeg', 'image/png')
               AND p.ID NOT IN (
                   SELECT post_id FROM {$wpdb->postmeta}
                   WHERE meta_key = 'wnb_optimized' AND meta_value = '1'
               )"
        );
    }

    // ──────────────────────────────────────────────
    //  Single Image Optimization
    // ──────────────────────────────────────────────

    /**
     * Optimize a single attachment image.
     *
     * @return array{success: bool, original_size: int, new_size: int, saved: int, webp_created: bool}
     */
    public function optimize_single(int $attachment_id): array {
        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return ['success' => false, 'error' => 'File not found.'];
        }

        $mime = get_post_mime_type($attachment_id);
        if (!isset($this->supported_types[$mime])) {
            return ['success' => false, 'error' => 'Unsupported image type: ' . $mime];
        }

        $settings = $this->get_settings();
        $type = $this->supported_types[$mime];
        $original_size = filesize($file);

        // Resize if exceeds max dimensions
        $resized = $this->resize_if_needed($file, $settings['max_width'], $settings['max_height']);

        // Load image via GD
        $image = $this->load_image($file, $type);
        if (!$image) {
            return ['success' => false, 'error' => 'Could not load image with GD.'];
        }

        // Strip EXIF by re-creating the image (GD doesn't copy EXIF by default)
        // This happens automatically when we re-save through GD.

        // Save optimized image
        $saved = $this->save_image($image, $file, $type, $settings['quality']);
        if (!$saved) {
            imagedestroy($image);
            return ['success' => false, 'error' => 'Could not save optimized image.'];
        }

        $new_size = filesize($file);

        // WebP conversion
        $webp_created = false;
        if ($settings['webp_enabled'] && function_exists('imagewebp')) {
            $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);
            if ($webp_path !== $file) {
                $webp_quality = min(90, $settings['quality'] + 5); // WebP can be slightly higher quality at same size
                $webp_created = imagewebp($image, $webp_path, $webp_quality);
            }
        }

        imagedestroy($image);

        // Also optimize thumbnail sizes
        $this->optimize_thumbnails($attachment_id, $type, $settings['quality'], $settings['webp_enabled']);

        // Store metadata
        $image_data = [
            'original_size' => $original_size,
            'new_size'      => $new_size,
            'saved'         => max(0, $original_size - $new_size),
            'percent'       => $original_size > 0 ? round((($original_size - $new_size) / $original_size) * 100, 1) : 0,
            'webp_created'  => $webp_created,
            'resized'       => $resized,
            'optimized_at'  => current_time('mysql'),
        ];

        update_post_meta($attachment_id, 'wnb_image_data', $image_data);
        update_post_meta($attachment_id, 'wnb_optimized', '1');

        return array_merge(['success' => true], $image_data);
    }

    // ──────────────────────────────────────────────
    //  Bulk Optimization
    // ──────────────────────────────────────────────

    /**
     * Optimize a batch of un-optimized images.
     */
    public function optimize_bulk(int $limit = 10): array {
        global $wpdb;

        $ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT p.ID FROM {$wpdb->posts} p
                 WHERE p.post_type = 'attachment'
                   AND p.post_mime_type IN ('image/jpeg', 'image/png')
                   AND p.ID NOT IN (
                       SELECT post_id FROM {$wpdb->postmeta}
                       WHERE meta_key = 'wnb_optimized' AND meta_value = '1'
                   )
                 ORDER BY p.ID ASC
                 LIMIT %d",
                $limit
            )
        );

        $results = [];
        $total_saved = 0;

        foreach ($ids as $id) {
            $result = $this->optimize_single((int) $id);
            $result['attachment_id'] = (int) $id;
            $results[] = $result;

            if (!empty($result['saved'])) {
                $total_saved += $result['saved'];
            }
        }

        return [
            'processed'    => count($results),
            'total_saved'  => $total_saved,
            'remaining'    => $this->get_unoptimized_count(),
            'results'      => $results,
        ];
    }

    // ──────────────────────────────────────────────
    //  Auto-Optimize on Upload
    // ──────────────────────────────────────────────

    /**
     * Hook: wp_generate_attachment_metadata
     * Automatically optimize images when they are uploaded.
     */
    public function auto_optimize_on_upload(array $metadata, int $attachment_id): array {
        $mime = get_post_mime_type($attachment_id);
        if (!isset($this->supported_types[$mime])) {
            return $metadata;
        }

        // Run optimization (non-blocking for the upload flow)
        $this->optimize_single($attachment_id);

        return $metadata;
    }

    // ──────────────────────────────────────────────
    //  Resize
    // ──────────────────────────────────────────────

    /**
     * Resize an image if it exceeds max dimensions.
     * Returns true if the image was resized.
     */
    public function resize_if_needed(string $file, int $max_width, int $max_height): bool {
        if (!file_exists($file)) return false;
        if ($max_width <= 0 && $max_height <= 0) return false;

        $size = @getimagesize($file);
        if (!$size) return false;

        $orig_w = $size[0];
        $orig_h = $size[1];

        // Nothing to do if already within bounds
        if ($orig_w <= $max_width && $orig_h <= $max_height) {
            return false;
        }

        // Use wp_get_image_editor for resizing (handles all types properly)
        $editor = wp_get_image_editor($file);
        if (is_wp_error($editor)) {
            return false;
        }

        $editor->resize($max_width, $max_height);
        $result = $editor->save($file);

        return !is_wp_error($result);
    }

    // ──────────────────────────────────────────────
    //  Settings
    // ──────────────────────────────────────────────

    /**
     * Get image optimizer settings.
     */
    public function get_settings(): array {
        $saved = get_option('wnb_image_settings', []);
        return wp_parse_args($saved, $this->defaults);
    }

    /**
     * Save image optimizer settings.
     */
    public function save_settings(array $data): bool {
        $clean = [
            'quality'       => max(1, min(100, (int) ($data['quality'] ?? $this->defaults['quality']))),
            'webp_enabled'  => !empty($data['webp_enabled']),
            'auto_optimize' => !empty($data['auto_optimize']),
            'max_width'     => max(0, (int) ($data['max_width'] ?? $this->defaults['max_width'])),
            'max_height'    => max(0, (int) ($data['max_height'] ?? $this->defaults['max_height'])),
            'strip_exif'    => !empty($data['strip_exif']),
        ];

        return update_option('wnb_image_settings', $clean);
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Bulk optimize a batch of images.
     */
    public function ajax_optimize_images(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $limit = max(1, min(50, (int) ($_POST['limit'] ?? 10)));
        $results = $this->optimize_bulk($limit);

        wp_send_json_success([
            'message'   => sprintf(
                '%d images optimized. %s saved. %d remaining.',
                $results['processed'],
                size_format($results['total_saved']),
                $results['remaining']
            ),
            'processed' => $results['processed'],
            'remaining' => $results['remaining'],
            'saved'     => $results['total_saved'],
            'results'   => $results['results'],
        ]);
    }

    /**
     * AJAX: Save image optimizer settings.
     */
    public function ajax_save_image_settings(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $data = [
            'quality'       => $_POST['quality'] ?? null,
            'webp_enabled'  => $_POST['webp_enabled'] ?? null,
            'auto_optimize' => $_POST['auto_optimize'] ?? null,
            'max_width'     => $_POST['max_width'] ?? null,
            'max_height'    => $_POST['max_height'] ?? null,
            'strip_exif'    => $_POST['strip_exif'] ?? null,
        ];

        $this->save_settings($data);

        wp_send_json_success([
            'message'  => 'Image optimizer settings saved.',
            'settings' => $this->get_settings(),
        ]);
    }

    /**
     * AJAX: Return image optimization stats.
     */
    public function ajax_image_stats(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        wp_send_json_success([
            'stats'    => $this->get_stats(),
            'settings' => $this->get_settings(),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Private GD Helpers
    // ──────────────────────────────────────────────

    /**
     * Load an image resource from file via GD.
     *
     * @return \GdImage|false
     */
    private function load_image(string $file, string $type) {
        switch ($type) {
            case 'jpeg':
                return @imagecreatefromjpeg($file);
            case 'png':
                return @imagecreatefrompng($file);
            default:
                return false;
        }
    }

    /**
     * Save an image resource back to file via GD.
     */
    private function save_image($image, string $file, string $type, int $quality): bool {
        switch ($type) {
            case 'jpeg':
                return imagejpeg($image, $file, $quality);
            case 'png':
                // PNG compression: 0 (none) to 9 (max). Map quality (1-100) inversely.
                // quality 82 → compression ~2, quality 50 → compression ~5
                $compression = (int) round((100 - $quality) / 11.11);
                $compression = max(0, min(9, $compression));
                // Preserve alpha channel
                imagealphablending($image, false);
                imagesavealpha($image, true);
                return imagepng($image, $file, $compression);
            default:
                return false;
        }
    }

    /**
     * Optimize all registered thumbnail sizes for an attachment.
     */
    private function optimize_thumbnails(int $attachment_id, string $type, int $quality, bool $webp_enabled): void {
        $metadata = wp_get_attachment_metadata($attachment_id);
        if (empty($metadata['sizes']) || !is_array($metadata['sizes'])) {
            return;
        }

        $file = get_attached_file($attachment_id);
        $dir = dirname($file);

        foreach ($metadata['sizes'] as $size_data) {
            if (empty($size_data['file'])) continue;

            $thumb_file = $dir . DIRECTORY_SEPARATOR . $size_data['file'];
            if (!file_exists($thumb_file)) continue;

            $image = $this->load_image($thumb_file, $type);
            if (!$image) continue;

            $this->save_image($image, $thumb_file, $type, $quality);

            // WebP for thumbnails
            if ($webp_enabled && function_exists('imagewebp')) {
                $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $thumb_file);
                if ($webp_path !== $thumb_file) {
                    $webp_quality = min(90, $quality + 5);
                    imagewebp($image, $webp_path, $webp_quality);
                }
            }

            imagedestroy($image);
        }
    }
}
