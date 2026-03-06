<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'digitalocean' => [
        'api_token' => env('DIGITALOCEAN_API_TOKEN'),
        'default_region' => env('DIGITALOCEAN_DEFAULT_REGION', 'nyc3'),
        'default_size' => env('DIGITALOCEAN_DEFAULT_SIZE', 's-2vcpu-4gb'),
        'default_image' => env('DIGITALOCEAN_DEFAULT_IMAGE', 'ubuntu-22-04-x64'),
    ],

    'cloudflare' => [
        'api_token' => env('CLOUDFLARE_API_TOKEN'),
        'zone_id' => env('CLOUDFLARE_ZONE_ID'),
        'account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'api_url' => 'https://generativelanguage.googleapis.com/v1beta',
    ],

    'ideogram' => [
        'api_key' => env('IDEOGRAM_API_KEY'),
        'api_url' => 'https://api.ideogram.ai',
    ],

    'unsplash' => [
        'access_key' => env('UNSPLASH_ACCESS_KEY'),
    ],

    'pexels' => [
        'api_key' => env('PEXELS_API_KEY'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
        'api_url' => 'https://api.anthropic.com/v1',
    ],

    'glm' => [
        'api_key' => env('GLM_API_KEY'),
        'model' => env('GLM_MODEL', 'glm-4.5-flash'),
        'api_url' => 'https://open.bigmodel.cn/api/paas/v4',
    ],

];
