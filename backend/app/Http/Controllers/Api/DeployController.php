<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deployment;
use App\Models\Project;
use App\Models\App;
use App\Services\DeployService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function __construct(private DeployService $deployService) {}
    public function index(Request $request): JsonResponse
    {
        $deployments = Deployment::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')->get();
        return response()->json($deployments);
    }

    public function deploy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'       => 'required|in:project,app',
            'id'         => 'required|integer',
            'domain'     => 'nullable|string|max:255',
            'provider'   => 'in:webnewbiz,vercel,netlify',
        ]);

        $userId = $request->user()->id;
        $type = $validated['type'];

        if ($type === 'project') {
            $item = Project::forUser($userId)->findOrFail($validated['id']);
            $deployableType = Project::class;
        } else {
            $item = App::forUser($userId)->findOrFail($validated['id']);
            $deployableType = App::class;
        }

        $subdomain = $item->slug . '.webnewbiz.app';

        $deployment = Deployment::create([
            'user_id'         => $userId,
            'deployable_type' => $deployableType,
            'deployable_id'   => $item->id,
            'type'            => $type === 'project' ? 'website' : 'app',
            'status'          => 'deploying',
            'subdomain'       => $subdomain,
            'domain'          => $validated['domain'] ?? null,
            'url'             => 'https://' . ($validated['domain'] ?? $subdomain),
            'provider'        => $validated['provider'] ?? 'webnewbiz',
            'ssl_status'      => 'pending',
            'deployed_at'     => now(),
            'expires_at'      => now()->addMonth(),
        ]);

        // Simulate deployment (in production: trigger actual deploy pipeline)
        $deployment->update([
            'status' => 'active',
            'ssl_status' => 'active',
            'server_ip' => '167.172.0.' . rand(1, 254),
            'dns_records' => [
                ['type' => 'A', 'name' => '@', 'value' => $deployment->server_ip],
                ['type' => 'CNAME', 'name' => 'www', 'value' => $subdomain],
            ],
            'build_log' => [
                ['time' => now()->toISOString(), 'msg' => 'Build started'],
                ['time' => now()->toISOString(), 'msg' => 'Files uploaded'],
                ['time' => now()->toISOString(), 'msg' => 'SSL certificate provisioned'],
                ['time' => now()->toISOString(), 'msg' => 'Deployment active'],
            ],
        ]);

        return response()->json(['deployment' => $deployment->fresh()], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        return response()->json($deployment);
    }

    public function addDomain(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        $validated = $request->validate(['domain' => 'required|string|max:255']);

        $deployment->update([
            'domain' => $validated['domain'],
            'url' => 'https://' . $validated['domain'],
            'dns_records' => [
                ['type' => 'A', 'name' => '@', 'value' => $deployment->server_ip],
                ['type' => 'CNAME', 'name' => 'www', 'value' => $deployment->subdomain],
            ],
        ]);

        return response()->json($deployment->fresh());
    }

    public function stop(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        $deployment->update(['status' => 'stopped']);
        return response()->json(['message' => 'Deployment stopped.']);
    }

    public function redeploy(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        $result = $this->deployService->redeploy($deployment);
        return response()->json($result);
    }

    public function logs(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        return response()->json($this->deployService->getLogs($deployment));
    }

    public function setupEmail(Request $request, int $id): JsonResponse
    {
        $deployment = Deployment::where('user_id', $request->user()->id)->findOrFail($id);
        $validated = $request->validate(['domain' => 'required|string|max:255']);
        $result = $this->deployService->setupEmail($deployment, $validated['domain']);
        return response()->json($result);
    }
}
