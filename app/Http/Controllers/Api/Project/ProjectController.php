<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use Src\Domain\Project\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a paginated listing of projects.
     *
     * Query params: ?per_page=10&status=completed
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('perPage', 15);
            $status = $request->query('status');

            $result = $this->projectService->getProjectsPaginated((int) $perPage, $status);

            // Map domain models to arrays
            $projects = array_map(
                fn($project) => $project->toArray(),
                $result['data']
            );

            return response()->json([
                'success' => true,
                'message' => 'Projects retrieved successfully',
                'data' => $projects,
                'pagination' => [
                    'current_page' => $result['current_page'],
                    'per_page' => $result['per_page'],
                    'total' => $result['total'],
                    'last_page' => $result['last_page'],
                    'from' => $result['from'],
                    'to' => $result['to'],
                    'has_more_pages' => $result['has_more_pages'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified project.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $project = $this->projectService->getProject($id);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $project->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created project.
     */
    public function store(ProjectRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $project = $this->projectService->createProject($validated);

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'data' => $project->toArray(),
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
                'message' => 'Failed to create project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified project.
     */
    public function update(ProjectRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();

            $project = $this->projectService->updateProject($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => $project->toArray(),
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
                'message' => 'Failed to update project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change project status.
     */
    public function changeStatus(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:in_progress,completed,backlog,cancelled',
            ]);

            $project = $this->projectService->changeProjectStatus($id, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Project status updated successfully',
                'data' => $project->toArray(),
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
                'message' => 'Failed to update project status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified project.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->projectService->deleteProject($id);

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully',
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
                'message' => 'Failed to delete project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

