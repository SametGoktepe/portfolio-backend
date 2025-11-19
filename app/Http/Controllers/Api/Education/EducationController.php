<?php

namespace App\Http\Controllers\Api\Education;

use App\Http\Controllers\Controller;
use App\Http\Requests\Education\EducationRequest;
use Src\Domain\Education\Services\EducationService;
use Illuminate\Http\JsonResponse;

class EducationController extends Controller
{
    private EducationService $educationService;

    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
    }

    /**
     * Display a listing of all education records.
     */
    public function index(): JsonResponse
    {
        try {
            $educations = $this->educationService->getAllEducation();

            return response()->json([
                'success' => true,
                'message' => 'Education records retrieved successfully',
                'data' => array_map(fn($education) => $education->toArray(), $educations),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve education records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified education record.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $education = $this->educationService->getEducation($id);

            if (!$education) {
                return response()->json([
                    'success' => false,
                    'message' => 'Education record not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $education->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve education record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created education record.
     */
    public function store(EducationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $education = $this->educationService->createEducation($validated);

            return response()->json([
                'success' => true,
                'message' => 'Education record created successfully',
                'data' => $education->toArray(),
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
                'message' => 'Failed to create education record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified education record.
     */
    public function update(EducationRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $education = $this->educationService->updateEducation($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Education record updated successfully',
                'data' => $education->toArray(),
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
                'message' => 'Failed to update education record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified education record.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->educationService->deleteEducation($id);

            return response()->json([
                'success' => true,
                'message' => 'Education record deleted successfully',
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
                'message' => 'Failed to delete education record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

