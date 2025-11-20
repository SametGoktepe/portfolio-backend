<?php

namespace Src\Domain\Hobby\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class HobbyId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}

