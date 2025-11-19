<?php

namespace Src\Domain\Education\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class FieldOfStudy
{
    private ?string $value;
    
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 255;

    public function __construct(?string $value)
    {
        if ($value !== null) {
            $this->ensureIsValid($value);
            $this->value = trim($value);
        } else {
            $this->value = null;
        }
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Field of study cannot be empty when provided');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Field of study must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Field of study cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function equals(FieldOfStudy $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}

