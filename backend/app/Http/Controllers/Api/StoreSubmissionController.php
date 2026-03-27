<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreSubmission;
use App\Models\App;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreSubmissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(StoreSubmission::where('user_id', $request->user()->id)->orderByDesc('created_at')->get());
    }

    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_id'      => 'required|exists:apps,id',
            'store'       => 'required|in:appstore,playstore',
            'app_name'    => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|string',
        ]);

        $app = App::forUser($request->user()->id)->findOrFail($validated['app_id']);

        $submission = StoreSubmission::create([
            'user_id'      => $request->user()->id,
            'app_id'       => $app->id,
            'store'        => $validated['store'],
            'status'       => 'preparing',
            'app_name'     => $validated['app_name'],
            'description'  => $validated['description'],
            'category'     => $validated['category'],
            'submitted_at' => now(),
        ]);

        // TODO: Trigger actual build + submission pipeline
        $submission->update(['status' => 'submitted']);

        return response()->json($submission->fresh(), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json(StoreSubmission::where('user_id', $request->user()->id)->findOrFail($id));
    }
}
