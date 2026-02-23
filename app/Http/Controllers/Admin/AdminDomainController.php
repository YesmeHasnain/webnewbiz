<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Services\DnsService;
use Illuminate\Http\Request;

class AdminDomainController extends Controller
{
    public function __construct(private DnsService $dnsService) {}

    public function index(Request $request)
    {
        $query = Domain::with(['website.user']);

        if ($search = $request->input('search')) {
            $query->where('domain', 'like', "%{$search}%");
        }

        if ($status = $request->input('ssl_status')) {
            $query->where('ssl_status', $status);
        }

        $domains = $query->latest()->paginate(20);
        return view('admin.domains.index', compact('domains'));
    }

    public function verify(Domain $domain)
    {
        $result = $this->dnsService->verifyDomain($domain->domain);

        if ($result['success'] && ($result['data']['verified'] ?? false)) {
            $domain->update(['dns_status' => 'active', 'verified_at' => now()]);
            return back()->with('success', 'Domain verified successfully.');
        }

        return back()->withErrors(['error' => 'Domain verification failed.']);
    }
}
