<?php

namespace Src\Domain\WorkLife\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class WorkLifeId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}

