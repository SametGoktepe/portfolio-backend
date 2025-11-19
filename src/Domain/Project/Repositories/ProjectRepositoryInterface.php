<?php

namespace Src\Domain\Project\Repositories;

use Src\Domain\Project\Models\Project;
use Src\Domain\Project\Models\ProjectId;

interface ProjectRepositoryInterface
{
    public function find(ProjectId $id): ?Project;
    
    public function findAll(): array;
    
    public function paginate(int $perPage = 15, ?string $status = null): array;
    
    public function findByStatus(string $status): array;
    
    public function save(Project $project): void;
    
    public function update(Project $project): void;
    
    public function delete(ProjectId $id): void;
    
    public function exists(ProjectId $id): bool;
}

