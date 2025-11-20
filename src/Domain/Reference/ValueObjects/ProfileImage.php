<?php

namespace Src\Domain\Reference\ValueObjects;

final class ProfileImage
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

    public function path(): ?string
    {
        return $this->value;
    }

    public function url(): ?string
    {
        if ($this->value === null) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->value, FILTER_VALIDATE_URL)) {
            return $this->value;
        }

        // Otherwise, prepend asset URL
        return asset($this->value);
    }

    public function equals(ProfileImage $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}

