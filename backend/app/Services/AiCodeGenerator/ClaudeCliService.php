<?php

namespace App\Services\AiCodeGenerator;

use App\Models\App;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ClaudeCliService
{
    public function run(Project|App $project, string $prompt, ?string $systemPrompt = null): array
    {
        $projectDir = $project->storagePath();
        File::ensureDirectoryExists($projectDir);

        $sessionFile = $projectDir . '/.claude-session';
        $existingSession = File::exists($sessionFile) ? trim(File::get($sessionFile)) : null;

        $promptFile = tempnam(sys_get_temp_dir(), 'claude_prompt_');
        File::put($promptFile, $prompt);

        $sysPromptFile = null;
        if ($systemPrompt) {
            $sysPromptFile = tempnam(sys_get_temp_dir(), 'claude_sys_');
            File::put($sysPromptFile, $systemPrompt);
        }

        $cmd = $this->buildCommand($projectDir, $promptFile, $sysPromptFile, $existingSession);

        Log::info('Claude CLI: Running', ['project' => $project->id, 'cmd_length' => strlen($cmd)]);

        $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $proc = proc_open($cmd, $descriptors, $pipes, $projectDir);

        if (!is_resource($proc)) {
            $this->cleanup($promptFile, $sysPromptFile);
            return ['success' => false, 'response' => 'Failed to start Claude Code process', 'session_id' => null];
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($proc);

        $this->cleanup($promptFile, $sysPromptFile);

        if ($exitCode !== 0) {
            Log::error('Claude CLI error', ['exit' => $exitCode, 'stderr' => mb_substr($stderr, 0, 500)]);
            return ['success' => false, 'response' => 'Claude error: ' . ($stderr ?: $stdout ?: "Exit {$exitCode}"), 'session_id' => null];
        }

        $result = json_decode($stdout, true);
        if (!$result) {
            return ['success' => true, 'response' => $stdout, 'session_id' => null];
        }

        $sessionId = $result['session_id'] ?? null;
        if ($sessionId) {
            File::put($sessionFile, $sessionId);
        }

        return ['success' => true, 'response' => $result['result'] ?? $stdout, 'session_id' => $sessionId];
    }

    public function runAsync(Project|App $project, string $prompt, ?string $systemPrompt = null): void
    {
        $projectDir = $project->storagePath();
        File::ensureDirectoryExists($projectDir);

        $sessionFile = $projectDir . '/.claude-session';
        $existingSession = File::exists($sessionFile) ? trim(File::get($sessionFile)) : null;

        $promptFile = tempnam(sys_get_temp_dir(), 'claude_prompt_');
        File::put($promptFile, $prompt);

        $sysPromptFile = null;
        if ($systemPrompt) {
            $sysPromptFile = tempnam(sys_get_temp_dir(), 'claude_sys_');
            File::put($sysPromptFile, $systemPrompt);
        }

        $doneFile = $projectDir . '/.claude-done';
        $streamFile = $projectDir . '/.claude-stream';

        @unlink($doneFile);
        File::put($streamFile, json_encode(['status' => 'running', 'text' => 'Claude is writing code...']));

        $claudeCmd = $this->buildCommand($projectDir, $promptFile, $sysPromptFile, $existingSession);

        // Build a wrapper that captures output to .claude-done
        $doneFileEsc = str_replace('\\', '/', $doneFile);
        $sessionFileEsc = str_replace('\\', '/', $sessionFile);
        $promptFileEsc = str_replace('\\', '/', $promptFile);

        if ($this->isWindows()) {
            // Windows: write a .bat wrapper with proper quoting
            $winDoneFile = str_replace('/', '\\', $doneFile);
            $winProjectDir = str_replace('/', '\\', $projectDir);
            $winPromptFile = str_replace('/', '\\', $promptFile);

            $batContent = "@echo off\r\n";
            $batContent .= "cd /d \"" . $winProjectDir . "\"\r\n";
            $batContent .= $claudeCmd . " > \"" . $winDoneFile . "\" 2>&1\r\n";
            $batContent .= "del \"" . $winPromptFile . "\" 2>nul\r\n";
            if ($sysPromptFile) {
                $batContent .= "del \"" . str_replace('/', '\\', $sysPromptFile) . "\" 2>nul\r\n";
            }

            $batFile = tempnam(sys_get_temp_dir(), 'claude_bg_') . '.bat';
            File::put($batFile, $batContent);

            pclose(popen('start /B cmd /c "' . str_replace('/', '\\', $batFile) . '"', 'r'));
        } else {
            // Unix
            $bgCmd = $claudeCmd . ' > ' . escapeshellarg($doneFileEsc) . ' 2>&1';
            $bgCmd .= '; rm -f ' . escapeshellarg($promptFileEsc);
            if ($sysPromptFile) {
                $bgCmd .= ' ' . escapeshellarg(str_replace('\\', '/', $sysPromptFile));
            }
            $bgCmd .= ' &';

            proc_close(proc_open($bgCmd, [['file', '/dev/null', 'r'], ['file', '/dev/null', 'w'], ['file', '/dev/null', 'w']], $pipes, $projectDir));
        }

        Log::info('Claude CLI: Background started', ['project' => $project->id]);
    }

    private function buildCommand(string $projectDir, string $promptFile, ?string $sysPromptFile, ?string $existingSession): string
    {
        $claude = $this->findClaudeBinary();
        $promptContent = File::get($promptFile);

        // Build command directly — no bash wrapper needed
        $cmd = escapeshellarg($claude);
        $cmd .= ' --print';
        $cmd .= ' --output-format json';
        $cmd .= ' --dangerously-skip-permissions';
        $cmd .= ' --model sonnet';

        if ($existingSession) {
            $cmd .= ' --resume ' . escapeshellarg($existingSession);
        }
        if ($sysPromptFile) {
            $cmd .= ' --append-system-prompt-file ' . escapeshellarg(str_replace('\\', '/', $sysPromptFile));
        }

        // Pass prompt directly as argument (escaped)
        $cmd .= ' ' . escapeshellarg($promptContent);

        return $cmd;
    }

    private function findClaudeBinary(): string
    {
        // Windows: check common npm global paths
        if ($this->isWindows()) {
            $appData = getenv('APPDATA') ?: '';
            $paths = [
                $appData . '\\npm\\claude.cmd',
                $appData . '\\npm\\claude',
                'C:\\Program Files\\nodejs\\claude.cmd',
            ];
            foreach ($paths as $p) {
                if (file_exists($p)) return $p;
            }

            // Try where command
            $result = trim(shell_exec('where claude 2>nul') ?? '');
            if ($result) return explode("\n", $result)[0];
        }

        // Unix paths
        $home = getenv('HOME') ?: '';
        $paths = [$home . '/.local/bin/claude', '/usr/local/bin/claude', '/usr/bin/claude'];
        foreach ($paths as $p) {
            if (file_exists($p)) return $p;
        }

        return 'claude';
    }

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function cleanup(string ...$files): void
    {
        foreach ($files as $f) {
            if ($f && file_exists($f)) @unlink($f);
        }
    }
}
