<?php

namespace Src\Domain\WorkLife\Models;

use Src\Domain\WorkLife\ValueObjects\Company;
use Src\Domain\WorkLife\ValueObjects\Position;
use Src\Domain\WorkLife\ValueObjects\WorkPeriod;
use Src\Domain\WorkLife\ValueObjects\WorkDescription;

final class WorkLife
{
    private WorkLifeId $id;
    private Company $company;
    private Position $position;
    private WorkPeriod $workPeriod;
    private WorkDescription $description;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        WorkLifeId $id,
        Company $company,
        Position $position,
        WorkPeriod $workPeriod,
        WorkDescription $description,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->company = $company;
        $this->position = $position;
        $this->workPeriod = $workPeriod;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        WorkLifeId $id,
        Company $company,
        Position $position,
        WorkPeriod $workPeriod,
        WorkDescription $description
    ): self {
        $now = new \DateTimeImmutable();
        
        return new self(
            $id,
            $company,
            $position,
            $workPeriod,
            $description,
            $now,
            $now
        );
    }

    public function update(
        Company $company,
        Position $position,
        WorkPeriod $workPeriod,
        WorkDescription $description
    ): void {
        $this->company = $company;
        $this->position = $position;
        $this->workPeriod = $workPeriod;
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): WorkLifeId
    {
        return $this->id;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function workPeriod(): WorkPeriod
    {
        return $this->workPeriod;
    }

    public function description(): WorkDescription
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

    public function equals(WorkLife $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'company_name' => $this->company->value(),
            'position' => $this->position->value(),
            'work_period' => $this->workPeriod->toArray(),
            'description' => $this->description->value(),
        ];
    }
}

