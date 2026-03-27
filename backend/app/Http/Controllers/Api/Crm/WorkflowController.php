<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $workflows = Workflow::forUser($request->user()->id)->withCount('steps')->orderByDesc('created_at')->get();
        return response()->json($workflows);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'trigger_type'   => 'required|string|max:100',
            'trigger_config' => 'nullable|array',
            'steps'          => 'nullable|array',
        ]);

        $workflow = Workflow::create([
            'user_id'        => $request->user()->id,
            'name'           => $validated['name'],
            'trigger_type'   => $validated['trigger_type'],
            'trigger_config' => $validated['trigger_config'] ?? [],
        ]);

        if (!empty($validated['steps'])) {
            foreach ($validated['steps'] as $i => $step) {
                WorkflowStep::create([
                    'workflow_id' => $workflow->id,
                    'step_order'  => $i + 1,
                    'type'        => $step['type'],
                    'config'      => $step['config'] ?? [],
                ]);
            }
        }

        return response()->json($workflow->load('steps'), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $workflow = Workflow::forUser($request->user()->id)->findOrFail($id);
        $workflow->update($request->only(['name', 'trigger_type', 'trigger_config']));
        return response()->json($workflow->fresh()->load('steps'));
    }

    public function activate(Request $request, int $id): JsonResponse
    {
        $workflow = Workflow::forUser($request->user()->id)->findOrFail($id);
        $newStatus = $workflow->status === 'active' ? 'inactive' : 'active';
        $workflow->update(['status' => $newStatus]);
        return response()->json(['status' => $newStatus]);
    }
}
