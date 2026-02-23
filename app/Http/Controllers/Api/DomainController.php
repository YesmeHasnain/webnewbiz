<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Website;
use App\Services\DnsService;
use App\Services\SslService;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(
        private DnsService $dnsService,
        private SslService $sslService,
    ) {}

    public function index(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'data' => $website->domains]);
    }

    public function store(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'domain' => 'required|string|max:255|unique:domains,domain',
        ]);

        $server = $website->server;
        if (!$server) {
            return response()->json(['success' => false, 'message' => 'No server assigned'], 422);
        }

        // Create DNS record
        $dnsResult = $this->dnsService->createRecord('A', $validated['domain'], $server->ip_address);

        $domain = Domain::create([
            'website_id' => $website->id,
            'domain' => $validated['domain'],
            'type' => 'custom',
            'is_primary' => false,
            'cloudflare_record_id' => $dnsResult['data']['id'] ?? null,
            'dns_status' => $dnsResult['success'] ? 'active' : 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $domain], 201);
    }

    public function destroy(Request $request, Website $website, Domain $domain)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($domain->is_primary && $domain->type === 'subdomain') {
            return response()->json(['success' => false, 'message' => 'Cannot delete primary subdomain'], 422);
        }

        if ($domain->cloudflare_record_id) {
            $this->dnsService->deleteRecord($domain->cloudflare_record_id);
        }

        $domain->delete();
        return response()->json(['success' => true, 'message' => 'Domain removed']);
    }

    public function verify(Request $request, Website $website, Domain $domain)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->dnsService->verifyDomain($domain->domain);

        if ($result['success'] && ($result['data']['verified'] ?? false)) {
            $domain->update(['dns_status' => 'active', 'verified_at' => now()]);
        }

        return response()->json($result);
    }

    public function ssl(Request $request, Website $website, Domain $domain)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->sslService->issueCertificate($domain);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function setPrimary(Request $request, Website $website, Domain $domain)
    {
        if ($website->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        Domain::where('website_id', $website->id)->update(['is_primary' => false]);
        $domain->update(['is_primary' => true]);
        $website->update(['custom_domain' => $domain->type === 'custom' ? $domain->domain : null]);

        return response()->json(['success' => true, 'data' => $domain->fresh()]);
    }
}
