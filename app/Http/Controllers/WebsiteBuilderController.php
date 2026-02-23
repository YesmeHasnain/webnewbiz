<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\WebsiteBuilderService;
use Illuminate\Http\Request;

class WebsiteBuilderController extends Controller
{
    public function __construct(
        private WebsiteBuilderService $builderService,
    ) {}

    public function index()
    {
        $user = auth()->user();
        $plan = $user->currentPlan();
        $websiteCount = $user->websites()->count();
        $maxWebsites = $plan->max_websites ?? 1;

        if ($websiteCount >= $maxWebsites) {
            return redirect()->route('dashboard')
                ->with('error', "You've reached your plan limit of {$maxWebsites} websites. Upgrade your plan to create more.");
        }

        return view('builder.index', compact('plan', 'websiteCount', 'maxWebsites'));
    }

    public function generate(Request $request)
    {
        $user = auth()->user();
        $plan = $user->currentPlan();
        $websiteCount = $user->websites()->count();
        $maxWebsites = $plan->max_websites ?? 1;

        if ($websiteCount >= $maxWebsites) {
            return redirect()->route('dashboard')
                ->with('error', 'You have reached your website limit.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'prompt' => 'nullable|string|max:2000',
            'style' => 'required|string|in:modern,classic,minimal,bold,elegant',
        ]);

        $validated['email'] = $user->email;

        $result = $this->builderService->buildWebsite($validated);

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        $website = $result['data']['website'];

        return redirect()->route('builder.status', $website);
    }

    public function status(Website $website)
    {
        abort_if($website->user_id !== auth()->id(), 403);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $website->status,
                'url' => $website->url,
                'screenshot' => $website->screenshot_path,
            ]);
        }

        return view('builder.progress', compact('website'));
    }

    public function complete(Website $website)
    {
        abort_if($website->user_id !== auth()->id(), 403);

        if ($website->status !== 'active') {
            return redirect()->route('builder.status', $website);
        }

        $website->load(['domains', 'server']);

        return view('builder.complete', compact('website'));
    }
}
