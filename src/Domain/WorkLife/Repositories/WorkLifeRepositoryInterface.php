<?php

namespace Src\Domain\WorkLife\Repositories;

use Src\Domain\WorkLife\Models\WorkLife;
use Src\Domain\WorkLife\Models\WorkLifeId;

interface WorkLifeRepositoryInterface
{
    public function find(WorkLifeId $id): ?WorkLife;
    
    public function findAll(): array;
    
    public function findOngoing(): array;
    
    public function save(WorkLife $workLife): void;
    
    public function update(WorkLife $workLife): void;
    
    public function delete(WorkLifeId $id): void;
    
    public function exists(WorkLifeId $id): bool;
}

