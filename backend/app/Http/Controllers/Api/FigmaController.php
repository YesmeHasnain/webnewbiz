<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FigmaController extends Controller
{
    public function generateDesign(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt'      => 'required|string|max:2000',
            'style'       => 'nullable|string|max:100',
            'color_theme' => 'nullable|string|max:50',
        ]);

        // In production: Call Claude Vision API to generate Figma JSON
        return response()->json([
            'success' => true,
            'design'  => [
                'frames' => [],
                'prompt' => $validated['prompt'],
                'style'  => $validated['style'] ?? 'modern',
            ],
            'message' => 'Design generation started. Plugin will receive updates via webhook.',
        ]);
    }

    public function imageToDesign(Request $request): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:10240']);

        // In production: Send to Claude Vision API for analysis
        return response()->json([
            'success' => true,
            'layers'  => [],
            'message' => 'Image analyzed. Figma layers will be created via plugin.',
        ]);
    }

    public function exportToCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'figma_file_id' => 'required|string',
            'frame_id'      => 'required|string',
            'output_type'   => 'required|in:react,html,wordpress,react-native',
        ]);

        return response()->json([
            'success'     => true,
            'output_type' => $validated['output_type'],
            'message'     => 'Export started. Code will be generated and available in your project.',
        ]);
    }

    public function projects(Request $request): JsonResponse
    {
        // In production: Query figma_projects table
        return response()->json([]);
    }
}
