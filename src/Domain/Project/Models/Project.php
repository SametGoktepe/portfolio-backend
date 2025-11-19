<?php

namespace Src\Domain\Project\Models;

use Src\Domain\Project\ValueObjects\Title;
use Src\Domain\Project\ValueObjects\Description;
use Src\Domain\Project\ValueObjects\Images;
use Src\Domain\Project\ValueObjects\Url;
use Src\Domain\Project\ValueObjects\Technologies;
use Src\Domain\Project\ValueObjects\ProjectStatus;

final class Project
{
    private ProjectId $id;
    private Title $title;
    private Description $description;
    private Images $images;
    private Url $githubUrl;
    private Url $demoLink;
    private Technologies $technologies;
    private ProjectStatus $status;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        ProjectId $id,
        Title $title,
        Description $description,
        Images $images,
        Url $githubUrl,
        Url $demoLink,
        Technologies $technologies,
        ProjectStatus $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->images = $images;
        $this->githubUrl = $githubUrl;
        $this->demoLink = $demoLink;
        $this->technologies = $technologies;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        ProjectId $id,
        Title $title,
        Description $description,
        Images $images,
        Url $githubUrl,
        Url $demoLink,
        Technologies $technologies,
        ProjectStatus $status
    ): self {
        $now = new \DateTimeImmutable();
        
        return new self(
            $id,
            $title,
            $description,
            $images,
            $githubUrl,
            $demoLink,
            $technologies,
            $status,
            $now,
            $now
        );
    }

    public function update(
        Title $title,
        Description $description,
        Images $images,
        Url $githubUrl,
        Url $demoLink,
        Technologies $technologies,
        ProjectStatus $status
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->images = $images;
        $this->githubUrl = $githubUrl;
        $this->demoLink = $demoLink;
        $this->technologies = $technologies;
        $this->status = $status;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsCompleted(): void
    {
        $this->status = new ProjectStatus('completed');
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsInProgress(): void
    {
        $this->status = new ProjectStatus('in_progress');
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsBacklog(): void
    {
        $this->status = new ProjectStatus('backlog');
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsCancelled(): void
    {
        $this->status = new ProjectStatus('cancelled');
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): ProjectId
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function images(): Images
    {
        return $this->images;
    }

    public function githubUrl(): Url
    {
        return $this->githubUrl;
    }

    public function demoLink(): Url
    {
        return $this->demoLink;
    }

    public function technologies(): Technologies
    {
        return $this->technologies;
    }

    public function status(): ProjectStatus
    {
        return $this->status;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Project $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'title' => $this->title->value(),
            'description' => $this->description->value(),
            'images' => $this->images->value(),
            'github_url' => $this->githubUrl->value(),
            'demo_link' => $this->demoLink->value(),
            'technologies' => $this->technologies->value(),
            'status' => [
                'value' => $this->status->value(),
                'label' => $this->status->label(),
                'color' => $this->status->color(),
            ],
        ];
    }
}

