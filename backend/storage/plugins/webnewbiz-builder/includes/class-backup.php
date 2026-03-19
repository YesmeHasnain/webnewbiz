<?php
/**
 * WebNewBiz Backup Manager
 *
 * Full / database / files backups stored under wp-content/webnewbiz-backups/.
 * Supports creation, listing, downloading, deletion, and database restore.
 * Scheduled weekly backups via WP-Cron.
 */

if (!defined('ABSPATH')) exit;

class WebNewBiz_Backup {

    private static ?self $instance = null;

    /** Sub-directory inside wp-content */
    private const BACKUP_DIR_NAME = 'webnewbiz-backups';

    /** Maximum rows per INSERT batch to avoid memory issues */
    private const DUMP_BATCH_SIZE = 100;

    public static function instance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Download handler (runs early on admin_init)
        add_action('admin_init', [$this, 'handle_download']);

        // WP-Cron scheduled backup
        add_action('wnb_scheduled_backup', [$this, 'auto_backup']);

        // AJAX handlers
        add_action('wp_ajax_wnb_create_backup', [$this, 'ajax_create_backup']);
        add_action('wp_ajax_wnb_delete_backup', [$this, 'ajax_delete_backup']);
        add_action('wp_ajax_wnb_restore_backup', [$this, 'ajax_restore_backup']);
        add_action('wp_ajax_wnb_list_backups', [$this, 'ajax_list_backups']);
    }

    // ================================================================
    //  Backup directory
    // ================================================================

    /**
     * Return (and lazily create) the backup directory path.
     */
    public function get_backup_dir(): string {
        $dir = WP_CONTENT_DIR . '/' . self::BACKUP_DIR_NAME;

        if (!is_dir($dir)) {
            wp_mkdir_p($dir);

            // Deny direct web access
            file_put_contents($dir . '/.htaccess', "Order Allow,Deny\nDeny from all\n");
            file_put_contents($dir . '/index.php', "<?php\n// Silence is golden.\n");
        }

        return $dir;
    }

    // ================================================================
    //  Create Backup
    // ================================================================

    /**
     * Create a backup of the specified type.
     *
     * @param string $type 'full', 'database', or 'files'
     * @return array{id: string, filename: string, type: string, size_bytes: int, created_at: string, status: string}
     * @throws \RuntimeException
     */
    public function create_backup(string $type = 'full'): array {
        if (!in_array($type, ['full', 'database', 'files'], true)) {
            throw new \RuntimeException('Invalid backup type: ' . $type);
        }

        $dir       = $this->get_backup_dir();
        $id        = wp_generate_uuid4();
        $timestamp = date('Y-m-d_H-i-s');
        $site_slug = sanitize_title(get_bloginfo('name')) ?: 'site';

        switch ($type) {
            case 'database':
                $filename = sprintf('%s_db_%s.sql', $site_slug, $timestamp);
                $filepath = $dir . '/' . $filename;
                $sql      = $this->get_db_dump();
                if (file_put_contents($filepath, $sql) === false) {
                    throw new \RuntimeException('Failed to write database dump.');
                }
                break;

            case 'files':
                $filename = sprintf('%s_files_%s.zip', $site_slug, $timestamp);
                $filepath = $dir . '/' . $filename;
                $this->create_files_zip($filepath);
                break;

            case 'full':
            default:
                $filename = sprintf('%s_full_%s.zip', $site_slug, $timestamp);
                $filepath = $dir . '/' . $filename;
                $this->create_full_zip($filepath);
                break;
        }

        if (!file_exists($filepath)) {
            throw new \RuntimeException('Backup file was not created.');
        }

        $entry = [
            'id'         => $id,
            'filename'   => $filename,
            'type'       => $type,
            'size_bytes' => filesize($filepath),
            'created_at' => current_time('mysql'),
            'status'     => 'complete',
        ];

        // Persist metadata
        $backups   = get_option('wnb_backups', []);
        $backups[] = $entry;
        update_option('wnb_backups', $backups);

        // Activity log (if Security module is active)
        if (class_exists('WebNewBiz_Security')) {
            WebNewBiz_Security::instance()->log_activity('backup_created', sprintf(
                '%s backup created: %s (%s)',
                ucfirst($type),
                $filename,
                size_format($entry['size_bytes'])
            ));
        }

        return $entry;
    }

    // ================================================================
    //  Database Dump (pure PHP via $wpdb)
    // ================================================================

    /**
     * Build a complete SQL dump of every table in the database.
     */
    public function get_db_dump(): string {
        global $wpdb;

        $sql  = "-- WebNewBiz Database Backup\n";
        $sql .= "-- Generated: " . current_time('mysql') . "\n";
        $sql .= "-- Site: " . get_site_url() . "\n";
        $sql .= "-- WordPress: " . get_bloginfo('version') . "\n\n";
        $sql .= "SET NAMES utf8mb4;\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        // Get all tables in the current database
        $tables = $wpdb->get_col('SHOW TABLES');

        foreach ($tables as $table) {
            // DROP + CREATE
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Table: {$table}\n";
            $sql .= "-- --------------------------------------------------------\n\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            $create_row = $wpdb->get_row("SHOW CREATE TABLE `{$table}`", ARRAY_N);
            if ($create_row && isset($create_row[1])) {
                $sql .= $create_row[1] . ";\n\n";
            }

            // Data — batched INSERTs
            $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table}`");
            if ($total === 0) {
                $sql .= "-- (empty table)\n\n";
                continue;
            }

            $offset = 0;
            while ($offset < $total) {
                $rows = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM `{$table}` LIMIT %d OFFSET %d",
                        self::DUMP_BATCH_SIZE,
                        $offset
                    ),
                    ARRAY_A
                );

                if (empty($rows)) {
                    break;
                }

                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . $wpdb->_real_escape($value) . "'";
                        }
                    }
                    $columns = '`' . implode('`, `', array_keys($row)) . '`';
                    $sql .= "INSERT INTO `{$table}` ({$columns}) VALUES (" . implode(', ', $values) . ");\n";
                }

                $offset += self::DUMP_BATCH_SIZE;
            }

            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

        return $sql;
    }

    // ================================================================
    //  Zip Helpers
    // ================================================================

    /**
     * Create a zip of wp-content/ (excluding backups dir and cache).
     */
    private function create_files_zip(string $dest): void {
        if (!class_exists('ZipArchive')) {
            throw new \RuntimeException('ZipArchive extension is not available.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($dest, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Could not create zip file.');
        }

        $source = WP_CONTENT_DIR;
        $exclude = [
            self::BACKUP_DIR_NAME,
            'cache',
            'upgrade',
            'wflogs',
        ];

        $this->add_dir_to_zip($zip, $source, 'wp-content', $exclude);

        $zip->close();
    }

    /**
     * Create a full backup: database SQL + wp-content/ files.
     */
    private function create_full_zip(string $dest): void {
        if (!class_exists('ZipArchive')) {
            throw new \RuntimeException('ZipArchive extension is not available.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($dest, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Could not create zip file.');
        }

        // Add database dump
        $sql = $this->get_db_dump();
        $zip->addFromString('database.sql', $sql);

        // Add wp-content/ files
        $source = WP_CONTENT_DIR;
        $exclude = [
            self::BACKUP_DIR_NAME,
            'cache',
            'upgrade',
            'wflogs',
        ];

        $this->add_dir_to_zip($zip, $source, 'wp-content', $exclude);

        $zip->close();
    }

    /**
     * Recursively add a directory to a ZipArchive.
     */
    private function add_dir_to_zip(\ZipArchive $zip, string $source, string $prefix, array $exclude): void {
        $source = rtrim($source, '/\\');

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
        } catch (\Throwable $e) {
            return;
        }

        foreach ($iterator as $item) {
            $realPath     = $item->getRealPath();
            $relativePath = $prefix . '/' . substr($realPath, strlen($source) + 1);

            // Normalise separators
            $relativePath = str_replace('\\', '/', $relativePath);

            // Check exclusions against the first sub-directory
            $firstSegment = explode('/', ltrim(substr($relativePath, strlen($prefix) + 1), '/'))[0] ?? '';
            if (in_array($firstSegment, $exclude, true)) {
                continue;
            }

            // Skip very large files (>50MB) to prevent timeouts
            if ($item->isFile() && $item->getSize() > 50 * 1024 * 1024) {
                continue;
            }

            if ($item->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($realPath, $relativePath);
            }
        }
    }

    // ================================================================
    //  List / Delete / Download / Restore
    // ================================================================

    /**
     * List all backups, checking file existence.
     */
    public function list_backups(): array {
        $backups = get_option('wnb_backups', []);
        if (!is_array($backups)) {
            return [];
        }

        $dir     = $this->get_backup_dir();
        $cleaned = [];

        foreach ($backups as $backup) {
            $filepath = $dir . '/' . ($backup['filename'] ?? '');
            if (file_exists($filepath)) {
                // Refresh file size in case of discrepancy
                $backup['size_bytes'] = filesize($filepath);
                $backup['exists']     = true;
            } else {
                $backup['exists'] = false;
                $backup['status'] = 'missing';
            }
            $cleaned[] = $backup;
        }

        return $cleaned;
    }

    /**
     * Delete a backup by ID (file + metadata).
     */
    public function delete_backup(string $id): bool {
        $backups = get_option('wnb_backups', []);
        if (!is_array($backups)) {
            return false;
        }

        $dir   = $this->get_backup_dir();
        $found = false;

        foreach ($backups as $i => $backup) {
            if (($backup['id'] ?? '') === $id) {
                $filepath = $dir . '/' . ($backup['filename'] ?? '');
                if (file_exists($filepath)) {
                    @unlink($filepath);
                }
                unset($backups[$i]);
                $found = true;
                break;
            }
        }

        if ($found) {
            update_option('wnb_backups', array_values($backups));
        }

        return $found;
    }

    /**
     * Get a nonce-protected download URL for a backup.
     */
    public function get_download_url(string $id): string {
        return admin_url('admin.php') . '?' . http_build_query([
            'wnb_download_backup' => $id,
            '_wpnonce'            => wp_create_nonce('wnb_download_' . $id),
        ]);
    }

    /**
     * Serve a backup file for download (hooked to admin_init).
     */
    public function handle_download(): void {
        if (empty($_GET['wnb_download_backup'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        $id = sanitize_text_field($_GET['wnb_download_backup']);

        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'wnb_download_' . $id)) {
            wp_die('Invalid or expired download link.', 403);
        }

        $backups = get_option('wnb_backups', []);
        $dir     = $this->get_backup_dir();

        foreach ($backups as $backup) {
            if (($backup['id'] ?? '') === $id) {
                $filepath = $dir . '/' . ($backup['filename'] ?? '');
                if (!file_exists($filepath)) {
                    wp_die('Backup file not found.', 404);
                }

                $mime = str_ends_with($filepath, '.sql') ? 'application/sql' : 'application/zip';

                header('Content-Type: ' . $mime);
                header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                header('Content-Length: ' . filesize($filepath));
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');

                readfile($filepath);
                exit;
            }
        }

        wp_die('Backup not found.', 404);
    }

    /**
     * Restore a database backup by ID.
     * Only works for backups that contain SQL (type = 'database' or 'full').
     */
    public function restore_backup(string $id): bool {
        global $wpdb;

        $backups = get_option('wnb_backups', []);
        $dir     = $this->get_backup_dir();

        foreach ($backups as $backup) {
            if (($backup['id'] ?? '') !== $id) {
                continue;
            }

            $filepath = $dir . '/' . ($backup['filename'] ?? '');
            if (!file_exists($filepath)) {
                return false;
            }

            $sql_content = '';

            if ($backup['type'] === 'database') {
                // Direct SQL file
                $sql_content = file_get_contents($filepath);
            } elseif ($backup['type'] === 'full') {
                // Extract database.sql from zip
                if (!class_exists('ZipArchive')) {
                    return false;
                }
                $zip = new \ZipArchive();
                if ($zip->open($filepath) !== true) {
                    return false;
                }
                $sql_content = $zip->getFromName('database.sql');
                $zip->close();
                if ($sql_content === false) {
                    return false;
                }
            } else {
                // 'files' backups cannot be restored via this method
                return false;
            }

            if (empty($sql_content)) {
                return false;
            }

            // Split SQL into individual statements
            // Remove comments and split on semicolons
            $lines      = explode("\n", $sql_content);
            $statement   = '';
            $errors      = 0;

            foreach ($lines as $line) {
                $trimmed = trim($line);

                // Skip comments and empty lines
                if ($trimmed === '' || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '#')) {
                    continue;
                }

                $statement .= $line . "\n";

                // Execute when we hit a semicolon at end of line
                if (str_ends_with($trimmed, ';')) {
                    $result = $wpdb->query($statement);
                    if ($result === false) {
                        $errors++;
                    }
                    $statement = '';
                }
            }

            // Execute any remaining statement
            if (trim($statement) !== '') {
                $result = $wpdb->query($statement);
                if ($result === false) {
                    $errors++;
                }
            }

            if (class_exists('WebNewBiz_Security')) {
                WebNewBiz_Security::instance()->log_activity('backup_restored', sprintf(
                    'Database restored from backup: %s (errors: %d)',
                    $backup['filename'],
                    $errors
                ));
            }

            return ($errors === 0);
        }

        return false;
    }

    // ================================================================
    //  Scheduled Backup (WP-Cron)
    // ================================================================

    /**
     * Register the weekly cron event if not already scheduled.
     */
    public function schedule_auto_backup(): void {
        if (!wp_next_scheduled('wnb_scheduled_backup')) {
            wp_schedule_event(time(), 'weekly', 'wnb_scheduled_backup');
        }
    }

    /**
     * Unschedule the cron event.
     */
    public function unschedule_auto_backup(): void {
        $timestamp = wp_next_scheduled('wnb_scheduled_backup');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wnb_scheduled_backup');
        }
    }

    /**
     * Cron callback: create a database backup automatically.
     */
    public function auto_backup(): void {
        try {
            $this->create_backup('database');

            // Clean up old auto-backups: keep only the last 5
            $this->cleanup_old_backups(5);
        } catch (\Throwable $e) {
            if (class_exists('WebNewBiz_Security')) {
                WebNewBiz_Security::instance()->log_activity('backup_failed', $e->getMessage());
            }
        }
    }

    /**
     * Keep only the most recent $keep backups, delete the rest.
     */
    private function cleanup_old_backups(int $keep): void {
        $backups = get_option('wnb_backups', []);
        if (!is_array($backups) || count($backups) <= $keep) {
            return;
        }

        // Sort by created_at descending
        usort($backups, function ($a, $b) {
            return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
        });

        $to_remove = array_slice($backups, $keep);
        $to_keep   = array_slice($backups, 0, $keep);

        $dir = $this->get_backup_dir();
        foreach ($to_remove as $old) {
            $filepath = $dir . '/' . ($old['filename'] ?? '');
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
        }

        update_option('wnb_backups', $to_keep);
    }

    // ================================================================
    //  Total Backup Size
    // ================================================================

    /**
     * Sum the size of all existing backup files on disk.
     */
    public function get_total_backup_size(): int {
        $backups = get_option('wnb_backups', []);
        if (!is_array($backups)) {
            return 0;
        }

        $dir   = $this->get_backup_dir();
        $total = 0;

        foreach ($backups as $backup) {
            $filepath = $dir . '/' . ($backup['filename'] ?? '');
            if (file_exists($filepath)) {
                $total += filesize($filepath);
            }
        }

        return $total;
    }

    // ================================================================
    //  AJAX Handlers
    // ================================================================

    /**
     * Create a backup via AJAX.
     * POST: nonce, type (full|database|files)
     */
    public function ajax_create_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $type = sanitize_key($_POST['type'] ?? 'full');
        if (!in_array($type, ['full', 'database', 'files'], true)) {
            $type = 'full';
        }

        try {
            // Increase limits for large sites
            @set_time_limit(600);
            @ini_set('memory_limit', '512M');

            $backup = $this->create_backup($type);

            wp_send_json_success([
                'backup'     => $backup,
                'download'   => $this->get_download_url($backup['id']),
                'total_size' => size_format($this->get_total_backup_size()),
            ]);
        } catch (\Throwable $e) {
            wp_send_json_error([
                'message' => 'Backup failed: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a backup via AJAX.
     * POST: nonce, id
     */
    public function ajax_delete_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $id = sanitize_text_field($_POST['id'] ?? '');
        if (empty($id)) {
            wp_send_json_error(['message' => 'No backup ID provided.']);
        }

        if ($this->delete_backup($id)) {
            wp_send_json_success([
                'message'    => 'Backup deleted successfully.',
                'total_size' => size_format($this->get_total_backup_size()),
            ]);
        } else {
            wp_send_json_error(['message' => 'Backup not found.']);
        }
    }

    /**
     * Restore a database backup via AJAX.
     * POST: nonce, id
     */
    public function ajax_restore_backup(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $id = sanitize_text_field($_POST['id'] ?? '');
        if (empty($id)) {
            wp_send_json_error(['message' => 'No backup ID provided.']);
        }

        try {
            @set_time_limit(600);
            @ini_set('memory_limit', '512M');

            $result = $this->restore_backup($id);

            if ($result) {
                wp_send_json_success([
                    'message' => 'Database restored successfully. You may need to re-login.',
                ]);
            } else {
                wp_send_json_error([
                    'message' => 'Restore completed with errors or backup not found.',
                ]);
            }
        } catch (\Throwable $e) {
            wp_send_json_error([
                'message' => 'Restore failed: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * List all backups via AJAX.
     */
    public function ajax_list_backups(): void {
        check_ajax_referer('wnb_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $backups = $this->list_backups();

        // Attach download URLs
        foreach ($backups as &$backup) {
            if (!empty($backup['id'])) {
                $backup['download_url'] = $this->get_download_url($backup['id']);
            }
        }
        unset($backup);

        wp_send_json_success([
            'backups'    => $backups,
            'total_size' => size_format($this->get_total_backup_size()),
            'backup_dir' => self::BACKUP_DIR_NAME,
            'next_scheduled' => wp_next_scheduled('wnb_scheduled_backup')
                ? date('Y-m-d H:i:s', wp_next_scheduled('wnb_scheduled_backup'))
                : null,
        ]);
    }
}
