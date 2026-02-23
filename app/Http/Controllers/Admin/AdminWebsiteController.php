<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\WebsiteBuilderService;
use Illuminate\Http\Request;

class AdminWebsiteController extends Controller
{
    public function __construct(private WebsiteBuilderService $builderService) {}

    public function index(Request $request)
    {
        $query = Website::with(['user', 'server', 'domains']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%")
                  ->orWhere('custom_domain', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $websites = $query->latest()->paginate(20);
        return view('admin.websites.index', compact('websites'));
    }

    public function show(Website $website)
    {
        $website->load(['user', 'server', 'domains', 'plugins', 'themes', 'backups']);
        return view('admin.websites.show', compact('website'));
    }

    public function suspend(Website $website, Request $request)
    {
        $this->builderService->suspendWebsite($website, $request->input('reason', 'Suspended by admin'));
        return back()->with('success', 'Website suspended.');
    }

    public function unsuspend(Website $website)
    {
        $this->builderService->unsuspendWebsite($website);
        return back()->with('success', 'Website reactivated.');
    }

    public function destroy(Website $website)
    {
        $this->builderService->deleteWebsite($website);
        return redirect()->route('admin.websites.index')->with('success', 'Website deleted.');
    }
}
