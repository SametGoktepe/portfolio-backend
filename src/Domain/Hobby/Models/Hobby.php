<?php

namespace Src\Domain\Hobby\Models;

use Src\Domain\Hobby\ValueObjects\HobbyName;
use Src\Domain\Hobby\ValueObjects\HobbyImage;
use Src\Domain\Hobby\ValueObjects\HobbyDescription;

final class Hobby
{
    private HobbyId $id;
    private HobbyName $name;
    private HobbyImage $image;
    private HobbyDescription $description;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        HobbyId $id,
        HobbyName $name,
        HobbyImage $image,
        HobbyDescription $description,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        HobbyId $id,
        HobbyName $name,
        HobbyImage $image,
        HobbyDescription $description
    ): self {
        $now = new \DateTimeImmutable();
        
        return new self(
            $id,
            $name,
            $image,
            $description,
            $now,
            $now
        );
    }

    public function update(
        HobbyName $name,
        HobbyImage $image,
        HobbyDescription $description
    ): void {
        $this->name = $name;
        $this->image = $image;
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): HobbyId
    {
        return $this->id;
    }

    public function name(): HobbyName
    {
        return $this->name;
    }

    public function image(): HobbyImage
    {
        return $this->image;
    }

    public function description(): HobbyDescription
    {
        return $this->description;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Hobby $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'image' => $this->image->url(),
            'description' => $this->description->value(),
        ];
    }
}

