<?php

namespace Src\Domain\Auth\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Password
{
    private string $value;

    private const MIN_LENGTH = 8;
    private const MAX_LENGTH = 255;

    public function __construct(string $value, bool $hashed = false)
    {
        if (!$hashed) {
            $this->ensureIsValid($value);
        }

        $this->value = $value;
    }

    private function ensureIsValid(string $value): void
    {
        if (empty($value)) {
            throw new DomainException('Password cannot be empty');
        }

        if (strlen($value) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Password must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($value) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Password cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function hash(): string
    {
        return password_hash($this->value, PASSWORD_BCRYPT);
    }

    public function verify(string $hashedPassword): bool
    {
        return password_verify($this->value, $hashedPassword);
    }

    public function value(): string
    {
        return $this->value;
    }
}

