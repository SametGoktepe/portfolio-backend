<?php

namespace Src\Domain\Skill\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class SkillName
{
    private string $value;
    
    private const MIN_LENGTH = 1;
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
            throw new DomainException('Skill name cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Skill name must be at least %d character long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Skill name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(SkillName $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}