<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Deployment;
use App\Models\Project;
use App\Models\App;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function overview(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $days = (int) $request->query('days', 30);
        $since = now()->subDays($days);

        $totalEvents = AnalyticsEvent::where('user_id', $userId)->where('occurred_at', '>=', $since)->count();
        $pageviews = AnalyticsEvent::where('user_id', $userId)->where('event', 'pageview')->where('occurred_at', '>=', $since)->count();
        $downloads = AnalyticsEvent::where('user_id', $userId)->where('event', 'download')->where('occurred_at', '>=', $since)->count();

        $topSources = AnalyticsEvent::where('user_id', $userId)->where('occurred_at', '>=', $since)
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')->orderByDesc('count')->limit(5)->get();

        $topCountries = AnalyticsEvent::where('user_id', $userId)->where('occurred_at', '>=', $since)
            ->select('country', DB::raw('count(*) as count'))
            ->groupBy('country')->orderByDesc('count')->limit(10)->get();

        $dailyEvents = AnalyticsEvent::where('user_id', $userId)->where('occurred_at', '>=', $since)
            ->select(DB::raw('DATE(occurred_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')->orderBy('date')->get();

        $activeDeployments = Deployment::where('user_id', $userId)->where('status', 'active')->count();
        $totalProjects = Project::forUser($userId)->count();
        $totalApps = App::forUser($userId)->count();

        return response()->json([
            'total_events'       => $totalEvents,
            'pageviews'          => $pageviews,
            'downloads'          => $downloads,
            'top_sources'        => $topSources,
            'top_countries'      => $topCountries,
            'daily_events'       => $dailyEvents,
            'active_deployments' => $activeDeployments,
            'total_projects'     => $totalProjects,
            'total_apps'         => $totalApps,
        ]);
    }

    // Public tracking endpoint (no auth — called from deployed sites)
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'deployment_id' => 'required|exists:deployments,id',
            'event'         => 'required|string|max:50',
            'source'        => 'nullable|string|max:100',
            'metadata'      => 'nullable|array',
        ]);

        $deployment = Deployment::findOrFail($validated['deployment_id']);

        AnalyticsEvent::create([
            'user_id'        => $deployment->user_id,
            'trackable_type' => $deployment->deployable_type,
            'trackable_id'   => $deployment->deployable_id,
            'event'          => $validated['event'],
            'source'         => $validated['source'] ?? 'direct',
            'country'        => $request->header('CF-IPCountry', 'unknown'),
            'device'         => $this->detectDevice($request->userAgent()),
            'browser'        => $this->detectBrowser($request->userAgent()),
            'metadata'       => $validated['metadata'] ?? null,
            'occurred_at'    => now(),
        ]);

        return response()->json(['tracked' => true]);
    }

    private function detectDevice(?string $ua): string
    {
        if (!$ua) return 'unknown';
        if (preg_match('/Mobile|Android|iPhone/i', $ua)) return 'mobile';
        if (preg_match('/Tablet|iPad/i', $ua)) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(?string $ua): string
    {
        if (!$ua) return 'unknown';
        if (str_contains($ua, 'Chrome')) return 'chrome';
        if (str_contains($ua, 'Firefox')) return 'firefox';
        if (str_contains($ua, 'Safari')) return 'safari';
        if (str_contains($ua, 'Edge')) return 'edge';
        return 'other';
    }
}
