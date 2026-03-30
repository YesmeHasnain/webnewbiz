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

        // Don't delete starter files — Claude will overwrite them

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
        // .claude-done must exist AND have content (>10 bytes) to be considered done
        // Empty file = Claude CLI still running (bat wrapper created file but hasn't written yet)
        $doneFileExists = File::exists($doneFile);
        $doneFileSize = $doneFileExists ? @filesize($doneFile) : 0;

        if ($doneFileExists && $doneFileSize > 10) {
            $rawContent = '';
            try { $rawContent = File::get($doneFile); } catch (\Throwable $e) {
                // File locked — still generating
            }
            $doneData = json_decode($rawContent, true) ?: [];

            // Handle raw CLI output format (has 'result' key instead of 'response')
            if (isset($doneData['result']) && !isset($doneData['response'])) {
                $doneData['response'] = $doneData['result'];
            }
            if (isset($doneData['session_id'])) {
                $sessionFile = $project->storagePath() . '/.claude-session';
                File::put($sessionFile, $doneData['session_id']);
            }

            // If done file is empty/invalid JSON, Claude created files directly on disk
            // Detect changes by comparing snapshots
            if (empty($doneData) || empty($rawContent)) {
                $doneData['response'] = 'Code generation complete. Files have been created in your project.';
            }

            // If not yet finalized, finalize now
            if ($project->status === 'generating') {
                $this->finalize($project, $doneData);
            }

            // Always rebuild file tree from disk after finalize
            $freshTree = $this->projectService->buildFileTree($project);
            $project->update(['file_tree' => $freshTree]);

            // Get all non-hidden files as "changed"
            $allProjectFiles = $this->projectService->listFiles($project);

            return [
                'status' => 'done',
                'text' => $doneData['response'] ?? $doneData['result'] ?? 'Code generation complete.',
                'files_changed' => !empty($doneData['files_changed']) ? $doneData['files_changed'] : $allProjectFiles,
                'file_tree' => $freshTree,
            ];
        }

        // Still running — return current progress
        $fileTree = $this->projectService->buildFileTree($project);

        $streamData = [];
        try {
            if (File::exists($streamFile)) {
                $streamData = json_decode(File::get($streamFile), true) ?: [];
            }
        } catch (\Throwable $e) {}

        $beforeSnapshot = [];
        try {
            $snapshotPath = $project->storagePath() . '/.claude-before-snapshot';
            if (File::exists($snapshotPath)) {
                $beforeSnapshot = json_decode(File::get($snapshotPath), true) ?: [];
            }
        } catch (\Throwable $e) {}

        $currentFiles = $this->getFileSnapshot($project);
        $changedFiles = array_keys($this->diffSnapshots($beforeSnapshot, $currentFiles));

        return [
            'status' => 'generating',
            'text' => $streamData['text'] ?? 'AI is generating code...',
            'files_changed' => $changedFiles,
            'file_tree' => $fileTree,
        ];
    }

    /**
     * Extract code blocks from Claude's response and write them as files.
     */
    private function extractAndWriteFiles(Project $project, string $response): array
    {
        $dir = $project->storagePath();
        $filesCreated = [];

        // Pattern: ```filename.ext or ```language:filename.ext followed by code block
        // Also matches: **filename.ext** followed by ```code```
        $patterns = [
            // Match: filename (path/to/file.ext) followed by code block
            '/(?:^|\n)(?:#+\s*)?(?:\*\*)?`?([a-zA-Z0-9_\/.-]+\.[a-zA-Z]{1,10})`?(?:\*\*)?(?:\s*[-:])?\s*\n```[a-z]*\n([\s\S]*?)```/m',
            // Match: ```path/to/file.ext\n...code...\n```
            '/```([a-zA-Z0-9_\/.-]+\.[a-zA-Z]{1,10})\n([\s\S]*?)```/m',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $response, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $filePath = trim($match[1]);
                    $content = $match[2];

                    // Skip obviously wrong paths
                    if (str_contains($filePath, '..') || strlen($filePath) > 200) continue;
                    if (in_array($filePath, ['.env', '.gitignore', 'package.json', 'package-lock.json'])) continue;

                    $fullPath = $dir . '/' . $filePath;
                    File::ensureDirectoryExists(dirname($fullPath));
                    File::put($fullPath, $content);
                    $filesCreated[] = $filePath;
                }
            }
        }

        // If no files extracted via patterns, check if response contains a single large code block
        // that should replace index.html or App.jsx
        if (empty($filesCreated)) {
            if (preg_match('/```(?:html|jsx?|tsx?)\n([\s\S]*?)```/', $response, $match)) {
                $code = $match[1];
                if (str_contains($code, '<!DOCTYPE') || str_contains($code, '<html')) {
                    File::put($dir . '/index.html', $code);
                    $filesCreated[] = 'index.html';
                } elseif (str_contains($code, 'function') || str_contains($code, 'const')) {
                    File::put($dir . '/App.jsx', $code);
                    $filesCreated[] = 'App.jsx';
                }
            }
        }

        return $filesCreated;
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

        // Extract code blocks from response and write as files
        $responseText = $doneData['response'] ?? $doneData['result'] ?? '';
        $extractedFiles = $this->extractAndWriteFiles($project, $responseText);

        $afterSnapshot = $this->getFileSnapshot($project);
        $filesChanged = $this->diffSnapshots($beforeSnapshot, $afterSnapshot);

        // Merge extracted files with diff-detected changes
        $allFiles = array_unique(array_merge(array_keys($filesChanged), $extractedFiles));

        // Save AI response
        ProjectMessage::create([
            'project_id'    => $project->id,
            'role'          => 'assistant',
            'content'       => $doneData['response'] ?? $doneData['result'] ?? 'Code generation complete.',
            'files_changed' => $allFiles,
        ]);

        // Update project
        $project->update([
            'status'    => 'ready',
            'file_tree' => $this->projectService->buildFileTree($project),
        ]);

        // Update done file with files_changed
        $doneData['files_changed'] = $allFiles;
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
## FILE STRUCTURE — Create these files:
```
index.html              ← Single-page website with ALL sections (hero, about, features, gallery, contact form, footer)
css/styles.css          ← All custom styles + animations
js/main.js              ← Navigation, smooth scroll, form validation, animations
```
Put ALL content in index.html as sections. Link css/styles.css and js/main.js from index.html.
FS,
        };

        return <<<PROMPT
You are a senior full-stack developer building a production website called "{$project->name}".

## CRITICAL RULES:
1. Create MULTIPLE FILES in SEPARATE FOLDERS. Never put all code in one file.
2. Every component/page MUST be in its OWN file.
3. OVERWRITE the existing index.html with your generated content. Replace it completely.
4. The file structure below is MANDATORY — create every single file listed.
5. START with index.html FIRST, then create other files.

{$fileStructure}

## DESIGN:
- Dark theme with Tailwind CSS CDN: <script src="https://cdn.tailwindcss.com"></script>
- Inter font from Google Fonts
- Premium quality: gradients, hover effects, animations, responsive
- Real content (no lorem ipsum), production-ready code

## IMPORTANT:
- OVERWRITE index.html completely with generated content
- Start creating index.html FIRST
PROMPT;
    }
}
