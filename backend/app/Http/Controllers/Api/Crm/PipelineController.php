<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Pipeline;
use App\Models\Deal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pipelines = Pipeline::forUser($request->user()->id)->with('deals')->get();
        return response()->json($pipelines);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'stages' => 'required|array|min:1',
        ]);

        $pipeline = Pipeline::create(['user_id' => $request->user()->id, ...$validated]);
        return response()->json($pipeline, 201);
    }

    public function deals(Request $request): JsonResponse
    {
        $query = Deal::forUser($request->user()->id)->with(['contact', 'pipeline']);
        if ($request->has('pipeline_id')) $query->where('pipeline_id', $request->pipeline_id);
        if ($request->has('stage')) $query->where('stage', $request->stage);
        return response()->json($query->orderByDesc('updated_at')->get());
    }

    public function storeDeal(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pipeline_id'    => 'required|exists:pipelines,id',
            'title'          => 'required|string|max:255',
            'contact_id'     => 'nullable|exists:contacts,id',
            'value'          => 'nullable|numeric|min:0',
            'stage'          => 'required|string|max:100',
            'probability'    => 'nullable|integer|min:0|max:100',
            'expected_close' => 'nullable|date',
        ]);

        $deal = Deal::create(['user_id' => $request->user()->id, ...$validated]);
        return response()->json($deal->load('contact'), 201);
    }

    public function updateDeal(Request $request, int $id): JsonResponse
    {
        $deal = Deal::forUser($request->user()->id)->findOrFail($id);
        $deal->update($request->only(['title', 'value', 'stage', 'probability', 'expected_close', 'status', 'contact_id']));
        return response()->json($deal->fresh()->load('contact'));
    }

    public function updateDealStage(Request $request, int $id): JsonResponse
    {
        $deal = Deal::forUser($request->user()->id)->findOrFail($id);
        $validated = $request->validate(['stage' => 'required|string|max:100']);
        $deal->update(['stage' => $validated['stage']]);
        return response()->json($deal->fresh());
    }
}
