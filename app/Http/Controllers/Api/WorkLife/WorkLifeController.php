<?php

namespace App\Http\Controllers\Api\WorkLife;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkLife\WorkLifeRequest;
use Src\Domain\WorkLife\Services\WorkLifeService;
use Illuminate\Http\JsonResponse;

class WorkLifeController extends Controller
{
    private WorkLifeService $workLifeService;

    public function __construct(WorkLifeService $workLifeService)
    {
        $this->workLifeService = $workLifeService;
    }

    /**
     * Display a listing of work experiences.
     */
    public function index(): JsonResponse
    {
        try {
            $workLives = $this->workLifeService->getAllWorkLife();

            return response()->json([
                'success' => true,
                'message' => 'Work experiences retrieved successfully',
                'data' => array_map(fn($workLife) => $workLife->toArray(), $workLives),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve work experiences',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified work experience.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $workLife = $this->workLifeService->getWorkLife($id);

            if (!$workLife) {
                return response()->json([
                    'success' => false,
                    'message' => 'Work experience not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $workLife->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve work experience',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created work experience.
     */
    public function store(WorkLifeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $workLife = $this->workLifeService->createWorkLife($validated);

            return response()->json([
                'success' => true,
                'message' => 'Work experience created successfully',
                'data' => $workLife->toArray(),
            ], 201);
        } catch (\Src\Domain\Shared\Exceptions\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Domain validation error',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create work experience',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified work experience.
     */
    public function update(WorkLifeRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $workLife = $this->workLifeService->updateWorkLife($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Work experience updated successfully',
                'data' => $workLife->toArray(),
            ]);
        } catch (\Src\Domain\Shared\Exceptions\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Domain validation error',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update work experience',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified work experience.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->workLifeService->deleteWorkLife($id);

            return response()->json([
                'success' => true,
                'message' => 'Work experience deleted successfully',
            ]);
        } catch (\Src\Domain\Shared\Exceptions\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Domain validation error',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete work experience',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

