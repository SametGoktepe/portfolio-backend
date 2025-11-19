<?php

namespace App\Http\Controllers\Api\About;

use App\Http\Controllers\Controller;
use App\Http\Requests\About\AboutRequest;
use Illuminate\Http\Request;
use Src\Domain\About\Services\AboutService;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    private AboutService $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }

    public function show(): JsonResponse
    {
        $about = $this->aboutService->getAbout();

        if (!$about) {
            return response()->json(['message' => 'About not found'], 404);
        }

        return response()->json($about->toArray());
    }

    public function store(AboutRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $about = $this->aboutService->createAbout($validated);
            return response()->json($about->toArray(), 201);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(AboutRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        try {
            $about = $this->aboutService->updateAbout($id, $validated);
            return response()->json($about->toArray());
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->aboutService->deleteAbout($id);
            return response()->json(['message' => 'About deleted successfully']);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
