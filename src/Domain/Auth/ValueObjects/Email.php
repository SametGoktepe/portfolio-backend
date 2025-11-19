<?php

namespace Src\Domain\Auth\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = strtolower(trim($value));
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Email cannot be empty');
        }

        if (!filter_var($trimmedValue, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('Invalid email format');
        }

        if (strlen($trimmedValue) > 255) {
            throw new DomainException('Email cannot exceed 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

