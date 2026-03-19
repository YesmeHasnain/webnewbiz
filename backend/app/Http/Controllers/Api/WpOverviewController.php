<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WpBridgeService;
use Illuminate\Http\Request;

class WpOverviewController extends Controller
{
    private const OPTION_WHITELIST = [
        'blogname', 'blogdescription', 'admin_email',
        'timezone_string', 'date_format', 'time_format',
        'posts_per_page', 'permalink_structure',
    ];

    public function __construct(private WpBridgeService $bridge) {}

    public function index(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->getOverview($website));
    }

    public function updates(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->checkUpdates($website));
    }

    public function clearCache(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->clearCache($website));
    }

    public function pages(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($this->bridge->listPages($website));
    }

    public function options(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $keys = $request->query('keys', []);
        if (is_string($keys)) $keys = explode(',', $keys);
        $keys = array_intersect($keys, self::OPTION_WHITELIST);
        return response()->json($this->bridge->getOptions($website, $keys));
    }

    public function updateOptions(Request $request, $websiteId)
    {
        $request->validate(['options' => 'required|array']);
        $website = $request->user()->websites()->findOrFail($websiteId);
        $filtered = array_intersect_key($request->options, array_flip(self::OPTION_WHITELIST));
        return response()->json($this->bridge->setOptions($website, $filtered));
    }
}
