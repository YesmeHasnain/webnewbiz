<?php
namespace WebnewBiz\Builder;

if (!defined('ABSPATH')) exit;

class AI_Service {

    private ?string $claude_api_key;
    private string $claude_model;
    private ?string $gemini_api_key;

    public function __construct() {
        $this->claude_api_key = get_option('wnb_claude_api_key', '');
        $this->claude_model   = get_option('wnb_claude_model', 'claude-sonnet-4-20250514');
        $this->gemini_api_key = get_option('wnb_gemini_api_key', '');
    }

    /**
     * Generate text using Claude (primary) with Gemini fallback.
     */
    public function generate(string $prompt, string $system_prompt = '', int $max_tokens = 4096): array {
        // Try Claude first
        if ($this->claude_api_key) {
            $result = $this->call_claude($prompt, $system_prompt, $max_tokens);
            if ($result['success']) {
                return $result;
            }
        }

        // Fallback to Gemini
        if ($this->gemini_api_key) {
            $full_prompt = $system_prompt ? "{$system_prompt}\n\n{$prompt}" : $prompt;
            return $this->call_gemini($full_prompt, $max_tokens);
        }

        return ['success' => false, 'message' => 'No AI API key configured. Please set a Claude or Gemini API key in WebnewBiz Settings.'];
    }

    /**
     * Perform a text action (rewrite, simplify, expand, etc.) for the Elementor editor.
     */
    public function text_action(string $text, string $action, string $widget_type = 'text', array $extra = []): array {
        $action_prompts = [
            'rewrite'      => "Rewrite the following {$widget_type} content while keeping the same meaning. Make it fresh and engaging:",
            'simplify'     => "Simplify the following {$widget_type} content. Use shorter sentences and simpler words:",
            'expand'       => "Expand the following {$widget_type} content with more detail, examples, and supporting points. Make it roughly twice as long:",
            'shorten'      => "Shorten the following {$widget_type} content to about half its length while preserving the key message:",
            'fix_grammar'  => "Fix any grammar, spelling, and punctuation errors in the following {$widget_type} content. Keep the original style and meaning:",
            'change_tone'  => "Rewrite the following {$widget_type} content in a " . ($extra['tone'] ?? 'professional') . " tone:",
            'translate'    => "Translate the following {$widget_type} content to " . ($extra['language'] ?? 'English') . ". Keep the formatting:",
        ];

        $action_prompt = $action_prompts[$action] ?? "Improve the following {$widget_type} content:";
        $prompt = "{$action_prompt}\n\n{$text}";

        $system = "You are a professional website content editor. Return ONLY the improved text, no explanations, no quotes, no markdown formatting. Keep the same HTML tags if present.";

        return $this->generate($prompt, $system, 2048);
    }

    /**
     * Multi-turn chat using Claude (for the AI Builder panel).
     */
    public function generate_chat(array $messages, string $system_prompt = '', int $max_tokens = 8192): array {
        // Try Claude first
        if ($this->claude_api_key) {
            $result = $this->call_claude_chat($messages, $system_prompt, $max_tokens);
            if ($result['success']) {
                return $result;
            }
        }

        // Fallback to Gemini (flatten messages)
        if ($this->gemini_api_key) {
            $flat = $system_prompt ? $system_prompt . "\n\n" : '';
            foreach ($messages as $msg) {
                $flat .= ($msg['role'] === 'user' ? 'User: ' : 'Assistant: ') . $msg['content'] . "\n\n";
            }
            return $this->call_gemini($flat, $max_tokens);
        }

        return ['success' => false, 'message' => 'No AI API key configured.'];
    }

    /**
     * Call Claude API with multi-turn messages.
     */
    private function call_claude_chat(array $messages, string $system_prompt, int $max_tokens): array {
        $payload = [
            'model'      => $this->claude_model,
            'max_tokens' => $max_tokens,
            'messages'   => $messages,
        ];

        if ($system_prompt) {
            $payload['system'] = $system_prompt;
        }

        $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
            'timeout' => 120,
            'headers' => [
                'Content-Type'      => 'application/json',
                'x-api-key'         => $this->claude_api_key,
                'anthropic-version' => '2023-06-01',
            ],
            'body' => wp_json_encode($payload),
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => 'Claude API error: ' . $response->get_error_message()];
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200 || empty($body['content'][0]['text'])) {
            $error = $body['error']['message'] ?? "HTTP {$code}";
            return ['success' => false, 'message' => "Claude API error: {$error}"];
        }

        return ['success' => true, 'data' => $body['content'][0]['text']];
    }

    /**
     * Call Claude API via wp_remote_post.
     */
    private function call_claude(string $prompt, string $system_prompt, int $max_tokens): array {
        $payload = [
            'model'      => $this->claude_model,
            'max_tokens' => $max_tokens,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ];

        if ($system_prompt) {
            $payload['system'] = $system_prompt;
        }

        $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
            'timeout' => 120,
            'headers' => [
                'Content-Type'      => 'application/json',
                'x-api-key'         => $this->claude_api_key,
                'anthropic-version' => '2023-06-01',
            ],
            'body' => wp_json_encode($payload),
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => 'Claude API request failed: ' . $response->get_error_message()];
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200 || empty($body['content'][0]['text'])) {
            $error = $body['error']['message'] ?? "HTTP {$code}";
            return ['success' => false, 'message' => "Claude API error: {$error}"];
        }

        return ['success' => true, 'data' => $body['content'][0]['text']];
    }

    /**
     * Call Gemini API via wp_remote_post (fallback).
     */
    private function call_gemini(string $prompt, int $max_tokens = 8192): array {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->gemini_api_key;

        $response = wp_remote_post($url, [
            'timeout' => 120,
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode([
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature'    => 0.7,
                    'topP'           => 0.9,
                    'maxOutputTokens' => $max_tokens,
                ],
            ]),
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => 'Gemini API request failed: ' . $response->get_error_message()];
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200) {
            $error = $body['error']['message'] ?? "HTTP {$code}";
            return ['success' => false, 'message' => "Gemini API error: {$error}"];
        }

        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (empty($text)) {
            return ['success' => false, 'message' => 'Gemini returned empty response'];
        }

        return ['success' => true, 'data' => $text];
    }
}
