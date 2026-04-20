<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Contracts\AiServiceInterface;

class GenericAiProvider implements AiServiceInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        // Get credentials from current request headers (BYOK) or config defaults
        $apiKey = request()->header('X-AI-API-KEY') ?? config('services.ai.api_key');

        // Clean the key: trim and remove 'Bearer ' if the user included it
        $this->apiKey = $apiKey ? trim(str_ireplace('Bearer ', '', $apiKey)) : '';

        // Consistently use X-AI-API-URL for the full endpoint
        $this->baseUrl = request()->header('X-AI-API-URL') ?? config('services.ai.base_url');
        $this->model = request()->header('X-AI-MODEL') ?? config('services.ai.model');
    }

    public function generateStructuredJson(string $systemPrompt, string $userPrompt): array
    {
        if (empty($this->apiKey)) {
            Log::error('AI Provider: API Key is missing.');
            throw new \Exception('API Key required. Please provide it in the settings.');
        }

        if (empty($this->baseUrl)) {
            Log::error('AI Provider: API URL is missing.');
            throw new \Exception('API URL required. Please provide the full endpoint URL in the settings.');
        }

        // Use the URL exactly as provided by the user (Full Endpoint)
        $endpoint = $this->baseUrl;

        Log::info("AI Provider: Sending request to: {$endpoint}");

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($endpoint, [
                'model' => $this->model, // No default - must be provided by user
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
                Log::error('AI Provider: Invalid JSON returned', ['content' => $content]);
                throw new \Exception('AI returned invalid JSON structure.');
            }

            return $decoded;
        }

        Log::error('AI Provider: API request failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        throw new \Exception('Failed to communicate with AI provider.');
    }
}
