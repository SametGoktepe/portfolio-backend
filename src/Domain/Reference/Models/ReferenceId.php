<?php

namespace Src\Domain\Reference\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class ReferenceId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}

