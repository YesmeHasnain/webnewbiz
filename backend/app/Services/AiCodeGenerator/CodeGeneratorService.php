<?php

namespace App\Services\AiCodeGenerator;

use App\Models\Project;
use App\Models\ProjectMessage;
use App\Services\ProjectService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CodeGeneratorService
{
    private ClaudeCliService $claudeCli;
    private ProjectService $projectService;

    public function __construct(ClaudeCliService $claudeCli, ProjectService $projectService)
    {
        $this->claudeCli = $claudeCli;
        $this->projectService = $projectService;
    }

    /**
     * Start AI generation in background — returns immediately.
     * Frontend polls /stream endpoint for real-time updates.
     */
    public function chatAsync(Project $project, string $userMessage): array
    {
        // Save user message
        ProjectMessage::create([
            'project_id' => $project->id,
            'role'       => 'user',
            'content'    => $userMessage,
        ]);

        // Mark project as generating
        $project->update(['status' => 'generating']);

        // Prepare stream file (frontend polls this)
        $streamFile = $project->storagePath() . '/.claude-stream';
        File::put($streamFile, json_encode([
            'status' => 'starting',
            'text' => '',
            'files_changed' => [],
            'started_at' => now()->toISOString(),
        ]));

        // Snapshot files before
        $beforeSnapshot = $this->getFileSnapshot($project);
        $snapshotFile = $project->storagePath() . '/.claude-before-snapshot';
        File::put($snapshotFile, json_encode($beforeSnapshot));

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($project);

        // Start Claude CLI in background
        $this->claudeCli->runAsync($project, $userMessage, $systemPrompt);

        return [
            'success' => true,
            'status' => 'generating',
            'message' => 'AI generation started. Poll /stream for updates.',
        ];
    }

    /**
     * Get current stream status — called by frontend polling.
     */
    public function getStream(Project $project): array
    {
        $streamFile = $project->storagePath() . '/.claude-stream';
        $doneFile = $project->storagePath() . '/.claude-done';

        // Check if process finished
        if (File::exists($doneFile)) {
            $doneData = json_decode(File::get($doneFile), true) ?: [];

            // Handle raw CLI output format (has 'result' key instead of 'response')
            if (isset($doneData['result']) && !isset($doneData['response'])) {
                $doneData['response'] = $doneData['result'];
            }
            if (isset($doneData['session_id'])) {
                $sessionFile = $project->storagePath() . '/.claude-session';
                File::put($sessionFile, $doneData['session_id']);
            }

            // If not yet finalized, finalize now
            if ($project->status === 'generating') {
                $this->finalize($project, $doneData);
            }

            return [
                'status' => 'done',
                'text' => $doneData['response'] ?? $doneData['result'] ?? 'Code generation complete.',
                'files_changed' => $doneData['files_changed'] ?? [],
                'file_tree' => $project->fresh()->file_tree ?? [],
            ];
        }

        // Still running — return current progress
        $fileTree = $this->projectService->buildFileTree($project);

        // Read stream file for Claude's output so far
        $streamData = [];
        if (File::exists($streamFile)) {
            $streamData = json_decode(File::get($streamFile), true) ?: [];
        }

        return [
            'status' => 'generating',
            'text' => $streamData['text'] ?? 'AI is generating code...',
            'files_changed' => array_keys($this->diffSnapshots(
                json_decode(File::get($project->storagePath() . '/.claude-before-snapshot') ?: '[]', true) ?: [],
                $this->getFileSnapshot($project)
            )),
            'file_tree' => $fileTree,
        ];
    }

    /**
     * Finalize after Claude CLI completes.
     */
    private function finalize(Project $project, array $doneData): void
    {
        $beforeSnapshot = [];
        $snapshotFile = $project->storagePath() . '/.claude-before-snapshot';
        if (File::exists($snapshotFile)) {
            $beforeSnapshot = json_decode(File::get($snapshotFile), true) ?: [];
        }

        $afterSnapshot = $this->getFileSnapshot($project);
        $filesChanged = $this->diffSnapshots($beforeSnapshot, $afterSnapshot);

        // Save AI response (handle both 'response' and 'result' keys)
        ProjectMessage::create([
            'project_id'    => $project->id,
            'role'          => 'assistant',
            'content'       => $doneData['response'] ?? $doneData['result'] ?? 'Code generation complete.',
            'files_changed' => $filesChanged,
        ]);

        // Update project
        $project->update([
            'status'    => 'ready',
            'file_tree' => $this->projectService->buildFileTree($project),
        ]);

        // Update done file with files_changed
        $doneData['files_changed'] = $filesChanged;
        File::put($project->storagePath() . '/.claude-done', json_encode($doneData));

        // Cleanup
        @unlink($project->storagePath() . '/.claude-stream');
        @unlink($snapshotFile);

        Log::info('CodeGenerator: Finalized', ['project' => $project->id, 'files' => count($filesChanged)]);
    }

    /**
     * Get a snapshot of all files with their modification times.
     */
    private function getFileSnapshot(Project $project): array
    {
        $root = $project->storagePath();
        if (!File::isDirectory($root)) return [];
        $snapshot = [];
        foreach (File::allFiles($root) as $file) {
            $rel = str_replace('\\', '/', ltrim(str_replace($root, '', $file->getPathname()), '\\/'));
            if (str_starts_with($rel, '.')) continue;
            $snapshot[$rel] = $file->getMTime();
        }
        return $snapshot;
    }

    /**
     * Compare before/after snapshots.
     */
    private function diffSnapshots(array $before, array $after): array
    {
        $changed = [];
        foreach ($after as $path => $mtime) {
            if (!isset($before[$path]) || $before[$path] !== $mtime) $changed[] = $path;
        }
        foreach ($before as $path => $mtime) {
            if (!isset($after[$path])) $changed[] = $path;
        }
        return array_values(array_unique($changed));
    }

    /**
     * Build system prompt.
     */
    private function buildSystemPrompt(Project $project): string
    {
        $framework = $project->framework;
        $frameworkNote = match ($framework) {
            'react' => 'REACT project. React 18 CDN + Babel standalone. Create .jsx files with hooks.',
            'nextjs' => 'Next.js project. Multi-page with shared components, App Router, page.tsx.',
            'vue' => 'VUE 3 project. Use Vue 3 CDN (unpkg.com/vue@3). Composition API, reactive refs.',
            'angular' => 'ANGULAR-style project. Component-based with services and templates.',
            'svelte' => 'SVELTE-inspired project. Reactive components, minimal boilerplate.',
            default => 'HTML/CSS/JS project. Create separate .html files for each page.',
        };

        return <<<PROMPT
You are building a website project called "{$project->name}".
{$frameworkNote}

## DESIGN STANDARDS — You MUST follow these:

1. **Premium Design**: Every page must look like a \$10,000+ professionally designed website. Think Dribbble/Awwwards quality.

2. **Visual Requirements**:
   - Hero sections: Full-viewport, dramatic gradients or background images, large bold typography (text-5xl to text-7xl), subtle animations
   - Rich color palette with gradients (bg-gradient-to-r/br), never flat/boring single colors
   - Generous whitespace (py-20, py-24, py-32 between sections)
   - Cards with rounded-2xl/3xl, shadow-xl/2xl, backdrop-blur for glass effects
   - Hover effects on ALL interactive elements (scale, shadow, color transitions)
   - CSS animations for visual interest (fade-in, float, gradient-shift)
   - Beautiful buttons: rounded-full or rounded-xl, gradient backgrounds, px-8 py-4

3. **Images**: Use https://images.unsplash.com/photo-{id}?w=800&h=600&fit=crop for high-quality relevant images. Choose photo IDs that match the content.

4. **Typography**: Use Inter font from Google Fonts. Bold headings, generous line-height.

5. **Multi-Page**: Create AT LEAST 5 fully-designed pages (Home, About, Services/Products, Portfolio/Gallery, Contact). Every page must have consistent nav + footer.

6. **Mobile Responsive**: Use Tailwind's sm:, md:, lg:, xl: breakpoints. Mobile hamburger menu with JavaScript toggle.

7. **Tailwind CSS**: Use CDN: <script src="https://cdn.tailwindcss.com"></script>

8. **Code Quality**: Complete files only — never placeholder comments. Production-ready code.
PROMPT;
    }
}
