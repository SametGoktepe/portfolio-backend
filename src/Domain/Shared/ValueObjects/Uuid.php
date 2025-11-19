<?php

namespace Src\Domain\Shared\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Uuid
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    protected static function random(): string
    {
        return RamseyUuid::uuid4()->toString();
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new DomainException(
                sprintf('<%s> does not allow the value <%s>', static::class, $id)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}