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

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key');
        $this->model = config('services.anthropic.model', 'claude-sonnet-4-20250514');
        $this->apiUrl = config('services.anthropic.api_url', 'https://api.anthropic.com/v1');

        $this->geminiApiKey = config('services.gemini.api_key');
        $this->geminiModel = config('services.gemini.model', 'gemini-2.0-flash');
        $this->geminiApiUrl = config('services.gemini.api_url');
    }

    public function chat(array $messages, string $systemPrompt, int $maxTokens = 4096): array
    {
        // Try Claude first
        $result = $this->chatWithClaude($messages, $systemPrompt, $maxTokens);
        if ($result['success']) {
            return $result;
        }

        Log::warning('Claude chat failed, falling back to Gemini: ' . ($result['message'] ?? 'Unknown'));

        // Fallback to Gemini
        return $this->chatWithGemini($messages, $systemPrompt, $maxTokens);
    }

    private function chatWithClaude(array $messages, string $systemPrompt, int $maxTokens): array
    {
        if (!$this->apiKey) {
            return ['success' => false, 'message' => 'Anthropic API not configured'];
        }

        try {
            $response = Http::timeout(60)
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

            Log::error('Anthropic API failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'API request failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("Anthropic API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function chatWithGemini(array $messages, string $systemPrompt, int $maxTokens): array
    {
        if (!$this->geminiApiKey) {
            return ['success' => false, 'message' => 'Gemini API not configured'];
        }

        try {
            // Convert messages to Gemini format
            $contents = [];
            foreach ($messages as $msg) {
                $role = $msg['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = ['role' => $role, 'parts' => [['text' => $msg['content']]]];
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
                Log::info('Gemini chat response generated (fallback)');
                return ['success' => true, 'data' => $text];
            }

            Log::error('Gemini API failed: ' . $response->status() . ' - ' . $response->body());
            return ['success' => false, 'message' => 'Gemini API failed: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("Gemini API error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
