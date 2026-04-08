<?php
/**
 * Change History Manager - Undo/Redo for Elementor page data
 * Stores snapshots in wp_options as a stack (LIFO), max 10 per page
 */
class AICopilot_History
{
    private static int $maxSnapshots = 10;

    /**
     * Save current page state before making changes
     */
    public static function saveSnapshot(int $pageId): bool
    {
        if (!$pageId) return false;

        $currentData = get_post_meta($pageId, '_elementor_data', true);
        if (!$currentData) return false;

        $optionKey = self::getOptionKey($pageId);
        $stack = get_option($optionKey, []);
        if (!is_array($stack)) $stack = [];

        // Push current state onto stack
        $stack[] = [
            'data' => $currentData,
            'time' => time(),
            'label' => 'Snapshot ' . count($stack) + 1,
        ];

        // Trim to max snapshots (keep most recent)
        if (count($stack) > self::$maxSnapshots) {
            $stack = array_slice($stack, -self::$maxSnapshots);
        }

        update_option($optionKey, $stack, false); // false = no autoload
        return true;
    }

    /**
     * Undo last change - restore previous snapshot
     */
    public static function undo(int $pageId): array
    {
        if (!$pageId) return ['success' => false, 'error' => 'No page ID'];

        $optionKey = self::getOptionKey($pageId);
        $stack = get_option($optionKey, []);

        if (empty($stack)) {
            return ['success' => false, 'error' => 'Nothing to undo'];
        }

        // Pop last snapshot
        $snapshot = array_pop($stack);
        update_option($optionKey, $stack, false);

        // Restore Elementor data
        update_post_meta($pageId, '_elementor_data', wp_slash($snapshot['data']));

        // Clear Elementor cache
        AICopilot_Executor::execute('clear_cache', ['page_id' => $pageId]);

        $remaining = count($stack);
        return [
            'success' => true,
            'message' => 'Undo successful. ' . $remaining . ' undo step(s) remaining.',
            'remaining' => $remaining,
        ];
    }

    /**
     * Get number of available undo steps
     */
    public static function getUndoCount(int $pageId): int
    {
        $stack = get_option(self::getOptionKey($pageId), []);
        return is_array($stack) ? count($stack) : 0;
    }

    /**
     * Clear all history for a page
     */
    public static function clearHistory(int $pageId): void
    {
        delete_option(self::getOptionKey($pageId));
    }

    private static function getOptionKey(int $pageId): string
    {
        return 'aicopilot_history_' . $pageId;
    }
}
