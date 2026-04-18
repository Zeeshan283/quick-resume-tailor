<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Contracts\AiServiceInterface;

class GroqService implements AiServiceInterface
{
    protected string $apiKey;
    // Switched to xAI (Grok) endpoint to match user's API key
    protected string $baseUrl = 'https://api.x.ai/v1';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key', '');
    }

    public function generateStructuredJson(string $systemPrompt, string $userPrompt): array
    {
        if (empty($this->apiKey)) {
            Log::error('AI API Key is not set.');
            throw new \Exception('AI Provider configuration is missing.');
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post("{$this->baseUrl}/chat/completions", [
                'model' => 'grok-4-1-fast-non-reasoning', // Using the fast model for better performance
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->successful()) {
            $content = $response->json('choices.0.message.content');
            $decoded = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON returned by AI', ['content' => $content]);
                throw new \Exception('AI returned invalid JSON structure.');
            }

            return $decoded;
        }

        Log::error('AI API request failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        throw new \Exception('Failed to communicate with AI provider.');
    }
}
