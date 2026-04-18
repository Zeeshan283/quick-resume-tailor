<?php

namespace App\Engines;

class ScoringEngine
{
    /**
     * Compute ATS match percentages based on keywords extracted from the JD 
     * vs the output resume text/skills.
     * 
     * @param array $jobKeywords
     * @param array $tailoredResume
     * @return array
     */
    public function computeAtsScore(array $jobKeywords, array $tailoredResume): array
    {
        // Flatten the resume into a single searchable string of values only
        $searchableText = $this->flattenResumeValues($tailoredResume);
        $searchableText = strtolower($searchableText);
        
        $matched = [];
        $missing = [];

        foreach ($jobKeywords as $kw) {
            $lowerKw = strtolower($kw);
            // Relaxed string matching: simply check if the keyword exists anywhere
            // This mirrors how many ATS parsers actually do simple inverted index lookups
            if (str_contains($searchableText, $lowerKw)) {
                $matched[] = $kw;
            } else {
                $missing[] = $kw;
            }
        }

        $total = count($jobKeywords);
        $score = $total > 0 ? (int)(round((count($matched) / $total) * 100)) : 100;

        return [
            'ats_score' => $score,
            'matched_keywords' => $matched,
            'missing_keywords' => $missing,
        ];
    }

    private function flattenResumeValues(array $data): string
    {
        $values = [];
        array_walk_recursive($data, function($v) use (&$values) {
            if (is_string($v)) $values[] = $v;
        });
        return implode(' ', $values);
    }
}
