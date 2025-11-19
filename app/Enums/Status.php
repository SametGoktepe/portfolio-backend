<?php

namespace App\Enums;

enum Status: string
{
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case BACKLOG = 'backlog';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::BACKLOG => 'Backlog',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function value(): string
    {
        return $this->value;
    }

    public function color(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'bg-blue-500',
            self::COMPLETED => 'bg-green-500',
            self::BACKLOG => 'bg-yellow-500',
            self::CANCELLED => 'bg-red-500',
        };
    }

    public function textColor(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'text-white-500',
            self::COMPLETED => 'text-white-500',
            self::BACKLOG => 'text-white-500',
            self::CANCELLED => 'text-white-500',
        };
    }
}
