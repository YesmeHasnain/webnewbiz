<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\AppMessage;
use App\Services\AppBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppController extends Controller
{
    public function __construct(private AppBuilderService $appBuilder) {}

    public function index(Request $request): JsonResponse
    {
        $apps = App::forUser($request->user()->id)->orderByDesc('updated_at')->get();
        return response()->json($apps);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'framework' => 'in:react-native,flutter',
            'platforms' => 'array',
        ]);

        $app = $this->appBuilder->createApp(
            $request->user(),
            $validated['name'],
            $validated['framework'] ?? 'react-native',
            $validated['platforms'] ?? ['ios', 'android'],
        );

        return response()->json(['app' => $app], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        $app->update(['file_tree' => $this->appBuilder->buildFileTree($app)]);
        return response()->json($app);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        $this->appBuilder->deleteApp($app);
        return response()->json(['message' => 'App deleted.']);
    }

    public function chat(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        $validated = $request->validate(['message' => 'required|string|max:10000']);

        $result = $this->appBuilder->chatAsync($app, $validated['message']);
        return response()->json($result);
    }

    public function stream(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        return response()->json($this->appBuilder->getStream($app));
    }

    public function messages(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        return response()->json($app->messages()->orderBy('created_at')->get());
    }

    public function readFile(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        $path = $request->query('path', '');
        $content = $this->appBuilder->readFile($app, $path);
        if ($content === null) return response()->json(['error' => 'File not found'], 404);
        return response()->json(['path' => $path, 'content' => $content]);
    }

    public function writeFile(Request $request, int $id): JsonResponse
    {
        $app = App::forUser($request->user()->id)->findOrFail($id);
        $validated = $request->validate(['path' => 'required|string', 'content' => 'required|string']);
        $this->appBuilder->writeFile($app, $validated['path'], $validated['content']);
        return response()->json(['message' => 'Saved.', 'file_tree' => $app->fresh()->file_tree]);
    }

    public function preview(int $id, ?string $path = null): Response
    {
        $app = App::findOrFail($id);
        $path = $path ?: 'App.js';
        $content = $this->appBuilder->readFile($app, $path);
        if ($content === null) return response('Not found', 404);
        $mime = match(pathinfo($path, PATHINFO_EXTENSION)) {
            'js', 'jsx' => 'application/javascript',
            'ts', 'tsx' => 'application/typescript',
            'json' => 'application/json',
            'css' => 'text/css',
            default => 'text/plain',
        };
        return response($content, 200, ['Content-Type' => $mime, 'Access-Control-Allow-Origin' => '*']);
    }
}
