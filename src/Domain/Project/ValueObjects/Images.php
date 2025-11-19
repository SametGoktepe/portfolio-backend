<?php

namespace Src\Domain\Project\ValueObjects;

final class Images
{
    private array $value;

    public function __construct(array $images = [])
    {
        $this->value = array_values(array_filter($images, fn($img) => !empty(trim($img))));
    }

    public function value(): array
    {
        return $this->value;
    }

    public function count(): int
    {
        return count($this->value);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function first(): ?string
    {
        return $this->value[0] ?? null;
    }

    public function toJson(): string
    {
        return json_encode($this->value);
    }

    public function equals(Images $other): bool
    {
        return $this->value === $other->value();
    }
}

