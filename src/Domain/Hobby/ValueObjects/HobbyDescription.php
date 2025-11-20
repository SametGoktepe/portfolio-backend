<?php

namespace Src\Domain\Hobby\ValueObjects;

final class HobbyDescription
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value ? trim($value) : null;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function equals(HobbyDescription $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}

