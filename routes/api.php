<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\About\AboutController;
use App\Http\Controllers\Api\Skills\SkillController;
use App\Http\Controllers\Api\Education\EducationController;
use App\Http\Controllers\Api\Project\ProjectController;

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
        Route::get('/{id}/show', [ProjectController::class, 'show']);
    });

    // Projects - Protected WRITE
    Route::middleware('auth:sanctum')->prefix('projects')->group(function () {
        Route::post('/store', [ProjectController::class, 'store']);
        Route::put('/{id}/update', [ProjectController::class, 'update']);
        Route::patch('/{id}/status', [ProjectController::class, 'changeStatus']);
        Route::delete('/{id}/delete', [ProjectController::class, 'destroy']);
    });
});
