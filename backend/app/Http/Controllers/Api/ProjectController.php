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
            'framework'   => 'in:html,react,nextjs,vue,angular,svelte',
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
     * Create a new file or folder.
     */
    public function createFile(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'path' => 'required|string|max:500',
            'type' => 'required|in:file,directory',
        ]);

        if ($validated['type'] === 'directory') {
            $this->projectService->createDirectory($project, $validated['path']);
        } else {
            $this->projectService->writeFile($project, $validated['path'], '');
        }

        return response()->json([
            'message'   => ucfirst($validated['type']) . ' created.',
            'file_tree' => $project->fresh()->file_tree,
        ]);
    }

    /**
     * Rename/move a file or folder.
     */
    public function renameFile(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'from' => 'required|string|max:500',
            'to'   => 'required|string|max:500',
        ]);

        $renamed = $this->projectService->renameFile($project, $validated['from'], $validated['to']);

        return response()->json([
            'message'   => $renamed ? 'Renamed successfully.' : 'Source not found.',
            'file_tree' => $project->fresh()->file_tree,
        ]);
    }

    /**
     * Execute a terminal command within the project directory.
     */
    public function terminal(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'command' => 'required|string|max:2000',
        ]);

        $command = $validated['command'];

        // Block dangerous commands
        $blocked = ['rm -rf /', 'mkfs', 'dd if=', ':(){', 'fork bomb', 'shutdown', 'reboot', 'halt', 'poweroff'];
        foreach ($blocked as $pattern) {
            if (str_contains(strtolower($command), $pattern)) {
                return response()->json([
                    'output' => "Command blocked for safety: {$pattern}",
                    'exit_code' => 1,
                ]);
            }
        }

        $cwd = $project->storagePath();
        $timeout = 30; // seconds

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $env = array_merge($_ENV, [
            'HOME'  => $cwd,
            'PATH'  => getenv('PATH') ?: '/usr/local/bin:/usr/bin:/bin',
            'TERM'  => 'xterm-256color',
        ]);

        $process = proc_open($command, $descriptors, $pipes, $cwd, $env);

        if (!is_resource($process)) {
            return response()->json([
                'output'    => 'Failed to execute command.',
                'exit_code' => 1,
            ]);
        }

        fclose($pipes[0]);

        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $start = time();

        while (true) {
            $status = proc_get_status($process);
            $stdout .= stream_get_contents($pipes[1]);
            $stderr .= stream_get_contents($pipes[2]);

            if (!$status['running']) break;
            if ((time() - $start) > $timeout) {
                proc_terminate($process, 9);
                $stderr .= "\n[Command timed out after {$timeout}s]";
                break;
            }

            usleep(50000); // 50ms
        }

        // Read any remaining output
        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        $output = trim($stdout);
        if ($stderr) {
            $output .= ($output ? "\n" : '') . trim($stderr);
        }

        // Limit output size
        if (strlen($output) > 50000) {
            $output = substr($output, 0, 50000) . "\n... [output truncated]";
        }

        return response()->json([
            'output'    => $output,
            'exit_code' => $exitCode,
        ]);
    }

    /**
     * Git operations within the project.
     */
    public function git(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'action'  => 'required|in:init,status,log,diff,add,commit,branch,checkout',
            'message' => 'nullable|string|max:500',
            'files'   => 'nullable|array',
            'branch'  => 'nullable|string|max:100',
        ]);

        $cwd = $project->storagePath();
        $action = $validated['action'];

        $commands = match ($action) {
            'init'     => 'git init && git add -A && git commit -m "Initial commit"',
            'status'   => 'git status --porcelain',
            'log'      => 'git log --oneline -20',
            'diff'     => 'git diff',
            'add'      => 'git add ' . (empty($validated['files']) ? '-A' : implode(' ', array_map('escapeshellarg', $validated['files']))),
            'commit'   => 'git add -A && git commit -m ' . escapeshellarg($validated['message'] ?? 'Update'),
            'branch'   => 'git branch',
            'checkout' => 'git checkout ' . escapeshellarg($validated['branch'] ?? 'main'),
        };

        $output = '';
        $exitCode = 0;

        exec("cd " . escapeshellarg($cwd) . " && {$commands} 2>&1", $outputLines, $exitCode);
        $output = implode("\n", $outputLines);

        return response()->json([
            'action'    => $action,
            'output'    => $output,
            'exit_code' => $exitCode,
        ]);
    }

    /**
     * Search across project files.
     */
    public function search(Request $request, int $id): JsonResponse
    {
        $project = Project::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'query' => 'required|string|max:200',
        ]);

        $query = $validated['query'];
        $results = [];
        $files = $this->projectService->listFiles($project);

        foreach ($files as $filePath) {
            $content = $this->projectService->readFile($project, $filePath);
            if ($content === null) continue;

            $lines = explode("\n", $content);
            foreach ($lines as $lineNum => $line) {
                if (stripos($line, $query) !== false) {
                    $results[] = [
                        'file' => $filePath,
                        'line' => $lineNum + 1,
                        'text' => trim($line),
                    ];
                }
            }

            if (count($results) >= 100) break;
        }

        return response()->json(['results' => $results]);
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
