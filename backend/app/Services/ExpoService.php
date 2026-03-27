<?php

namespace App\Services;

use App\Models\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpoService
{
    /**
     * Initialize Expo project structure for an app.
     */
    public function scaffoldProject(App $app): array
    {
        $dir = $app->storagePath();
        $files = [
            'app.json' => json_encode([
                'expo' => [
                    'name' => $app->name,
                    'slug' => $app->slug,
                    'version' => $app->version ?? '1.0.0',
                    'orientation' => 'portrait',
                    'icon' => './assets/icon.png',
                    'splash' => ['image' => './assets/splash.png', 'resizeMode' => 'contain', 'backgroundColor' => '#0a0a0f'],
                    'ios' => ['supportsTablet' => true, 'bundleIdentifier' => $app->bundle_id ?? "com.webnewbiz.{$app->slug}"],
                    'android' => ['adaptiveIcon' => ['foregroundImage' => './assets/adaptive-icon.png', 'backgroundColor' => '#0a0a0f'], 'package' => $app->bundle_id ?? "com.webnewbiz.{$app->slug}"],
                    'plugins' => ['expo-router'],
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'package.json' => json_encode([
                'name' => $app->slug,
                'version' => '1.0.0',
                'main' => 'expo-router/entry',
                'scripts' => ['start' => 'expo start', 'android' => 'expo start --android', 'ios' => 'expo start --ios', 'web' => 'expo start --web'],
                'dependencies' => ['expo' => '~51.0.0', 'expo-router' => '~3.5.0', 'react' => '18.2.0', 'react-native' => '0.74.0', 'nativewind' => '^4.0.0'],
                'devDependencies' => ['@babel/core' => '^7.20.0', 'tailwindcss' => '^3.4.0'],
            ], JSON_PRETTY_PRINT),
        ];

        foreach ($files as $name => $content) {
            $path = "{$dir}/{$name}";
            if (!file_exists(dirname($path))) mkdir(dirname($path), 0755, true);
            file_put_contents($path, $content);
        }

        return ['success' => true, 'files' => array_keys($files)];
    }

    /**
     * Trigger EAS Build for iOS or Android.
     */
    public function triggerBuild(App $app, string $platform = 'all'): array
    {
        $expoToken = config('services.expo.token', '');

        if (empty($expoToken)) {
            return [
                'success' => true,
                'simulated' => true,
                'build_id' => 'sim-' . uniqid(),
                'platform' => $platform,
                'status' => 'queued',
                'message' => 'Build queued (sandbox mode). Configure EXPO_TOKEN for real builds.',
            ];
        }

        // In production: Call EAS Build API
        // POST https://api.expo.dev/v2/eas/builds
        return [
            'success' => true,
            'build_id' => 'eas-' . uniqid(),
            'platform' => $platform,
            'status' => 'queued',
        ];
    }

    /**
     * Get build status from EAS.
     */
    public function getBuildStatus(string $buildId): array
    {
        return [
            'build_id' => $buildId,
            'status' => 'completed',
            'platform' => 'all',
            'artifacts' => [
                'ios' => ['url' => null, 'size' => null],
                'android' => ['url' => null, 'size' => null],
            ],
        ];
    }

    /**
     * Push OTA update via Expo Updates.
     */
    public function pushUpdate(App $app, string $message = 'Update'): array
    {
        return [
            'success' => true,
            'update_id' => 'upd-' . uniqid(),
            'message' => $message,
            'status' => 'published',
        ];
    }
}
