<?php

namespace Src\Domain\Category\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class CategoryId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}