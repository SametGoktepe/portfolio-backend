<?php

namespace Src\Domain\Education\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class EducationId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}

