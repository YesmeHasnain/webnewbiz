<?php

namespace App\Http\Controllers;

use App\Models\Website;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $websiteQuery = Website::query();
        } else {
            $websiteQuery = $user->websites();
        }

        $websites = (clone $websiteQuery)->latest()->limit(6)->get();
        $plan = $user->currentPlan();

        $totalWebsites = (clone $websiteQuery)->count();
        $activeWebsites = (clone $websiteQuery)->where('status', 'active')->count();
        $buildingWebsites = (clone $websiteQuery)->where('status', 'provisioning')->count();
        $errorWebsites = (clone $websiteQuery)->where('status', 'error')->count();
        $maxWebsites = $plan->max_websites ?? 0;

        // Recent websites (last 5 created)
        $recentWebsites = (clone $websiteQuery)->latest()->limit(5)->get();

        return view('dashboard', compact(
            'websites', 'plan', 'totalWebsites', 'activeWebsites',
            'buildingWebsites', 'errorWebsites', 'maxWebsites', 'recentWebsites'
        ));
    }
}
