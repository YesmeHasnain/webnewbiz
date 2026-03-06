<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$action = $argv[1] ?? 'list';

$ws = App\Models\Website::all();
echo "Found {$ws->count()} websites:\n";
foreach ($ws as $w) {
    echo "  #{$w->id} | {$w->subdomain} | db={$w->wp_db_name} | {$w->status}\n";
}

if ($action === 'delete') {
    $htdocs = config('webnewbiz.xampp_htdocs', 'C:/xampp/htdocs');
    $dbHost = config('database.connections.mysql.host', '127.0.0.1');
    $dbUser = config('database.connections.mysql.username', 'root');
    $dbPass = config('database.connections.mysql.password', '');

    foreach ($ws as $w) {
        // Delete WordPress directory
        $sitePath = $htdocs . '/' . $w->subdomain;
        if (is_dir($sitePath)) {
            echo "Deleting dir: {$sitePath}\n";
            // Use system command for fast recursive delete on Windows
            exec("rmdir /s /q \"" . str_replace('/', '\\', $sitePath) . "\" 2>NUL");
        }

        // Drop database
        if ($w->wp_db_name) {
            try {
                $pdo = new PDO("mysql:host={$dbHost}", $dbUser, $dbPass);
                $pdo->exec("DROP DATABASE IF EXISTS `{$w->wp_db_name}`");
                echo "Dropped db: {$w->wp_db_name}\n";
            } catch (Exception $e) {
                echo "DB drop failed: {$e->getMessage()}\n";
            }
        }
    }

    // Delete all website records and related data
    App\Models\Domain::query()->delete();
    App\Models\Website::query()->delete();
    if (class_exists('App\Models\ChatMessage')) {
        App\Models\ChatMessage::query()->delete();
    }

    echo "\nAll websites, domains, and databases deleted.\n";

    // Also clean up master sites
    $masterDirs = glob($htdocs . '/master-*');
    foreach ($masterDirs as $dir) {
        if (is_dir($dir)) {
            echo "Deleting master: {$dir}\n";
            exec("rmdir /s /q \"" . str_replace('/', '\\', $dir) . "\" 2>NUL");
            // Drop master db
            $dbName = 'wp_' . str_replace('-', '_', basename($dir));
            try {
                $pdo = new PDO("mysql:host={$dbHost}", $dbUser, $dbPass);
                $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
                echo "Dropped master db: {$dbName}\n";
            } catch (Exception $e) {}
        }
    }
}
