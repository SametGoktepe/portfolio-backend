<?php

namespace Src\Domain\Project\ValueObjects;

use App\Enums\Status;
use Src\Domain\Shared\Exceptions\DomainException;

final class ProjectStatus
{
    private Status $status;

    public function __construct(string|Status $value)
    {
        if ($value instanceof Status) {
            $this->status = $value;
        } else {
            $this->status = Status::tryFrom($value) ?? throw new DomainException("Invalid status: {$value}");
        }
    }

    public function value(): string
    {
        return $this->status->value;
    }

    public function label(): string
    {
        return $this->status->label();
    }

    public function color(): string
    {
        return $this->status->color();
    }

    public function enum(): Status
    {
        return $this->status;
    }

    public function equals(ProjectStatus $other): bool
    {
        return $this->status === $other->enum();
    }

    public function isInProgress(): bool
    {
        return $this->status === Status::IN_PROGRESS;
    }

    public function isCompleted(): bool
    {
        return $this->status === Status::COMPLETED;
    }

    public function isBacklog(): bool
    {
        return $this->status === Status::BACKLOG;
    }

    public function isCancelled(): bool
    {
        return $this->status === Status::CANCELLED;
    }

    public function __toString(): string
    {
        return $this->status->value;
    }
}

