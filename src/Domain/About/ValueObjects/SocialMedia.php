<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class SocialMedia
{
    private ?string $github;
    private ?string $linkedin;
    private ?string $twitter;

    public function __construct(
        ?string $github = null,
        ?string $linkedin = null,
        ?string $twitter = null
    ) {
        if ($github !== null) {
            $this->ensureIsValidUrl($github, 'GitHub');
        }
        if ($linkedin !== null) {
            $this->ensureIsValidUrl($linkedin, 'LinkedIn');
        }
        if ($twitter !== null) {
            $this->ensureIsValidUrl($twitter, 'Twitter');
        }

        $this->github = $github;
        $this->linkedin = $linkedin;
        $this->twitter = $twitter;
    }

    private function ensureIsValidUrl(string $url, string $platform): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new DomainException(
                sprintf('The %s URL <%s> is not valid', $platform, $url)
            );
        }
    }

    public function github(): ?string
    {
        return $this->github;
    }

    public function linkedin(): ?string
    {
        return $this->linkedin;
    }

    public function twitter(): ?string
    {
        return $this->twitter;
    }

    public function toArray(): array
    {
        return [
            'github' => $this->github,
            'linkedin' => $this->linkedin,
            'twitter' => $this->twitter,
        ];
    }

    public function hasAny(): bool
    {
        return $this->github !== null || 
               $this->linkedin !== null || 
               $this->twitter !== null;
    }
}