<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResumeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Temporarily removed auth:sanctum for MVP development/testing
Route::get('/resume/status', [ResumeController::class, 'checkStatus']);
Route::post('/resume/upload', [ResumeController::class, 'uploadResumePdf']);
Route::post('/resume/tailor', [ResumeController::class, 'tailor']);
Route::get('/resume/download/{id}', [ResumeController::class, 'downloadPdf']);
