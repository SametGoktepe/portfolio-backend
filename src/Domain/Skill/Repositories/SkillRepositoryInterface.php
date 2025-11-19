<?php

namespace Src\Domain\Skill\Repositories;

use Src\Domain\Skill\Models\Skill;
use Src\Domain\Skill\Models\SkillId;
use Src\Domain\Category\Models\CategoryId;

interface SkillRepositoryInterface
{
    public function find(SkillId $id): ?Skill;

    public function findAll(): array;

    public function findByCategory(CategoryId $categoryId): array;

    public function save(Skill $skill): void;

    public function saveBatch(array $skills): void;

    public function update(Skill $skill): void;

    public function delete(SkillId $id): void;

    public function exists(SkillId $id): bool;

    public function nameExistsInCategory(string $name, CategoryId $categoryId, ?SkillId $excludeId = null): bool;
}

