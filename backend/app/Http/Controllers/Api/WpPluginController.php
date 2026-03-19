<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;

class WpPluginController extends Controller
{
    public function __construct(private WpBridgeService $bridge) {}

    public function index(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listPlugins($website));
    }

    public function activate(Request $request, $websiteId)
    {
        $request->validate(['plugin' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->activatePlugin($website, $request->plugin));
    }

    public function deactivate(Request $request, $websiteId)
    {
        $request->validate(['plugin' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->deactivatePlugin($website, $request->plugin));
    }

    public function install(Request $request, $websiteId)
    {
        $request->validate(['slug' => 'required|string|alpha_dash']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->installPlugin($website, $request->slug));
    }

    public function update(Request $request, $websiteId)
    {
        $request->validate(['plugin' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->updatePlugin($website, $request->plugin));
    }

    public function destroy(Request $request, $websiteId)
    {
        $request->validate(['plugin' => 'required|string']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->deletePlugin($website, $request->plugin));
    }
}
