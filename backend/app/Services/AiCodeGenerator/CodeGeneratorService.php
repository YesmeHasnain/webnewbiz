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

        $fileStructure = match ($framework) {
            'react' => <<<'FS'
## FILE STRUCTURE — You MUST create these files:
```
index.html              ← Main HTML (loads React CDN + Babel + Tailwind CDN + main App.jsx)
src/
  App.jsx               ← Main App component with Router/page switching
  components/
    Navbar.jsx           ← Sticky navigation with mobile hamburger menu
    Footer.jsx           ← Footer with links and social icons
    Hero.jsx             ← Hero section component
    About.jsx            ← About section component
    Skills.jsx           ← Skills/features component
    Projects.jsx         ← Projects/portfolio gallery component
    Contact.jsx          ← Contact form component
  pages/
    HomePage.jsx         ← Home page combining Hero + sections
    AboutPage.jsx        ← Full about page
    ContactPage.jsx      ← Contact page with form
styles.css              ← Custom CSS animations and styles
```
Use React 18 CDN + Babel standalone for browser JSX. Each component in its OWN file.
In index.html load: React CDN, ReactDOM CDN, Babel standalone, Tailwind CDN.
In index.html add: <script type="text/babel" src="src/App.jsx"></script>
Each .jsx file must use: const { useState, useEffect } = React; (no imports, CDN mode)
FS,
            'nextjs' => <<<'FS'
## FILE STRUCTURE — Create these files:
```
index.html, src/App.jsx, src/components/Navbar.jsx, src/components/Footer.jsx,
src/components/Hero.jsx, src/components/About.jsx, src/components/Skills.jsx,
src/components/Projects.jsx, src/components/Contact.jsx,
src/pages/HomePage.jsx, src/pages/AboutPage.jsx, src/pages/ContactPage.jsx, styles.css
```
Use React 18 CDN + Babel standalone. Next.js-inspired component architecture.
FS,
            'vue' => <<<'FS'
## FILE STRUCTURE — Create these files:
```
index.html (loads Vue 3 CDN + Tailwind CDN + mounts app),
src/App.js, src/components/Navbar.js, src/components/Footer.js,
src/components/Hero.js, src/components/About.js, src/components/Skills.js,
src/components/Contact.js, styles.css
```
Use Vue 3 CDN (unpkg.com/vue@3). Composition API with setup(). Each component in own file.
FS,
            default => <<<'FS'
## FILE STRUCTURE — Create these SEPARATE files:
```
index.html              ← Home page
about.html              ← About page
services.html           ← Services page
portfolio.html          ← Portfolio/projects page
contact.html            ← Contact page with form
css/
  styles.css            ← All styles
  animations.css        ← CSS animations
js/
  main.js               ← Navigation, hamburger menu, smooth scroll
  form.js               ← Contact form validation
```
Each HTML page must be a COMPLETE standalone file with full nav + footer.
FS,
        };

        return <<<PROMPT
You are a senior full-stack developer building a production website called "{$project->name}".

## CRITICAL RULES:
1. Create MULTIPLE FILES in SEPARATE FOLDERS. Never put all code in one file.
2. Every component/page MUST be in its OWN file.
3. DELETE the existing starter template files (App.jsx, index.html) and CREATE fresh ones.
4. The file structure below is MANDATORY — create every single file listed.

{$fileStructure}

## DESIGN STANDARDS (Awwwards/Dribbble quality):
- Dark theme: bg-gray-950/bg-[#0a0a0f] background, white text, blue/purple gradients
- Hero: Full-viewport, dramatic gradient background, text-5xl to text-7xl bold heading, animated entrance
- Cards: rounded-2xl, border border-gray-800, shadow-xl, backdrop-blur glass effects
- Buttons: rounded-full or rounded-xl, gradient bg (blue-600 to purple-600), hover scale effect
- Spacing: generous (py-20, py-24, py-32 between sections)
- Hover effects on ALL interactive elements (scale, shadow, color transitions)
- CSS animations: fade-in on scroll, floating elements, gradient-shift backgrounds
- Images: Use https://images.unsplash.com/photo-{id}?w=800&h=600&fit=crop
- Typography: Inter font from Google Fonts, bold headings, generous line-height
- Mobile responsive: Tailwind sm:/md:/lg: breakpoints, hamburger menu on mobile
- Tailwind CSS via CDN: <script src="https://cdn.tailwindcss.com"></script>

## QUALITY:
- Production-ready code only. No placeholder text like "Lorem ipsum".
- Real content that matches the project description.
- Every file must be complete — no comments like "// add more here".
- Smooth scrolling navigation between sections.
- Form validation with success/error states.
- At least 5 fully designed sections/pages.
PROMPT;
    }
}
