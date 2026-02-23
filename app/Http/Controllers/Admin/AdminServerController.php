<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\DigitalOceanService;
use App\Services\ServerProvisioningService;
use Illuminate\Http\Request;

class AdminServerController extends Controller
{
    public function __construct(
        private DigitalOceanService $doService,
        private ServerProvisioningService $provisioningService,
    ) {}

    public function index()
    {
        $servers = Server::withCount('websites')->latest()->get();
        return view('admin.servers.index', compact('servers'));
    }

    public function show(Server $server)
    {
        $server->load('websites.user');
        return view('admin.servers.show', compact('server'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'sometimes|string',
            'size' => 'sometimes|string',
        ]);

        // Create droplet on DigitalOcean
        $result = $this->doService->createDroplet(
            $validated['name'],
            $validated['region'] ?? null,
            $validated['size'] ?? null,
        );

        if (!$result['success']) {
            return back()->withErrors(['error' => $result['message']])->withInput();
        }

        $droplet = $result['data']['droplet'] ?? [];

        $server = Server::create([
            'name' => $validated['name'],
            'provider' => 'digitalocean',
            'provider_id' => $droplet['id'] ?? null,
            'ip_address' => $droplet['networks']['v4'][0]['ip_address'] ?? null,
            'region' => $validated['region'] ?? config('services.digitalocean.default_region'),
            'size' => $validated['size'] ?? config('services.digitalocean.default_size'),
            'status' => 'provisioning',
        ]);

        return redirect()->route('admin.servers.show', $server)->with('success', 'Server created. Provisioning in progress...');
    }

    public function provision(Server $server)
    {
        $result = $this->provisioningService->provisionServer($server);

        if ($result['success']) {
            return back()->with('success', 'Server provisioned successfully.');
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    public function healthCheck(Server $server)
    {
        $result = $this->provisioningService->checkHealth($server);

        if ($result['success']) {
            return back()->with('success', 'Health check completed.');
        }

        return back()->withErrors(['error' => $result['message']]);
    }

    public function destroy(Server $server)
    {
        if ($server->current_websites > 0) {
            return back()->withErrors(['error' => 'Cannot delete server with active websites.']);
        }

        if ($server->provider_id) {
            $this->doService->deleteDroplet((int) $server->provider_id);
        }

        $server->delete();
        return redirect()->route('admin.servers.index')->with('success', 'Server deleted.');
    }
}
