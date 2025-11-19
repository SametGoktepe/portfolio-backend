<?php

namespace Src\Domain\Project\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class ProjectId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}

