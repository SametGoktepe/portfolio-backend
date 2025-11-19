<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Skill\Models\Skill;
use Src\Domain\Skill\Models\SkillId;
use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Skill\ValueObjects\SkillName;
use Src\Domain\Skill\Repositories\SkillRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentSkill;

final class EloquentSkillRepository implements SkillRepositoryInterface
{
    public function find(SkillId $id): ?Skill
    {
        $eloquentSkill = EloquentSkill::find($id->value());

        if (!$eloquentSkill) {
            return null;
        }

        return $this->toDomain($eloquentSkill);
    }

    public function findAll(): array
    {
        $eloquentSkills = EloquentSkill::all();

        return $eloquentSkills->map(fn($skill) => $this->toDomain($skill))->toArray();
    }

    public function findByCategory(CategoryId $categoryId): array
    {
        $eloquentSkills = EloquentSkill::where('category_id', $categoryId->value())->get();

        return $eloquentSkills->map(fn($skill) => $this->toDomain($skill))->toArray();
    }

    public function save(Skill $skill): void
    {
        $eloquentSkill = new EloquentSkill();

        $this->mapToEloquent($eloquentSkill, $skill);

        $eloquentSkill->save();
    }

    public function saveBatch(array $skills): void
    {
        $data = array_map(function (Skill $skill) {
            return [
                'id' => $skill->id()->value(),
                'category_id' => $skill->categoryId()->value(),
                'name' => $skill->name()->value(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $skills);

        EloquentSkill::insert($data);
    }

    public function update(Skill $skill): void
    {
        $eloquentSkill = EloquentSkill::findOrFail($skill->id()->value());

        $this->mapToEloquent($eloquentSkill, $skill);

        $eloquentSkill->save();
    }

    public function delete(SkillId $id): void
    {
        EloquentSkill::destroy($id->value());
    }

    public function exists(SkillId $id): bool
    {
        return EloquentSkill::where('id', $id->value())->exists();
    }

    public function nameExistsInCategory(string $name, CategoryId $categoryId, ?SkillId $excludeId = null): bool
    {
        $query = EloquentSkill::where('category_id', $categoryId->value())
            ->where('name', $name);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId->value());
        }

        return $query->exists();
    }

    private function toDomain(EloquentSkill $eloquentSkill): Skill
    {
        return new Skill(
            new SkillId($eloquentSkill->id),
            new CategoryId($eloquentSkill->category_id),
            new SkillName($eloquentSkill->name),
            new \DateTimeImmutable($eloquentSkill->created_at),
            new \DateTimeImmutable($eloquentSkill->updated_at)
        );
    }

    private function mapToEloquent(EloquentSkill $eloquentSkill, Skill $skill): void
    {
        $eloquentSkill->id = $skill->id()->value();
        $eloquentSkill->category_id = $skill->categoryId()->value();
        $eloquentSkill->name = $skill->name()->value();
    }
}

