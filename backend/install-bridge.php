<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Website;
use Illuminate\Support\Facades\Crypt;

$sites = Website::where('status', 'active')->get();

foreach ($sites as $site) {
    $sitePath = 'C:\\xampp\\htdocs\\' . $site->slug;

    if (!is_dir($sitePath)) {
        echo "SKIP: {$site->slug} (no dir)\n";
        continue;
    }

    if (empty($site->wp_auto_login_token)) {
        echo "SKIP: {$site->slug} (no token)\n";
        continue;
    }
    $token = Crypt::decrypt($site->wp_auto_login_token);
    $stub = file_get_contents(base_path('stubs/wp-site-manager.php'));
    $bridge = str_replace('__BRIDGE_TOKEN__', $token, $stub);
    file_put_contents($sitePath . '/wp-site-manager.php', $bridge);
    echo "INSTALLED: {$site->slug}\n";
}

echo "Done.\n";
