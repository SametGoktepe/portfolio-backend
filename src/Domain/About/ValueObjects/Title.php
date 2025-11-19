<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Title
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
            throw new DomainException('Title cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Title must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Title cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        // Özel karakterler kontrolü
        if (!preg_match('/^[a-zA-Z0-9\s\-\.\/\&]+$/u', $trimmedValue)) {
            throw new DomainException('Title contains invalid characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function slug(): string
    {
        $slug = strtolower($this->value);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        return $slug;
    }

    public function uppercase(): string
    {
        return strtoupper($this->value);
    }

    public function capitalize(): string
    {
        return ucwords(strtolower($this->value));
    }

    public function equals(Title $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}