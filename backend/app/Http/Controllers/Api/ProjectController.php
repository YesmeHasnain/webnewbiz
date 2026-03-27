<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AiCodeGenerator\CodeGeneratorService;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService,
        private CodeGeneratorService $codeGenerator,
    ) {}

    /**
     * List user's projects.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = Project::forUser($request->user()->id)
            ->orderByDesc('updated_at')
            ->get();

        return response()->json($projects);
    }

    /**
     * Create a new project.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'framework'   => 'in:html,react,nextjs',
            'description' => 'nullable|string|max:1000',
        ]);

        $project = $this->projectService->createProject(
            $request->user(),
            $validated['name'],
            $validated['framework'] ?? 'html',
        );

        return response()->json(['project' => $project], 201);
    }

    /**
     * Get project details with file tree.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        // Refresh file tree from disk
        $project->update(['file_tree' => $this->projectService->buildFileTree($project)]);

        return response()->json($project);
    }

    /**
     * Delete a project.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);
        $this->projectService->deleteProject($project);

        return response()->json(['message' => 'Project deleted.']);
    }

    /**
     * Read a file from the project.
     */
    public function readFile(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);
        $path = $request->query('path', '');

        if (empty($path)) {
            return response()->json(['error' => 'Path required'], 400);
        }

        $content = $this->projectService->readFile($project, $path);

        if ($content === null) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json(['path' => $path, 'content' => $content]);
    }

    /**
     * Write/save a file.
     */
    public function writeFile(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'path'    => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        $this->projectService->writeFile($project, $validated['path'], $validated['content']);

        return response()->json([
            'message'   => 'File saved.',
            'file_tree' => $project->fresh()->file_tree,
        ]);
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);
        $path = $request->query('path', '');

        if (empty($path)) {
            return response()->json(['error' => 'Path required'], 400);
        }

        $deleted = $this->projectService->deleteFile($project, $path);

        return response()->json([
            'message'   => $deleted ? 'File deleted.' : 'File not found.',
            'file_tree' => $project->fresh()->file_tree,
        ]);
    }

    /**
     * Send a chat message — starts AI generation in background, returns immediately.
     */
    public function chat(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'message' => 'required|string|max:10000',
        ]);

        $result = $this->codeGenerator->chatAsync($project, $validated['message']);

        return response()->json($result);
    }

    /**
     * Poll stream — returns current AI generation status + files.
     */
    public function stream(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $result = $this->codeGenerator->getStream($project);

        return response()->json($result);
    }

    /**
     * Get chat history for a project.
     */
    public function messages(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $messages = $project->messages()
            ->orderBy('created_at')
            ->get(['id', 'role', 'content', 'files_changed', 'created_at']);

        return response()->json($messages);
    }

    /**
     * Serve project files for iframe preview (public route — no auth).
     */
    public function preview(int $id, ?string $path = null): Response
    {
        $project = Project::findOrFail($id);

        // Default to index.html
        if (empty($path) || $path === '/') {
            $path = 'index.html';
        }

        $content = $this->projectService->readFile($project, $path);

        if ($content === null) {
            // Try with .html extension
            $content = $this->projectService->readFile($project, $path . '.html');
            if ($content === null) {
                return response('Not found', 404);
            }
            $path .= '.html';
        }

        $mimeType = $this->projectService->getMimeType($path);

        return response($content, 200, [
            'Content-Type'                 => $mimeType,
            'Access-Control-Allow-Origin'  => '*',
            'Cache-Control'                => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
