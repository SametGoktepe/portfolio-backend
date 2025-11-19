<?php

namespace Src\Domain\Category\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class CategoryName
{
    private string $value;
    
    private const MIN_LENGTH = 2;
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
            throw new DomainException('Category name cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Category name must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Category name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match('/^[\p{L}\p{N}\s\-\.]+$/u', $trimmedValue)) {
            throw new DomainException('Category name contains invalid characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(CategoryName $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}