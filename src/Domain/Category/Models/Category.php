<?php

namespace Src\Domain\Category\Models;

use Src\Domain\Category\ValueObjects\CategoryName;
use Src\Domain\Category\ValueObjects\CategorySlug;
use Src\Domain\Skill\Models\Skill;

final class Category
{
    private CategoryId $id;
    private CategoryName $name;
    private CategorySlug $slug;
    private array $skills;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        CategoryId $id,
        CategoryName $name,
        CategorySlug $slug,
        array $skills,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->skills = $skills;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        CategoryId $id,
        CategoryName $name,
        ?CategorySlug $slug = null
    ): self {
        $now = new \DateTimeImmutable();
        
        // Auto-generate slug if not provided
        if ($slug === null) {
            $slug = CategorySlug::fromName($name->value());
        }
        
        return new self(
            $id,
            $name,
            $slug,
            [],
            $now,
            $now
        );
    }

    public function rename(CategoryName $name, ?CategorySlug $slug = null): void
    {
        $this->name = $name;
        
        if ($slug !== null) {
            $this->slug = $slug;
        } else {
            $this->slug = CategorySlug::fromName($name->value());
        }
        
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addSkill(Skill $skill): void
    {
        if (!$this->hasSkill($skill)) {
            $this->skills[] = $skill;
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function removeSkill(Skill $skill): void
    {
        $this->skills = array_filter(
            $this->skills,
            fn(Skill $s) => !$s->id()->equals($skill->id())
        );
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function hasSkill(Skill $skill): bool
    {
        foreach ($this->skills as $existingSkill) {
            if ($existingSkill->id()->equals($skill->id())) {
                return true;
            }
        }
        return false;
    }

    public function skillCount(): int
    {
        return count($this->skills);
    }

    // Getters
    public function id(): CategoryId
    {
        return $this->id;
    }

    public function name(): CategoryName
    {
        return $this->name;
    }

    public function slug(): CategorySlug
    {
        return $this->slug;
    }

    public function skills(): array
    {
        return $this->skills;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Category $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'slug' => $this->slug->value(),
            'skill_count' => $this->skillCount(),
            'skills' => array_map(fn(Skill $skill) => $skill->toArray(), $this->skills),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}