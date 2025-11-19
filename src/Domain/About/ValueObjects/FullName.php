<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class FullName
{
    private string $firstName;
    private string $lastName;
    private ?string $middleName;

    public function __construct(string $value)
    {
        $this->ensureIsNotEmpty($value);
        $this->parseFullName($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new DomainException('Full name cannot be empty');
        }

        if (strlen($value) < 3) {
            throw new DomainException('Full name must be at least 3 characters long');
        }

        if (strlen($value) > 100) {
            throw new DomainException('Full name cannot exceed 100 characters');
        }
    }

    private function parseFullName(string $value): void
    {
        $parts = array_filter(explode(' ', trim($value)));
        
        if (count($parts) < 2) {
            throw new DomainException('Full name must contain at least first name and last name');
        }

        $this->firstName = array_shift($parts);
        $this->lastName = array_pop($parts);
        $this->middleName = count($parts) > 0 ? implode(' ', $parts) : null;
    }

    public function value(): string
    {
        $parts = array_filter([
            $this->firstName,
            $this->middleName,
            $this->lastName
        ]);

        return implode(' ', $parts);
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function middleName(): ?string
    {
        return $this->middleName;
    }

    public function initials(): string
    {
        $initials = strtoupper(substr($this->firstName, 0, 1));
        
        if ($this->middleName) {
            $initials .= strtoupper(substr($this->middleName, 0, 1));
        }
        
        $initials .= strtoupper(substr($this->lastName, 0, 1));
        
        return $initials;
    }

    public function equals(FullName $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }
}