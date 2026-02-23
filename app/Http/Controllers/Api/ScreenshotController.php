<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\ScreenshotService;
use Illuminate\Http\Request;

class ScreenshotController extends Controller
{
    public function __construct(private ScreenshotService $screenshotService) {}

    public function capture(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->screenshotService->capture($website);
        return response()->json($result);
    }

    public function show(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $url = $this->screenshotService->getScreenshotUrl($website);

        return response()->json([
            'success' => true,
            'data' => ['screenshot_url' => $url],
        ]);
    }
}
