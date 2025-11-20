<?php

namespace Src\Domain\Hobby\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class HobbyImage
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = trim($value);
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Hobby image cannot be empty');
        }

        if (strlen($trimmedValue) > 255) {
            throw new DomainException('Hobby image path cannot exceed 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function path(): string
    {
        return $this->value;
    }

    public function url(): string
    {
        // If it's already a full URL, return as is
        if (filter_var($this->value, FILTER_VALIDATE_URL)) {
            return $this->value;
        }

        // Otherwise, prepend asset URL
        return asset($this->value);
    }

    public function equals(HobbyImage $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

