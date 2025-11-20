<?php

namespace Src\Domain\Reference\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class FullName
{
    private string $value;

    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 100;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = trim($value);
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Full name cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Full name must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Full name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(FullName $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

