<?php

namespace Src\Domain\Project\Services;

use Src\Domain\Project\Models\Project;
use Src\Domain\Project\Models\ProjectId;
use Src\Domain\Project\ValueObjects\Title;
use Src\Domain\Project\ValueObjects\Description;
use Src\Domain\Project\ValueObjects\Images;
use Src\Domain\Project\ValueObjects\Url;
use Src\Domain\Project\ValueObjects\Technologies;
use Src\Domain\Project\ValueObjects\ProjectStatus;
use Src\Domain\Project\Repositories\ProjectRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class ProjectService
{
    private ProjectRepositoryInterface $repository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createProject(array $data): Project
    {
        $project = Project::create(
            ProjectId::generate(),
            new Title($data['title']),
            new Description($data['description']),
            new Images($data['images'] ?? []),
            new Url($data['github_url'] ?? null),
            new Url($data['demo_link'] ?? null),
            new Technologies($data['technologies']),
            new ProjectStatus($data['status'] ?? 'in_progress')
        );

        $this->repository->save($project);

        return $project;
    }

    public function updateProject(string $id, array $data): Project
    {
        $projectId = new ProjectId($id);
        $project = $this->repository->find($projectId);

        if (!$project) {
            throw new DomainException('Project not found');
        }

        $project->update(
            new Title($data['title']),
            new Description($data['description']),
            new Images($data['images'] ?? []),
            new Url($data['github_url'] ?? null),
            new Url($data['demo_link'] ?? null),
            new Technologies($data['technologies']),
            new ProjectStatus($data['status'] ?? 'in_progress')
        );

        $this->repository->update($project);

        return $project;
    }

    public function getProject(string $id): ?Project
    {
        $projectId = new ProjectId($id);
        return $this->repository->find($projectId);
    }

    public function getAllProjects(): array
    {
        return $this->repository->findAll();
    }

    public function getProjectsPaginated(int $perPage = 15, ?string $status = null): array
    {
        return $this->repository->paginate($perPage, $status);
    }

    public function getProjectsByStatus(string $status): array
    {
        return $this->repository->findByStatus($status);
    }

    public function deleteProject(string $id): void
    {
        $projectId = new ProjectId($id);
        
        if (!$this->repository->exists($projectId)) {
            throw new DomainException('Project not found');
        }

        $this->repository->delete($projectId);
    }

    public function changeProjectStatus(string $id, string $status): Project
    {
        $projectId = new ProjectId($id);
        $project = $this->repository->find($projectId);

        if (!$project) {
            throw new DomainException('Project not found');
        }

        $newStatus = new ProjectStatus($status);

        // Use domain methods for status changes
        match ($status) {
            'completed' => $project->markAsCompleted(),
            'in_progress' => $project->markAsInProgress(),
            'backlog' => $project->markAsBacklog(),
            'cancelled' => $project->markAsCancelled(),
            default => throw new DomainException("Invalid status: {$status}")
        };

        $this->repository->update($project);

        return $project;
    }
}

