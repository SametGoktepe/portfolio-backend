<?php

namespace Src\Domain\Reference\ValueObjects;

final class ContactInfo
{
    private ?string $email;
    private ?string $phone;

    public function __construct(?string $email, ?string $phone)
    {
        $this->email = $email ? trim($email) : null;
        $this->phone = $phone ? trim($phone) : null;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function phone(): ?string
    {
        return $this->phone;
    }

    public function hasEmail(): bool
    {
        return !empty($this->email);
    }

    public function hasPhone(): bool
    {
        return !empty($this->phone);
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    public function equals(ContactInfo $other): bool
    {
        return $this->email === $other->email()
            && $this->phone === $other->phone();
    }
}

