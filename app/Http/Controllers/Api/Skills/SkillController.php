<?php

namespace App\Http\Controllers\Api\Skills;

use App\Http\Controllers\Controller;
use App\Http\Requests\Skill\CreateSkillRequest;
use App\Http\Requests\Skill\UpdateSkillsByCategoryRequest;
use Src\Domain\Skill\Services\SkillService;
use Src\Domain\Category\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    private SkillService $skillService;
    private CategoryService $categoryService;

    public function __construct(
        SkillService $skillService,
        CategoryService $categoryService
    ) {
        $this->skillService = $skillService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of all skills grouped by category.
     */
    public function index(): JsonResponse
    {
        try {
            $skills = $this->skillService->getAllSkills();

            // Group skills by category
            $groupedSkills = [];
            $categoryCache = [];

            foreach ($skills as $skill) {
                $categoryId = $skill->categoryId()->value();

                // Fetch category info if not cached
                if (!isset($categoryCache[$categoryId])) {
                    $category = $this->categoryService->getCategory($categoryId);
                    if ($category) {
                        $categoryCache[$categoryId] = [
                            'id' => $category->id()->value(),
                            'name' => $category->name()->value(),
                            'slug' => $category->slug()->value(),
                        ];
                    }
                }

                if (!isset($groupedSkills[$categoryId])) {
                    $groupedSkills[$categoryId] = [
                        'category' => $categoryCache[$categoryId] ?? [
                            'id' => $categoryId,
                            'name' => 'Unknown',
                            'slug' => 'unknown',
                        ],
                        'skills' => [],
                    ];
                }

                $groupedSkills[$categoryId]['skills'][] = $skill->toArray();
            }

            return response()->json([
                'success' => true,
                'message' => 'Skills retrieved successfully',
                'data' => array_values($groupedSkills),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve skills',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * Accepts: { "category_name": "Frontend", "skills": ["React", "TypeScript"] }
     */
    public function store(CreateSkillRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $result = $this->skillService->createSkillsWithCategory(
                $validated['category_name'],
                $validated['skills']
            );

            $category = $result['category'];
            $skills = $result['skills'];

            return response()->json([
                'success' => true,
                'message' => 'Skills created successfully',
                'data' => [
                    'category' => [
                        'id' => $category->id()->value(),
                        'name' => $category->name()->value(),
                        'slug' => $category->slug()->value(),
                    ],
                    'skills' => array_map(fn($skill) => $skill->toArray(), $skills),
                    'created_count' => count($skills),
                ],
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
                'message' => 'Failed to create skills',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified skill.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $skill = $this->skillService->getSkill($id);

            if (!$skill) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill not found',
                ], 404);
            }

            // Get category info
            $category = $this->categoryService->getCategory($skill->categoryId()->value());

            return response()->json([
                'success' => true,
                'data' => [
                    'skill' => $skill->toArray(),
                    'category' => $category ? [
                        'id' => $category->id()->value(),
                        'name' => $category->name()->value(),
                        'slug' => $category->slug()->value(),
                    ] : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve skill',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update skills by category (sync operation).
     *
     * Accepts: { "category_name": "Frontend", "skills": ["React", "TypeScript"] }
     *
     * This will:
     * - Create category if it doesn't exist
     * - Add new skills that are in the list
     * - Keep existing skills that are in the list
     * - Remove skills that are NOT in the list
     */
    public function update(UpdateSkillsByCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $result = $this->skillService->syncSkillsByCategory(
                $validated['category_name'],
                $validated['skills']
            );

            $category = $result['category'];
            $skills = $result['skills'];

            return response()->json([
                'success' => true,
                'message' => 'Skills synchronized successfully',
                'data' => [
                    'category' => [
                        'id' => $category->id()->value(),
                        'name' => $category->name()->value(),
                        'slug' => $category->slug()->value(),
                    ],
                    'skills' => array_map(fn($skill) => $skill->toArray(), $skills),
                    'statistics' => [
                        'added' => $result['added_count'],
                        'deleted' => $result['deleted_count'],
                        'total' => $result['total_count'],
                    ],
                ],
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
                'message' => 'Failed to sync skills',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified skill.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->skillService->deleteSkill($id);

            return response()->json([
                'success' => true,
                'message' => 'Skill deleted successfully',
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
                'message' => 'Failed to delete skill',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
