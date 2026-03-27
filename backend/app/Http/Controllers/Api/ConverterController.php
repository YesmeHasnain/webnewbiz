<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConverterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(Conversion::where('user_id', $request->user()->id)->orderByDesc('created_at')->get());
    }

    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input_type'  => 'required|in:image,code,figma,url',
            'output_type' => 'required|in:wordpress,react,nextjs,shopify,react-native,html',
            'input_data'  => 'required|string',
        ]);

        $conversion = Conversion::create([
            'user_id'     => $request->user()->id,
            'input_type'  => $validated['input_type'],
            'output_type' => $validated['output_type'],
            'input_data'  => $validated['input_data'],
            'status'      => 'converting',
        ]);

        // TODO: Trigger actual conversion (Claude Vision for images, code parser for code)
        // For now, simulate completion
        $conversion->update(['status' => 'done', 'output_files' => ['index.html', 'style.css']]);

        return response()->json($conversion->fresh(), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json(Conversion::where('user_id', $request->user()->id)->findOrFail($id));
    }
}
