<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Image
{
    private ?string $path;
    private ?string $url;

    public function __construct(?string $path = null)
    {
        $this->path = $path;
        $this->url = $path ? $this->generateUrl($path) : null;
    }

    private function generateUrl(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return config('app.url') . '/' . ltrim($path, '/');
    }

    public function path(): ?string
    {
        return $this->path;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function exists(): bool
    {
        return $this->path !== null;
    }

    public function __toString(): string
    {
        return $this->url ?? '';
    }
}