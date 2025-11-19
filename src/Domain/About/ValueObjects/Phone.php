<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Phone
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidPhone($value);
        $this->value = $this->normalize($value);
    }

    private function ensureIsValidPhone(string $phone): void
    {
        $cleaned = preg_replace('/[^0-9+]/', '', $phone);
        
        if (strlen($cleaned) < 10 || strlen($cleaned) > 15) {
            throw new DomainException(
                sprintf('The phone number <%s> is not valid', $phone)
            );
        }
    }

    private function normalize(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function formatted(): string
    {
        // Türkiye telefon formatı örneği
        if (preg_match('/^(\+90|0)?(\d{3})(\d{3})(\d{2})(\d{2})$/', $this->value, $matches)) {
            return sprintf('+90 %s %s %s %s', $matches[2], $matches[3], $matches[4], $matches[5]);
        }
        
        return $this->value;
    }

    public function equals(Phone $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->formatted();
    }
}