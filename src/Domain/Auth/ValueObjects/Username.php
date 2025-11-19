<?php

namespace Src\Domain\Auth\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Username
{
    private string $value;

    private const MIN_LENGTH = 3;
    private const MAX_LENGTH = 50;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = trim($value);
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Username cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Username must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Username cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $trimmedValue)) {
            throw new DomainException('Username can only contain letters, numbers, underscores, and hyphens');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Username $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

