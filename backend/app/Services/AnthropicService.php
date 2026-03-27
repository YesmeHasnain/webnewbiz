<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicService
{
    private ?string $apiKey;
    private string $model;
    private string $apiUrl;

    private ?string $geminiApiKey;
    private string $geminiModel;
    private string $geminiApiUrl;

    private ?string $grokApiKey;
    private string $grokModel;
    private string $grokApiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key');
        $this->model = config('services.anthropic.model', 'claude-sonnet-4-20250514');
        $this->apiUrl = config('services.anthropic.api_url', 'https://api.anthropic.com/v1');

        $this->geminiApiKey = config('services.gemini.api_key');
        $this->geminiModel = config('services.gemini.model', 'gemini-2.0-flash');
        $this->geminiApiUrl = config('services.gemini.api_url');

        $this->grokApiKey = config('services.grok.api_key');
        $this->grokModel = config('services.grok.model', 'grok-3-mini');
        $this->grokApiUrl = config('services.grok.api_url', 'https://api.x.ai/v1');
    }

    /**
     * Simple chat (no tools). Primary → Fallback.
     * Set AI_PROVIDER in .env: grok (default), gemini, or claude.
     */
    public function chat(array $messages, string $systemPrompt, int $maxTokens = 4096): array
    {
        $provider = config('services.ai_provider', 'grok');
        $methods = $this->getChatOrder($provider);

        foreach ($methods as $i => $method) {
            $result = $this->$method($messages, $systemPrompt, $maxTokens);
            if ($result['success']) return $result;
            if ($i < count($methods) - 1) {
                Log::warning("{$method} failed, trying next fallback");
            }
        }
        return $result;
    }

    /**
     * Chat with Tool-Use / Function Calling. Primary → Fallback.
     */
    public function chatWithTools(array $messages, string $systemPrompt, array $tools, int $maxTokens = 4096): array
    {
        $provider = config('services.ai_provider', 'grok');
        $methods = $this->getToolsOrder($provider);

        foreach ($methods as $i => $method) {
            $result = $this->$method($messages, $systemPrompt, $tools, $maxTokens);
            if ($result['success']) return $result;
            if ($i < count($methods) - 1) {
                Log::warning("{$method} failed, trying next fallback");
            }
        }
        return $result;
    }

    private function getChatOrder(string $provider): array
    {
        return match ($provider) {
            'claude-cli' => ['chatWithClaudeCli', 'chatWithGemini', 'chatWithClaude'],
            'claude'     => ['chatWithClaude', 'chatWithGrok', 'chatWithGemini'],
            'gemini'     => ['chatWithGemini', 'chatWithGrok', 'chatWithClaude'],
            'grok'       => ['chatWithGrok', 'chatWithGemini', 'chatWithClaude'],
            default      => ['chatWithClaudeCli', 'chatWithGemini', 'chatWithClaude'],
        };
    }

    private function getToolsOrder(string $provider): array
    {
        return match ($provider) {
            'claude-cli' => ['claudeCliWithTools', 'geminiWithTools', 'claudeWithTools'],
            'claude'     => ['claudeWithTools', 'grokWithTools', 'geminiWithTools'],
            'gemini'     => ['geminiWithTools', 'grokWithTools', 'claudeWithTools'],
            'grok'       => ['grokWithTools', 'geminiWithTools', 'claudeWithTools'],
            default      => ['claudeCliWithTools', 'geminiWithTools', 'claudeWithTools'],
        };
    }

    // ══════════════════════════════════════════
    // ─── CLAUDE CLI (local, free) ───────────
    // ══════════════════════════════════════════

    private function chatWithClaudeCli(array $messages, string $systemPrompt, int $maxTokens): array
    {
        try {
            // Build the prompt from messages
            $prompt = $this->buildCliPrompt($messages);

            $claudePath = $this->findClaudeBinary();
            $cmd = [$claudePath, '--print', '--output-format', 'json', '--dangerously-skip-permissions', '--model', 'sonnet'];

            if ($systemPrompt) {
                $cmd[] = '--append-system-prompt';
                $cmd[] = $systemPrompt;
            }

            $cmd[] = $prompt;

            $process = new \Symfony\Component\Process\Process($cmd, null, null, null, 120);
            $process->run();

            if ($process->getExitCode() !== 0) {
                $err = $process->getErrorOutput() ?: $process->getOutput();
                Log::error('Claude CLI chat failed: ' . mb_substr($err, 0, 300));
                return ['success' => false, 'message' => 'Claude CLI failed: ' . mb_substr($err, 0, 200)];
            }

            $stdout = $process->getOutput();
            $result = json_decode($stdout, true);
            $text = $result['result'] ?? $stdout;

            Log::info('Claude CLI chat done', ['cost' => $result['cost_usd'] ?? 0]);
            return ['success' => true, 'data' => $text];
        } catch (\Exception $e) {
            Log::error("Claude CLI error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function claudeCliWithTools(array $messages, string $systemPrompt, array $tools, int $maxTokens): array
    {
        try {
            // Build prompt that includes tool definitions and asks for JSON tool calls
            $toolDescriptions = $this->buildToolPromptForCli($tools);
            $userPrompt = $this->buildCliPrompt($messages);

            $fullSystemPrompt = $systemPrompt . "\n\n" . $toolDescriptions;

            $claudePath = $this->findClaudeBinary();
            $cmd = [$claudePath, '--print', '--output-format', 'json', '--dangerously-skip-permissions', '--model', 'sonnet'];
            $cmd[] = '--append-system-prompt';
            $cmd[] = $fullSystemPrompt;
            $cmd[] = $userPrompt;

            $process = new \Symfony\Component\Process\Process($cmd, null, null, null, 180);
            $process->run();

            if ($process->getExitCode() !== 0) {
                $err = $process->getErrorOutput() ?: $process->getOutput();
                Log::error('Claude CLI tools failed: ' . mb_substr($err, 0, 300));
                return ['success' => false, 'message' => 'Claude CLI failed'];
            }

            $stdout = $process->getOutput();
            $result = json_decode($stdout, true);
            $text = $result['result'] ?? $stdout;

            Log::info('Claude CLI tools done', ['cost' => $result['cost_usd'] ?? 0]);

            // Parse tool calls from the response text
            return $this->parseCliToolResponse($text);
        } catch (\Exception $e) {
            Log::error("Claude CLI tools error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function buildCliPrompt(array $messages): string
    {
        $parts = [];
        foreach ($messages as $msg) {
            $role = $msg['role'];
            $content = is_string($msg['content']) ? $msg['content'] : json_encode($msg['content']);

            // Only include user/assistant text messages (skip tool blocks for CLI)
            if (is_string($msg['content'])) {
                if ($role === 'user') {
                    $parts[] = $content;
                }
            }
        }
        return end($parts) ?: 'Hello';
    }

    private function buildToolPromptForCli(array $tools): string
    {
        $prompt = "You have the following tools available. When you want to use a tool, respond with EXACTLY this JSON format (and nothing else before it):\n";
        $prompt .= "```json\n{\"tool_calls\": [{\"name\": \"tool_name\", \"input\": {\"param\": \"value\"}}]}\n```\n";
        $prompt .= "After the JSON block, add your explanation text.\n\n";
        $prompt .= "If you don't need to use any tool, just respond with plain text.\n\n";
        $prompt .= "AVAILABLE TOOLS:\n";

        foreach ($tools as $tool) {
            $name = $tool['name'];
            $desc = $tool['description'];
            $params = $tool['input_schema']['properties'] ?? [];
            $required = $tool['input_schema']['required'] ?? [];

            $prompt .= "- **{$name}**: {$desc}\n";
            if (!empty($params) && !($params instanceof \stdClass && empty((array)$params))) {
                $paramList = [];
                foreach ((array)$params as $pName => $pDef) {
                    $req = in_array($pName, $required) ? ' (required)' : '';
                    $pDesc = $pDef['description'] ?? $pDef['type'] ?? '';
                    $paramList[] = "  - {$pName}: {$pDesc}{$req}";
                }
                $prompt .= implode("\n", $paramList) . "\n";
            }
        }

        return $prompt;
    }

    private function parseCliToolResponse(string $text): array
    {
        $content = [];
        $hasToolCalls = false;

        // Try to extract JSON tool_calls block
        if (preg_match('/```json\s*(\{[\s\S]*?"tool_calls"[\s\S]*?\})\s*```/i', $text, $m)) {
            $json = json_decode($m[1], true);
            if ($json && !empty($json['tool_calls'])) {
                $hasToolCalls = true;
                foreach ($json['tool_calls'] as $tc) {
                    $content[] = [
                        'type' => 'tool_use',
                        'id' => 'call_' . bin2hex(random_bytes(8)),
                        'name' => $tc['name'],
                        'input' => $tc['input'] ?? $tc['arguments'] ?? [],
                    ];
                }
                // Get text after the JSON block
                $textAfter = trim(preg_replace('/```json[\s\S]*?```/', '', $text));
                if ($textAfter) {
                    $content[] = ['type' => 'text', 'text' => $textAfter];
                }
            }
        } elseif (preg_match('/\{\s*"tool_calls"\s*:/i', $text, $m, PREG_OFFSET_CAPTURE)) {
            // Try without code fence
            $jsonStart = $m[0][1];
            $jsonStr = substr($text, $jsonStart);
            // Find matching closing brace
            $depth = 0;
            $end = 0;
            for ($i = 0; $i < strlen($jsonStr); $i++) {
                if ($jsonStr[$i] === '{') $depth++;
                elseif ($jsonStr[$i] === '}') { $depth--; if ($depth === 0) { $end = $i + 1; break; } }
            }
            if ($end > 0) {
                $json = json_decode(substr($jsonStr, 0, $end), true);
                if ($json && !empty($json['tool_calls'])) {
                    $hasToolCalls = true;
                    foreach ($json['tool_calls'] as $tc) {
                        $content[] = [
                            'type' => 'tool_use',
                            'id' => 'call_' . bin2hex(random_bytes(8)),
                            'name' => $tc['name'],
                            'input' => $tc['input'] ?? $tc['arguments'] ?? [],
                        ];
                    }
                    $textBefore = trim(substr($text, 0, $jsonStart));
                    $textAfter = trim(substr($text, $jsonStart + $end));
                    $surrounding = trim($textBefore . "\n" . $textAfter);
                    if ($surrounding) {
                        $content[] = ['type' => 'text', 'text' => $surrounding];
                    }
                }
            }
        }

        if (!$hasToolCalls) {
            $content[] = ['type' => 'text', 'text' => $text];
        }

        return [
            'success' => true,
            'content' => $content,
            'stop_reason' => $hasToolCalls ? 'tool_use' : 'end_turn',
            'usage' => [],
        ];
    }

    private function findClaudeBinary(): string
    {
        $paths = [
            getenv('USERPROFILE') . '/.local/bin/claude',
            getenv('USERPROFILE') . '/.local/bin/claude.exe',
            '/usr/local/bin/claude',
            '/usr/bin/claude',
            'claude',
        ];
        foreach ($paths as $path) {
            if ($path === 'claude' || file_exists($path)) return $path;
        }
        return 'claude';
    }

    // ══════════════════════════════════════════
    // ─── GROK (OpenAI-compatible) ───────────
    // ══════════════════════════════════════════

    private function chatWithGrok(array $messages, string $systemPrompt, int $maxTokens): array
    {
        if (!$this->grokApiKey) {
            return ['success' => false, 'message' => 'Grok API not configured'];
        }

        try {
            $grokMessages = [['role' => 'system', 'content' => $systemPrompt]];
            foreach ($messages as $msg) {
                $content = is_string($msg['content']) ? $msg['content'] : json_encode($msg['content']);
                $grokMessages[] = ['role' => $msg['role'], 'content' => $content];
            }

            $response = Http::timeout(90)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->grokApiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->grokApiUrl}/chat/completions", [
                    'model' => $this->grokModel,
                    'messages' => $grokMessages,
                    'max_tokens' => $maxTokens,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $text = $response->json('choices.0.message.content', '');
                Log::info('Grok chat response generated');
                return ['success' => true, 'data' => $text];
            }

            Log::error('Grok API failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'Grok API failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("Grok API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function grokWithTools(array $messages, string $systemPrompt, array $tools, int $maxTokens): array
    {
        if (!$this->grokApiKey) {
            return ['success' => false, 'message' => 'Grok API not configured'];
        }

        try {
            // Convert Claude tools → OpenAI function calling format
            $openaiTools = $this->convertToolsToOpenAI($tools);

            // Convert messages to OpenAI format
            $grokMessages = $this->convertMessagesToOpenAI($messages, $systemPrompt);

            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->grokApiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->grokApiUrl}/chat/completions", [
                    'model' => $this->grokModel,
                    'messages' => $grokMessages,
                    'tools' => $openaiTools,
                    'max_tokens' => $maxTokens,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $this->parseOpenAIToolResponse($response->json());
            }

            $status = $response->status();
            Log::error("Grok Tool-Use failed: {$status} - " . $response->body());
            return ['success' => false, 'message' => "Grok API failed: {$status}"];
        } catch (\Exception $e) {
            Log::error("Grok Tool-Use error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Convert Claude tool definitions → OpenAI function calling format.
     */
    private function convertToolsToOpenAI(array $claudeTools): array
    {
        $openaiTools = [];
        foreach ($claudeTools as $tool) {
            $params = $tool['input_schema'] ?? ['type' => 'object', 'properties' => (object)[]];
            if (isset($params['properties']) && empty((array)$params['properties'])) {
                $params['properties'] = (object)[];
            }

            $openaiTools[] = [
                'type' => 'function',
                'function' => [
                    'name' => $tool['name'],
                    'description' => $tool['description'],
                    'parameters' => $params,
                ],
            ];
        }
        return $openaiTools;
    }

    /**
     * Convert messages to OpenAI format, handling tool_use/tool_result blocks.
     */
    private function convertMessagesToOpenAI(array $messages, string $systemPrompt): array
    {
        $result = [['role' => 'system', 'content' => $systemPrompt]];

        foreach ($messages as $msg) {
            $role = $msg['role'];
            $content = $msg['content'];

            if (is_string($content)) {
                $result[] = ['role' => $role, 'content' => $content];
                continue;
            }

            if (is_array($content)) {
                $textParts = [];
                $toolCalls = [];
                $toolResults = [];

                foreach ($content as $block) {
                    $type = $block['type'] ?? '';
                    if ($type === 'text') {
                        $textParts[] = $block['text'];
                    } elseif ($type === 'tool_use') {
                        $args = $block['input'] ?? [];
                        $toolCalls[] = [
                            'id' => $block['id'] ?? ('call_' . bin2hex(random_bytes(4))),
                            'type' => 'function',
                            'function' => [
                                'name' => $block['name'],
                                'arguments' => json_encode(empty($args) ? (object)[] : $args),
                            ],
                        ];
                    } elseif ($type === 'tool_result') {
                        $toolResults[] = [
                            'role' => 'tool',
                            'tool_call_id' => $block['tool_use_id'] ?? '',
                            'content' => is_string($block['content']) ? $block['content'] : json_encode($block['content']),
                        ];
                    }
                }

                if ($role === 'assistant') {
                    $msg = ['role' => 'assistant'];
                    if (!empty($textParts)) $msg['content'] = implode("\n", $textParts);
                    else $msg['content'] = null;
                    if (!empty($toolCalls)) $msg['tool_calls'] = $toolCalls;
                    $result[] = $msg;
                } elseif (!empty($toolResults)) {
                    foreach ($toolResults as $tr) {
                        $result[] = $tr;
                    }
                } else {
                    $result[] = ['role' => $role, 'content' => implode("\n", $textParts)];
                }
            }
        }

        return $result;
    }

    /**
     * Parse OpenAI-compatible response into Claude-compatible format.
     */
    private function parseOpenAIToolResponse(array $data): array
    {
        $choice = $data['choices'][0] ?? [];
        $message = $choice['message'] ?? [];
        $finishReason = $choice['finish_reason'] ?? 'stop';

        $content = [];

        if (!empty($message['content'])) {
            $content[] = ['type' => 'text', 'text' => $message['content']];
        }

        $hasToolCalls = false;
        if (!empty($message['tool_calls'])) {
            $hasToolCalls = true;
            foreach ($message['tool_calls'] as $tc) {
                $args = json_decode($tc['function']['arguments'] ?? '{}', true) ?: [];
                $content[] = [
                    'type' => 'tool_use',
                    'id' => $tc['id'] ?? ('call_' . bin2hex(random_bytes(4))),
                    'name' => $tc['function']['name'],
                    'input' => $args,
                ];
            }
        }

        return [
            'success' => true,
            'content' => $content,
            'stop_reason' => $hasToolCalls ? 'tool_use' : 'end_turn',
            'usage' => $data['usage'] ?? [],
        ];
    }

    // ══════════════════════════════════════════
    // ─── GEMINI ──────────────────────────────
    // ══════════════════════════════════════════

    private function geminiWithTools(array $messages, string $systemPrompt, array $tools, int $maxTokens): array
    {
        if (!$this->geminiApiKey) {
            return ['success' => false, 'message' => 'Gemini API not configured'];
        }

        try {
            // Convert Claude tool format → Gemini functionDeclarations
            $geminiTools = $this->convertToolsToGemini($tools);

            // Convert messages to Gemini format
            $contents = $this->convertMessagesToGemini($messages);

            $response = Http::timeout(120)->post(
                "{$this->geminiApiUrl}/models/{$this->geminiModel}:generateContent?key={$this->geminiApiKey}",
                [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'tools' => [['functionDeclarations' => $geminiTools]],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => $maxTokens,
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                return $this->parseGeminiToolResponse($data);
            }

            $status = $response->status();
            $body = $response->body();
            Log::error("Gemini Tool-Use API failed: {$status} - {$body}");
            return ['success' => false, 'message' => "Gemini API failed: {$status}"];
        } catch (\Exception $e) {
            Log::error("Gemini Tool-Use error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Convert Claude tool definitions to Gemini functionDeclarations.
     */
    private function convertToolsToGemini(array $claudeTools): array
    {
        $geminiTools = [];

        foreach ($claudeTools as $tool) {
            $params = $tool['input_schema'] ?? [];

            // Ensure properties is an object, not empty array
            if (isset($params['properties']) && empty($params['properties'])) {
                $params['properties'] = (object)[];
            }

            // Gemini doesn't support (object)[] well, use a dummy param for no-arg tools
            if (empty((array)($params['properties'] ?? []))) {
                $params = [
                    'type' => 'object',
                    'properties' => (object)[],
                ];
                unset($params['required']);
            }

            $geminiTools[] = [
                'name' => $tool['name'],
                'description' => $tool['description'],
                'parameters' => $params,
            ];
        }

        return $geminiTools;
    }

    /**
     * Convert messages to Gemini format, handling tool_use and tool_result blocks.
     */
    private function convertMessagesToGemini(array $messages): array
    {
        $contents = [];

        foreach ($messages as $msg) {
            $role = ($msg['role'] === 'assistant') ? 'model' : 'user';
            $content = $msg['content'] ?? '';

            // String content — simple text message
            if (is_string($content)) {
                $contents[] = ['role' => $role, 'parts' => [['text' => $content]]];
                continue;
            }

            // Array content — could be Claude-format tool_use or tool_result blocks
            if (is_array($content)) {
                $parts = [];

                foreach ($content as $block) {
                    $type = $block['type'] ?? '';

                    if ($type === 'text') {
                        $parts[] = ['text' => $block['text']];
                    } elseif ($type === 'tool_use') {
                        // Claude tool_use → Gemini functionCall
                        $args = $block['input'] ?? [];
                        if (empty($args) || (is_array($args) && count($args) === 0)) {
                            $args = (object)[];
                        }
                        $parts[] = ['functionCall' => [
                            'name' => $block['name'],
                            'args' => $args,
                        ]];
                    } elseif ($type === 'tool_result') {
                        // Claude tool_result → Gemini functionResponse
                        $responseContent = $block['content'] ?? '';
                        if (is_string($responseContent)) {
                            $decoded = json_decode($responseContent, true);
                            $responseContent = $decoded ?: ['result' => $responseContent];
                        }
                        $parts[] = ['functionResponse' => [
                            'name' => $block['tool_name'] ?? $block['tool_use_id'] ?? 'unknown',
                            'response' => $responseContent,
                        ]];
                    }
                }

                if (!empty($parts)) {
                    $contents[] = ['role' => $role, 'parts' => $parts];
                }
            }
        }

        return $contents;
    }

    /**
     * Parse Gemini response into Claude-compatible format.
     */
    private function parseGeminiToolResponse(array $data): array
    {
        $candidate = $data['candidates'][0] ?? [];
        $parts = $candidate['content']['parts'] ?? [];
        $finishReason = $candidate['finishReason'] ?? 'STOP';

        $content = [];
        $hasToolCalls = false;

        foreach ($parts as $part) {
            if (isset($part['text'])) {
                $content[] = [
                    'type' => 'text',
                    'text' => $part['text'],
                ];
            } elseif (isset($part['functionCall'])) {
                $hasToolCalls = true;
                $content[] = [
                    'type' => 'tool_use',
                    'id' => 'call_' . bin2hex(random_bytes(8)),
                    'name' => $part['functionCall']['name'],
                    'input' => $part['functionCall']['args'] ?? [],
                ];
            }
        }

        return [
            'success' => true,
            'content' => $content,
            'stop_reason' => $hasToolCalls ? 'tool_use' : 'end_turn',
            'usage' => $data['usageMetadata'] ?? [],
        ];
    }

    private function chatWithGemini(array $messages, string $systemPrompt, int $maxTokens): array
    {
        if (!$this->geminiApiKey) {
            return ['success' => false, 'message' => 'Gemini API not configured'];
        }

        try {
            $contents = [];
            foreach ($messages as $msg) {
                $role = $msg['role'] === 'assistant' ? 'model' : 'user';
                $text = is_string($msg['content']) ? $msg['content'] : json_encode($msg['content']);
                $contents[] = ['role' => $role, 'parts' => [['text' => $text]]];
            }

            $response = Http::timeout(60)->post(
                "{$this->geminiApiUrl}/models/{$this->geminiModel}:generateContent?key={$this->geminiApiKey}",
                [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => $maxTokens,
                    ],
                ]
            );

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text', '');
                Log::info('Gemini chat response generated');
                return ['success' => true, 'data' => $text];
            }

            Log::error('Gemini API failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'Gemini API failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("Gemini API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ══════════════════════════════════════════
    // ─── CLAUDE (fallback) ──────────────────
    // ══════════════════════════════════════════

    private function claudeWithTools(array $messages, string $systemPrompt, array $tools, int $maxTokens): array
    {
        if (!$this->apiKey) {
            return ['success' => false, 'message' => 'Anthropic API not configured'];
        }

        $maxRetries = 3;
        $lastError = '';

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::timeout(120)
                    ->withHeaders([
                        'x-api-key' => $this->apiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->apiUrl}/messages", [
                        'model' => $this->model,
                        'max_tokens' => $maxTokens,
                        'system' => $systemPrompt,
                        'messages' => $messages,
                        'tools' => $tools,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'content' => $data['content'] ?? [],
                        'stop_reason' => $data['stop_reason'] ?? 'end_turn',
                        'usage' => $data['usage'] ?? [],
                    ];
                }

                $status = $response->status();
                $lastError = "API request failed: {$status}";

                if (in_array($status, [500, 529, 429]) && $attempt < $maxRetries) {
                    sleep($attempt * 2);
                    continue;
                }

                Log::error("Claude Tool-Use API failed: {$status} - " . $response->body());
                return ['success' => false, 'message' => $lastError];
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                if ($attempt < $maxRetries) {
                    sleep($attempt * 2);
                    continue;
                }
                Log::error("Claude Tool-Use error: {$lastError}");
                return ['success' => false, 'message' => $lastError];
            }
        }

        return ['success' => false, 'message' => $lastError];
    }

    private function chatWithClaude(array $messages, string $systemPrompt, int $maxTokens): array
    {
        if (!$this->apiKey) {
            return ['success' => false, 'message' => 'Anthropic API not configured'];
        }

        $maxRetries = 3;
        $lastError = '';

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::timeout(90)
                    ->withHeaders([
                        'x-api-key' => $this->apiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])
                    ->post("{$this->apiUrl}/messages", [
                        'model' => $this->model,
                        'max_tokens' => $maxTokens,
                        'system' => $systemPrompt,
                        'messages' => $messages,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['content'][0]['text'] ?? '';
                    return ['success' => true, 'data' => $text];
                }

                $status = $response->status();
                $lastError = "API request failed: {$status}";

                if (in_array($status, [500, 529, 429]) && $attempt < $maxRetries) {
                    sleep($attempt * 2);
                    continue;
                }

                Log::error("Claude API failed: {$status} - " . $response->body());
                return ['success' => false, 'message' => $lastError];
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                if ($attempt < $maxRetries) {
                    sleep($attempt * 2);
                    continue;
                }
                Log::error("Claude API error: {$lastError}");
                return ['success' => false, 'message' => $lastError];
            }
        }

        return ['success' => false, 'message' => $lastError];
    }
}
