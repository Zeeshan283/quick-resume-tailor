<?php

namespace App\Http\Controllers\Api;

use App\Models\Resume;
use App\Models\GeneratedResume;
use App\Engines\ResumeParserEngine;
use App\Engines\TailoringEngine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    /**
     * Store the user's base resume by parsing a PDF document into JSON.
     */
    public function uploadResumePdf(Request $request, ResumeParserEngine $parser): JsonResponse
    {
        Log::info('Quick Resume Tailor: Upload request received.');
        try {
            $request->validate([
                'resume_pdf' => 'required|file|mimes:pdf|max:10240', // 10MB max
            ]);

            $path = $request->file('resume_pdf')->store('resumes');
            $fullPath = Storage::path($path);

            Log::info('Quick Resume Tailor: File stored.', ['path' => $fullPath]);

            // Run the PDF -> Text -> JSON pipeline
            $structuredJson = $parser->parsePdfToStructuredJson($fullPath);

            // Fallback to first user if not authenticated (for MVP/testing purposes)
            $user = $request->user() ?? User::first();
            
            if (!$user) {
                return response()->json(['error' => 'No user found in database. Run a seeder or create a user.'], 400);
            }

            $resume = Resume::updateOrCreate(
                ['user_id' => $user->getKey()],
                ['content' => $structuredJson]
            );

            Log::info('Quick Resume Tailor: Resume uploaded and parsed successfully.', ['resume_id' => $resume->id]);

            return response()->json([
                'message' => 'Base resume processed and structured successfully.',
                'data' => $resume
            ]);
        } catch (Exception $e) {
            Log::error('AutoJob: Resume upload failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tailor the user's base resume.
     */
    public function tailor(Request $request, TailoringEngine $tailorEngine): JsonResponse
    {
        try {
            Log::info('Quick Resume Tailor: Tailor request received.', $request->only(['job_title', 'company']));

            $request->validate([
                'job_title' => 'nullable|string',
                'company' => 'nullable|string',
                'job_description' => 'required|string',
            ]);

            $user = $request->user() ?? User::first();
            $baseResume = $user ? $user->resumes()->latest()->first() : null;

            if (!$baseResume) {
                Log::warning('Quick Resume Tailor: Tailor failed - No base resume found.');
                return response()->json(['error' => 'No structured base resume found. Please upload your resume PDF first.'], 404);
            }

            // Run the 5-step strict zero-hallucination Tailoring pipeline
            $result = $tailorEngine->tailor($baseResume->content, $request->job_description);

            // Store the generation result
            $generatedResume = GeneratedResume::create([
                'resume_id' => $baseResume->id,
                'job_description' => $request->job_description,
                'output' => $result['tailored_resume'],
                'ats_score' => $result['ats_score'],
            ]);

            Log::info('Quick Resume Tailor: Tailor successful.', ['generated_id' => $generatedResume->id]);

            return response()->json([
                'generated_id' => $generatedResume->id,
                'tailored_resume' => $result['tailored_resume'],
                'ats_score' => $result['ats_score'],
                'matched_keywords' => $result['matched_keywords'] ?? [],
                'missing_keywords' => $result['missing_keywords'] ?? []
            ]);
        } catch (Exception $e) {
            Log::error('Quick Resume Tailor: Tailoring failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Tailoring failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check if the user already has a base resume uploaded.
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $user = $request->user() ?? User::first();
        if (!$user) {
            return response()->json(['has_resume' => false]);
        }

        $baseResume = $user->resumes()->latest()->first();
        return response()->json([
            'has_resume' => !is_null($baseResume),
            'resume_data' => $baseResume ? $baseResume->content : null
        ]);
    }

    /**
     * Download the Tailored Resume PDF with a specific template
     */
    public function downloadPdf(Request $request, string $id)
    {
        $generatedResume = GeneratedResume::findOrFail($id);
        
        $template = $request->query('template', 'classic');
        $allowedTemplates = ['classic', 'modern', 'compact', 'creative'];
        
        if (!in_array($template, $allowedTemplates)) {
            $template = 'classic';
        }

        $pdf = Pdf::loadView("resume.{$template}", [
            'resume' => $generatedResume->output
        ]);

        return $pdf->download("Tailored_Resume_{$template}.pdf");
    }
}
