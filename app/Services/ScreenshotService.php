<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScreenshotService
{
    public function capture(Website $website): array
    {
        try {
            $url = $website->url;
            $filename = "screenshots/{$website->id}_{$website->subdomain}.png";

            // Use a free screenshot API or placeholder
            $screenshotUrl = "https://image.thum.io/get/width/1280/crop/800/noanimate/" . urlencode($url);

            $response = Http::timeout(30)->get($screenshotUrl);

            if ($response->successful()) {
                Storage::disk('public')->put($filename, $response->body());
                $website->update(['screenshot_path' => $filename]);
                return ['success' => true, 'data' => ['path' => $filename]];
            }

            return ['success' => false, 'message' => 'Screenshot capture failed'];
        } catch (\Exception $e) {
            Log::warning("Screenshot failed for {$website->subdomain}: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getScreenshotUrl(Website $website): ?string
    {
        if ($website->screenshot_path) {
            return Storage::disk('public')->url($website->screenshot_path);
        }
        return null;
    }
}
