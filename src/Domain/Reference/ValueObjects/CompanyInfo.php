<?php

namespace Src\Domain\Reference\ValueObjects;

final class CompanyInfo
{
    private ?string $company;
    private ?string $position;

    public function __construct(?string $company, ?string $position)
    {
        $this->company = $company ? trim($company) : null;
        $this->position = $position ? trim($position) : null;
    }

    public function company(): ?string
    {
        return $this->company;
    }

    public function position(): ?string
    {
        return $this->position;
    }

    public function hasCompany(): bool
    {
        return !empty($this->company);
    }

    public function hasPosition(): bool
    {
        return !empty($this->position);
    }

    public function toArray(): array
    {
        return [
            'company' => $this->company,
            'position' => $this->position,
        ];
    }

    public function equals(CompanyInfo $other): bool
    {
        return $this->company === $other->company()
            && $this->position === $other->position();
    }
}

