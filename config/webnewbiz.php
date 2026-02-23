<?php

return [
    'name' => env('APP_NAME', 'Webnewbiz'),
    'domain' => env('PLATFORM_DOMAIN', 'webnewbiz.com'),
    'subdomain_suffix' => env('PLATFORM_SUBDOMAIN_SUFFIX', '.webnewbiz.com'),

    'defaults' => [
        'wordpress_version' => 'latest',
        'php_version' => env('CLOUDPANEL_DEFAULT_PHP', '8.2'),
        'elementor_version' => 'latest',
    ],

    'limits' => [
        'free' => ['websites' => 1, 'storage_gb' => 1, 'bandwidth_gb' => 10],
        'starter' => ['websites' => 5, 'storage_gb' => 10, 'bandwidth_gb' => 100],
        'business' => ['websites' => 20, 'storage_gb' => 50, 'bandwidth_gb' => 500],
        'agency' => ['websites' => 100, 'storage_gb' => 200, 'bandwidth_gb' => 2000],
    ],

    'backup' => [
        'retention_days' => 30,
        'max_backups_per_site' => 10,
        'storage_path' => storage_path('app/backups'),
    ],

    'screenshot' => [
        'storage_path' => storage_path('app/screenshots'),
        'width' => 1280,
        'height' => 800,
    ],
];
