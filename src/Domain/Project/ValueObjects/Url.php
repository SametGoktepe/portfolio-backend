<?php

namespace Src\Domain\Project\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Url
{
    private ?string $value;

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
            return;
        }

        if (!filter_var($trimmedValue, FILTER_VALIDATE_URL)) {
            throw new DomainException('Invalid URL format');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function equals(Url $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}

