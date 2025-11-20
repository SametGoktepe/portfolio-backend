<?php

namespace App\Http\Controllers\Api\Hobby;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hobby\HobbyRequest;
use Src\Domain\Hobby\Services\HobbyService;
use Illuminate\Http\JsonResponse;

class HobbyController extends Controller
{
    private HobbyService $hobbyService;

    public function __construct(HobbyService $hobbyService)
    {
        $this->hobbyService = $hobbyService;
    }

    /**
     * Display a listing of hobbies.
     */
    public function index(): JsonResponse
    {
        try {
            $hobbies = $this->hobbyService->getAllHobbies();

            return response()->json([
                'success' => true,
                'message' => 'Hobbies retrieved successfully',
                'data' => array_map(fn($hobby) => $hobby->toArray(), $hobbies),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hobbies',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified hobby.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $hobby = $this->hobbyService->getHobby($id);

            if (!$hobby) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hobby not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $hobby->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve hobby',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created hobby.
     */
    public function store(HobbyRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $hobby = $this->hobbyService->createHobby($validated);

            return response()->json([
                'success' => true,
                'message' => 'Hobby created successfully',
                'data' => $hobby->toArray(),
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
                'message' => 'Failed to create hobby',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified hobby.
     */
    public function update(HobbyRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            $hobby = $this->hobbyService->updateHobby($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Hobby updated successfully',
                'data' => $hobby->toArray(),
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
                'message' => 'Failed to update hobby',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified hobby.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->hobbyService->deleteHobby($id);

            return response()->json([
                'success' => true,
                'message' => 'Hobby deleted successfully',
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
                'message' => 'Failed to delete hobby',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

