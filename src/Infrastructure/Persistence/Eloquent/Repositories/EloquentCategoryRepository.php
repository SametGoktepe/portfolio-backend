<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Category\Models\Category;
use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Category\ValueObjects\CategoryName;
use Src\Domain\Category\ValueObjects\CategorySlug;
use Src\Domain\Category\Repositories\CategoryRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentCategory;
use Src\Domain\Skill\Models\Skill;
use Src\Domain\Skill\Models\SkillId;
use Src\Domain\Skill\ValueObjects\SkillName;

final class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function find(CategoryId $id): ?Category
    {
        $eloquentCategory = EloquentCategory::find($id->value());

        if (!$eloquentCategory) {
            return null;
        }

        return $this->toDomain($eloquentCategory);
    }

    public function findBySlug(CategorySlug $slug): ?Category
    {
        $eloquentCategory = EloquentCategory::where('slug', $slug->value())->first();

        if (!$eloquentCategory) {
            return null;
        }

        return $this->toDomain($eloquentCategory);
    }

    public function findAll(): array
    {
        $eloquentCategories = EloquentCategory::all();

        return $eloquentCategories->map(fn($category) => $this->toDomain($category))->toArray();
    }

    public function findWithSkills(CategoryId $id): ?Category
    {
        $eloquentCategory = EloquentCategory::with('skills')->find($id->value());

        if (!$eloquentCategory) {
            return null;
        }

        return $this->toDomain($eloquentCategory, true);
    }

    public function save(Category $category): void
    {
        $eloquentCategory = new EloquentCategory();

        $this->mapToEloquent($eloquentCategory, $category);

        $eloquentCategory->save();
    }

    public function update(Category $category): void
    {
        $eloquentCategory = EloquentCategory::findOrFail($category->id()->value());

        $this->mapToEloquent($eloquentCategory, $category);

        $eloquentCategory->save();
    }

    public function delete(CategoryId $id): void
    {
        EloquentCategory::destroy($id->value());
    }

    public function exists(CategoryId $id): bool
    {
        return EloquentCategory::where('id', $id->value())->exists();
    }

    public function slugExists(CategorySlug $slug, ?CategoryId $excludeId = null): bool
    {
        $query = EloquentCategory::where('slug', $slug->value());

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId->value());
        }

        return $query->exists();
    }

    private function toDomain(EloquentCategory $eloquentCategory, bool $withSkills = false): Category
    {
        $skills = [];

        if ($withSkills && $eloquentCategory->relationLoaded('skills')) {
            $skills = $eloquentCategory->skills->map(function ($eloquentSkill) use ($eloquentCategory) {
                return Skill::create(
                    new SkillId($eloquentSkill->id),
                    new CategoryId($eloquentCategory->id),
                    new SkillName($eloquentSkill->name)
                );
            })->toArray();
        }

        return new Category(
            new CategoryId($eloquentCategory->id),
            new CategoryName($eloquentCategory->name),
            new CategorySlug($eloquentCategory->slug),
            $skills,
            new \DateTimeImmutable($eloquentCategory->created_at),
            new \DateTimeImmutable($eloquentCategory->updated_at)
        );
    }

    private function mapToEloquent(EloquentCategory $eloquentCategory, Category $category): void
    {
        $eloquentCategory->id = $category->id()->value();
        $eloquentCategory->name = $category->name()->value();
        $eloquentCategory->slug = $category->slug()->value();
    }
}

