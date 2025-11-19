<?php

namespace Src\Domain\About\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class AboutId extends Uuid
{
    public static function generate(): self
    {
        return self::generate();
    }
}