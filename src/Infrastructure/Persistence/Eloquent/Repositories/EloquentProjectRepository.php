<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Project\Models\Project;
use Src\Domain\Project\Models\ProjectId;
use Src\Domain\Project\ValueObjects\Title;
use Src\Domain\Project\ValueObjects\Description;
use Src\Domain\Project\ValueObjects\Images;
use Src\Domain\Project\ValueObjects\Url;
use Src\Domain\Project\ValueObjects\Technologies;
use Src\Domain\Project\ValueObjects\ProjectStatus;
use Src\Domain\Project\Repositories\ProjectRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentProject;

final class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function find(ProjectId $id): ?Project
    {
        $eloquentProject = EloquentProject::find($id->value());

        if (!$eloquentProject) {
            return null;
        }

        return $this->toDomain($eloquentProject);
    }

    public function findAll(): array
    {
        $eloquentProjects = EloquentProject::orderBy('created_at', 'desc')->get();

        return $eloquentProjects->map(fn($project) => $this->toDomain($project))->toArray();
    }

    public function paginate(int $perPage = 15, ?string $status = null): array
    {
        $query = EloquentProject::query()->orderBy('created_at', 'desc');

        if ($status !== null) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate($perPage);

        // Convert Eloquent models to Domain models
        $domainProjects = collect($paginator->items())
            ->map(fn($eloquentProject) => $this->toDomain($eloquentProject))
            ->toArray();

        return [
            'data' => $domainProjects,
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages(),
        ];
    }

    public function findByStatus(string $status): array
    {
        $eloquentProjects = EloquentProject::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return $eloquentProjects->map(fn($project) => $this->toDomain($project))->toArray();
    }

    public function save(Project $project): void
    {
        $eloquentProject = new EloquentProject();

        $this->mapToEloquent($eloquentProject, $project);

        $eloquentProject->save();
    }

    public function update(Project $project): void
    {
        $eloquentProject = EloquentProject::findOrFail($project->id()->value());

        $this->mapToEloquent($eloquentProject, $project);

        $eloquentProject->save();
    }

    public function delete(ProjectId $id): void
    {
        EloquentProject::destroy($id->value());
    }

    public function exists(ProjectId $id): bool
    {
        return EloquentProject::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentProject $eloquentProject): Project
    {
        return new Project(
            new ProjectId($eloquentProject->id),
            new Title($eloquentProject->title),
            new Description($eloquentProject->description),
            new Images($eloquentProject->images ?? []),
            new Url($eloquentProject->github_url),
            new Url($eloquentProject->demo_link),
            new Technologies($eloquentProject->technologies ?? []),
            new ProjectStatus($eloquentProject->status),
            new \DateTimeImmutable($eloquentProject->created_at),
            new \DateTimeImmutable($eloquentProject->updated_at)
        );
    }

    private function mapToEloquent(EloquentProject $eloquentProject, Project $project): void
    {
        $eloquentProject->id = $project->id()->value();
        $eloquentProject->title = $project->title()->value();
        $eloquentProject->description = $project->description()->value();
        $eloquentProject->images = $project->images()->value();
        $eloquentProject->github_url = $project->githubUrl()->value();
        $eloquentProject->demo_link = $project->demoLink()->value();
        $eloquentProject->technologies = $project->technologies()->value();
        $eloquentProject->status = $project->status()->value();
    }
}

