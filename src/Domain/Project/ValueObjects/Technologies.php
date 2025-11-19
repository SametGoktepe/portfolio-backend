<?php

namespace Src\Domain\Project\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Technologies
{
    private array $value;

    public function __construct(array $technologies)
    {
        $this->ensureIsValid($technologies);
        $this->value = array_values(array_unique(array_map('trim', $technologies)));
    }

    private function ensureIsValid(array $technologies): void
    {
        if (empty($technologies)) {
            throw new DomainException('At least one technology is required');
        }

        foreach ($technologies as $tech) {
            if (!is_string($tech)) {
                throw new DomainException('All technologies must be strings');
            }

            $trimmed = trim($tech);
            if (empty($trimmed)) {
                throw new DomainException('Technology name cannot be empty');
            }

            if (strlen($trimmed) > 50) {
                throw new DomainException('Technology name cannot exceed 50 characters');
            }
        }
    }

    public function value(): array
    {
        return $this->value;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function contains(string $technology): bool
    {
        return in_array(trim($technology), $this->value, true);
    }

    public function toJson(): string
    {
        return json_encode($this->value);
    }

    public function equals(Technologies $other): bool
    {
        return $this->value === $other->value();
    }
}

