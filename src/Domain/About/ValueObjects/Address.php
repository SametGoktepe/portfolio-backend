<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Address
{
    private string $city;
    private string $state;
    private string $country;
    private ?string $postalCode;

    public function __construct(
        string $city,
        string $state,
        string $country,
        ?string $postalCode = null
    ) {
        $this->ensureIsNotEmpty($city, 'city');
        $this->ensureIsNotEmpty($country, 'country');
        
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->postalCode = $postalCode;
    }

    private function ensureIsNotEmpty(string $value, string $field): void
    {
        if (empty(trim($value))) {
            throw new DomainException(
                sprintf('The address %s cannot be empty', $field)
            );
        }
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function postalCode(): ?string
    {
        return $this->postalCode;
    }

    public function fullAddress(): string
    {
        $parts = [
            $this->city,
            $this->state,
            $this->postalCode,
            $this->country
        ];

        return implode(', ', array_filter($parts));
    }

    public function toArray(): array
    {
        return [
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postalCode,
            'full_address' => $this->fullAddress()
        ];
    }

    public function equals(Address $other): bool
    {
        return $this->fullAddress() === $other->fullAddress();
    }

    public function __toString(): string
    {
        return $this->fullAddress();
    }
}