<?php

namespace Src\Domain\WorkLife\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class WorkPeriod
{
    private int $startYear;
    private ?int $endYear;
    private bool $isOngoing;

    public function __construct(int $startYear, ?int $endYear, bool $isOngoing = false)
    {
        $this->ensureIsValid($startYear, $endYear, $isOngoing);
        $this->startYear = $startYear;
        $this->endYear = $endYear;
        $this->isOngoing = $isOngoing;
    }

    private function ensureIsValid(int $startYear, ?int $endYear, bool $isOngoing): void
    {
        $currentYear = (int) date('Y');

        if ($startYear < 1900 || $startYear > $currentYear + 1) {
            throw new DomainException('Start year must be between 1900 and ' . ($currentYear + 1));
        }

        if ($endYear !== null && ($endYear < 1900 || $endYear > $currentYear + 1)) {
            throw new DomainException('End year must be between 1900 and ' . ($currentYear + 1));
        }

        if ($endYear !== null && $startYear > $endYear) {
            throw new DomainException('Start year cannot be after end year');
        }

        // If ongoing, end year should be null
        if ($isOngoing && $endYear !== null) {
            throw new DomainException('End year must be null for ongoing positions');
        }

        // If not ongoing, end year should be set
        if (!$isOngoing && $endYear === null) {
            throw new DomainException('End year is required for completed positions');
        }
    }

    public function startYear(): int
    {
        return $this->startYear;
    }

    public function endYear(): ?int
    {
        return $this->endYear;
    }

    public function isOngoing(): bool
    {
        return $this->isOngoing;
    }

    public function duration(): string
    {
        $end = $this->endYear ?? (int) date('Y');
        $years = $end - $this->startYear;

        if ($years === 0) {
            return 'Less than a year';
        } elseif ($years === 1) {
            return '1 year';
        } else {
            return "{$years} years";
        }
    }

    public function toArray(): array
    {
        return [
            'start_year' => $this->startYear,
            'end_year' => $this->endYear,
            'is_ongoing' => $this->isOngoing,
            'duration' => $this->duration(),
        ];
    }

    public function equals(WorkPeriod $other): bool
    {
        return $this->startYear === $other->startYear()
            && $this->endYear === $other->endYear()
            && $this->isOngoing === $other->isOngoing();
    }
}

