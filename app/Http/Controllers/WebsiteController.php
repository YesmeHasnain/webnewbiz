<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\WebsiteBuilderService;

class WebsiteController extends Controller
{
    public function __construct(
        private WebsiteBuilderService $builderService,
    ) {}

    public function index()
    {
        $websites = auth()->user()->websites()
            ->with(['server', 'domains'])
            ->latest()
            ->paginate(12);

        return view('websites.index', compact('websites'));
    }

    public function show(Website $website)
    {
        abort_if($website->user_id !== auth()->id(), 403);

        $website->load(['server', 'domains', 'plugins', 'themes', 'backups']);

        return view('websites.show', compact('website'));
    }

    public function destroy(Website $website)
    {
        abort_if($website->user_id !== auth()->id(), 403);

        $this->builderService->deleteWebsite($website);

        return redirect()->route('websites.index')
            ->with('success', 'Website deleted successfully.');
    }
}
