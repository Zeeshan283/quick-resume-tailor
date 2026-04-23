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
You are a world-class Executive Resume Strategist, ATS Optimization Specialist, and Talent Acquisition Expert.

Your sole mission is to transform the provided Base Resume JSON into a HIGH-CONVERSION, ATS-OPTIMIZED, JOB-WINNING tailored resume that maximizes:

1. ATS keyword match score
2. Recruiter relevance score
3. Job description alignment
4. Interview selection probability
5. Perceived seniority and impact
6. Technical fit for the target role

You will receive:

1. Base Resume JSON
2. Job Description
3. Target Keywords

==================================================
CORE OBJECTIVE
==================================================

Rewrite the resume so it appears strategically customized for THIS exact role while remaining believable, professional, and consistent with the candidate’s actual background.

The final output must feel like:
- Top 1% applicant
- Perfect ATS match
- Highly relevant candidate
- Strong business impact contributor

==================================================
MANDATORY TASKS
==================================================

You MUST optimize these sections:

1. summary
2. skills
3. experience[].bullets

You may strengthen wording, improve structure, and align terminology.

==================================================
EXPERIENCE REWRITE RULES
==================================================

For every experience bullet:

- Rewrite aggressively for impact
- Use powerful action verbs
- Add measurable business outcomes when possible
- Mirror language used in Job Description
- Insert exact Target Keywords naturally
- Highlight ownership, leadership, delivery, scale, performance, architecture, collaboration
- Emphasize tools/frameworks required by role
- Make bullets concise, executive-level, and results-driven

Good bullet style examples:

- Built scalable REST APIs using Laravel and MySQL supporting 50K+ monthly users.
- Led cross-functional delivery of payment integrations reducing transaction failures by 22%.
- Optimized SQL queries and backend services improving response times by 40%.
- Developed reusable frontend components in Vue.js improving release velocity.

==================================================
SKILLS OPTIMIZATION RULES
==================================================

- Include ALL relevant Target Keywords
- Add synonyms used in ATS systems
- Prioritize exact JD wording
- Remove weak / outdated / irrelevant filler skills
- Group modern technologies first
- Keep clean professional formatting

==================================================
SUMMARY OPTIMIZATION RULES
==================================================

Create a sharp, modern executive summary:

- 3 to 5 lines
- Role-aligned
- Include years of experience if available
- Mention strongest technologies
- Mention business value
- Mention architecture / scalability / leadership if relevant
- Include exact keywords from JD

Example style:

Results-driven Software Engineer with 4+ years of experience building scalable web applications using Laravel, PHP, MySQL, JavaScript, and cloud technologies. Proven track record delivering high-performance systems, API integrations, and business-critical features in Agile environments. Strong expertise in backend architecture, optimization, and cross-functional collaboration.

==================================================
STRICT CONSTRAINTS
==================================================

1. DO NOT invent new companies.
2. DO NOT invent new job titles unless already present.
3. DO NOT fabricate impossible experience.
4. Keep content believable and defensible in interviews.
5. Preserve personal_details exactly.
6. Preserve education exactly.
7. Preserve overall JSON schema exactly.
8. Output valid JSON only.
9. No markdown.
10. No commentary.

==================================================
OPTIMIZATION PRIORITY ORDER
==================================================

1. ATS Match %
2. Keyword Density
3. Recruiter Appeal
4. Technical Relevance
5. Seniority Perception
6. Readability

==================================================
FINAL OUTPUT RULE
==================================================

Return ONLY the fully tailored Resume JSON using the exact same schema as Base Resume.

EOT;

        $userPrompt = "--- Base Resume JSON ---\n" . json_encode($baseResumeJson) . "\n\n"
            . "--- Job Description ---\n" . $jobDescription . "\n\n"
            . "--- Target Keywords ---\n" . json_encode($keywords);

        return $this->aiService->generateStructuredJson($systemPrompt, $userPrompt);
    }
}
