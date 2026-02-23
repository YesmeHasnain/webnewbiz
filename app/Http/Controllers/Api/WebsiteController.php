<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Services\WebsiteBuilderService;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function __construct(private WebsiteBuilderService $builderService) {}

    public function index(Request $request)
    {
        $websites = Website::forUser($request->user()->id)
            ->with(['server', 'domains', 'plugins', 'themes'])
            ->latest()
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $websites]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'sometimes|string|max:63|alpha_dash|unique:websites,subdomain',
            'business_type' => 'sometimes|string|max:100',
            'prompt' => 'sometimes|string|max:2000',
            'style' => 'sometimes|string|in:modern,classic,minimal,bold,elegant',
            'email' => 'sometimes|email',
        ]);

        $result = $this->builderService->buildWebsite($validated);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result, 201);
    }

    public function show(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $website->load(['server', 'domains', 'plugins', 'themes', 'backups']),
        ]);
    }

    public function update(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'custom_domain' => 'sometimes|nullable|string|max:255',
        ]);

        $website->update($validated);

        return response()->json(['success' => true, 'data' => $website->fresh()]);
    }

    public function destroy(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->builderService->deleteWebsite($website);
        return response()->json($result);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'prompt' => 'required|string|max:2000',
            'style' => 'sometimes|string|in:modern,classic,minimal,bold,elegant',
        ]);

        $result = $this->builderService->buildWebsite($validated);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        return response()->json($result, 201);
    }

    public function status(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $website->status,
                'url' => $website->url,
                'screenshot' => $website->screenshot_path,
            ],
        ]);
    }

    public function suspend(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->builderService->suspendWebsite($website, $request->input('reason', ''));
        return response()->json($result);
    }

    public function unsuspend(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->builderService->unsuspendWebsite($website);
        return response()->json($result);
    }

    public function regenerate(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'prompt' => 'required|string|max:2000',
            'style' => 'sometimes|string|in:modern,classic,minimal,bold,elegant',
        ]);

        // This would re-generate AI content and re-inject
        $result = $this->builderService->buildWebsite(array_merge(
            $validated,
            ['name' => $website->name, 'business_type' => $website->ai_business_type, 'subdomain' => $website->subdomain]
        ));

        return response()->json($result);
    }
}
