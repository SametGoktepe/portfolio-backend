<?php

namespace Src\Domain\Education\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class YearPeriod
{
    private ?int $startYear;
    private ?int $endYear;

    public function __construct(?int $startYear, ?int $endYear)
    {
        $this->ensureIsValid($startYear, $endYear);
        $this->startYear = $startYear;
        $this->endYear = $endYear;
    }

    private function ensureIsValid(?int $startYear, ?int $endYear): void
    {
        $currentYear = (int) date('Y');

        if ($startYear !== null && ($startYear < 1900 || $startYear > $currentYear + 10)) {
            throw new DomainException('Start year must be between 1900 and ' . ($currentYear + 10));
        }

        if ($endYear !== null && ($endYear < 1900 || $endYear > $currentYear + 10)) {
            throw new DomainException('End year must be between 1900 and ' . ($currentYear + 10));
        }

        if ($startYear && $endYear && $startYear > $endYear) {
            throw new DomainException('Start year cannot be after end year');
        }
    }

    public function startYear(): ?int
    {
        return $this->startYear;
    }

    public function endYear(): ?int
    {
        return $this->endYear;
    }

    public function isOngoing(): bool
    {
        return $this->startYear !== null && $this->endYear === null;
    }

    public function duration(): ?string
    {
        if ($this->startYear === null) {
            return null;
        }

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
            'is_ongoing' => $this->isOngoing(),
            'duration' => $this->duration(),
        ];
    }

    public function equals(YearPeriod $other): bool
    {
        return $this->startYear === $other->startYear()
            && $this->endYear === $other->endYear();
    }
}

