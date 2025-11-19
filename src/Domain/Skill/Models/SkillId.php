<?php

namespace Src\Domain\Skill\Models;

use Src\Domain\Shared\ValueObjects\Uuid;

final class SkillId extends Uuid
{
    public static function generate(): self
    {
        return new self(self::random());
    }
}