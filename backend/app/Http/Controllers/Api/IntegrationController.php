<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Integration::where('user_id', $request->user()->id)->get());
    }

    public function connect(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'platform'   => 'required|in:shopify,squarespace,woocommerce',
            'store_name' => 'required|string|max:255',
            'store_url'  => 'required|url',
            'api_key'    => 'nullable|string',
        ]);

        $integration = Integration::create([
            'user_id'    => $request->user()->id,
            'platform'   => $validated['platform'],
            'store_name' => $validated['store_name'],
            'store_url'  => $validated['store_url'],
            'api_key'    => $validated['api_key'] ?? null,
            'status'     => 'connected',
        ]);

        return response()->json($integration, 201);
    }

    public function disconnect(Request $request, int $id): JsonResponse
    {
        $integration = Integration::where('user_id', $request->user()->id)->findOrFail($id);
        $integration->update(['status' => 'disconnected']);
        return response()->json(['message' => 'Disconnected.']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        Integration::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
