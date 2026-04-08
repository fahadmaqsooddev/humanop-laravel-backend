<?php

/**
 * Admin API Routes — JWT-authenticated, JSON responses only.
 *
 * Sprint 0b: All admin SPA endpoints live here.
 * Middleware: api + auth:api + isAdmin (applied via group)
 *
 * Prefix: /api/admin
 */

use App\Http\Controllers\AdminApi\AdminAuthController;
use App\Http\Controllers\AdminApi\AdminDashboardController;
use App\Http\Controllers\AdminApi\AdminUserController;
use App\Http\Controllers\AdminApi\AdminAssessmentController;
use App\Http\Controllers\AdminApi\AdminPeopleController;
use App\Http\Controllers\AdminApi\RdLabController;
use App\Http\Controllers\AdminApi\AdminTeamController;
use App\Http\Controllers\AdminApi\HaiTrainerController;
use App\Http\Controllers\AdminApi\HaiTrainingQueueController;
use App\Http\Controllers\AdminApi\HaiKnowledgeController;
use App\Http\Controllers\AdminApi\HaiDojoController;
use App\Http\Controllers\AdminApi\HaiStatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Admin Auth Routes (no auth middleware)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AdminAuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (JWT + isAdmin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'isAdmin'])->group(function () {

    // Auth
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/me', [AdminAuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats']);

    // Users
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::get('/users/{id}/assessment', [AdminUserController::class, 'assessment']);
    Route::get('/users/{id}/activity', [AdminUserController::class, 'activity']);
    Route::put('/users/{id}/tier', [AdminUserController::class, 'changeTier']);
    Route::post('/users/{id}/reset-assessment', [AdminUserController::class, 'resetAssessment']);
    Route::put('/users/{id}/archive', [AdminUserController::class, 'archive']);

    // Assessments
    Route::get('/assessments', [AdminAssessmentController::class, 'index']);
    Route::get('/assessments/stats', [AdminAssessmentController::class, 'stats']);
    Route::get('/assessments/{id}', [AdminAssessmentController::class, 'show']);

    // ─── HAi: Sprint 1 ──────────────────────────────────────────────

    // Trainers
    Route::get('/hai/trainers', [HaiTrainerController::class, 'index']);
    Route::get('/hai/trainers/{id}', [HaiTrainerController::class, 'show']);
    Route::post('/hai/trainers', [HaiTrainerController::class, 'store']);
    Route::put('/hai/trainers/{id}', [HaiTrainerController::class, 'update']);
    Route::delete('/hai/trainers/{id}', [HaiTrainerController::class, 'destroy']);

    // Training Queue
    Route::get('/hai/training-queue', [HaiTrainingQueueController::class, 'index']);
    Route::post('/hai/training-queue', [HaiTrainingQueueController::class, 'store']);
    Route::put('/hai/training-queue/{id}/status', [HaiTrainingQueueController::class, 'updateStatus']);

    // Knowledge
    Route::get('/hai/knowledge/clusters', [HaiKnowledgeController::class, 'clusters']);
    Route::post('/hai/knowledge/upload', [HaiKnowledgeController::class, 'upload']);
    Route::post('/hai/knowledge/clusters/{clusterId}/train', [HaiKnowledgeController::class, 'train']);
    Route::get('/hai/knowledge/units', [HaiKnowledgeController::class, 'units']);

    // Dojo
    Route::get('/hai/dojo/sessions', [HaiDojoController::class, 'sessions']);
    Route::post('/hai/dojo/sessions', [HaiDojoController::class, 'startSession']);
    Route::put('/hai/dojo/sessions/{id}/end', [HaiDojoController::class, 'endSession']);
    Route::post('/hai/dojo/query', [HaiDojoController::class, 'query']);
    Route::post('/hai/dojo/evaluate', [HaiDojoController::class, 'evaluate']);

    // HAi Stats
    Route::get('/hai/stats', [HaiStatsController::class, 'index']);

    // ─── People (merged users + assessments) ────────────────────
    Route::get('/people', [AdminPeopleController::class, 'index']);
    Route::get('/people/{id}', [AdminPeopleController::class, 'show']);
    Route::get('/people/{id}/grid', [AdminPeopleController::class, 'grid']);
    Route::get('/people/{id}/activity', [AdminPeopleController::class, 'activity']);
    Route::get('/people/{id}/hai-history', [AdminPeopleController::class, 'haiHistory']);
    Route::get('/people/{id}/notes', [AdminPeopleController::class, 'notes']);
    Route::post('/people/{id}/notes', [AdminPeopleController::class, 'addNote']);

    // ─── R&D Lab ────────────────────────────────────────────────
    Route::post('/hai/rd-lab/search', [RdLabController::class, 'search']);
    Route::get('/hai/rd-lab/stats', [RdLabController::class, 'stats']);

    // ─── Admin Team ─────────────────────────────────────────────
    Route::get('/team', [AdminTeamController::class, 'index']);
    Route::post('/team/invite', [AdminTeamController::class, 'invite']);
    Route::get('/team/{id}', [AdminTeamController::class, 'show']);
    Route::put('/team/{id}', [AdminTeamController::class, 'update']);
    Route::put('/team/{id}/deactivate', [AdminTeamController::class, 'deactivate']);
});
