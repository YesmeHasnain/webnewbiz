<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $websites = $user->websites()->latest()->limit(6)->get();
        $plan = $user->currentPlan();

        $totalWebsites = $user->websites()->count();
        $activeWebsites = $user->websites()->where('status', 'active')->count();
        $buildingWebsites = $user->websites()->where('status', 'provisioning')->count();
        $maxWebsites = $plan->max_websites ?? 1;

        return view('dashboard', compact(
            'websites', 'plan', 'totalWebsites', 'activeWebsites', 'buildingWebsites', 'maxWebsites'
        ));
    }
}
