<?php
/**
 * WebNewBiz Database Optimizer
 *
 * Cleanup revisions, drafts, trash, spam, orphaned meta, expired transients.
 * Optimize tables. Schedule automated weekly cleanups.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Database {

    private static ?self $instance = null;

    /** Supported cleanup types */
    private array $cleanup_types = [
        'revisions',
        'auto_drafts',
        'trashed_posts',
        'spam_comments',
        'trashed_comments',
        'expired_transients',
        'all_transients',
        'orphaned_postmeta',
        'orphaned_commentmeta',
    ];

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // AJAX handlers
        add_action('wp_ajax_wnb_db_cleanup', [$this, 'ajax_db_cleanup']);
        add_action('wp_ajax_wnb_db_optimize', [$this, 'ajax_db_optimize']);
        add_action('wp_ajax_wnb_db_stats', [$this, 'ajax_db_stats']);

        // Scheduled cleanup hook
        add_action('wnb_scheduled_cleanup', [$this, 'scheduled_cleanup_handler']);
    }

    // ──────────────────────────────────────────────
    //  Cleanup Stats
    // ──────────────────────────────────────────────

    /**
     * Get counts for every cleanable item type.
     */
    public function get_cleanup_stats(): array {
        global $wpdb;

        return [
            'revisions' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'revision'"
            ),
            'auto_drafts' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'auto-draft'"
            ),
            'trashed_posts' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'trash'"
            ),
            'spam_comments' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = 'spam'"
            ),
            'trashed_comments' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = 'trash'"
            ),
            'expired_transients' => (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->options}
                     WHERE option_name LIKE %s
                       AND option_value < %d",
                    $wpdb->esc_like('_transient_timeout_') . '%',
                    time()
                )
            ),
            'all_transients' => (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->options}
                     WHERE option_name LIKE %s",
                    '%' . $wpdb->esc_like('_transient_') . '%'
                )
            ),
            'orphaned_postmeta' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->postmeta}
                 WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})"
            ),
            'orphaned_commentmeta' => (int) $wpdb->get_var(
                "SELECT COUNT(*) FROM {$wpdb->commentmeta}
                 WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->comments})"
            ),
        ];
    }

    // ──────────────────────────────────────────────
    //  Cleanup Operations
    // ──────────────────────────────────────────────

    /**
     * Delete items by type. Returns the number of rows deleted.
     */
    public function cleanup(string $type): int {
        global $wpdb;

        switch ($type) {
            case 'revisions':
                // Delete revision postmeta first, then revisions
                $revision_ids = $wpdb->get_col(
                    "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'revision'"
                );
                if (!empty($revision_ids)) {
                    $placeholders = implode(',', array_fill(0, count($revision_ids), '%d'));
                    $wpdb->query(
                        $wpdb->prepare(
                            "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ($placeholders)",
                            ...$revision_ids
                        )
                    );
                    return (int) $wpdb->query(
                        "DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'"
                    );
                }
                return 0;

            case 'auto_drafts':
                $draft_ids = $wpdb->get_col(
                    "SELECT ID FROM {$wpdb->posts} WHERE post_status = 'auto-draft'"
                );
                if (!empty($draft_ids)) {
                    $placeholders = implode(',', array_fill(0, count($draft_ids), '%d'));
                    $wpdb->query(
                        $wpdb->prepare(
                            "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ($placeholders)",
                            ...$draft_ids
                        )
                    );
                    return (int) $wpdb->query(
                        "DELETE FROM {$wpdb->posts} WHERE post_status = 'auto-draft'"
                    );
                }
                return 0;

            case 'trashed_posts':
                $trash_ids = $wpdb->get_col(
                    "SELECT ID FROM {$wpdb->posts} WHERE post_status = 'trash'"
                );
                if (!empty($trash_ids)) {
                    $placeholders = implode(',', array_fill(0, count($trash_ids), '%d'));
                    $wpdb->query(
                        $wpdb->prepare(
                            "DELETE FROM {$wpdb->postmeta} WHERE post_id IN ($placeholders)",
                            ...$trash_ids
                        )
                    );
                    return (int) $wpdb->query(
                        "DELETE FROM {$wpdb->posts} WHERE post_status = 'trash'"
                    );
                }
                return 0;

            case 'spam_comments':
                $spam_ids = $wpdb->get_col(
                    "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved = 'spam'"
                );
                if (!empty($spam_ids)) {
                    $placeholders = implode(',', array_fill(0, count($spam_ids), '%d'));
                    $wpdb->query(
                        $wpdb->prepare(
                            "DELETE FROM {$wpdb->commentmeta} WHERE comment_id IN ($placeholders)",
                            ...$spam_ids
                        )
                    );
                    return (int) $wpdb->query(
                        "DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'"
                    );
                }
                return 0;

            case 'trashed_comments':
                $trash_comment_ids = $wpdb->get_col(
                    "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved = 'trash'"
                );
                if (!empty($trash_comment_ids)) {
                    $placeholders = implode(',', array_fill(0, count($trash_comment_ids), '%d'));
                    $wpdb->query(
                        $wpdb->prepare(
                            "DELETE FROM {$wpdb->commentmeta} WHERE comment_id IN ($placeholders)",
                            ...$trash_comment_ids
                        )
                    );
                    return (int) $wpdb->query(
                        "DELETE FROM {$wpdb->comments} WHERE comment_approved = 'trash'"
                    );
                }
                return 0;

            case 'expired_transients':
                // Get expired transient names
                $expired = $wpdb->get_col(
                    $wpdb->prepare(
                        "SELECT option_name FROM {$wpdb->options}
                         WHERE option_name LIKE %s
                           AND option_value < %d",
                        $wpdb->esc_like('_transient_timeout_') . '%',
                        time()
                    )
                );

                $count = 0;
                foreach ($expired as $timeout_name) {
                    // Delete both the timeout entry and the value entry
                    $transient_name = str_replace('_transient_timeout_', '_transient_', $timeout_name);
                    $wpdb->query(
                        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name = %s", $timeout_name)
                    );
                    $wpdb->query(
                        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name = %s", $transient_name)
                    );
                    $count++;
                }
                return $count;

            case 'all_transients':
                return (int) $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM {$wpdb->options}
                         WHERE option_name LIKE %s
                            OR option_name LIKE %s",
                        $wpdb->esc_like('_transient_') . '%',
                        $wpdb->esc_like('_site_transient_') . '%'
                    )
                );

            case 'orphaned_postmeta':
                return (int) $wpdb->query(
                    "DELETE FROM {$wpdb->postmeta}
                     WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})"
                );

            case 'orphaned_commentmeta':
                return (int) $wpdb->query(
                    "DELETE FROM {$wpdb->commentmeta}
                     WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->comments})"
                );

            default:
                return 0;
        }
    }

    /**
     * Run all cleanup types and return total deleted.
     */
    public function cleanup_all(): array {
        $results = [];
        $total = 0;

        foreach ($this->cleanup_types as $type) {
            // Skip 'all_transients' when running all — just expired is safer
            if ($type === 'all_transients') continue;

            $count = $this->cleanup($type);
            $results[$type] = $count;
            $total += $count;
        }

        $results['total'] = $total;
        return $results;
    }

    // ──────────────────────────────────────────────
    //  Table Info & Optimization
    // ──────────────────────────────────────────────

    /**
     * Get sizes of all tables in the current database.
     */
    public function get_table_sizes(): array {
        global $wpdb;

        $db_name = DB_NAME;

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT
                    TABLE_NAME AS table_name,
                    TABLE_ROWS AS `rows`,
                    ROUND(DATA_LENGTH / 1048576, 2) AS data_size_mb,
                    ROUND(INDEX_LENGTH / 1048576, 2) AS index_size_mb
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = %s
                   AND TABLE_TYPE = 'BASE TABLE'
                 ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC",
                $db_name
            ),
            ARRAY_A
        );

        if (!is_array($rows)) return [];

        return array_map(function (array $row): array {
            return [
                'table_name'   => $row['table_name'],
                'rows'         => (int) $row['rows'],
                'data_size_mb' => (float) $row['data_size_mb'],
                'index_size_mb'=> (float) $row['index_size_mb'],
            ];
        }, $rows);
    }

    /**
     * Run OPTIMIZE TABLE on all WordPress tables.
     */
    public function optimize_tables(): array {
        global $wpdb;

        $tables = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT TABLE_NAME
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = %s
                   AND TABLE_TYPE = 'BASE TABLE'
                   AND TABLE_NAME LIKE %s",
                DB_NAME,
                $wpdb->esc_like($wpdb->prefix) . '%'
            )
        );

        $results = [];

        foreach ($tables as $table) {
            // Sanitize table name — only allow word chars and underscores
            $safe_table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
            if (empty($safe_table)) continue;

            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $res = $wpdb->get_results("OPTIMIZE TABLE `{$safe_table}`", ARRAY_A);

            $results[] = [
                'table'   => $safe_table,
                'status'  => !empty($res[0]['Msg_text']) ? $res[0]['Msg_text'] : 'OK',
            ];
        }

        return $results;
    }

    /**
     * Get total database size in MB.
     */
    public function get_total_db_size(): float {
        global $wpdb;

        $size = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ROUND(SUM(DATA_LENGTH + INDEX_LENGTH) / 1048576, 2)
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = %s",
                DB_NAME
            )
        );

        return (float) ($size ?? 0);
    }

    // ──────────────────────────────────────────────
    //  Scheduled Cleanup
    // ──────────────────────────────────────────────

    /**
     * Schedule a weekly automatic cleanup.
     */
    public function schedule_cleanup(): bool {
        if (wp_next_scheduled('wnb_scheduled_cleanup')) {
            return true; // Already scheduled
        }

        return (bool) wp_schedule_event(time(), 'weekly', 'wnb_scheduled_cleanup');
    }

    /**
     * Remove the scheduled cleanup.
     */
    public function unschedule_cleanup(): void {
        $timestamp = wp_next_scheduled('wnb_scheduled_cleanup');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wnb_scheduled_cleanup');
        }
        wp_clear_scheduled_hook('wnb_scheduled_cleanup');
    }

    /**
     * Handler for the scheduled cron event.
     */
    public function scheduled_cleanup_handler(): void {
        $results = $this->cleanup_all();

        // Log the cleanup for diagnostics
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[WebNewBiz] Scheduled DB cleanup: ' . wp_json_encode($results));
        }

        // Store last run info
        update_option('wnb_last_db_cleanup', [
            'time'    => current_time('mysql'),
            'results' => $results,
        ]);
    }

    // ──────────────────────────────────────────────
    //  AJAX Handlers
    // ──────────────────────────────────────────────

    /**
     * AJAX: Run cleanup by type (or 'all').
     */
    public function ajax_db_cleanup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $type = sanitize_text_field($_POST['type'] ?? '');

        if ($type === 'all') {
            $results = $this->cleanup_all();
            wp_send_json_success([
                'message' => sprintf('Cleanup complete. %d items removed.', $results['total']),
                'results' => $results,
            ]);
            return;
        }

        if (!in_array($type, $this->cleanup_types, true)) {
            wp_send_json_error(['message' => 'Invalid cleanup type: ' . $type]);
            return;
        }

        $count = $this->cleanup($type);

        wp_send_json_success([
            'message' => sprintf('%d %s cleaned up.', $count, str_replace('_', ' ', $type)),
            'type'    => $type,
            'count'   => $count,
            'stats'   => $this->get_cleanup_stats(),
        ]);
    }

    /**
     * AJAX: Optimize all database tables.
     */
    public function ajax_db_optimize(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $results = $this->optimize_tables();

        wp_send_json_success([
            'message' => sprintf('%d tables optimized.', count($results)),
            'results' => $results,
            'db_size' => $this->get_total_db_size(),
        ]);
    }

    /**
     * AJAX: Return all cleanup stats + table info.
     */
    public function ajax_db_stats(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Insufficient permissions.'], 403);
        }

        $stats = $this->get_cleanup_stats();
        $tables = $this->get_table_sizes();
        $total_size = $this->get_total_db_size();
        $last_cleanup = get_option('wnb_last_db_cleanup', null);
        $next_scheduled = wp_next_scheduled('wnb_scheduled_cleanup');

        wp_send_json_success([
            'cleanup_stats'  => $stats,
            'tables'         => $tables,
            'total_size_mb'  => $total_size,
            'last_cleanup'   => $last_cleanup,
            'next_scheduled' => $next_scheduled ? date('Y-m-d H:i:s', $next_scheduled) : null,
        ]);
    }
}
