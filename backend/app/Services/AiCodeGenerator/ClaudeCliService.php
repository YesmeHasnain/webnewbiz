<?php

namespace App\Services\AiCodeGenerator;

use App\Models\App;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Wraps Claude Code CLI for code generation.
 * Uses shell_exec with a temp script to ensure full environment inheritance on Windows.
 */
class ClaudeCliService
{
    /**
     * Run Claude Code CLI in the project directory.
     */
    public function run(Project|App $project, string $prompt, ?string $systemPrompt = null): array
    {
        $projectDir = $project->storagePath();
        File::ensureDirectoryExists($projectDir);

        // Check for existing session (for follow-up messages)
        $sessionFile = $projectDir . '/.claude-session';
        $existingSession = File::exists($sessionFile) ? trim(File::get($sessionFile)) : null;

        Log::info('Claude CLI: Starting', [
            'project' => $project->id,
            'resume'  => $existingSession ? 'yes' : 'no',
            'prompt'  => mb_substr($prompt, 0, 100) . '...',
        ]);

        // Write prompt to temp file (avoids shell escaping issues with long prompts)
        $promptFile = tempnam(sys_get_temp_dir(), 'claude_prompt_');
        File::put($promptFile, $prompt);

        // Write system prompt to temp file if provided
        $sysPromptFile = null;
        if ($systemPrompt) {
            $sysPromptFile = tempnam(sys_get_temp_dir(), 'claude_sys_');
            File::put($sysPromptFile, $systemPrompt);
        }

        // Build the shell script
        $scriptContent = $this->buildScript($projectDir, $promptFile, $sysPromptFile, $existingSession);
        $scriptFile = tempnam(sys_get_temp_dir(), 'claude_run_') . '.sh';
        File::put($scriptFile, $scriptContent);

        // Execute — use proc_open for full env inheritance
        $descriptors = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],  // stderr
        ];

        $proc = proc_open("bash " . escapeshellarg($scriptFile), $descriptors, $pipes, $projectDir);

        if (!is_resource($proc)) {
            $this->cleanup($promptFile, $sysPromptFile, $scriptFile);
            return ['success' => false, 'response' => 'Failed to start Claude Code process', 'session_id' => null];
        }

        fclose($pipes[0]); // close stdin

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($proc);

        // Cleanup temp files
        $this->cleanup($promptFile, $sysPromptFile, $scriptFile);

        if ($exitCode !== 0) {
            Log::error('Claude CLI exited with error', [
                'exit_code' => $exitCode,
                'stderr'    => mb_substr($stderr, 0, 500),
                'stdout'    => mb_substr($stdout, 0, 500),
            ]);
            return [
                'success'    => false,
                'response'   => 'Claude Code error: ' . ($stderr ?: $stdout ?: "Exit code {$exitCode}"),
                'session_id' => null,
            ];
        }

        // Parse JSON output
        $result = json_decode($stdout, true);
        if (!$result) {
            Log::warning('Claude CLI: non-JSON output', ['length' => strlen($stdout)]);
            return ['success' => true, 'response' => $stdout, 'session_id' => null];
        }

        $responseText = $result['result'] ?? $stdout;
        $sessionId = $result['session_id'] ?? null;

        // Save session ID for follow-up messages
        if ($sessionId) {
            File::put($sessionFile, $sessionId);
        }

        Log::info('Claude CLI: Done', [
            'project'    => $project->id,
            'cost'       => $result['cost_usd'] ?? 0,
            'duration'   => ($result['duration_ms'] ?? 0) . 'ms',
            'turns'      => $result['num_turns'] ?? 0,
        ]);

        return [
            'success'    => true,
            'response'   => $responseText,
            'session_id' => $sessionId,
        ];
    }

    /**
     * Run Claude Code CLI in background — returns immediately.
     * Output is written to .claude-done file when complete.
     */
    public function runAsync(Project|App $project, string $prompt, ?string $systemPrompt = null): void
    {
        $projectDir = $project->storagePath();
        File::ensureDirectoryExists($projectDir);

        $sessionFile = $projectDir . '/.claude-session';
        $existingSession = File::exists($sessionFile) ? trim(File::get($sessionFile)) : null;

        // Write prompt + system prompt to temp files
        $promptFile = tempnam(sys_get_temp_dir(), 'claude_prompt_');
        File::put($promptFile, $prompt);

        $sysPromptFile = null;
        if ($systemPrompt) {
            $sysPromptFile = tempnam(sys_get_temp_dir(), 'claude_sys_');
            File::put($sysPromptFile, $systemPrompt);
        }

        // Build a wrapper script that runs Claude and writes result to .claude-done
        $doneFile = str_replace('\\', '/', $projectDir . '/.claude-done');
        $streamFile = str_replace('\\', '/', $projectDir . '/.claude-stream');

        $claudePath = $this->findClaudeBinary();
        $escapedDir = str_replace('\\', '/', $projectDir);
        $escapedPrompt = str_replace('\\', '/', $promptFile);

        $script = "#!/bin/bash\n";
        $script .= "cd " . escapeshellarg($escapedDir) . "\n\n";

        // Update stream file to show running
        $script .= "echo '{\"status\":\"running\",\"text\":\"Claude is writing code...\"}' > " . escapeshellarg($streamFile) . "\n\n";

        // Build claude command — capture output to variable
        $script .= "OUTPUT=$(" . escapeshellarg($claudePath);
        $script .= " --print";
        $script .= " --output-format json";
        $script .= " --dangerously-skip-permissions";
        $script .= " --model sonnet";

        if ($existingSession) {
            $script .= " --resume " . escapeshellarg($existingSession);
        }
        if ($sysPromptFile) {
            $script .= " --append-system-prompt-file " . escapeshellarg(str_replace('\\', '/', $sysPromptFile));
        }
        $script .= ' "$(cat ' . escapeshellarg($escapedPrompt) . ')"';
        $script .= " 2>/dev/null)\n\n";

        // Extract result and session_id from JSON output, write to done file
        $script .= "RESULT=$(echo \"\$OUTPUT\" | python3 -c \"import sys,json; d=json.load(sys.stdin); print(json.dumps({'response':d.get('result',''),'session_id':d.get('session_id',''),'cost':d.get('cost_usd',0),'duration':d.get('duration_ms',0),'turns':d.get('num_turns',0)}))\" 2>/dev/null || echo '{\"response\":\"Code generation complete.\",\"session_id\":\"\"}')\n";
        $script .= "echo \"\$RESULT\" > " . escapeshellarg($doneFile) . "\n\n";

        // Save session ID
        $script .= "SESSION_ID=$(echo \"\$RESULT\" | python3 -c \"import sys,json; print(json.load(sys.stdin).get('session_id',''))\" 2>/dev/null)\n";
        $script .= "if [ -n \"\$SESSION_ID\" ]; then echo \"\$SESSION_ID\" > " . escapeshellarg(str_replace('\\', '/', $sessionFile)) . "; fi\n\n";

        // Cleanup temp files
        $script .= "rm -f " . escapeshellarg($escapedPrompt) . "\n";
        if ($sysPromptFile) {
            $script .= "rm -f " . escapeshellarg(str_replace('\\', '/', $sysPromptFile)) . "\n";
        }

        $scriptFile = tempnam(sys_get_temp_dir(), 'claude_bg_') . '.sh';
        File::put($scriptFile, $script);

        // Remove old done file
        @unlink($projectDir . '/.claude-done');

        // Start background process — must work on Windows
        $scriptPath = str_replace('\\', '/', $scriptFile);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows: use "start /B bash ..." to run detached
            $bgCmd = 'start /B bash ' . escapeshellarg($scriptPath);
            pclose(popen($bgCmd, 'r'));
        } else {
            $bgCmd = "bash " . escapeshellarg($scriptPath) . " > /dev/null 2>&1 &";
            proc_close(proc_open($bgCmd, [['file', '/dev/null', 'r'], ['file', '/dev/null', 'w'], ['file', '/dev/null', 'w']], $pipes, $projectDir));
        }

        Log::info('Claude CLI: Started background process', [
            'app' => $project->id,
            'resume'  => $existingSession ? 'yes' : 'no',
        ]);
    }

    /**
     * Build a bash script that runs Claude Code CLI.
     * This ensures full environment inheritance regardless of how PHP is invoked.
     */
    private function buildScript(string $projectDir, string $promptFile, ?string $sysPromptFile, ?string $existingSession): string
    {
        $claudePath = $this->findClaudeBinary();
        $escapedDir = str_replace('\\', '/', $projectDir);
        $escapedPrompt = str_replace('\\', '/', $promptFile);

        $script = "#!/bin/bash\n";
        $script .= "cd " . escapeshellarg($escapedDir) . "\n";

        // Build claude command
        $script .= escapeshellarg($claudePath);
        $script .= " --print";
        $script .= " --output-format json";
        $script .= " --dangerously-skip-permissions";
        $script .= " --model sonnet";

        if ($existingSession) {
            $script .= " --resume " . escapeshellarg($existingSession);
        }

        if ($sysPromptFile) {
            $escapedSys = str_replace('\\', '/', $sysPromptFile);
            $script .= " --append-system-prompt-file " . escapeshellarg($escapedSys);
        }

        // Read prompt from file to avoid shell escaping issues
        $script .= ' "$(cat ' . escapeshellarg($escapedPrompt) . ')"';
        $script .= "\n";

        return $script;
    }

    /**
     * Find the Claude Code binary path.
     */
    private function findClaudeBinary(): string
    {
        $home = getenv('USERPROFILE') ?: getenv('HOME') ?: '';
        $home = str_replace('\\', '/', $home);

        $paths = [
            $home . '/.local/bin/claude',
            '/usr/local/bin/claude',
            '/usr/bin/claude',
        ];

        foreach ($paths as $path) {
            if (File::exists($path) || File::exists($path . '.exe')) {
                return $path;
            }
        }

        return 'claude';
    }

    /**
     * Clean up temporary files.
     */
    private function cleanup(string ...$files): void
    {
        foreach ($files as $file) {
            if ($file && File::exists($file)) {
                @unlink($file);
            }
        }
    }
}
