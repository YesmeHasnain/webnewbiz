<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;

class WpThemeController extends Controller
{
    public function __construct(private WpBridgeService $bridge) {}

    public function index(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listThemes($website));
    }

    public function activate(Request $request, $websiteId)
    {
        $request->validate(['theme' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->activateTheme($website, $request->theme));
    }

    public function install(Request $request, $websiteId)
    {
        $request->validate(['slug' => 'required|string|alpha_dash']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->installTheme($website, $request->slug));
    }

    public function update(Request $request, $websiteId)
    {
        $request->validate(['theme' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->updateTheme($website, $request->theme));
    }

    public function destroy(Request $request, $websiteId)
    {
        $request->validate(['theme' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->deleteTheme($website, $request->theme));
    }
}
