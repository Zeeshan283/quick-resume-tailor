<?php

namespace App\Contracts;

interface AiServiceInterface
{
    /**
     * Send a prompt to the AI and expect a structured JSON response.
     *
     * @param string $systemPrompt
     * @param string $userPrompt
     * @return array The decoded JSON response
     */
    public function generateStructuredJson(string $systemPrompt, string $userPrompt): array;
}
