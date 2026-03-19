<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index(Request $request, $websiteId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        return response()->json($website->domains()->orderByDesc('created_at')->get());
    }

    public function store(Request $request, $websiteId)
    {
        $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain',
            'type'   => 'nullable|string|in:primary,alias,redirect',
        ]);

        $website = $request->user()->websites()->findOrFail($websiteId);

        $domain = $website->domains()->create([
            'domain' => strtolower(trim($request->domain)),
            'type'   => $request->input('type', 'primary'),
            'status' => 'pending',
        ]);

        return response()->json($domain, 201);
    }

    public function destroy(Request $request, $websiteId, $domainId)
    {
        $website = $request->user()->websites()->findOrFail($websiteId);
        $domain = $website->domains()->findOrFail($domainId);
        $domain->delete();

        return response()->json(['success' => true]);
    }
}
