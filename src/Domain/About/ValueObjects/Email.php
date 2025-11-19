<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidEmail($value);
        $this->value = $value;
    }

    private function ensureIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException(
                sprintf('The email <%s> is not valid', $email)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}