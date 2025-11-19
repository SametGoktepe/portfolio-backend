<?php

namespace Src\Domain\Education\Models;

use Src\Domain\Education\ValueObjects\School;
use Src\Domain\Education\ValueObjects\Degree;
use Src\Domain\Education\ValueObjects\FieldOfStudy;
use Src\Domain\Education\ValueObjects\YearPeriod;

final class Education
{
    private EducationId $id;
    private School $school;
    private Degree $degree;
    private FieldOfStudy $fieldOfStudy;
    private YearPeriod $yearPeriod;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        EducationId $id,
        School $school,
        Degree $degree,
        FieldOfStudy $fieldOfStudy,
        YearPeriod $yearPeriod,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->school = $school;
        $this->degree = $degree;
        $this->fieldOfStudy = $fieldOfStudy;
        $this->yearPeriod = $yearPeriod;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        EducationId $id,
        School $school,
        Degree $degree,
        FieldOfStudy $fieldOfStudy,
        YearPeriod $yearPeriod
    ): self {
        $now = new \DateTimeImmutable();

        return new self(
            $id,
            $school,
            $degree,
            $fieldOfStudy,
            $yearPeriod,
            $now,
            $now
        );
    }

    public function update(
        School $school,
        Degree $degree,
        FieldOfStudy $fieldOfStudy,
        YearPeriod $yearPeriod
    ): void {
        $this->school = $school;
        $this->degree = $degree;
        $this->fieldOfStudy = $fieldOfStudy;
        $this->yearPeriod = $yearPeriod;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): EducationId
    {
        return $this->id;
    }

    public function school(): School
    {
        return $this->school;
    }

    public function degree(): Degree
    {
        return $this->degree;
    }

    public function fieldOfStudy(): FieldOfStudy
    {
        return $this->fieldOfStudy;
    }

    public function yearPeriod(): YearPeriod
    {
        return $this->yearPeriod;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Education $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'school' => $this->school->value(),
            'degree' => $this->degree->value(),
            'field_of_study' => $this->fieldOfStudy->value(),
            'year_period' => $this->yearPeriod->toArray(),
        ];
    }
}

