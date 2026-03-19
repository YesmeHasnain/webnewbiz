<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Website;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

$sites = Website::where('status', 'active')->whereNotNull('wp_auto_login_token')->get();
$allPlugins = [];

foreach ($sites as $site) {
    try {
        $token = Crypt::decrypt($site->wp_auto_login_token);
        $r = Http::timeout(20)
            ->withHeaders(['X-Bridge-Token' => $token])
            ->asForm()
            ->post($site->url . '/wp-site-manager.php', ['action' => 'plugins.list']);

        $data = $r->json('data', []);
        foreach ($data as $p) {
            $key = $p['slug'] . '|' . $p['version'];
            if (!isset($allPlugins[$key])) {
                $allPlugins[$key] = [
                    'slug' => $p['slug'],
                    'name' => $p['name'],
                    'version' => $p['version'],
                    'file' => $p['file'],
                    'count' => 0,
                ];
            }
            $allPlugins[$key]['count']++;
        }
    } catch (\Exception $e) {
        echo "ERR: {$site->slug} - {$e->getMessage()}\n";
    }
}

// Sort by count desc
usort($allPlugins, fn($a, $b) => $b['count'] - $a['count']);

echo str_pad('PLUGIN', 40) . str_pad('VERSION', 15) . str_pad('SLUG', 30) . 'SITES' . PHP_EOL;
echo str_repeat('-', 90) . PHP_EOL;
foreach ($allPlugins as $p) {
    echo str_pad($p['name'], 40) . str_pad($p['version'], 15) . str_pad($p['slug'], 30) . $p['count'] . PHP_EOL;
}
echo PHP_EOL . "Total unique plugins: " . count($allPlugins) . PHP_EOL;
