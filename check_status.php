<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$w = App\Models\Website::latest()->first();
if ($w) {
    echo "ID: {$w->id} | Status: {$w->status} | Theme: {$w->ai_theme} | Sub: {$w->subdomain}\n";
} else {
    echo "No websites found\n";
}
