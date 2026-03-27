<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\File;

class ProjectService
{
    /**
     * Create a new project with starter template files.
     */
    public function createProject(User $user, string $name, string $framework = 'html'): Project
    {
        $project = Project::create([
            'user_id'   => $user->id,
            'name'      => $name,
            'slug'      => Project::generateSlug($name),
            'framework' => $framework,
            'status'    => 'draft',
        ]);

        // Create project directory
        $dir = $project->storagePath();
        File::ensureDirectoryExists($dir);

        // Copy starter template
        $templateDir = storage_path("app/project-templates/{$framework}");
        if (File::isDirectory($templateDir)) {
            File::copyDirectory($templateDir, $dir);
        }

        // Cache file tree
        $project->update(['file_tree' => $this->buildFileTree($project)]);

        return $project;
    }

    /**
     * Build a nested file tree array from disk.
     */
    public function buildFileTree(Project $project): array
    {
        $root = $project->storagePath();
        if (!File::isDirectory($root)) {
            return [];
        }
        return $this->scanDirectory($root, $root);
    }

    private function scanDirectory(string $dir, string $root): array
    {
        $items = [];
        $entries = File::files($dir);
        $dirs = File::directories($dir);

        // Directories first
        sort($dirs);
        foreach ($dirs as $d) {
            $relativePath = str_replace('\\', '/', ltrim(str_replace($root, '', $d), '\\/'));
            $items[] = [
                'name'     => basename($d),
                'path'     => $relativePath,
                'type'     => 'directory',
                'children' => $this->scanDirectory($d, $root),
            ];
        }

        // Then files
        foreach ($entries as $file) {
            $relativePath = str_replace('\\', '/', ltrim(str_replace($root, '', $file->getPathname()), '\\/'));
            $items[] = [
                'name' => $file->getFilename(),
                'path' => $relativePath,
                'type' => 'file',
            ];
        }

        return $items;
    }

    /**
     * List all files flat (for searching).
     */
    public function listFiles(Project $project): array
    {
        $root = $project->storagePath();
        if (!File::isDirectory($root)) {
            return [];
        }

        $files = [];
        $allFiles = File::allFiles($root);
        foreach ($allFiles as $file) {
            $files[] = str_replace('\\', '/', ltrim(str_replace($root, '', $file->getPathname()), '\\/'));
        }
        sort($files);
        return $files;
    }

    /**
     * Read file content.
     */
    public function readFile(Project $project, string $path): ?string
    {
        $this->validatePath($path);
        $fullPath = $project->storagePath() . '/' . $path;

        if (!File::exists($fullPath)) {
            return null;
        }

        return File::get($fullPath);
    }

    /**
     * Write file content (create or update).
     */
    public function writeFile(Project $project, string $path, string $content): void
    {
        $this->validatePath($path);
        $fullPath = $project->storagePath() . '/' . $path;

        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $content);

        // Update cached file tree
        $project->update(['file_tree' => $this->buildFileTree($project)]);
    }

    /**
     * Create a directory.
     */
    public function createDirectory(Project $project, string $path): void
    {
        $this->validatePath($path);
        $fullPath = $project->storagePath() . '/' . $path;
        File::ensureDirectoryExists($fullPath);

        // Put a .gitkeep so empty dirs show in tree
        if (count(File::allFiles($fullPath)) === 0) {
            File::put($fullPath . '/.gitkeep', '');
        }

        $project->update(['file_tree' => $this->buildFileTree($project)]);
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Project $project, string $path): bool
    {
        $this->validatePath($path);
        $fullPath = $project->storagePath() . '/' . $path;

        if (!File::exists($fullPath)) {
            return false;
        }

        File::delete($fullPath);

        // Clean up empty parent directories
        $dir = dirname($fullPath);
        while ($dir !== $project->storagePath() && File::isDirectory($dir) && count(File::allFiles($dir)) === 0 && count(File::directories($dir)) === 0) {
            File::deleteDirectory($dir);
            $dir = dirname($dir);
        }

        $project->update(['file_tree' => $this->buildFileTree($project)]);
        return true;
    }

    /**
     * Rename/move a file.
     */
    public function renameFile(Project $project, string $from, string $to): bool
    {
        $this->validatePath($from);
        $this->validatePath($to);

        $fromPath = $project->storagePath() . '/' . $from;
        $toPath = $project->storagePath() . '/' . $to;

        if (!File::exists($fromPath)) {
            return false;
        }

        File::ensureDirectoryExists(dirname($toPath));
        File::move($fromPath, $toPath);

        $project->update(['file_tree' => $this->buildFileTree($project)]);
        return true;
    }

    /**
     * Delete entire project from disk.
     */
    public function deleteProject(Project $project): void
    {
        $dir = $project->storagePath();
        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }
        $project->delete();
    }

    /**
     * Get MIME type for preview serving.
     */
    public function getMimeType(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'html', 'htm' => 'text/html',
            'css'         => 'text/css',
            'js', 'mjs'   => 'application/javascript',
            'json'        => 'application/json',
            'svg'         => 'image/svg+xml',
            'png'         => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            'ico'         => 'image/x-icon',
            'woff'        => 'font/woff',
            'woff2'       => 'font/woff2',
            'ttf'         => 'font/ttf',
            'txt'         => 'text/plain',
            'xml'         => 'application/xml',
            default       => 'application/octet-stream',
        };
    }

    /**
     * Validate path to prevent directory traversal.
     */
    private function validatePath(string $path): void
    {
        $normalized = str_replace('\\', '/', $path);
        if (str_contains($normalized, '..') || str_starts_with($normalized, '/') || str_starts_with($normalized, '.')) {
            throw new \InvalidArgumentException("Invalid file path: {$path}");
        }
    }
}
