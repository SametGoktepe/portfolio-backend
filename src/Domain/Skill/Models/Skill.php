<?php

namespace Src\Domain\Skill\Models;

use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Skill\ValueObjects\SkillName;

final class Skill
{
    private SkillId $id;
    private CategoryId $categoryId;
    private SkillName $name;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        SkillId $id,
        CategoryId $categoryId,
        SkillName $name,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        SkillId $id,
        CategoryId $categoryId,
        SkillName $name,
    ): self {
        $now = new \DateTimeImmutable();
        
        return new self(
            $id,
            $categoryId,
            $name,
            $now,
            $now
        );
    }

    public function update(
        SkillName $name,
    ): void {
        $this->name = $name;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function changeCategory(CategoryId $categoryId): void
    {
        $this->categoryId = $categoryId;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function belongsToCategory(CategoryId $categoryId): bool
    {
        return $this->categoryId->equals($categoryId);
    }

    // Getters
    public function id(): SkillId
    {
        return $this->id;
    }

    public function categoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function name(): SkillName
    {
        return $this->name;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Skill $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'category_id' => $this->categoryId->value(),
            'name' => $this->name->value(),
        ];
    }
}