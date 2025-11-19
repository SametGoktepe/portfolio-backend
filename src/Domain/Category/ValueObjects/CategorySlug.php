<?php

namespace Src\Domain\Category\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;
use Illuminate\Support\Str;

final class CategorySlug
{
    private string $value;
    
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 60;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    public static function fromName(string $name): self
    {
        $slug = Str::slug($name);
        return new self($slug);
    }

    private function ensureIsValid(string $value): void
    {
        if (empty($value)) {
            throw new DomainException('Category slug cannot be empty');
        }

        if (strlen($value) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Category slug must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Category slug cannot exceed %d characters', self::MAX_LENGTH)
            );
        }

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value)) {
            throw new DomainException(
                'Category slug must contain only lowercase letters, numbers, and hyphens'
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(CategorySlug $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}