<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Server;
use App\Models\User;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')->where('status', 'active')->count();
        $totalWebsites = Website::count();
        $activeWebsites = Website::where('status', 'active')->count();
        $totalServers = Server::count();
        $activeServers = Server::where('status', 'active')->count();

        $recentWebsites = Website::with(['user', 'server'])->latest()->take(5)->get();
        $recentActivity = ActivityLog::with('user')->latest()->take(10)->get();
        $servers = Server::active()->get();

        // Chart data - websites created per day (last 7 days)
        $chartLabels = [];
        $websitesData = [];
        $usersData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('M d');
            $websitesData[] = Website::whereDate('created_at', $date)->count();
            $usersData[] = User::whereDate('created_at', $date)->count();
        }

        return view('admin.dashboard.index', compact(
            'totalUsers', 'activeUsers', 'totalWebsites', 'activeWebsites',
            'totalServers', 'activeServers', 'recentWebsites', 'recentActivity',
            'servers', 'chartLabels', 'websitesData', 'usersData'
        ));
    }

    public function realtimeStats()
    {
        return response()->json([
            'totalWebsites' => Website::count(),
            'activeWebsites' => Website::where('status', 'active')->count(),
            'pendingWebsites' => Website::where('status', 'provisioning')->count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'activeServers' => Server::where('status', 'active')->count(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
