<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteTheme;
use App\Services\WordPressService;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function __construct(private WordPressService $wpService) {}

    public function index(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'data' => $website->themes]);
    }

    public function install(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'slug' => 'required|string|max:255',
            'name' => 'sometimes|string|max:255',
        ]);

        $result = $this->wpService->installTheme($website, $validated['slug']);

        if ($result['success']) {
            // Deactivate other themes
            WebsiteTheme::where('website_id', $website->id)->update(['is_active' => false]);
            $theme = WebsiteTheme::updateOrCreate(
                ['website_id' => $website->id, 'slug' => $validated['slug']],
                ['name' => $validated['name'] ?? $validated['slug'], 'is_active' => true]
            );
            return response()->json(['success' => true, 'data' => $theme], 201);
        }

        return response()->json($result, 422);
    }

    public function activate(Request $request, Website $website, string $slug)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->wpService->activateTheme($website, $slug);

        if ($result['success']) {
            WebsiteTheme::where('website_id', $website->id)->update(['is_active' => false]);
            WebsiteTheme::where('website_id', $website->id)->where('slug', $slug)->update(['is_active' => true]);
        }

        return response()->json($result);
    }
}
