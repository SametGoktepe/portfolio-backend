<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\About\AboutController;
use App\Http\Controllers\Api\Skills\SkillController;
use App\Http\Controllers\Api\Education\EducationController;
use App\Http\Controllers\Api\Project\ProjectController;
use App\Http\Controllers\Api\Hobby\HobbyController;
use App\Http\Controllers\Api\Reference\ReferenceController;
use App\Http\Controllers\Api\WorkLife\WorkLifeController;

Route::prefix('v1')->group(function () {
    // Auth - Public routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Auth - Protected routes
    Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // About - Public GET
    Route::prefix('about')->group(function () {
        Route::get('/', [AboutController::class, 'show']);
    });

    // About - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('about')->group(function () {
        Route::post('/', [AboutController::class, 'store']);
        Route::put('/{id}/update', [AboutController::class, 'update']);
        Route::delete('/{id}/delete', [AboutController::class, 'destroy']);
    });

    // Skills - Public GET
    Route::prefix('skills')->group(function () {
        Route::get('/', [SkillController::class, 'index']);
    });

    // Skills - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('skills')->group(function () {
        Route::post('/store', [SkillController::class, 'store']);
        Route::put('/update', [SkillController::class, 'update']);
        Route::delete('/{id}/delete', [SkillController::class, 'destroy']);
    });

    // Education - Public GET
    Route::prefix('education')->group(function () {
        Route::get('/', [EducationController::class, 'index']);
        Route::get('/{id}/show', [EducationController::class, 'show']);
    });

    // Education - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('education')->group(function () {
        Route::post('/store', [EducationController::class, 'store']);
        Route::put('/{id}/update', [EducationController::class, 'update']);
        Route::delete('/{id}/delete', [EducationController::class, 'destroy']);
    });

    // Projects - Public GET
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']); // With pagination

    });

    // Projects - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('projects')->group(function () {
        Route::post('/store', [ProjectController::class, 'store']);
        Route::get('/{id}/show', [ProjectController::class, 'show']);
        Route::put('/{id}/update', [ProjectController::class, 'update']);
        Route::patch('/{id}/status', [ProjectController::class, 'changeStatus']);
        Route::delete('/{id}/delete', [ProjectController::class, 'destroy']);
    });

    // Hobbies - Public GET
    Route::prefix('hobbies')->group(function () {
        Route::get('/', [HobbyController::class, 'index']);
    });

    // Hobbies - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('hobbies')->group(function () {
        Route::get('/{id}/show', [HobbyController::class, 'show']);
        Route::post('/store', [HobbyController::class, 'store']);
        Route::put('/{id}/update', [HobbyController::class, 'update']);
        Route::delete('/{id}/delete', [HobbyController::class, 'destroy']);
    });

    // References - Public GET
    Route::prefix('references')->group(function () {
        Route::get('/', [ReferenceController::class, 'index']);
    });

    // References - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('references')->group(function () {
        Route::get('/{id}/show', [ReferenceController::class, 'show']);
        Route::post('/store', [ReferenceController::class, 'store']);
        Route::put('/{id}/update', [ReferenceController::class, 'update']);
        Route::delete('/{id}/delete', [ReferenceController::class, 'destroy']);
    });

    // Work Life - Public GET
    Route::prefix('work-life')->group(function () {
        Route::get('/', [WorkLifeController::class, 'index']);
    });

    // Work Life - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('work-life')->group(function () {
        Route::get('/{id}/show', [WorkLifeController::class, 'show']);
        Route::post('/store', [WorkLifeController::class, 'store']);
        Route::put('/{id}/update', [WorkLifeController::class, 'update']);
        Route::delete('/{id}/delete', [WorkLifeController::class, 'destroy']);
    });
});
