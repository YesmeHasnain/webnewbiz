<?php

return [
    'php_version' => env('CLOUDPANEL_DEFAULT_PHP', '8.2'),
    'vhost_template' => 'WordPress',
    'database_charset' => 'utf8mb4',
    'database_collation' => 'utf8mb4_unicode_ci',

    'paths' => [
        'sites' => '/home',
        'clp_cli' => '/usr/bin/clpctl',
        'wp_cli' => '/usr/local/bin/wp',
        'acme' => '/root/.acme.sh/acme.sh',
    ],

    'firewall' => [
        'allowed_ports' => [22, 80, 443, 8443],
    ],
];
