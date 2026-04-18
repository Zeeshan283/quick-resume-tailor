<?php

namespace App\Engines;

use App\Contracts\AiServiceInterface;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;

class ResumeParserEngine
{
    private AiServiceInterface $aiService;

    public function __construct(AiServiceInterface $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Parses a PDF file into rigid structured JSON via AI.
     */
    public function parsePdfToStructuredJson(string $pdfFilePath): array
    {
        Log::info('Quick Resume Tailor: Commencing PDF parsing.', ['path' => $pdfFilePath]);
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfFilePath);
        $text = $pdf->getText();

        // Sanitize UTF-8 to prevent JSON encoding errors
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        Log::info('Quick Resume Tailor: PDF text extracted. Sending to AI for structuring.');
        return $this->parseTextToStructuredJson($text);
    }

    /**
     * Converts raw text into structured Base Resume JSON format.
     */
    public function parseTextToStructuredJson(string $rawText): array
    {
        $systemPrompt = <<<EOT
You are an expert resume data extractor. 
Extract the user's resume data from the provided text and output it strictly in the following JSON format. Do not fabricate or invent data.
If a section is missing, return an empty array for it.

Required JSON Schema:
{
  "personal_details": {
    "name": "string",
    "email": "string",
    "phone": "string OR null",
    "linkedin": "string OR null",
    "github": "string OR null"
  },
  "education": [
    {
      "institution": "string",
      "degree": "string",
      "start_date": "string OR null",
      "end_date": "string OR null"
    }
  ],
  "summary": "string",
  "experience": [
    {
      "company": "string",
      "role": "string",
      "start_date": "string OR null",
      "end_date": "string OR null",
      "bullets": ["string"]
    }
  ],
  "skills": ["string"]
}
EOT;

        $userPrompt = "Here is the raw resume text:\n" . $rawText;

        return $this->aiService->generateStructuredJson($systemPrompt, $userPrompt);
    }
}
