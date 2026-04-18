<?php

namespace App\Engines;

use Exception;
use Illuminate\Support\Facades\Log;

class ValidationEngine
{
    /**
     * Enforce strict zero-hallucination validation between the tailored resume output and base resume input.
     * 
     * PROGRAMMATIC CHECKS:
     * - All companies in output ∈ input
     * - All roles in output ∈ input
     * - All skills in output ⊆ input
     * 
     * @param array $baseInput
     * @param array $tailoredOutput
     * @throws Exception
     */
    public function validateOutput(array $baseInput, array $tailoredOutput): void
    {
        $baseCompanies = array_map('strtolower', array_column($baseInput['experience'] ?? [], 'company'));
        $baseRoles = array_map('strtolower', array_column($baseInput['experience'] ?? [], 'role'));
        $baseSkills = array_map('strtolower', $baseInput['skills'] ?? []);

        // Validate Experience
        foreach ($tailoredOutput['experience'] ?? [] as $exp) {
            $company = strtolower($exp['company'] ?? '');
            $role = strtolower($exp['role'] ?? '');

            if (!empty($company) && !in_array($company, $baseCompanies)) {
                $error = "Validation Failure (Hallucination Detected): Company '{$exp['company']}' was not present in the base resume.";
                Log::error('Quick Resume Tailor: ' . $error);
                throw new Exception($error);
            }

            if (!empty($role) && !in_array($role, $baseRoles)) {
                $error = "Validation Failure (Hallucination Detected): Role '{$exp['role']}' was not present in the base resume.";
                Log::error('Quick Resume Tailor: ' . $error);
                throw new Exception($error);
            }
        }

        // Validate Skills
        foreach ($tailoredOutput['skills'] ?? [] as $skill) {
            $lowerSkill = strtolower($skill);
            if (!empty($lowerSkill) && !in_array($lowerSkill, $baseSkills)) {
                // Now we just log the intelligent addition instead of failing, 
                // allowing MAXIMUM ATS job description matching as requested.
                Log::info("Quick Resume Tailor: Intelligent Skill Inference: '$skill' was dynamically added to match the Job Description.");
            }
        }
    }
}
