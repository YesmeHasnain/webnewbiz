<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\EmailSequence;
use App\Models\EmailSequenceStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SequenceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sequences = EmailSequence::forUser($request->user()->id)->withCount('steps')->get();
        return response()->json($sequences);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'trigger' => 'nullable|string|max:100',
            'steps'   => 'nullable|array',
        ]);

        $sequence = EmailSequence::create([
            'user_id' => $request->user()->id,
            'name'    => $validated['name'],
            'trigger' => $validated['trigger'] ?? 'manual',
        ]);

        if (!empty($validated['steps'])) {
            foreach ($validated['steps'] as $i => $step) {
                EmailSequenceStep::create([
                    'email_sequence_id' => $sequence->id,
                    'step_order'        => $i + 1,
                    'delay_hours'       => $step['delay_hours'] ?? 24,
                    'subject'           => $step['subject'],
                    'body_html'         => $step['body_html'],
                    'type'              => $step['type'] ?? 'email',
                ]);
            }
        }

        return response()->json($sequence->load('steps'), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $sequence = EmailSequence::forUser($request->user()->id)->findOrFail($id);
        $sequence->update($request->only(['name', 'trigger', 'status']));
        return response()->json($sequence->fresh()->load('steps'));
    }
}
