<?php

namespace Src\Domain\Skill\Services;

use Src\Domain\Skill\Models\Skill;
use Src\Domain\Skill\Models\SkillId;
use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Skill\ValueObjects\SkillName;
use Src\Domain\Skill\Repositories\SkillRepositoryInterface;
use Src\Domain\Category\Services\CategoryService;
use Src\Domain\Shared\Exceptions\DomainException;

final class SkillService
{
    private SkillRepositoryInterface $repository;
    private CategoryService $categoryService;

    public function __construct(
        SkillRepositoryInterface $repository,
        CategoryService $categoryService
    ) {
        $this->repository = $repository;
        $this->categoryService = $categoryService;
    }

    public function createSkill(string $categoryId, string $name): Skill
    {
        $skillName = new SkillName($name);
        $categoryIdVO = new CategoryId($categoryId);

        // Check if skill name already exists in this category
        if ($this->repository->nameExistsInCategory($name, $categoryIdVO)) {
            throw new DomainException("Skill '{$name}' already exists in this category");
        }

        $skill = Skill::create(
            SkillId::generate(),
            $categoryIdVO,
            $skillName
        );

        $this->repository->save($skill);

        return $skill;
    }

    public function createSkillsWithCategory(string $categoryName, array $skillNames): array
    {
        // Find or create category
        $category = $this->categoryService->findOrCreateCategory($categoryName);

        $skills = [];

        foreach ($skillNames as $skillName) {
            // Skip if skill already exists in category
            if ($this->repository->nameExistsInCategory($skillName, $category->id())) {
                continue;
            }

            $skill = Skill::create(
                SkillId::generate(),
                $category->id(),
                new SkillName($skillName)
            );

            $skills[] = $skill;
        }

        // Batch save for better performance
        if (!empty($skills)) {
            $this->repository->saveBatch($skills);
        }

        return [
            'category' => $category,
            'skills' => $skills,
        ];
    }

    public function updateSkill(string $id, string $name, ?string $categoryId = null): Skill
    {
        $skillId = new SkillId($id);
        $skill = $this->repository->find($skillId);

        if (!$skill) {
            throw new DomainException('Skill not found');
        }

        $skillName = new SkillName($name);

        // If changing category
        if ($categoryId !== null) {
            $newCategoryId = new CategoryId($categoryId);

            // Check if skill name exists in new category
            if ($this->repository->nameExistsInCategory($name, $newCategoryId, $skillId)) {
                throw new DomainException("Skill '{$name}' already exists in the target category");
            }

            $skill->changeCategory($newCategoryId);
        } else {
            // Check if skill name exists in current category
            if ($this->repository->nameExistsInCategory($name, $skill->categoryId(), $skillId)) {
                throw new DomainException("Skill '{$name}' already exists in this category");
            }
        }

        $skill->update($skillName);

        $this->repository->update($skill);

        return $skill;
    }

    public function getSkill(string $id): ?Skill
    {
        $skillId = new SkillId($id);
        return $this->repository->find($skillId);
    }

    public function getAllSkills(): array
    {
        return $this->repository->findAll();
    }

    public function getSkillsByCategory(string $categoryId): array
    {
        $categoryIdVO = new CategoryId($categoryId);
        return $this->repository->findByCategory($categoryIdVO);
    }

    public function deleteSkill(string $id): void
    {
        $skillId = new SkillId($id);

        if (!$this->repository->exists($skillId)) {
            throw new DomainException('Skill not found');
        }

        $this->repository->delete($skillId);
    }

    public function syncSkillsByCategory(string $categoryName, array $skillNames): array
    {
        // Find or create category
        $category = $this->categoryService->findOrCreateCategory($categoryName);

        // Get existing skills for this category
        $existingSkills = $this->repository->findByCategory($category->id());

        // Map existing skills by name for easy lookup
        $existingSkillsMap = [];
        foreach ($existingSkills as $skill) {
            $existingSkillsMap[$skill->name()->value()] = $skill;
        }

        // Determine which skills to add and which to keep
        $newSkills = [];
        $keptSkillNames = [];

        foreach ($skillNames as $skillName) {
            $trimmedName = trim($skillName);

            if (isset($existingSkillsMap[$trimmedName])) {
                // Skill already exists, keep it
                $keptSkillNames[] = $trimmedName;
            } else {
                // New skill, create it
                $skill = Skill::create(
                    SkillId::generate(),
                    $category->id(),
                    new SkillName($trimmedName)
                );
                $newSkills[] = $skill;
            }
        }

        // Delete skills that are no longer in the list
        $deletedCount = 0;
        foreach ($existingSkills as $existingSkill) {
            if (!in_array($existingSkill->name()->value(), $keptSkillNames) &&
                !in_array($existingSkill->name()->value(), $skillNames)) {
                $this->repository->delete($existingSkill->id());
                $deletedCount++;
            }
        }

        // Batch save new skills
        if (!empty($newSkills)) {
            $this->repository->saveBatch($newSkills);
        }

        // Get all current skills for this category
        $currentSkills = $this->repository->findByCategory($category->id());

        return [
            'category' => $category,
            'skills' => $currentSkills,
            'added_count' => count($newSkills),
            'deleted_count' => $deletedCount,
            'total_count' => count($currentSkills),
        ];
    }
}

