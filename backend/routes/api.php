<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuestionnaireController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Api\DashboardController;

// Public routes
Route::prefix('api')->group(function () {
    // Survey endpoints (public)
    Route::get('/surveys', [QuestionnaireController::class, 'index']);
    Route::get('/surveys/{questionnaire}', [QuestionnaireController::class, 'show']);
    
    // Response submission (public)
    Route::post('/responses', [ResponseController::class, 'store']);
    
    // Authentication (placeholder)
    Route::post('/auth/login', function () {
        return response()->json(['token' => 'sample_token']);
    });
    
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Admin questionnaire management
        Route::post('/surveys', [QuestionnaireController::class, 'store']);
        Route::put('/surveys/{questionnaire}', [QuestionnaireController::class, 'update']);
        Route::delete('/surveys/{questionnaire}', [QuestionnaireController::class, 'destroy']);
        
        // Question management
        Route::post('/surveys/{questionnaire}/questions', [QuestionController::class, 'store']);
        Route::put('/questions/{question}', [QuestionController::class, 'update']);
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);
        
        // Response management
        Route::get('/responses', [ResponseController::class, 'index']);
        
        // Dashboard
        Route::get('/admin/dashboard', [DashboardController::class, 'stats']);
    });
});
