<?php

namespace App\Services;

use App\Models\App;
use App\Models\AppMessage;
use App\Models\User;
use App\Services\AiCodeGenerator\ClaudeCliService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AppBuilderService
{
    public function __construct(private ClaudeCliService $claudeCli) {}

    public function createApp(User $user, string $name, string $framework, array $platforms): App
    {
        $app = App::create([
            'user_id'   => $user->id,
            'name'      => $name,
            'slug'      => App::generateSlug($name),
            'framework' => $framework,
            'platforms' => $platforms,
            'bundle_id' => 'com.webnewbiz.' . preg_replace('/[^a-z0-9]/', '', strtolower($name)),
            'status'    => 'draft',
        ]);

        File::ensureDirectoryExists($app->storagePath());

        return $app;
    }

    public function chatAsync(App $app, string $message): array
    {
        AppMessage::create(['app_id' => $app->id, 'role' => 'user', 'content' => $message]);
        $app->update(['status' => 'generating']);

        $streamFile = $app->storagePath() . '/.claude-stream';
        File::put($streamFile, json_encode(['status' => 'starting', 'text' => '']));

        $snapshotFile = $app->storagePath() . '/.claude-before-snapshot';
        File::put($snapshotFile, json_encode($this->getFileSnapshot($app)));

        $systemPrompt = $this->buildSystemPrompt($app);
        $this->claudeCli->runAsync($app, $message, $systemPrompt);

        return ['success' => true, 'status' => 'generating'];
    }

    public function getStream(App $app): array
    {
        $doneFile = $app->storagePath() . '/.claude-done';

        if (File::exists($doneFile)) {
            $doneData = json_decode(File::get($doneFile), true) ?: [];

            if ($app->status === 'generating') {
                $this->finalize($app, $doneData);
            }

            return [
                'status' => 'done',
                'text' => $doneData['response'] ?? '',
                'files_changed' => $doneData['files_changed'] ?? [],
                'file_tree' => $app->fresh()->file_tree ?? [],
            ];
        }

        return [
            'status' => 'generating',
            'text' => 'Building your app...',
            'files_changed' => [],
            'file_tree' => $this->buildFileTree($app),
        ];
    }

    private function finalize(App $app, array $doneData): void
    {
        $before = [];
        $sf = $app->storagePath() . '/.claude-before-snapshot';
        if (File::exists($sf)) $before = json_decode(File::get($sf), true) ?: [];

        $after = $this->getFileSnapshot($app);
        $changed = [];
        foreach ($after as $p => $m) { if (!isset($before[$p]) || $before[$p] !== $m) $changed[] = $p; }

        AppMessage::create([
            'app_id' => $app->id,
            'role' => 'assistant',
            'content' => $doneData['response'] ?? 'App code generated.',
            'files_changed' => $changed,
        ]);

        $doneData['files_changed'] = $changed;
        File::put($app->storagePath() . '/.claude-done', json_encode($doneData));

        $app->update(['status' => 'ready', 'file_tree' => $this->buildFileTree($app)]);
        @unlink($app->storagePath() . '/.claude-stream');
        @unlink($sf);
    }

    public function buildFileTree(App $app): array
    {
        $root = $app->storagePath();
        if (!File::isDirectory($root)) return [];
        return $this->scanDir($root, $root);
    }

    private function scanDir(string $dir, string $root): array
    {
        $items = [];
        foreach (scandir($dir) as $entry) {
            if ($entry === '.' || $entry === '..' || str_starts_with($entry, '.')) continue;
            $fullPath = $dir . '/' . $entry;
            $relPath = str_replace('\\', '/', ltrim(str_replace($root, '', $fullPath), '\\/'));
            if (is_dir($fullPath)) {
                $items[] = ['name' => $entry, 'path' => $relPath, 'type' => 'directory', 'children' => $this->scanDir($fullPath, $root)];
            } else {
                $items[] = ['name' => $entry, 'path' => $relPath, 'type' => 'file'];
            }
        }
        usort($items, fn($a, $b) => ($a['type'] === 'directory' ? 0 : 1) - ($b['type'] === 'directory' ? 0 : 1) ?: strcmp($a['name'], $b['name']));
        return $items;
    }

    public function readFile(App $app, string $path): ?string
    {
        $full = $app->storagePath() . '/' . $path;
        return File::exists($full) ? File::get($full) : null;
    }

    public function writeFile(App $app, string $path, string $content): void
    {
        $full = $app->storagePath() . '/' . $path;
        File::ensureDirectoryExists(dirname($full));
        File::put($full, $content);
        $app->update(['file_tree' => $this->buildFileTree($app)]);
    }

    public function deleteApp(App $app): void
    {
        $dir = $app->storagePath();
        if (File::isDirectory($dir)) File::deleteDirectory($dir);
        $app->delete();
    }

    private function getFileSnapshot(App $app): array
    {
        $root = $app->storagePath();
        if (!File::isDirectory($root)) return [];
        $snap = [];
        foreach (File::allFiles($root) as $f) {
            $rel = str_replace('\\', '/', ltrim(str_replace($root, '', $f->getPathname()), '\\/'));
            if (str_starts_with($rel, '.')) continue;
            $snap[$rel] = $f->getMTime();
        }
        return $snap;
    }

    private function buildSystemPrompt(App $app): string
    {
        $fw = $app->framework === 'flutter' ? 'Flutter/Dart' : 'React Native with Expo';
        return <<<PROMPT
You are building a mobile app called "{$app->name}" using {$fw}.

## REQUIREMENTS:
1. Use Expo SDK 52+ with React Native
2. Create a professional, production-ready mobile app
3. Use Expo Router for navigation (file-based routing in app/ directory)
4. Use NativeWind (Tailwind for React Native) or StyleSheet for styling
5. Create beautiful, premium UI with smooth animations
6. Include proper screens: Home, Profile, Settings at minimum
7. Use expo-linear-gradient for gradient backgrounds
8. Use @expo/vector-icons for icons
9. Make it work on both iOS and Android
10. Include app.json with proper Expo config

## FILE STRUCTURE:
```
app/                 # Expo Router screens
  (tabs)/            # Tab navigation
    index.tsx        # Home screen
    profile.tsx      # Profile screen
    settings.tsx     # Settings screen
  _layout.tsx        # Root layout
components/          # Shared components
assets/              # Images, fonts
app.json             # Expo config
package.json         # Dependencies
```

## DESIGN STANDARDS:
- Premium, modern mobile design (iOS + Android native feel)
- Smooth transitions and animations
- Proper safe area handling
- Dark mode support
- Clean typography with system fonts
PROMPT;
    }
}
