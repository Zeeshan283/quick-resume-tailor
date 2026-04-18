<?php

namespace App\Engines;

use App\Contracts\AiServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class TailoringEngine
{
    private AiServiceInterface $aiService;
    private ValidationEngine $validationEngine;
    private ScoringEngine $scoringEngine;

    public function __construct(
        AiServiceInterface $aiService,
        ValidationEngine $validationEngine,
        ScoringEngine $scoringEngine
    ) {
        $this->aiService = $aiService;
        $this->validationEngine = $validationEngine;
        $this->scoringEngine = $scoringEngine;
    }

    /**
     * Executes the strict tailoring pipeline.
     */
    public function tailor(array $baseResumeJson, string $jobDescription): array
    {
        Log::info('Quick Resume Tailor: Starting tailoring process.');

        // Sanitize UTF-8 input
        $jobDescription = mb_convert_encoding($jobDescription, 'UTF-8', 'UTF-8');

        // Step 1: Extract Keywords (Unrestricted Job Description targets for max ATS alignment)
        $keywords = $this->extractKeywords($jobDescription);
        Log::info('Quick Resume Tailor: Extracted keywords.', ['keywords' => $keywords]);

        // Step 2 & 3: Semantic Match & Controlled Rewrite
        $tailoredResume = $this->performControlledRewrite($baseResumeJson, $jobDescription, $keywords);

        // Step 4: Validation (CRITICAL)
        try {
            $this->validationEngine->validateOutput($baseResumeJson, $tailoredResume);
            Log::info('Quick Resume Tailor: Validation passed successfully.');
        } catch (Exception $e) {
            Log::warning('Quick Resume Tailor: Validation failed (hallucination detected). Falling back to base resume.', [
                'error' => $e->getMessage()
            ]);
            // Failure Handling: If validation fails (hallucinations detected), 
            // Fallback to a minimal rewrite (returning the base resume with no changes to avoid lying)
            $tailoredResume = $baseResumeJson; 
        }

        // Step 5: ATS Scoring
        $scoringDetails = $this->scoringEngine->computeAtsScore($keywords, $tailoredResume);

        Log::info('Quick Resume Tailor: Tailoring complete.', [
            'ats_score' => $scoringDetails['ats_score']
        ]);

        return array_merge([
            'tailored_resume' => $tailoredResume
        ], $scoringDetails);
    }

    private function extractKeywords(string $jobDescription): array
    {
        $systemPrompt = <<<EOT
You are an expert ATS (Applicant Tracking System) keyword extractor.
Given a 'Job Description', extract the top 10 to 15 most critical atomic technical keywords and core competencies.

CRITICAL ATS SCORING RULE:
Extract the absolute most important deal-breaker keywords required for this job so the tailoring engine can optimize the user's resume around them.
Each keyword should be 1-3 words max.

Format: {"keywords": ["skill1", "skill2"]}
EOT;

        $response = $this->aiService->generateStructuredJson($systemPrompt, $jobDescription);
        return $response['keywords'] ?? [];
    }

    private function performControlledRewrite(array $baseResumeJson, string $jobDescription, array $keywords): array
    {
        $systemPrompt = <<<EOT
You are an expert career consultant operating to MAXIMIZE ATS SCORE AND JOB MATCH ALIGNMENT.
You will receive a User's 'Base Resume JSON', a 'Job Description', and a list of 'Target Keywords'.

YOUR TASK:
Rewrite the 'bullets' inside the 'experience' array, update the 'summary', and update the 'skills' list to aggressively match the Target Keywords and the Job Description language as much as humanly possible.

STRICT CONSTRAINTS:
1. DO NOT introduce new 'company' names or 'role' titles not present in the Base Resume.
2. YOU MUST ADD all relevant 'Target Keywords' to the user's 'skills' array to guarantee the platform matches ATS requirements.
3. Rewrite the experience bullets heavily. If the Job Description requires a certain skill, describe the user's past work using that exact keyword and phrasing. Ensure the resume looks custom-built for this exact job description.
4. Keep the exact same JSON schema structure as the Base Resume.
5. DO NOT alter the 'personal_details' or 'education' fields.

Respond ONLY with the tailored Resume JSON matching the exact schema.
EOT;

        $userPrompt = "--- Base Resume JSON ---\n" . json_encode($baseResumeJson) . "\n\n"
            . "--- Job Description ---\n" . $jobDescription . "\n\n"
            . "--- Target Keywords ---\n" . json_encode($keywords);

        return $this->aiService->generateStructuredJson($systemPrompt, $userPrompt);
    }
}
