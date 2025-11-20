<?php

namespace App\Http\Controllers\Api\Reference;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reference\ReferenceRequest;
use Src\Domain\Reference\Services\ReferenceService;
use Illuminate\Http\JsonResponse;

class ReferenceController extends Controller
{
    private ReferenceService $referenceService;

    public function __construct(ReferenceService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    /**
     * Display a listing of references.
     */
    public function index(): JsonResponse
    {
        try {
            $references = $this->referenceService->getAllReferences();

            return response()->json([
                'success' => true,
                'message' => 'References retrieved successfully',
                'data' => array_map(fn($reference) => $reference->toArray(), $references),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve references',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified reference.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $reference = $this->referenceService->getReference($id);

            if (!$reference) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reference not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $reference->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reference',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created reference.
     */
    public function store(ReferenceRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $reference = $this->referenceService->createReference($validated);

            return response()->json([
                'success' => true,
                'message' => 'Reference created successfully',
                'data' => $reference->toArray(),
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
                'message' => 'Failed to create reference',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified reference.
     */
    public function update(ReferenceRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $reference = $this->referenceService->updateReference($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Reference updated successfully',
                'data' => $reference->toArray(),
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
                'message' => 'Failed to update reference',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified reference.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->referenceService->deleteReference($id);

            return response()->json([
                'success' => true,
                'message' => 'Reference deleted successfully',
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
                'message' => 'Failed to delete reference',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

